<?php
namespace Classes\Mapper\Event;

class EventMapper extends \Classes\Mapper\Mapper
{
    /**
     * 現在日時以降のイベント情報をイベントの日程順で取得
     * （当日を含む）
     *
     * @return array
     */
    public function getLatestInfo(){
        $sql = 'SELECT * FROM `event` WHERE event_date >= CURRENT_DATE() order by event_date';
        $query = $this->db->prepare($sql);
        $query->execute();

        $result = array();
        // 取得したデータをデータクラスに格納する
        while($row = $query -> fetch()){
            // データクラス生成
            $data = new EventData($row);
            // 返り値の配列にpush
            array_push($result,$data);
        }

        // pushした配列を返す　array(0->eventData 1->eventData)
        return $result;
    }

    /**
     * イベントIDからイベント情報を取得
     *
     * @return 
     */
    public function getEventIdInfo(string $event_id){
        $sql = 'SELECT * FROM `event` WHERE event_id = :event_id';
        $query = $this->db->prepare($sql);
        $query->bindParam(':event_id', $event_id, \PDO::PARAM_STR);
        $query->execute();

        $result = new EventData($query -> fetch());
        return $result;
    }

    /**
     * イベントが存在するか
     *
     * @param string $event_id
     * @return boolean
     */
    public function isEvent(string $event_id){
        $sql = 'SELECT * FROM `event` WHERE event_id = :event_id';
        $query = $this->db->prepare($sql);
        $query->bindParam(':event_id', $event_id, \PDO::PARAM_STR);
        $query->execute();

        if($query->rowCount()>0){
            return true;
        }
        return false;
    }

    /**
     * 一番最新の情報取得
     *
     * @return EventData
     */
    public function selectLatest(){
        $sql = 'SELECT * FROM event WHERE event_id = (select max(event_id) from event )';
        $query = $this->db->prepare($sql);
        $query->execute();

        $result = new EventData($query -> fetch());
        return $result;
    }

    /**
     * 挿入
     *
     * @param array $info
     * @return void
     */
    public function insert(array $info){

        $sql = 'INSERT INTO event (event_id,title,place,event_date,start_time,end_time,fee,before_seven_days,before_one_day,created_at,updated_at) 
            VALUES (:event_id,:title,:place,:event_date,:start_time,:end_time,:fee,0,0,NOW(),NOW())';

        $query = $this->db->prepare($sql);
        $query->bindParam(':event_id', $info['event_id'], \PDO::PARAM_STR);
        $query->bindParam(':title', $info['title'], \PDO::PARAM_STR);
        $query->bindParam(':place', $info['place'], \PDO::PARAM_STR);
        $query->bindParam(':event_date', $info['event_date'], \PDO::PARAM_STR);
        $query->bindParam(':start_time', $info['start_time'], \PDO::PARAM_STR);
        $query->bindParam(':end_time', $info['end_time'], \PDO::PARAM_STR);
        $query->bindParam(':fee', $info['fee'], \PDO::PARAM_INT);
        $query->execute();

        return true;

    }
}