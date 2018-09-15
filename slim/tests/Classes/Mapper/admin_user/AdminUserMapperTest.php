<?php
namespace Tests\Classes\Mapper\AdminUser;

use PHPUnit\Framework\TestCase;
use Classes\Mapper\AdminUser;
use AspectMock\Test as test;
use Tests\Classes as Base;

class AdminUserMapperTest extends Base\BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->container['db']->beginTransaction();

        // 元のデータを全て削除
        $this->container['db']->query(
            "DELETE FROM `admin_user`"
        );
    }
    protected function tearDown()
    {
        parent::tearDown();
        $this->container['db']->rollback(); // 元に戻す

        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group map
     */
    public function testselectFromName(){
        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `admin_user`( `id`,`name`, `password`) 
            VALUES (null,'テスト太郎selectFromName','1122334455')"
        );
        $stmt = $this->container['db']->query("SELECT * FROM `admin_user` WHERE name = 'テスト太郎selectFromName'");
        $select = $stmt->fetch();

        // メソッド実行
        $object = new AdminUser\AdminUserMapper($this->container['db']);
        $result = $object->selectFromName('テスト太郎AAAAAAAAAAAAAA');

        // 検証 0件
        $this->assertEquals($result, false);

        // 検証 存在
        $array = array(
            'id' => $select['id'],
            'name' => 'テスト太郎selectFromName',
            'password' => '1122334455',
        );
        $testResult = new AdminUser\AdminUserData($array);

        $result = $object->selectFromName('テスト太郎selectFromName');

        $this->assertEquals($testResult, $result);
    }
}