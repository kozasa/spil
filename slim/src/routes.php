<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
const ACCESS_TOKEN = '8FRJvUP1pkopPJ41hiyiG0mkwPFbJYBf22MCNVX6bljI9C5qUwIW6v/DuMXHkufdWzZEqxog+3J3CDrFQKlTzR965nRNeHKRMVBaIBCdvHJkFpD6Gu2PgV88yVNBPEhKhmKPhb46tt3ph43zevtUIwdB04t89/1O/w1cDnyilFU=';
const PUSH_URL = 'https://api.line.me/v2/bot/message/push';
const PROFILE_URL = 'https://api.line.me/v2/profile';
const GROUP_URL = 'https://api.line.me/v2/bot/group/';
const GROUP_ID = 'Cf14de01a5ca9e6dd9d522e04421ae47a';
const PUSH_KEY = 'spil_push';

/**
 * トップページアクセス
 */
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

/**
 * イベント情報画面
 */
$app->get('/event/{id}',function(Request $request,Response $response){

    // 引数の取得
    $id = $request->getAttribute('id');

    // DB取得
    $mapper = new EventMapper($this->db);
    $event_info = $mapper->getEventInfo($id);
    //var_dump($event_info);
    
    if($event_info){
        // イベントが存在する場合はイベントページを表示
        return $this->renderer->render($response, 'event.phtml', $event_info);
    }
    
});

/**
 * グループ通知機能
 */
$app->get('/push/{key}',function(Request $request,Response $response){

    // 引数の取得
    $key = $request->getAttribute('key');

    // key確認
    if($key!==PUSH_KEY){
        return;
    }

    // 通知するイベント情報を取得
    $mapper = new PushMapper($this->db);
    $push_info = $mapper->getPushInfo();

    var_dump($push_info);
    // 通知
    if($push_info){
        push($push_info);
    }
    
});

/**
* データ送信処理
* @param array $info
*/
function push($info)
{
    // ヘッダーの作成
    $headers = array('Content-Type: application/json',
    'Authorization: Bearer ' . ACCESS_TOKEN);

    // 送信するメッセージ作成
    $message = "";
    if($info["day"]===7){
        $message = push_join_message_seven($info);
    }elseif($info["day"]===1){
        $message = push_join_message_one($info);
    }
    

    $body = json_encode(array('to' => GROUP_ID,
            'messages'   => array($message)));  // 複数送る場合は、array($mesg1,$mesg2) とする。


    // 送り出し用
    $options = array(CURLOPT_URL            => PUSH_URL,
    CURLOPT_CUSTOMREQUEST  => 'POST',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => $headers,
    CURLOPT_POSTFIELDS     => $body);
    $curl = curl_init();
    curl_setopt_array($curl, $options);
    curl_exec($curl);
    curl_close($curl);
}

/**
 * 応募メッセージ 7日前
 *
 * @param array $info
 * @return array
 */
function push_join_message_seven($info){

    return array(
        "type" => "template",
        "altText" => "バドミントンの参加者募集！！\n 開催日時："
            .$info["event_date"].$info["start_time"]."~".$info["end_time"]."\n場所：".$info["place"],
        "template" => array(
            "type" => "buttons",
            "thumbnailImageUrl" => "https://spil.hetabun.com/img/s_badminton.jpg",
            "imageAspectRatio" => "rectangle",
            "imageSize" => "cover",
            "imageBackgroundColor" => "#e0c0a0",
            "title" => "バドミントン参加者募集！",
            "text" => "開催日時：".$info["event_date"].$info["start_time"]."~".$info["end_time"].
                "\n場所：".$info["place"]."\n※参加人数の確認は画像をタップ!",
            "defaultAction" => array(
                "type" => "uri",
                "label" => "View detail",
                "uri" => "https://spil.hetabun.com/event/".$info["event_id"],
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
}

/**
 * 応募メッセージ 1日前
 *
 * @param array $info
 * @return array
 */
function push_join_message_one($info){

    return array(
        "type" => "template",
        "altText" => "明日はバドミントン活動日！\n 開催日時："
            .$info["event_date"].$info["start_time"]."~".$info["end_time"]."\n場所：".$info["place"],
        "template" => array(
            "type" => "buttons",
            "thumbnailImageUrl" => "https://spil.hetabun.com/img/illust_badminton.png",
            "imageAspectRatio" => "rectangle",
            "imageSize" => "cover",
            "imageBackgroundColor" => "#e0c0a0",
            "title" => "明日はバドミントン活動日！",
            "text" => "開催日時：".$info["event_date"].$info["start_time"]."~".$info["end_time"].
                "\n場所：".$info["place"],
            "defaultAction" => array(
                "type" => "uri",
                "label" => "View detail",
                "uri" => "https://spil.hetabun.com/event/".$info["event_id"],
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
                    "uri" => "https://spil.hetabun.com/event/".$info["event_id"]
                )
            )
        )        
    );
}

