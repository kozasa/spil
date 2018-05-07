<?php

namespace Classes\Utility;

class LineBotMassage
{
    /**
     * 応募メッセージ 7日前
     *
     * @param array $info
     * @return array
     */
    public static function push_join_message_seven($info){

        return array(
            "type" => "template",
            "altText" => "バドミントンの参加者募集！！\n 開催日時："
                .$info["event_date"].$info["start_time"]."~".$info["end_time"]."\n場所：".$info["place"],
            "template" => array(
                "type" => "buttons",
                "thumbnailImageUrl" => ROOT_URL."img/s_badminton.jpg",
                "imageAspectRatio" => "rectangle",
                "imageSize" => "cover",
                "imageBackgroundColor" => "#e0c0a0",
                "title" => "バドミントン参加者募集！",
                "text" => "開催日時：".$info["event_date"].$info["start_time"]."~".$info["end_time"].
                    "\n場所：".$info["place"].
                    "\nタイトル：".$info["title"],
                "defaultAction" => array(
                    "type" => "uri",
                    "label" => "View detail",
                    "uri" => ROOT_URL.'event/'.$info["event_id"],
                    "area" => array(  
                        "x" => 0,
                        "y" => 0,
                        "width" => 20,
                        "height" => 100
                    )
                ),
                "actions" => array(
                    array(
                        "type" => "postback",
                        "label" => "参加！",
                        "data" => "action=join&event_id=".$info["event_id"]."&key=spil_push",
                        "displayText" => "参加！"
                    ),
                    array(
                        "type" => "postback",
                        "label" => "参加取り消し",
                        "data" => "action=exit&event_id=".$info["event_id"]."&key=spil_push",
                        "displayText" => "参加取り消し"
                    ),
                )
            )        
        );
    }

    /**
     * 応募メッセージ 1日前
     *
     * @param array $info
     * @return array
     */
    public static function push_join_message_one($info){

        return array(
            "type" => "template",
            "altText" => "明日はバドミントン活動日！\n 開催日時："
                .$info["event_date"].$info["start_time"]."~".$info["end_time"]."\n場所：".$info["place"],
            "template" => array(
                "type" => "buttons",
                "thumbnailImageUrl" => ROOT_URL."img/illust_badminton.png",
                "imageAspectRatio" => "rectangle",
                "imageSize" => "cover",
                "imageBackgroundColor" => "#e0c0a0",
                "title" => "明日はバドミントン活動日！",
                "text" => "開催日時：".$info["event_date"].$info["start_time"]."~".$info["end_time"].
                    "\n場所：".$info["place"].
                    "\nタイトル：".$info["title"],
                "defaultAction" => array(
                    "type" => "uri",
                    "label" => "View detail",
                    "uri" => ROOT_URL."event/".$info["event_id"],
                    "area" => array(  
                        "x" => 0,
                        "y" => 0,
                        "width" => 20,
                        "height" => 100
                    )
                ),
                "actions" => array(
                    array(
                        "type" => "uri",
                        "label" => "詳細を確認する",
                        "uri" => ROOT_URL."event/".$info["event_id"]
                    )
                )
            )        
        );
    }

    /**
     * イベント情報投稿メッセージ
     *
     * @param array $info
     * @return array
     */
    public static function push_event_info($info,$event_id){

        return array(
            "type" => "template",
            "altText" => "イベントが追加されました！\n 開催日時："
                .$info["event_date"].$info["start_time"]."~".$info["end_time"]."\n場所：".$info["place"],
            "template" => array(
                "type" => "buttons",
                "thumbnailImageUrl" => ROOT_URL."img/calender_takujou.png",
                "imageAspectRatio" => "rectangle",
                "imageSize" => "cover",
                "imageBackgroundColor" => "#e0c0a0",
                "title" => "イベントが追加されました！",
                "text" => "開催日：".$info["event_date"]."\n".
                    "開催時間：".$info["start_time"]."~".$info["end_time"].
                    "\n場所：".$info["place"],
                "defaultAction" => array(
                    "type" => "uri",
                    "label" => "View detail",
                    "uri" => ROOT_URL."latest/",
                    "area" => array(  
                        "x" => 0,
                        "y" => 0,
                        "width" => 20,
                        "height" => 100
                    )
                ),
                "actions" => array(
                    array(
                        "type" => "uri",
                        "label" => "直近イベント情報を確認",
                        "uri" => ROOT_URL."latest/"
                    )
                )
            )        
        );
    }

    /**
     * 新規者情報投稿メッセージ
     *
     * @param array $info
     * @return array
     */
    public static function push_new_info($info,$date){

        return array(
            "type" => "text",
            "text" => $date."に新しく".$info['name']."さんが参加するよ！"
        );
    }
}