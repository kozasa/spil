<?php
namespace Classes\Mapper\UserMst;

class UserMstData
{
    private $id;
    private $user_id;
    private $display_name;
    private $picture_url;

    public function __construct($data){
        $this->id = $data['id'];
        $this->user_id = $data['user_id'];
        $this->display_name = $data['display_name'];
        $this->picture_url = $data['picture_url'];
    }

    public function getId(){
        return $this->id;
    }

    public function getUserId(){
        return $this->user_id;
    }

    public function getDisplayName(){
        return $this->display_name;
    }

    public function getPictureUrl(){
        return $this->picture_url;
    }
}