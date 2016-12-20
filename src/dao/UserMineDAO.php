<?php
/**
 * @desc UserMineDAO
 */
class UserMineDAO extends BaseDao {

  //缓存key前缀
  const CACHE_PREFIX = 'USER_MINE_CACHE_';
  
  //缓存版本号
  const CACHE_VERSION = '1.0';

  //缓存 namespace
  const USER_MINE_SPACE_NAME = 'USER_MINE_SPACE_NAME';
  
  //缓存 scope key前缀
  const USER_MINE_THREAD_SCOPE_REFIX = 'USER_MINE_THREAD_SCOPE';
  const USER_MINE_FRIEND_SCOPE_REFIX = 'USER_MINE_FRIEND_SCOPE';
  const USER_MINE_DISAPPEAR_USER_SCOPE_REFIX = 'USER_MINE_DISAPPEAR_USER_SCOPE';
  
  private function getCacheKey($id) {
    return self::CACHE_PREFIX . strtoupper($id) . '_' . self::CACHE_VERSION;
  }

  private function getUserMineMemcacheNameSpace() {
    return $this->getMemcacheNameSpace(self::USER_MINE_SPACE_NAME, self::CACHE_TIME);
  }
  
  private function getUserMineThreadScopeKey($uid) {
    return self::USER_MINE_THREAD_SCOPE_REFIX . $uid;
  }
  
  private function getUserMineFriendsScopeKey($uid) {
    return self::USER_MINE_FRIEND_SCOPE_REFIX . $uid;
  }
  private function getUserMineDisappearUserScopeKey($uid) {
    return self::USER_MINE_DISAPPEAR_USER_SCOPE_REFIX . $uid;
  }
  
  private function getMineThreadsTableName ($uid) {
    return $this->getHashTableName($uid, 'um_threads', self::HASH_TABLE_NUM);
  }
  private function getMineFriendsTableName ($uid) {
    return $this->getHashTableName($uid, 'um_friends', self::LARGE_HASH_TABLE_NUM);
  }
  private function getMineDisappearUserTableName ($uid) {
    return $this->getHashTableName($uid, 'um_disappear_user', self::HASH_TABLE_NUM);
  }
  
  /**
   * @return um_threads
   */
  public function findMineThreadsWithUid($uid, $offset = 0, $limit = 10) {
    if (!$uid || !$limit) {
      throw new Exception('uid or limit is null...');
    }
    
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getUserMineThreadScopeKey($uid);
    $rows = $this->getUserMineMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getUcenterMineConnection()->prepare("SELECT * FROM {$this->getMineThreadsTableName($uid)} WHERE uid = :uid ORDER BY created_time DESC LIMIT {$offset}, {$limit}");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getUserMineMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows ? $rows : array();
  }
  
  /**
   * @desc insert um_threads
   */
  public function insertMineThread($uid, $tid) {
    if (!$uid || !$tid) {
      throw new Exception('uid or tid is null...');
    }
    
    $insertFields = array();
    $insertFields['uid'] = $uid;
    $insertFields['tid'] = $tid;
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterMineConnection(), $this->getMineThreadsTableName($uid), $insertFields);
    if ($res) {
      $scopeKey = $this->getUserMineThreadScopeKey($uid);
   	  $this->getUserMineMemcacheNameSpace()->removeBatchKeys($scopeKey);
   	  return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc delete um_threads
   */
  public function deleteMineThread ($uid, $tid) {
    if (!$uid || !$tid) {
      throw new Exception('uid or tid id null...');
    }
  
    $stmt = $this->getUcenterMineConnection()->prepare("DELETE FROM {$this->getMineThreadsTableName($uid)} WHERE uid = :uid AND tid = :tid LIMIT 1");
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if ($rowCount) {
      $scopeKey = $this->getUserMineThreadScopeKey($uid);
   	  $this->getUserMineMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @return um_friends
   */
  public function findMineFriendsWithUid($uid, $offset = 0, $limit = 10) {
    if (!$uid || !$limit) {
      throw new Exception('uid or limit is null...');
    }
    
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getUserMineFriendsScopeKey($uid);
    $rows = $this->getUserMineMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getUcenterMineConnection()->prepare("SELECT * FROM {$this->getMineFriendsTableName($uid)} WHERE uid = :uid ORDER BY created_time DESC LIMIT {$offset}, {$limit}");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows))$rows = array();
      $this->getUserMineMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows ? $rows : array();
  }
  
  /**
   * @return um_friends (with friend_uids)
   */
  public function findMineFriendsWithFriendUids($uid, Array $friendUids) {
    if (!$uid || !$friendUids) {
      throw new Exception('uid or friend_uid is null...');
    }
  
    $friendUidsImp = implode(',', $friendUids);
    
    if ($friendUidsImp && $friendUidsImp != 'Array') {
      $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_FOLLOWUIDS_' . $friendUidsImp);
      $scopeKey = $this->getUserMineFriendsScopeKey($uid);
      $rows = $this->getUserMineMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
      if ($rows === FALSE) {
        $stmt = $this->getUcenterMineConnection()->prepare("SELECT * FROM {$this->getMineFriendsTableName($uid)} WHERE uid = :uid AND friend_uid IN ({$friendUidsImp})");
        $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($rows))$rows = array();
        $this->getUserMineMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
      }
    }
    return $rows ? $rows : array();
  }
  
