<?php
namespace Tests\Classes\Controller;

use PHPUnit\Framework\TestCase;
use Classes\Controller;
use AspectMock\Test as test;
use Tests\Classes as Base;

class AdminControllerTest extends Base\BaseTestCase
{
    protected function tearDown()
    {
        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group controller
     */
    public function testtopGet(){

        $response = $this->runApp('GET', '/admin/');
        
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());
        // タイトル確認
        $this->assertContains('スピル管理者ログインページ', (string)$response->getBody());
    }

    /**
     * @group controller
     */
    public function testtopPost(){

        /**
         * ログインOK
         */
        $mock1 = test::double('\Classes\Utility\Login', ['isCheck' => true]); 
        $response = $this->runApp('POST', '/admin/',array('user' => 'aaa', 'password' => 'bbb'));

        // リダイレクトのため302となる
        $this->assertEquals(302, $response->getStatusCode());
        // リダイレクト先取得
        $header = $response->getHeaders();
        $this->assertContains('../admin/menu/', (string)$header['Location'][0]);

        test::clean();
        /**
         * ログインNG
         */
        $mock1 = test::double('\Classes\Utility\Login', ['isCheck' => false]); 
        $response = $this->runApp('POST', '/admin/',array('user' => 'aaa', 'password' => 'bbb'));
        
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());
        // タイトル確認
        $this->assertContains('スピル管理者ログインページ', (string)$response->getBody());

