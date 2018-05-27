<?php
namespace Tests\Classes\Mapper;

use PHPUnit\Framework\TestCase;
use Classes\Mapper;
use AspectMock\Test as test;
use Tests\Classes as Base;

class EventEditMapperTest extends Base\BaseTestCase
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

    private $week = [
        '日', //0
        '月', //1
        '火', //2
        '水', //3
        '木', //4
        '金', //5
        '土', //6
    ];

    /**
     * @group mapper
     */
    public function testgetEventList(){

        // テストデータ作成
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (null,'event_id','バドミントン１面','なんとか公園',CURRENT_DATE(),'11:11:11','22:22:22',501,0,0,CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (null,'event_id','バドミントン２面','なんとか公園あ',CURRENT_DATE()+1,'11:11:22','33:22:22',601,1,1,CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Mapper\EventEditMapper($this->container['db']);
        $result = $object->getEventList();

        // 検証
        $stmt = $this->container['db']->query('SELECT * FROM `event` WHERE event_date >= CURRENT_DATE() order by event_date');
        $year_list = array();
        $month_list = array();
        
        #イベント情報取得
        while($row = $stmt -> fetch()){
            $year = date('Y年' ,strtotime($row['event_date']));
            $month = date('n月' ,strtotime($row['event_date']));
            $weekday = date('w',strtotime($row['event_date']));
            
            #年がリストに存在しなければリスト作成、月のリストを初期化
            if(!array_key_exists($year, $year_list)){
                array_merge($year_list,array($year => array()));
                $month_list = array();
            }
            #月がリストに存在しなければ、リスト作成、イベントリストを初期化
            if(!array_key_exists($month, $month_list)){
                array_merge($month_list,array($month => array()));
                $event_list = array();
            }
            $event_info = array(
                'event_id' => $row['event_id'],
                'title' => $row['title'],
                'place' => $row['place'],
                'date' => date('j' ,strtotime($row['event_date'])),
                'week' => $this->week[$weekday],
                'start_time' => date('H:i' ,strtotime($row['start_time'])),
                'end_time' => date('H:i' ,strtotime($row['end_time'])),
            );
            array_push($event_list,$event_info);
            $month_list[$month] = $event_list;
            $year_list[$year] = $month_list;
        }

        $this->assertEquals($year_list,$result);
    }

    /**
     * @group mapper
     */
    public function testgetEventFromId(){
        
        // テストデータ作成
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (null,'x000001','バドミントン１面','なんとか公園','2200-11-27','11:11:11','22:22:22',501,0,0,'2200-11-27','2200-11-27')"
        );

        // メソッド実行
        $object = new Mapper\EventEditMapper($this->container['db']);
        $result = $object->getEventFromId('x000001');

        // 検証
        $weekday = date('w',strtotime('2200-11-27'));
        $event = array(
            'event_id' => 'x000001',
            'title' => 'バドミントン１面',
            'place' => 'なんとか公園',
            'date' => '2200-11-27',
            'week' => $this->week[$weekday],
            'fee' => 501,
            'start_time' => date('H:i' ,strtotime('11:11:11')),
            'end_time' => date('H:i' ,strtotime('22:22:22')),
        );
        $this->assertEquals($event,$result);

    }

    /**
     * @group mapper
     */
    public function testupdateEvent(){

        // テストデータ作成
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (null,'x000001','バドミントン１面','なんとか公園','2200-11-27','11:11:11','22:22:22',501,0,0,'2200-11-27','2200-11-27')"
        );

        $info = array(
            'event_id' => 'x000001',
            'title' => 'バドミントン２面',
            'place' => 'なんとか体育館',
            'event_date' => '2300-12-23',
            'fee' => 500,
            'start_time' => date('H:i' ,strtotime('12:11:11')),
            'end_time' => date('H:i' ,strtotime('21:22:22')),
        );

        $mock1 = test::double('\Classes\Mapper\EventEditMapper', ['checkInfo' => true]); 

        // メソッド実行
        $object = new Mapper\EventEditMapper($this->container['db']);
        $result = $object->updateEvent($info);
        
        // 検証
        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'x000001'");
        $row = $stmt->fetch();
        $update_info = array(
            'event_id' => $row['event_id'],
            'title' => $row['title'],
            'place' => $row['place'],
            'event_date' => $row['event_date'],
            'fee' => $row['fee'],
            'start_time' => date('H:i' ,strtotime($row['start_time'])),
            'end_time' => date('H:i' ,strtotime($row['end_time'])),
        );
        
        $this->assertEquals($info,$update_info);

        //checkInfo失敗
        $mock1 = test::double('\Classes\Mapper\EventEditMapper', ['checkInfo' => false]);
        $result = $object->updateEvent($info);
        
        $this->assertFalse($result);

        
    }

    /**
     * @group mapper
     */
    public function testdeleteEvent(){

        // テストデータ作成
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (null,'x000001','バドミントン１面','なんとか公園','2200-11-27','11:11:11','22:22:22',501,0,0,'2200-11-27','2200-11-27')"
        );

        // メソッド実行
        $object = new Mapper\EventEditMapper($this->container['db']);
        $result = $object->deleteEvent('x000001');

        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'x000001' LIMIT 1");
        
        $row = $stmt->fetch();

        $this->assertFalse($row);
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
        $object = new Mapper\EventEditMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$info);

        $this->assertTrue($res);

        // false1
        $object = new Mapper\EventEditMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$infoFalse1);

        $this->assertFalse($res);

        // false2
        $object = new Mapper\EventEditMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$infoFalse2);

        $this->assertFalse($res);

        // false3
        $object = new Mapper\EventEditMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$infoFalse3);

        $this->assertFalse($res);

        // false4
        $object = new Mapper\EventEditMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$infoFalse4);

        $this->assertFalse($res);

        // false5
        $object = new Mapper\EventEditMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$infoFalse5);

        $this->assertFalse($res);

        // false6
        $object = new Mapper\EventEditMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$infoFalse6);

        $this->assertFalse($res);

        // false7
        $object = new Mapper\EventEditMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$infoFalse7);

        $this->assertFalse($res);

    }
}

