<?php

namespace Hikouki\Stigma;

use \PDO;

class Database extends PDO
{
    /**
     * Singleton instance
     * @param $database_file_path Database file path.
     */
    private static $instance;

    public static function load($database_file_path)
    {
        if (!self::$instance) {
            self::$instance = new self('sqlite:'.$database_file_path);
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
