<?php
/**
 * @desc url跳转处理
 */
class RedirectController extends BaseController {

  public function actionIndex () {
    $from =  $this->getSafeRequest('fr');
    $targetUrl =  $this->getSafeRequest('tt');
    $level =  $this->getSafeRequest('level', NULL, 'GET', 'int');
    $category =  $this->getSafeRequest('category', 0, 'GET', 'int');
    if ($targetUrl) {
      $targetUrl = urldecode($targetUrl);
      
      //根据分组级别获取随机域名
      if ($level >= 0) $targetUrl = $this->randDomain($level, $category) . $targetUrl;
      
      //跳转
      //$this->redirect($targetUrl);
      
      $data = array();
      $data['targetUrl'] = $targetUrl;
      $this->render("index", $data);
    }
  }
}
?>
