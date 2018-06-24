<?php
namespace Classes\Controller;

use Classes\Utility;
use Classes\Model;

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
        $model = new Model\HomeModel($this->container->db);
        $latest_info = $model->getLatestInfo();

        // Render index view
        return $this->container->renderer->render(
            $response, 
            'index.phtml', 
            array('latest_info'=>$latest_info)
        );

   }
}