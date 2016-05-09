<?php

namespace Hikouki\Stigma;

class Database extends \PDO
{
    /**
     * Singleton instance
     */
    private static $instance;

    /**
     * Get Detabase singleton instance.
     * @return Database Database instance.
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self('sqlite:'.$database_file_path);
            self::$instance->setAttribute(DB::ATTR_ERRMODE, DB::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(DB::ATTR_DEFAULT_FETCH_MODE, DB::FETCH_ASSOC);
        }
        return self::$instance;
    }
}
