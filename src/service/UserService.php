<?php
/**
 * @desc 用户Service
 */
class UserService extends BaseService {

  //生成 auth_id 的密钥
  const AUTH_ID_TOKEN = 'AUTH_ID_TOKEN_V1';
  
  //第3方登录用户（或管理后台马甲用户等）初始密码
  const DEFAULT_THIRD_USER_PASSWORD = 'wz201609@qw';
  public static function getDefaultPassword() {
    return self::DEFAULT_THIRD_USER_PASSWORD;
  }
  
  //官方管理用户信息
  private static $officialUserInfo = array('uid' => 51000, 'nickname' => '小主', 'avatar' => 'https://ss.wanzhucdn.com/ui/img/app/xiaozhu300.png');
  public static function getOfficialUserInfo () {
    return self::$officialUserInfo;
  }
  
  private function getUserDAO() {
    return DAOFactory::getInstance()->createUserDAO();
  }
  
  /**
   * @desc 积分等级规则
   */
  private static $levelRules = array(
    array('id' => 1, 'min_pionts' => 0, 'max_pionts' => 50),
    array('id' => 2, 'min_pionts' => 50, 'max_pionts' => 150),
    array('id' => 3, 'min_pionts' => 150, 'max_pionts' => 200),
    array('id' => 4, 'min_pionts' => 200, 'max_pionts' => 250),
    array('id' => 5, 'min_pionts' => 250, 'max_pionts' => 300),
    array('id' => 6, 'min_pionts' => 300, 'max_pionts' => 350),
    array('id' => 7, 'min_pionts' => 350, 'max_pionts' => 400),
    array('id' => 8, 'min_pionts' => 400, 'max_pionts' => 450),
    array('id' => 9, 'min_pionts' => 450, 'max_pionts' => 500),
    array('id' => 10, 'min_pionts' => 500, 'max_pionts' => 700),
    array('id' => 11, 'min_pionts' => 900, 'max_pionts' => 900),
    array('id' => 12, 'min_pionts' => 900, 'max_pionts' => 1100),
    array('id' => 13, 'min_pionts' => 1100, 'max_pionts' => 1300),
    array('id' => 14, 'min_pionts' => 1300, 'max_pionts' => 1500),
    array('id' => 15, 'min_pionts' => 1500, 'max_pionts' => 1700),
    array('id' => 16, 'min_pionts' => 1700, 'max_pionts' => 1900),
    array('id' => 17, 'min_pionts' => 1900, 'max_pionts' => 2100),
    array('id' => 18, 'min_pionts' => 2100, 'max_pionts' => 2300),
    array('id' => 19, 'min_pionts' => 2300, 'max_pionts' => 2500),
    array('id' => 20, 'min_pionts' => 2500, 'max_pionts' => 2800),
    array('id' => 21, 'min_pionts' => 2800, 'max_pionts' => 3100),
    array('id' => 22, 'min_pionts' => 3100, 'max_pionts' => 3400),
    array('id' => 23, 'min_pionts' => 3400, 'max_pionts' => 3700),
    array('id' => 24, 'min_pionts' => 3700, 'max_pionts' => 4000),
    array('id' => 25, 'min_pionts' => 4000, 'max_pionts' => 4300),
    array('id' => 26, 'min_pionts' => 4300, 'max_pionts' => 4600),
    array('id' => 27, 'min_pionts' => 4600, 'max_pionts' => 4900),
    array('id' => 28, 'min_pionts' => 4900, 'max_pionts' => 5200),
    array('id' => 29, 'min_pionts' => 5200, 'max_pionts' => 5500),
    array('id' => 30, 'min_pionts' => 5500, 'max_pionts' => 5900),
    array('id' => 31, 'min_pionts' => 5900, 'max_pionts' => 6400),
    array('id' => 32, 'min_pionts' => 6400, 'max_pionts' => 6900),
    array('id' => 33, 'min_pionts' => 6900, 'max_pionts' => 7400),
    array('id' => 34, 'min_pionts' => 7400, 'max_pionts' => 7900),
    array('id' => 35, 'min_pionts' => 7900, 'max_pionts' => 8400),
    array('id' => 36, 'min_pionts' => 8400, 'max_pionts' => 8900),
    array('id' => 37, 'min_pionts' => 8900, 'max_pionts' => 9400),
    array('id' => 38, 'min_pionts' => 9400, 'max_pionts' => 9900),
    array('id' => 39, 'min_pionts' => 9900, 'max_pionts' => 10400),
    array('id' => 40, 'min_pionts' => 10400, 'max_pionts' => 11000),
    array('id' => 41, 'min_pionts' => 11000, 'max_pionts' => 11600),
    array('id' => 42, 'min_pionts' => 11600, 'max_pionts' => 12200),
    array('id' => 43, 'min_pionts' => 12200, 'max_pionts' => 12800),
    array('id' => 44, 'min_pionts' => 12800, 'max_pionts' => 13400),
    array('id' => 45, 'min_pionts' => 13400, 'max_pionts' => 14000),
    array('id' => 46, 'min_pionts' => 14000, 'max_pionts' => 14600),
    array('id' => 47, 'min_pionts' => 14600, 'max_pionts' => 15200),
    array('id' => 48, 'min_pionts' => 15200, 'max_pionts' => 15800),
    array('id' => 49, 'min_pionts' => 15800, 'max_pionts' => 16400),
    array('id' => 50, 'min_pionts' => 16400, 'max_pionts' => 17400),
    array('id' => 51, 'min_pionts' => 17400, 'max_pionts' => 18400),
    array('id' => 52, 'min_pionts' => 18400, 'max_pionts' => 19400),
    array('id' => 53, 'min_pionts' => 19400, 'max_pionts' => 20400),
    array('id' => 54, 'min_pionts' => 20400, 'max_pionts' => 21400),
    array('id' => 55, 'min_pionts' => 21400, 'max_pionts' => 22400),
    array('id' => 56, 'min_pionts' => 22400, 'max_pionts' => 23400),
    array('id' => 57, 'min_pionts' => 23400, 'max_pionts' => 24400),
    array('id' => 58, 'min_pionts' => 24400, 'max_pionts' => 25400),
    array('id' => 59, 'min_pionts' => 25400, 'max_pionts' => 26400),
    array('id' => 60, 'min_pionts' => 26400, 'max_pionts' => 27900),
    array('id' => 61, 'min_pionts' => 27900, 'max_pionts' => 29400),
    array('id' => 62, 'min_pionts' => 29400, 'max_pionts' => 30900),
    array('id' => 63, 'min_pionts' => 30900, 'max_pionts' => 32400),
    array('id' => 64, 'min_pionts' => 32400, 'max_pionts' => 33900),
    array('id' => 65, 'min_pionts' => 33900, 'max_pionts' => 35400),
    array('id' => 66, 'min_pionts' => 35400, 'max_pionts' => 36900),
    array('id' => 67, 'min_pionts' => 36900, 'max_pionts' => 38400),
    array('id' => 68, 'min_pionts' => 38400, 'max_pionts' => 39900),
    array('id' => 69, 'min_pionts' => 39900, 'max_pionts' => 41400),
    array('id' => 70, 'min_pionts' => 41400, 'max_pionts' => 43400),
    array('id' => 71, 'min_pionts' => 43400, 'max_pionts' => 45400),
    array('id' => 72, 'min_pionts' => 45400, 'max_pionts' => 47400),
    array('id' => 73, 'min_pionts' => 47400, 'max_pionts' => 49400),
    array('id' => 74, 'min_pionts' => 49400, 'max_pionts' => 51400),
    array('id' => 75, 'min_pionts' => 51400, 'max_pionts' => 53400),
    array('id' => 76, 'min_pionts' => 53400, 'max_pionts' => 55400),
    array('id' => 77, 'min_pionts' => 55400, 'max_pionts' => 57400),
    array('id' => 78, 'min_pionts' => 57400, 'max_pionts' => 59400),
    array('id' => 79, 'min_pionts' => 59400, 'max_pionts' => 61400),
    array('id' => 80, 'min_pionts' => 61400, 'max_pionts' => 63900),
    array('id' => 81, 'min_pionts' => 63900, 'max_pionts' => 66400),
    array('id' => 82, 'min_pionts' => 66400, 'max_pionts' => 68900),
    array('id' => 83, 'min_pionts' => 68900, 'max_pionts' => 71400),
    array('id' => 84, 'min_pionts' => 71400, 'max_pionts' => 73900),
    array('id' => 85, 'min_pionts' => 73900, 'max_pionts' => 76400),
    array('id' => 86, 'min_pionts' => 76400, 'max_pionts' => 78900),
    array('id' => 87, 'min_pionts' => 78900, 'max_pionts' => 81400),
    array('id' => 88, 'min_pionts' => 81400, 'max_pionts' => 83900),
    array('id' => 89, 'min_pionts' => 83900, 'max_pionts' => 86400),
    array('id' => 90, 'min_pionts' => 86400, 'max_pionts' => 89400),
    array('id' => 91, 'min_pionts' => 89400, 'max_pionts' => 92400),
    array('id' => 92, 'min_pionts' => 92400, 'max_pionts' => 95400),
    array('id' => 93, 'min_pionts' => 95400, 'max_pionts' => 98400),
    array('id' => 94, 'min_pionts' => 98400, 'max_pionts' => 101400),
    array('id' => 95, 'min_pionts' => 101400, 'max_pionts' => 104400),
    array('id' => 96, 'min_pionts' => 104400, 'max_pionts' => 107400),
    array('id' => 97, 'min_pionts' => 107400, 'max_pionts' => 110400),
    array('id' => 98, 'min_pionts' => 110400, 'max_pionts' => 113400),
    array('id' => 99, 'min_pionts' => 113400, 'max_pionts' => 0),
  );
  
