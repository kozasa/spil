<?php

namespace Classes\Utility;

use Classes\Mapper;

class Login
{
    /**
     * ログイン確認
     *
     * @param  $db
     * @param string $user
     * @param string $password
     * @param string $error_msg
     * @return string ErrorMassage
     */
    public static function isCheck($db,$user,$password,&$error_msg)
    {
        // エラーメッセージ初期化
        $error_msg = "";

        // 入力チェック
        if(empty($user))
        {
            $error_msg = "IDを確認してください";
            return false;
        }
        if(empty($password))
        {
            $error_msg = "パスワードを確認してください";
            return false;
        }

        // ユーザを検索してパスワードを取得
        $mapper = new Mapper\LoginMapper($db);
        $user_info = $mapper->getUserInfo($user);

        if(!$user_info){
            $error_msg = "IDを確認してください";
            return false;
        }

        // パスワード一致判定
        if (password_verify($password, $user_info['password'])) {
            // 判定OK
            session_regenerate_id(true);
        }else{
          // 認証失敗
          $error_msg = 'IDまたはパスワードを確認してください';
          return false; 
        }

        return true;
    }
}