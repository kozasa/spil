<?php
namespace Classes\Controller;

use Classes\Utility;
use Classes\Mapper;

/**
 * ホームページ
 */
class HomeController extends Controller
{
    /**
     * トップページアクセス
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return 
     */
    public function home($request, $response, $args) {
        
        // DB取得
        $mapper = new Mapper\IndexMapper($this->container->db);
        $latest_info = $mapper->getLatestInfo();

        // Render index view
        return $this->container->renderer->render(
            $response, 
            'index.phtml', 
            array('latest_info'=>$latest_info)
        );

   }

   /**
    * AUTHテスト用
    *
    * @param [type] $request
    * @param [type] $response
    * @param [type] $args
    * @return void
    */
    public function auth($request, $response, $args){

        //$massage = Utility\LineBotMassage::pushTestAuth();
        //$result = Utility\LineBotPush::push($massage);


        $session_factory = new \Aura\Session\SessionFactory;
        $session = $session_factory->newInstance($_COOKIE);
        $segment = $session->getSegment('Vendor\Package\ClassName');
        
        $csrf_value = $session->getCsrfToken()->getValue();

        $callback = urlencode(ROOT_URL  . 'auth_callback/');
        //$callback = urlencode("https://www.yahoo.co.jp/");
        //$callback = urlencode("https://192.168.33.10/spil/slim/public/index.php/auth_callback/");
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
    * AUTHコールバックテスト用
    *
    * @param [type] $request
    * @param [type] $response
    * @param [type] $args
    * @return void
    */
    public function auth_callback($request, $response, $args){

        $unsafe = $_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'DELETE';

        $session_factory = new \Aura\Session\SessionFactory;
        $session = $session_factory->newInstance($_COOKIE);
        $csrf_value = $_GET['state'];
        $csrf_token = $session->getCsrfToken();
        if ($unsafe || !$csrf_token->isValid($csrf_value)) {
            return;
        }

        $json = "";

        if (isset($_GET['code'])) {

            $callback = ROOT_URL  . 'auth_callback/';
            //$callback = urlencode('https://192.168.33.10/spil/slim/public/index.php/auth_callback/');
            $postData = array(
                'grant_type'    => 'authorization_code',
                'code'          => $_GET['code'],
                'redirect_uri'  => $callback,
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

            // ユーザ情報取得
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken));
            curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/v2/profile');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $curlResponse = curl_exec($ch);
            curl_close($ch);

            $json = json_decode($curlResponse);

            $userId = $json->{'userId'};
            $userName = $json->{'displayName'};
            $pictureUrl = $json->{'pictureUrl'};
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