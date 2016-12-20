<?php
/**
 * @desc 扫码下载处理 
 */
class DController extends BaseController {

  /**
   * @desc 下载页
   */
  public function actionIndex () {
    $uid = $this->getSafeRequest('uid', 0, 'GET', 'int');
    if ($uid) {
      $user = $this->getUserService()->getUserByUid($uid);
      $data = array();
      $data['avatar'] = $user['avatar'];
      $data['nickname'] = $user['nickname'];
    }
  	$this->renderPartial('download', $data);
  }
  
}
?>