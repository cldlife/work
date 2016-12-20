<?php
/**
 * @desc UserFortuneDAO
 */
class UserFortuneDAO extends BaseDao {

  //缓存key前缀
  const CACHE_PREFIX = 'USER_FORTUNE_CACHE_';
  
  //缓存版本号
  const CACHE_VERSION = '332365.0';

  //缓存 namespace
  const USER_FORTUNE_SPACE_NAME = 'USER_FORTUNE_SPACE_NAME';
  
  //缓存 scope key前缀
  const USER_FORTUNE_STATUS_SCOPE_REFIX = 'USER_FORTUNE_STATUS_SCOPE';
  
  private function getCacheKey($id) {
    return self::CACHE_PREFIX . strtoupper($id) . '_' . self::CACHE_VERSION;
  }

  private function getUserFortuneMemcacheNameSpace() {
    return $this->getMemcacheNameSpace(self::USER_FORTUNE_SPACE_NAME, self::CACHE_TIME);
  }
  
  private function getUserFortuneStatusScopeKey($uid) {
    return self::USER_FORTUNE_STATUS_SCOPE_REFIX . $uid;
  }

  private function getUserFortuneStatusTableName ($uid) {
   return $this->getHashTableName($uid, 'uf_status', self::LARGE_HASH_TABLE_NUM);
   
  }
  
  private function getUserFortunePointTableName ($uid) {
    return $this->getHashTableName($uid, 'uf_point', self::LARGE_HASH_TABLE_NUM);
  }

  private function getUserFortuneCoinTableName ($uid) {
    return $this->getHashTableName($uid, 'uf_coin', self::LARGE_HASH_TABLE_NUM);
  }

  private function getUserFortuneRoseTableName ($uid) {
    return $this->getHashTableName($uid, 'uf_rose', self::LARGE_HASH_TABLE_NUM);
  }

  private function getUserFortuneValuesTableName ($uid) {
    return $this->getHashTableName($uid, 'uf_values', self::LARGE_HASH_TABLE_NUM);
  }
  
