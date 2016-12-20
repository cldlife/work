<?php
class UserController extends BaseController {
  
  //昵称字数限制
  const MAX_NICKNAME_LENGTH = 15;
  //签名字数限制
  const MAX_SIGN_LENGTH = 30;
  
  /**
   * @desc 手机号/ID登录
   */
  public function actionLogin() {
    $mobile = $this->getSafeRequest('mobile', NULL, 'int');
    $passwd = $this->getSafeRequest('passwd');
    
    //参数验证
    if (!$mobile || !$passwd) $this->outputJsonData(1001);
    
    //用户ID登录
    if (strlen($mobile) < 10) {
      $user = $this->getUserService()->getUserByUid($mobile);
    } else {
      if (!Utils::checkMobile($mobile)) $this->outputJsonData(1, array(
        'apptip' => '手机号格式错误'
      ));
      $user = $this->getUserService()->getUserByMobile($mobile);
    }
    
    //验证用户权限
    if (!$user['uid'] || $user['password'] != md5($passwd)) {
      $this->outputJsonData(1, array(
        'apptip' => '手机号或密码错误'
      ));
    }
    
    if ($user['private_key']) {
      //生成会话
      $suuid = $this->getUserService()->generateUserSessionToken($this->currentClientId, $user['uid'], $user['private_key']);
      
      //验证是否需要完善资料
      $userStatus = $this->getUserFortuneService()->getUserFortuneStatusByUid($user['uid']);
      $isNeedEdit = $userStatus ? $userStatus['is_need_edit'] : 1;
      
      $this->outputJsonData(0, array(
      	's_uuid' => $suuid,
        'user_info' => array(
          'nickname' => $user['is_init_nickname'] ? '' : $user['nickname'],
          'avatar' => $user['avatar'],
          'gender' => $user['gender_name'],
          'birthday' => $user['birthday'],
        ),
        'is_need_edit' => $isNeedEdit,
      ));
    } else {
      $this->outputJsonData(1, array(
        'apptip' => '登录失败，请重新试试'
      ));
    }
  }
  
  /**
   * @desc 微信登录
   */
  public function actionLoginWeixin() {
    $unionid = $this->getSafeRequest("unionid");
    $openid = $this->getSafeRequest("openid");
    $nickname = $this->getSafeRequest("nickname");
    $gender = $this->getSafeRequest("gender");
    $avatar = $this->getSafeRequest("avatar");
    $accessToken = $this->getSafeRequest("access_token");
    $expiresIn = $this->getSafeRequest("expires_in", 7776000, 'int');
    $refreshToken = $this->getSafeRequest("refresh_token");
    $location = $this->getSafeRequest("location");
    $sign = $this->getSafeRequest("sign");
    $ip = Yii::app()->request->userHostAddress;
    
    //参数验证
    if (!$unionid || !$openid || !$nickname || !$gender || !$accessToken) $this->outputJsonData(1004);
    if (strlen($openid) > 32) $this->outputJsonData(1005);
    
    //登录
    $user = $this->getUserService()->loginByWeixin(array(
      'appid' => Yii::app()->params['wx_open_config']['appid'],
      'unionid' => $unionid,
      'openid' => $openid,
      'nickname' => $nickname,
      'gender' => $gender,
      'avatar' => $avatar,
      'access_token' => $accessToken,
      'expires_in' => $expiresIn,
      'refresh_token' => $refreshToken,
      'location' => $location,
      'sign' => $sign,
      'reg_ip' => $ip,
      'wx_from' => 1
    ));

    if ($user['user']['uid'] && $user['user']['private_key']) {
      //生成会话
      $suuid = $this->getUserService()->generateUserSessionToken($this->currentClientId, $user['user']['uid'], $user['user']['private_key']);
      
      //验证是否需要完善资料
      $userStatus = $this->getUserFortuneService()->getUserFortuneStatusByUid($user['user']['uid']);
      $isNeedEdit = $userStatus ? $userStatus['is_need_edit'] : 1;
      
      $this->outputJsonData(0, array(
        's_uuid' => $suuid,
        'user_info' => array(
          'nickname' => $user['user']['nickname'],
          'avatar' => $user['user']['avatar'],
          'gender' => $user['user']['gender_name'],
          'birthday' => $user['user']['birthday'],
        ),
        'is_need_edit' => $isNeedEdit,
      ));
    } else {
      $this->outputJsonData(1, array(
        'apptip' => '登录失败，请重新试试'
      ));
    }
  }
  
