<?php
namespace Tests\Classes\Model;

use PHPUnit\Framework\TestCase;
use Classes\Model;
use Classes\Mapper\Event;
use Classes\Mapper\AdminUser;
use AspectMock\Test as test;
use Tests\Classes as Base;

class LoginModelTest extends Base\BaseTestCase
{
    protected function tearDown()
    {
        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group model
     */
    /*
    public function testgetPassword(){

//
//         * 情報が取得できた場合
//         


        $mock1 = test::double('\Classes\Mapper\AdminUser\AdminUserMapper', ['selectFromName' => function($arg){
            if($arg==='name'){
                $array = array(
                    'id' => 1,
                    'name' => 'name',
                    'password' => 'password',
                );
                $data = new AdminUser\AdminUserData($array);

                return $data;
            }else{
                throw exception;
            }
        }]);

        // 実行
        $object1 = new Model\LoginModel($this->container['db']);
        $result = $object1->getPassword('name');

        $this->assertEquals('password', $result);

        test::clean(); // 登録したテストダブルをすべて削除
//
//         * 情報が取得できない場合
//         

        $mock1 = test::double('\Classes\Mapper\AdminUser\AdminUserMapper', ['selectFromName' => function($arg){
            if($arg==='name'){
                return false;
            }else{
                throw exception;
            }
        }]);

        // 実行
        $object1 = new Model\LoginModel($this->container['db']);
        $result = $object1->getPassword('name');

        $this->assertEquals(false, $result);

        test::clean(); // 登録したテストダブルをすべて削除
    }
    */

    /**
     * @group model
     */
    
    public function testLoginConfirmation(){

        $mock1 = test::double('\Classes\Mapper\AdminUser\AdminUserMapper', ['isUserId' => function($arg){
            if($arg==='name'){
            
                return true;
            }else{
                throw exception;
            }
        }]);

        // 実行
        $object1 = new Model\LoginModel($this->container['db']);
        $result = $object1->loginConfirmation('name');

        // 検証
        $this->assertEquals(true, $result);

    }

}