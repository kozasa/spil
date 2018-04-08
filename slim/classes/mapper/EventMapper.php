<?php
namespace Classes\Mapper;

class EventMapper extends Mapper
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
     * イベント情報を取得
     */
    public function getEventInfo($event_id) {

        // イベントが存在するか確認
        if(!$this->isEvent($event_id)){
            return false;
        }
        
        // イベント情報を取得
        return $this->isEventInfo($event_id);
        
    }

    /**
     * イベント情報を取得
     * @args string $event_id
     */
    private function isEventInfo($event_id){

        $event_info = array(
            'title' => null,
            'place' => null,
            'event_date' => null,
            'start_time' => null,
            'end_time' => null,
            'fee' => null,
            'join_member' => null,
            'none_join_member' => null,
        );

        $sql = 'SELECT * FROM event WHERE event_id = :event_id';
        $query = $this->db->prepare($sql);
        $query->bindParam(':event_id', $event_id, \PDO::PARAM_STR);
        $query->execute();

        if($row = $query->fetch()){
            $event_info['title'] = $row['title'];
            $event_info['place'] = $row['place'];
            $weekday = date('w',strtotime($row['event_date']));
            $event_info['event_date'] = date('m/d',strtotime($row['event_date'])) ."(".$this->week[$weekday].")";
            $event_info['start_time'] = date('H:i',strtotime($row['start_time']));
            $event_info['end_time'] = date('H:i',strtotime($row['end_time']));
            $event_info['fee'] = $row['fee'];
        }

        // 参加者一覧取得
        $sql = 'SELECT * FROM event_participants WHERE event_id = :event_id';
        $query = $this->db->prepare($sql);
        $query->bindParam(':event_id', $event_id, \PDO::PARAM_STR);
        $query->execute();

        $join_member = array();
        $none_join_member = array();
        while($row = $query -> fetch()){
            if($row['join_flag']==="1"){
                // 参加者
                $join_member = array_merge($join_member,array(
                    array('id'=>$row['member_id'],'new_flag'=>$row['new_flag'])
                ));

            }else{
                // 不参加者
                $none_join_member = array_merge($none_join_member,array(
                    array('id'=>$row['member_id'],'new_flag'=>$row['new_flag'])
                ));
            }
        }

        // 参加者一覧からメンバーの情報取得
        $join_member_info = array();
        foreach($join_member as $data){

            if(!intval($data['new_flag'])){
                // 既存メンバーの場合

                //ユーザマスタからユーザ名と画像URL取得
                $user_info = $this->isUserInfo($data['id']);
                $join_member_info = array_merge(
                    $join_member_info,
                    array(
                        array(
                            'id'=>$data['id'],
                            'new_flag'=>$data['new_flag'],
                            'display_name' => $user_info['display_name'],
                            'picture_url' => $user_info['picture_url'],
                        )
                    )
                );
            }
        }

        // 不参加者一覧からメンバー情報の取得
        $none_join_member_info = array();
        foreach($none_join_member as $data){

            if(!intval($data['new_flag'])){
                // 既存メンバーの場合

                //ユーザマスタからユーザ名と画像URL取得
                $user_info = $this->isUserInfo($data['id']);
                $none_join_member_info = array_merge(
                    $none_join_member_info,
                    array(
                        array(
                            'id'=>$data['id'],
                            'new_flag'=>$data['new_flag'],
                            'display_name' => $user_info['display_name'],
                            'picture_url' => $user_info['picture_url'],
                        )
                    )
                );
            }
        }

        $event_info['join_member'] = $join_member_info;
        $event_info['none_join_member'] = $none_join_member_info;

        return $event_info;
    }

    /**
     * イベントが存在するか
     * @args string $event_id
     */
    private function isEvent($event_id){

        $sql = 'SELECT * FROM event WHERE event_id = :event_id';
        $query = $this->db->prepare($sql);
        $query->bindParam(':event_id', $event_id, \PDO::PARAM_STR);
        $query->execute();

        if($query->rowCount()>0){
            return true;
        }
        return false;
    }

    /**
     * ユーザマスタから情報取得
     */
    private function isUserInfo($user_id){

        $sql = 'SELECT * FROM user_mst WHERE user_id = :user_id';
        $query = $this->db->prepare($sql);
        $query->bindParam(':user_id', $user_id, \PDO::PARAM_STR);
        $query->execute();

        $array = array();
        if($row = $query->fetch()){
            $array = array(
                'display_name' => $row['display_name'],
                'picture_url' => $row['picture_url']
            );
        }
        return $array;
    }
}