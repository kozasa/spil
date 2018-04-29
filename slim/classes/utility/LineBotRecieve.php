<?php

namespace Classes\Utility;

class LineBotRecieve
{

    const spilMan = 'スピルくん';

    /**
     * メッセージ受信
     *
     * @param string $massage_text
     * @return void
     */
    public static function recieveMassage($massage_text){

        if(!strpos($massage_text,'スピルくん再通知')){
            //スピルくん再通知が含まれている場合

            $massage = array(
                "type" => "text",
                "text" => "aaaa"
            );
            \Classes\Utility\LineBotPush::push($massage);

        }
        
    }

}