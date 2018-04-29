<?php

namespace Classes\Utility;

class LineBotRecieve
{
    public static function recieveMassage($massage_text){

        error_log(print_r($massage_text, TRUE), 3, 'yamato_dbg_log.txt');
    }

}