  /**
   * @desc 手机注册
   */
  public function actionSignup() {
    $mobile = $this->getSafeRequest("mobile", 0, 'int');
    $passwd = $this->getSafeRequest("passwd");
    $mcode = $this->getSafeRequest("mcode", 0, 'int');
    $ip = Yii::app()->request->userHostAddress;
    
    //参数验证
    if (!$mobile || !$passwd || !$mcode) $this->outputJsonData(1000, array(
      'apptip' => '验证码错误'
    ));
    if (!Utils::checkMobile($mobile)) $this->outputJsonData(1, array(
  	  'apptip' => '手机号格式错误'
    ));
    
    //验证码验证
    $SMSType = 1;
    $mobileSMScode = $this->getMessageService()->getMobileSMScode($mobile, $SMSType);
    if (!$mobileSMScode || $mobileSMScode['is_expired'] || intval($mobileSMScode['code']) != intval($mcode)) {
      $this->outputJsonData(1, array(
        'apptip' => '验证码错误'
      ));
    }
    
    //验证手机号是否已注册
    $user = $this->getUserService()->getUserByMobile($mobile);
    if ($user['uid']) {
      $this->outputJsonData(1, array(
        'apptip' => '该手机号已被占用'
      ));
    }
    
    //注册
    $regUser = $this->getUserService()->regUserInfo(array(
      'mobile' => $mobile,
      'password' => $passwd,
      'reg_ip' => $ip,
    ));
    
    //生成会话
    if ($regUser['uid'] && $regUser['private_key']) {
      //删除验证码
      $this->getMessageService()->deleteMobileSMScode($mobile, $SMSType);
      $suuid = $this->getUserService()->generateUserSessionToken($this->currentClientId, $regUser['uid'], $regUser['private_key']);
      
      $this->outputJsonData(0, array(
        's_uuid' => $suuid,
        'user_info' => (object) array(),
        'is_need_edit' => 1,
      ));
    } else {
      $this->outputJsonData(1, array(
        'apptip' => '注册失败，请重新试试'
      ));
    }
  }
  
