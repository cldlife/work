<?php
/**
 * @desc 后台管理权限验证
 * @return is_admin 管理级别(1-最高权限, 0-按权限点)
 */
class PermissionController extends BaseController {

  /**
   * @desc actions 主入口
   */
  public function run ($actionID = NULL) {
    parent::filters();
    $this->defaultURIDoAction = 'login';
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
   * @desc 登录
   */
  private function doLogin () {
    $mobile = $this->getSafeRequest('mobile', 0, 'POST', 'int');
    $passwd = $this->getSafeRequest('passwd', '', 'POST');

    if ($mobile && $passwd) {

      //参数验证
      if (!$mobile || !$passwd) $this->outputJsonData(array('code' => 1001));
      if (!Utils::checkMobile($mobile)) $this->outputJsonData(array('code' => 1001));

      //验证是否有密码安全key
      if (!preg_match("/.+".self::BKMAN_PASSWORD_KEY."$/", $passwd)) $this->outputJsonData(array('code' => 1002));

      //验证用户权限
      $user = $this->getUserService()->getUserByMobile($mobile);
      $passwd = str_replace(self::BKMAN_PASSWORD_KEY, '', $passwd);
      if (!$user['uid'] || $user['password'] != md5($passwd)) {
        $this->outputJsonData(array('code' => 1002));
      }

      //验证是否是管理员
      $bkAdminUser = $this->getUserService()->getBkAdminUserByUid($user['uid']);

      //登录成功, 验证管理权限
      if ($bkAdminUser['uid']) {
        //设置后台会话session（过期时间，2小时）
        $options = array('expire' => 7200);
        $this->setCookie('_bk_admin_uid', $bkAdminUser['uid'], $options);
        $this->setCookie('_bk_admin_accesskey', md5($user['password'] . self::BKMAN_PASSWORD_KEY), $options);

        //更新最近登录时间
        $this->getUserService()->updateBkAdminUser($user['uid'], array(
          'last_login_time' => $bkAdminUser['updated_time'],
          'updated_time' => time(),
        ));

        $this->outputJsonData(array('code' => 1));
      } else {
        $this->outputJsonData(array('code' => 1003));
      }
    }

    $this->title = '登录 - 管理后台';
    $this->render('login', $show);
  }

  /**
   * @desc 退出登录
   */
  private function doLogout () {
    $this->deleteCookie('_bk_admin_uid');
    $this->deleteCookie('_bk_admin_accesskey');
    $this->redirect($this->getDeUrl('permission/login'));
  }

  /**
   * @desc 添加管理员
   */
  private function doNewuser () {
    $action = $this->getSafeRequest('action');

    //添加
    if ($action == 'new') {
      $mobile = $this->getSafeRequest('mobile', 0, 'POST', 'int');
      $adminName = $this->getSafeRequest('admin_name', '', 'POST');

      //参数验证
      if (!$mobile || !$adminName) $this->outputJsonData(array('code' => -3));
      if (!Utils::checkMobile($mobile)) $this->outputJsonData(array('code' => -3));

      //验证用户权限
      $user = $this->getUserService()->getUserByMobile($mobile);
      if ($user['uid']) {
        //检查是否已添加
        if ($this->getUserService()->getBkAdminUserByUid($user['uid'])) {
          $this->outputJsonData(array('code' => -1));
        } else {
          if ($this->getUserService()->newBkAdminUser(array(
              'uid' => $user['uid'],
              'admin_name' => $adminName,
          ))) {
            $this->outputJsonData(array('code' => 1));
          }
          $this->outputJsonData(array('code' => 0));
        }
      }

      $this->outputJsonData(array('code' => -2));

    //删除
    } elseif ($action == 'del') {
      $uid = $this->getSafeRequest('uid', 0, 'POST', 'int');

      //验证管理员是否存在
      if ($this->getUserService()->getBkAdminUserByUid($uid)) {
        //删除管理账号
        if ($this->getUserService()->deleteBkAdminUser($uid)) {
          $this->outputJsonData(array('code' => 1));
        }
      } else {
        $this->outputJsonData(array('code' => 0));
      }
    }

    //管理员列表
    $adminUserList = $this->getUserService()->getBkAdminUsers();

    $this->title = '管理员账号设置';
    $show = array();
    $show['adminUserList'] = $adminUserList;
    $this->render('newuser', $show);
  }

  /**
   * @desc 管理员权限设置
   */
  private function doSetuser () {
    $action = $this->getSafeRequest('action');
    $uid = $this->getSafeRequest('uid', 0, 'GET', 'int');

    //获取管理员信息
    $adminUserInfo = $this->getUserService()->getBkAdminUserByUid($uid);

    //保存设置
    if ($action == 'save') {
      $permissionIds = $this->getSafeRequest('permission_ids', '', 'POST', 'array');
      if (!$permissionIds) $permissionIds = array();

      //计算差值
      $delPermissionIds = array_diff($adminUserInfo['permission_ids'], $permissionIds);
      if ($delPermissionIds) {
        $this->getUserService()->deleteBkAdminUserPermission($uid, $delPermissionIds);
      }

      $addPermissionIds = array_diff($permissionIds, $adminUserInfo['permission_ids']);
      if ($addPermissionIds) {
        foreach ($addPermissionIds as $permissionId) {
          $this->getUserService()->saveBkAdminUserPermission(array(
            'uid' => $uid,
            'permission_id' => $permissionId
          ));
        }
      }

      $this->outputJsonData(array('code' => 1));
    }

    //获取权限点
    $adminPermissionList = $this->getBkAdminService()->getLeftMenu();

    $this->title = '管理员权限设置';
    $show = array();
    $show['adminUserInfo'] = $adminUserInfo;
    $show['adminPermissionList'] = $adminPermissionList;
    $show['adminUserInfo'] = $adminUserInfo;
    $this->render('setuser', $show);
  }

  /**
   * @desc 马甲账号管理
   */
  private function doVestuser () {
    $action = $this->getSafeRequest('action');
    $uid = $this->getSafeRequest('uid', 0, 'GET', 'int');
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');

    $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
    
    //查询马甲
    $pageSize = 20;
    $vestUserList = array();
    if ($action == 'search') {
      if ($this->isSystemAdmin) {
        if (!$uid) $uid = $officialUserInfo['uid'];
      } else {
        $uid = $this->bkAdminUser['uid'];
      }

      $userVests = $this->getUserService()->getBkAdminUserVests($uid, $page, $pageSize);
      if ($userVests) {
        foreach ($userVests as $userVest) {

          if (!$userVest['online_uid']) continue;

          $user = $this->getUserService()->getUserByUid($userVest['online_uid'], TRUE);
          $user['status'] = $this->getUserFortuneService()->getUserFortuneStatusByUid($userVest['online_uid']);
          $user['level'] = $this->getUserService()->getUserLevel($user['status']['points']);
          $user['owner_uid'] = $userVest['uid'];
          $user['is_robot_vest'] = $userVest['is_robot_vest'];
          $user['cdate'] = Utils::getDiffTime($userVest['created_time']);
          $user['update'] = $userVest['created_time'] == $userVest['updated_time'] ? '未活动' : Utils::getDiffTime($userVest['updated_time']);

          $vestUserList[] = $user;
        }
      }
      
      //分页处理
      $count = self::DEFAULT_PAGER_PAGESIZE * $pageSize;
      $vestUserCount = count($vestUserList);
      if ($vestUserCount < $pageSize) {
        $count = ($page - 1) * $pageSize + $vestUserCount;
      }

    //添加马甲&编辑昵称
    } elseif ($action == 'save') {
      $ouid = $this->getSafeRequest('ouid', 0, 'GET', 'int');
      $nickname = $this->getSafeRequest('nickname', '', 'POST');
      $password = $this->getSafeRequest('password', '', 'POST');
      $ip = Yii::app()->request->userHostAddress;

      //编辑昵称
      if ($ouid) {
        $user = $this->getUserService()->getUserByUid($ouid);
        if ($user['uid']) {
          if ($nickname == $user['nickname'] && !$password) {
            $this->outputJsonData(array('code' => 2));
          } else {
            $fields = array();
            $fields['reg_ip'] = $ip;
            $fields['nickname'] = $nickname;
            if ($password != $user['password']) $fields['password'] = $password;
            if ($this->getUserService()->updateUser($user['uid'], $fields)) {
              $this->outputJsonData(array('code' => 1));
            }
          }
        }

      //创建新马甲
      } else {
        $fields = array();
        $fields['reg_ip'] = $ip;
        $fields['nickname'] = $nickname;
        if ($password) $fields['password'] = $password;
        $user = $this->getUserService()->addUser($fields);
        if ($user['uid']) {
          //初始化用户状态数
          $this->getUserFortuneService()->initUserFortuneStatus($user);
          
          if ($this->getUserService()->addBkAdminUserVest(array(
            'uid' => $this->bkAdminUser['uid'],
            'online_uid' => $user['uid']
          ))) {
            
            //同时添加到值班账号
            if ($this->bkAdminUser['uid'] != $officialUserInfo['uid']) {
              $this->getUserService()->addBkAdminUserVest(array(
                'uid' => $officialUserInfo['uid'],
                'online_uid' => $user['uid']
              ));
            }

            $this->outputJsonData(array('code' => 1));
          }
        }
      }
    }

    //管理员列表
    $adminUserList = $this->getUserService()->getBkAdminUsers();

    $this->title = '马甲账号管理';
    $show = array();
    $show['adminUserList'] = $adminUserList;
    $show['defaultPassword'] = $this->getUserService()->getDefaultPassword();
    $show['uid'] = $uid;
    $show['vestUserList'] = $vestUserList;
    $show['curPage'] = $page;
    $show['pager'] = $this->getPager($count, $page, $pageSize);
    $this->render('vestuser', $show);
  }
}
?>
