<?php
namespace Classes\Controller;

use Classes\Utility;
use Classes\Model;

/**
 * メンバー用ページ
 */
class MemberController extends Controller
{
    /**
     * イベント情報ページ
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function event($request, $response, $args) {
        
        // 引数の取得
        $id = $request->getAttribute('id');

        // アクション取得
        $action = $request->getAttribute('action');
        $actionMassage = null;
        if($action=="join"){
            $actionMassage = "参加にしました";
        }else if($action=="exit"){
            $actionMassage = "不参加にしました";
        }

        // DB取得
        $model = new Model\MemberModel($this->container->db);
        $event_info = $model->event($id);
        
        if($event_info){
            
            // アクションを配列に追記
            $event_info = array_merge($event_info , array('actionMassage'=>$actionMassage));

            // イベントが存在する場合はイベントページを表示
            return $this->container->renderer->render($response, 'event.phtml', $event_info);
        }

    }

    /**
     * 直近イベント日程
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function latest($request, $response, $args) {
        
        // DB取得
        $model = new Model\MemberModel($this->container->db);
        $latest_info = $model->latest();

        // 直近イベント情報ページ表示
        return $this->container->renderer->render(
            $response,
            'latest.phtml', 
            array('latest_info' => $latest_info)
        );

    }

    /**
    * LINEログイン
    *
    * @param [type] $request
    * @param [type] $response
    * @param [type] $args
    * @return void
    */
    public function auth($request, $response, $args){

        // セッションの初期化
        session_destroy();
        session_start();

        // ページ名の取得
        $page = $request->getAttribute('page');

        // ページ分岐
        if($page == "event"){
            // イベントページの場合
            $_SESSION['page'] = $page;
            $_SESSION['arg1'] = $request->getAttribute('arg1');   // 参加不参加
            $_SESSION['arg2'] = $request->getAttribute('arg2');  // イベントID

        }else if($page == "admin"){    
            // 管理画面の場合
            $_SESSION['page'] = $page;

        }else{
            return;
        }

        // CSRF対策
        $session_factory = new \Aura\Session\SessionFactory;
        $session = $session_factory->newInstance($_COOKIE);
        $segment = $session->getSegment('Vendor\Package\ClassName');
        
        $csrf_value = $session->getCsrfToken()->getValue();

        // LINEログインURL作成
        $callback = urlencode(ROOT_URL  . 'auth_callback/');
        $url = 'https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=' . LOGIN_CHANNEL_ID . 
            '&redirect_uri=' . $callback . 
            '&state=' . $csrf_value .
            '&scope=profile';

        return $this->container->renderer->render(
            $response, 
            'auth.phtml', 
            array('url'=>$url)
        );
   }

    /**
    * LINEコールバック
    *
    * @param [type] $request
    * @param [type] $response
    * @param [type] $args
    * @return void
    */
    public function authCallback($request, $response, $args){

        $unsafe = $_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'DELETE';

        // CSRF対策
        $session_factory = new \Aura\Session\SessionFactory;
        $session = $session_factory->newInstance($_COOKIE);
        $csrf_value = $_GET['state'];
        $csrf_token = $session->getCsrfToken();
        if ($unsafe || !$csrf_token->isValid($csrf_value)) {
            return;
        }

        if (isset($_GET['code'])) {

            // アクセストークン取得
            $json = "";
            $callbackUrl = ROOT_URL  . 'auth_callback/';
            $postData = array(
                'grant_type'    => 'authorization_code',
                'code'          => $_GET['code'],
                'redirect_uri'  => $callbackUrl,
                'client_id'     => LOGIN_CHANNEL_ID,
                'client_secret' => LOGIN_CHANNEL_SECRET
            );
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/oauth2/v2.1/token');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $curlresponse = curl_exec($ch);
            curl_close($ch);
            
            $json = json_decode($curlresponse);
            $accessToken = $json->access_token;

            // アクセストークンが取得できたかチェック
            if(!isset($accessToken)){
                // 取得できない場合はエラー表示
                return $this->container->renderer->render(
                    $response, 
                    'error.phtml',
                    array()
                );
            }

            // ユーザ情報取得
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken));
            curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/v2/profile');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $curlResponse = curl_exec($ch);
            curl_close($ch);

            $json = json_decode($curlResponse);

            $insertInfo = array(
                'userId' => $json->{'userId'},
                'displayName' => $json->{'displayName'},
                'pictureUrl' => $json->{'pictureUrl'}
            );

            // ユーザマスタ更新
            $model = new Model\MemberModel($this->container->db);
            $latest_info = $model->authCallbackUserMst($insertInfo);

            $_SESSION['user'] = $json->{'userId'};

        }

        // セッション情報から処理
        $sessionPage = $_SESSION['page'];
        if($sessionPage == "event"){
            // イベント情報ページ

            // 引数チェック
            if(!isset($_SESSION['arg1']) || !isset($_SESSION['arg2'])){
                // 引数が一部でも設定されていない場合、エラーページへ遷移
                return $this->container->renderer->render(
                    $response, 
                    'error.phtml',
                    array()
                );
            }

            $data = array(
                'action' => $_SESSION['arg1'],
                'event_id' => $_SESSION['arg2'],
            );
            // 参加不参加の登録処理
            $model->authCallbackEventAction($data,$_SESSION['user']);

            // イベント詳細画面へリダイレクト
            return $response->withStatus(302)->withHeader('Location', '../event/'.$data['event_id'].'/'.$data['action']);
        
        }elseif($sessionPage == "admin"){
            // 管理画面

            // 管理画面ログイン確認
            $model = new Model\LoginModel($this->container->db);
            $result = $model->loginConfirmation($json->{'userId'});

            if($result){

                // セッションへ登録
                $_SESSION['admin_user'] = $json->{'userId'};

                // 管理者メニュー画面へリダイレクト
                return $response->withStatus(302)->withHeader('Location', '../admin/menu/');

            }else{

                // ログインエラー画面へリダイレクト
                return $this->container->renderer->render(
                    $response, 
                    'login_error.phtml', 
                    array()
                );

            }
            

        }

        return $this->container->renderer->render(
            $response, 
            'auth_callback.phtml', 
            array(
                'userId' => $userId,
                'userName' => $userName,
                'pictureUrl' => $pictureUrl,
            )
        );
   }
}