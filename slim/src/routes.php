<?php

use Slim\Http\Request;
use Slim\Http\Response;

use Classes\Utility;
use Classes\Mapper;

/**
 * トップページアクセス
 */
$app->get('/index/', function (Request $request, Response $response, array $args) {
    
    // DB取得
    $mapper = new Mapper\IndexMapper($this->db);
    $latest_info = $mapper->getLatestInfo();

    // Render index view
    return $this->renderer->render($response, 'index.phtml', array('latest_info'=>$latest_info));
});

/**
 * イベント情報画面
 */
$app->get('/event/{id}',function(Request $request,Response $response){

    // 引数の取得
    $id = $request->getAttribute('id');

    // DB取得
    $mapper = new Mapper\EventMapper($this->db);
    $event_info = $mapper->getEventInfo($id);
    //var_dump($event_info);
    
    if($event_info){
        // イベントが存在する場合はイベントページを表示
        return $this->renderer->render($response, 'event.phtml', $event_info);
    }
    
});

/**
 * 直近イベント日程画面
 */
$app->get('/latest/',function(Request $request,Response $response){

    // DB取得
    $mapper = new Mapper\LatestMapper($this->db);
    $latest_info = $mapper->getLatestInfo();

    // 直近イベント情報ページ表示
    return $this->renderer->render(
        $response,
        'latest.phtml', 
        array('latest_info'=>$latest_info)
    );
    
    
});

/**
 * 管理者画面 ログイン画面
 */
$app->get('/admin/',function(Request $request,Response $response){
    
    // 管理者ログイン画面表示
    return $this->renderer->render(
        $response,
        'admin_login.phtml', 
        array('error_msg' => null)
    );

});

/**
 * 管理者画面 メニュー画面
 */
$app->post('/admin/',function(Request $request,Response $response){
    
    // POSTデータ取得
    $post_data = $request->getParsedBody();

    // ログイン確認処理
    $result = Utility\Login::isCheck($this->db,$post_data['user'],$post_data['password'],$error_msg);

    if($result)
    {
        // 管理者ログイン画面表示
        return $this->renderer->render(
            $response,
            'admin_menu.phtml', 
            array()
        );
    }
    else
    {
        // 管理者ログイン画面表示
        return $this->renderer->render(
            $response,
            'admin_login.phtml', 
            array('error_msg' => $error_msg)
        );
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
    $mapper = new Mapper\PushMapper($this->db);
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
}

