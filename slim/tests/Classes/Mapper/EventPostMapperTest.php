<?php
namespace Tests\Classes\Mapper;

use PHPUnit\Framework\TestCase;
use Classes\Mapper;
use AspectMock\Test as test;
use Tests\Classes as Base;

class EventPostMapperTest extends Base\BaseTestCase
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
    public function testinsertEventPost(){

        // mock
        $mock1 = test::double('\Classes\Mapper\EventPostMapper', ['checkInfo' => function($arg){
            if($arg===false){
                return false;
            }else{
                return true;
            }
        }]); 
        $mock2 = test::double('\Classes\Mapper\EventPostMapper', ['getMaxId' => 999]); 

        // メソッド実行
        // false
        $object = new Mapper\EventPostMapper($this->container['db']);
        $result = $object->insertEventPost(false);

        $this->assertFalse($result);

        // true
        $info = array(
            'title' => 'テスト１２３',
            'place' => '〜〜公園',
            'event_date' => '2018-11-11',
            'start_time' => '12:33:33',
            'end_time' => '13:44:44',
            'fee' => '900',
        );
        $object = new Mapper\EventPostMapper($this->container['db']);
        $result = $object->insertEventPost($info);

        $this->assertEquals($result,'b001000');

        // 検索
        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'b001000'");
        $ticket = $stmt->fetch();

        $resultTest = array(
            'event_id' => 'b001000',
            'title' => 'テスト１２３',
            'place' => '〜〜公園',
            'event_date' => '2018-11-11',
            'start_time' => '12:33:33',
            'end_time' => '13:44:44',
            'fee' => '900',
            'before_seven_days' => '0',
            'before_one_day' => '0',
            'id' => $ticket['id'],
            'created_at' => $ticket['created_at'],
            'updated_at' => $ticket['updated_at'],
        );

        $this->assertEquals($resultTest,$ticket);

    }

    /**
     * @group mapper
     */
    public function testgetMaxId(){

        // テストデータ挿入
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (99999,'','','',CURRENT_DATE(),'11:11:11','22:22:22',100,0,0,CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Mapper\EventPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('getMaxId');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object);

        $this->assertEquals($res,99999);
    }

    /**
     * @group mapper
     */
    public function testcheckInfo(){
        // 前準備
        $info = array(
            'fee' => 'xxx',
            'title' => 'xxx',
            'place' => 'xxx',
            'event_date' => 'xxx',
            'start_time' => 'xxx',
            'end_time' => 'xxx',
        );
        $infoFalse1 = array(
            'fee' => '',
            'title' => 'xxx',
            'place' => 'xxx',
            'event_date' => 'xxx',
            'start_time' => 'xxx',
            'end_time' => 'xxx',
        );
        $infoFalse2 = array(
            'fee' => 'xxx',
            'title' => '',
            'place' => 'xxx',
            'event_date' => 'xxx',
            'start_time' => 'xxx',
            'end_time' => 'xxx',
        );
        $infoFalse3 = array(
            'fee' => 'xxx',
            'title' => 'xxx',
            'place' => '',
            'event_date' => 'xxx',
            'start_time' => 'xxx',
            'end_time' => 'xxx',
        );
        $infoFalse4 = array(
            'fee' => 'xxx',
            'title' => 'xxx',
            'place' => 'xxx',
            'event_date' => '',
            'start_time' => 'xxx',
            'end_time' => 'xxx',
        );
        $infoFalse5 = array(
            'fee' => 'xxx',
            'title' => 'xxx',
            'place' => 'xxx',
            'event_date' => 'xxx',
            'start_time' => '',
            'end_time' => 'xxx',
        );
        $infoFalse6 = array(
            'fee' => 'xxx',
            'title' => 'xxx',
            'place' => 'xxx',
            'event_date' => 'xxx',
            'start_time' => 'xxx',
            'end_time' => '',
        );
        $infoFalse7 = array(
            'fee' => null,
            'title' => null,
            'place' => null,
            'event_date' => null,
            'start_time' => null,
            'end_time' => null,
        );

        // true
        $object = new Mapper\EventPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$info);

        $this->assertTrue($res);

        // false1
        $object = new Mapper\EventPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$infoFalse1);

        $this->assertFalse($res);

        // false2
        $object = new Mapper\EventPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$infoFalse2);

        $this->assertFalse($res);

        // false3
        $object = new Mapper\EventPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$infoFalse3);

        $this->assertFalse($res);

        // false4
        $object = new Mapper\EventPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$infoFalse4);

        $this->assertFalse($res);

        // false5
        $object = new Mapper\EventPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$infoFalse5);

        $this->assertFalse($res);

        // false6
        $object = new Mapper\EventPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$infoFalse6);

        $this->assertFalse($res);

        // false7
        $object = new Mapper\EventPostMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$infoFalse7);

        $this->assertFalse($res);

    }
}