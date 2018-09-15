<?php
namespace Classes\Controller;

use Classes\Utility;
use Classes\Model;

/**
 * 管理者用ページ
 */
class AdminController extends Controller
{
    /**
     * 管理者画面 ログイン画面 get
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function topGet($request, $response, $args) {
        
        // 管理者ログイン画面表示
        return $this->container->renderer->render(
            $response,
            'admin_login.phtml', 
            array('error_msg' => null)
        );
    }

    /**
     * 管理者画面 ログイン画面 post
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function topPost($request, $response, $args) {
        
        // POSTデータ取得
        $post_data = $request->getParsedBody();

        // ログイン確認処理
        $result = Utility\Login::isCheck(
            $this->container->db,
            $post_data['user'],
            $post_data['password'],
            $error_msg
        );

        if($result)
        {
            // セッション格納
            $_SESSION['user'] = $post_data['user'];

            // 管理者メニュー画面へリダイレクト
            return $response->withStatus(302)->withHeader('Location', '../admin/menu/');
        }
        else
        {
            // 管理者ログイン画面表示
            return $this->container->renderer->render(
                $response,
                'admin_login.phtml', 
                array('error_msg' => $error_msg)
            );
        }
    }

    /**
     * 管理者画面 メニュー画面
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function menuGet($request, $response, $args) {
        
        // ログイン認証
        if(!Utility\Login::isCheckAfter($_SESSION['user'])){
            return $response->withStatus(302)->withHeader('Location', '../../admin/');
        }

        // 管理者ログイン画面表示
        return $this->container->renderer->render(
            $response,
            'admin_menu.phtml', 
            array()
        );
    }

    /**
     * 管理者画面 イベント投稿画面 get
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function eventPostGet($request, $response, $args) {
        
        // ログイン認証
        if(!Utility\Login::isCheckAfter($_SESSION['user'])){
            return $response->withStatus(302)->withHeader('Location', '../../admin/');
        }

        // 管理者ログイン画面表示
        return $this->container->renderer->render(
            $response,
            'admin_event_post.phtml', 
            array('error_msg' => null)
        );
    }

    /**
     * 管理者画面 イベント投稿画面 post
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function eventPostPost($request, $response, $args) {
        
        // ログイン認証
        if(!Utility\Login::isCheckAfter($_SESSION['user'])){
            return $response->withStatus(302)->withHeader('Location', '../../admin/');
        }

        // POSTデータ取得
        $post_data = $request->getParsedBody();

        // DB挿入
        $model = new Model\AdminModel($this->container->db);
        $event_id = $model->eventPostPost($post_data);

        if($event_id){
            // 成功した場合、チャットに投稿
            $message = Utility\LineBotMassage::push_event_info($post_data,$event_id);
            Utility\LineBotPush::push($message);

            // メニュー画面へリダイレクト
            return $response->withStatus(302)->withHeader('Location', '../menu/');

        }else{

            // 失敗した場合、エラー表示
            return $this->container->renderer->render(
                $response,
                'admin_event_post.phtml', 
                array('error_msg' => "投稿処理に失敗しました。入力内容を確認してください。")
            );
        }
    }

    /**
     * 管理者画面 イベント編集一覧画面 get
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function eventEditListGet($request, $response, $args) {

        // ログイン認証
        if(!Utility\Login::isCheckAfter($_SESSION['user'])){
            return $response->withStatus(302)->withHeader('Location', '../../admin/');
        }

        // DB取得
        $model = new Model\AdminModel($this->container->db);
        $latest_info = $model->eventEditListGet();

        // 管理者イベント一覧画面表示
        return $this->container->renderer->render(
            $response,
            'admin_event_list.phtml', 
            array('latest_info' => $latest_info)
        );
        
    }

    /**
     * 管理者画面 イベント編集画面 get
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function eventEditGet($request, $response, $args) {

        // ログイン認証
        if(!Utility\Login::isCheckAfter($_SESSION['user'])){
            return $response->withStatus(302)->withHeader('Location', '../../admin/');
        }

        // event id 取得
        $event_id = $request->getAttribute('event_id');

        // DB取得
        $model = new Model\AdminModel($this->container->db);
        $event = $model->eventEditGet($event_id);
        
        // 管理者ログイン画面表示
        return $this->container->renderer->render(
            $response,
            'admin_event_edit.phtml', 
            array(
                'event' => $event,
                'error_msg' => null,
            )
        );
    }

    /**
     * 管理者画面 イベント編集画面 post
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function eventEditPost($request, $response, $args) {

        // ログイン認証
        if(!Utility\Login::isCheckAfter($_SESSION['user'])){
            return $response->withStatus(302)->withHeader('Location', '../../admin/');
        }

        // event id 取得
        $event_id = $request->getAttribute('event_id');

        // POSTデータ取得
        $post_data = $request->getParsedBody();
        $post_data['event_id'] = $event_id;

        // DB更新
        $model = new Model\AdminModel($this->container->db);
        $event_id = $model->eventEditPost($post_data);

        if($event_id){
            // 成功した場合、チャットに投稿
            $message = Utility\LineBotMassage::push_event_info($post_data,$event_id);
            $message['altText'] = str_replace('追加', '変更', $message['altText']);
            $message['template']['title'] = 'イベントが変更されました。';
            Utility\LineBotPush::push($message);

            // メニュー画面へリダイレクト
            return $response->withStatus(302)->withHeader('Location', '../');
        }else{

            // 失敗した場合、エラー表示
            return $this->container->renderer->render(
                $response,
                'admin_event_edit.phtml', 
                array(
                    'error_msg' => "投稿処理に失敗しました。入力内容を確認してください。",
                    'event' => array(
                        'title' => null,
                        'place' => null,
                        'date' => null,
                        'start_time' => null,
                        'end_time' => null,
                    ),
                )
            );
        }
        
    }

    /**
     * 管理者画面 新規者登録 get
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function newPostGet($request, $response, $args) {
        
        // ログイン認証
        if(!Utility\Login::isCheckAfter($_SESSION['user'])){
            return $response->withStatus(302)->withHeader('Location', '../../admin/');
        }

        // DB取得
        $model = new Model\AdminModel($this->container->db);
        $latest_info = $model->newPostGet();

        // 管理者ログイン画面表示
        return $this->container->renderer->render(
            $response,
            'admin_new_post.phtml', 
            array('latest_info' => $latest_info,
            'error_msg' => null)
        );
    }

    /**
     * 管理者画面 新規者登録 post
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function newPostPost($request, $response, $args) {
        
        // ログイン認証
        if(!Utility\Login::isCheckAfter($_SESSION['user'])){
            return $response->withStatus(302)->withHeader('Location', '../../admin/');
        }

        // POSTデータ取得
        $post_data = $request->getParsedBody();

        // DB挿入、直近イベント情報取得
        $model = new Model\AdminModel($this->container->db);
        $latest_info = $model->newPostPost($post_data);

        if($latest_info){
            // 成功した場合、チャットに投稿

            // 日付の情報を取得
            $date = null;
            foreach($latest_info as $info){
                if($info['event_id'] == $post_data['join_day']){
                    $date = $info['event_date'];
                }
            }

            $message = Utility\LineBotMassage::push_new_info($post_data,$date);
            Utility\LineBotPush::push($message);

            // メニュー画面へリダイレクト
            return $response->withStatus(302)->withHeader('Location', '../menu/');
        }else{

            // 失敗した場合、エラー表示
            return $this->container->renderer->render(
                $response,
                'admin_new_post.phtml', 
                array('latest_info' => $latest_info,
                'error_msg' => "投稿処理に失敗しました。入力内容を確認してください。")
            );
        }
    }

    /**
     * 管理者画面 ログアウト
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function logout($request, $response, $args) {
        
        // ログイン認証
        if(!Utility\Login::isCheckAfter($_SESSION['user'])){
            return $response->withStatus(302)->withHeader('Location', '../../admin/');
        }

        // セッションの変数のクリア
        $_SESSION = array();

        // セッション破棄
        @session_destroy();

        // ログイン画面へリダイレクト
        return $response->withStatus(302)->withHeader('Location', '../../admin/');
    }

    /**
     * グループ通知機能
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function push($request, $response, $args) {
        
        // 引数の取得
        $key = $request->getAttribute('key');

        // key確認
        if($key!==PUSH_KEY){
            return;
        }

        // 通知するイベント情報を取得
        // 当日にイベントが開催されている場合は情報を取得せず、通知処理は行わない
        $model = new Model\AdminModel($this->container->db);
        $push_info = $model->push();

        var_dump($push_info);

        // 通知
        if($push_info){
            $result = Utility\LineBotPush::pushCron($push_info);
        }
    }

    /**
     * 直近イベント開催通知機能
     * (イベント開催日の22時ごろにcronで通知)
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function latestpush($request, $response, $args) {
        
        // 引数の取得
        $key = $request->getAttribute('key');

        // key確認
        if($key!==PUSH_KEY){
            return;
        }

        // 通知するイベント情報(三日分)を取得
        $model = new Model\AdminModel($this->container->db);
        $push_info = $model->latestpush();

        var_dump($push_info);

        // 通知　当日イベント日の場合通知 TODO
        if($push_info[0]['event_date'] === date("Y-m-d")){

            // 送信するメッセージ作成
            $message = Utility\LineBotMassage::push_latest_message($push_info);

            // メッセージ送信
            $result = Utility\LineBotPush::push($message);

        }
        
    }
}