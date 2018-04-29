<?php

require('../slim/src/settings_param.php');
require('../slim/classes/utility/LineBotRecieve.php');
require('../slim/classes/utility/LineBotMassage.php');
require('../slim/classes/utility/LineBotPush.php');

$myclass = new MyBot;
$myclass->main();

class MyBot{

    /**
     * メインメソッド
     */
    public function main()
    {
        // 受信処理
        $user_info = $this->recieve();

        // ユーザ情報が取得できる場合はマスタへ書き込み
        if(isset($user_info["userId"]) && isset($user_info["displayName"]) ){
            $this->db($user_info);
        }
    }

    /**
     * DB関連
     */
    private function db($user_info){
        try {
            $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DBNAME.';charset=utf8',DB_USER,DB_PASS,
            array(PDO::ATTR_EMULATE_PREPARES => false));

            // ユーザ情報が登録されているか確認
            $select = $pdo -> prepare("SELECT * FROM user_mst WHERE user_id = :user_id");
            $select->bindValue(':user_id', $user_info["userId"], PDO::PARAM_STR);
            $select->execute();
            if($select->rowCount() === 0 )
            {
                // 登録されていない場合はinsert
                $stmt = $pdo -> prepare("INSERT INTO user_mst (id,user_id,display_name,picture_url,insert_date,delete_flg) VALUES (:id, :user_id,:display_name,:picture_url,:insert_date,:delete_flg)");
                $stmt->bindValue(':id', null, PDO::PARAM_INT);
                $stmt->bindValue(':user_id', $user_info["userId"], PDO::PARAM_STR);
                $stmt->bindValue(':display_name', $user_info["displayName"], PDO::PARAM_STR);
                $stmt->bindValue(':picture_url', $user_info["pictureUrl"], PDO::PARAM_STR);
                $stmt->bindValue(':insert_date', date("Y/m/d H:i:s"), PDO::PARAM_STR);
                $stmt->bindValue(':delete_flg', '0', PDO::PARAM_STR);
                $stmt->execute();
            }
            else
            {
                // 登録されている場合は、更新確認
                $select2 = $pdo -> prepare("SELECT * FROM user_mst WHERE user_id = :user_id and display_name = :display_name and picture_url = :picture_url");
                $select2->bindValue(':user_id', $user_info["userId"], PDO::PARAM_STR);
                $select2->bindValue(':display_name', $user_info["displayName"], PDO::PARAM_STR);
                $select2->bindValue(':picture_url', $user_info["pictureUrl"], PDO::PARAM_STR);
                $select2->execute();
                if($select2->rowCount() === 0)
                {
                    // 変化がある場合は更新処理
                    $stmt = $pdo -> prepare("UPDATE user_mst SET display_name = :display_name, picture_url =:picture_url WHERE user_id = :user_id");
                    $stmt->bindValue(':user_id', $user_info["userId"], PDO::PARAM_STR);
                    $stmt->bindValue(':display_name', $user_info["displayName"], PDO::PARAM_STR);
                    $stmt->bindValue(':picture_url', $user_info["pictureUrl"], PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
        } catch (PDOException $e) {
            echo "error";
            exit('データベース接続失敗。'.$e->getMessage());
        }
    }

    /**
     * 募集関連DB処理
     * @param array $data
     * @param string $user_id
     */
    private function offerDb($data,$user_id){
        try {
            $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DBNAME.';charset=utf8',DB_USER,DB_PASS,
            array(PDO::ATTR_EMULATE_PREPARES => false));

            // 参加、不参加
            $join_flg = false;
            if($data['action']=="join"){
                $join_flg = true;
            }

            // イベント情報テーブルに登録されているか確認
            $select = $pdo -> prepare("SELECT * FROM event_participants WHERE member_id = :user_id AND event_id = :event_id");
            $select->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $select->bindValue(':event_id', $data["event_id"], PDO::PARAM_STR);
            $select->execute();

            if($select->rowCount() === 0 )
            {
                // 登録されていない場合、INSERT処理
                $stmt = $pdo -> prepare(
                    "INSERT INTO event_participants (id,event_id,member_id,join_flag,new_flag,created_at,updated_at) 
                    VALUES (:id, :event_id,:member_id,:join_flag,:new_flag,:created_at,:updated_at)"
                );
                $stmt->bindValue(':id', null, PDO::PARAM_INT);
                $stmt->bindValue(':event_id', $data["event_id"], PDO::PARAM_STR);
                $stmt->bindValue(':member_id', $user_id, PDO::PARAM_STR);
                $stmt->bindValue(':join_flag', $join_flg, PDO::PARAM_INT);
                $stmt->bindValue(':new_flag', false, PDO::PARAM_INT);
                $stmt->bindValue(':created_at', date("Y/m/d H:i:s"), PDO::PARAM_STR);
                $stmt->bindValue(':updated_at', date("Y/m/d H:i:s"), PDO::PARAM_STR);
                $stmt->execute();

            }else{
                // 登録されている場合、UPDATE処理
                $stmt = $pdo -> prepare(
                    "UPDATE event_participants SET join_flag = :join_flag, updated_at =:updated_at 
                    WHERE member_id = :member_id AND event_id = :event_id"
                );
                $stmt->bindValue(':join_flag', $join_flg, PDO::PARAM_INT);
                $stmt->bindValue(':updated_at', date("Y/m/d H:i:s"), PDO::PARAM_STR);
                $stmt->bindValue(':member_id', $user_id, PDO::PARAM_STR);
                $stmt->bindValue(':event_id', $data["event_id"], PDO::PARAM_STR);
                $stmt->execute();
            }

        } catch (PDOException $e) {
            echo "error";
            exit('データベース接続失敗。'.$e->getMessage());
        }
    }

    /**
     * データ受信処理
     */
    private function recieve(){
        // データの受信
        $raw = file_get_contents('php://input');
        $receive = json_decode($raw, true);

        // ログ出力
        $log_text = date('Y/m/d H:i:s') . ":" . $receive['events'][0]["source"]["groupId"].":".$receive['events'][0]['type']."\n";
        error_log(print_r($log_text, TRUE), 3, 'yamato_dbg_log.txt');

        // メッセージ確認
        $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DBNAME.';charset=utf8',DB_USER,DB_PASS,
            array(PDO::ATTR_EMULATE_PREPARES => false));
        //$settings = require '/../slim/src/settings.php';
        //error_log(print_r($settings, TRUE), 3, 'yamato_dbg_log.txt');
        //$app = new \Slim\App($settings);

        $massage_text = $receive['events'][0]["message"]["text"];
        $result = \Classes\Utility\LineBotRecieve::recieveMassage($pdo,$massage_text);

        // ポストバック
        if($receive['events'][0]['type'] == 'postback')
        {
            // ポストバック処理の場合
            $postback = $receive['events'][0]['postback']['data'];
            $user_id = $receive['events'][0]['source']['userId'];
            parse_str($postback, $data);
            
            if($data['key']=="spil_push"){

                // DB更新処理
                $this->offerDb($data,$user_id);
            }
        }

        // ユーザ情報を取得
        $user_id = $receive['events'][0]['source']['userId'];
        $user_info = $this->get_user_info($user_id);

        return $user_info;
        
    }

    /**
     * ユーザ情報を取得
     */
    private function get_user_info($id){
        $profUrl = GROUP_URL . GROUP_ID . '/member/' . $id;
        $ch = curl_init($profUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . ACCESS_TOKEN
        ));
        $getProfile = curl_exec($ch);
        curl_close($ch);
        $resProfJson = json_decode($getProfile);
        return array(
            "userId" => $resProfJson->userId,
            "displayName" => $resProfJson->displayName,
            "pictureUrl" => $resProfJson->pictureUrl
        );
    }
}