<?php
namespace Classes\Mapper\UserMst;

use PHPUnit\Framework\TestCase;
use Classes\Mapper\UserMst;
use AspectMock\Test as test;
use Tests\Classes as Base;


class UserMstDataTest extends Base\BaseTestCase
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

    // データ作成
    private $data = array(
        'id' => 'idxxx',
        'user_id' => 'namexxx',
        'display_name' => 'passwordxxx',
        'picture_url' => 'pictureurlxxx',
    );

    /**
     *
     * @group usermst
     */
    public function test__construct(){
        // データ作成
        $object = new UserMst\UserMstData($this->data);

        // プライベート変数取得
        $reflectionClass = new \ReflectionClass($object);
        $id = $reflectionClass->getProperty('id');
        $id->setAccessible(true);
        
        $reflectionClass = new \ReflectionClass($object);
        $user_id = $reflectionClass->getProperty('user_id');
        $user_id->setAccessible(true);

        $reflectionClass = new \ReflectionClass($object);
        $display_name = $reflectionClass->getProperty('display_name');
        $display_name->setAccessible(true);

        $reflectionClass = new \ReflectionClass($object);
        $picture_url = $reflectionClass->getProperty('picture_url');
        $picture_url->setAccessible(true);

        // id
        $idValue = $id->getValue($object);
        $this->assertEquals($idValue,$this->data['id']);

        $userIdValue = $user_id->getValue($object);
        $this->assertEquals($userIdValue,$this->data['user_id']);

        $displayName = $display_name->getValue($object);
        $this->assertEquals($displayName,$this->data['display_name']);

        $pictureUrl = $picture_url->getValue($object);
        $this->assertEquals($pictureUrl,$this->data['picture_url']);
        
    }

    /**
     *
     * @group usermst
     */
    public function testgetId(){
        $object = new UserMst\UserMstData($this->data);

        $this->assertEquals($object->getId(),$this->data['id']);
    }

    /**
     *
     * @group usermst
     */
    public function testgetUserId(){
        $object = new UserMst\UserMstData($this->data);

        $this->assertEquals($object->getUserId(),$this->data['user_id']);

    }

    /**
     *
     * @group usermst
     */
    public function testgetDisplayName(){
        $object = new UserMst\UserMstData($this->data);

        $this->assertEquals($object->getDisplayName(),$this->data['display_name']);

    }
    
    /**
     *
     * @group usermst
     */
    public function testgetPicturUrl(){
        $object = new UserMst\UserMstData($this->data);

        $this->assertEquals($object->getPictureUrl(),$this->data['picture_url']);

    }
    
}