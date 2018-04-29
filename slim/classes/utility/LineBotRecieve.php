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

        error_log(print_r($massage_text, TRUE), 3, 'yamato_dbg_log.txt');

        /*
        if(!strpos($massage_text,spilMan.'再通知')){
            //スピルくん再通知が含まれている場合

            $massage = array(
                "type" => "text",
                "text" => "aaaa"
            );
            Utility\LineBotPush::push($massage);

        }
        */
    }

}