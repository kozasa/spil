<?php

namespace Classes\Utility;

class LineBotRecieve
{

    /**
     * メッセージ受信
     *
     * @param string $massage_text
     * @return void
     */
    public static function recieveMassage($db,$massage_text){

        if(strpos($massage_text,'曜日再通知')!== false){
            // ●曜日再通知が含まれている場合

            // 曜日の切り出し
            $num = mb_strpos($massage_text,'曜日再通知');
            $weekStr = mb_substr($massage_text,$num-1,1);

            // 直近のイベント情報を取得
            $mapper = new \Classes\Mapper\PushMapper($db);
            $push_info = $mapper->getRePushWeekInfo($weekStr);

            // 7日前イベント情報メッセージ取得
            $message = \Classes\Utility\LineBotMassage::push_join_message_seven($push_info);

            // lineメッセージの送信
            \Classes\Utility\LineBotPush::push($message);

        }else if(strpos($massage_text,'再通知')!== false){
            // 再通知が含まれている場合

            // 直近のイベント情報を取得
            $mapper = new \Classes\Mapper\PushMapper($db);
            $push_info = $mapper->getRePushInfo();

            // 7日前イベント情報メッセージ取得
            $message = \Classes\Utility\LineBotMassage::push_join_message_seven($push_info);

            // lineメッセージの送信
            \Classes\Utility\LineBotPush::push($message);

        }
        
    }

}