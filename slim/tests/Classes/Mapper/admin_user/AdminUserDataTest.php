<?php
namespace Tests\Classes\Mapper\admin_user;

use PHPUnit\Framework\TestCase;
use Classes\Mapper\AdminUser;
use AspectMock\Test as test;
use Tests\Classes as Base;

class AdminUserDataTest extends Base\BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }
    protected function tearDown()
    {
        parent::tearDown();

        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group data
     */
    public function test__construct(){

        // データ作成
        $data = array(
            'id' => 'idxxx',
            'name' => 'namexxx',
            'user_id' => 'passwordxxx',
        );
        $object = new AdminUser\AdminUserData($data);
        
        // プライベート変数取得
        $reflectionClass = new \ReflectionClass($object);
        $id = $reflectionClass->getProperty('id');
        $id->setAccessible(true);
        $name = $reflectionClass->getProperty('name');
        $name->setAccessible(true);
        $userId = $reflectionClass->getProperty('userId');
        $userId->setAccessible(true);

        // id
        $idValue = $id->getValue($object);
        $this->assertEquals($idValue,$data['id']);

        // name
        $nameValue = $name->getValue($object);
        $this->assertEquals($nameValue,$data['name']);

        // user_id
        $userIdValue = $userId->getValue($object);
        $this->assertEquals($userIdValue,$data['user_id']);

    }

    /**
     *
     * @group data
     */
    public function testgetName(){

        // データ作成
        $data = array(
            'id' => 'idxxx',
            'name' => 'namexxx',
            'user_id' => 'passwordxxx',
        );
        $object = new AdminUser\AdminUserData($data);

        $this->assertEquals($object->getName(),$data['name']);

    }

    /**
     *
     * @group data
     */
    public function testgetUserId(){

        // データ作成
        $data = array(
            'id' => 'idxxx',
            'name' => 'namexxx',
            'user_id' => 'passwordxxx',
        );
        $object = new AdminUser\AdminUserData($data);

        $this->assertEquals($object->getUserId(),$data['user_id']);

    }
}