<?php
namespace Classes\Model;

use Classes\Mapper\EventMapper;

class LineBotRecieveModel extends Model
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
     * 曜日再通知
     *
     * @return string
     */
    public function WeekRePush(string $massageText){
        
        // 曜日の切り出し
        $num = mb_strpos($massageText,'曜日再通知');
        $weekStr = mb_substr($massageText,$num-1,1);

        // 漢字を数値変換
        $weekInt = array_search($weekStr, $this->week);

        // 直近のイベント情報を取得
        $mapper = new \Classes\Mapper\Event\EventMapper($this->db);
        $push_info = $mapper->selectFromWeek($weekInt);

        if($push_info){
            // 情報が取得できた場合、取得した情報を返す
            $return = array(
                'event_id' => $push_info->getEventId(),
                'title' => $push_info->getTitle(),
                'place' => $push_info->getPlace(),
                'event_date' => $push_info->getEventDateDisplay(),
                'start_time' => $push_info->getStartTime(),
                'end_time' => $push_info->getEndTime(),
            );
            return $return;
        }
        
        return false;

    }

    /**
     * 再通知
     *
     * @return void
     */
    public function RePush(){

        // 直近のイベント情報を取得
        $mapper = new \Classes\Mapper\Event\EventMapper($this->db);
        $push_info = $mapper->isBeforeDaysInfo(0);

        if($push_info){
            // 情報が取得できた場合、取得した情報を返す
            $return = array(
                'event_id' => $push_info->getEventId(),
                'title' => $push_info->getTitle(),
                'place' => $push_info->getPlace(),
                'event_date' => $push_info->getEventDateDisplay(),
                'start_time' => $push_info->getStartTime(),
                'end_time' => $push_info->getEndTime(),
            );
            return $return;
        }
        
        return false;
    }
}