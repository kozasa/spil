<?php
namespace Tests\Classes\Utility;

use PHPUnit\Framework\TestCase;
use Classes\Utility;

class NewRegisterEnumTest extends TestCase
{
    public function testgetGender()
    {
        $this->assertEquals("男性",Utility\NewRegisterEnum::getGender(1));
        $this->assertEquals("女性",Utility\NewRegisterEnum::getGender(2));
    }
}