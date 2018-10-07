<?php
namespace Classes\Model;

use Classes\Mapper\event;

class HomeModel extends Model
{

    /**
     * 現在日時以降のイベント情報をイベントの日程順で取得
     * （当日を含む）
     *
     * @return array
     */
    public function home(){
        
        $mapper = new event\EventMapper($this->db);
        $latest_info = $mapper->selectLatest();
        
        // 2面のみ取得
        $latest_2court = array_filter($latest_info, function($v){return $v->getTitle() == "バドミントン2面";});

        // 直近３日程を取り出す
        $latest_3day = array_slice($latest_2court,0,3);

        $result = array();
        foreach ($latest_3day as $row) {
            $data = array(
                'event_id' => $row->getEventId(),
                'title' => $row->getTitle(),
                'place' => $row->getPlace(),
                'event_date' => $row->getEventDateDisplay(),
                'start_time' => $row->getStartTime(),
                'end_time' => $row->getEndTime(),
            );
            array_push($result,$data);
        }

        return $result;
    }
}