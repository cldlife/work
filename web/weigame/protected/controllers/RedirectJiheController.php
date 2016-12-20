<?php
/**
 * @desc 集合页url跳转处理
 * @see 同 RedirectContontroller
 */
class RedirectJiheController extends BaseController {

  public function actionIndex () {
    $targetUrl =  $this->getSafeRequest('tt');
    $level =  $this->getSafeRequest('level', NULL, 'GET', 'int');
    $category =  $this->getSafeRequest('category', 0, 'GET', 'int');
    if ($targetUrl) {
      $targetUrl = urldecode($targetUrl);
      
      //根据分组级别获取随机域名
      if ($level >= 0) $targetUrl = $this->randDomain($level, $category) . $targetUrl;
      
      $data = array();
      $data['targetUrl'] = $targetUrl;
      $this->render("/redirect/index", $data);
    }
  }
}
?>
