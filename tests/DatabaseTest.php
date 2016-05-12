<?php

namespace Hikouki\Stigma\PHPUnit;

use PHPUnit_Framework_TestCase;
use Hikouki\Stigma\Database;
use Closure;

class DatabaseTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        copy(__DIR__.'/resources/database.sqlite', __DIR__.'/resources/database.sqlite.test');
        Database::reload(__DIR__.'/resources/database.sqlite.test');
    }

    public static function tearDownAfterClass()
    {
        unlink(__DIR__.'/resources/database.sqlite.test');
    }

    public function testLoad()
    {
        Closure::bind(function () {
            Database::$instance = null;
            Database::load(__DIR__.'/resources/database.sqlite.test');
            $tmp = Database::$instance;
            Database::load(__DIR__.'/resources/database.sqlite.test');
            $this->assertNotNull(Database::$instance);
            $this->assertSame(Database::$instance, $tmp);
        }, $this, '\Hikouki\Stigma\Database')->__invoke();
    }

    public function testReLoad()
    {
        Closure::bind(function () {
            Database::$instance = null;
            Database::load(__DIR__.'/resources/database.sqlite.test');
            $tmp = Database::$instance;
            Database::reload(__DIR__.'/resources/database.sqlite.test');
            $this->assertNotSame(Database::$instance, $tmp);
        }, $this, '\Hikouki\Stigma\Database')->__invoke();
    }

    public function testGetInstance()
    {
        Closure::bind(function () {
            $this->assertNotNull(Database::$instance, Database::getInstance());
        }, $this, '\Hikouki\Stigma\Database')->__invoke();
    }
}
