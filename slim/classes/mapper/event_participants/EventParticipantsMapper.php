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
    public function getEventIdAndJoin(string $event_id,int $join_flag){

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
}