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
    public function testeventEditListGet(){
        // ログイン認証NG
        $this->loginNG('GET','/admin/eventedit/');

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
        
        $mock2 = test::double('\Classes\Mapper\EventEditMapper', ['getEventList' => 
            array( 
                '2018年' => array(
                    '8月' =>array(
                        0 => array('event_id' => "b000004",
                            'title' => "バドミントン１面",
                            'place' => "富田地区会館",
                            'date' => '25',
                            'week' => '水',
                            'start_time' => '13:00',
                            'end_time' => '18:00',
                        ),
                        1 => array(
                            'event_id' => "b000005",
                            'title' => "バドミントン１面",
                            'place' => "富田地区会館",
                            'date' => '25',
                            'week' => '水',
                            'start_time' => '13:00',
                            'end_time' => '18:00',
                        ),
                    ),
                    '9月' =>array(
                        0 => array('event_id' => "b000006",
                            'title' => "バドミントン１面",
                            'place' => "富田地区会館",
                            'date' => '25',
                            'week' => '水',
                            'start_time' => '13:00',
                            'end_time' => '18:00',
                        ),
                    ),
                ),
                '2019年' => array(
                    '11月' => array(
                        0 => array('event_id' => "b000007",
                                'title' => "バドミントン１面",
                                'place' => "富田地区会館",
                                'date' => '25',
                                'week' => '水',
                                'start_time' => '13:00',
                                'end_time' => '18:00',
                        ),
                    ),
                ),
            ),
        ]);

        $response = $this->runApp('GET', '/admin/eventedit/');
        
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());
        // タイトル確認
        $this->assertContains('イベント情報編集', (string)$response->getBody());
        // 出力確認
        $this->assertContains('2018年', (string)$response->getBody());
        $this->assertContains('2019年', (string)$response->getBody());
        $this->assertContains('８月', (string)$response->getBody());
        $this->assertContains('９月', (string)$response->getBody());
        $this->assertContains('１１月', (string)$response->getBody());
        $this->assertContains('b000004', (string)$response->getBody());
        $this->assertContains('b000005', (string)$response->getBody());
        $this->assertContains('b000006', (string)$response->getBody());
        $this->assertContains('b000007', (string)$response->getBody());
    }

    /**
     * @group controller
     */
    public function testeventEditGet(){
        // ログイン認証NG
        $this->loginNG('GET','/admin/eventedit/update/b00004');

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

        $event_id = 'b000004';
        $mock1 = test::double('\Classes\Mapper\EventEditMapper', ['getEventFromId' => function($arg){
            if($arg === 'b000004'){
                return array(
                    'event_id' => 'b000004',
                    'title' => "バドミントン１面",
                    'place' => "富田地区会館",
                    'date' => '2018-06-18',
                    'week' => '水',
                    'fee' => '500',
                    'start_time' => '13:00',
                    'end_time' => '18:00',
                );
            }else{
                return false;
            }
        }]);

        $response = $this->runApp('GET', '/admin/eventedit/update/b000004');
        
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());
        // タイトル確認
        $this->assertContains('イベント情報編集', (string)$response->getBody());
        // 出力確認
        $this->assertContains('2018-06-18', (string)$response->getBody());
    }

    /**
     * @group controller
     */
    public function testeventEditPost(){
        // ログイン認証NG
        $this->loginNG('POST','/admin/eventedit/update/b000004',array());

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

        $mock3 = test::double('\Classes\Mapper\EventEditMapper', ['updateEvent' => function($arg){
            if($arg['event_id'] === 'b000004'){
                return true;
            }else{
                return false;
            }
        }]);
        
        $mock4 = test::double('\Classes\Utility\LineBotMassage', ['push_event_edit_info' => function($arg0,$arg1){
            if($arg0['event_id'] === 'b000004'){
                return "message";
            }else{
                throw exception;
            }
        }]); 
        $mock5 = test::double('\Classes\Utility\LineBotPush', ['push' => function($arg0){
            if($arg0 === 'message'){
                return true;
            }else{
                throw exception;
            }
        }]); 
        
        $post_data = array(
            'title' => "バドミントン１面",
            'place' => "富田地区会館",
            'date' => '2018-06-18',
            'week' => '水',
            'fee' => '500',
            'start_time' => '13:00',
            'end_time' => '18:00',
        );
        $response = $this->runApp('POST', '/admin/eventedit/update/b000004',$post_data );
        // リダイレクト
        $this->assertEquals(302, $response->getStatusCode());
        // リダイレクト先取得
        $header = $response->getHeaders();
        $this->assertContains('../', (string)$header['Location'][0]);

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

        $mock2 = test::double('\Classes\Mapper\EventEditMapper', ['updateEvent' => false]);
        
        $response = $this->runApp('POST', '/admin/eventedit/update/b000004',$post_data);
        // ページが正常動作の場合は200となる
        $this->assertEquals(200, $response->getStatusCode());
        // タイトル確認
        $this->assertContains('イベント情報編集', (string)$response->getBody());

        test::clean();
    }

    /**
     * @group controller
     */
    public function testeventDeleteGet(){
        // ログイン認証NG
        $this->loginNG('GET','/admin/eventedit/delete/b00004');

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

        $mock2 = test::double('\Classes\Mapper\EventEditMapper', ['deleteEvent' => function($arg){
            if($arg === 'b000004'){
                return true;
            }else{
                throw exception;
            }
        }]); 
        $mock3 = test::double('\Classes\Mapper\EventEditMapper', ['getEventFromId' => function($arg){
            if($arg === 'b000004'){
                return array(
                    'event_id' => 'b000004',
                    'title' => "バドミントン１面",
                    'place' => "富田地区会館",
                    'date' => '2018-06-18',
                    'week' => '水',
                    'fee' => '500',
                    'start_time' => '13:00',
                    'end_time' => '18:00',
                );
            }else{
                throw exception;
            }
        }]); 

        $response = $this->runApp('GET', '/admin/eventedit/delete/b000004');
        // リダイレクトのため302となる
        $this->assertEquals(302, $response->getStatusCode());
        // リダイレクト先取得
        $header = $response->getHeaders();
        $this->assertContains('../', (string)$header['Location'][0]);

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

        $mock2 = test::double('\Classes\Mapper\EventEditMapper', ['deleteEvent' => false]);
        
        $response = $this->runApp('GET', '/admin/eventedit/delete/b000004');
        // リダイレクトのため302となる
        $this->assertEquals(302, $response->getStatusCode());
        // リダイレクト先取得
        $header = $response->getHeaders();
        $this->assertContains('../', (string)$header['Location'][0]);

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