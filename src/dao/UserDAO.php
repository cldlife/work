<?php
/**
 * @desc 用户UserDAO
 */
class UserDAO extends BaseDao {
  
  //缓存key前缀
  const CACHE_PREFIX = 'USER_CACHE_';
  
  //缓存版本号
  const CACHE_VERSION = '111.0';
  
  const SINGLE_USER_CACHE_VERSION = '1.0';
  
  const SINGLE_WEIXIN_CACHE_VERSION = '1.0';

  //缓存 namespace
  const USER_CACHE_SPACE_NAME = 'USER_CACHE_SPACE_NAME';
  
  const BKADMIN_USER_CACHE_SPACE_NAME = 'BKADMIN_USER_CACHE_SPACE_NAME';
  
  //缓存 scope key前缀
  const USER_NICKNAME_INDEX_SCOPE_REFIX = 'SCOPE_USER_NICKNAME_INDEX_';
  
  const BKADMIN_USER_SCOPE_REFIX = 'SCOPE_BKADMIND_USER_';
  
  const BKADMIN_USER_VESTS_SCOPE_REFIX = 'SCOPE_BKADMIN_USER_VESTS_';
  
  private function getCacheKey($id) {
    return self::CACHE_PREFIX . strtoupper($id) . '_' . self::CACHE_VERSION;
  }

  private function getUserMemcacheNameSpace() {
    return $this->getMemcacheNameSpace(self::USER_CACHE_SPACE_NAME, self::ONE_DAY_CACHE_TIME);
  }
  
  private function getAdminUserMemcacheNameSpace() {
    return $this->getBkAdminMemcacheNameSpace(self::BKADMIN_USER_CACHE_SPACE_NAME, self::ONE_DAY_CACHE_TIME);
  }
  
  private function getAdminUserScopeKey($uid) {
    return self::BKADMIN_USER_SCOPE_REFIX . $uid;
  }
  
  private function getAdminUserVestscopeKey($uid) {
    return self::BKADMIN_USER_VESTS_SCOPE_REFIX . $uid;
  }
  
  private function getUserNicknameIndexcopeKey($nickname) {
    $splitKey = $this->getFirstStringFromString($nickname);
    return self::USER_NICKNAME_INDEX_SCOPE_REFIX . $splitKey;
  }
  
  private function getUserTableName ($uid) {
    return $this->getHashTableName($uid, 'user', self::LARGER_HASH_TABLE_NUM);
  }
  
  private function getMobileIndexTableName ($mobile) {
    return $this->getHashTableName($mobile, 'user_mobile_index', self::LARGE_HASH_TABLE_NUM);
  }
  
  private function getUserNicknameIndexTableName ($nickname) {
    $splitKey = $this->getFirstStringFromString($nickname);
    return $this->getStringHashTableName($splitKey, 'user_nickname_index', self::LARGE_HASH_TABLE_NUM);
  }
  
  private function getUserWeixinInfoTableName ($uid) {
    return $this->getHashTableName($uid, 'user_weixin_info', self::LARGE_HASH_TABLE_NUM);
  }
  
  private function getUserWeixinOpenidIndexTableName ($openid) {
    return $this->getHashTableName($openid, 'user_weixin_openid_index', self::LARGE_HASH_TABLE_NUM);
  }
  
  private function getUserWeixinUnionidIndexTableName ($unionid) {
    return $this->getHashTableName($unionid, 'user_weixin_unionid_index', self::LARGE_HASH_TABLE_NUM);
  }
  
  private function getUserWeixinOpenidsTableName ($uid) {
    return $this->getStringHashTableName($uid, 'user_weixin_openids', self::LARGE_HASH_TABLE_NUM);
  }
  
  private function getUserSessionTableName ($uid) {
    return $this->getHashTableName($uid, 'user_session', self::LARGER_HASH_TABLE_NUM);
  }
  
