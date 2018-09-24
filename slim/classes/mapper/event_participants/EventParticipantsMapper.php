<?php
namespace Classes\Mapper\EventParticipants;

class EventParticipantsMapper extends \Classes\Mapper\Mapper
{
    /**
     * イベントIDから情報取得
     *
     * @param string $event_id
     * @param int $join_flag
     * @return 
     */
    public function selectFromEventIdAndJoinFlag(string $event_id,int $join_flag){

        $sql = 'SELECT * FROM event_participants 
                LEFT JOIN user_mst ON event_participants.member_id = user_id
                WHERE event_id = :event_id AND join_flag = :join_flag';
        $query = $this->db->prepare($sql);
        $query->bindParam(':event_id', $event_id, \PDO::PARAM_STR);
        $query->bindParam(':join_flag', $join_flag, \PDO::PARAM_INT);
        $query->execute();

        $result = array();
        // 取得したデータをデータクラスに格納する
        while($row = $query -> fetch()){
            // データクラス生成
            $data = new EventParticipantsData($row);
            // 返り値の配列にpush
            array_push($result,$data);
        }

        // pushした配列を返す
        return $result;

    }
    
    /**
     * 存在確認（ユーザIDとイベントIDから）
     *
     * @param string $userId
     * @param string $eventId
     * @return bool
     */
    public function selectExistFromUseridAndEventid(string $userId,string $eventId){

        $sql = 'SELECT * FROM event_participants WHERE member_id = :user_id AND event_id = :event_id';
        $select = $this->db->prepare($sql);
        $select->bindValue(':user_id', $userId, \PDO::PARAM_STR);
        $select->bindValue(':event_id', $eventId, \PDO::PARAM_STR);
        $select->execute();

        if($select->rowCount() === 0 )
        {
            return false;
        }
        return true;
    }

    /**
     * 挿入（既存メンバー）
     *
     * @param array $info
     * @return bool
     */
    public function insert(array $info){

        $sql = 'INSERT INTO event_participants (id,event_id,member_id,join_flag,new_flag,new_name,new_gender,new_age,created_at,updated_at) 
                    VALUES (:id, :event_id,:member_id,:join_flag,:new_flag,"",0,0,:created_at,:updated_at)';
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':id', null, \PDO::PARAM_INT);
            $stmt->bindValue(':event_id', $info["event_id"], \PDO::PARAM_STR);
            $stmt->bindValue(':member_id', $info["member_id"], \PDO::PARAM_STR);
            $stmt->bindValue(':join_flag', $info["join_flag"], \PDO::PARAM_INT);
            $stmt->bindValue(':new_flag', false, \PDO::PARAM_INT);
            $stmt->bindValue(':created_at', date("Y/m/d H:i:s"), \PDO::PARAM_STR);
            $stmt->bindValue(':updated_at', date("Y/m/d H:i:s"), \PDO::PARAM_STR);
            $stmt->execute();

        return true;
    }

    /**
     * 挿入(新規ユーザー)
     *
     * @param array $info
     * @return void
     */
    public function insertNewUser(array $info){
        
        $sql = 'INSERT INTO event_participants (event_id,member_id,join_flag,new_flag,new_name,new_gender,new_age,created_at,updated_at) 
        VALUES (:event_id," ",1,1,:new_name,:new_gender,:new_age,NOW(),NOW())';

        $query = $this->db->prepare($sql);
        $query->bindParam(':event_id', $info['event_id'], \PDO::PARAM_STR);
        $query->bindParam(':new_name', $info['new_name'], \PDO::PARAM_STR);
        $query->bindParam(':new_gender', $info['new_gender'], \PDO::PARAM_INT);
        $query->bindParam(':new_age', $info['new_age'], \PDO::PARAM_INT);
        $query->execute();

        return true;
    }

    /**
     * 更新
     *
     * @param array $info
     * @return void
     */
    public function update(array $info){

        $sql = 'UPDATE event_participants SET join_flag = :join_flag, updated_at =:updated_at 
                WHERE member_id = :member_id AND event_id = :event_id';
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':join_flag', $info["join_flag"], \PDO::PARAM_INT);
        $stmt->bindValue(':member_id', $info["member_id"], \PDO::PARAM_STR);
        $stmt->bindValue(':event_id',  $info["eventId"], \PDO::PARAM_STR);
        $stmt->bindValue(':updated_at', date("Y/m/d H:i:s"), \PDO::PARAM_STR);
        
        $stmt->execute();
    }
}