<?php
namespace Classes\Mapper\UserMst;

class UserMstMapper extends \Classes\Mapper\Mapper
{
    
    /**
     * 更新確認
     * 更新されている（ID,ディスプレイ名,画像URLが同一のものがある）場合はtrue
     * そうでない場合はfalse
     *
     * @param array $info
     * @return bool
     */
    public function selectUpdateConfirm(array $info){

        $sql = 'SELECT * FROM user_mst 
                WHERE user_id = :user_id and display_name = :display_name 
                and picture_url = :picture_url';
        $querySelect = $this->db->prepare($sql);

        $querySelect->bindValue(':user_id', $info["userId"], \PDO::PARAM_STR);
        $querySelect->bindValue(':display_name', $info["displayName"], \PDO::PARAM_STR);
        $querySelect->bindValue(':picture_url', $info["pictureUrl"], \PDO::PARAM_STR);
        $querySelect->execute();
        if($querySelect->rowCount() === 0){
            return false;
        }
        return true;
    }

    /**
     * ユーザIDからセレクト
     *
     * @param string $userId
     * @return void
     */
    public function selectExist(string $userId){

        $sql = 'SELECT * FROM user_mst WHERE user_id = :user_id';
        $query = $this->db->prepare($sql);
        $query->bindParam(':user_id', $userId, \PDO::PARAM_STR);
        $query->execute();

        //取得件数が0件の場合、falseを返す
        if($query->rowCount()==0){
            return false;
        }
        
        return true;
    }

    /**
     * 挿入
     *
     * @param array $info
     * @return void
     */
    public function insert(array $info){

        $sql = 'INSERT INTO user_mst (id,user_id,display_name,picture_url,insert_date,delete_flg) 
                VALUES (:id, :user_id,:display_name,:picture_url,:insert_date,:delete_flg)';
        $queryInsert = $this->db->prepare($sql);

        $queryInsert->bindValue(':id', null, \PDO::PARAM_INT);
        $queryInsert->bindValue(':user_id', $info["userId"], \PDO::PARAM_STR);
        $queryInsert->bindValue(':display_name', $info["displayName"], \PDO::PARAM_STR);
        $queryInsert->bindValue(':picture_url', $info["pictureUrl"], \PDO::PARAM_STR);
        $queryInsert->bindValue(':insert_date', date("Y/m/d H:i:s"), \PDO::PARAM_STR);
        $queryInsert->bindValue(':delete_flg', '0', \PDO::PARAM_STR);
        $queryInsert->execute();

    }

    /**
     * 更新
     *
     * @param array $info
     * @return void
     */
    public function update(array $info){

        $sql = 'UPDATE user_mst SET display_name = :display_name, picture_url =:picture_url WHERE user_id = :user_id';
        $queryUpdate = $this->db->prepare($sql);

        $queryUpdate->bindValue(':user_id', $info["userId"], \PDO::PARAM_STR);
        $queryUpdate->bindValue(':display_name', $info["displayName"], \PDO::PARAM_STR);
        $queryUpdate->bindValue(':picture_url', $info["pictureUrl"], \PDO::PARAM_STR);
        $queryUpdate->execute();
    }



}