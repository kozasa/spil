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
                        "type" => "uri",
                        "label" => "参加！",
                        "uri" => ROOT_URL."auth/event/join/".$info["event_id"]
                    ),
                    array(
                        "type" => "uri",
                        "label" => "不参加",
                        "uri" => ROOT_URL."auth/event/exit/".$info["event_id"]
                    )
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

    /**
     * 直近情報投稿メッセージ
     *
     * @param array $info
     * @return array
     */
    public static function push_latest_message($info){

        return array(
            "type" => "text",
            "text" =>   "本日はご参加ありがとうございました〜！！\n".
                        "\n".
                        "〜直近の活動日〜\n".
                        $info[1]['month']."月".$info[1]['day']."日"."(".$info[1]['week'].")"." ".date('H:i' ,strtotime($info[1]['start_time']))."〜 ".$info[1]['place']." ".str_replace('バドミントン','',$info[1]['title'])."\n".
                        $info[2]['month']."月".$info[2]['day']."日"."(".$info[2]['week'].")"." ".date('H:i' ,strtotime($info[2]['start_time']))."〜 ".$info[2]['place']." ".str_replace('バドミントン','',$info[2]['title'])."\n".
                        $info[3]['month']."月".$info[3]['day']."日"."(".$info[3]['week'].")"." ".date('H:i' ,strtotime($info[3]['start_time']))."〜 ".$info[3]['place']." ".str_replace('バドミントン','',$info[3]['title'])."\n".
                        "https://spil.hetabun.com/latest/\n".
                        "\n".
                        "また空いてる日があったら参加してね！"
        );
    }

    /**
     * AUTHテストメッセージ
     *
     * @return void
     */
    public static function pushTestAuth(){
        return array(
            "type" => "template",
            "altText" => "AUTHテスト",
            "template" => array(
                "type" => "confirm",
                "text" => "Are you sure?",
                "actions" => array(
                    array(
                      "type" => "uri",
                      "label" => "ログイン",
                      "uri" => "http://www.geocities.jp/tkozasa0119/redirect.html"
                    ),
                    array(
                      "type" => "message",
                      "label" => "No",
                      "text" => "no"
                    )
                )
            )        
        );
    }
}