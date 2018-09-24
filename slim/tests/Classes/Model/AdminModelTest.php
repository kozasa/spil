<?php
namespace Tests\Classes\Model;

use PHPUnit\Framework\TestCase;
use Classes\Model;
use Classes\Mapper\Event;
use AspectMock\Test as test;
use Tests\Classes as Base;

class AdminModelTest extends Base\BaseTestCase
{
    protected function tearDown()
    {
        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group model
     */
    public function testeventPostPost(){

        /**
         * 入力情報の確認でエラー
         */
        $mock1 = test::double('\Classes\Model\AdminModel', ['checkEventPost' => function($arg){
            if($arg===array('aa')){
                return false;
            }else{
                throw exception;
            }
        }]);

        $object1 = new Model\AdminModel($this->container['db']);
        $result = $object1->eventPostPost(array('aa'));

        $this->assertEquals(false, $result);

        test::clean();
        

        /**
         * 通常処理
         */
        $mock1 = test::double('\Classes\Model\AdminModel', ['checkEventPost' => function($arg){
            if($arg===array('aa')){
                return true;
            }else{
                throw exception;
            }
        }]);
        $array = array(
            'id' => 1,
            'event_id' => 'event_id1',
            'title' => 'バドミントン１面',
            'place' => 'なんとか公園',
            'event_date' => '',
            'start_time' => '11:11:11',
            'end_time' => '22:22:22',
            'fee' => '501',
            'before_seven_days' => '0',
            'before_one_day' => '0',
            'comment' => 'test',
            'created_at' => '',
            'updated_at' => '',
        );
        $mock2 = test::double('\Classes\Mapper\Event\EventMapper', ['selectLatestPiece' => new Event\EventData($array)]);
        $mock3 = test::double('\Classes\Mapper\Event\EventMapper', ['insert' => function($arg){
            if($arg===array('aa','event_id' => 'b000002')){
                return true;
            }else{
                throw exception;
            }
        }]);

        $result = $object1->eventPostPost(array('aa'));
    }

    /**
     * @group model
     */
    public function testeventEditListGet(){

        /**
         * 通常処理
         */

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
        
        $object1 = new Model\AdminModel($this->container['db']);
        $result = $object1->eventEditListGet();

        //検証用データ
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

        $this->assertEquals($testResult2, $result);
    }

    /**
     * @group model
     */
    public function testeventEditGet(){

        /**
         * 通常処理
         */

        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['selectFromEventId' => function($arg){
            if($arg==='event_id1'){
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
                return new Event\EventData($array);
            }else{
                throw exception;
            }
        }]); 
        
        $object1 = new Model\AdminModel($this->container['db']);
        $result = $object1->eventEditGet('event_id1');

        //検証用データ
        $array = array(
            'event_id' => 'event_id1',
            'title' => 'バドミントン１面',
            'place' => 'なんとか公園',
            'date' => '2018-01-03',
            'week' => '水',
            'fee' => '501',
            'start_time' => '11:11',
            'end_time' => '22:22',
        );

        $this->assertEquals($result, $array);
    }

    /**
     * @group model
     */
    public function testeventEditPost(){

        /**
         * 入力情報の確認でエラー
         */
        $mock1 = test::double('\Classes\Model\AdminModel', ['checkEventPost' => function($arg){
            if($arg===array('aa')){
                return false;
            }else{
                throw exception;
            }
        }]);
        $object1 = new Model\AdminModel($this->container['db']);
        $result = $object1->eventEditPost(array('aa'));

        $this->assertEquals(false, $result);

        test::clean(); // 登録したテストダブルをすべて削除

        /**
         * 通常処理
         */
        $mock1 = test::double('\Classes\Model\AdminModel', ['checkEventPost' => function($arg){
            if($arg===array('aa')){
                return true;
            }else{
                throw exception;
            }
        }]);

        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['update' => function($arg){
            if($arg===array('aa')){
                return 'b0000001';
            }else{
                throw exception;
            }
        }]);

        $object1 = new Model\AdminModel($this->container['db']);
        $result = $object1->eventEditPost(array('aa'));

