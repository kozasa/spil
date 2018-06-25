<?php
namespace Classes\Model;

use Classes\Mapper\AdminUser;

class LoginModel extends Model
{

    /**
     * パスワード取得
     *
     * @return string
     */
    public function getPassword(string $name){
        
        $mapper = new AdminUser\AdminUserMapper($this->db);
        $adminUser = $mapper->selectFromName($name);

        // 取得できなかった場合はfalseを返す
        if(!$adminUser){
            return false;
        }

        return $adminUser->getPassword();
    }
}