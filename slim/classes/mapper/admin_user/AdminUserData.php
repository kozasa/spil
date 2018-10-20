<?php
namespace Classes\Mapper\AdminUser;

class AdminUserData
{
    private $id;
    private $name;
    private $userId;

    public function __construct($data){
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->userId = $data['user_id'];
    }

    public function getName(){
        return $this->name;
    }

    public function getUserId(){
        return $this->userId;
    }
}