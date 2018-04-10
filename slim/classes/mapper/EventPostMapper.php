<?php
namespace Classes\Mapper;

class EventPostMapper extends Mapper
{

    /**
     * イベント情報を挿入
     * @param array $info
     */
    public function insertEventPost($info) {

        // 入力情報の確認
        if(!$this->checkInfo($info)){
            return false;
        }

        // イベント番号を決定
        $max_id = $this->getMaxId();
        $event_id = 'b' . str_pad($max_id + 1, 6, 0, STR_PAD_LEFT);

        $sql = 'INSERT INTO event (event_id,title,place,event_date,start_time,end_time,fee,before_seven_days,before_one_day,created_at,updated_at) VALUES (:event_id,:title,:place,:event_date,:start_time,:end_time,:fee,0,0,NOW(),NOW())';

        $query = $this->db->prepare($sql);
        $query->bindParam(':event_id', $event_id, \PDO::PARAM_STR);
        $query->bindParam(':title', $info['title'], \PDO::PARAM_STR);
        $query->bindParam(':place', $info['place'], \PDO::PARAM_STR);
        $query->bindParam(':event_date', $info['event_date'], \PDO::PARAM_STR);
        $query->bindParam(':start_time', $info['start_time'], \PDO::PARAM_STR);
        $query->bindParam(':end_time', $info['end_time'], \PDO::PARAM_STR);
        $query->bindParam(':fee', $info['fee'], \PDO::PARAM_INT);
        $query->execute();

        return $event_id;
    }
    
    /**
     * IDの最大番号を取得
     *
     * @return void
     */
    private function getMaxId(){

        $sql = 'SELECT max(id) as maxid FROM event';
        $query = $this->db->prepare($sql);
        $query->execute();

        $info = $query -> fetch();
        return $info['maxid'];
    }

    /**
     * 入力情報チェック
     *
     * @param array $info
     * @return bool
     */
    private function checkInfo($info){

        if(!empty($info['fee']) && !empty($info['title']) 
        && !empty($info['place']) && !empty($info['event_date'])
        && !empty($info['start_time']) && !empty($info['end_time']))
        {
            return true;
        }
        return false;
    }

}