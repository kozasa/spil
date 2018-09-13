<?php
namespace Tests\Classes\Model;

use PHPUnit\Framework\TestCase;
use Classes\Model;
use Classes\Mapper\Event;
use AspectMock\Test as test;
use Tests\Classes as Base;

class LineBotRecieveModelTest extends Base\BaseTestCase
{
    protected function tearDown()
    {
        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group model
     */
    public function testWeekRePush(){

        /**
         * 情報が取得できた場合
         */


        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['selectFromWeek' => function($arg){
            if($arg===2){ //火曜日

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

                return $data;
            }else{
                throw exception;
            }
        }]);

        // 実行
        $object1 = new Model\LineBotRecieveModel($this->container['db']);
        $result = $object1->WeekRePush('火曜日再通知');

        // 検証用データ
        $array = array(
            'event_id' => 'event_id1',
            'title' => 'バドミントン１面',
            'place' => 'なんとか公園',
            'event_date' => '01月03日 (水)',
            'start_time' => '11:11',
            'end_time' => '22:22',
        );

        $this->assertEquals($array, $result);

        /**
         * 情報が取得できない場合
         */

        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['selectFromWeek' => function($arg){
            if($arg===3){ //水曜日

                return false;
            }else{
                throw exception;
            }
        }]);

        // 実行
        $object1 = new Model\LineBotRecieveModel($this->container['db']);
        $result = $object1->WeekRePush('水曜日再通知');

        $this->assertEquals(false, $result);
    }

    /**
     * @group model
     */
    public function testRePush(){

        /**
         * 情報が取得できた場合
         */
        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['isBeforeDaysInfo' => function($arg){
            if($arg===0){ 

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

                return $data;
            }else{
                throw exception;
            }
        }]);

        // 実行
        $object1 = new Model\LineBotRecieveModel($this->container['db']);
        $result = $object1->RePush();

        // 検証用データ
        $array = array(
            'event_id' => 'event_id1',
            'title' => 'バドミントン１面',
            'place' => 'なんとか公園',
            'event_date' => '01月03日 (水)',
            'start_time' => '11:11',
            'end_time' => '22:22',
        );

        $this->assertEquals($array, $result);
        
         /**
          * 情報が取得できない場合
          */

        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['isBeforeDaysInfo' => function($arg){
            if($arg===0){ 
                return false;
            }else{
                throw exception;
            }
        }]);

        // 実行
        $object1 = new Model\LineBotRecieveModel($this->container['db']);
        $result = $object1->RePush();

        $this->assertEquals(false, $result);
        
    }
}