  //地区 fields
  private static $regionFields = array(
    'country_id', 'province_id', 'city_id', 'district_id'
  );
  //性别 mapping
  private static $genderEnum = array(0 => '保密', 1 => '男', 2 => '女');
  //weibo,QQ第三方用户性别mapping
  private static $openGenderEnum = array('n' => '保密', 'm' => '男', 'f' => '女', '男' => '男', '女' => '女');
  public function getGender($genderKey = 0) {
    $gender = self::$genderEnum[$genderKey]; 
    if (!$gender) {
      $gender = self::$openGenderEnum[$genderKey] ? self::$openGenderEnum[$genderKey] : self::$genderEnum[0];
    }
    return $gender;
  }
  
  /**
   * @desc 生日
   * @param int $birthday 
   * @param int $displayType 展现形式，0: --，1: ..，2: 年月日
   * @return string
   */
  public function getBirthdayDesc ($birthday, $displayType = 0) {
    if (!$birthday) return '';
    if ($displayType == 2) {
      return preg_replace('/(\d{4})(\d{2})(\d{2})/', '$1年$2月$3日', $birthday);
    } elseif ($displayType == 1) {
      return preg_replace('/(\d{4})(\d{2})(\d{2})/', '$1.$2.$3', $birthday);
    } else {
      return preg_replace('/(\d{4})(\d{2})(\d{2})/', '$1-$2-$3', $birthday);
    }
  }
  
