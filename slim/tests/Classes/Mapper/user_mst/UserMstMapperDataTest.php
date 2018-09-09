<?php
namespace Classes\Mapper\UserMst;

use PHPUnit\Framework\TestCase;
use Classes\Mapper\UserMst;
use AspectMock\Test as test;
use Tests\Classes as Base;

class UserMstMapperDataTest extends Base\BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->container['db']->beginTransaction();

        // 元のデータを全て削除
        $this->container['db']->query(
            "DELETE FROM `user_mst`"
        );

        // auto_increment初期化
        $this->container['db']->query(
            "ALTER TABLE `user_mst` AUTO_INCREMENT = 1"
        );
        
    }
    protected function tearDown()
    {
        parent::tearDown();
        $this->container['db']->rollback(); // 元に戻す

        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     *
     * @group usermst
     */
    public function testselectUpdateConfirm(){
        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `user_mst`(`id`, `user_id`, `display_name`, `picture_url`, `insert_date`) 
            VALUES (null,'user_id1','display_name1','picture_url1',CURRENT_DATE())"
        );

        // データ作成
        $data1 = array(
            'userId' => 'user_id1',
            'displayName' => 'display_name1',
            'pictureUrl' => 'picture_url1',
        );
        $data2 = array(
            'userId' => 'namexxx',
            'displayName' => 'passwordxxx',
            'pictureUrl' => 'pictureurlxxx',
        );

        // メソッド実行
        $object = new UserMst\UserMstMapper($this->container['db']);
        $result = $object->selectUpdateConfirm($data1);
        $this->assertEquals(true, $result);
        $result = $object->selectUpdateConfirm($data2);
        $this->assertEquals(false, $result);
    }

    /**
     *
     * @group usermst
     */
    public function testselectExist(){
        // テストデータ投稿
        $this->container['db']->query(
            "INSERT INTO `user_mst`(`id`, `user_id`, `display_name`, `picture_url`, `insert_date`) 
            VALUES (null,'user_id1','display_name1','picture_url1',CURRENT_DATE())"
        );
        
        // データ作成
        $id1 = 'user_id1';

        $id2 = 'user_id2';

        // メソッド実行
        $object = new UserMst\UserMstMapper($this->container['db']);
        $result = $object->selectExist($id1);
        $this->assertEquals(true, $result);
        $result = $object->selectExist($id2);
        $this->assertEquals(false, $result);

    }

    /**
     *
     * @group usermst
     */
    public function testinsert(){
        $info = array(
            'userId' => 'user_id1',
            'displayName' => 'displayName1',
            'pictureUrl' => 'picture-url1'
        );
        $object = new UserMst\UserMstMapper($this->container['db']);
        $object->insert($info);
        
        $sql = 'SELECT * FROM user_mst WHERE user_id = :userId and display_name = :displayName and picture_url = :pictureUrl';
        $query = $this->container['db']->prepare($sql);
        $query->bindParam(':userId', $info['userId'], \PDO::PARAM_STR);
        $query->bindParam(':displayName', $info['displayName'], \PDO::PARAM_STR);
        $query->bindParam(':pictureUrl', $info['pictureUrl'], \PDO::PARAM_STR);
        $query->execute();

        $result = $query->rowCount()==1;

        $this->assertEquals(true, $result);

    }

    /**
     *
     * @group usermst
     */
    public function testupdate(){
        // 更新前テストデータ
        $info1 = array(
            'userId' => 'user_id',
            'displayName' => 'displayName1',
            'pictureUrl' => 'picture-url1'
        );
        $sql = 'INSERT INTO user_mst (id,user_id,display_name,picture_url,insert_date,delete_flg) 
                VALUES (:id, :user_id,:display_name,:picture_url,:insert_date,:delete_flg)';
        $queryInsert = $this->container['db']->prepare($sql);

        $queryInsert->bindValue(':id', null, \PDO::PARAM_INT);
        $queryInsert->bindValue(':user_id', $info1["userId"], \PDO::PARAM_STR);
        $queryInsert->bindValue(':display_name', $info1["displayName"], \PDO::PARAM_STR);
        $queryInsert->bindValue(':picture_url', $info1["pictureUrl"], \PDO::PARAM_STR);
        $queryInsert->bindValue(':insert_date', date("Y/m/d H:i:s"), \PDO::PARAM_STR);
        $queryInsert->bindValue(':delete_flg', '0', \PDO::PARAM_STR);
        $queryInsert->execute();

        // 更新後テストデータ
        $info2 = array(
            'userId' => 'user_id',
            'displayName' => 'displayName2',
            'pictureUrl' => 'picture-url2'
        );

        $object = new UserMst\UserMstMapper($this->container['db']);
        $object->update($info2);
        
        // 更新データ確認
        $sql = 'SELECT * FROM user_mst WHERE user_id = :userId and display_name = :displayName and picture_url = :pictureUrl';
        $query = $this->container['db']->prepare($sql);
        $query->bindParam(':userId', $info2['userId'], \PDO::PARAM_STR);
        $query->bindParam(':displayName', $info2['displayName'], \PDO::PARAM_STR);
        $query->bindParam(':pictureUrl', $info2['pictureUrl'], \PDO::PARAM_STR);
        $query->execute();

        $result = $query->rowCount()==1;

        $this->assertEquals(true, $result);
    }

}