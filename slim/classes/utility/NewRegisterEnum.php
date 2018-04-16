<?php

namespace Classes\Utility;

class NewRegisterEnum
{
    /**
     * 性別取得
     *
     * @param [type] $new_gender
     * @return void
     */
    public static function getGender($new_gender){
        switch($new_gender){
            case 1:
                return "男性";
            case 2:
                return "女性";
        }
    }

    public static function getAge($new_age){
        switch($new_age){
            case 1:
                return "１０代";
            case 2:
                return "２０代";
            case 3:
                return "３０代";
            case 4:
                return "４０代";
            case 5:
                return "５０代";
            case 6:
                return "６０代";
        }
    }

    public static function getImage($new_gender){
        switch($new_gender){
            case 1:
                return "/img/man.png";
            case 2:
                return "/img/woman.png";
        }
    }
}