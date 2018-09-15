<?php
namespace Tests\Classes\Model;

use PHPUnit\Framework\TestCase;
use Classes\Model;
use Classes\Mapper\Event;
use AspectMock\Test as test;
use Tests\Classes as Base;

class HomeModelTest extends Base\BaseTestCase
{
    protected function tearDown()
    {
        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group model
     */
    public function testhome(){

        $testResult = array();
        $array = array(
            'id' => 1,
            'event_id' => 'event_id1',
            'title' => 'バドミントン１面',
            'place' => 'なんとか公園',
            'event_date' => '2018-01-03',
            'start_time' => '11:11:11',
            'end_time' => '22:22:22',
            'fee' => '501',
            'before_seven_days' => '0',
            'before_one_day' => '0',
            'created_at' => '',
            'updated_at' => '',
        );
        $data = new Event\EventData($array);
        array_push($testResult,$data);

        $array = array(
            'id' => 2,
            'event_id' => 'event_id2',
            'title' => 'バドミントン２面',
            'place' => 'なんとか公園あ',
            'event_date' => '2018-01-01',
            'start_time' => '11:11:22',
            'end_time' => '11:22:22',
            'fee' => '601',
            'before_seven_days' => '1',
            'before_one_day' => '1',
            'created_at' => '',
            'updated_at' => '',
        );
        $data = new Event\EventData($array);
        array_push($testResult,$data);

        $array = array(
            'id' => 3,
            'event_id' => 'event_id4',
            'title' => 'バドミントン４面',
            'place' => 'なんとか公園あああ',
            'event_date' => '2018-01-02',
            'start_time' => '11:11:44',
            'end_time' => '22:22:44',
            'fee' => '801',
            'before_seven_days' => '0',
            'before_one_day' => '0',
            'created_at' => '',
            'updated_at' => '',
        );
        $data = new Event\EventData($array);
        array_push($testResult,$data);

        $array = array(
            'id' => 4,
            'event_id' => 'event_id5',
            'title' => 'バドミントン6面',
            'place' => 'なんとか公園あああaaaaaaaaaa',
            'event_date' => '2018-01-02',
            'start_time' => '11:11:44',
            'end_time' => '22:22:44',
            'fee' => '801',
            'before_seven_days' => '0',
            'before_one_day' => '0',
            'created_at' => '',
            'updated_at' => '',
        );
        $data = new Event\EventData($array);
        array_push($testResult,$data);

        $array = array(
            'id' => 5,
            'event_id' => 'event_id6',
            'title' => 'バドミントン7面',
            'place' => 'なんとか公園あああbbbbbbbbb',
            'event_date' => '2018-01-02',
            'start_time' => '11:11:44',
            'end_time' => '22:22:44',
            'fee' => '801',
            'before_seven_days' => '0',
            'before_one_day' => '0',
            'created_at' => '',
            'updated_at' => '',
        );
        $data = new Event\EventData($array);
        array_push($testResult,$data);

        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['selectLatest' => $testResult]);

        // 実行
        $object1 = new Model\HomeModel($this->container['db']);
        $result = $object1->home();

        //検証用データ
        $testResult2 = array();
        $array = array(
            'event_id' => 'event_id1',
            'title' => 'バドミントン１面',
            'place' => 'なんとか公園',
            'event_date' => '01月03日 (水)',
            'start_time' => '11:11',
            'end_time' => '22:22',
        );
        array_push($testResult2,$array);

        $array = array(
            'event_id' => 'event_id2',
            'title' => 'バドミントン２面',
            'place' => 'なんとか公園あ',
            'event_date' => '01月01日 (月)',
            'start_time' => '11:11',
            'end_time' => '11:22',
        );
        array_push($testResult2,$array);

        $array = array(
            'event_id' => 'event_id4',
            'title' => 'バドミントン４面',
            'place' => 'なんとか公園あああ',
            'event_date' => '01月02日 (火)',
            'start_time' => '11:11',
            'end_time' => '22:22',
        );
        array_push($testResult2,$array);

        $this->assertEquals($testResult2, $result);

    }
}