        test::clean();
    }

    /**
     * @group controller
     */
    public function testmenuGet(){
        
        // ログイン認証NG
        $this->loginNG('GET','/admin/menu/');

        /**
         * ログイン認証OK
         */
        $mock1 = test::double('\Classes\Utility\Login', ['isCheckAfter' => function($arg){
            if($arg === 'aaa'){
                return true;
            }else{
                return false;
            }
        }]); 
        $response = $this->runApp('GET', '/admin/menu/',array('user'=>'aaa'));
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());
        // タイトル確認
        $this->assertContains('スピル管理者メニュー', (string)$response->getBody());

        test::clean();

    }

    /**
     * @group controller
     */
    public function testeventPostGet(){
        // ログイン認証NG
        $this->loginNG('GET','/admin/eventpost/');

        /**
         * ログイン認証OK
         */
        $mock1 = test::double('\Classes\Utility\Login', ['isCheckAfter' => function($arg){
            if($arg === 'aaa'){
                return true;
            }else{
                return false;
            }
        }]); 
        $response = $this->runApp('GET', '/admin/eventpost/',array('user'=>'aaa'));
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());
        // タイトル確認
        $this->assertContains('イベント情報投稿', (string)$response->getBody());

        test::clean();

    }

    /**
     * @group controller
     */
    public function testeventPostPost(){
        // ログイン認証NG
        $this->loginNG('POST','/admin/eventpost/');

        /**
         * ログイン認証OK 
         * DB挿入OK
         */
        $mock1 = test::double('\Classes\Utility\Login', ['isCheckAfter' => function($arg){
            if($arg === 'aaa'){
                return true;
            }else{
                return false;
            }
        }]); 
        $mock2 = test::double('\Classes\Mapper\EventPostMapper', ['insertEventPost' => function($arg){
            if($arg['post'] === 'post'){
                return true;
            }else{
                return false;
            }
        }]); 
        $mock3 = test::double('\Classes\Utility\LineBotMassage', ['push_event_info' => 'massage']); 
        $mock4 = test::double('\Classes\Utility\LineBotPush', ['push' => function($arg){
            if($arg === 'massage'){
                return true;
            }else{
                throw exception;
            }
        }]); 

        $response = $this->runApp('POST', '/admin/eventpost/',array('post'=>'post'));
        // リダイレクト
        $this->assertEquals(302, $response->getStatusCode());
        // リダイレクト先取得
        $header = $response->getHeaders();
        $this->assertContains('../menu/', (string)$header['Location'][0]);

        test::clean();

        /**
         * ログイン認証OK 
         * DB挿入NG
         */
        $mock1 = test::double('\Classes\Mapper\EventPostMapper', ['insertEventPost' => false]);
        $response = $this->runApp('POST', '/admin/eventpost/');
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());
        // タイトル確認
        $this->assertContains('イベント情報投稿', (string)$response->getBody());
        // 出力内容確認
        $this->assertContains('投稿処理に失敗しました。入力内容を確認してください。', (string)$response->getBody());

        test::clean();
    }

    /**
     * @group controller
     */
    public function testnewPostGet(){
        // ログイン認証NG
        $this->loginNG('GET','/admin/newpost/');

        /**
         * ログイン認証OK 
         */
        $mock1 = test::double('\Classes\Mapper\LatestMapper', ['getLatestInfo' => array(
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
        $response = $this->runApp('GET', '/admin/newpost/');
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());
        // タイトル確認
        $this->assertContains('新規者登録', (string)$response->getBody());
        // 出力内容確認
        $this->assertContains('2018-04-25', (string)$response->getBody());
    }

    /**
     * @group controller
     */
    public function testnewPostPost(){
        // ログイン認証NG
        $this->loginNG('POST','/admin/newpost/');

        /**
         * DB挿入成功
         */
        $mock1 = test::double('\Classes\Utility\Login', ['isCheckAfter' => function($arg){
            if($arg === 'aaa'){
                return true;
            }else{
                return false;
            }
        }]); 
        $mock2 = test::double('\Classes\Mapper\NewPostMapper', ['insertNewRegistant' => function($arg){
            if($arg['post'] === 'post'){
                return true;
            }else{
                return false;
            }
        }]); 
        $mock3 = test::double('\Classes\Mapper\LatestMapper', ['getLatestInfo' => array(0 =>
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
        $mock4 = test::double('\Classes\Utility\LineBotMassage', ['push_new_info' => function($arg0,$arg1){
            if($arg1 === '2018-04-25'){
                return "message";
            }else{
                return "xxxxx";
            }
        }]); 
        $mock5 = test::double('\Classes\Utility\LineBotPush', ['push' => function($arg0){
            if($arg0 === 'message'){
                return true;
            }else{
                throw exception;
            }
        }]); 

        $response = $this->runApp('POST', '/admin/newpost/',array('post'=>'post','user'=>'aaa','join_day'=>'b000004'));
        // リダイレクト
        $this->assertEquals(302, $response->getStatusCode());
        // リダイレクト先取得
        $header = $response->getHeaders();
        $this->assertContains('../menu/', (string)$header['Location'][0]);

        test::clean();

        /**
         * DB挿入失敗
         */
        $mock1 = test::double('\Classes\Utility\Login', ['isCheckAfter' => function($arg){
            if($arg === 'aaa'){
                return true;
            }else{
                return false;
            }
        }]); 
        $mock2 = test::double('\Classes\Mapper\NewPostMapper', ['insertNewRegistant' => false]); 
        $mock3 = test::double('\Classes\Mapper\LatestMapper', ['getLatestInfo' => array(0 =>
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

        $response = $this->runApp('POST', '/admin/newpost/',array('post'=>'post','user'=>'aaa','join_day'=>'b000004'));

        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());
        // タイトル確認
        $this->assertContains('新規者登録', (string)$response->getBody());
        // 出力内容確認
        $this->assertContains('投稿処理に失敗しました。入力内容を確認してください。', (string)$response->getBody());

        test::clean();

    }

    /**
     * @group controller
     */
    public function testlogout(){
        // ログイン認証NG
        $this->loginNG('GET','/admin/logout/');

        /**
         * ログイン認証OK
         */
        $response = $this->runApp('GET', '/admin/logout/');
        // リダイレクト
        $this->assertEquals(302, $response->getStatusCode());
        // リダイレクト先取得
        $header = $response->getHeaders();
        $this->assertContains('../../admin/', (string)$header['Location'][0]);
        // セッションクリア確認
        $this->assertEmpty($_SESSION);

        test::clean();
    }

    /**
     * @group controller
     */
    public function testpush(){
        /**
         * key失敗
         */
        $response = $this->runApp('GET', '/push/'."aaa");
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());

        /**
         * key ok イベント情報false
         */
        $mock1 = test::double('\Classes\Mapper\PushMapper', ['getPushInfo' => false]);
        ob_start();
        $response = $this->runApp('GET', '/push/'.PUSH_KEY);
        ob_get_clean();
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());

        test::clean();

        /**
         * key ok イベント情報true
         */
        $mock1 = test::double('\Classes\Mapper\PushMapper', ['getPushInfo' => true]);
        $mock2 = test::double('\Classes\Utility\LineBotPush', ['pushCron' => function($arg){
            if($arg===true){
                return true;
            }else{
                throw exception;
            }
        }]);

        ob_start();
        $response = $this->runApp('GET', '/push/'.PUSH_KEY);
        ob_get_clean();

        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());

        test::clean();
    }

    /**
     * @group controller
     */
    public function testlatestpush(){
        /**
         * key失敗
         */
        $response = $this->runApp('GET', '/latestpush/'."aaa");
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());

        /**
         * key ok イベント情報 日付違い
         */
        $mock1 = test::double('\Classes\Mapper\LatestMapper', ['getLatestInfo' => array(0 => array('event_date' => '1111-11-11'))]);
        ob_start();
        $response = $this->runApp('GET', '/latestpush/'.PUSH_KEY);
        ob_get_clean();
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());

        test::clean();

        /**
         * key ok イベント情報true
         */
        $mock1 = test::double('\Classes\Mapper\LatestMapper', ['getLatestInfo' => array(0 => array('event_date' => date("Y-m-d")))]);
        $mock2 = test::double('\Classes\Utility\LineBotMassage', ['push_latest_message' => function($arg){
            if($arg[0]['event_date']===date("Y-m-d")){
                return true;
            }else{
                throw exception;
            }
        }]);
        $mock3 = test::double('\Classes\Utility\LineBotPush', ['push' => function($arg){
            if($arg===true){
                return true;
            }else{
                throw exception;
            }
        }]);

        ob_start();
        $response = $this->runApp('GET', '/latestpush/'.PUSH_KEY);
        ob_get_clean();

        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());

        test::clean();
    }

    /**
     * ログインNGテスト処理
     *
     * @param [type] $path
     * @return void
     */
    private function loginNG($http,$path){

        /**
         * ログイン認証NG
         */
        $mock1 = test::double('\Classes\Utility\Login', ['isCheckAfter' => false]); 
        $response = $this->runApp($http, $path,array('user'=>'aaa'));
        // リダイレクトのため302となる
        $this->assertEquals(302, $response->getStatusCode());
        // リダイレクト先取得
        $header = $response->getHeaders();
        $this->assertContains('../admin/', (string)$header['Location'][0]);

        test::clean();
    }
}