<?php

namespace Hikouki\Stigma\PHPUnit;

use PHPUnit_Framework_TestCase;
use Hikouki\Stigma\App;
use Closure;

class AppTest extends PHPUnit_Framework_TestCase
{
    public function testReplaceIfHit()
    {
        Closure::bind(function () {
            $row = [
                "id" => "1",
                "name" => "hikouki",
            ];
            $app = new App();
            $ret = $app->replaceIfHit($row, 'hikouki', 'stigma');

            $this->assertEquals($ret, true);
            $this->assertEquals($row, [
                "id" => "1",
                "name" => "stigma",
            ]);
        }, $this, '\Hikouki\Stigma\App')->__invoke();
    }
}