  /**
   * @desc 生成用户会话auth_id
   * @param int $clientId：1-iOS，2-Android，3-微信登录授权，4-weibo登录授权，5-QQ登录授权
   * @return auth_token + sid
   * auth_token => md5(AUTH_ID_TOKEN + clientId + uid) 密匙token
   */
  private function createAuthId($clientId, $uid, $sid) {
    $authId = md5(self::AUTH_ID_TOKEN . '_' . $clientId . '_' . $uid) . '_' . $sid;
    return $authId;
  }
  
  /**
   * @desc 生成用户会话密匙$suuid
   * @param string $privateKey 注册生成的用户私匙
   * @return base64_encode({$clientId}|{$uid}|{$authId}) 返回base64编码串
   * sid  => md5(private_key + uid + (time + expiresIn)) 授权设备唯一标识id
   */
  public function generateUserSessionToken($clientId, $uid, $privateKey, $expiresIn = 0) {
    if (!$clientId || !$uid || !$privateKey) {
      throw new Exception('clientId, uid or privateKey is null...');
    }
  
    //会话是否存在
    $hasSession = FALSE;
  
    $userSession = $this->getUserDAO()->findUserSessionByUid($uid);
    if ($userSession) {
      $hasSession = TRUE;
  
      //更新最后会话时间 (sid不一致则更新)
      $sid = $userSession['sid'];
      $newSid = md5($privateKey . $uid . ($userSession['created_time'] + $userSession['expires_in']));
      if ($newSid != $sid) {
        if ($this->getUserDAO()->updateUserSession($uid, array(
          'sid' => $newSid,
          'updated_time' => time()
        ))) $sid = $newSid;
      }
    } else {
      //设置有效期, 默认3个月
      //TODO 暂时未用作验证
      $expiresIn = intval($expiresIn) ? intval($expiresIn) : 86400 * 90;
  
      //生成sid
      $nowTime = time();
      $sid = md5($privateKey . $uid . ($nowTime + $expiresIn));
      $fields = array();
      $fields['uid'] = $uid;
      $fields['sid'] = $sid;
      $fields['expires_in'] = $expiresIn;
      $fields['created_time'] = $nowTime;
      $hasSession = $this->getUserDAO()->insertUserSession($fields);
    }
  
    $authId = '';
    if ($uid && $sid && $hasSession) {
      $authId = $this->createAuthId($clientId, $uid, $sid);
    }
  
    return str_replace('=', '', base64_encode("{$clientId}|{$uid}|{$authId}")) . '==';
  }
  
  /**
   * @desc 验证用户会话 $authId
   * @return array $user 验证成功返回用户信息
   * @TODO 暂时未用作验证
   */
  public function authUserSessionToken($clientId, $uid, $authId) {
    $user = array();
    if ($clientId && $uid && $authId) {
      $userSession = $this->getUserDAO()->findUserSessionByUid($uid);
      if ($userSession) {
        $tmpAuthId = $this->createAuthId($clientId, $userSession['uid'], $userSession['sid']);
        if ($tmpAuthId == $authId) {
          $user = $this->getUserByUid($userSession['uid']);
        }
      }
    }
  
    return $user;
  }
  
  /**
   * @desc 获取用户等级
   */
  public function getUserLevel ($points) {
    $userLevel = array();
    if (!$points) $points = 0;
    foreach (self::$levelRules as $levelRule) {
      if ($levelRule['max_pionts']) {
        if ($points >= $levelRule['min_pionts'] && $points < $levelRule['max_pionts']) {
          $userLevel = $levelRule;
          break;
        }
      } else {
        if ($points >= $levelRule['min_pionts']) {
          $userLevel = $levelRule;
          break;
        }
      }
    }
    return $userLevel;
  }
  
