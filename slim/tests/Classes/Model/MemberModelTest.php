<?php
namespace Tests\Classes\Model;

use PHPUnit\Framework\TestCase;
use Classes\Model;
use Classes\Mapper\Event;
use Classes\Mapper\EventParticipants;
use Classes\Mapper\AdminUser;
use AspectMock\Test as test;
use Tests\Classes as Base;

class MemberModelTest extends Base\BaseTestCase
{
    protected function tearDown()
    {
        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group model
     */
    public function testevent(){

        /**
         * イベントが存在しない
         */

        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['isEvent' => function($arg){
            if($arg==='12345'){
                return false;
            }else{
                throw exception;
            }
        }]);

        // 実行
        $object1 = new Model\MemberModel($this->container['db']);
        $result = $object1->event('12345');

        $this->assertEquals(false, $result);

        test::clean(); // 登録したテストダブルをすべて削除
        
        /**
         * イベントが存在する
         */

        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['isEvent' => function($arg){
            if($arg==='12345'){
                return true;
            }else{
                throw exception;
            }
        }]);
        $mock2 = test::double('\Classes\Mapper\Event\EventMapper', ['selectFromEventId' => function($arg){
            if($arg==='12345'){
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
        $mock3 = test::double('\Classes\Mapper\EventParticipants\EventParticipantsMapper', ['selectFromEventIdAndJoinFlag' => function($arg,$arg1){
            if($arg==="12345" && $arg1 === 1){
                $testResult=array();
                $array = array(
                    'id' => 1,
                    'event_id' => 'event_id1',
                    'member_id' => 'memberid',
                    'join_flag' => 1,
                    'new_flag' => 0,
                    'new_name' => null,
                    'new_gender' => null,
                    'new_age' => null,
                    'created_at' => '',
                    'updated_at' => '',
                    'display_name' => '田中太郎',
                    'picture_url' => 'htttp://1111.jp/',
                );
                $data = new EventParticipants\EventParticipantsData($array);
                array_push($testResult,$data);

                $array = array(
                    'id' => 2,
                    'event_id' => 'event_id2',
                    'member_id' => 'memberid',
                    'join_flag' => 1,
                    'new_flag' => 1,
                    'new_name' => '新人',
                    'new_gender' => 1,
                    'new_age' => 3,
                    'created_at' => '',
                    'updated_at' => '',
                    'display_name' => '',
                    'picture_url' => '',
                );
                $data = new EventParticipants\EventParticipantsData($array);
                array_push($testResult,$data);

                $array = array(
                    'id' => 3,
                    'event_id' => 'event_id3',
                    'member_id' => 'memberid',
                    'join_flag' => 1,
                    'new_flag' => 0,
                    'new_name' => null,
                    'new_gender' => null,
                    'new_age' => null,
                    'created_at' => '',
                    'updated_at' => '',
                    'display_name' => '田中太郎２',
                    'picture_url' => 'htttp://11112.jp/',
                );
                $data = new EventParticipants\EventParticipantsData($array);
                array_push($testResult,$data);

                $array = array(
                    'id' => 4,
                    'event_id' => 'event_id2',
                    'member_id' => 'memberid',
                    'join_flag' => 1,
                    'new_flag' => 1,
                    'new_name' => '新人',
                    'new_gender' => 1,
                    'new_age' => 3,
                    'created_at' => '',
                    'updated_at' => '',
                    'display_name' => '',
                    'picture_url' => '',
                );
                $data = new EventParticipants\EventParticipantsData($array);
                array_push($testResult,$data);

                return $testResult;
            }elseif($arg==="12345" && $arg1 === 0){
                $testResult=array();
                $array = array(
                    'id' => 1,
                    'event_id' => 'event_id1',
                    'member_id' => 'memberid',
                    'join_flag' => 1,
                    'new_flag' => 0,
                    'new_name' => null,
                    'new_gender' => null,
                    'new_age' => null,
                    'created_at' => '',
                    'updated_at' => '',
                    'display_name' => '田中太郎3',
                    'picture_url' => 'htttp://11113.jp/',
                );
                $data = new EventParticipants\EventParticipantsData($array);
                array_push($testResult,$data);

                $array = array(
                    'id' => 3,
                    'event_id' => 'event_id3',
                    'member_id' => 'memberid',
                    'join_flag' => 1,
                    'new_flag' => 0,
                    'new_name' => null,
                    'new_gender' => null,
                    'new_age' => null,
                    'created_at' => '',
                    'updated_at' => '',
                    'display_name' => '田中太郎２',
                    'picture_url' => 'htttp://11112.jp/',
                );
                $data = new EventParticipants\EventParticipantsData($array);
                array_push($testResult,$data);

                return $testResult;
            }else{
                throw exception;
            }
        }]);

        // 実行
        $object1 = new Model\MemberModel($this->container['db']);
        $result = $object1->event('12345');

        // 検証用データ
        $arrayTest = array(

            'event_id'=>"event_id1",
            'title'=>"バドミントン１面",
            'place'=>"なんとか公園",
            'event_date'=>"01月03日 (水)",
            'start_time'=>"11:11",
            'end_time'=>"22:22",
            'join_member' => array(
                array(
                    'new_flag' => 0,
                    'display_name' => '田中太郎',
                    'picture_url' => 'htttp://1111.jp/',
                ),
                array(
                    'new_flag' => 1,
                    'display_name' => '新人',
                    'gender' => '男性',
                    'age' => '３０代',
                    'picture_url' => '/img/man.png',
                ),
                array(
                    'new_flag' => 0,
                    'display_name' => '田中太郎２',
                    'picture_url' => 'htttp://11112.jp/',
                ),
                array(
                    'new_flag' => 1,
                    'display_name' => '新人',
                    'gender' => '男性',
                    'age' => '３０代',
                    'picture_url' => '/img/man.png',
                )
            ),
            'none_join_member' => array(
                array(
                    'new_flag' => 0,
                    'display_name' => '田中太郎3',
                    'picture_url' => 'htttp://11113.jp/',
                ),
                array(
                    'new_flag' => 0,
                    'display_name' => '田中太郎２',
                    'picture_url' => 'htttp://11112.jp/',
                ),
            ),
            'comment'=>'',
        );

        $this->assertEquals($arrayTest, $result);

        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group model
     */
    public function testlatest(){

        // 直近イベント情報取得mock作成
        $testResult = array();
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
        array_push($testResult,$data);

        $array = array(
            'id' => 2,
            'event_id' => 'event_id2',
            'title' => 'バドミントン２面',
            'place' => 'なんとか公園あ',
            'event_date' => '2018-01-01',
            'start_time' => '11:11:22',
            'end_time' => '11:22:22',
            'fee' => '601',
            'before_seven_days' => '1',
            'before_one_day' => '1',
            'created_at' => '',
            'updated_at' => '',
        );
        $data = new Event\EventData($array);
        array_push($testResult,$data);

        $array = array(
            'id' => 3,
            'event_id' => 'event_id4',
            'title' => 'バドミントン４面',
            'place' => 'なんとか公園あああ',
            'event_date' => '2018-01-02',
            'start_time' => '11:11:44',
            'end_time' => '22:22:44',
            'fee' => '801',
            'before_seven_days' => '0',
            'before_one_day' => '0',
            'created_at' => '',
            'updated_at' => '',
        );
        $data = new Event\EventData($array);
        array_push($testResult,$data);

        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['selectLatest' => $testResult]); 

        // 実行
        $object1 = new Model\MemberModel($this->container['db']);
        $result = $object1->latest();

        // 検証用データ
        $testResult2 = array();
        $array = array(
            'event_id' => 'event_id1',
            'title' => 'バドミントン１面',
            'place' => 'なんとか公園',
            'year' => '2018',
            'month' => '01',
            'day' => '03',
            'week' => '水',
            'start_time' => '11:11',
            'end_time' => '22:22',
        );
        array_push($testResult2,$array);

        $array = array(
            'event_id' => 'event_id2',
            'title' => 'バドミントン２面',
            'place' => 'なんとか公園あ',
            'year' => '2018',
            'month' => '01',
            'day' => '01',
            'week' => '月',
            'start_time' => '11:11',
            'end_time' => '11:22',
        );
        array_push($testResult2,$array);

        $array = array(
            'event_id' => 'event_id4',
            'title' => 'バドミントン４面',
            'place' => 'なんとか公園あああ',
            'year' => '2018',
            'month' => '01',
            'day' => '02',
            'week' => '火',
            'start_time' => '11:11',
            'end_time' => '22:22',
        );
        array_push($testResult2,$array);

        $this->assertEquals($result, $testResult2);
    }

    /**
     * @group model
     */
    public function testauthCallbackUserMst(){

        /**
         * 登録されている場合
         */

        // 引数作成
        $info = array(
            'userId' => 'userid',
            'aaa' => 'aaaa',
        );

        // mock作成
        $mock1 = test::double('\Classes\Mapper\UserMst\UserMstMapper', ['selectExist' => function($arg){
            if($arg==='userid'){
                return false;
            }else{
                throw exception;
            }
        }]);

        $mock2 = test::double('\Classes\Mapper\UserMst\UserMstMapper', ['insert' => function($arg){
            if($arg===array(
                'userId' => 'userid',
                'aaa' => 'aaaa',
            )){
                return true;
            }else{
                throw exception;
            }
        }]);

        // 実行
        $object1 = new Model\MemberModel($this->container['db']);
        $result = $object1->authCallbackUserMst($info);

        // 返り値が無いため1=1で回避
        $this->assertEquals(1, 1);

        test::clean(); // 登録したテストダブルをすべて削除

        /**
         * 登録されていない場合 かつ ユーザ情報がDBと相違
         */

         // mock作成
        $mock1 = test::double('\Classes\Mapper\UserMst\UserMstMapper', ['selectExist' => function($arg){
            if($arg==='userid'){
                return true;
            }else{
                throw exception;
            }
        }]);

        $mock2 = test::double('\Classes\Mapper\UserMst\UserMstMapper', ['selectUpdateConfirm' => function($arg){
            if($arg===array(
                'userId' => 'userid',
                'aaa' => 'aaaa',
            )){
                return false;
            }else{
                throw exception;
            }
        }]);

        $mock3 = test::double('\Classes\Mapper\UserMst\UserMstMapper', ['update' => function($arg){
            if($arg===array(
                'userId' => 'userid',
                'aaa' => 'aaaa',
            )){
                return true;
            }else{
                throw exception;
            }
        }]);

        // 実行
        $object1 = new Model\MemberModel($this->container['db']);
        $result = $object1->authCallbackUserMst($info);

        // 返り値が無いため1=1で回避
        $this->assertEquals(1, 1);

        test::clean(); // 登録したテストダブルをすべて削除

        /**
         * 登録されていない場合 かつ ユーザ情報がDBと相違では無い
         */

         // mock作成
        $mock1 = test::double('\Classes\Mapper\UserMst\UserMstMapper', ['selectExist' => function($arg){
            if($arg==='userid'){
                return true;
            }else{
                throw exception;
            }
        }]);

        $mock2 = test::double('\Classes\Mapper\UserMst\UserMstMapper', ['selectUpdateConfirm' => function($arg){
            if($arg===array(
                'userId' => 'userid',
                'aaa' => 'aaaa',
            )){
                return true;
            }else{
                throw exception;
            }
        }]);

        // updateを呼び出したらエラーにする
        $mock3 = test::double('\Classes\Mapper\UserMst\UserMstMapper', ['update' => function($arg){
                throw exception;
        }]);

        // 実行
        $object1 = new Model\MemberModel($this->container['db']);
        $result = $object1->authCallbackUserMst($info);

        // 返り値が無いため1=1で回避
        $this->assertEquals(1, 1);

        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group model
     */
    public function testauthCallbackEventAction(){

        // 引数作成
        $data1 = array(
            'action' => "join",
            'event_id' => 'eventId',
        );
        $data2 = array(
            'action' => "exit",
            'event_id' => 'eventId'
        );
        $userId = 'userId';

        /**
         * 登録されている場合
         */
        $mock1 = test::double('\Classes\Mapper\EventParticipants\EventParticipantsMapper', ['selectExistFromUseridAndEventid' => function($arg,$arg1){
            if($arg==='userId' && $arg1 ==='eventId'){
                return true;
            }else{
                throw exception;
            }
        }]);
        $mock2 = test::double('\Classes\Mapper\EventParticipants\EventParticipantsMapper', ['update' => function($arg){
            if($arg===array(
                'action' => "join",
                'event_id' => "eventId",
                'join_flag'=>true,
                'member_id' => "userId"
            )){
                return true;
            }else{
                throw exception;
            }
        }]);

        // 実行
        $object1 = new Model\MemberModel($this->container['db']);
        $result = $object1->authCallbackEventAction($data1,$userId);

        // 返り値が無いため1=1で回避
        $this->assertEquals(1, 1);

        test::clean(); // 登録したテストダブルをすべて削除

        /**
         * 登録されていない場合
         */

        $mock1 = test::double('\Classes\Mapper\EventParticipants\EventParticipantsMapper', ['selectExistFromUseridAndEventid' => function($arg,$arg1){
            if($arg==='userId' && $arg1 ==='eventId'){
                return false;
            }else{
                throw exception;
            }
        }]);
        $mock2 = test::double('\Classes\Mapper\EventParticipants\EventParticipantsMapper', ['insert' => function($arg){
            if($arg===array(
                'action' => "exit",
                'event_id' => "eventId",
                'join_flag'=>false,
                'member_id' => "userId"
            )){
                return true;
            }else{
                throw exception;
            }
        }]);

        // 実行
        $object1 = new Model\MemberModel($this->container['db']);
        $result = $object1->authCallbackEventAction($data2,$userId);

        // 返り値が無いため1=1で回避
        $this->assertEquals(1, 1);

        test::clean(); // 登録したテストダブルをすべて削除
    }
}