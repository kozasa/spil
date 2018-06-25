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
     * 挿入
     *
     * @param array $info
     * @return void
     */
    public function insert(array $info){
        
        $sql = 'INSERT INTO event_participants (event_id,member_id,join_flag,new_flag,new_name,new_gender,new_age,created_at,updated_at) VALUES (:event_id," ",1,1,:new_name,:new_gender,:new_age,NOW(),NOW())';

        $query = $this->db->prepare($sql);
        $query->bindParam(':event_id', $info['join_day'], \PDO::PARAM_STR);
        $query->bindParam(':new_name', $info['name'], \PDO::PARAM_STR);
        $query->bindParam(':new_gender', $info['gender'], \PDO::PARAM_INT);
        $query->bindParam(':new_age', $info['age'], \PDO::PARAM_INT);
        $query->execute();

        return true;
    }
}