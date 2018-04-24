<?php
namespace Tests\Classes\Mapper;

use PHPUnit\Framework\TestCase;
use Classes\Mapper;
use AspectMock\Test as test;
use Tests\Classes as Base;

class LatestMapperTest extends Base\BaseTestCase
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
    public function testgetLatestInfo(){

        $week = [
            '日', //0
            '月', //1
            '火', //2
            '水', //3
            '木', //4
            '金', //5
            '土', //6
        ];

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
        $object = new Mapper\LatestMapper($this->container['db']);
        $result = $object->getLatestInfo();

        // 検証
        $stmt = $this->container['db']->query('SELECT *,year(`event_date`) as year,month(`event_date`) as month ,day(`event_date`) as day FROM `event` WHERE event_date >= CURRENT_DATE() order by event_date');

        $latest_all_info = array();
        while($row = $stmt -> fetch()){
            $weekday = date('w',strtotime($row['event_date']));
            $latest_all_info = array_merge(
                $latest_all_info,
                array(
                    array(
                        'event_id' => $row['event_id'],
                        'title' => $row['title'],
                        'place' => $row['place'],
                        'event_date' => $row['event_date'],
                        'start_time' => $row['start_time'],
                        'end_time' => $row['end_time'],
                        'year' => $row['year'],
                        'month' => $row['month'],
                        'day' => $row['day'],
                        'week' => $week[$weekday],
                    )
                )
            );
        }

        $this->assertEquals($latest_all_info,$result);

    }

}