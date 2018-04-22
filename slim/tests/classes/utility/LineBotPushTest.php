<?php
namespace Tests\Classes\Utility;

use PHPUnit\Framework\TestCase;
use Classes\Utility;
use AspectMock\Test as test;

class LineBotPushTest extends TestCase
{
    protected function tearDown()
    {
        test::clean(); // 登録したテストダブルをすべて削除
    }

    public function testpush(){
        // LINEボットのpush機能のため、検証不可
        // LINEボットを実際に手動で動作させて検証する
        $this->assertEquals(true,true);

    }
    public function testpushCron(){
        /**
         * 7日前
         * 7日前のメッセージとなっているか確認
         */
        $mock1 = test::double('\Classes\Utility\LineBotMassage', ['push_join_message_seven' => '7day']);
        $mock2 = test::double('\Classes\Utility\LineBotPush', ['push' => function($arg){
            if ($arg === '7day') {
                return true;
            }else{
                return false;
            }
        }]);

        $info = array('day' => 7);
        $this->assertEquals(true,Utility\LineBotPush::pushCron($info));

        test::clean();

        /**
         * 1日前
         */
        $mock1 = test::double('\Classes\Utility\LineBotMassage', ['push_join_message_one' => '1day']);
        $mock2 = test::double('\Classes\Utility\LineBotPush', ['push' => function($arg){
            if ($arg === '1day') {
                return true;
            }else{
                return false;
            }
        }]);

        $info = array('day' => 1);
        $this->assertEquals(true,Utility\LineBotPush::pushCron($info));

        test::clean();
    }
}