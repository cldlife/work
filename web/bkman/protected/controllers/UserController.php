<?php
/**
 * @desc 用户管理
 */
class UserController extends BaseController {

  /**
   * @desc actions 主入口
   */
  public function run () {
    parent::filters();
    $this->defaultURIDoAction = '';
    $method = $this->getURIDoAction($this);
    $this->$method();
  }
  
  /**
   * @desc 错误处理回调方法
   */
  public function errorRedirect () {
    $this->redirect($this->getDeUrl('main/error', array('id' => -404)));
  }
  
  /**
   * @desc 查看用户资料
   */
  private function doProfile() {
    $uid = $this->getSafeRequest('uid', 0, 'GET', 'int');
    $user = $this->getUserService()->getUserByUid($uid);
    if ($user) {
      $userStatus = $user['status'];
      $user['status'] = $this->getUserFortuneService()->getUserFortuneStatusByUid($user['uid']);
      $user = $this->getUserService()->getUserProfile($user, TRUE);
      $user['user_status'] = $userStatus;
    }
    
    $data = array();
    $data['code'] = 1;
    $data['user'] = $user;
    $this->outputJsonData($data);
  }
  
  /**
   * @desc 屏蔽/取消用户
   * @return
   * $code 0-失败，1-成功，-1-无权限
   * $disabledStatus 操作后的用户状态，0-正常，2-屏蔽
   */
  private function doDisabled() {
    $code = 0;
    $disabledStatus = 0;
    $uid = $this->getSafeRequest('uid', 0, 'GET', 'int');
    $user = $this->getUserService()->getUserByUid($uid);
    if ($user && $this->bkAdminUser['uid'] != $user['uid']) {
      $disabledStatus = $user['status'] == 2 ? 0 : 2;
      
      //判断用户是否是后台管理员
      $bkAdminUser = $this->getUserService()->getBkAdminUserByUid($user['uid']);
      if ($bkAdminUser) {
        //仅系统管理员才有权限屏蔽管理用户
        if ($this->isSystemAdmin) {
          if ($this->getUserService()->updateUser($uid, array('status' => $disabledStatus))) $code = 1;
        } else $code = -1;
      } else {
        if ($this->getUserService()->updateUser($uid, array('status' => $disabledStatus))) $code = 1;
      }
    }
    
    $data = array();
    $data['code'] = $code;
    $data['status'] = $disabledStatus;
    $this->outputJsonData($data);
  }
  
  /**
   * @desc 更新用户状态
   * @param int uid
   * @param json status_fields {"privilege_public_num":1}
   */
  private function doUpdateStatus() {
    $uid = $this->getSafeRequest('uid', 0, 'POST', 'int');
    $statusFields = $this->getSafeRequest('status_fields', array(), 'POST', 'json');
    
    $code = 0;
    $user = array();
    if ($uid && $statusFields) $user = $this->getUserService()->getUserByUid($uid);
    if ($user) {
      $fields = array();
      foreach ($statusFields as $key => $value) {
        $inDe = $value < 0 ? '-' : '+';
        $fields[] = array('key' => $key, 'value' => abs($value), 'in_de' => $inDe);
      }
      if ($this->getUserFortuneService()->inDecreaseUserFortuneStatusByUid($uid, $fields)) $code = 1;
    }
  
    $data = array();
    $data['code'] = $code;
    $this->outputJsonData($data);
  }
}
?>