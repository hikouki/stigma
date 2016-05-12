<?php

namespace Hikouki\Stigma;

use PDO;
use Exception;

class Model
{
    /**
     * Find all table structure.
     * @return array
     */
    public static function findAllTableStructure()
    {
        return Database::getInstance()->query("SELECT name FROM sqlite_master WHERE type = 'table' ORDER BY name")
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Find all data in table.
     * @param string $table Table name.
     * @return array
     */
    public static function findAll($table)
    {
        return Database::getInstance()->query("SELECT * FROM ".$table." ORDER BY 1")->fetchAll();
    }


    /**
     * Delete and Insert database row.
     * @param $row array Row in table.
     * @param $table string Table name.
     * @return void
     */
    public static function updateRow($row, $table)
    {
        $fields = array_keys($row);
        $values = array_values($row);
        $pressholders = str_repeat("?,", count($fields)-1);
        $insertSQL = "INSERT INTO ".$table."(".implode(',', $fields).") VALUES (".$pressholders."?)";
        $deleteSQL = "DELETE FROM ".$table." WHERE ".current($fields)." = ?";

        $db = Database::getInstance();
        $db->prepare($deleteSQL)->execute([current($values)]);
        $db->prepare($insertSQL)->execute($values);

        echo '→ MODIFY: ('.$table.') '.implode(', ', $values)."\n";
    }
}
