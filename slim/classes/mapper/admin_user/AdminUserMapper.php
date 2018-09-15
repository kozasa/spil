<?php
namespace Classes\Mapper\AdminUser;

class AdminUserMapper extends \Classes\Mapper\Mapper
{
    
    public function selectFromName(string $name){

        $sql = 'SELECT * FROM admin_user WHERE name = :name';
        $query = $this->db->prepare($sql);
        $query->bindParam(':name', $name, \PDO::PARAM_STR);
        $query->execute();

        //取得件数が0件の場合、falseを返す
        if($query->rowCount()==0){
            return false;
        }
        
        $result = new AdminUserData($query -> fetch());
        return $result;
    }

}