<?php
namespace Tests\Classes\Mapper;

use PHPUnit\Framework\TestCase;
use Classes\Mapper;
use AspectMock\Test as test;
use Tests\Classes as Base;

class PushMapperTest extends Base\BaseTestCase
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
    public function testgetPushInfo(){

        /**
         * // 当日イベントが開催 return false
         */
        // mock
        $mock1 = test::double('\Classes\Mapper\PushMapper', ['isEventToday' => true]); 

        $object = new Mapper\PushMapper($this->container['db']);
        $result = $object->getPushInfo();

        $this->assertFalse($result);

        test::clean();
        /**
         * 開催7日前
         */

        // mock
        $mock1 = test::double('\Classes\Mapper\PushMapper', ['isEventToday' => false]); 
        $mock2 = test::double('\Classes\Mapper\PushMapper', ['isBeforeDaysInfo' => function($arg){
            if($arg===7){
                return array('event_id'=>'7');
            }else if($arg===1){
                return array('event_id'=>'1');
            }
        }]); 
        $mock3 = test::double('\Classes\Mapper\PushMapper', ['setDaysFlag' => function($arg0,$arg1){
            if($arg0==='7'&&$arg1===7){
                return true;
            }else{
                throw exception;
            }
        }]); 

        $object = new Mapper\PushMapper($this->container['db']);
        $result = $object->getPushInfo();

        $resultTest = array(
            'event_id' => '7',
            'day'=>7
        );

        $this->assertEquals($result,$resultTest);

        /**
         * 開催1日前
         */
        $mock2 = test::double('\Classes\Mapper\PushMapper', ['setDaysFlag' => function($arg0,$arg1){
            if($arg0==='1'&&$arg1===1){
                return true;
            }else{
                throw exception;
            }
        }]); 
        $mock3 = test::double('\Classes\Mapper\PushMapper', ['isBeforeDaysInfo' => function($arg){
            if($arg===1){
                return array('event_id'=>'1');
            }
        }]); 

        $object = new Mapper\PushMapper($this->container['db']);
        $result = $object->getPushInfo();

        $resultTest = array(
            'event_id' => '1',
            'day'=>1
        );

        $this->assertEquals($result,$resultTest);

        test::clean();

        // どちらも該当しない
        $mock1 = test::double('\Classes\Mapper\PushMapper', ['isEventToday' => false]); 
        $mock2 = test::double('\Classes\Mapper\PushMapper', ['isBeforeDaysInfo' => false]); 

        $object = new Mapper\PushMapper($this->container['db']);
        $result = $object->getPushInfo();

        $this->assertFalse($result);
    }

    /**
     * @group mapper
     */
    public function testisBeforeDaysInfo(){

        // データ削除
        $this->container['db']->query(
            "DELETE FROM `event` WHERE 1"
        );

        // 7 false
        $object = new Mapper\PushMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('isBeforeDaysInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,7);

        $this->assertFalse($res);

        // 1 false
        $object = new Mapper\PushMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('isBeforeDaysInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,1);

        $this->assertFalse($res);

        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (null,'event_id1','バドミントン１面1','なんとか公園1',DATE_ADD(CURRENT_DATE(),INTERVAL 6 DAY),'11:11:11','22:22:22',501,0,0,CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (null,'event_id2','バドミントン１面2','なんとか公園2',DATE_ADD(CURRENT_DATE(),INTERVAL 7 DAY),'11:11:12','22:22:22',502,0,0,CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (null,'event_id3','バドミントン１面3','なんとか公園3',DATE_ADD(CURRENT_DATE(),INTERVAL 5 DAY),'11:11:13','22:22:23',503,0,0,CURRENT_DATE(),CURRENT_DATE())"
        );

        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (null,'event_id4','バドミントン１面4','なんとか公園4',DATE_ADD(CURRENT_DATE(),INTERVAL 1 DAY),'11:11:14','22:22:24',504,0,0,CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (null,'event_id5','バドミントン１面5','なんとか公園5',CURRENT_DATE(),'11:11:15','22:22:25',505,0,0,CURRENT_DATE(),CURRENT_DATE())"
        );

        // 7 array
        $object = new Mapper\PushMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('isBeforeDaysInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,7);

        $resultTest = array(
            'event_id'=>"event_id1",
            'title'=>"バドミントン１面1",
            'place'=>"なんとか公園1",
            'event_date'=>$res['event_date'],
            'start_time'=>"11:11",
            'end_time'=>"22:22",
            'fee'=>"501",
        );

        $this->assertEquals($resultTest,$res);
        
        // 1 array
        $object = new Mapper\PushMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('isBeforeDaysInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,1);

        $resultTest = array(
            'event_id'=>"event_id4",
            'title'=>"バドミントン１面4",
            'place'=>"なんとか公園4",
            'event_date'=>$res['event_date'],
            'start_time'=>"11:11",
            'end_time'=>"22:22",
            'fee'=>"504",
        );

        $this->assertEquals($resultTest,$res);
    }

    /**
     * @group mapper
     */
    public function testisEventToday(){

        /**
         * false
         */

        // データ削除
        $this->container['db']->query(
            "DELETE FROM `event` WHERE 1"
        );

        // メソッド実行
        $object = new Mapper\PushMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('isEventToday');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object);

        $this->assertFalse($res);

        /**
         * true
         */

        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (null,'event_id','バドミントン１面','なんとか公園',CURRENT_DATE(),'11:11:11','22:22:22',501,0,0,CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Mapper\PushMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('isEventToday');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object);

        $this->assertTrue($res);

    }

    /**
     * @group mapper
     */
    public function testsetDaysFlag(){

        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (null,'event_id','バドミントン１面','なんとか公園',CURRENT_DATE(),'11:11:11','22:22:22',501,0,0,CURRENT_DATE(),CURRENT_DATE())"
        );

        // 7
        $object = new Mapper\PushMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('setDaysFlag');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,'event_id',7);

        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'event_id'");
        $ticket = $stmt->fetch();

        $this->assertEquals('バドミントン１面',$ticket['title']);
        $this->assertEquals('なんとか公園',$ticket['place']);
        $this->assertEquals('11:11:11',$ticket['start_time']);
        $this->assertEquals('22:22:22',$ticket['end_time']);
        $this->assertEquals('501',$ticket['fee']);
        $this->assertEquals('1',$ticket['before_seven_days']);
        $this->assertEquals('0',$ticket['before_one_day']);

        // 1
        $object = new Mapper\PushMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('setDaysFlag');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,'event_id',1);

        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'event_id'");
        $ticket = $stmt->fetch();

        $this->assertEquals('バドミントン１面',$ticket['title']);
        $this->assertEquals('なんとか公園',$ticket['place']);
        $this->assertEquals('11:11:11',$ticket['start_time']);
        $this->assertEquals('22:22:22',$ticket['end_time']);
        $this->assertEquals('501',$ticket['fee']);
        $this->assertEquals('1',$ticket['before_seven_days']);
        $this->assertEquals('1',$ticket['before_one_day']);
    }
}