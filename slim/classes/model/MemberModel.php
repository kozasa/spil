<?php
namespace Classes\Model;

use Classes\Mapper\Event;
use Classes\Mapper\EventParticipants;

class MemberModel extends Model
{
    /**
     * イベント情報取得
     *
     * @return array
     */
    public function getEventInfo(string $id){
        
        $result = array();

        // イベントが存在するか確認
        $eventMapper = new event\EventMapper($this->db);
        if(!$eventMapper->isEvent($id)){
            // 存在しない
            return false;
        }
        
        // イベントIDからイベント情報を取得
        $eventInfo = $eventMapper->getEventIdInfo($id);

        // イベント情報を格納
        $result = array(
            'event_id' => $eventInfo->getEventId(),
            'title' => $eventInfo->getTitle(),
            'place' => $eventInfo->getPlace(),
            'event_date' => $eventInfo->getEventDateDisplay(),
            'start_time' => $eventInfo->getStartTime(),
            'end_time' => $eventInfo->getEndTime(),
        );

        // イベントIDからイベント参加者一覧情報を取得
        $eventParticipantsMapper = new EventParticipants\EventParticipantsMapper($this->db);
        // 参加者情報取得
        $joinMember = $eventParticipantsMapper->getEventIdAndJoin($id,1);
        $joinMemberResult = array();
        foreach ($joinMember as $row) {
            $data = array(
                'new_flag' => $row->getNewFlag(),
                'display_name' => $row->getDisplayName(),
                'picture_url' => $row->getPictureUrl(),
            );
            array_push($joinMemberResult,$data);
        }
        $result['join_member'] = $joinMemberResult;

        // 非参加者情報取得
        $noneJoinMember = $eventParticipantsMapper->getEventIdAndJoin($id,0);
        $noneJoinMemberResult = array();
        foreach ($noneJoinMember as $row) {
            $data = array(
                'new_flag' => $row->getNewFlag(),
                'display_name' => $row->getDisplayName(),
                'picture_url' => $row->getPictureUrl(),
            );
            array_push($noneJoinMemberResult,$data);
        }
        $result['none_join_member'] = $noneJoinMemberResult;

        return $result;
    }

    /**
     * 直近イベント情報
     *
     * @return array
     */
    public function getLatestInfo(){

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
    
}