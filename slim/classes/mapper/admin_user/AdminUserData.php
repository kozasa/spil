<?php
namespace Classes\Mapper\AdminUser;

class AdminUserData
{
    private $id;
    private $name;
    private $password;

    public function __construct($data){
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->password = $data['password'];
    }

    public function getName(){
        return $this->name;
    }

    public function getPassword(){
        return $this->password;
    }
}