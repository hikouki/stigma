<?php

namespace Hikouki\Stigma\PHPUnit;

use PHPUnit_Framework_TestCase;
use Hikouki\Stigma\App;
use Hikouki\Stigma\Model;
use Hikouki\Stigma\Database;

class AppTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        copy(__DIR__.'/resources/app.sqlite', __DIR__.'/resources/app.sqlite.test');
    }

    public static function tearDownAfterClass()
    {
        unlink(__DIR__.'/resources/app.sqlite.test');
    }

    public function setUp()
    {
        Database::unload();
    }

    public function testExecute()
    {
        $app = new App;
        $app->execute('sqlite:'.__DIR__.'/resources/app.sqlite.test', 'github', 'qiita');

        $users = Model::findAll('users');
        $usermeta = Model::findAll('usermeta');

        $this->assertSame($users, [
            [
                'id' => '1',
                'name' => 'hikouki',
                'website' => 'https://qiita.com/hikouki'
            ],
            [
                'id' => '2',
                'name' => 'tarou',
                'website' => 'https://qiita.com/tarou'
            ],
            [
                'id' => '3',
                'name' => 'bob',
                'website' => 'https://qiita.com/bob'
            ],
            [
                'id' => '4',
                'name' => 'jon',
                'website' => 'https://googlee.com/jon'
            ],
        ]);

        $this->assertSame($usermeta, [
            [
                'id' => '1',
                'meta' => 'a:2:{s:3:"pet";s:3:"cat";s:3:"app";s:9:"qiita.com";}'
            ],
            [
                'id' => '2',
                'meta' => 'a:2:{s:3:"pet";s:3:"dog";s:3:"app";s:9:"qiita.com";}'
            ],
            [
                'id' => '3',
                'meta' => 'a:2:{s:3:"pet";s:4:"bird";s:3:"app";s:10:"google.com";}'
            ]
        ]);
    }

    public function test2002Exception()
    {
        $this->expectOutputString('No such file or database server.');

        $app = new App;
        $app->execute('mysql:host=localhost;dbname=testdb;username=test;password=pw', 'github', 'qiita');
    }

    public function testDefaultException()
    {
        $this->expectOutputString('Sorry, something database isn\'t supported.');

        $app = new App;
        $app->execute('something:'.__DIR__.'/resources/app.sqlite.test', 'github', 'qiita');
    }
}
