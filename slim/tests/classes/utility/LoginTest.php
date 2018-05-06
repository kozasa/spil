<?php 
namespace Tests\Classes\Utility;

use PHPUnit\Framework\TestCase;
use Classes\Utility;
use AspectMock\Test as test;

class LoginTest extends TestCase
{
    protected function tearDown()
    {
        test::clean(); // 登録したテストダブルをすべて削除
    }
    
    /**
     * @group utility
     */
    public function testisCheck(){

        /**
         * user入力チェックでエラー
         */
        $non_user = "";
        $password = "1111";
        $result = Utility\Login::isCheck(null,$non_user,$password,$error_msg);
        $this->assertEquals(false,$result);
        $this->assertEquals("IDを確認してください",$error_msg);

        /**
         * password入力チェックでエラー
         */
        $user = "user01";
        $non_password = "";
        $result = Utility\Login::isCheck(null,$user,$non_password,$error_msg);
        $this->assertEquals(false,$result);
        $this->assertEquals("パスワードを確認してください",$error_msg);

        /**
         * ユーザ検索後取得できない場合
         */
        // mock作成　nullが返るように設定
        $mock = test::double('\Classes\Mapper\LoginMapper', ['getUserInfo' => []]);

        $user = "user01";
        $password = "1111";
        $result = Utility\Login::isCheck(null,$user,$password,$error_msg);
        $this->assertEquals(false,$result);
        $this->assertEquals("IDを確認してください",$error_msg);

        test::clean();

        /**
         * パスワード一致判定でOK
         */
        // mock作成　パスワードが返るように設定
        $password_hash = password_hash( "test", PASSWORD_DEFAULT);
        $password = "test";
        $mock = test::double('\Classes\Mapper\LoginMapper', 
            ['getUserInfo' => array('password' => $password_hash)]
        );
        $func = test::func('Classes\Utility', 'session_regenerate_id', true);
        
        $result = Utility\Login::isCheck(null,$user,$password,$error_msg);

        $this->assertEquals(true,$result);
        $this->assertEquals("",$error_msg);

        test::clean();

        /**
         * パスワード一致判定でNG
         */
        $non_password_hash = "ng";
        $password = "test";
        $mock = test::double('\Classes\Mapper\LoginMapper', 
            ['getUserInfo' => array('password' => $non_password_hash)]
        );
        $func = test::func('Classes\Utility', 'session_regenerate_id', true);

        $result = Utility\Login::isCheck(null,$user,$password,$error_msg);

        $this->assertEquals(false,$result);
        $this->assertEquals("IDまたはパスワードを確認してください",$error_msg);

        test::clean();
    }

    /**
     * @group utility
     */
    public function testisCheckAfter(){
        $this->assertEquals(true,Utility\Login::isCheckAfter("user01"));
        $this->assertEquals(false,Utility\Login::isCheckAfter(null));
    }
}