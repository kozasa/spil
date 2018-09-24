<?php
namespace Tests\Classes\Mapper\Event;

use PHPUnit\Framework\TestCase;
use Classes\Mapper\Event;
use AspectMock\Test as test;
use Tests\Classes as Base;

class EventDataTest extends Base\BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }
    protected function tearDown()
    {
        parent::tearDown();

        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group data
     */
    public function test__construct(){

        // データ作成
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => 'event_datexxx',
            'start_time' => 'start_timexxx',
            'end_time' => 'end_timexxx',
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
            'comment' => 'comment_aaaa',
        );
        $object = new Event\EventData($data);
        
        // プライベート変数取得
        $reflectionClass = new \ReflectionClass($object);
        $id = $reflectionClass->getProperty('id');
        $id->setAccessible(true);
        $event_id = $reflectionClass->getProperty('event_id');
        $event_id->setAccessible(true);
        $title = $reflectionClass->getProperty('title');
        $title->setAccessible(true);
        $place = $reflectionClass->getProperty('place');
        $place->setAccessible(true);
        $event_date = $reflectionClass->getProperty('event_date');
        $event_date->setAccessible(true);
        $start_time = $reflectionClass->getProperty('start_time');
        $start_time->setAccessible(true);
        $end_time = $reflectionClass->getProperty('end_time');
        $end_time->setAccessible(true);
        $fee = $reflectionClass->getProperty('fee');
        $fee->setAccessible(true);
        $before_seven_days = $reflectionClass->getProperty('before_seven_days');
        $before_seven_days->setAccessible(true);
        $before_one_day = $reflectionClass->getProperty('before_one_day');
        $before_one_day->setAccessible(true);
        $comment = $reflectionClass->getProperty('comment');
        $comment->setAccessible(true);
        $created_at = $reflectionClass->getProperty('created_at');
        $created_at->setAccessible(true);
        $updated_at = $reflectionClass->getProperty('updated_at');
        $updated_at->setAccessible(true);

        // id
        $idValue = $id->getValue($object);
        $this->assertEquals($idValue,$data['id']);

        // event_id
        $event_idValue = $event_id->getValue($object);
        $this->assertEquals($event_idValue,$data['event_id']);

        // title
        $titleValue = $title->getValue($object);
        $this->assertEquals($titleValue,$data['title']);

        // place
        $placeValue = $place->getValue($object);
        $this->assertEquals($placeValue,$data['place']);

        // event_date
        $event_dateValue = $event_date->getValue($object);
        $this->assertEquals($event_dateValue,$data['event_date']);

        // start_time
        $start_timeValue = $start_time->getValue($object);
        $this->assertEquals($start_timeValue,$data['start_time']);

        // end_time
        $end_timeValue = $end_time->getValue($object);
        $this->assertEquals($end_timeValue,$data['end_time']);

        // fee
        $feeValue = $fee->getValue($object);
        $this->assertEquals($feeValue,$data['fee']);

        // before_seven_days
        $before_seven_daysValue = $before_seven_days->getValue($object);
        $this->assertEquals($before_seven_daysValue,$data['before_seven_days']);

        // before_one_day
        $before_one_dayValue = $before_one_day->getValue($object);
        $this->assertEquals($before_one_dayValue,$data['before_one_day']);

        // comment
        $comment = $comment->getValue($object);
        $this->assertEquals($comment,$data['comment']);

        // created_at
        $created_atValue = $created_at->getValue($object);
        $this->assertEquals($created_atValue,$data['created_at']);

        // id
        $updated_atValue = $updated_at->getValue($object);
        $this->assertEquals($updated_atValue,$data['updated_at']);

    }

    /**
     * @group data
     */
    public function testgetId(){

        // データ作成
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => 'event_datexxx',
            'start_time' => 'start_timexxx',
            'end_time' => 'end_timexxx',
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getId(),$data['id']);

    }

    /**
     * @group data
     */
    public function testgetEventId(){

        // データ作成
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => 'event_datexxx',
            'start_time' => 'start_timexxx',
            'end_time' => 'end_timexxx',
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getEventId(),$data['event_id']);

    }

    /**
     * @group data
     */
    public function testgetTitle(){

        // データ作成
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => 'event_datexxx',
            'start_time' => 'start_timexxx',
            'end_time' => 'end_timexxx',
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getTitle(),$data['title']);

    }

    /**
     * @group data
     */
    public function testgetPlace(){

        // データ作成
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => 'event_datexxx',
            'start_time' => 'start_timexxx',
            'end_time' => 'end_timexxx',
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getPlace(),$data['place']);

    }

    /**
     * @group data
     */
    public function testgetEventDate(){

        // データ作成
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => 'event_datexxx',
            'start_time' => 'start_timexxx',
            'end_time' => 'end_timexxx',
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getEventDate(),$data['event_date']);

    }

    /**
     * @group data
     */
    public function testgetEventYear(){

        // データ作成
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => date('2015/03/01'),
            'start_time' => 'start_timexxx',
            'end_time' => 'end_timexxx',
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getEventYear(),'2015');

    }

    /**
     * @group data
     */
    public function testgetEventMonth(){

        // データ作成
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => date('2015/03/01'),
            'start_time' => 'start_timexxx',
            'end_time' => 'end_timexxx',
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getEventMonth(),'03');

    }

    /**
     * @group data
     */
    public function testgetEventDay(){

        // データ作成
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => date('2015/03/01'),
            'start_time' => 'start_timexxx',
            'end_time' => 'end_timexxx',
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getEventDay(),'01');

    }

    /**
     * @group data
     */
    public function testgetEventDateDisplay(){

        // データ作成
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => date('2015/03/01'),
            'start_time' => 'start_timexxx',
            'end_time' => 'end_timexxx',
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getEventDateDisplay(),'03月01日 (日)');

    }

    /**
     * @group data
     */
    public function testgetEventWeek(){

        // データ作成
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => date('2015/03/01'),
            'start_time' => 'start_timexxx',
            'end_time' => 'end_timexxx',
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getEventWeek(),'日');

    }

    /**
     * @group data
     */
    public function testgetStartTime(){

        // データ作成
        $date = new \DateTime();
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => date('2015/03/01'),
            'start_time' => $date->format('2018-07-18 21:34:56'),
            'end_time' => $date->format('2019-08-21 12:31:41'),
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getStartTime(),'21:34');

    }

    /**
     * @group data
     */
    public function testgetEndTime(){

        // データ作成
        $date = new \DateTime();
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => date('2015/03/01'),
            'start_time' => $date->format('2018-07-18 21:34:56'),
            'end_time' => $date->format('2019-08-21 12:31:41'),
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getEndTime(),'12:31');

    }

    /**
     * @group data
     */
    public function testgetFee(){

        // データ作成
        $date = new \DateTime();
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => date('2015/03/01'),
            'start_time' => $date->format('2018-07-18 21:34:56'),
            'end_time' => $date->format('2019-08-21 12:31:41'),
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getFee(),$data['fee']);

    }

    /**
     * @group data
     */
    public function testgetBeforeSevenDays(){

        // データ作成
        $date = new \DateTime();
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => date('2015/03/01'),
            'start_time' => $date->format('2018-07-18 21:34:56'),
            'end_time' => $date->format('2019-08-21 12:31:41'),
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getBeforeSevenDays(),$data['before_seven_days']);

    }

    /**
     * @group data
     */
    public function testgetBeforeOneDay(){

        // データ作成
        $date = new \DateTime();
        $data = array(
            'id' => 'idxxx',
            'event_id' => 'event_idxxx',
            'title' => 'titlexxx',
            'place' => 'placexxx',
            'event_date' => date('2015/03/01'),
            'start_time' => $date->format('2018-07-18 21:34:56'),
            'end_time' => $date->format('2019-08-21 12:31:41'),
            'fee' => 'feexxx',
            'before_seven_days' => 'before_seven_daysxxx',
            'before_one_day' => 'before_one_dayxxx',
            'comment' => 'comment_aaaa',
            'created_at' => 'created_atxxx',
            'updated_at' => 'updated_atxxx',
        );
        $object = new Event\EventData($data);

        $this->assertEquals($object->getBeforeOneDay(),$data['before_one_day']);

    }
}