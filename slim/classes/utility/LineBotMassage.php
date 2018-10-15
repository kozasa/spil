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
            "type" => "flex",
            "altText" => "イベント参加者募集！ ".$info["event_date"].$info["start_time"]."~".$info["end_time"],
            "contents" => array(
                "type" =>  "bubble",
                
                "header" =>  array(
                    "type" =>  "box",
                    "layout" =>  "horizontal",
                    "contents" =>  array(
                        array(
                        "type" =>  "text",
                        "text" =>  "イベント参加者募集！",
                        "weight" =>  "bold",
                        "color" =>  "#2f4f4f",
                        "size" =>  "sm",
                        "margin" =>  "none"
                        )
                    )
                    ),
                    "hero" =>  array(
                    "type" =>  "image",
                    "url" =>  ROOT_URL."img/pushseven.jpg",
                    "size" =>  "full",
                    "aspectRatio" =>  "3:1",
                    "aspectMode" =>  "cover",
                    "action" =>  array(
                        "type" =>  "uri",
                        "uri" =>  ROOT_URL.'event/'.$info["event_id"]
                    )
                ),
                
                "body" =>  array(
                "type" =>  "box",
                "layout" =>  "vertical",
                "spacing" =>  "sm",
                "contents" =>  array(
                    array(
                    "type" =>  "text",
                    "text" =>  $info["title"],
                    "size" =>  "lg",
                    "weight" =>  "bold"
                    ),
                    array(
                    "type" =>  "box",
                    "layout" =>  "baseline",
                    "spacing" =>  "sm",
                    "contents" =>  array(
                        array(
                        "type" =>  "text",
                        "text" =>  "開催日時",
                        "color" =>  "#aaaaaa",
                        "size" =>  "md",
                        "flex" =>  2
                        ),
                        array(
                        "type" =>  "text",
                        "text" =>  $info["event_date"].$info["start_time"]."~".$info["end_time"],
                        "wrap" =>  true,
                        "size" =>  "sm",
                        "color" =>  "#666666",
                        "flex" =>  5
                        )
                    )
                    ),
                    array(
                    "type" =>  "box",
                    "layout" =>  "baseline",
                    "spacing" =>  "sm",
                    "contents" =>  array(
                        array(
                        "type" =>  "text",
                        "text" =>  "場所",
                        "color" =>  "#aaaaaa",
                        "size" =>  "md",
                        "flex" =>  2
                        ),
                        array(
                        "type" =>  "text",
                        "text" =>  $info["place"],
                        "wrap" =>  true,
                        "size" =>  "md",
                        "color" =>  "#666666",
                        "flex" =>  5
                        )
                    )
                    ),
                    array(
                    "type" =>  "text",
                    "text" =>  $info['comment'],
                    "wrap" =>  true,
                    "color" =>  "#666666",
                    "size" =>  "xs"
                    )
                )
                ),
                "footer" =>  array(
                "type" =>  "box",
                "layout" =>  "vertical",
                "contents" =>  array(
                    array(
                    "type" =>  "button",
                    "style" =>  "primary",
                    "color" =>  "#9acd32",
                    "action" =>  array(
                        "type" =>  "uri",
                        "label" =>  "参加する",
                        "uri" =>  ROOT_URL."auth/event/join/".$info["event_id"]
                    )
                    ),
                    array(
                    "type" =>  "button",
                    "action" =>  array(
                        "type" =>  "uri",
                        "label" =>  "不参加",
                        "uri" =>  ROOT_URL."auth/event/exit/".$info["event_id"]
                    )
                    )
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
            "type" => "flex",
            "altText" => "明日はイベント活動日！".$info["event_date"].$info["start_time"]."~".$info["end_time"],
            "contents" => array(
                "type" =>  "bubble",
                
                "header" =>  array(
                    "type" =>  "box",
                    "layout" =>  "horizontal",
                    "contents" =>  array(
                        array(
                        "type" =>  "text",
                        "text" =>  "明日はイベント活動日！",
                        "weight" =>  "bold",
                        "color" =>  "#2f4f4f",
                        "size" =>  "sm",
                        "margin" =>  "none"
                        )
                    )
                    ),
                    "hero" =>  array(
                    "type" =>  "image",
                    "url" =>  ROOT_URL."img/pushone.jpeg",
                    "size" =>  "full",
                    "aspectRatio" =>  "3:1",
                    "aspectMode" =>  "cover",
                    "action" =>  array(
                        "type" =>  "uri",
                        "uri" =>  ROOT_URL.'event/'.$info["event_id"]
                    )
                ),
                
                "body" =>  array(
                "type" =>  "box",
                "layout" =>  "vertical",
                "spacing" =>  "sm",
                "contents" =>  array(
                    array(
                    "type" =>  "text",
                    "text" =>  $info["title"],
                    "size" =>  "lg",
                    "weight" =>  "bold"
                    ),
                    array(
                    "type" =>  "box",
                    "layout" =>  "baseline",
                    "spacing" =>  "sm",
                    "contents" =>  array(
                        array(
                        "type" =>  "text",
                        "text" =>  "開催日時",
                        "color" =>  "#aaaaaa",
                        "size" =>  "md",
                        "flex" =>  2
                        ),
                        array(
                        "type" =>  "text",
                        "text" =>  $info["event_date"].$info["start_time"]."~".$info["end_time"],
                        "wrap" =>  true,
                        "size" =>  "sm",
                        "color" =>  "#666666",
                        "flex" =>  5
                        )
                    )
                    ),
                    array(
                    "type" =>  "box",
                    "layout" =>  "baseline",
                    "spacing" =>  "sm",
                    "contents" =>  array(
                        array(
                        "type" =>  "text",
                        "text" =>  "場所",
                        "color" =>  "#aaaaaa",
                        "size" =>  "md",
                        "flex" =>  2
                        ),
                        array(
                        "type" =>  "text",
                        "text" =>  $info["place"],
                        "wrap" =>  true,
                        "size" =>  "md",
                        "color" =>  "#666666",
                        "flex" =>  5
                        )
                    )
                    ),
                    array(
                    "type" =>  "text",
                    "text" =>  $info['comment'],
                    "wrap" =>  true,
                    "color" =>  "#666666",
                    "size" =>  "xs"
                    )
                )
                ),
                "footer" =>  array(
                "type" =>  "box",
                "layout" =>  "vertical",
                "contents" =>  array(
                    array(
                    "type" =>  "button",
                    "style" =>  "primary",
                    "color" =>  "#9acd32",
                    "action" =>  array(
                        "type" =>  "uri",
                        "label" =>  "参加する",
                        "uri" =>  ROOT_URL."auth/event/join/".$info["event_id"]
                    )
                    ),
                    array(
                    "type" =>  "button",
                    "action" =>  array(
                        "type" =>  "uri",
                        "label" =>  "不参加",
                        "uri" =>  ROOT_URL."auth/event/exit/".$info["event_id"]
                    )
                    )
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
            "text" => $date."に新しく".$info['new_name']."さんが参加するよ！"
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
}