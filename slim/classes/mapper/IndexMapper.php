<?php
namespace Classes\Mapper;

class IndexMapper extends Mapper
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
    public function getLatestInfo(){

        $sql = 'SELECT *,year(`event_date`) as year,month(`event_date`) as month ,day(`event_date`) as day FROM `event` WHERE event_date >= now() order by event_date';
        
        $query = $this->db->prepare($sql);
        $query->execute();

        $latest_info = array();
        $count = 0;
        #最大3日分のイベント情報取得
        while($row = $query -> fetch() and $count < 3){
            $weekday = date('w',strtotime($row['event_date']));
            $event_info = array(
                'event_id' => $row['event_id'],
                'title' => $row['title'],
                'place' => $row['place'],
                'event_date' => $row['event_date'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
                'year' => $row['year'],
                'month' => $row['month'],
                'day' => $row['day'],
                'week' => $this->week[$weekday],
            );
            array_push($latest_info,$event_info);
            $count += 1;
        }

        return $latest_info;
    }
}