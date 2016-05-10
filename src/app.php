<?php

namespace Hikouki\Stigma;

use \PDO;
use \Exception;

class App
{
    /**
     * Execute.
     * @param $database_file_path Database file path.
     * @param $target replace target string.
     * @param $replace replaced string.
     * @return void
     */
    public function execute($database_file_path, $target, $replace)
    {
        try {
            Database::load($database_file_path);

            $db = Database::getInstance();

            $tables = $db->query("SELECT name FROM sqlite_master WHERE type = 'table'")
                    ->fetchAll(PDO::FETCH_COLUMN);

            foreach ($tables as $table) {
                $rows = $db->query("SELECT * FROM ".$table)->fetchAll();
                foreach ($rows as &$row) {
                    if ($this->replaceIfHit($row, $target, $replace)) {
                        $this->updateRow($row, $table);
                    }
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Replace if hit.
     * @param $row array Row in table.
     * @param $target string Replace target string.
     * @param $replace string Replace string.
     * @return boolean
     */
    private function replaceIfHit(&$row, $target, $replace)
    {
        $replaced = false;
        foreach ($row as &$column) {
            if (Replacer::execute($column, $target, $replace) && !$replaced) {
                $replaced = true;
            }
        }

        return $replaced;
    }

    /**
     * Delete and Insert database row.
     * @param $row array Row in table.
     * @param $fields array Fields in table.
     * @param $table string Table name.
     * @return void
     */
    private function updateRow($row, $table)
    {
        $fields = array_keys($row);
        $values = array_values($row);
        $pressholders = str_repeat("?,", count($fields)-1);
        $insert_sql = "INSERT INTO ".$table."(".implode(',', $fields).") VALUES (".$pressholders."?)";
        $delete_sql = "DELETE FROM ".$table." WHERE ".current($fields)." = ?";

        $db = Database::getInstance();
        $db->prepare($delete_sql)->execute([current($values)]);
        $db->prepare($insert_sql)->execute($values);

        echo '→ MODIFY: ('.$table.') '.implode(', ', $values)."\n";
    }
}
