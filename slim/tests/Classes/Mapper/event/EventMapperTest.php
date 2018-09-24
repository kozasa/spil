<?php
namespace Tests\Classes\Mapper\Event;

use PHPUnit\Framework\TestCase;
use Classes\Mapper\Event;
use AspectMock\Test as test;
use Tests\Classes as Base;

class EventMapperTest extends Base\BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->container['db']->beginTransaction();

        // 元のデータを全て削除
        $this->container['db']->query(
            "DELETE FROM `event`"
        );

        // auto_increment初期化
        $this->container['db']->query(
            "ALTER TABLE `event` AUTO_INCREMENT = 1"
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
    public function testselectLatest(){
        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `comment`,`created_at`, `updated_at`) 
            VALUES (null,'event_id1','バドミントン１面','なんとか公園',CURRENT_DATE(),'11:11:11','22:22:22',501,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `comment`,`created_at`, `updated_at`) 
            VALUES (null,'event_id2','バドミントン２面','なんとか公園あ',CURRENT_DATE()+1,'11:11:22','33:22:22',601,1,1,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `comment`,`created_at`, `updated_at`) 
            VALUES (null,'event_id3','バドミントン３面','なんとか公園ああ',CURRENT_DATE()-1,'11:11:33','22:22:33',701,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `comment`,`created_at`, `updated_at`) 
            VALUES (null,'event_id4','バドミントン４面','なんとか公園あああ',CURRENT_DATE()+100,'11:11:44','22:22:44',801,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->selectLatest();

        // 検証用データ準備
        $testResult = array();

        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_date >= CURRENT_DATE() order by event_date");
        $select = $stmt->fetch();
        $array = array(
            'id' => $select['id'],
            'event_id' => 'event_id1',
            'title' => 'バドミントン１面',
            'place' => 'なんとか公園',
            'event_date' => $select['event_date'],
            'start_time' => '11:11:11',
            'end_time' => '22:22:22',
            'fee' => '501',
            'before_seven_days' => '0',
            'before_one_day' => '0',
            'comment' => 'test',
            'created_at' => $select['created_at'],
            'updated_at' => $select['updated_at'],
        );
        $data = new Event\EventData($array);
        array_push($testResult,$data);

        $select = $stmt->fetch();
        $array = array(
            'id' => $select['id'],
            'event_id' => 'event_id2',
            'title' => 'バドミントン２面',
            'place' => 'なんとか公園あ',
            'event_date' => $select['event_date'],
            'start_time' => '11:11:22',
            'end_time' => '33:22:22',
            'fee' => '601',
            'before_seven_days' => '1',
            'before_one_day' => '1',
            'comment' => 'test',
            'created_at' => $select['created_at'],
            'updated_at' => $select['updated_at'],
        );
        $data = new Event\EventData($array);
        array_push($testResult,$data);

        $select = $stmt->fetch();
        $array = array(
            'id' => $select['id'],
            'event_id' => 'event_id4',
            'title' => 'バドミントン４面',
            'place' => 'なんとか公園あああ',
            'event_date' => $select['event_date'],
            'start_time' => '11:11:44',
            'end_time' => '22:22:44',
            'fee' => '801',
            'before_seven_days' => '0',
            'before_one_day' => '0',
            'comment' => 'test',
            'created_at' => $select['created_at'],
            'updated_at' => $select['updated_at'],
        );
        $data = new Event\EventData($array);
        array_push($testResult,$data);

        $this->assertEquals($testResult, $result);

    }

    /**
     * @group map
     */
    public function testselectFromEventId(){

        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `comment`,`created_at`, `updated_at`) 
            VALUES (null,'event_id5','バドミントン１面','なんとか公園',CURRENT_DATE(),'11:11:11','22:22:22',501,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->selectFromEventId('event_id5');

        // 検証用データ準備
        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'event_id5'");
        $select = $stmt->fetch();
        $array = array(
            'id' => $select['id'],
            'event_id' => 'event_id5',
            'title' => 'バドミントン１面',
            'place' => 'なんとか公園',
            'event_date' => $select['event_date'],
            'start_time' => '11:11:11',
            'end_time' => '22:22:22',
            'fee' => '501',
            'before_seven_days' => '0',
            'before_one_day' => '0',
            'comment' => 'test',
            'created_at' => $select['created_at'],
            'updated_at' => $select['updated_at'],
        );
        $testResult = new Event\EventData($array);

        $this->assertEquals($testResult, $result);
    }

    /**
     * @group map
     */
    public function testisEvent(){

        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (100,'event_id7','バドミントン１面','なんとか公園',CURRENT_DATE(),'11:11:11','22:22:22',501,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->isEvent('event_id7');

        $this->assertEquals(true, $result);

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->isEvent('event_id9');

        $this->assertEquals(false, $result);

    }

    /**
     * @group map
     */
    public function testisEventToday(){

        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id2','バドミントン２面','なんとか公園あ',CURRENT_DATE()+1,'11:11:22','33:22:22',601,1,1,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id3','バドミントン３面','なんとか公園ああ',CURRENT_DATE()-1,'11:11:33','22:22:33',701,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id4','バドミントン４面','なんとか公園あああ',CURRENT_DATE()+100,'11:11:44','22:22:44',801,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->isEventToday();

        $this->assertEquals(false, $result);

        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id1','バドミントン１面','なんとか公園',CURRENT_DATE(),'11:11:11','22:22:22',501,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->isEventToday();

        $this->assertEquals(true, $result);

    }

    /**
     * @group map
     */
    public function testisBeforeDaysInfo(){

        /**
         * ７日前でデータなし
         */
        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id2','バドミントン２面','なんとか公園あ',DATE_ADD(CURRENT_DATE(),INTERVAL 7 DAY),'11:11:22','33:22:22',601,1,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id2','バドミントン２面','なんとか公園あ',DATE_ADD(CURRENT_DATE(),INTERVAL 8 DAY),'11:11:22','33:22:22',601,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->isBeforeDaysInfo(7);

        $this->assertEquals(false, $result);

        /**
         * ７日前でデータあり
         */
        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id3','バドミントン３面','なんとか公園ああ',DATE_ADD(CURRENT_DATE(),INTERVAL 7 DAY),'11:11:33','22:22:33',701,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id4','バドミントン４面','なんとか公園あああ',DATE_ADD(CURRENT_DATE(),INTERVAL 6 DAY),'11:11:44','22:22:44',801,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->isBeforeDaysInfo(7);

        // 検証用データ準備
        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'event_id4'");
        $select = $stmt->fetch();
        $array = array(
            'id' => $select['id'],
            'event_id' => 'event_id4',
            'title' => 'バドミントン４面',
            'place' => 'なんとか公園あああ',
            'event_date' => $select['event_date'],
            'start_time' => '11:11:44',
            'end_time' => '22:22:44',
            'fee' => '801',
            'before_seven_days' => '0',
            'before_one_day' => '0',
            'comment' => 'test',
            'created_at' => $select['created_at'],
            'updated_at' => $select['updated_at'],
        );
        $testResult = new Event\EventData($array);

        $this->assertEquals($testResult, $result);

        /**
         * 1日前でデータなし
         */
        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id2','バドミントン２面','なんとか公園あ',DATE_ADD(CURRENT_DATE(),INTERVAL 2 DAY),'11:11:22','33:22:22',601,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id9','バドミントン２面','なんとか公園あ',DATE_ADD(CURRENT_DATE(),INTERVAL 1 DAY),'11:11:22','33:22:22',601,0,1,'test',CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->isBeforeDaysInfo(1);

        $this->assertEquals(false, $result);

        /**
         * 1日前でデータあり
         */
        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id3','バドミントン３面','なんとか公園ああ',DATE_ADD(CURRENT_DATE(),INTERVAL 1 DAY),'11:11:33','22:22:33',701,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id8','バドミントン４面','なんとか公園あああ',CURRENT_DATE(),'11:11:44','22:22:44',801,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->isBeforeDaysInfo(7);

        // 検証用データ準備
        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'event_id8'");
        $select = $stmt->fetch();
        $array = array(
            'id' => $select['id'],
            'event_id' => 'event_id8',
            'title' => 'バドミントン４面',
            'place' => 'なんとか公園あああ',
            'event_date' => $select['event_date'],
            'start_time' => '11:11:44',
            'end_time' => '22:22:44',
            'fee' => '801',
            'before_seven_days' => '0',
            'before_one_day' => '0',
            'comment' => 'test',
            'created_at' => $select['created_at'],
            'updated_at' => $select['updated_at'],
        );
        $testResult = new Event\EventData($array);

        $this->assertEquals($testResult, $result);

        /**
         * 0の場合
         */
        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id6','バドミントン３面','なんとか公園ああ',DATE_SUB(CURRENT_DATE(),INTERVAL 1 DAY),'11:11:33','22:22:33',701,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->isBeforeDaysInfo(0);

        // 検証用データ準備
        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'event_id9'");
        $select = $stmt->fetch();
        $array = array(
            'id' => $select['id'],
            'event_id' => 'event_id9',
            'title' => 'バドミントン２面',
            'place' => 'なんとか公園あ',
            'event_date' => $select['event_date'],
            'start_time' => '11:11:22',
            'end_time' => '33:22:22',
            'fee' => '601',
            'before_seven_days' => '0',
            'before_one_day' => '1',
            'comment' => 'test',
            'created_at' => $select['created_at'],
            'updated_at' => $select['updated_at'],
        );
        $testResult = new Event\EventData($array);

        $this->assertEquals($testResult, $result);
    }

    /**
     * @group map
     */
    public function testselectLatestPiece(){

        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id2','バドミントン２面','なんとか公園あ',DATE_ADD(CURRENT_DATE(),INTERVAL 1 DAY),'11:11:22','33:22:22',601,1,1,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id3','バドミントン３面','なんとか公園ああ',DATE_SUB(CURRENT_DATE(),INTERVAL 1 DAY),'11:11:33','22:22:33',701,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id4','バドミントン４面','なんとか公園あああ',DATE_ADD(CURRENT_DATE(),INTERVAL 100 DAY),'11:11:44','22:22:44',801,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->selectLatestPiece();

        // 検証用データ準備
        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'event_id4'");
        $select = $stmt->fetch();
        $array = array(
            'id' => $select['id'],
            'event_id' => 'event_id4',
            'title' => 'バドミントン４面',
            'place' => 'なんとか公園あああ',
            'event_date' => $select['event_date'],
            'start_time' => '11:11:44',
            'end_time' => '22:22:44',
            'fee' => '801',
            'before_seven_days' => '0',
            'before_one_day' => '0',
            'comment' => 'test',
            'created_at' => $select['created_at'],
            'updated_at' => $select['updated_at'],
        );
        $testResult = new Event\EventData($array);

        $this->assertEquals($testResult, $result);

    }

    /**
     * @group map
     */
    public function testselectFromWeek(){

        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id2','バドミントン２面','なんとか公園あ',cast('2018-07-21' as date),'11:11:22','33:22:22',601,1,1,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id3','バドミントン３面','なんとか公園ああ',DATE_ADD(CURRENT_DATE(),INTERVAL 2 DAY),'11:11:33','22:22:33',701,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id4','バドミントン４面','なんとか公園あああ',cast('2018-07-22' as date),'11:11:44','22:22:44',801,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );

        /**
         * データなし
         */

        $week = date("w");
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->selectFromWeek($week);

        $this->assertEquals(false, $result);

        /**
         * データあり
         */
        $Today = date('Y/m/d');
        $wk_date = date("Y-m-d", strtotime($Today) + 60*60*24*2); // ２日後
        $day2Week = date("w",strtotime($wk_date));
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->selectFromWeek($day2Week);

        // 検証用データ準備
        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'event_id3'");
        $select = $stmt->fetch();
        $array = array(
            'id' => $select['id'],
            'event_id' => 'event_id3',
            'title' => 'バドミントン３面',
            'place' => 'なんとか公園ああ',
            'event_date' => $select['event_date'],
            'start_time' => '11:11:33',
            'end_time' => '22:22:33',
            'fee' => '701',
            'before_seven_days' => '0',
            'before_one_day' => '0',
            'comment' => 'test',
            'created_at' => $select['created_at'],
            'updated_at' => $select['updated_at'],
        );
        $testResult = new Event\EventData($array);

        $this->assertEquals($testResult, $result);

    }

    /**
     * @group map
     */
    public function testupdate(){

        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id2','バドミントン２面','なんとか公園あ',cast('2018-07-21' as date),'11:11:22','33:22:22',601,1,1,'test',CURRENT_DATE(),CURRENT_DATE())"
        );

        // テストデータ作成
        $array = array(
            'event_id' => 'event_id2',
            'title' => 'バドミントン４面',
            'place' => 'なんとか公園あああ',
            'event_date' => '2018-11-22',
            'start_time' => '11:11:44',
            'end_time' => '22:22:44',
            'fee' => '801'
        );

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->update($array);
        
        $this->assertEquals('event_id2', $result);

        // 検索
        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'event_id2'");
        $select = $stmt->fetch();

        $this->assertEquals($select['event_id'], $array['event_id']);
        $this->assertEquals($select['title'], $array['title']);
        $this->assertEquals($select['place'], $array['place']);
        $this->assertEquals($select['event_date'], $array['event_date']);
        $this->assertEquals($select['start_time'], $array['start_time']);
        $this->assertEquals($select['end_time'], $array['end_time']);
        $this->assertEquals($select['fee'], $array['fee']);

    }

    /**
     * @group map
     */
    public function testupdateFlag(){

        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id2','バドミントン２面','なんとか公園あ',cast('2018-07-21' as date),'11:11:22','33:22:22',601,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id3','バドミントン３面','なんとか公園ああ',DATE_ADD(CURRENT_DATE(),INTERVAL 2 DAY),'11:11:33','22:22:33',701,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`,`comment`, `created_at`, `updated_at`) 
            VALUES (null,'event_id4','バドミントン４面','なんとか公園あああ',cast('2018-07-22' as date),'11:11:44','22:22:44',801,0,0,'test',CURRENT_DATE(),CURRENT_DATE())"
        );

        /**
         * 7日
         */

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->updateFlag('event_id2',7);

        // 検索
        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'event_id2'");
        $select = $stmt->fetch();

        $this->assertEquals($select['before_seven_days'], true);

        /**
         * 1日
         */

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->updateFlag('event_id3',1);

        // 検索
        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'event_id3'");
        $select = $stmt->fetch();

        $this->assertEquals($select['before_one_day'], true);
    }

    /**
     * @group map
     */
    public function testinsert(){

        // テストデータ作成
        $array = array(
            'event_id' => 'event_id2',
            'title' => 'バドミントン４面',
            'place' => 'なんとか公園あああ',
            'event_date' => '2018-11-22',
            'start_time' => '11:11:44',
            'end_time' => '22:22:44',
            'comment' => 'test',
            'fee' => '801'
        );

        // メソッド実行
        $object = new Event\EventMapper($this->container['db']);
        $result = $object->insert($array);
        
        $this->assertEquals('event_id2', $result);

        // 検索
        $stmt = $this->container['db']->query("SELECT * FROM `event` WHERE event_id = 'event_id2'");
        $select = $stmt->fetch();

        $this->assertEquals($select['event_id'], $array['event_id']);
        $this->assertEquals($select['title'], $array['title']);
        $this->assertEquals($select['place'], $array['place']);
        $this->assertEquals($select['event_date'], $array['event_date']);
        $this->assertEquals($select['start_time'], $array['start_time']);
        $this->assertEquals($select['end_time'], $array['end_time']);
        $this->assertEquals($select['fee'], $array['fee']);
        $this->assertEquals($select['comment'], $array['comment']);
    }
}