        $this->assertEquals('b0000001', $result);

    }

    /**
     * @group model
     */
    public function testnewPostGet(){

        /**
         * 通常処理
         */
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
            'comment' => 'test',
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
            'comment' => 'test',
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
            'comment' => 'test',
            'created_at' => '',
            'updated_at' => '',
        );
        $data = new Event\EventData($array);
        array_push($testResult,$data);
        
        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['selectLatest' => $testResult]); 
        
        $object1 = new Model\AdminModel($this->container['db']);
        $result = $object1->newPostGet();

        //検証用データ
        $testResult2 = array();
        $array = array(
            'event_id' => 'event_id1',
            'week' => '水',
            'event_date' => '2018-01-03',
        );
        array_push($testResult2,$array);

        $array = array(
            'event_id' => 'event_id2',
            'week' => '月',
            'event_date' => '2018-01-01',
        );
        array_push($testResult2,$array);

        $array = array(
            'event_id' => 'event_id4',
            'week' => '火',
            'event_date' => '2018-01-02',
        );
        array_push($testResult2,$array);

        $this->assertEquals($result, $testResult2);
    }

    /**
     * @group model
     */
    public function testnewPostPost(){

        /**
         * 入力情報の確認でエラー
         */
        $mock1 = test::double('\Classes\Model\AdminModel', ['checkRegistantPost' => function($arg){
            if($arg===array('aa')){
                return false;
            }else{
                throw exception;
            }
        }]);
        $object1 = new Model\AdminModel($this->container['db']);
        $result = $object1->newPostPost(array('aa'));

        $this->assertEquals(false, $result);

        test::clean(); // 登録したテストダブルをすべて削除

        /**
         * DB挿入で失敗した場合
         */

        $arrayNewMember = array(
            'new_name' => '田中太郎',
            'new_gender' => 1,
            'new_age' => 3,
            'event_id' => '2018-01-03',
        );
    
        // DB挿入mock作成
        $mock1 = test::double('\Classes\Mapper\EventParticipants\EventParticipantsMapper', ['insert' => function($arg){
            if($arg === array(
                'new_name' => '田中太郎',
                'new_gender' => 1,
                'new_age' => 3,
                'event_id' => '2018-01-03',
                'member_id' => '',
                'join_flag' => true,
                'new_flag' => true,
            )){
                return false;
            }else{
                throw exception;
            }
        }]);

        $object1 = new Model\AdminModel($this->container['db']);
        $result = $object1->newPostPost($arrayNewMember);

        $this->assertEquals(false, $result);
        
        test::clean(); // 登録したテストダブルをすべて削除

        /**
         * 通常処理
         */
        $arrayNewMember = array(
            'new_name' => '田中太郎',
            'new_gender' => 1,
            'new_age' => 3,
            'event_id' => '2018-01-03',
        );
    
        // DB挿入mock作成
        $mock1 = test::double('\Classes\Mapper\EventParticipants\EventParticipantsMapper', ['insert' => function($arg){
            if($arg === array(
                'new_name' => '田中太郎',
                'new_gender' => 1,
                'new_age' => 3,
                'event_id' => '2018-01-03',
                'member_id' => '',
                'join_flag' => true,
                'new_flag' => true,
            )){
                return true;
            }else{
                throw exception;
            }
        }]);

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
        $mock2 = test::double('\Classes\Mapper\Event\EventMapper', ['selectLatest' => $testResult]); 

        $object1 = new Model\AdminModel($this->container['db']);
        $result = $object1->newPostPost($arrayNewMember);

        //検証用データ
        $testResult2 = array();
        $array = array(
            'event_id' => 'event_id1',
            'week' => '水',
            'event_date' => '2018-01-03',
        );
        array_push($testResult2,$array);

        $array = array(
            'event_id' => 'event_id2',
            'week' => '月',
            'event_date' => '2018-01-01',
        );
        array_push($testResult2,$array);

        $array = array(
            'event_id' => 'event_id4',
            'week' => '火',
            'event_date' => '2018-01-02',
        );
        array_push($testResult2,$array);

        $this->assertEquals($result, $testResult2);
    }

    /**
     * @group model
     */
    public function testpush(){

        /**
         * １日前の情報が取得できた場合
         */

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
        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['isBeforeDaysInfo' => $data]); 

        $mock2 = test::double('\Classes\Mapper\Event\EventMapper', ['updateFlag' => function($arg,$arg1){
            if($arg === 'event_id1' && $arg1 === 1){

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

        $object1 = new Model\AdminModel($this->container['db']);
        $result = $object1->push();

        // 比較データ
        $testResult = array(
            'event_id' => 'event_id1',
            'title' => 'バドミントン１面',
            'place' => 'なんとか公園',
            'event_date' => '01月03日 (水)',
            'start_time' => '11:11',
            'end_time' => '22:22',
            'fee' => '501',
            'day' => 1,
        );

        $this->assertEquals($result, $testResult);

        test::clean(); // 登録したテストダブルをすべて削除
        /**
         * １日前の情報が取得できない　かつ　当日にイベントがある場合
         */
        
        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['isBeforeDaysInfo' => false]);

        $mock2 = test::double('\Classes\Mapper\Event\EventMapper', ['isEventToday' => true]);

        $object1 = new Model\AdminModel($this->container['db']);
        $result = $object1->push();

        $this->assertEquals($result, false);

        test::clean(); // 登録したテストダブルをすべて削除
        /**
         * １日前の情報が取得できない　かつ　当日にイベントがない　かつ　７日前の情報が取得できた場合
         */
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

        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['isBeforeDaysInfo' => function($arg){
            if($arg === 7){
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
            }else if($arg === 1){
                return false;
            }else{
                throw exception;
            }
        }]);

        $mock2 = test::double('\Classes\Mapper\Event\EventMapper', ['updateFlag' => function($arg,$arg1){
            if($arg === 'event_id1' && $arg1 === 7){

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

        $object1 = new Model\AdminModel($this->container['db']);
        $result = $object1->push();

        // 比較データ
        $testResult = array(
            'event_id' => 'event_id1',
            'title' => 'バドミントン１面',
            'place' => 'なんとか公園',
            'event_date' => '01月03日 (水)',
            'start_time' => '11:11',
            'end_time' => '22:22',
            'fee' => '501',
            'day' => 7,
        );

        $this->assertEquals($result, $testResult);

        test::clean(); // 登録したテストダブルをすべて削除
        /**
         * どちらにも該当しない場合
         */

        $mock1 = test::double('\Classes\Mapper\Event\EventMapper', ['isBeforeDaysInfo' => false]);

        $mock2 = test::double('\Classes\Mapper\Event\EventMapper', ['isEventToday' => false]);

        $object1 = new Model\AdminModel($this->container['db']);
        $result = $object1->push();

        $this->assertEquals($result, false);

        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group model
     */
    public function testlatestpush(){

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
        $object1 = new Model\AdminModel($this->container['db']);
        $result = $object1->latestpush();

        // 検証データ
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
            'event_date' => '2018-01-03',
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
            'event_date' => '2018-01-01',
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
            'event_date' => '2018-01-02',
        );
        array_push($testResult2,$array);

        $this->assertEquals($result, $testResult2);

    }

    /**
     * @group model
     */
    public function testcheckEventPost(){

        $object = new Model\AdminModel($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkEventPost');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする

        /**
         * チェックOK
         */

        $info = array(
            'fee' => 300,
            'title' => 'title',
            'place' => 'place',
            'event_date' => '11-22-11',
            'start_time' => '22:11:22',
            'end_time' => '44:33:22'
        );

        // 実行
        $result = $method->invoke($object,$info);

        $this->assertEquals($result, true);

        /**
         * チェックNG
         */

        $info = array(
            'title' => 'title',
            'place' => 'place',
            'event_date' => '11-22-11',
            'start_time' => '22:11:22',
            'end_time' => '44:33:22'
        );

        // 実行
        $result = $method->invoke($object,$info);

        $this->assertEquals($result, false);

        $info = array(
            'fee' => 300,
            'place' => 'place',
            'event_date' => '11-22-11',
            'start_time' => '22:11:22',
            'end_time' => '44:33:22'
        );

        // 実行
        $result = $method->invoke($object,$info);

        $this->assertEquals($result, false);

        $info = array(
            'fee' => 300,
            'title' => 'title',
            'event_date' => '11-22-11',
            'start_time' => '22:11:22',
            'end_time' => '44:33:22'
        );

        // 実行
        $result = $method->invoke($object,$info);

        $this->assertEquals($result, false);

        $info = array(
            'fee' => 300,
            'title' => 'title',
            'place' => 'place',
            'start_time' => '22:11:22',
            'end_time' => '44:33:22'
        );

        // 実行
        $result = $method->invoke($object,$info);

        $this->assertEquals($result, false);

        $info = array(
            'fee' => 300,
            'title' => 'title',
            'place' => 'place',
            'event_date' => '11-22-11',
            'end_time' => '44:33:22'
        );

        // 実行
        $result = $method->invoke($object,$info);

        $this->assertEquals($result, false);

        $info = array(
            'fee' => 300,
            'title' => 'title',
            'place' => 'place',
            'event_date' => '11-22-11',
            'start_time' => '22:11:22',
        );

        // 実行
        $result = $method->invoke($object,$info);

        $this->assertEquals($result, false);

        $info = array(
            'fee' => '',
            'title' => '',
            'place' => '',
            'event_date' => '',
            'start_time' => '',
            'end_time' => ''
        );

        // 実行
        $result = $method->invoke($object,$info);

        $this->assertEquals($result, false);
    }

    /**
     * @group model
     */
    public function testcheckRegistantPost(){

        $object = new Model\AdminModel($this->container['db']);
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod('checkRegistantPost');
        $method->setAccessible(true);                   // privateメソッドを実行できるようにする

        /**
         * チェックOK
         */

        $info = array(
            'new_name' => 'name',
            'new_gender' => 2,
            'new_age' => 4,
            'event_id' => 't11111'
        );

        // 実行
        $result = $method->invoke($object,$info);

        $this->assertEquals($result, true);

        /**
         * チェックNG
         */

        $info = array(
            'gender' => 2,
            'age' => 4,
            'join_day' => '11-22-11'
        );

        // 実行
        $result = $method->invoke($object,$info);

        $this->assertEquals($result, false);

        $info = array(
            'name' => 'name',
            'age' => 4,
            'join_day' => '11-22-11'
        );

        // 実行
        $result = $method->invoke($object,$info);

        $this->assertEquals($result, false);

        $info = array(
            'name' => 'name',
            'gender' => 2,
            'join_day' => '11-22-11'
        );

        // 実行
        $result = $method->invoke($object,$info);

        $this->assertEquals($result, false);

        $info = array(
            'name' => 'name',
            'gender' => 2,
            'age' => 4,
        );

        // 実行
        $result = $method->invoke($object,$info);

        $this->assertEquals($result, false);

        $info = array(
            'name' => '',
            'gender' => null,
            'age' => null,
            'join_day' => ''
        );

        // 実行
        $result = $method->invoke($object,$info);

        $this->assertEquals($result, false);

    }
}