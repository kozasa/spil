<?php
namespace Tests\Classes\Utility;

use PHPUnit\Framework\TestCase;
use Classes\Utility;
use AspectMock\Test as test;

class LineBotRecieveTest extends TestCase
{
    protected function tearDown()
    {
        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group utility
     */
    public function testrecieveMassage(){

        /**
         * スピルくん再通知が含まれている場合
         */
        $massage_text = "あああスピルくん再通知ああああ";

        $mock1 = test::double('\Classes\Mapper\PushMapper', ['getRePushInfo' => 'push_massage']);
        $mock2 = test::double('\Classes\Utility\LineBotMassage', ['push_join_message_seven' => function($arg){
            if($arg === 'push_massage'){
                return "massage";
            }else{
                throw e;
            }
        }]);
        $mock3 = test::double('\Classes\Utility\LineBotPush', ['push' => function($arg){
            if($arg === 'massage'){
                return true;
            }else{
                throw e;
            }
        }]);

        $this->assertEquals(null,Utility\LineBotRecieve::recieveMassage(null,$massage_text));

        test::clean();

        /**
         * スピルくん再通知が含まれていない場合
         */

        $mock1 = test::double('\Classes\Mapper\PushMapper', ['getRePushInfo' => function($arg){
            throw e;
        }]);

        $massage_text_none = "あああスピルくん再送信あああ";
        $this->assertEquals(null,Utility\LineBotRecieve::recieveMassage(null,$massage_text_none));

        test::clean();

    }
}