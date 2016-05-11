<?php

namespace Hikouki\Stigma\PHPUnit;

use \PHPUnit_Framework_TestCase;
use Hikouki\Stigma\Replacer;
use JBZoo\Utils\Ser;

class ReplacerTest extends PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        // string
        $stringObject = 'wwwcoinwww';
        $stringObjectRet = Replacer::execute($stringObject, 'coin', 'steak');
        $this->assertEquals($stringObjectRet, true);
        $this->assertEquals($stringObject, 'wwwsteakwww');

        // serialize string
        $serializeObject = 'a:2:{s:7:"company";s:7:"hikouki";s:7:"website";s:26:"https://github.com/hikouki";}';
        $serializeObjectRet = Replacer::execute($serializeObject, 'github.com', 'qiita.com');
        $this->assertEquals($serializeObjectRet, true);
        $this->assertEquals(
            $serializeObject,
            Ser::maybe(['company' => 'hikouki', 'website' => 'https://qiita.com/hikouki'])
        );
    }
}
