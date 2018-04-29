<?php
namespace Classes\Controller;

use Classes\Utility;
use Classes\Mapper;

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

        // DB取得
        $mapper = new Mapper\EventMapper($this->container->db);
        $event_info = $mapper->getEventInfo($id);
        
        if($event_info){
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
        $mapper = new Mapper\LatestMapper($this->container->db);
        $latest_info = $mapper->getLatestInfo();

        // 直近イベント情報ページ表示
        return $this->container->renderer->render(
            $response,
            'latest.phtml', 
            array('latest_info' => $latest_info)
        );

    }
}