<?php
namespace Tests\Classes\Mapper;

use PHPUnit\Framework\TestCase;
use Classes\Mapper;
use AspectMock\Test as test;
use Tests\Classes as Base;

class LoginMapperTest extends Base\BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->container['db']->beginTransaction();
    }
    protected function tearDown()
    {
        parent::tearDown();
        $this->container['db']->rollback(); // 元に戻す

        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group mapper
     */
    public function testgetUserInfo(){

        // テストデータ作成
        $this->container['db']->query(
            "INSERT INTO `admin_user`(`id`, `name`, `password`) VALUES ('0','田中太郎','password')"
        );

        // メソッド実行
        // true
        $object = new Mapper\LoginMapper($this->container['db']);
        $result = $object->getUserInfo('田中太郎');
        $resultTest = array(
            'id'=>$result['id'],
            'name' => '田中太郎',
            'password' => 'password',
        );

        $this->assertEquals($result,$resultTest);

        // false
        $object = new Mapper\LoginMapper($this->container['db']);
        $result = $object->getUserInfo('田中');

        $this->assertFalse($result);
    }
}