  /**
   * @desc 找回密码
   */
  public function actionFindPasswd() {
    $mobile = $this->getSafeRequest("mobile", 0, 'int');
    $passwd = $this->getSafeRequest("passwd");
    $mcode = $this->getSafeRequest("mcode", 0, 'int');
    $ip = Yii::app()->request->userHostAddress;
  
    //参数验证
    if (!$mobile || !$passwd || !$mcode) $this->outputJsonData(1000, array(
      'apptip' => '验证码错误'
    ));
    if (!Utils::checkMobile($mobile)) $this->outputJsonData(1, array(
      'apptip' => '手机号格式错误'
    ));
    
    //验证手机号是否已注册
    $user = $this->getUserService()->getUserByMobile($mobile);
    if (!$user['uid']) {
      $this->outputJsonData(1, array(
        'apptip' => '该账号不存在'
      ));
    }
    
    //验证码验证
    $SMSType = 2;
    $mobileSMScode = $this->getMessageService()->getMobileSMScode($mobile, $SMSType);
    if (!$mobileSMScode || $mobileSMScode['is_expired'] || intval($mobileSMScode['code']) != intval($mcode)) {
      $this->outputJsonData(1, array(
        'apptip' => '验证码错误'
      ));
    }
    
    //修改密码
    $suuid = '';
    if ($user['password'] == md5($passwd)) {
      $suuid = $this->getUserService()->generateUserSessionToken($this->currentClientId, $user['uid'], $user['private_key']);
      
      //删除验证码
      $this->getMessageService()->deleteMobileSMScode($mobile, $SMSType);
    } else {
      $res = $this->getUserService()->updateUser($user['uid'], array(
        'password' => $passwd,
        'reg_ip' => $ip
      ));
      if ($res['private_key']) {
        $suuid = $this->getUserService()->generateUserSessionToken($this->currentClientId, $user['uid'], $res['private_key']);
        
        //删除验证码
        $this->getMessageService()->deleteMobileSMScode($mobile, $SMSType);
      }
    }
    
    $data = array();
    $data['s_uuid'] = $suuid;
    $data['apptip'] = '设置成功';
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 绑定手机且设置密码
   */
  public function actionBindMobile() {
    $mobile = $this->getSafeRequest("mobile", 0, 'int');
    $mcode = $this->getSafeRequest("mcode", 0, 'int');
    $passwd = $this->getSafeRequest("passwd");
    $ip = Yii::app()->request->userHostAddress;
    
    //参数验证
    if (!$mcode || !$passwd) $this->outputJsonData(1011);
      
    //绑定手机
    if (!Utils::checkMobile($mobile)) $this->outputJsonData(1, array(
      'apptip' => '手机号格式错误'
    ));
    
    //验证手机号是否绑定
    $userMobileIndex = $this->getUserService()->getUserByMobile($mobile);
    if ($userMobileIndex['uid']) {
      $this->outputJsonData(1, array(
        'apptip' => '该手机号已绑定'
      ));
    }
    
    //验证码验证
    $SMSType = 3;
    $mobileSMScode = $this->getMessageService()->getMobileSMScode($mobile, $SMSType);
    if (!$mobileSMScode || $mobileSMScode['is_expired'] || intval($mobileSMScode['code']) != intval($mcode)) {
      $this->outputJsonData(1, array(
        'apptip' => '验证码错误'
      ));
    }
    
    //更新用户手机号&密码
    $res = $this->getUserService()->updateUser($this->currentUser['uid'], array(
      'mobile' => $mobile,
      'password' => $passwd,
      'reg_ip' => $ip
    ));
    if ($res['private_key']) {
    
      //更新用户状态（已绑定手机&已设置密码）
      $this->getUserService()->addUserMobileIndex($this->currentUser['uid'], $mobile);
      $this->getUserFortuneService()->updateUserFortuneStatusByUid($this->currentUser['uid'], array(
        'is_binded_mobile' => 1,
        'is_setted_passwd' => 1
      ));
      
      //生成会话id
      $suuid = $this->getUserService()->generateUserSessionToken($this->currentClientId, $this->currentUser['uid'], $res['private_key']);
    }
    
    //删除验证码
    $this->getMessageService()->deleteMobileSMScode($mobile, $SMSType);
    
    $this->outputJsonData(0, array(
      's_uuid' => $suuid,
      'apptip' => '绑定成功'
    ));
    
    $this->outputJsonData(1);
  }
  
  /**
   * @desc 获取用户详细资料
   */
  public function actionProfile() {
    $uid = $this->getSafeRequest('uid', 0, 'int');
  
    $userInfo = array();
  
    //验证用户是否存在
    if ($uid && $uid != $this->currentUser['uid']) {
      $user = $this->getUserService()->getUserByUid($uid);
      if (!$user) $pseudoUser = $this->getGameService()->getPseudoUserByUid($uid);//机器人信息
      if ($pseudoUser) $user = $pseudoUser;
      
      if (!$user) $this->outputJsonData(400, array(
        'apptip' => '该用户不存在或已删除'
      ));
      $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
      
      //验证用户好友关系
      $friend = FALSE;
      if ($user['uid'] == $officialUserInfo['uid']) $friend = TRUE;
      if (!$friend) $friend = $this->getUserMineService()->getMineFriendByFriendUid($this->currentUser['uid'], $user['uid']);
      $user['friend_status'] = $friend ? 1 : 0;
  
      //获取用户状态总数
      $user['status'] = $this->getUserFortuneService()->getUserFortuneStatusByUid($user['uid']);
      $userInfo = $this->getUserService()->getUserProfile($user, FALSE);
    } else {
      $userInfo = $this->getUserService()->getUserProfile($this->currentUser);
    }
    
    //统一json对象格式
    $userInfo['region'] = (object) $userInfo['region'];
  
    //更改绑定中都设置为已绑定
    if ($userInfo['status']['is_binded_mobile'] > 0) {
      $userInfo['status']['is_binded_mobile'] = 1;
    } else {
      if ($userInfo['mobile']) {
        $this->getUserFortuneService()->updateUserFortuneStatusByUid($uid, array('is_binded_mobile' => 1));
        $userInfo['status']['is_binded_mobile'] = 1;
      }
    }
    
    //获取融云用户token
    $rcUserToken = '';
    if (!$this->currentUser['status']['is_need_edit'] && $userInfo['status']['is_mine']) {
      $rcUserToken = $this->getMessageService()->getRcUserToken($userInfo)['token'];
    }

    $this->outputJsonData(0, array(
      'user_info' => $userInfo,
      'is_need_edit' => $this->currentUser['status']['is_need_edit'],
      'rc_user_token' => $rcUserToken,
    ));
  }
  
  /**
   * @desc 更新我的资料
   */
  public function actionEditProfile() {
    $newProfile = $this->getSafeRequest('new_profile', array(), 'json');
    
    //修改头像
    $file = $this->currentClientId == 1 ? Yii::app()->params['upload_token'] : Yii::app()->params['upload_token_android'];
    $uploadRes = $this->getAttachmentService()->uploadAvatar($_FILES[$file]['tmp_name'], 0);
    if ($uploadRes['code'] == 1 && $uploadRes['fileInfo']) $newProfile['avatar'] = WEB_QW_APP_DYNAMIC_FILE_DOMAIN . $uploadRes['fileInfo']['file_uri'] . $uploadRes['fileInfo']['file_name'] . '/200';
    
    if ($newProfile) {
      
      //修改好友门槛（friending_rose_id:选项id）
      if ($newProfile['friending_rose_id']) {
        $friendingRoseInfo = $this->globalAttributions['friending_roses'][$newProfile['friending_rose_id']-1];
        if ($friendingRoseInfo && $friendingRoseInfo['roses'] != $this->currentUser['status']['friending_roses']) {
          if ($this->getUserFortuneService()->updateUserFortuneStatusByUid($this->currentUser['uid'], array('friending_roses' => intval($friendingRoseInfo['roses'])))) {
            $this->currentUser['status']['friending_roses'] = $friendingRoseInfo['roses'];
          }
        }
        unset($newProfile['friending_rose_id']);
      }
      
      //昵称禁止使用：官方指定文案词汇（如玩主、官方等词组）
      if ($newProfile['nickname'] == $this->currentUser['nickname']) unset($newProfile['nickname']);
      if ($newProfile['nickname']) {
  
        //后台管理用户不受限制
        if (!in_array($this->currentUser['uid'], $this->getBkAdminService()->getBkmanUids())) {
  
          //昵称15个字
          if (mb_strlen($newProfile['nickname'], APP_DEFAULT_CHARACTER) > self::MAX_NICKNAME_LENGTH) {
            $this->outputJsonData(1, array(
              'apptip' => '昵称不能超过15个字！'
            ));
          }
  
          if (preg_match("/(玩主|小主|wanzhu|官方|客服)/Ui", str_replace(' ', '', $newProfile['nickname']))) {
            $this->outputJsonData(1, array(
              'apptip' => '该昵称含有关键词，请更换哦！'
            ));
          }
        }
  
        //验证用户名是否已经存在
        $userNicknameIndex = $this->getUserService()->getUserNicknameIndex($newProfile['nickname']);
        if ($userNicknameIndex) $this->outputJsonData(1, array(
          'apptip' => '该昵称已经存在，请更换哦！'
        ));
      }
      
      //更新用户信息
      $updatedFields = array();
      if ($newProfile['avatar'] != $this->currentUser['avatar']) $updatedFields['avatar'] = $newProfile['avatar'];
      if ($newProfile['nickname'] != $this->currentUser['nickname']) $updatedFields['nickname'] = $newProfile['nickname'];
      if ($newProfile['gender'] != $this->currentUser['gender_name']) $updatedFields['gender'] = $newProfile['gender'];
      if ($newProfile['birthday'] != $this->currentUser['birthday']) $updatedFields['birthday'] = $newProfile['birthday'];
      $updatedUser = $this->getUserService()->updateUser($this->currentUser['uid'], $updatedFields);
      if ($updatedUser) {
        
        //设置昵称
        if ($updatedUser['nickname']) {
          $userNicknameIndex = $this->getUserService()->getUserNicknameIndex($this->currentUser['nickname']);
          if ($userNicknameIndex) $this->getUserService()->deleteUserNicknameIndex($this->currentUser['uid'], $this->currentUser['nickname']);
          $this->getUserService()->addUserNicknameIndex($this->currentUser['uid'], $newProfile['nickname']);
        }
        
        //重新获取用户信息
        $user = $this->getUserService()->getUserByUid($this->currentUser['uid']);
        $user['status'] = $this->currentUser['status'];
        $this->currentUser = $user;
        
        //验证是否需要完善资料
        if ($user['status']['is_need_edit'] && !$user['is_init_nickname'] && $user['avatar'] && $user['birthday']) {
          $this->getUserFortuneService()->updateUserFortuneStatusByUid($this->currentUser['uid'], array('is_need_edit' => 0));
          $this->currentUser['status']['is_need_edit'] = 0;
        }
        
        //刷新融云用户信息（昵称或头像）
        if ($updatedFields['nickname'] || $updatedFields['avatar']) {
          $this->getMessageService()->refreshRcUserInfo($user);
        }
      }
      
      $userInfo = $this->getUserService()->getUserProfile($this->currentUser);
      $userInfo['region'] = (object) $userInfo['region'];
      
      //获取融云用户token
      $rcUserToken = '';
      if (!$this->currentUser['status']['is_need_edit']) {
        $rcUserToken = $this->getMessageService()->getRcUserToken($userInfo)['token'];
      }
  
      $this->outputJsonData(0, array(
        'apptip' => '保存成功',
        'user_info' => $userInfo,
        'is_need_edit' => $this->currentUser['status']['is_need_edit'],
        'rc_user_token' => $rcUserToken,
      ));
    }
  
    $this->outputJsonData(1);
  }
  
  /**
   * @desc 获取我的特权列表
   */
  public function actionPrivileges() {
    $data = array();
    $data['list']['public_thread'] = $this->currentUser['status']['privilege_public_num'] ? 1 : 0;
    $this->outputJsonData(0, $data);
  }
}
