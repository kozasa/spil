<?php
namespace Classes\Mapper;

class NewPostMapper extends Mapper
{

    /**
     * イベント情報を挿入
     * @param array $info
     */
    public function insertNewRegistant($info) {

        // 入力情報の確認
        if(!$this->checkInfo($info)){
            return false;
        }

        // イベント番号を決定
        $sql = 'INSERT INTO event_participants (event_id,member_id,join_flag,new_flag,new_name,new_gender,new_age,created_at,updated_at) VALUES (:event_id," ",1,1,:new_name,:new_gender,:new_age,NOW(),NOW())';

        $query = $this->db->prepare($sql);
        $query->bindParam(':event_id', $info['join_day'], \PDO::PARAM_STR);
        $query->bindParam(':new_name', $info['name'], \PDO::PARAM_STR);
        $query->bindParam(':new_gender', $info['gender'], \PDO::PARAM_INT);
        $query->bindParam(':new_age', $info['age'], \PDO::PARAM_INT);
        $query->execute();

        return true;
    }

    /**
     * 入力情報チェック
     *
     * @param array $info
     * @return bool
     */
    private function checkInfo($info){

        if(!empty($info['name']) && !empty($info['gender']) 
        && !empty($info['age']) && !empty($info['join_day']))
        {
            return true;
        }
        return false;
    }

}