  /**
   * @desc 用户资料
   * @param array $user
   * @param boolean $isMine 是否是用户自己
   */
  public function getUserProfile($user, $isMine = TRUE) {
    $profile = array();
    if ($user) {
      //用户等级
      $userLevel = $this->getUserLevel($user['status']['points']);
      
      //是否是初始的昵称
      $profile['uid'] = $user['uid'];
      $profile['nickname'] = $user['is_init_nickname'] ? '' : $user['nickname'];
      $profile['mobile'] = $isMine ? Utils::hideMobileFourNumber($user['mobile']) : "";
      $profile['avatar'] = $user['avatar'];
      $profile['avatar_hd'] = $user['avatar'] ? preg_replace(array("/\/132$/", "/\/200$/"), array("/0", "/750"), $user['avatar']) : "";
      $profile['gender'] = $user['gender_name'];
      //$profile['sign'] = $user['sign'];
      $profile['birthday'] = $isMine ? $user['birthday'] : 0;
      $profile['age'] = Utils::getAgeFromDate($user['birthday']);
      $profile['region'] = $user['region'];
      
      //计算到下一级的积分差
      $diffPoints = 0;
      if ($userLevel['max_pionts']) $diffPoints = $userLevel['max_pionts'] - $user['status']['points'];
      
      $profile['level'] = array();
      $profile['level']['num'] = $userLevel['id'];
      $profile['level']['name'] = $userLevel['name'];
      $profile['level']['desc'] = $diffPoints ? "还差 {$diffPoints} 分升级到 V" . ($userLevel['id'] + 1) : '';
      
      $profile['status'] = array();
      $profile['status']['is_mine'] = $isMine ? 1 : 0;
      $profile['status']['is_binded_mobile'] = $isMine ? $user['status']['is_binded_mobile'] : 0;
      $profile['status']['is_setted_passwd'] = $isMine ? $user['status']['is_setted_passwd'] : 0;
      $profile['status']['roses'] = intval($user['status']['roses']);
      $profile['status']['points'] = $isMine ? $user['status']['points'] : 0;
      $profile['status']['coins'] = $isMine ? $user['status']['coins'] : 0;
      $profile['status']['friending_roses'] = intval($user['status']['friending_roses']);
      $profile['friend_status'] = $user['friend_status'] ? $user['friend_status'] : 0;
      
      //权限控制
      //修改性别权限：金币数大于5000
      $upgenderCoins = 5000;
      $profile['permissions'] = array();
      $profile['permissions']['upgender'] = ($isMine && $user['status']['coins'] >= $upgenderCoins) ? 1 : 0;
    }

    return $profile;
  }
  
  /**
   * @return 根据昵称获取用户uid索引
   */
  public function getUserNicknameIndex($nickname) {
    $userNicknameIndex = array();
    if ($nickname) {
       $userNicknameIndex = $this->getUserDAO()->findUserNicknameIndex($nickname);
    }
    return $userNicknameIndex;
  }
  
  /**
   * @desc 根据昵称模糊搜索用户nickname索引
   */
  public function getUserNicknamesIndexLikeNickname ($nickname, $page = 1, $pageSize = 20) {
    $userNicknamesIndex = array();
    $nickname = trim($nickname);
    if ($nickname && $page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $userNicknamesIndex = $this->getUserDAO()->findUserNicknamesIndexLikeNickname($nickname, $offset, $pageSize);
    }
    return $userNicknamesIndex;
  }
  
  /**
   * @desc 添加用户昵称uid索引
   */
  public function addUserNicknameIndex($uid, $nickname) {
    $nickname = trim($nickname);
    if ($uid && $nickname) {
      return $this->getUserDAO()->insertUserNicknameIndex($uid, $nickname);
    }
  
    return FALSE;
  }
  
  /**
   * @desc 删除用户昵称uid索引
   */
  public function deleteUserNicknameIndex($uid, $nickname) {
    $nickname = trim($nickname);
    if ($uid && $nickname) {
      return $this->getUserDAO()->deleteUserNicknameIndex($uid, $nickname);
    }
    
    return FALSE;
  }
  
  /**
   * @return 根据$uid获取用户信息
   * @param 如果无头像，是否返回默认头像
   */
  public function getUserByUid($uid, $showDefaultAvatar = FALSE) {
    $user = array();

    if ($uid) {
      $user = $this->getUserDAO()->findUserByUid($uid);
      if ($user) {
        
        //是否是初始的昵称
        $user['is_init_nickname'] = preg_match("/wz\d{6}$/i", $user['nickname']) ? TRUE : FALSE;
        
        //性别
        $user['gender_name'] = $this->getGender($user['gender']);
        
        //生日
        $user['birthday_desc'] = $this->getBirthdayDesc($user['birthday']);
        
        //HTTPS适配 & 无头像时，返回默认头像
        if ($user['avatar']) {
          $avatarUrl = parse_url($user['avatar']);
          $user['avatar'] = stripos($avatarUrl['host'], WEB_QW_APP_FILE_DOMAIN_SUFFIX) === FALSE ? Utils::urlToHttps($user['avatar']) : WEB_QW_APP_FILE_DOMAIN . $avatarUrl['path'];
        }
        if (!$user['avatar'] && $showDefaultAvatar) $user['avatar'] = WEB_QW_APP_FILE_DOMAIN . '/ui/img/m/avatar_none.png';
        
        //海外国家（无city_id时处理）
        if (!$user['city_id']) $user['city_id'] = $user['province_id'];
        if (!$user['city_id']) $user['city_id'] = $user['country_id'];
        
        //解析region json
        $userRegion = json_decode($user['region'], TRUE);
        $user['region'] = array();
        if ($userRegion) {
          $user['region']['name'] = $userRegion['name'];
          //$user['region']['selected'] = $userRegion['selected'];
          unset($userRegion);
        }
      }
    }
    
    return $user;
  }
  
