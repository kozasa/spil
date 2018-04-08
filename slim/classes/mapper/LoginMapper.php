<?php
namespace Classes\Mapper;

class LoginMapper extends Mapper
{

    /**
     * ユーザ情報を取得
     */
    public function getUserInfo($user) {

        $sql = 'SELECT * FROM admin_user WHERE name = :name';
        $query = $this->db->prepare($sql);
        $query->bindParam(':name', $user, \PDO::PARAM_STR);
        $query->execute();

        //取得件数が０件の場合、falseを返す
        if($query->rowCount()==0){
            return false;
        }
        
        return $query -> fetch();
    }
}