  /**
   * @return uf_status
   */
  public function findUserFortuneStatusWithUid($uid) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $uid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterFortuneConnection()->prepare("SELECT * FROM {$this->getUserFortuneStatusTableName($uid)} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert uf_status
   */
  public function insertUserFortuneStatus($uid, $fields = array()) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
  
    $insertFields = array();
    $insertFields['uid'] = $uid;
    $insertFields['points'] = $fields['points'] ? $fields['points'] : 0;
    $insertFields['coins'] = $fields['coins'] ? $fields['coins'] : 0;
    $insertFields['roses'] = $fields['roses'] ? $fields['roses'] : 0;
    $insertFields['values'] = $fields['values'] ? $fields['values'] : 0;
    $insertFields['privilege_public_num'] = $fields['privilege_public_num'] ? $fields['privilege_public_num'] : 0;
    $insertFields['friending_roses'] = $fields['friending_roses'] ? $fields['friending_roses'] : 0;
    $insertFields['is_setted_passwd'] = $fields['is_setted_passwd'] ? $fields['is_setted_passwd'] : 0;
    $insertFields['is_binded_mobile'] = $fields['is_binded_mobile'] ? $fields['is_binded_mobile'] : 0;
    $insertFields['is_need_edit'] = $fields['is_need_edit'];
    $insertFields['is_app_installed'] = $fields['is_app_installed'] ? $fields['is_app_installed'] : 0;
    $insertFields['created_time'] = 'NONE';
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterFortuneConnection(), $this->getUserFortuneStatusTableName($uid), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findUserFortuneStatusWithUid' . '_' . $uid);
      $this->getMemcache()->delete($cacheKey);
      return $insertFields;
    }
    return array();
  }
  
  /**
   * @desc update uf_status
   */
  public function updateUserFortuneStatusWithUid($uid, Array $fields) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }
  
    $updatedFields = array();
    if (isset($fields['points'])) $updatedFields['points'] = $fields['points'];
    if (isset($fields['coins'])) $updatedFields['coins'] = $fields['coins'];
    if (isset($fields['roses'])) $updatedFields['roses'] = $fields['roses'];
    if (isset($fields['values'])) $updatedFields['values'] = $fields['values'];
    if (isset($fields['privilege_public_num'])) $updatedFields['privilege_public_num'] = $fields['privilege_public_num'];
    if (isset($fields['friending_roses'])) $updatedFields['friending_roses'] = $fields['friending_roses'];
    if (isset($fields['is_setted_passwd'])) $updatedFields['is_setted_passwd'] = $fields['is_setted_passwd'];
    if (isset($fields['is_binded_mobile'])) $updatedFields['is_binded_mobile'] = $fields['is_binded_mobile'];
    if (isset($fields['is_need_edit'])) $updatedFields['is_need_edit'] = $fields['is_need_edit'];
    if (isset($fields['is_app_installed'])) $updatedFields['is_app_installed'] = $fields['is_app_installed'];
    if ($updatedFields) {
      $stmt = $this->getUcenterFortuneConnection()->prepare("UPDATE {$this->getUserFortuneStatusTableName($uid)} {$this->getUpdateSect($updatedFields)} WHERE uid = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $this->bindValues($stmt, $updatedFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $cacheKey = $this->getCacheKey('findUserFortuneStatusWithUid' . '_' . $uid);
        $this->getMemcache()->delete($cacheKey);
        return TRUE;
      }
    }
    
    return FALSE;
  }

  /**
   * @desc increase/decrease uf_status
   */
  public function inDecreaseUserFortuneStatusWithUid($uid, Array $fields) {
    if (!$uid) {
      throw new Exception('uid is null...');
    }

    try {
      $allowedFields = array('points', 'coins', 'roses', 'values', 'privilege_public_num');
      $updateSect = $this->getInDecreaseUpdateSect($fields, $allowedFields);

      if ($updateSect) {
        $stmt = $this->getUcenterFortuneConnection()->prepare("UPDATE {$this->getUserFortuneStatusTableName($uid)} {$updateSect} WHERE uid = :uid LIMIT 1");
        $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount) {
          $cacheKey = $this->getCacheKey('findUserFortuneStatusWithUid' . '_' . $uid);
          $this->getMemcache()->delete($cacheKey);
          return TRUE;
        }
      }
    } catch (PDOException $e) {
      Utils::log(__METHOD__ . ":: error: " . $e->getMessage(), 'DB');
    }
  
    return FALSE;
  }
  
  /**
   * @desc insert uf_point
   */
  public function insertUserFortunePoint($uid, $fields = array()) {
    if (!$uid || !$fields['rule_id'] || !$fields['point']) {
      throw new Exception('uid, rule_id or point is null...');
    }
  
    $insertFields = array();
    $insertFields['uid'] = $uid;
    $insertFields['rule_id'] = $fields['rule_id'];
    $insertFields['point'] = $fields['point'];
    if ($fields['reason']) $insertFields['reason'] = $fields['reason'];
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterFortuneConnection(), $this->getUserFortunePointTableName($uid), $insertFields);
    if ($res) return $insertFields;
    return array();
  }

  /**
   * @return uf_coin
   */
  public function findUserFortuneCoinWithUidAndRuleId($uid, $ruleId) {
    if (!$uid || !$ruleId) {
      throw new Exception('uid, ruleId is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_RULEID_' . $ruleId) ;
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterFortuneConnection()->prepare("SELECT * FROM {$this->getUserFortuneCoinTableName($uid)} WHERE uid = :uid AND rule_id = :ruleId LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->bindValue(':ruleId', $ruleId, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert uf_coin
   */
  public function insertUserFortuneCoin($uid, $fields = array()) {
    if (!$uid || !$fields['rule_id'] || !$fields['coin']) {
      throw new Exception('uid, rule_id or coin is null...');
    }
    $insertFields = array();
    $insertFields['uid'] = $uid;
    $insertFields['rule_id'] = $fields['rule_id'];
    $insertFields['coin'] = $fields['coin'];
    if ($fields['reason']) $insertFields['reason'] = $fields['reason'];
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterFortuneConnection(), $this->getUserFortuneCoinTableName($uid), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findUserFortuneCoinWithUidAndRuleId' . '_UID_' . $uid . '_RULEID_' . $fields['rule_id']);
      $this->getMemcache()->delete($cacheKey);
      return $insertFields;
    }
    return array();
  }

  /**
   * @desc insert uf_rose
   */
  public function insertUserFortuneRose($uid, $fields = array()) {
    if (!$uid || !$fields['rule_id'] || !$fields['rose']) {
      throw new Exception('uid, rule_id or rose is null...');
    }
    $insertFields = array();
    $insertFields['uid'] = $uid;
    $insertFields['rule_id'] = $fields['rule_id'];
    $insertFields['rose'] = $fields['rose'];
    if ($fields['reason']) $insertFields['reason'] = $fields['reason'];
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterFortuneConnection(), $this->getUserFortuneRoseTableName($uid), $insertFields);
    if ($res) return $insertFields;
    return array();
  }

  /**
   * @return uf_values
   */
  public function findUserFortuneValuesWithUidAndRuleId($uid, $ruleId) {
    if (!$uid || !$ruleId) {
      throw new Exception('uid, ruleId is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_RULEID_' . $ruleId) ;
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterFortuneConnection()->prepare("SELECT * FROM {$this->getUserFortuneValuesTableName($uid)} WHERE uid = :uid AND rule_id = :ruleId LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->bindValue(':ruleId', $ruleId, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row ? $row : array();
  }

  /**
   * @desc insert uf_values
   */
  public function insertUserFortuneValues($uid, $fields = array()) {
    if (!$uid || !$fields['rule_id'] || !$fields['values']) {
      throw new Exception('uid, rule_id or values is null...');
    }
    $insertFields = array();
    $insertFields['uid'] = $uid;
    $insertFields['rule_id'] = $fields['rule_id'];
    $insertFields['values'] = $fields['values'];
    if ($fields['reason']) $insertFields['reason'] = $fields['reason'];
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterFortuneConnection(), $this->getUserFortuneValuesTableName($uid), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findUserFortuneValuesWithUidAndRuleId' . '_UID_' . $uid . '_RULEID_' . $fields['rule_id']);
      $this->getMemcache()->delete($cacheKey);
      return $insertFields;
    }
    return array();
  }
}