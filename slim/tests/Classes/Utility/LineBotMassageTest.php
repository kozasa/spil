<?php
namespace Tests\Classes\Utility;

use PHPUnit\Framework\TestCase;
use Classes\Utility;
use AspectMock\Test as test;

class LineBotMassageTest extends TestCase
{

    protected function tearDown()
    {
        test::clean(); // 登録したテストダブルをすべて削除
    }

    /**
     * @group utility
     */
    public function testpush_join_message_seven(){
        $info = array(
            'event_date' => date('h-i-s'),
            'start_time' => '11:11',
            'end_time' => '22:22',
            'place' => '場所場所場所',
            'event_id' => 'event_id'

        );

        $massage = array(
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
                    "\n場所：".$info["place"]."\n※参加人数の確認は画像をタップ!",
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
                        "label" => "不参加",
                        "data" => "action=exit&event_id=".$info["event_id"]."&key=spil_push",
                        "displayText" => "不参加"
                    ),
                )
            )
        );
        $this->assertEquals($massage,Utility\LineBotMassage::push_join_message_seven($info));
    }

    /**
     * @group utility
     */
    public function testpush_join_message_one(){
        
        $info = array(
            'event_date' => date('h-i-s'),
            'start_time' => '11:11',
            'end_time' => '22:22',
            'place' => '場所場所場所',
            'event_id' => 'event_id'

        );

        $massage = array(
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
                    "\n場所：".$info["place"],
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
        $this->assertEquals($massage,Utility\LineBotMassage::push_join_message_one($info));
    }

    /**
     * @group utility
     */
    public function testpush_event_info(){
        
        $info = array(
            'event_date' => date('h-i-s'),
            'start_time' => '11:11',
            'end_time' => '22:22',
            'place' => '場所場所場所',

        );
        $event_id = 'testevent_id';
        $massage = array(
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
        $this->assertEquals($massage,Utility\LineBotMassage::push_event_info($info,$event_id));
    }

    /**
     * @group utility
     */
    public function testpush_event_edit_info(){
        
        $info = array(
            'event_date' => date('h-i-s'),
            'start_time' => '11:11',
            'end_time' => '22:22',
            'place' => '場所場所場所',

        );
        $massage = array(
            "type" => "template",
            "altText" => "イベントが変更されました！\n 開催日時："
                .$info["event_date"].$info["start_time"]."~".$info["end_time"]."\n場所：".$info["place"],
            "template" => array(
                "type" => "buttons",
                "thumbnailImageUrl" => ROOT_URL."img/calender_takujou.png",
                "imageAspectRatio" => "rectangle",
                "imageSize" => "cover",
                "imageBackgroundColor" => "#e0c0a0",
                "title" => "イベントが変更されました！",
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
        $this->assertEquals($massage,Utility\LineBotMassage::push_event_edit_info($info,$info));
    }


    /**
     * @group utility
     */
    public function testpush_new_info(){
        
        $info = array('name' => '名前');
        $date = date('h-i-s');
        $massage = array(
            "type" => "text",
            "text" => $date."に新しく".$info['name']."さんが参加するよ！"
        );

        $this->assertEquals($massage,Utility\LineBotMassage::push_new_info($info,$date));
    }

}