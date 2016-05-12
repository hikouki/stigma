<?php

namespace Hikouki\Stigma;

use PDO;
use Exception;

class App
{
    /**
     * Execute.
     * @param $databaseFilePath Database file path.
     * @param $target replace target string.
     * @param $replace replaced string.
     * @return void
     */
    public function execute($databaseFilePath, $target, $replace)
    {
        try {
            Database::load($databaseFilePath);

            $tables = Model::findAllTableStructure();

            foreach ($tables as $table) {
                $rows = Model::findAll($table);
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
}
