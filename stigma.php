<?php

require 'vendor/autoload.php';

use JBZoo\Utils\Ser;

if (count($argv) <= 1 || count($argv) >= 5) {
    // usage
    echo <<<EOF

Usage:
  php replace.php <database_file_path> <target> <replace>

Example:
  php replace.php ./ht.sqlite localhost 160.122.111.11

EOF;
    exit(1);
} else {
    @list(, $database_file_path, $target, $replace) = $argv;
}

try {
    $pdo = new PDO('sqlite:'.$database_file_path);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $select_all_tables_query = $pdo->query("SELECT name FROM sqlite_master WHERE type = 'table'");
    $tables = $select_all_tables_query->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tables as $table) {
        $select_all_query = $pdo->query("SELECT * FROM ".$table);
        $rows = $select_all_query->fetchAll();

        foreach ($rows as &$row) {
            $replaced = false;
            foreach ($row as &$column) {
                if (replacer($column, $target, $replace) && !$replaced) {
                    $replaced = true;
                }
            }

            if ($replaced) {
                $fields = array_keys($row);
                $values = array_values($row);
                $pressholders = str_repeat("?,", count($fields)-1);
                $insert_sql = "INSERT INTO ".$table."(".implode(',', $fields).") VALUES (".$pressholders."?)";
                $delete_sql = "DELETE FROM ".$table." WHERE ".current($fields)." = ?";

                $pdo->prepare($delete_sql)->execute([current($values)]);
                $pdo->prepare($insert_sql)->execute($values);
            }
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

function replacer(&$object, $target, $replace)
{
    $plain_object = Ser::maybeUn($object);

    if (is_array($plain_object)) {
        $replaced = false;

        foreach ($plain_object as &$value) {
            if (replacer($value, $target, $replace) && !$replaced) {
                $replaced = true;
            }
        }

        $object = Ser::maybe($plain_object);
        return $replaced;
    } else {
        if (strpos($object, $target)) {
            $object = str_replace($target, $replace, $object);
            return true;
        }

        return false;
    }
}
