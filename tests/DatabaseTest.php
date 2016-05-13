<?php

namespace Hikouki\Stigma\PHPUnit;

use PHPUnit_Framework_TestCase;
use Hikouki\Stigma\Database;
use Closure;
use Exception;

class DatabaseTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        copy(__DIR__.'/resources/database.sqlite', __DIR__.'/resources/database.sqlite.test');
        Database::reload('sqlite:'.__DIR__.'/resources/database.sqlite.test');
    }

    public static function tearDownAfterClass()
    {
        unlink(__DIR__.'/resources/database.sqlite.test');
    }

    public function setUp()
    {
        Database::unload();
    }

    public function testLoadSQLite()
    {
        Database::load('sqlite:'.__DIR__.'/resources/database.sqlite.test');
        $tmp = Database::getInstance();
        Database::load('sqlite:'.__DIR__.'/resources/database.sqlite.test');
        $this->assertNotNull(Database::getInstance());
        $this->assertSame(Database::getInstance(), $tmp);
    }

    public function testLoadNotSupported()
    {
        try {
            Database::load('something:'.__DIR__.'/resources/database.sqlite.test');
            $this->fail('Not throw expcetion.');
        } catch (Exception $e) {
            $this->assertNull(Database::getInstance());
            $this->assertSame('Sorry, something database isn\'t supported.', $e->getMessage());
        }
    }

    public function testUnload()
    {
        Database::load('sqlite:'.__DIR__.'/resources/database.sqlite.test');
        Database::unload('sqlite:'.__DIR__.'/resources/database.sqlite.test');
        $this->assertNull(Database::getInstance());
    }

    public function testReLoad()
    {
        Database::load('sqlite:'.__DIR__.'/resources/database.sqlite.test');
        $tmp = Database::getInstance();
        Database::reload('sqlite:'.__DIR__.'/resources/database.sqlite.test');
        $this->assertNotSame(Database::getInstance(), $tmp);
    }

    public function testGetInstance()
    {
        Closure::bind(function () {
            Database::load('sqlite:'.__DIR__.'/resources/database.sqlite.test');
            $this->assertNotNull(Database::getInstance());
            $this->assertSame(Database::$instance, Database::getInstance());
        }, $this, '\Hikouki\Stigma\Database')->__invoke();
    }
}
