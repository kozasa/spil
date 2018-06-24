<?php
namespace Classes\Model;

use Classes\Mapper\Event;

class AdminModel extends Model
{

    /**
     * 管理者画面 イベント投稿画面 post
     *
     * @return int
     */
    public function eventPostPost(array $info){
        
        // 入力情報の確認
        if(!$this->checkEventPost($info)){
            return false;
        }

        // 最大イベント番号を取得
        $mapper = new Event\EventMapper($this->db);
        $latestData = $mapper->selectLatest();
        $maxId = $latestData->getId();

        // イベントIDを決定
        $eventId = 'b' . str_pad($maxId + 1, 6, 0, STR_PAD_LEFT);
        $info['event_id'] = $eventId;

        $result = $mapper->insert($info);
        return $eventId;
    }

    /**
     * 管理者画面 イベント編集一覧画面 get
     *
     * @return void
     */
    public function eventEditListGet(){
        
        // 直近のイベント情報を取得
        $eventMapper = new event\EventMapper($this->db);
        $eventInfo = $eventMapper->getLatestInfo();

        $result = array();
        foreach ($eventInfo as $row) {
            $data = array(
                'event_id' => $row->getEventId(),
                'title' => $row->getTitle(),
                'place' => $row->getPlace(),
                'year' => $row->getEventYear(),
                'month' => $row->getEventMonth(),
                'day' => $row->getEventDay(),
                'week' => $row->getEventWeek(),
                'start_time' => $row->getStartTime(),
                'end_time' => $row->getEndTime(),
            );
            array_push($result,$data);
        }

        return $result;
    }

    /**
     * イベント投稿画面 入力情報チェック
     *
     * @param array $info
     * @return bool
     */
    private function checkEventPost($info){

        if(!empty($info['fee']) && !empty($info['title']) 
        && !empty($info['place']) && !empty($info['event_date'])
        && !empty($info['start_time']) && !empty($info['end_time']))
        {
            return true;
        }
        return false;
    }
}