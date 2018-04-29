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

        if(strpos($massage_text,'スピルくん再通知')!== false){
            //スピルくん再通知が含まれている場合

            $mapper = new Mapper\PushMapper($db);
            $push_info = $mapper->getPushInfo();

            $massage = array(
                "type" => "text",
                "text" => "(´･ω･｀ ) "
            );
            \Classes\Utility\LineBotPush::push($massage);

        }
        
    }

}