<?php
namespace Classes\Mapper;

class PushMapper extends Mapper
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
     * プッシュする情報を取得
     */
    public function getPushInfo(){

        // 当日イベントがある場合は通知を行わない
        // 翌日以降にイベント通知とする
        $isEventToday = $this->isEventToday();
        if($isEventToday){
            // 当日にイベントが開催されていた場合、通知しない
            return false;
        }

        // 開催7日前 かつ 7日前フラグの立っていないイベントを取得
        $isEventInfo7 = $this->isBeforeDaysInfo(7);
        if($isEventInfo7){
            // 情報が取得できた場合、フラグを立てる
            $this->setDaysFlag($isEventInfo7["event_id"],7);

            // 7日前をセット
            $isEventInfo7 = array_merge($isEventInfo7,array('day'=>7));

            // 取得した情報を返す
            return $isEventInfo7;
        }

        $isEventInfo1 = $this->isBeforeDaysInfo(1);
        if($isEventInfo1){
            // 1日前の情報が取得できた場合、フラグを立てる
            $this->setDaysFlag($isEventInfo1["event_id"],1);

            // 1日前をセット
            $isEventInfo1 = array_merge($isEventInfo1,array('day'=>1));

            // 取得した情報を返す
            return $isEventInfo1;
        }

        // どちらも該当しない場合
        return false;

    }

    /**
     * 開催●日前 かつ フラグの立っていないイベントを取得
     * @args integer $day
     */
    private function isBeforeDaysInfo($day){

        $sql="";
        if($day===7){
            $sql = 'SELECT * FROM `event` WHERE `before_seven_days` = false 
            AND `event_date`  < DATE_ADD( now(), interval :day DAY ) 
            ORDER BY id;';
        }elseif($day===1){
            $sql = 'SELECT * FROM `event` WHERE `before_one_day` = false 
            AND `event_date`  < DATE_ADD( now(), interval :day DAY ) 
            ORDER BY id;';
        }
        
        $query = $this->db->prepare($sql);
        $query->bindParam(':day', $day, \PDO::PARAM_STR);
        $query->execute();

        //取得件数が０件の場合、falseを返す
        if($query->rowCount()==0){
            return false;
        }

        $array = array();
        if($row = $query->fetch()){
            $weekday = date('w',strtotime($row['event_date']));
            $array = array(
                'event_id' => $row['event_id'],
                'title' => $row['title'],
                'place' => $row['place'],
                'event_date' => date('m/d',strtotime($row['event_date'])) ."(".$this->week[$weekday].")",
                'start_time' => date('H:i',strtotime($row['start_time'])),
                'end_time' => date('H:i',strtotime($row['end_time'])),
                'fee' => $row['fee'],
            );
        }
        return $array;
    }

    /**
     * 当日イベント確認
     *
     * @return boolean
     */
    private function isEventToday(){
        $sql = 'SELECT id FROM `event` WHERE event_date = CURRENT_DATE()';
        $query = $this->db->prepare($sql);
        $query->execute();

        //取得件数が０件の場合、falseを返す
        if($query->rowCount()==0){
            return false;
        }
        return true;
    }

    /**
     * 開催日フラグの更新
     *
     * @param string $event_id
     * @param integer $day
     * @return void
     */
    private function setDaysFlag($event_id,$day){
        $sql="";
        if($day===7){
            $sql = 'UPDATE `event` SET `before_seven_days` = true WHERE event_id = :event_id';
        }elseif($day===1){
            $sql = 'UPDATE `event` SET `before_one_day` = true WHERE event_id = :event_id';
        }
        $query = $this->db->prepare($sql);
        $query->bindParam(':event_id', $event_id, \PDO::PARAM_STR);
        $query->execute();
    }
}