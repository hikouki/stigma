<?php

namespace Hikouki\Stigma;

use \PDO;

class Database extends PDO
{
    /**
     * Singleton instance
     * @param $databaseFilePath Database file path.
     */
    private static $instance;

    /**
     * Load database file.
     * Create singleton instance.
     * @param $databaseFilePath Database file path.
     * @return void
     */
    public static function load($databaseFilePath)
    {
        if (!self::$instance) {
            self::$instance = new self('sqlite:'.$databaseFilePath);//
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
    }


    /**
     * Get Detabase singleton instance.
     * @return Database Database instance.
     */
    public static function getInstance()
    {
        return self::$instance;
    }
}