  /**
   * @return 根据$mobile获取用户信息
   */
  public function getUserByMobile($mobile) {
    if (!$mobile) {
      throw new Exception('mobile is null...');
    }
  
    $user = array();
    $userMobileIndex = $this->getUserDAO()->findUserMobileIndex($mobile);
    if ($userMobileIndex['uid']) {
      $user = $this->getUserByUid($userMobileIndex['uid']);
    }
    
    return $user;
  }
  
  /**
   * @desc 添加用户手机索引记录
   */
  public function addUserMobileIndex($uid, $mobile) {
    if ($uid && $mobile) {
      return $this->getUserDAO()->insertUserMobileIndex($uid, $mobile);
    }
    
    return FALSE;
  }
  
  /**
   * @desc 删除用户手机索引记录
   */
  public function deleteUserMobileIndex($uid, $mobile) {
    if ($uid && $mobile) {
      return $this->getUserDAO()->deleteUserMobileIndex($uid, $mobile);
    }
  
    return FALSE;
  }
  
  /**
   * @desc 手机注册用户
   * @param array $fields
   */
  public function regUserInfo(Array $fields) {
    if (!$fields['mobile'] || !$fields['password'] || !$fields['reg_ip']) {
      throw new Exception('mobile, password or reg_ip is null...');
    }
    
    //无用户名，系统自动生成昵称 TODO
    if (!$fields['nickname']) {
      $fields['nickname'] = 'wz' . mt_rand(100000, 999999);
    }
    
    return $this->addUser($fields);
  }
  
  /**
   * @desc 添加用户
   * @param array $fields['reg_from'] 注册来源，0-手机，1-微信，2-QQ，3-微博
   */
  public function addUser (Array $fields) {
    $user = array();
    $fields['nickname'] = trim($fields['nickname']);
    if ($fields['nickname'] && $fields['reg_ip']) {
      $userFields = array();
      
      //性别
      $genderEnum = array_flip(self::$genderEnum);
      
      //获取所在地（第3方location匹配）
      $fields['region'] = array();
      if ($fields['location']) {
        $locationExp = explode(" ", trim($fields['location']));
        
        //获取省份、城市
        $provinceInfo = array();
        $cityInfo = array();
        if ($locationExp[0]) $provinceInfo = $this->getCommonService()->getRegionByName($locationExp[0], 'province');
        if ($provinceInfo['has_subitem'] && $locationExp[1]) $cityInfo = $this->getCommonService()->getRegionByName($locationExp[1], 'city');
        if ($provinceInfo) {
          $userFields['country_id'] = 1;
          $userFields['province_id'] = $provinceInfo['id'];
          
          $fields['region']['name'] = $provinceInfo['name'] . ($cityInfo['name'] ? ' ' . $cityInfo['name'] : '');
          $fields['region']['selected'] = array();
          $fields['region']['selected'][] = array('id' => 1, 'name' => '中国', 'has_subitem' => 1);
          $fields['region']['selected'][] = array('id' => $provinceInfo['id'], 'name' => $provinceInfo['name'], 'has_subitem' => intval($provinceInfo['has_subitem']));
          if ($cityInfo) {
            $userFields['city_id'] = $cityInfo['id'];
            $fields['region']['selected'][] = array('id' => $cityInfo['id'], 'name' => $cityInfo['name'], 'has_subitem' => intval($cityInfo['has_subitem']));
          }
        }
      }
      $userFields['region'] = json_encode($fields['region']);
      $userFields['nickname'] = $fields['nickname'];
      $userFields['password'] = $fields['password'] ? $fields['password'] : self::DEFAULT_THIRD_USER_PASSWORD;
      if ($fields['mobile']) $userFields['mobile'] = $fields['mobile'];
      if ($fields['avatar']) $userFields['avatar'] = $fields['avatar'];
      if ($fields['gender']) $userFields['gender'] = intval($genderEnum[$fields['gender']]);
      if ($fields['sign']) $userFields['sign'] = $fields['sign'];
      $userFields['reg_ip'] = $fields['reg_ip'];
      $userFields['reg_from'] = intval($fields['reg_from']);
      
      //写入用户信息
      $user = $this->getUserDAO()->insertUserInfo($userFields);
      if ($user) $user['region'] = json_decode($user['region'], TRUE);
    }
    return $user;
  }
  
  /**
   * @desc 更新用户信息
   */
  public function updateUser($uid, $fields = array()) {
    $user = array();
    if ($uid) {
      //过滤昵称前后空格
      if ($fields['nickname']) $fields['nickname'] = trim($fields['nickname']);
      
      //性别参数兼容字符串处理（如: 男/女）
      if ($fields['gender'] && !is_numeric($fields['gender'])) {
        $genderEnum = array_flip(self::$genderEnum);
        $fields['gender'] = $genderEnum[$fields['gender']];
      }
      
      //地区信息处理 region
      //TODO name需要从selected里去取，不作为参数（需修改逻辑）
      if ($fields['region'] && trim($fields['region']['name']) && $fields['region']['selected']) {
        $fields['region']['name'] = trim($fields['region']['name']);
        $tmpNameExp = explode(" ", $fields['region']['name']);
        if ($tmpNameExp[0] == $tmpNameExp[1]) $fields['region']['name'] = $tmpNameExp[0];
        unset($tmpNameExp);
        
        foreach (self::$regionFields as $i => $k) {
          $fields[$k] = intval($fields['region']['selected'][$i]['id']);
        }
        $fields['region'] = json_encode($fields['region']);
      }
      
      $user = $this->getUserDAO()->updateUser($uid, $fields);
    }
  
    return $user;
  }
  
