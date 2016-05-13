<?php

namespace Hikouki\Stigma;

use PDO;
use Exception;

class Database extends PDO
{
    /**
     * Singleton instance
     * @param $databaseURI Database uri.
     */
    private static $instance;

    /**
     * Load database file.
     * Create singleton instance.
     * @param $databaseURI Database uri.
     * @return void
     */
    public static function load($databaseURI)
    {
        if (!static::$instance) {
            static::$instance = static::createInstance($databaseURI);
            static::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            static::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
    }

    /**
     * Create this class instance.
     * @param $databaseURI Database uri.
     * @throws Exception
     * @return object
     */
    private static function createInstance($databaseURI)
    {
        preg_match("/^([^:]+):.+/", $databaseURI, $matches);
        $scheme = $matches[1];
        switch ($scheme) {
            case 'sqlite':
                return new static($databaseURI);
            case 'mysql':
                preg_match("/username=([^;]+)/", $databaseURI, $username);
                preg_match("/password=([^;]+)/", $databaseURI, $password);
                return new static($databaseURI, $username[1], $password[1]);
            default:
                throw new Exception('Sorry, '.$scheme.' database isn\'t supported.');
        }
    }

    /**
     * Unload singleton instance.
     * @param $databaseURI Database uri.
     * @return void
     */
    public static function unload()
    {
        static::$instance = null;
    }

    /**
     * Recreate singleton instance.
     * @param $databaseURI Database uri.
     * @return void
     */
    public static function reload($databaseURI)
    {
        static::unload();
        static::load($databaseURI);
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
