<?php
namespace Tests\Classes\Mapper;

use PHPUnit\Framework\TestCase;
use Classes\Mapper;
use AspectMock\Test as test;
use Tests\Classes as Base;

class EventMapperTest extends Base\BaseTestCase
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
    public function testgetEventInfo(){

        // mock
        $mock1 = test::double('\Classes\Mapper\EventMapper', ['isEvent' => function($arg){
            if($arg==='id_true'){
                return true;
            }else if($arg==='id_false'){
                return false;
            }else{
                throw exception;
            }
        }]); 
        $mock1 = test::double('\Classes\Mapper\EventMapper', ['isEventInfo' => function($arg){
            if($arg==='id_true'){
                return true;
            }else{
                throw exception;
            }
        }]); 

        // メソッド実行
        // true
        $object1 = new Mapper\EventMapper($this->container['db']);
        $result = $object1->getEventInfo('id_true');

        $this->assertTrue($result);

        // false
        $object2 = new Mapper\EventMapper($this->container['db']);
        $result = $object2->getEventInfo('id_false');

        $this->assertFalse($result);
    }

    /**
     * @group mapper
     */
    public function testisEventInfo(){

        // mock
        $mock1 = test::double('\Classes\Mapper\EventMapper', ['getEventTblInfo' => function($arg){
            if($arg==='id1'){
                return array(
                    'join_member' => null,
                    'none_join_member' => null,
                );
            }
        }]); 
        $mock2 = test::double('\Classes\Mapper\EventMapper', ['getEventParticipantsInfo' => function($arg0,&$arg1,&$arg2){
            if($arg0==='id1'){
                $arg1 = "join_member";
                $arg2 = "none_join_member";
                return true;
            }else{
                throw exception;
            }
        }]); 
        $mock3 = test::double('\Classes\Mapper\EventMapper', ['getMemberInfo' => function($arg0){
            if($arg0==='join_member'){
                return '参加者一覧';
            }else if($arg0==='none_join_member'){
                return '不参加者一覧';
            }
        }]); 

        // メソッド実行
        $object = new Mapper\EventMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('isEventInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,'id1');

        // 検証
        $resultTest = array(
            'join_member' => '参加者一覧',
            'none_join_member' => '不参加者一覧',
        );
        $this->assertEquals($resultTest, $res);

    }

    /**
     * @group mapper
     */
    public function testgetMemberInfo(){

        // mock
        $mock1 = test::double('\Classes\Mapper\EventMapper', ['isUserInfo' => function($arg){
            if($arg==='id1'){
                return array(
                    'display_name' => 'display_name1',
                    'picture_url' => 'picture_url1',
                );
            }else if($arg==='id2'){
                return array(
                    'display_name' => 'display_name2',
                    'picture_url' => 'picture_url2',
                );
            }
        }]); 

        // メソッド実行
        $arg = array(
            0 => array(
                'id' => 'id1',
                'new_flag' => 0,
            ),
            1 => array(
                'id' => 'id2',
                'new_flag' => 0,
            ),
            2 => array(
                'id' => 'id3',
                'new_flag' => 1,
                'new_name' => 'newnew',
                'new_gender' => 1,
                'new_age' => 2
            ),
        );
        $object = new Mapper\EventMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('getMemberInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,$arg);

        // 検証
        $resultTest = array(
            0 => array(
                'id' => 'id1',
                'new_flag' => 0,
                'display_name' => 'display_name1',
                'picture_url' => 'picture_url1',
            ),
            1 => array(
                'id' => 'id2',
                'new_flag' => 0,
                'display_name' => 'display_name2',
                'picture_url' => 'picture_url2',
            ),
            2 => array(
                'new_flag' => 1,
                'display_name' => 'newnew',
                'gender' => '男性',
                'age' => '２０代',
                'picture_url' => '/img/man.png',
            ),
        );

        $this->assertEquals($resultTest, $res);
    }

    /**
     * @group mapper
     */
    public function testgetEventParticipantsInfo(){

        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event_participants`(`id`, `event_id`, `member_id`, `join_flag`, `new_flag`, `new_name`, `new_gender`, `new_age`, `created_at`, `updated_at`) 
            VALUES (null,'testgetEventParticipantsInfo','001','0','1','aaa',1,0,CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event_participants`(`id`, `event_id`, `member_id`, `join_flag`, `new_flag`, `new_name`, `new_gender`, `new_age`, `created_at`, `updated_at`) 
            VALUES (null,'testgetEventParticipantsInfo','003','1','0','bbb',0,2,CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event_participants`(`id`, `event_id`, `member_id`, `join_flag`, `new_flag`, `new_name`, `new_gender`, `new_age`, `created_at`, `updated_at`) 
            VALUES (null,'testgetEventParticipantsInfo','002','0','1','aaa',1,0,CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event_participants`(`id`, `event_id`, `member_id`, `join_flag`, `new_flag`, `new_name`, `new_gender`, `new_age`, `created_at`, `updated_at`) 
            VALUES (null,'testgetEventParticipantsInfo','004','1','0','bbb',0,2,CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Mapper\EventMapper($this->container['db']);
        $result = $object->getEventParticipantsInfo("testgetEventParticipantsInfo",$join_member,$none_join_member);

        // 検証
        $test_join_member = array(
            0 => array(
                'id'=>"003",
                'new_flag'=>"0",
                'new_name'=>"bbb",
                'new_gender'=>"0",
                'new_age'=>"2",
            ),
            1 => array(
                'id'=>"004",
                'new_flag'=>"0",
                'new_name'=>"bbb",
                'new_gender'=>"0",
                'new_age'=>"2",
            )
        );
        $test_none_join_member = array(
            0 => array(
                'id'=>"001",
                'new_flag'=>"1",
                'new_name'=>"aaa",
                'new_gender'=>"1",
                'new_age'=>"0",
            ),
            1 => array(
                'id'=>"002",
                'new_flag'=>"1",
                'new_name'=>"aaa",
                'new_gender'=>"1",
                'new_age'=>"0",
            )
        );
        $this->assertTrue($result);
        $this->assertEquals($test_join_member, $join_member);
        $this->assertEquals($test_none_join_member, $none_join_member);

    }

    /**
     * @group mapper
     */
    public function testgetEventTblInfo(){
        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (null,'test_event_id','バドミントン１面','なんとか公園','2018-04-23','11:11:11','22:22:22',501,0,0,CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Mapper\EventMapper($this->container['db']);
        $result = $object->getEventTblInfo("test_event_id");

        // 検証
        $test_result = array(
            'title' => 'バドミントン１面',
            'place' => 'なんとか公園',
            'event_date' => '04/23(月)',
            'start_time' => '11:11',
            'end_time' => '22:22',
            'fee' => '501',
            'join_member' => null,
            'none_join_member' => null,
        );

        $this->assertEquals($test_result, $result);
    }

    /**
     * @group mapper
     */
    public function testisEvent(){

        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (null,'event_id','バドミントン１面','なんとか公園',CURRENT_DATE(),'11:11:11','22:22:22',501,0,0,CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行　true
        $object = new Mapper\EventMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('isEvent');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,'event_id');

        // 検証
        $this->assertTrue($res);

        // メソッド実行　false
        $object = new Mapper\EventMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('isEvent');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,'event_idxxxxxxxx');

        // 検証
        $this->assertFalse($res);

    }

    /**
     * @group mapper
     */
    public function testisUserInfo(){

        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `user_mst`(`id`, `user_id`, `display_name`, `picture_url`, `insert_date`, `delete_flg`) 
            VALUES (null,'testid','あああ','http://aaa.jp/image.png',CURRENT_DATE(),0)"
        );

        // メソッド実行
        $object = new Mapper\EventMapper($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('isUserInfo');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする
        $res = $method->invoke($object,'testid');

        // 検証
        $array = array(
            'display_name' => 'あああ',
            'picture_url' => 'http://aaa.jp/image.png'
        );
        $this->assertEquals($array, $res);

    }
}