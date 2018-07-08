<?php
namespace Classes\Mapper;

class AuthCallbackMapper extends Mapper
{

    /**
     * ユーザ情報の登録
     * @param array $info
     */
    public function insertUserMst(array $info) {

        // ユーザ情報が登録されているか確認
        $sql = 'SELECT * FROM user_mst WHERE user_id = :user_id';
        $query = $this->db->prepare($sql);
        $query->bindParam(':user_id', $info['userId'], \PDO::PARAM_STR);
        $query->execute();

        if($query->rowCount() === 0 )
        {
            // 登録されていない場合はinsert
            $sql = 'INSERT INTO user_mst (id,user_id,display_name,picture_url,insert_date,delete_flg) VALUES (:id, :user_id,:display_name,:picture_url,:insert_date,:delete_flg)';
            $queryInsert = $this->db->prepare($sql);

            $queryInsert->bindValue(':id', null, \PDO::PARAM_INT);
            $queryInsert->bindValue(':user_id', $info["userId"], \PDO::PARAM_STR);
            $queryInsert->bindValue(':display_name', $info["displayName"], \PDO::PARAM_STR);
            $queryInsert->bindValue(':picture_url', $info["pictureUrl"], \PDO::PARAM_STR);
            $queryInsert->bindValue(':insert_date', date("Y/m/d H:i:s"), \PDO::PARAM_STR);
            $queryInsert->bindValue(':delete_flg', '0', \PDO::PARAM_STR);
            $queryInsert->execute();
        }
        else
        {
            // 登録されている場合は、更新確認
            $sql = 'SELECT * FROM user_mst WHERE user_id = :user_id and display_name = :display_name and picture_url = :picture_url';
            $querySelect = $this->db->prepare($sql);

            $querySelect->bindValue(':user_id', $info["userId"], \PDO::PARAM_STR);
            $querySelect->bindValue(':display_name', $info["displayName"], \PDO::PARAM_STR);
            $querySelect->bindValue(':picture_url', $info["pictureUrl"], \PDO::PARAM_STR);
            $querySelect->execute();
            if($querySelect->rowCount() === 0)
            {
                // 変化がある場合は更新処理
                $sql = 'UPDATE user_mst SET display_name = :display_name, picture_url =:picture_url WHERE user_id = :user_id';
                $queryUpdate = $this->db->prepare($sql);

                $queryUpdate->bindValue(':user_id', $info["userId"], \PDO::PARAM_STR);
                $queryUpdate->bindValue(':display_name', $info["displayName"], \PDO::PARAM_STR);
                $queryUpdate->bindValue(':picture_url', $info["pictureUrl"], \PDO::PARAM_STR);
                $queryUpdate->execute();
            }
        }
    }

    /**
     * イベント参加、不参加登録
     * @param array $info
     */
    public function insertEventAction(array $data,string $userId) {

        // 参加、不参加
        $joinFlg = false;
        if($data['action']=="join"){
            $joinFlg = true;
        }

        // イベント情報テーブルに登録されているか確認
        $sql = 'SELECT * FROM event_participants WHERE member_id = :user_id AND event_id = :event_id';
        $select = $this->db->prepare($sql);
        $select->bindValue(':user_id', $userId, \PDO::PARAM_STR);
        $select->bindValue(':event_id', $data["eventId"], \PDO::PARAM_STR);
        $select->execute();

        if($select->rowCount() === 0 )
        {
            // 登録されていない場合、INSERT処理
            $sql = 'INSERT INTO event_participants (id,event_id,member_id,join_flag,new_flag,new_name,new_gender,new_age,created_at,updated_at) 
                    VALUES (:id, :event_id,:member_id,:join_flag,:new_flag,"",0,0,:created_at,:updated_at)';
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':id', null, \PDO::PARAM_INT);
            $stmt->bindValue(':event_id', $data["eventId"], \PDO::PARAM_STR);
            $stmt->bindValue(':member_id', $userId, \PDO::PARAM_STR);
            $stmt->bindValue(':join_flag', $joinFlg, \PDO::PARAM_INT);
            $stmt->bindValue(':new_flag', false, \PDO::PARAM_INT);
            $stmt->bindValue(':created_at', date("Y/m/d H:i:s"), \PDO::PARAM_STR);
            $stmt->bindValue(':updated_at', date("Y/m/d H:i:s"), \PDO::PARAM_STR);
            $stmt->execute();

        }else{
            // 登録されている場合、UPDATE処理
            $sql = 'UPDATE event_participants SET join_flag = :join_flag, updated_at =:updated_at 
                    WHERE member_id = :member_id AND event_id = :event_id';
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':join_flag', $joinFlg, \PDO::PARAM_INT);
            $stmt->bindValue(':updated_at', date("Y/m/d H:i:s"), \PDO::PARAM_STR);
            $stmt->bindValue(':member_id', $userId, \PDO::PARAM_STR);
            $stmt->bindValue(':event_id', $data["eventId"], \PDO::PARAM_STR);
            $stmt->execute();
        }
    }
}