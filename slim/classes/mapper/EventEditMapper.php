<?php
namespace Classes\Mapper;

class EventEditMapper extends Mapper
{
    private $week = [
        '日', //0
        '月', //1
        '火', //2
        '水', //3
        '木', //4
        '金', //5
        '土', //6
    ];

    /**
     * 現在日時以降のイベント情報をイベントの日程順で取得
     * （当日を含む）
     *
     * @return array
     */
    public function getEventList(){
        $sql = 'SELECT * FROM `event` WHERE event_date >= CURRENT_DATE() order by event_date';
        $query = $this->db->prepare($sql);
        $query->execute();

        $year_list = array();
        $month_list = array();
        
        #イベント情報取得
        while($row = $query -> fetch()){
            $year = date('Y年' ,strtotime($row['event_date']));
            $month = date('n月' ,strtotime($row['event_date']));
            $weekday = date('w',strtotime($row['event_date']));
            
            #年がリストに存在しなければリスト作成、月のリストを初期化
            if(!array_key_exists($year, $year_list)){
                array_merge($year_list,array($year => array()));
                $month_list = array();
            }
            #月がリストに存在しなければ、リスト作成、イベントリストを初期化
            if(!array_key_exists($month, $month_list)){
                array_merge($month_list,array($month => array()));
                $event_list = array();
            }
            $event_info = array(
                'event_id' => $row['event_id'],
                'title' => $row['title'],
                'place' => $row['place'],
                'date' => date('j' ,strtotime($row['event_date'])),
                'week' => $this->week[$weekday],
                'start_time' => date('H:i' ,strtotime($row['start_time'])),
                'end_time' => date('H:i' ,strtotime($row['end_time'])),
            );
            array_push($event_list,$event_info);
            $month_list[$month] = $event_list;
            $year_list[$year] = $month_list;
        }
        return $year_list;
    }
    

    /**
     * 現在日時以降のイベント情報をイベントの日程順で取得
     * （当日を含む）
     *
     * @return array
     */
    public function getEventFromId($event_id){
        $sql = 'SELECT * FROM event WHERE event_id = :event_id';
        $query = $this->db->prepare($sql);
        $query->bindParam(':event_id', $event_id, \PDO::PARAM_STR);
        $query->execute();

        #イベント情報取得
        $row = $query -> fetch();
        $weekday = date('w',strtotime($row['event_date']));
        $event = array(
            'event_id' => $row['event_id'],
            'title' => $row['title'],
            'place' => $row['place'],
            'date' => $row['event_date'],
            'week' => $this->week[$weekday],
            'fee' => $row['fee'],
            'start_time' => date('H:i' ,strtotime($row['start_time'])),
            'end_time' => date('H:i' ,strtotime($row['end_time'])),
        );

        return $event;
    }

    /**
     * イベント情報を更新
     * @param array $info
     */
    public function updateEvent($info) {

        // 入力情報の確認
        if(!$this->checkInfo($info)){
            return false;
        }
        
        $sql = 'UPDATE event SET title=:title, place=:place, event_date=:event_date, start_time=:start_time, end_time=:end_time, fee=:fee WHERE event_id =:event_id';

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


    /**
     * イベント情報を削除
     * @param $event_id
     */
    public function deleteEvent($event_id) {
        $sql = 'DELETE FROM event WHERE event_id =:event_id';
        $query = $this->db->prepare($sql);
        $query->bindParam(':event_id', $event_id, \PDO::PARAM_STR);
        $query->execute();

        return true;
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