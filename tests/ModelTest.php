<?php

namespace Hikouki\Stigma\PHPUnit;

use PHPUnit_Framework_TestCase;
use Hikouki\Stigma\Model;
use Hikouki\Stigma\Database;

class ModelTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        copy(__DIR__.'/resources/model.sqlite', __DIR__.'/resources/model.sqlite.test');
        Database::reload('sqlite:'.__DIR__.'/resources/model.sqlite.test');
    }

    public static function tearDownAfterClass()
    {
        unlink(__DIR__.'/resources/model.sqlite.test');
    }

    public function testFindAllTableStructure()
    {
        $tables = Model::findAllTableStructure();
        $this->assertSame($tables, ['robots', 'users']);
    }

    public function testFindAll()
    {
        $rows = Model::findAll("users");
        $this->assertSame($rows, [
            [
                'id' => '1',
                'name' => 'hikouki',
            ],
            [
                'id' => '2',
                'name' => 'bob',
            ],
            [
                'id' => '3',
                'name' => 'mikel',
            ],
            [
                'id' => '4',
                'name' => 'jon',
            ],
        ]);
    }

    public function testUpdateRow()
    {
        Model::updateRow(['id' => 1, 'name' => 'stigma'], 'users');
        $rows = Model::findAll("users");
        $this->assertSame($rows, [
            [
                'id' => '1',
                'name' => 'stigma',
            ],
            [
                'id' => '2',
                'name' => 'bob',
            ],
            [
                'id' => '3',
                'name' => 'mikel',
            ],
            [
                'id' => '4',
                'name' => 'jon',
            ],
        ]);
    }
}