  /**
   * @desc 第3方QQ用户登录
   */
  public function loginByQQ (Array $fields) {
    if (!$fields['openid'] || !$fields['nickname'] || !$fields['access_token'] || !$fields['reg_ip']) {
      throw new Exception('openid, nickname, accessToken, reg_ip or field is null...');
    }
  
    $user = array();
  
    //检测是否有注册
    $userQQIndex = $this->getUserQQIndex($fields['openid']);
    if ($userQQIndex['uid']) {
      $userQQInfo = $this->getUserQQInfo($userQQIndex['uid']);
      if ($userQQInfo) {
        //仅支持更新QQ昵称、头像
        if ($userQQInfo['nickname'] != $fields['nickname'] || ($fields['avatar'] && $userQQInfo['avatar'] != $fields['avatar'])) {
          $fields['updated_time'] = time();
          $userQQInfo = $this->getUserDAO()->updateUserQQInfo($userQQInfo['uid'], $fields);
        }
      } else {
        $fields['uid'] = $userQQIndex['uid'];
        $userQQInfo = $this->getUserDAO()->insertUserQQInfo($fields);
      }

      $user['user'] = $this->getUserByUid($userQQIndex['uid']);
      $user['qq_user'] = $userQQInfo;
      $user['is_new'] = FALSE;
    } else {
  
      //添加基本用户信息
      $fields['reg_from'] = 2;
      $newUser = $this->addUser($fields);
  
      //添加QQ信息
      if ($newUser['uid']) {
        $fields['uid'] = $newUser['uid'];
        if ($newUser['gender']) $fields['gender'] = $newUser['gender'];
        $newQQUser = $this->getUserDAO()->insertUserQQIndexAndInfo($fields);
      }
  
      if ($newQQUser) {
        $user['user'] = $newUser;
        $user['qq_user'] = $newQQUser;
        $user['is_new'] = TRUE;
      }
    }
    
    return $user;
  }
  
  /**
   * @desc 获取第3方QQ用户信息索引
   */
  public function getUserQQIndex($openid) {
    $userQQIndex = array();
    if ($openid) {
      $userQQIndex = $this->getUserDAO()->findUserQQIndex($openid);
    }
  
    return $userQQIndex;
  }
  
  /**
   * @desc 获取第3方QQ用户信息
   */
  public function getUserQQInfo($uid, $showDefaultAvatar = FALSE) {
    $userQQInfo = array();
    if ($uid) {
      $userQQInfo = $this->getUserDAO()->findUserQQInfo($uid);
      if ($userQQInfo && $showDefaultAvatar) {
        $userQQInfo['avatar'] = preg_replace(array("/\/40$/"), array("/100"), $userQQInfo['avatar']);
        $userQQInfo['avatar'] = $userQQInfo['avatar'] ? Utils::urlToHttps($userQQInfo['avatar']) : WEB_QW_APP_FILE_DOMAIN . '/ui/img/m/avatar_none.png';
      }
    }
  
    return $userQQInfo;
  }
  
  /**
   * @desc 第3方sina用户登录
   */
  public function loginBySina (Array $fields) {
    if (!$fields['sina_uid'] || !$fields['nickname'] || !$fields['access_token'] || !$fields['reg_ip']) {
      throw new Exception('sina_uid, nickname, accessToken, reg_ip or field is null...');
    }
  
    $user = array();
  
    //检测是否有注册
    $userSinaIndex = $this->getUserSinaIndex($fields['sina_uid']);
    if ($userSinaIndex['uid']) {
      $userSinaInfo = $this->getUserSinaInfo($userSinaIndex['uid']);
      if ($userSinaInfo) {
        //仅支持更新Weibo昵称、头像
        if ($userSinaInfo['nickname'] != $fields['nickname'] || ($fields['avatar'] && $userSinaInfo['avatar'] != $fields['avatar'])) {
          $fields['updated_time'] = time();
          $userSinaInfo = $this->getUserDAO()->updateUserSinaInfo($userSinaIndex['uid'], $fields);
        }
      } else {
        $fields['uid'] = $userSinaIndex['uid'];
        $userSinaInfo = $this->getUserDAO()->insertUserSinaInfo($fields);
      }

      $user['user'] = $this->getUserByUid($userSinaIndex['uid']);
      $user['sina_user'] = $userSinaInfo;
      $user['is_new'] = FALSE;
    } else {
  
      //添加基本用户信息
      $fields['reg_from'] = 3;
      $newUser = $this->addUser($fields);
  
      //添加sina信息
      if ($newUser['uid']) {
        $fields['uid'] = $newUser['uid'];
        $newSinaUser = $this->getUserDAO()->insertUserSinaIndexAndInfo($fields);
      }
  
      if ($newSinaUser) {
        $user['user'] = $newUser;
        $user['sina_user'] = $newSinaUser;
        $user['is_new'] = TRUE;
      }
    }
  
    return $user;
  }
  