  /**
   * @return user_nickname_index
   */
  public function findUserNicknameIndex($nickname) {
    if (!$nickname) {
      throw new Exception('nickname is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $nickname);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterAccountConnection()->prepare("SELECT * FROM {$this->getUserNicknameIndexTableName($nickname)} WHERE nickname = :nickname LIMIT 1");
      $stmt->bindValue(':nickname', $nickname, PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert user_nickname_index
   */
  public function insertUserNicknameIndex($uid, $nickname) {
    if (!$uid || !$nickname) {
      throw new Exception('uid or nickname is null...');
    }
  
    $userNicknameIndexFields = array();
    $userNicknameIndexFields['nickname'] = $nickname;
    $userNicknameIndexFields['uid'] = $uid;
    $userNicknameIndexFields['created_time'] = 'NONE';
    $userNicknameIndexFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterAccountConnection(), $this->getUserNicknameIndexTableName($nickname), $userNicknameIndexFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findUserNicknameIndex' . '_' . $nickname);
      $this->getMemcache()->delete($cacheKey);
      $scopeKey = $this->getUserNicknameIndexcopeKey($nickname);
      $this->getUserMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
  
    return FALSE;
  }
  
  /**
   * @desc delete user_nickname_index
   */
  public function deleteUserNicknameIndex($uid, $nickname) {
    if (!$uid || !$nickname) {
      throw new Exception('uid or nickname is null...');
    }
  
    $stmt = $this->getUcenterAccountConnection()->prepare("DELETE FROM {$this->getUserNicknameIndexTableName($nickname)} WHERE uid = :uid LIMIT 1");
    $stmt->bindValue(':uid', $uid, PDO::PARAM_STR);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if ($rowCount) {
      $cacheKey = $this->getCacheKey('findUserNicknameIndex' . '_' . $nickname);
      $this->getMemcache()->delete($cacheKey);
      $scopeKey = $this->getUserNicknameIndexcopeKey($nickname);
      $this->getUserMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @return user_session
   */
  public function findUserSessionByUid($uid) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $uid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterSessionConnection()->prepare("SELECT * FROM {$this->getUserSessionTableName($uid)} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert user_session
   */
  public function insertUserSession(Array $fields) {
    if (!$fields['uid'] || !$fields['sid']) {
      throw new Exception('uid or sid is null...');
    }
  
    $insertFields = array();
    $insertFields['uid'] = $fields['uid'];
    $insertFields['sid'] = $fields['sid'];
    if ($fields['expires_in']) $insertFields['expires_in'] = $fields['expires_in'];
    if ($fields['created_time']) $insertFields['created_time'] = $fields['created_time'];
    if ($this->insert($this->getUcenterSessionConnection(), $this->getUserSessionTableName($fields['uid']), $insertFields)) {
      $cacheKey = $this->getCacheKey('findUserSessionByUid_' . $fields['uid']);
      $this->getMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc update user_session
   */
  public function updateUserSession($uid, Array $fields) {
    if (!$uid) {
      throw new Exception('uid or expires_in is null...');
    }
  
    $updateFields = array();
    if ($fields['sid']) $updateFields['sid'] = $fields['sid'];
    if (isset($fields['expires_in'])) $updateFields['expires_in'] = $fields['expires_in'];
    if ($fields['updated_time']) $updateFields['updated_time'] = $fields['updated_time'];
    if ($updateFields) {
      $stmt = $this->getUcenterSessionConnection()->prepare("UPDATE {$this->getUserSessionTableName($uid)} {$this->getUpdateSect($updateFields)} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        if ($fields['sid']) {
          $cacheKey = $this->getCacheKey('findUserSessionByUid_' . $uid);
          $this->getMemcache()->delete($cacheKey);
        }
        return TRUE;
      }
    }
    return FALSE;
  }
  
  /**
   * @return user
   */
  public function findUserByUid($uid) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $uid . self::SINGLE_USER_CACHE_VERSION);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterAccountConnection()->prepare("SELECT * FROM {$this->getUserTableName($uid)} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $row ? $row : array();
  }

  /**
   * @return user_mobile_index
   */
  public function findUserMobileIndex($mobile) {
    if (!$mobile) {
      throw new Exception('mobile is null...');
    }
    
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $mobile);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterAccountConnection()->prepare("SELECT * FROM {$this->getMobileIndexTableName($mobile)} WHERE mobile = :mobile LIMIT 1");
      $stmt->bindValue(':mobile', $mobile, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert user_mobile_index
   */
  public function insertUserMobileIndex($uid, $mobile) {
    if (!$uid || !$mobile) {
      throw new Exception('uid or mobile is null...');
    }
  
    $userMobileIndexFields = array();
    $userMobileIndexFields['mobile'] = $mobile;
    $userMobileIndexFields['uid'] = $uid;
    $res = $this->insert($this->getUcenterAccountConnection(), $this->getMobileIndexTableName($mobile), $userMobileIndexFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findUserMobileIndex' . '_' . $mobile);
      $this->getMemcache()->delete($cacheKey);
      return TRUE;
    }
  
    return FALSE;
  }
  
  /**
   * @desc delete user_mobile_index
   */
  public function deleteUserMobileIndex($uid, $mobile) {
    if (!$uid || !$mobile) {
      throw new Exception('uid or mobile is null...');
    }
  
    $stmt = $this->getUcenterAccountConnection()->prepare("DELETE FROM {$this->getMobileIndexTableName($mobile)} WHERE mobile = :mobile AND uid = :uid LIMIT 1");
    $stmt->bindValue(':mobile', $mobile, PDO::PARAM_INT);
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if ($rowCount) {
      $cacheKey = $this->getCacheKey('findUserMobileIndex' . '_' . $mobile);
      $this->getMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc insert user & user_index
   */
  public function insertUserInfo(Array $fields) {
    $regUser = array();
    if (!$fields['nickname'] || !$fields['password'] || !$fields['reg_ip']) {
      throw new Exception('nickname, password or reg_ip is null...');
    }
  
    //生成步长uid
    $userIndexFields = array();
    $userIndexFields['uid'] = 0;
    $userIndexFields['created_time'] = 'NONE';
    $userIndexFields['updated_time'] = 'NONE';
    $uid = $this->insert($this->getUcenterAccountConnection(), 'user_index', $userIndexFields, TRUE);
    
    //写入用户资料
    if ($uid && is_numeric($uid)) {
      $userFields = array();
      $userFields['uid'] = $uid;
      $userFields['nickname'] = $fields['nickname'];
      $userFields['password'] = md5($fields['password']);
      $userFields['private_key'] = md5($uid . $userFields['password'] . $fields['reg_ip']);
      if ($fields['mobile']) $userFields['mobile'] = $fields['mobile'];
      if ($fields['avatar']) $userFields['avatar'] = $fields['avatar'];
      if ($fields['gender']) $userFields['gender'] = $fields['gender'];
      if ($fields['region']) $userFields['region'] = $fields['region'];
      if ($fields['country_id']) $userFields['country_id'] = $fields['country_id'];
      if ($fields['province_id']) $userFields['province_id'] = $fields['province_id'];
      if ($fields['city_id']) $userFields['city_id'] = $fields['city_id'];
      if ($fields['district_id']) $userFields['district_id'] = $fields['district_id'];
      if ($fields['sign']) $userFields['sign'] = $fields['sign'];
      if ($fields['birthday']) $userFields['birthday'] = $fields['birthday'];
      $userFields['reg_ip'] = $fields['reg_ip'];
      $userFields['reg_from'] = $fields['reg_from'];
      $res = $this->insert($this->getUcenterAccountConnection(), $this->getUserTableName($uid), $userFields);
      if ($res) {
        if ($fields['mobile']) {
          try {
            $this->insertUserMobileIndex($uid, $fields['mobile']);
          } catch (PDOException $e) {
            $this->deleteUser($uid);
          }
        }
        $regUser = $userFields;
      }
    }
    return $regUser;
  }
  
  /**
   * @desc update user
   */
  public function updateUser($uid, Array $fields) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
  
    $updatedUser = array();
    $userFields = array();
    
    //update nickname
    if ($fields['nickname']) $userFields['nickname'] = $fields['nickname'];
    
    //update password & private_key
    if ($fields['password'] && $fields['reg_ip']) {
      $userFields['password'] = md5($fields['password']);
      $userFields['private_key'] = md5($uid . $userFields['password'] . $fields['reg_ip']);
    }
    
    if ($fields['mobile']) $userFields['mobile'] = $fields['mobile'];
    if ($fields['avatar']) $userFields['avatar'] = $fields['avatar'];
    if ($fields['gender']) $userFields['gender'] = $fields['gender'];
    if ($fields['region']) $userFields['region'] = $fields['region'];
    if ($fields['country_id']) $userFields['country_id'] = $fields['country_id'];
    if (isset($fields['province_id'])) $userFields['province_id'] = $fields['province_id'];
    if (isset($fields['city_id'])) $userFields['city_id'] = $fields['city_id'];
    if (isset($fields['district_id'])) $userFields['district_id'] = $fields['district_id'];
    if ($fields['sign']) $userFields['sign'] = $fields['sign'];
    if ($fields['birthday']) $userFields['birthday'] = $fields['birthday'];
    if (isset($fields['reg_from'])) $userFields['reg_from'] = $fields['reg_from'];
    if (isset($fields['status'])) $userFields['status'] = $fields['status'];
    if ($userFields) {
      $stmt = $this->getUcenterAccountConnection()->prepare("UPDATE {$this->getUserTableName($uid)} {$this->getUpdateSect($userFields)} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $this->bindValues($stmt, $userFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $cacheKey = $this->getCacheKey('findUserByUid' . '_' . $uid . self::SINGLE_USER_CACHE_VERSION);
        $this->getMemcache()->delete($cacheKey);
        $updatedUser = $userFields;
      }
    }
    return $updatedUser;
  }
  
  /**
   * @desc delete user
   */
  public function deleteUser ($uid) {
    if ($uid) {
      $stmt = $this->getUcenterAccountConnection()->prepare("DELETE FROM {$this->getUserTableName($uid)} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @return insert user_qq_index & user_qq_info
   */
  public function insertUserQQIndexAndInfo(Array $fields) {
    $userQQ = array();
    $res = $this->insertUserQQIndex($fields);
    if ($res) {
      $userQQ = $this->insertUserQQInfo($fields);
    }
    return $userQQ;
  }

  /**
   * @return user_qq_index
   */
  public function findUserQQIndex ($openId) {
    if (!$openId) {
      throw new Exception('openId is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $openId);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterAccountConnection()->prepare("SELECT * FROM {$this->getStringHashTableName($openId, 'user_qq_index')} WHERE openid = :openid LIMIT 1");
      $stmt->bindValue(':openid', $openId, PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) $this->getMemcache()->set($cacheKey, $row, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $row ? $row : array();
  }

  /**
   * @return insert user_qq_index
   */
  public function insertUserQQIndex (Array $fields) {
    if (!$fields['uid'] || !$fields['openid']) {
      throw new Exception('uid or openid is null');
    }

    $userQQIndex = array();
    $indexFields = array();
    $indexFields['openid'] = $fields['openid'];
    $indexFields['uid'] = $fields['uid'];
    $indexFields['created_time'] = 'NONE';
    $indexFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterAccountConnection(), $this->getStringHashTableName($fields['openid'], 'user_qq_index'), $indexFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findUserQQIndex' . '_' . $fields['openid']);
      $this->getMemcache()->delete($cacheKey);
      $userQQIndex = $indexFields;
    }

    return $userQQIndex;
  }
  
  /**
   * @return user_qq_info
   */
  public function findUserQQInfo ($uid) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $uid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterAccountConnection()->prepare("SELECT * FROM {$this->getHashTableName($uid, 'user_qq_info')} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) $this->getMemcache()->set($cacheKey, $row, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert user_qq_info
   */
  public function insertUserQQInfo(Array $fields) {
    if (!$fields['uid'] || !$fields['openid'] || !$fields['nickname'] || !$fields['access_token'] || !$fields['expires_in']) {
      throw new Exception('uid, openid, nickname, access_token or field is null...');
    }
  
    $userQQInfo = array();
    $insertFields = array();
    $insertFields['uid'] = $fields['uid'];
    $insertFields['openid'] = $fields['openid'];
    $insertFields['nickname'] = $fields['nickname'];
    if ($fields['avatar']) $insertFields['avatar'] = $fields['avatar'];
    if (isset($fields['gender'])) $insertFields['gender'] = $fields['gender'];
    $insertFields['access_token'] = $fields['access_token'];
    $insertFields['expires_in'] = $fields['expires_in'] ? $fields['expires_in'] : 7776000;
    if ($fields['refresh_token']) $insertFields['refresh_token'] = $fields['refresh_token'];
    if ($fields['reg_from']) $insertFields['reg_from'] = $fields['reg_from'];
    $insertFields['status'] = 1;
    $res = $this->insert($this->getUcenterAccountConnection(), $this->getHashTableName($fields['uid'], 'user_qq_info'), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findUserQQInfo' . '_' . $fields['uid']);
      $this->getMemcache()->delete($cacheKey);
      $userQQInfo = $insertFields;
    }
  
    return $userQQInfo;
  }
  
  /**
   * @desc update user_qq_info
   */
  public function updateUserQQInfo($uid, Array $fields) {
    if (!$uid) {
      throw new Exception('uid or field is null...');
    }
  
    $userQQInfo = array();
    $updateFields = array();
    if ($fields['nickname']) $updateFields['nickname'] = $fields['nickname'];
    if ($fields['avatar']) $updateFields['avatar'] = $fields['avatar'];
    if (isset($fields['gender'])) $insertFields['gender'] = $fields['gender'];
    if ($fields['access_token']) $updateFields['access_token'] = $fields['access_token'];
    if ($fields['refresh_token']) $updateFields['refresh_token'] = $fields['refresh_token'];
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($updateFields) {
      if ($fields['updated_time']) $updateFields['updated_time'] = $fields['updated_time'];
      $stmt = $this->getUcenterAccountConnection()->prepare("UPDATE {$this->getHashTableName($uid, 'user_qq_info')} {$this->getUpdateSect($updateFields)} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $cacheKey = $this->getCacheKey('findUserQQInfo' . '_' .$uid);
        $this->getMemcache()->delete($cacheKey);
        $userQQInfo = $fields;
      }
    }
    return $userQQInfo;
  }
  
  /**
   * @desc insert user_sina_index & user_sina_info
   */
  public function insertUserSinaIndexAndInfo (Array $fields) {
    $userSina = array();
    $res = $this->insertUserSinaIndex($fields);
    if ($res) {
      $userSina = $this->insertUserSinaInfo($fields);
    }
    return $userSina;
  }
  
  /**
   * @return user_sina_index
   */
  public function findUserSinaIndex ($sinaUid) {
    if (!$sinaUid) {
      throw new Exception('sinaUid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $sinaUid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterAccountConnection()->prepare("SELECT * FROM {$this->getStringHashTableName($sinaUid, 'user_sina_index')} WHERE sina_uid = :sina_uid LIMIT 1");
      $stmt->bindValue(':sina_uid', $sinaUid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) $this->getMemcache()->set($cacheKey, $row, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert user_sina_index
   */
  public function insertUserSinaIndex(Array $fields) {
    if (!$fields['uid'] || !$fields['sina_uid']) {
      throw new Exception('uid or sina_uid field is null...');
    }
    
    $userSinaIndex = array();
    $indexFields = array();
    $indexFields['sina_uid'] = $fields['sina_uid'];
    $indexFields['uid'] = $fields['uid'];
    $indexFields['created_time'] = 'NONE';
    $indexFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterAccountConnection(), $this->getStringHashTableName($fields['sina_uid'], 'user_sina_index'), $indexFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findUserSinaIndex' . '_' . $fields['sina_uid']);
      $this->getMemcache()->delete($cacheKey);
      $userSinaIndex = $indexFields;
    }
  
    return $userSinaIndex; 
  }
  
  /**
   * @return user_sina_info
   */
  public function findUserSinaInfo ($uid) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $uid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterAccountConnection()->prepare("SELECT * FROM {$this->getHashTableName($uid, 'user_sina_info')} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) $this->getMemcache()->set($cacheKey, $row, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert user_sina_info
   */
  public function insertUserSinaInfo(Array $fields) {
    if (!$fields['uid'] || !$fields['sina_uid'] || !$fields['nickname'] || !$fields['access_token'] || !$fields['expires_in']) {
      throw new Exception('uid, sina_uid, unionid, nickname, access_token or field is null...');
    }
  
    $userSinaInfo = array();
    $insertFields = array();
    $insertFields['uid'] = $fields['uid'];
    $insertFields['sina_uid'] = $fields['sina_uid'];
    $insertFields['nickname'] = $fields['nickname'];
    if ($fields['avatar']) $insertFields['avatar'] = $fields['avatar'];
    $insertFields['access_token'] = $fields['access_token'];
    $insertFields['expires_in'] = $fields['expires_in'] ? $fields['expires_in'] : 3600;
    if ($fields['refresh_token']) $insertFields['refresh_token'] = $fields['refresh_token'];
    if ($fields['reg_from']) $insertFields['reg_from'] = $fields['reg_from'];
    $insertFields['status'] = 1;
    $res = $this->insert($this->getUcenterAccountConnection(), $this->getHashTableName($fields['uid'], 'user_sina_info'), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findUserSinaInfo' . '_' . $fields['uid']);
      $this->getMemcache()->delete($cacheKey);
      $userSinaInfo = $insertFields;
    }
    
    return $userSinaInfo;
  }
  
  /**
   * @desc update user_sina_info
   */
  public function updateUserSinaInfo($uid, Array $fields) {
    if (!$uid) {
      throw new Exception('uid or field is null...');
    }
  
    $userSinaInfo = array();
    $updateFields = array();
    if ($fields['nickname']) $updateFields['nickname'] = $fields['nickname'];
    if ($fields['avatar']) $updateFields['avatar'] = $fields['avatar'];
    if ($fields['access_token']) $updateFields['access_token'] = $fields['access_token'];
    if ($fields['refresh_token']) $updateFields['refresh_token'] = $fields['refresh_token'];
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($updateFields) {
      if ($fields['updated_time']) $updateFields['updated_time'] = $fields['updated_time'];
      $stmt = $this->getUcenterAccountConnection()->prepare("UPDATE {$this->getHashTableName($uid, 'user_sina_info')} {$this->getUpdateSect($updateFields)} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $cacheKey = $this->getCacheKey('findUserSinaInfo' . '_' .$uid);
        $this->getMemcache()->delete($cacheKey);
        $userSinaInfo = $fields;
      }
    }
    return $userSinaInfo;
  }
  
  /**
   * @desc insert indexs and info
   * @see insert order
   * user_weixin_unionid_index 
   * user_weixin_openids
   * user_weixin_info
   * user_weixin_openid_index
   */
  public function insertUserWeixinIndexAndInfo (Array $fields) {
    $userWeixin = array();
    $res = $this->insertUserWeixinUnionidIndex($fields);
    if ($res && $this->insertUserWeixinOpenid($fields)) {
      $this->insertUserWeixinOpenidIndex($fields);
      $userWeixin = $this->insertUserWeixinInfo($fields);
    }
    return $userWeixin;
  }
  
  /**
   * @return user_weixin_unionid_index
   * @param string $unionid
   */
  public function findUserWeixinUnionidIndex ($unionid) {
    if (!$unionid) {
      throw new Exception('unionid is null...');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $unionid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterWeixinConnection()->prepare("SELECT * FROM {$this->getUserWeixinUnionidIndexTableName($unionid)} WHERE unionid = :unionid LIMIT 1");
      $stmt->bindValue(':unionid', $unionid, PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) $this->getMemcache()->set($cacheKey, $row, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert user_weixin_unionid_index
   */
  public function insertUserWeixinUnionidIndex(Array $fields) {
    if (!$fields['unionid'] || !$fields['uid']) {
      throw new Exception('unionid or uid is null...');
    }

    $userWeixinIndex = array();
    $indexFields = array();
    $indexFields['unionid'] = $fields['unionid'];
    $indexFields['uid'] = $fields['uid'];
    $indexFields['created_time'] = 'NONE';
    $indexFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterWeixinConnection(), $this->getUserWeixinUnionidIndexTableName($fields['unionid']), $indexFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findUserWeixinUnionidIndex' . '_' . $fields['unionid']);
      $this->getMemcache()->delete($cacheKey);
      $userWeixinIndex = $indexFields;
    }
  
    return $userWeixinIndex;
  }
  
  /**
   * @return user_weixin_unionid_index
   * @param string $openid
   */
  public function findUserWeixinOpenidIndex ($openid) {
    if (!$openid) {
      throw new Exception('openid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $openid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterWeixinConnection()->prepare("SELECT * FROM {$this->getUserWeixinOpenidIndexTableName($openid)} WHERE openid = :openid LIMIT 1");
      $stmt->bindValue(':openid', $openid, PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) $this->getMemcache()->set($cacheKey, $row, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert user_weixin_openid_index
   */
  public function insertUserWeixinOpenidIndex(Array $fields) {
    if (!$fields['openid'] || !$fields['uid']) {
      throw new Exception('openid or uid is null...');
    }
  
    $userWeixinIndex = array();
    $indexFields = array();
    $indexFields['openid'] = $fields['openid'];
    $indexFields['uid'] = $fields['uid'];
    $indexFields['created_time'] = 'NONE';
    $indexFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterWeixinConnection(), $this->getUserWeixinOpenidIndexTableName($fields['openid']), $indexFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findUserWeixinOpenidIndex' . '_' . $fields['openid']);
      $this->getMemcache()->delete($cacheKey);
      $userWeixinIndex = $indexFields;
    }
  
    return $userWeixinIndex;
  }
  
  /**
   * @return user_weixin_openids
   * @param int $uid
   * @param string $appid
   */
  public function findUserWeixinOpenidWithUidAndAppid ($uid, $appid) {
    if (!$uid || !$appid) {
      throw new Exception('uid or appid is null');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_APPID_' . $appid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterWeixinConnection()->prepare("SELECT * FROM {$this->getUserWeixinOpenidsTableName($uid)} WHERE uid = :uid AND appid = :appid LIMIT 1" );
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->bindValue(':appid', $appid, PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @return user_weixin_openids
   */
  public function insertUserWeixinOpenid ($fields) {
    if (!$fields['uid'] || !$fields['appid'] || !$fields['openid']) {
      throw new Exception('uid, appid or openid is null');
    }
  
    $resFields = array();
    $insertFields = array();
    $insertFields['uid'] = $fields['uid'];
    $insertFields['appid'] = $fields['appid'];
    $insertFields['openid'] = $fields['openid'];
    $insertFields['created_time'] = 'NONE';
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterWeixinConnection(), $this->getUserWeixinOpenidsTableName($fields['uid']), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findUserWeixinOpenidWithUidAndAppid' . '_UID_' . $fields['uid'] . '_APPID_' . $fields['appid']);
      $this->getMemcache()->delete($cacheKey);
      $resFields = $insertFields;
    }
    return $resFields;
  }
  
  /**
   * @return user_weixin_info
   */
  public function findUserWeixinInfo ($uid) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $uid . self::SINGLE_WEIXIN_CACHE_VERSION);
    $row = $this->getMemcache()->get($cacheKey);
    if (!$row) {
      $stmt = $this->getUcenterWeixinConnection()->prepare("SELECT * FROM {$this->getUserWeixinInfoTableName($uid)} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) $this->getMemcache()->set($cacheKey, $row, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert user_weixin_info
   * @param int $fields['reg_from'] 1-app，2-公众号，3-web
   */
  public function insertUserWeixinInfo(Array $fields) {
    if (!$fields['uid'] || !$fields['openid'] || !$fields['nickname']) {
      throw new Exception('uid, openid, nickname or field is null...');
    }
  
    $userWeixinInfo = array();
    $insertFields = array();
    $insertFields['uid'] = $fields['uid'];
    $insertFields['openid'] = $fields['openid'];
    $insertFields['nickname'] = $fields['nickname'];
    if ($fields['avatar']) $insertFields['avatar'] = $fields['avatar'];
    if ($fields['access_token']) $insertFields['access_token'] = $fields['access_token'];
    $insertFields['expires_in'] = $fields['expires_in'] ? $fields['expires_in'] : 2592000;
    if ($fields['refresh_token']) $insertFields['refresh_token'] = $fields['refresh_token'];
    if ($fields['wx_from']) $insertFields['wx_from'] = $fields['wx_from'];
    $insertFields['status'] = 1;
    $res = $this->insert($this->getUcenterWeixinConnection(), $this->getUserWeixinInfoTableName($fields['uid']), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findUserWeixinInfo' . '_' . $fields['uid'] . self::SINGLE_WEIXIN_CACHE_VERSION);
      $this->getMemcache()->delete($cacheKey);
      $userWeixinInfo = $insertFields;
    }
    
    return $userWeixinInfo;
  }
  
  /**
   * @desc update user_weixin_info
   */
  public function updateUserWeixinInfo($uid, Array $fields) {
    if (!$uid) {
      throw new Exception('uid or field is null...');
    }
  
    $userWeixinInfo = array();
    $updateFields = array();
    if ($fields['nickname']) $updateFields['nickname'] = $fields['nickname'];
    if ($fields['avatar']) $updateFields['avatar'] = $fields['avatar'];
    if ($fields['access_token']) $updateFields['access_token'] = $fields['access_token'];
    if ($fields['refresh_token']) $updateFields['refresh_token'] = $fields['refresh_token'];
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($updateFields) {
      if ($fields['updated_time']) $updateFields['updated_time'] = $fields['updated_time'];
      $stmt = $this->getUcenterWeixinConnection()->prepare("UPDATE {$this->getUserWeixinInfoTableName($uid)} {$this->getUpdateSect($updateFields)} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $cacheKey = $this->getCacheKey('findUserWeixinInfo' . '_' .$uid . self::SINGLE_WEIXIN_CACHE_VERSION);
        $this->getMemcache()->delete($cacheKey);
        $userWeixinInfo = $fields;
      }
    }
    return $userWeixinInfo;
  }
  
  /**
   * @desc find bk_user
   */
  public function findBkAdminUserByUid ($uid) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
    
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_v1_' . $uid);
    $row = $this->getBkAdminMemcache()->get($cacheKey);
    if (!$row) {
      $stmt = $this->getBackendConnection()->prepare("SELECT * FROM bk_user WHERE uid = :uid");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) $this->getBkAdminMemcache()->set($cacheKey, $row, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc find bk_user
   */
  public function findBkAdminUsers ($offset = 0, $limit = 20) {
    if (!$limit) {
      throw new Exception('limit is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getAdminUserScopeKey(0);
    $rows = $this->getAdminUserMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getBackendConnection()->prepare("SELECT * FROM bk_user LIMIT :offset, :limit");
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      $cacheTime = self::CACHE_TIME;
      if (empty($rows)) {
        $rows = array();
        $cacheTime = self::NONE_CACHE_TIME;
      }
      
      $this->getAdminUserMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, $cacheTime);
    }
    return $rows;
  }
  
  /**
   * @desc insert bk_user
   */
  public function insertBkAdminUser ($fields) {
    if (!$fields['uid'] || !$fields['admin_name']) {
      throw new Exception('uid or admin_name is null...');
    }
  
    $insertFields = array();
    $insertFields['uid'] = $fields['uid'];
    $insertFields['admin_name'] = $fields['admin_name'];
    $res = $this->insert($this->getBackendConnection(), 'bk_user', $fields);
    if ($res) {
      $scopeKey = $this->getAdminUserScopeKey(0);
      $this->getAdminUserMemcacheNameSpace()->removeBatchKeys($scopeKey);
      
      $cacheKey = $this->getCacheKey('findBkAdminUserByUid' . '_v1_' . $fields['uid']);
      $this->getBkAdminMemcache()->delete($cacheKey);
    }
    return $res;
  }
  
  /**
   * @desc update bk_user
   */
  public function updateBkAdminUser ($uid, $fields) {
    if (!$uid || !$fields) {
      throw new Exception('uid is null...');
    }
  
    if ($fields['last_login_time']) $updateFields['last_login_time'] = $fields['last_login_time'];
    if ($fields['updated_time']) $updateFields['updated_time'] = $fields['updated_time'];
    if ($updateFields) {
      $stmt = $this->getBackendConnection()->prepare("UPDATE bk_user {$this->getUpdateSect($updateFields)} WHERE uid = :uid");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $scopeKey = $this->getAdminUserScopeKey(0);
        $this->getAdminUserMemcacheNameSpace()->removeBatchKeys($scopeKey);
        
        $cacheKey = $this->getCacheKey('findBkAdminUserByUid' . '_v1_' . $uid);
        $this->getBkAdminMemcache()->delete($cacheKey);
        return TRUE;
      }
    }
    return FALSE;
  }
  
  /**
   * @desc delete bk_user
   */
  public function deleteBkAdminUser ($uid) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
  
    $stmt = $this->getBackendConnection()->prepare("DELETE FROM bk_user WHERE uid = :uid");
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if ($rowCount) {
      $scopeKey = $this->getAdminUserScopeKey(0);
      $this->getAdminUserMemcacheNameSpace()->removeBatchKeys($scopeKey);
      
      $cacheKey = $this->getCacheKey('findBkAdminUserByUid' . '_v1_' . $uid);
      $this->getBkAdminMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc find bk_user_permissions
   */
  public function findBkAdminUserPermissionByUid ($uid) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
    
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $uid);
    $rows = $this->getBkAdminMemcache()->get($cacheKey);
    if (!$rows) {
      $stmt = $this->getBackendConnection()->prepare("SELECT * FROM bk_user_permissions WHERE uid = :uid");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if ($rows) $this->getBkAdminMemcache()->set($cacheKey, $rows, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $rows ? $rows : array();
  }

  /**
   * @desc insert bk_user_permissions
   */
  public function insertBkAdminUserPermission ($fields) {
    if (!$fields['uid'] || !$fields['permission_id']) {
      throw new Exception('uid or permission_id is null...');
    }
    
    $fields['created_time'] = 'NONE';
    $fields['updated_time'] = 'NONE';
    $res = $this->insert($this->getBackendConnection(), 'bk_user_permissions', $fields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findBkAdminUserPermissionByUid' . '_' . $fields['uid']);
      $this->getBkAdminMemcache()->delete($cacheKey);
    }
    return $res;
  }

  /**
   * @desc delete user_permission
   * @param $permission_ids=null, delelte all
   */
  public function deleteBkAdminUserPermission ($uid, $permission_ids) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
    
    $whereSQL = '';
    if ($permission_ids) {
      $whereSQL = " AND permission_id IN ({$permission_ids})";
    }

    $stmt = $this->getBackendConnection()->prepare("DELETE FROM bk_user_permissions WHERE uid = :uid {$whereSQL}");
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if ($rowCount) {
      $cacheKey = $this->getCacheKey('findBkAdminUserPermissionByUid' . '_' . $uid);
      $this->getBkAdminMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc find bk_user_vests
   */
  public function findBkAdminUserVests ($uid, $offset = 0, $limit = 10) {
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getAdminUserVestscopeKey($uid);
    $rows = $this->getAdminUserMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if (!$rows) {
      $stmt = $this->getBackendConnection()->prepare("SELECT * FROM bk_user_vests WHERE uid = :uid ORDER BY id DESC LIMIT {$offset}, {$limit}");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
      $cacheTime = self::CACHE_TIME;
      if (empty($rows)) {
        $rows = array();
        $cacheTime = self::NONE_CACHE_TIME;
      }
      $this->getAdminUserMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, $cacheTime);
    }
    return $rows ? $rows : array();
  }
  
  /**
   * @desc find bk_user_vests
   */
  public function findBkAdminUserVestByUidAndOuid ($uid, $ouid) {
    if (!$uid || !$ouid) {
      throw new Exception('uid or ouid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $uid . '_OUID_' . $ouid);
    $row = $this->getBkAdminMemcache()->get($cacheKey);
  
    if (!$row) {
      $stmt = $this->getBackendConnection()->prepare("SELECT * FROM bk_user_vests WHERE uid = :uid AND online_uid = :online_uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->bindValue(':online_uid', $ouid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
      if (empty($row)) $row = array();
      $this->getBkAdminMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
  
    return $row ? $row : array();
  }
  
  /**
   * @desc find bk_user_vests
   */
  public function findBkAdminUserVestByOuid ($ouid) {
    if (!$ouid) {
      throw new Exception('ouid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_OUID_' . $ouid);
    $row = $this->getBkAdminMemcache()->get($cacheKey);
  
    if (!$row) {
      $stmt = $this->getBackendConnection()->prepare("SELECT * FROM bk_user_vests WHERE online_uid = :online_uid LIMIT 1");
      $stmt->bindValue(':online_uid', $ouid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
      if (empty($row)) $row = array();
      $this->getBkAdminMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
  
    return $row ? $row : array();
  }
  
  /**
   * @desc insert bk_user_vests
   */
  public function insertBkAdminUserVest ($fields) {
    if (!$fields['uid'] || !$fields['online_uid']) {
      throw new Exception('uid or online_uid is null...');
    }
  
    $insertFields = array();
    $insertFields['uid'] = $fields['uid'];
    $insertFields['online_uid'] = $fields['online_uid'];
    $insertFields['uid'] = $fields['uid'];
    if ($fields['is_robot_vest']) $insertFields['is_robot_vest'] = 1;
    $res = $this->insert($this->getBackendConnection(), 'bk_user_vests', $insertFields);
    if ($res) {
      $scopeKey = $this->getAdminUserVestscopeKey($insertFields['uid']);
      $this->getAdminUserMemcacheNameSpace()->removeBatchKeys($scopeKey);
       
      $cacheKey = $this->getCacheKey('findBkAdminUserVestByUidAndOuid' . '_' . $insertFields['uid'] . '_OUID_' . $insertFields['online_uid']);
      $this->getBkAdminMemcache()->delete($cacheKey);
      
      $cacheKey = $this->getCacheKey('findBkAdminUserVestByOuid' . '_OUID_' . $insertFields['online_uid']);
      $this->getBkAdminMemcache()->delete($cacheKey);
    }
  
    return $res;
  }
  
  /**
   * @desc update bk_user_vests
   */
  public function updateBkAdminUserVestByUidAndOuid ($uid, $ouid, Array $fields) {
    if (!$uid || !$ouid) {
      throw new Exception('uid or ouid is null...');
    }
  
    $updateFields = array();
    if ($fields['updated_time']) $updateFields['updated_time'] = $fields['updated_time'];
    if (isset($fields['is_robot_vest'])) $updateFields['is_robot_vest'] = $fields['is_robot_vest'];
    if ($updateFields) {
      $stmt = $this->getBackendConnection()->prepare("UPDATE bk_user_vests {$this->getUpdateSect($updateFields)} WHERE uid = :uid AND online_uid = :online_uid");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->bindValue(':online_uid', $ouid, PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $scopeKey = $this->getAdminUserVestscopeKey($uid);
        $this->getAdminUserMemcacheNameSpace()->removeBatchKeys($scopeKey);
        
        $cacheKey = $this->getCacheKey('findBkAdminUserVestByUidAndOuid' . '_' . $uid . '_OUID_' . $ouid);
        $this->getBkAdminMemcache()->delete($cacheKey);
        return TRUE;
      }
    }
    return FALSE;
  }
}
