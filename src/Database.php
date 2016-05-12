<?php

namespace Hikouki\Stigma;

use PDO;

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
        if (!static::$instance) {
            static::$instance = new static('sqlite:'.$databaseFilePath);
            static::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            static::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
    }

    /**
     * Recreate singleton instance.
     * @param $databaseFilePath Database file path.
     * @return void
     */
    public static function reload($databaseFilePath)
    {
        static::$instance = null;
        static::load($databaseFilePath);
    }

    /**
     * Get Detabase singleton instance.
     * @return Database Database instance.
     */
    public static function getInstance()
    {
        return static::$instance;
    }
}
