<?php
namespace Classes\Controller;

use Classes\Utility;
use Classes\Mapper;

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
        $mapper = new Mapper\EventPostMapper($this->container->db);
        $event_id = $mapper->insertEventPost($post_data);

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
        $mapper = new Mapper\EventEditMapper($this->container->db);
        $year_list = $mapper->getEventList();

        // 管理者イベント一覧画面表示
        return $this->container->renderer->render(
            $response,
            'admin_event_list.phtml', 
            array('year_list' => $year_list)
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

        // DB挿入
        $mapper = new Mapper\EventEditMapper($this->container->db);
        $event = $mapper->getEventFromId($event_id);
        
        // 管理者ログイン画面表示
        return $this->container->renderer->render(
            $response,
            'admin_event_edit.phtml', 
            array('event' => $event)
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
        $mapper = new Mapper\EventEditMapper($this->container->db);
        $event_id = $mapper->updateEvent($post_data);

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
                array('error_msg' => "投稿処理に失敗しました。入力内容を確認してください。")
            );
        }
        
    }


    /**
     * 管理者画面 イベント削除 get
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function eventDeleteGet($request, $response, $args) {

        // ログイン認証
        if(!Utility\Login::isCheckAfter($_SESSION['user'])){
            return $response->withStatus(302)->withHeader('Location', '../../admin/');
        }

        // event id 取得
        $event_id = $request->getAttribute('event_id');

        // DB更新
        $mapper = new Mapper\EventEditMapper($this->container->db);
        $event_id = $mapper->deleteEvent($event_id);

        if($event_id){
            // 成功した場合、チャットに投稿
            $message = Utility\LineBotMassage::push_event_info($post_data,$event_id);
            $message['altText'] = str_replace('追加', '削除', $message['altText']);
            $message['template']['title'] = 'イベントが削除されました。';
            Utility\LineBotPush::push($message);
            // イベントリスト画面へリダイレクト
            return $response->withStatus(302)->withHeader('Location', '../');
        }else{

            // 失敗した場合、エラー表示
            return $this->container->renderer->render(
                $response,
                'admin_event_edit.phtml', 
                array('error_msg' => "投稿処理に失敗しました。入力内容を確認してください。")
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

        // 直近イベント情報取得
        $mapper = new Mapper\LatestMapper($this->container->db);
        $latest_info = $mapper->getLatestInfo();

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

        // DB挿入
        $mapper = new Mapper\NewPostMapper($this->container->db);
        $result = $mapper->insertNewRegistant($post_data);

        // 直近イベント情報取得
        $mapper_event = new Mapper\LatestMapper($this->container->db);
        $latest_info = $mapper_event->getLatestInfo();

        if($result){
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
        $mapper = new Mapper\PushMapper($this->container->db);
        $push_info = $mapper->getPushInfo();

        var_dump($push_info);

        // 通知
        if($push_info){
            $result = Utility\LineBotPush::pushCron($push_info);
        }
    }
}