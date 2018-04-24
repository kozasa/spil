<?php
namespace Tests\Classes\Mapper;

use PHPUnit\Framework\TestCase;
use Classes\Mapper;
use AspectMock\Test as test;
use Tests\Classes as Base;

class NewPostMapperTest extends Base\BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->container['db']->beginTransaction();
    }
    protected function tearDown()
    {
        parent::tearDown();
        $this->container['db']->rollback(); // 元に戻す

        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group mapper
     */
    public function testinsertNewRegistant(){

        // mock
        $mock1 = test::double('\Classes\Mapper\NewPostMapper', ['checkInfo' => function($arg){
            if($arg===false){
                return false;
            }else{
                return true;
            }
        }]); 

        // メソッド実行
        // false
        $object = new Mapper\NewPostMapper($this->container['db']);
        $result = $object->insertNewRegistant(false);

        $this->assertFalse($result);

        // true
        $data = array(
            'name' => 'xxx',
            'gender' => '1',
            'age' => '2',
            'join_day' => 't111',
        );
        $object = new Mapper\NewPostMapper($this->container['db']);
        $result = $object->insertNewRegistant($data);

        $stmt = $this->container['db']->query("SELECT * FROM `event_participants` WHERE event_id = 't111'");
        $ticket = $stmt->fetch();

        $resultTest = array(
            'id' => $ticket['id'],
            'event_id' => 't111',
            'member_id' => ' ',
            'join_flag' => '1',
            'new_flag' => '1',
            'new_name' => 'xxx',
            'new_gender' => '1',
            'new_age' => '2',
            'created_at' => $ticket['created_at'],
            'updated_at' => $ticket['updated_at'],
        );
        $this->assertEquals($ticket,$resultTest);
    }

    /**
     * @group mapper
     */
    public function testcheckInfo(){

        // 引数準備
        $argTrue = array(
            'name' => 'xxx',
            'gender' => 'xxx',
            'age' => 'xxx',
            'join_day' => 'xxx',
        );
        $argFalse1 = array(
            'name' => '',
            'gender' => 'xxx',
            'age' => 'xxx',
            'join_day' => 'xxx',
        );
        $argFalse2 = array(
            'name' => 'xxx',
            'gender' => '',
            'age' => 'xxx',
            'join_day' => 'xxx',
        );
        $argFalse3 = array(
            'name' => 'xxx',
            'gender' => 'xxx',
            'age' => '',
            'join_day' => 'xxx',
        );
        $argFalse4 = array(
            'name' => 'xxx',
            'gender' => 'xxx',
            'age' => 'xxx',
            'join_day' => '',
        );
        $argFalse5 = array(
            'name' => null,
            'gender' => null,
            'age' => null,
            'join_day' => null,
        );

        // メソッド実行

        // true
        $object = new Mapper\NewPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$argTrue);

        $this->assertTrue($res);

        // false
        $object = new Mapper\NewPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$argFalse1);

        $this->assertFalse($res);

        // false
        $object = new Mapper\NewPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$argFalse2);

        $this->assertFalse($res);

        // false
        $object = new Mapper\NewPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$argFalse3);

        $this->assertFalse($res);

        // false
        $object = new Mapper\NewPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$argFalse4);

        $this->assertFalse($res);

        // false
        $object = new Mapper\NewPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$argFalse5);

        $this->assertFalse($res);

    }
}