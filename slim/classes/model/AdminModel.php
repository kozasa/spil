<?php
namespace Classes\Model;

use Classes\Mapper\Event;
use Classes\Mapper\EventParticipants;

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
        $latestData = $mapper->selectLatestPiece();
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
        $eventInfo = $eventMapper->selectLatest();

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
     * 管理者画面 イベント編集画面 get
     *
     * @return void
     */
    public function eventEditGet(string $eventId){

        // 指定したイベントIDのイベント情報取得
        $eventMapper = new event\EventMapper($this->db);
        $eventInfo = $eventMapper->selectFromEventId($eventId);
        
        $result = array(
            'event_id' => $eventInfo->getEventId(),
            'title' => $eventInfo->getTitle(),
            'place' => $eventInfo->getPlace(),
            'date' => $eventInfo->getEventDate(),
            'week' => $eventInfo->getEventWeek(),
            'fee' => $eventInfo->getFee(),
            'start_time' => $eventInfo->getStartTime(),
            'end_time' => $eventInfo->getEndTime(),
        );

        return $result;

    }

    /**
     * 管理者画面 イベント編集画面 post
     *
     * @param array $postData
     * @return void
     */
    public function eventEditPost(array $postData){

        // 入力情報の確認
        if(!$this->checkEventPost($postData)){
            return false;
        }

        // DB更新
        $eventMapper = new event\EventMapper($this->db);
        $eventId = $eventMapper->update($postData);

        return $eventId;
    }

    /**
     * 管理者画面 新規者登録 get
     *
     * @return void
     */
    public function newPostGet(){

        // 直近のイベント情報を取得
        $eventMapper = new event\EventMapper($this->db);
        $eventInfo = $eventMapper->selectLatest();

        $result = array();
        foreach ($eventInfo as $row) {
            $data = array(
                'event_id' => $row->getEventId(),
                'week' => $row->getEventWeek(),
                'event_date' => $row->getEventDate(),
            );
            array_push($result,$data);
        }

        return $result;

    }

    /**
     * 管理者画面 新規者登録 post
     *
     * @param array $postData
     * @return void
     */
    public function newPostPost(array $postData){

        // 入力情報の確認
        if(!$this->checkRegistantPost($postData)){
            return false;
        }

        // 新規登録者のメンバーID、joinFlgを設定（空欄）
        $postData['member_id'] = '';
        $postData['join_flag'] = true;
        $postData['new_flag'] = true;

        // DB挿入
        $participantsMapper = new EventParticipants\EventParticipantsMapper($this->db);
        $result = $participantsMapper->insert($postData);

        // DB挿入で失敗した場合
        if(!$result){
            return false;
        }
        
        // 直近イベント情報取得
        $eventMapper = new event\EventMapper($this->db);
        $eventInfo = $eventMapper->selectLatest();

        $result = array();
        foreach ($eventInfo as $row) {
            $data = array(
                'event_id' => $row->getEventId(),
                'week' => $row->getEventWeek(),
                'event_date' => $row->getEventDate(),
            );
            array_push($result,$data);
        }

        return $result;
    }

    /**
     * グループ通知機能
     *
     * @return void
     */
    public function push(){

        // 開催1日前 かつ 1日前フラグの立っていないイベントを取得
        $eventMapper = new event\EventMapper($this->db);
        $isEventInfo1 = $eventMapper->isBeforeDaysInfo(1);

        if($isEventInfo1){
            // 1日前の情報が取得できた場合、フラグを立てる
            $eventMapper->updateFlag($isEventInfo1->getEventId(),1);

            // 配列の作成
            $array = array(
                'event_id' => $isEventInfo1->getEventId(),
                'title' => $isEventInfo1->getTitle(),
                'place' => $isEventInfo1->getPlace(),
                'event_date' => $isEventInfo1->getEventDateDisplay(),
                'start_time' => $isEventInfo1->getStartTime(),
                'end_time' => $isEventInfo1->getEndTime(),
                'fee' => $isEventInfo1->getFee(),
            );

            // 1日前をセット
            $isEventInfo1 = array_merge($array,array('day'=>1));

            // 取得した情報を返す
            return $isEventInfo1;
        }

        // 当日イベントがある場合は通知を行わない
        // 翌日以降にイベント通知とする
        $isEventToday = $eventMapper->isEventToday();
        if($isEventToday){
            // 当日にイベントが開催されていた場合、通知しない
            return false;
        }

        // 開催7日前 かつ 7日前フラグの立っていないイベントを取得
        $isEventInfo7 = $eventMapper->isBeforeDaysInfo(7);
        if($isEventInfo7){

            // 7日前の情報が取得できた場合、フラグを立てる
            $eventMapper->updateFlag($isEventInfo7->getEventId(),7);

            // 配列の作成
            $array = array(
                'event_id' => $isEventInfo7->getEventId(),
                'title' => $isEventInfo7->getTitle(),
                'place' => $isEventInfo7->getPlace(),
                'event_date' => $isEventInfo7->getEventDateDisplay(),
                'start_time' => $isEventInfo7->getStartTime(),
                'end_time' => $isEventInfo7->getEndTime(),
                'fee' => $isEventInfo7->getFee(),
            );

            // 7日前をセット
            $isEventInfo7 = array_merge($array,array('day'=>7));

            // 取得した情報を返す
            return $isEventInfo7;
        }

        // どちらも該当しない場合
        return false;

    }

    /**
     * 直近イベント開催通知機能
     * (イベント開催日の22時ごろにcronで通知)
     *
     * @return void
     */
    public function latestpush(){

        // 直近のイベント情報を取得
        $eventMapper = new event\EventMapper($this->db);
        $eventInfo = $eventMapper->selectLatest();

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
                'event_date' => $row->getEventDate(),
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

    /**
     * 参加者登録　入力情報チェック
     *
     * @param array $info
     * @return bool
     */
    private function checkRegistantPost($info){

        if(!empty($info['new_name']) && !empty($info['new_gender']) 
        && !empty($info['new_age']) && !empty($info['event_id']))
        {
            return true;
        }
        return false;
    }
}