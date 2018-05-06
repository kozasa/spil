<?php
namespace Tests\Classes\Mapper;

use PHPUnit\Framework\TestCase;
use Classes\Mapper;
use AspectMock\Test as test;
use Tests\Classes as Base;

class IndexMapperTest extends Base\BaseTestCase
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

        // テストデータ投入
        $this->container['db']->query(
            "INSERT INTO `event`(`id`, `event_id`, `title`, `place`, `event_date`, `start_time`, `end_time`, `fee`, `before_seven_days`, `before_one_day`, `created_at`, `updated_at`) 
            VALUES (99999,'','','',CURRENT_DATE(),'11:11:11','22:22:22',100,0,0,CURRENT_DATE(),CURRENT_DATE())"
        );

        $stmt = $this->container['db']->query('SELECT * FROM `event` WHERE event_date >= now() order by event_date');

        // メソッド実行
        $object = new Mapper\IndexMapper($this->container['db']);
        $result = $object->getLatestInfo();

        #最大3日分のイベント情報取得
        $latest_info = array();
        $count = 0;
        while($row = $stmt->fetch() and $count < 3){
            $weekday = date('w',strtotime($row['event_date']));
            $event_info = array(
                'event_id' => $row['event_id'],
                'title' => $row['title'],
                'place' => $row['place'],
                'event_date' => date('m月d日 ' ,strtotime($row['event_date'])).'('.$week[$weekday].') ',
                'start_time' => date('H:i' ,strtotime($row['start_time'])),
                'end_time' => date('H:i' ,strtotime($row['end_time'])),
            );
            array_push($latest_info,$event_info);
            $count++;
        }

        $this->assertEquals($latest_info,$result);

    }
}