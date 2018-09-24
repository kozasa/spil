<?php
namespace Tests\Classes\Mapper\EventParticipants;

use PHPUnit\Framework\TestCase;
use Classes\Mapper\EventParticipants;
use AspectMock\Test as test;
use Tests\Classes as Base;

class EventParticipantsMapperTest extends Base\BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->container['db']->beginTransaction();

        // 元のデータを全て削除
        $this->container['db']->query(
            "DELETE FROM `event_participants`"
        );
        $this->container['db']->query(
            "DELETE FROM `user_mst`"
        );

        // auto_increment初期化
        $this->container['db']->query(
            "ALTER TABLE `event_participants` AUTO_INCREMENT = 1"
        );
        $this->container['db']->query(
            "ALTER TABLE `user_mst` AUTO_INCREMENT = 1"
        );
        
    }
    protected function tearDown()
    {
        parent::tearDown();
        $this->container['db']->rollback(); // 元に戻す

        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group map
     */
    public function testselectFromEventIdAndJoinFlag(){
        
        // ユーザマスタデータ作成　３件
        $this->container['db']->query(
            "INSERT INTO `user_mst`(`id`, `user_id`, `display_name`, `picture_url`, `insert_date`, `delete_flg`) 
            VALUES (null,'U3dabd0f08de3043c350bc1a14dc34a93','テスト太郎','http://test1.jp/1.png',CURRENT_DATE(),0)"
        );
        $this->container['db']->query(
            "INSERT INTO `user_mst`(`id`, `user_id`, `display_name`, `picture_url`, `insert_date`, `delete_flg`) 
            VALUES (null,'222222222222222222222222222222222','テスト花子','http://test2.jp/2.png',CURRENT_DATE(),0)"
        );
        $this->container['db']->query(
            "INSERT INTO `user_mst`(`id`, `user_id`, `display_name`, `picture_url`, `insert_date`, `delete_flg`) 
            VALUES (null,'333333333333333333333333333333333','テスト一郎','http://test3.jp/3.png',CURRENT_DATE(),0)"
        );

        // イベント情報データ作成
        $this->container['db']->query(
            "INSERT INTO `event_participants`(`id`, `event_id`, `member_id`, `join_flag`, `new_flag`, `new_name`, `new_gender`, `new_age`, `created_at`, `updated_at`) 
            VALUES (null,'b000005','U3dabd0f08de3043c350bc1a14dc34a93',1,0,'',0,0,CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event_participants`(`id`, `event_id`, `member_id`, `join_flag`, `new_flag`, `new_name`, `new_gender`, `new_age`, `created_at`, `updated_at`) 
            VALUES (null,'b000005','222222222222222222222222222222222',1,0,'',0,0,CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event_participants`(`id`, `event_id`, `member_id`, `join_flag`, `new_flag`, `new_name`, `new_gender`, `new_age`, `created_at`, `updated_at`) 
            VALUES (null,'b000005','333333333333333333333333333333333',0,0,'',0,0,CURRENT_DATE(),CURRENT_DATE())"
        );

        // データあり　フラグあり

        // メソッド実行
        $object = new EventParticipants\EventParticipantsMapper($this->container['db']);
        $result = $object->selectFromEventIdAndJoinFlag('b000005',1);

        // 検証用データ
        $testResult = array();
        $stmt = $this->container['db']->query("SELECT * FROM `event_participants` WHERE event_id = 'b000005'");
        $select = $stmt->fetch();

        $array = array(
            'id' => 1,
            'event_id' => 'b000005',
            'member_id' => 'U3dabd0f08de3043c350bc1a14dc34a93',
            'join_flag' => 1,
            'new_flag' => 0,
            'new_name' => '',
            'new_gender' => 0,
            'new_age' => 0,
            'created_at' => $select['created_at'],
            'updated_at' => $select['updated_at'],
            'display_name' => 'テスト太郎',
            'picture_url' => 'http://test1.jp/1.png',
        );
        $data = new EventParticipants\EventParticipantsData($array);
        array_push($testResult,$data);

        $array = array(
            'id' => 2,
            'event_id' => 'b000005',
            'member_id' => '222222222222222222222222222222222',
            'join_flag' => 1,
            'new_flag' => 0,
            'new_name' => '',
            'new_gender' => 0,
            'new_age' => 0,
            'created_at' => $select['created_at'],
            'updated_at' => $select['updated_at'],
            'display_name' => 'テスト花子',
            'picture_url' => 'http://test2.jp/2.png',
        );
        $data = new EventParticipants\EventParticipantsData($array);
        array_push($testResult,$data);

        $this->assertEquals($testResult, $result);

        // データあり　フラグなし

        // メソッド実行
        $object = new EventParticipants\EventParticipantsMapper($this->container['db']);
        $result = $object->selectFromEventIdAndJoinFlag('b000005',0);

        $testResult = array();

        $array = array(
            'id' => 3,
            'event_id' => 'b000005',
            'member_id' => '333333333333333333333333333333333',
            'join_flag' => 0,
            'new_flag' => 0,
            'new_name' => '',
            'new_gender' => 0,
            'new_age' => 0,
            'created_at' => $select['created_at'],
            'updated_at' => $select['updated_at'],
            'display_name' => 'テスト一郎',
            'picture_url' => 'http://test3.jp/3.png',
        );
        $data = new EventParticipants\EventParticipantsData($array);
        array_push($testResult,$data);

        $this->assertEquals($testResult, $result);

        // データなし

        $object = new EventParticipants\EventParticipantsMapper($this->container['db']);
        $result = $object->selectFromEventIdAndJoinFlag('b000006',1);

        $testResult = array();

        $this->assertEquals($testResult, $result);
    }

    /**
     * @group map
     */
    public function testselectExistFromUseridAndEventid(){
        
        // イベント情報データ作成
        $this->container['db']->query(
            "INSERT INTO `event_participants`(`id`, `event_id`, `member_id`, `join_flag`, `new_flag`, `new_name`, `new_gender`, `new_age`, `created_at`, `updated_at`) 
            VALUES (null,'b000005','U3dabd0f08de3043c350bc1a14dc34a93',1,0,'',0,0,CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event_participants`(`id`, `event_id`, `member_id`, `join_flag`, `new_flag`, `new_name`, `new_gender`, `new_age`, `created_at`, `updated_at`) 
            VALUES (null,'b000005','222222222222222222222222222222222',1,0,'',0,0,CURRENT_DATE(),CURRENT_DATE())"
        );

        // データなし 1
        $object = new EventParticipants\EventParticipantsMapper($this->container['db']);
        $result = $object->selectExistFromUseridAndEventid('U3dabd0f08de3043c350bc1a14dc34a93','b000006');

        $this->assertEquals(false, $result);

        // データなし 2
        $result = $object->selectExistFromUseridAndEventid('U3dabd0f08de3043c350bc1a14dc34a91','b000005');

        $this->assertEquals(false, $result);

        // データあり
        $result = $object->selectExistFromUseridAndEventid('U3dabd0f08de3043c350bc1a14dc34a93','b000005');
        $this->assertEquals(true, $result);

        
    }

    /**
     * @group map
     */
    public function testinsert(){

        // 挿入データ
        $array = array(
            'event_id' => 'b000005',
            'member_id' => '333333333333333333333333333333333',
            'join_flag' => 0,
        );

        // メソッド実行
        $object = new EventParticipants\EventParticipantsMapper($this->container['db']);
        $result = $object->insert($array);

        // 検証
        $stmt = $this->container['db']->query("SELECT * FROM `event_participants` WHERE event_id = 'b000005'");
        $select = $stmt->fetch();

        $this->assertEquals($select['event_id'], $array['event_id']);
        $this->assertEquals($select['member_id'], $array['member_id']);
        $this->assertEquals($select['join_flag'], $array['join_flag']);
    }

    /**
     * @group map
     */
    public function testinsertNewUser(){

        // 挿入データ
        $array = array(
            'event_id' => 'b000005',
            'new_name' => '田中太郎',
            'new_gender' => 1,
            'new_age' => 2,
        );

        // メソッド実行
        $object = new EventParticipants\EventParticipantsMapper($this->container['db']);
        $result = $object->insertNewUser($array);

        // 検証
        $stmt = $this->container['db']->query("SELECT * FROM `event_participants` WHERE event_id = 'b000005'");
        $select = $stmt->fetch();

        $this->assertEquals($select['event_id'], $array['event_id']);
        $this->assertEquals($select['new_name'], $array['new_name']);
        $this->assertEquals($select['new_gender'], $array['new_gender']);
        $this->assertEquals($select['new_age'], $array['new_age']);
    }

    /**
     * @group map
     */
    public function testupdate(){

        // テストデータ挿入
        $this->container['db']->query(
            "INSERT INTO `event_participants`(`id`, `event_id`, `member_id`, `join_flag`, `new_flag`, `new_name`, `new_gender`, `new_age`, `created_at`, `updated_at`) 
            VALUES (null,'b000005','222222222222222222222222222222222',1,0,'',0,0,CURRENT_DATE(),CURRENT_DATE())"
        );

        // 挿入データ
        $array = array(
            'join_flag' => 0,
            'member_id' => '222222222222222222222222222222222',
            'eventId' => 'b000005',
        );

        // メソッド実行
        $object = new EventParticipants\EventParticipantsMapper($this->container['db']);
        $result = $object->update($array);

        // 検証
        $stmt = $this->container['db']->query("SELECT * FROM `event_participants` WHERE event_id = 'b000005' AND member_id = '222222222222222222222222222222222'");
        $select = $stmt->fetch();

        $this->assertEquals($select['join_flag'], $array['join_flag']);
    }
}