  /**
   * @desc 获取第3方sina用户信息索引
   */
  public function getUserSinaIndex($sinaUid) {
    $userSinaIndex = array();
    if ($sinaUid) {
      $userSinaIndex = $this->getUserDAO()->findUserSinaIndex($sinaUid);
    }
  
    return $userSinaIndex;
  }
  
  /**
   * @desc 获取第3方sina用户信息
   */
  public function getUserSinaInfo($uid) {
    $userSinaInfo = array();
    if ($uid) {
      $userSinaInfo = $this->getUserDAO()->findUserSinaInfo($uid);
    }
  
    return $userSinaInfo;
  }
  
  /**
   * @desc 第3方微信用户登录
   * @param int $fields['wx_from'] 注册来源，1-app，2-公众号，3-web
   */
  public function loginByWeixin (Array $fields) {
    if (!$fields['appid'] || !$fields['unionid'] || !$fields['openid'] || !$fields['nickname'] || !$fields['reg_ip']) {
      throw new Exception('appid, unionid, openid, nickname, reg_ip or field is null...');
    }
  
    $user = array();
    
    //检测unionid是否有注册
    $userWeixinUnionidIndex = $this->getUserWeixinUnionidIndex($fields['unionid']);
    if ($userWeixinUnionidIndex['uid']) {
      $fields['uid'] = $userWeixinUnionidIndex['uid'];
      
      //检测weixin index
      $isNewWeixinUser = FALSE;
      $userWeixinOpenid = $this->getUserWeixinOpenidByUidAndAppid($userWeixinUnionidIndex['uid'], $fields['appid']);
      if (!$userWeixinOpenid) {
        $this->getUserDAO()->insertUserWeixinOpenid($fields);
        $this->getUserDAO()->insertUserWeixinOpenidIndex($fields);
        $isNewWeixinUser = TRUE;
      }
      
      //检测weixin info
      //更新微信昵称、头像
      $userWeixinInfo = $this->getUserWeixinInfo($userWeixinUnionidIndex['uid']);
      if ($userWeixinInfo) {
        if (($fields['nickname'] && $userWeixinInfo['nickname'] != $fields['nickname']) || ($fields['avatar'] && $userWeixinInfo['avatar'] != $fields['avatar'])) {
          $fields['updated_time'] = time();
          $userWeixinInfo = $this->getUserDAO()->updateUserWeixinInfo($userWeixinUnionidIndex['uid'], $fields);
        }
      } else {
        $userWeixinInfo = $this->getUserDAO()->insertUserWeixinInfo($fields);
      }
      
      $user['user'] = $this->getUserByUid($userWeixinUnionidIndex['uid']);
      $user['weixin_user'] = $userWeixinInfo;
      $user['weixin_user']['is_new'] = $isNewWeixinUser;
      $user['is_new'] = FALSE;
    } else {
  
      //添加基本用户信息
      $fields['reg_from'] = 1;
      $newUser = $this->addUser($fields);
  
      //添加weixin信息
      if ($newUser['uid']) {
        $fields['uid'] = $newUser['uid'];
        $newWeixinUser = $this->getUserDAO()->insertUserWeixinIndexAndInfo($fields);
      }
  
      if ($newWeixinUser) {
        $user['user'] = $newUser;
        $user['weixin_user'] = $newWeixinUser;
        $user['weixin_user']['is_new'] = TRUE;
        $user['is_new'] = TRUE;
      }
    }
  
    return $user;
  }
  
  /**
   * @desc 获取第3方微信unionid用户索引
   * @param string $unionid
   */
  public function getUserWeixinUnionidIndex($unionid) {
    $userWeixinUnionidIndex = array();
    if ($unionid) {
      $userWeixinUnionidIndex = $this->getUserDAO()->findUserWeixinUnionidIndex($unionid);
    }
    return $userWeixinUnionidIndex;
  }
  
  /**
   * @desc 获取第3方微信openid用户索引
   * @param string $openid
   */
  public function getUserWeixinOpenidIndex($openid) {
    $userWeixinOpenidIndex = array();
    if ($openid) {
      $userWeixinOpenidIndex = $this->getUserDAO()->findUserWeixinOpenidIndex($openid);
    }
    return $userWeixinOpenidIndex;
  }
  
  /**
   * @desc 获取第3方微信openid索引信息
   * @param int $uid
   * @param string $appid
   * @return array
   */
  public function getUserWeixinOpenidByUidAndAppid($uid, $appid) {
    $userWeixinOpenid = array();
    if ($uid && $appid) {
      $userWeixinOpenid = $this->getUserDAO()->findUserWeixinOpenidWithUidAndAppid($uid, $appid);
    }
    return $userWeixinOpenid;
  }
  
  /**
   * @desc 添加第3方微信openid索引信息
   * @param array fields
   * @return array
   */
  public function addUserWeixinOpenid ($fields) {
    $userWeixinOpenid = array();
    if ($fields['uid'] && $fields['appid'] && $fields['openid']) {
      $userWeixinOpenid = $this->getUserDAO()->insertUserWeixinOpenid($fields);
    }
    return $userWeixinOpenid;
  }

