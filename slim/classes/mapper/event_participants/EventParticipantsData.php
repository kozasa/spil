<?php
namespace Classes\Mapper\EventParticipants;

class EventParticipantsData
{
    private $id;
    private $event_id;
    private $member_id;
    private $join_flag;
    private $new_flag;
    private $new_name;
    private $new_gender;
    private $new_age;
    private $created_at;
    private $updated_at;

    // user_mst
    private $display_name;
    private $picture_url;

    public function __construct($data){
        $this->id = $data['id'];
        $this->event_id = $data['event_id'];
        $this->member_id = $data['member_id'];
        $this->join_flag = $data['join_flag'];
        $this->new_flag = $data['new_flag'];
        $this->new_name = $data['new_name'];
        $this->new_gender = $data['new_gender'];
        $this->new_age = $data['new_age'];
        $this->created_at = $data['created_at'];
        $this->updated_at = $data['updated_at'];

        $this->display_name = $data['display_name'];
        $this->picture_url = $data['picture_url'];
    }

    public function getNewFlag(){
        return $this->new_flag;
    }

    public function getDisplayName(){
        return $this->display_name;
    }

    public function getPictureUrl(){
        return $this->picture_url;
    }

    public function getNewName(){
        return $this->new_name;
    }

    public function getNewGender(){
        return $this->new_gender;
    }

    public function getNewAge(){
        return $this->new_age;
    }
}