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

        $monthly_event_list = array();
        $event_list = array();
        
        #イベント情報取得
        while($row = $query -> fetch()){
            $month = date('n月' ,strtotime($row['event_date']));
            $weekday = date('w',strtotime($row['event_date']));
            
            if(!array_key_exists($month, $monthly_event_list)){
                array_merge($monthly_event_list,array($month => array()));
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
            $monthly_event_list[$month] = $event_list;
        }

        return $monthly_event_list;
    }
}