  /**
   * @desc 获取第3方微信用户信息
   * @param bool $showDefaultAvatar 是否返回默认头像
   */
  public function getUserWeixinInfo($uid, $showDefaultAvatar = FALSE) {
    $userWeixinInfo = array();
    if ($uid) {
      $userWeixinInfo = $this->getUserDAO()->findUserWeixinInfo($uid);
      if ($userWeixinInfo && $showDefaultAvatar) $userWeixinInfo['avatar'] = $userWeixinInfo['avatar'] ? Utils::urlToHttps($userWeixinInfo['avatar']) : WEB_QW_APP_FILE_DOMAIN . '/ui/img/m/avatar_none.png';
    }
  
    return $userWeixinInfo;
  }
  
  /**
   * @return 根据$uid获取管理后台用户&权限点
   * @param bool $detail 是否获取更详细的信息(包括app账号信息 & 管理员权限点集合)
   */
  public function getBkAdminUserByUid ($uid, $detail = TRUE) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
    $BkAdminUser = $this->getUserDAO()->findBkAdminUserByUid($uid);
    if ($detail && $BkAdminUser && $BkAdminUser['uid']) {
      $BkAdminUser['user_info'] = $this->getUserByUid($BkAdminUser['uid']);
      $BkAdminUser['permission_ids'] = array();
      $permissionIds = $this->getUserDAO()->findBkAdminUserPermissionByUid($BkAdminUser['uid']);
      foreach ($permissionIds as $permissionId) {
        $BkAdminUser['permission_ids'][] = $permissionId['permission_id'];
      }
    }
    return $BkAdminUser;
  }
  
  /**
   * @desc 获取管理后台用户列表
   */
  public function getBkAdminUsers ($page = 1, $pageSize = 20) {
    $users = array();
    
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $bkAdminUsers = $this->getUserDAO()->findBkAdminUsers($offset, $pageSize);
      if ($bkAdminUsers) {
        foreach ($bkAdminUsers as $bkAdminUser) {
          $bkAdminUser['user_info'] = $this->getUserByUid($bkAdminUser['uid']);
          $bkAdminUser['cdate'] = Utils::getDiffTime($bkAdminUser['created_time']);
          $bkAdminUser['last_login_time'] = $bkAdminUser['last_login_time'] ? Utils::getDiffTime($bkAdminUser['last_login_time']) : '无记录';
          $users[] = $bkAdminUser;
        }
      }
    }
    
    return $users;
  }
  
  /**
   * @desc new admin user
   */
  public function newBkAdminUser ($fields) {
    if (!$fields['uid'] || !$fields['admin_name']) {
      throw new Exception('uid or admin_name is null...');
    }
    
    return $this->getUserDAO()->insertBkAdminUser($fields);
  }
  
  /**
   * @desc update admin user
   */
  public function updateBkAdminUser ($uid, $fields) {
    if (!$uid || !$fields) {
      throw new Exception('uid is null...');
    }
    return $this->getUserDAO()->updateBkAdminUser($uid, $fields);
  }
  
  /**
   * @desc delete admin user
   */
  public function deleteBkAdminUser ($uid) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
    return $this->getUserDAO()->deleteBkAdminUser($uid);
  }
  
  /**
   * @desc save admin user permission
   */
  public function saveBkAdminUserPermission ($fields) {
    if (!$fields['uid'] || !$fields['permission_id']) {
      throw new Exception('uid or permission_id is null...');
    }
    
    return $this->getUserDAO()->insertBkAdminUserPermission($fields);
  }
  
  /**
   * @desc delete admin user permission
   */
  public function deleteBkAdminUserPermission ($uid, $permission_ids = array()) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
    $permission_ids = implode(',', $permission_ids);
    return $this->getUserDAO()->deleteBkAdminUserPermission($uid, $permission_ids);
  }
  
  /**
   * @desc 获取马甲用户列表
   */
  public function getBkAdminUserVests ($uid = 0, $page = 1, $pageSize = 20) {
    $userVests = array();
    
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $userVests =  $this->getUserDAO()->findBkAdminUserVests($uid, $offset, $pageSize);
    }
  
    return $userVests;
  }
  
  /**
   * @desc 获取单个马甲用户
   */
  public function getBkAdminUserVesterByUidAndOuid ($uid, $ouid) {
    if ($uid && $ouid) {
      return $this->getUserDAO()->findBkAdminUserVestByUidAndOuid($uid, $ouid);
    }
  
    return array();
  }
  
  /**
   * @desc 获取单个马甲用户
   */
  public function getBkAdminUserVesterByOuid ($ouid) {
    if ($ouid) {
      return $this->getUserDAO()->findBkAdminUserVestByOuid($ouid);
    }
  
    return array();
  }
  
  /**
   * @desc 添加马甲用户
   */
  public function addBkAdminUserVest ($fields) {
    if (!$fields['uid'] || !$fields['online_uid']) {
      throw new Exception('uid or online_uid is null...');
    }
    
    return $this->getUserDAO()->insertBkAdminUserVest($fields);
  }
  
  /**
   * @desc 更新马甲用户
   */
  public function updateBkAdminUserVestByUidAndOuid ($uid, $ouid, Array $fields) {
    if (!$uid || !$ouid) {
      throw new Exception('uid or ouid is null...');
    }
    
    return $this->getUserDAO()->updateBkAdminUserVestByUidAndOuid($uid, $ouid, $fields);
  }
}
