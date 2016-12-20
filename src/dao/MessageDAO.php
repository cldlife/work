<?php
/**
 * @desc MessageDAO
 */
class MessageDAO extends BaseDao {

  const CACHE_TIME = 300;

  //缓存key前缀
  const CACHE_PREFIX = 'MESSAGE_CACHE_';
  
  //缓存版本号
  const CACHE_VERSION = '1.0';

  //缓存 namespace
  const MESSAGE_SPACE_NAME = 'MESSAGE_SPACE_NAME';
  
  private function getCacheKey($id) {
    return self::CACHE_PREFIX . strtoupper($id) . '_' . self::CACHE_VERSION;
  }

  private function getMessageMemcacheNameSpace() {
    return $this->getMemcacheNameSpace(self::MESSAGE_SPACE_NAME, self::CACHE_TIME);
  }
  
  private function getMessageRcUserTokenTableName ($uid) {
    return $this->getHashTableName($uid, 'message_rc_user_token', self::LARGE_HASH_TABLE_NUM);
  }

  /**
   * @return message_smscode
   */
  public function findMobileSMScode($mobile, $type) {
    if (!$mobile || !$type) {
      throw new Exception('mobile or type is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $mobile . '_' . $type);
    $row = $this->getMemcache()->get($cacheKey);
    if (!$row) {
      $stmt = $this->getUcenterMessageConnection()->prepare("SELECT * FROM message_smscode WHERE mobile = :mobile AND type = :type LIMIT 1");
      $stmt->bindValue(':mobile', $mobile, PDO::PARAM_INT);
      $stmt->bindValue(':type', $type, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert message_smscode
   */
  public function insertMobileSMScode($mobile, $type, $code) {
    if (!$mobile || !$type || !$code) {
      throw new Exception('mobile, type or code is null...');
    }
  
    $insertFields = array();
    $insertFields['mobile'] = $mobile;
    $insertFields['type'] = $type;
    $insertFields['code'] = $code;
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterMessageConnection(), 'message_smscode', $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findMobileSMScode' . '_' . $mobile . '_' . $type);
      $this->getMemcache()->delete($cacheKey);
    }
    return $res;
  }
  
  /**
   * @return delete message_smscode
   */
  public function deleteMobileSMScode($mobile, $type) {
    if (!$mobile || !$type) {
      throw new Exception('mobile or type is null...');
    }
  
    $stmt = $this->getUcenterMessageConnection()->prepare("DELETE FROM message_smscode WHERE mobile = :mobile AND type = :type");
    $stmt->bindValue(':mobile', $mobile, PDO::PARAM_INT);
    $stmt->bindValue(':type', $type, PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if ($rowCount) {
      $cacheKey = $this->getCacheKey('findMobileSMScode' . '_' . $mobile . '_' . $type);
      $this->getMemcache()->delete($cacheKey);
      return TRUE;
    }
    
    return FALSE;
  }
  
  /**
   * @return message_rc_user_token
   */
  public function findRcUserToken($uid) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $uid);
    $row = $this->getMemcache()->get($cacheKey);
    if (!$row) {
      $stmt = $this->getUcenterMessageConnection()->prepare("SELECT * FROM {$this->getMessageRcUserTokenTableName($uid)} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert message_rc_user_token
   */
  public function insertRcUserToken($uid, $token) {
    if (!$uid || !$token) {
      throw new Exception('uid or token is null...');
    }
  
    $resFields = array();
    $insertFields = array();
    $insertFields['uid'] = $uid;
    $insertFields['token'] = $token;
    $res = $this->insert($this->getUcenterMessageConnection(), $this->getMessageRcUserTokenTableName($uid), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findRcUserToken' . '_' . $uid);
      $this->getMemcache()->delete($cacheKey);
      $resFields = $insertFields;
    }
    return $resFields;
  }
  
  /**
   * @desc update message_rc_user_token
   */
  public function updateRcUserToken($uid, $token) {
    if (!$uid || !$token) {
      throw new Exception('uid or token is null...');
    }
  
    $updateFields = array();
    $updateFields['token'] = $token;
    $updateFields['updated_time'] = time();
    if ($updateFields) {
      $stmt = $this->getUcenterMessageConnection()->prepare("UPDATE {$this->getMessageRcUserTokenTableName($uid)} {$this->getUpdateSect($updateFields)} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $cacheKey = $this->getCacheKey('findRcUserToken' . '_' . $uid);
        $this->getMemcache()->delete($cacheKey);
        return TRUE;
      }
    }
    return FALSE;
  }
}