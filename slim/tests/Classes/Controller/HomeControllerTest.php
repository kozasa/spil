<?php
namespace Tests\Classes\Controller;

use PHPUnit\Framework\TestCase;
use Classes\Controller;
use AspectMock\Test as test;
use Tests\Classes as Base;

class HomeControllerTest extends Base\BaseTestCase
{
    protected function tearDown()
    {
        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group controller
     */
    public function testhome(){

        $mock1 = test::double('\Classes\Mapper\IndexMapper', ['getLatestInfo' => array( 0 => 
            array(
                'event_id'=>"b000004",
                'title'=>"バドミントン１面",
                'place'=>"富田地区会館",
                'event_date'=>"2018-04-25",
                'start_time'=>"18:30:00",
                'end_time'=>"21:00:00",
                'year'=>"2018",
                'month'=>"4",
                'day'=>"25",
                'week'=>"水",
            ),
        )]);
        $response = $this->runApp('GET', '/');
        
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());
        // タイトル確認
        $this->assertContains('スピル - 名古屋市内のバドミントンサークル', (string)$response->getBody());
        // 出力確認
        $this->assertContains('富田地区会館', (string)$response->getBody());

    }
}