  /**
   * @return um_friends (with friend_uid)
   */
  public function findMineFriendWithFriendUid($uid, $friendUid) {
    if (!$uid || !$friendUid) {
      throw new Exception('uid or friend_uid is null...');
    }
    
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_FOLLOWUID_' . $friendUid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterMineConnection()->prepare("SELECT * FROM {$this->getMineFriendsTableName($uid)} WHERE uid = :uid AND friend_uid = :friend_uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->bindValue(':friend_uid', $friendUid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert um_friends
   */
  public function insertMineFriend($uid, $friendUid) {
    if (!$uid || !$friendUid) {
      throw new Exception('uid or friend_uid is null...');
    }
  
    $insertFields = array();
    $insertFields['uid'] = $uid;
    $insertFields['friend_uid'] = $friendUid;
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterMineConnection(), $this->getMineFriendsTableName($uid), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findMineFriendWithFriendUid' . '_UID_' . $uid . '_FOLLOWUID_' . $friendUid);
      $this->getMemcache()->delete($cacheKey);
  
      $scopeKey = $this->getUserMineFriendsScopeKey($uid);
      $this->getUserMineMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc delete um_friends
   */
  public function deleteMineFriend ($uid, $friendUid) {
    if (!$uid || !$friendUid) {
      throw new Exception('uid or friend_uid is null...');
    }
  
    $stmt = $this->getUcenterMineConnection()->prepare("DELETE FROM {$this->getMineFriendsTableName($uid)} WHERE uid = :uid AND friend_uid = :friend_uid LIMIT 1");
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->bindValue(':friend_uid', $friendUid, PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if ($rowCount) {
      $cacheKey = $this->getCacheKey('findMineFriendWithFriendUid' . '_UID_' . $uid . '_FOLLOWUID_' . $friendUid);
      $this->getMemcache()->delete($cacheKey);
  
      $scopeKey = $this->getUserMineFriendsScopeKey($uid);
      $this->getUserMineMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @return um_disappear_user (with disappear_uids)
   */
  public function findMineDisappearUsersWithUids($uid, Array $disappearUids) {
    if (!$uid || !$disappearUids) {
      throw new Exception('uid or disappear_uid is null...');
    }
  
    $disappearUidsImp = implode(',', $disappearUids);
    if ($disappearUidsImp && $disappearUidsImp != 'Array') {
      $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_DISAPPEARUIDS_' . $disappearUidsImp);
      $scopeKey = $this->getUserMineDisappearUserScopeKey($uid);
      $rows = $this->getUserMineMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
      if ($rows === FALSE) {
        $stmt = $this->getUcenterMineConnection()->prepare("SELECT * FROM {$this->getMineDisappearUserTableName($uid)} WHERE uid = :uid AND disappear_uid IN ({$disappearUidsImp})");
        $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($rows))$rows = array();
        $this->getUserMineMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
      }
    }
    return $rows ? $rows : array();
  }
  
  /**
   * @return um_disappear_user (with disappear_uid)
   */
  public function findMineDisappearUsersWithUid($uid, $disappearUid) {
    if (!$uid || !$disappearUid) {
      throw new Exception('uid or disappear_uid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_DISAPPEARUID_' . $disappearUid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getUcenterMineConnection()->prepare("SELECT * FROM {$this->getMineDisappearUserTableName($uid)} WHERE uid = :uid AND disappear_uid = :disappear_uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->bindValue(':disappear_uid', $disappearUid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert um_disappear_user
   */
  public function insertMineDisappearUser($uid, $disappearUid) {
    if (!$uid || !$disappearUid) {
      throw new Exception('uid or disappear_uid is null...');
    }
  
    $insertFields = array();
    $insertFields['uid'] = $uid;
    $insertFields['disappear_uid'] = $disappearUid;
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getUcenterMineConnection(), $this->getMineDisappearUserTableName($uid), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findMineDisappearUsersWithUid' . '_UID_' . $uid . '_DISAPPEARUID_' . $disappearUid);
      $this->getMemcache()->delete($cacheKey);
  
      $scopeKey = $this->getUserMineDisappearUserScopeKey($uid);
      $this->getUserMineMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
    return FALSE;
  }
}