<?php
namespace Tests\Classes\Controller;

use PHPUnit\Framework\TestCase;
use Classes\Controller;
use AspectMock\Test as test;

class MemberControllerTest extends ControllerBaseTestCase
{
    protected function tearDown()
    {
        test::clean(); // 登録したテストダブルをすべて削除
    }

    public function testevent(){

        /**
         * パラメータのイベントIDがDBに登録されている
         */
        $event_id = 'b000004';
        $mock1 = test::double('\Classes\Mapper\EventMapper', ['getEventInfo' => function($arg){
            if($arg === 'b000004'){
                return array(
                    'title'=>"バドミントン１面",
                    'place'=>"富田地区会館",
                    'event_date'=>"04/25(水)",
                    'start_time'=>"18:30",
                    'end_time'=>"21:00",
                    'fee'=>"500",
                    'join_member'=> [] ,
                    'none_join_member'=> [] ,
                );
            }else{
                return false;
            }
        }]);
        
        $response = $this->runApp('GET', '/event/'.$event_id);
        
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());
        // タイトル確認
        $this->assertContains('イベント情報 - スピル', (string)$response->getBody());
        // 出力確認
        $this->assertContains('バドミントン１面', (string)$response->getBody());

        /**
         * パラメータのイベントIDがDBに登録されていない
         */
        $event_id = 'b000009';
        $response = $this->runApp('GET', '/event/'.$event_id);
        
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotContains('イベント情報 - スピル', (string)$response->getBody());
        $this->assertNotContains('バドミントン１面', (string)$response->getBody());

    }

    public function testlatest(){

        $mock1 = test::double('\Classes\Mapper\LatestMapper', ['getLatestInfo' => array( 0 => 
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
        $response = $this->runApp('GET', '/latest/');
        
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());
        // タイトル確認
        $this->assertContains('直近イベント日時情報 - スピル', (string)$response->getBody());
        // 出力確認
        $this->assertContains('b000004', (string)$response->getBody());

    }
}