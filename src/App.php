<?php

namespace Hikouki\Stigma;

use PDO;
use Exception;

class App
{
    /**
     * Execute.
     * @param $databaseURI Database uri.
     * @param $target replace target string.
     * @param $replace replaced string.
     * @return void
     */
    public function execute($databaseURI, $target, $replace)
    {
        try {
            Database::load($databaseURI);

            $tables = Model::findAllTableStructure();

            foreach ($tables as $table) {
                $rows = Model::findAll($table);
                foreach ($rows as &$row) {
                    if ($this->replaceIfHit($row, $target, $replace)) {
                        Model::updateRow($row, $table);
                    }
                }
            }
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case '2002':
                    echo "No such file or database server.";
                    break;
                default:
                    echo $e->getMessage();
            }
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
