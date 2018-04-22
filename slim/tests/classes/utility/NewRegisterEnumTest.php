<?php
namespace Tests\Classes\Utility;

use PHPUnit\Framework\TestCase;
use Classes\Utility;

class NewRegisterEnumTest extends TestCase
{
    public function testgetGender(){
        $this->assertEquals("男性",Utility\NewRegisterEnum::getGender(1));
        $this->assertEquals("女性",Utility\NewRegisterEnum::getGender(2));
        $this->assertEquals(null,Utility\NewRegisterEnum::getGender(3));
    }

    public function testgetAge(){
        $this->assertEquals("１０代",Utility\NewRegisterEnum::getAge(1));
        $this->assertEquals("２０代",Utility\NewRegisterEnum::getAge(2));
        $this->assertEquals("３０代",Utility\NewRegisterEnum::getAge(3));
        $this->assertEquals("４０代",Utility\NewRegisterEnum::getAge(4));
        $this->assertEquals("５０代",Utility\NewRegisterEnum::getAge(5));
        $this->assertEquals("６０代",Utility\NewRegisterEnum::getAge(6));
        $this->assertEquals(null,Utility\NewRegisterEnum::getAge(7));
    }

    public function testgetImage(){
        $this->assertEquals("/img/man.png",Utility\NewRegisterEnum::getImage(1));
        $this->assertEquals("/img/woman.png",Utility\NewRegisterEnum::getImage(2));
        $this->assertEquals(null,Utility\NewRegisterEnum::getImage(3));
    }
}