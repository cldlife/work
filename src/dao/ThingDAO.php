<?php
/**
 * @desc ThingDAO
 */
class ThingDAO extends BaseDao {

  //缓存key前缀
  const CACHE_PREFIX = 'THING_CACHE_';
  
  //缓存版本号
  const CACHE_VERSION = '1.0';

  //缓存 namespace
  const THING_SPACE_NAME = 'THING_SPACE_NAME';

  //缓存 scope key前缀
  const THREAD_LIST_SCOPE_REFIX = 'SCOPE_THREAD_LIST_';
  
  const THREAD_POST_LIST_SCOPE_REFIX = 'SCOPE_THREAD_POST_LIST_';

  const THREAD_VOTE_USER_SCOPE_REFIX = 'SCOPE_THREAD_VOTE_USER_';

  private function getCacheKey($id) {
    return self::CACHE_PREFIX . strtoupper($id) . '_' . self::CACHE_VERSION;
  }
  private function getThingMemcacheNameSpace() {
    return $this->getMemcacheNameSpace(self::THING_SPACE_NAME, self::CACHE_TIME);
  }
  
  private function getThreadListScopeKey($category) {
    return self::THREAD_LIST_SCOPE_REFIX . $category;
  }
  private function getThreadPostListScopeKey($tid) {
    return self::THREAD_POST_LIST_SCOPE_REFIX . $tid;
  }
  private function getThreadVoteUserScopeKey($tid) {
    return self::THREAD_VOTE_USER_SCOPE_REFIX . $tid;
  }
  
  private function getThreadTableName ($tid) {
    return $this->getHashTableName($tid, 'thing_thread');
  }
  private function getThreadStatusTableName ($tid) {
    return $this->getHashTableName($tid, 'thing_thread_status');
  }
  private function getThreadPostTableName ($tid) {
    return $this->getHashTableName($tid, 'thing_thread_post', self::LARGE_HASH_TABLE_NUM);
  }
  private function getThreadListTableName () {
    return 'thing_thread_list';
  }
  private function getThreadVoteUserTableName ($tid) {
    return $this->getHashTableName($tid, 'thing_thread_vote_user', self::LARGE_HASH_TABLE_NUM);
  }

  /**
   * @return thing_thread_list (with category)
   */
  public function findThreadListWithCategory($category, $offset = 0, $limit = 10) {
    if (!$category || !$limit) {
      throw new Exception('category or limit is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_CATEGORY_' . $category . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getThreadListScopeKey($category);
    $rows = $this->getThingMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getThingConnection()->prepare("SELECT * FROM {$this->getThreadListTableName()} WHERE category = :category ORDER BY created_time DESC LIMIT {$offset}, {$limit}");
      $stmt->bindValue(':category', $category, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
      $cacheTime = self::CACHE_TIME;
      if (empty($rows)) {
        $rows = array();
        $cacheTime = self::NONE_CACHE_TIME;
      }
      $this->getThingMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, $cacheTime);
    }
    return $rows;
  }
  
  /**
   * @desc insert thing_thread_list
   */
  public function insertThreadList(Array $fields) {
    if (!$fields['tid'] || !$fields['category']) {
      throw new Exception('tid, category or uid is null...');
    }
  
    $resFields = array();
    $insertFields = array();
    $insertFields['tid'] = $fields['tid'];
    $insertFields['category'] = $fields['category'];
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getThingConnection(), $this->getThreadListTableName(), $insertFields);
    if ($res) {
      $scopeKey = $this->getThreadListScopeKey($fields['category']);
      $this->getThingMemcacheNameSpace()->removeBatchKeys($scopeKey);
      $resFields = $insertFields;
    }
    return $resFields;
  }
  
  /**
   * @desc delete thing_thread_list (with tid)
   */
  public function deleteThreadList($tid, $category) {
    if (!$tid || !$category) {
      throw new Exception('tid or category is null...');
    }
  
    $stmt = $this->getThingConnection()->prepare("DELETE FROM {$this->getThreadListTableName()} WHERE tid = :tid LIMIT 1");
    $stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if ($rowCount) {
      $scopeKey = $this->getThreadListScopeKey($category);
      $this->getThingMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @return thing_thread
   */
  public function findThreadBytid($tid) {
    if (!$tid) {
      throw new Exception('tid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_TID_' . $tid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getThingConnection()->prepare("SELECT * FROM {$this->getThreadTableName($tid)} WHERE tid = :tid LIMIT 1");
      $stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert thing_thread
   */
  public function insertThread(Array $fields) {
    if (!$fields['tid'] || !$fields['uid'] || !$fields['content']) {
      throw new Exception('tid, uid or content is null...');
    }
  
    $resFields = array();
    $insertFields = array();
    $insertFields['tid'] = $fields['tid'];
    if ($fields['category']) $insertFields['category'] = $fields['category'];
    if ($fields['extend_type']) $insertFields['extend_type'] = $fields['extend_type'];
    if ($fields['extend_id']) $insertFields['extend_id'] = $fields['extend_id'];
    $insertFields['uid'] = $fields['uid'];
    $insertFields['content'] = $fields['content'];
    if ($fields['attach_hashid']) $insertFields['attach_hashid'] = $fields['attach_hashid'];
    if (isset($fields['status'])) $insertFields['status'] = $fields['status'];
    $res = $this->insert($this->getThingConnection(), $this->getThreadTableName($fields['tid']), $insertFields);
    if ($res) $resFields = $insertFields;
    return $resFields;
  }
  
  /**
   * @desc update thing_thread
   */
  public function updateThread($tid, Array $fields) {
    if (!$tid) {
      throw new Exception('tid is null...');
    }
    
    $resFields = array();
    $updateFields = array();
    if ($fields['content']) $updateFields['content'] = trim($fields['content']);
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($fields['updated_time']) $updateFields['updated_time'] = $fields['updated_time'];
    if ($updateFields) {
      $stmt = $this->getThingConnection()->prepare("UPDATE {$this->getThreadTableName($tid)} {$this->getUpdateSect($updateFields)} WHERE tid = :tid LIMIT 1");
      $stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $cacheKey = $this->getCacheKey('findThreadBytid' . '_TID_' . $tid);
        $this->getMemcache()->delete($cacheKey);
        $resFields = $updateFields;
      }
    }
  
    return $resFields;
  }
  
  /**
   * @return thing_thread_status
   */
  public function findThreadStatusBytid($tid) {
    if (!$tid) {
      throw new Exception('tid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_TID_' . $tid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getThingConnection()->prepare("SELECT * FROM {$this->getThreadStatusTableName($tid)} WHERE tid = :tid LIMIT 1");
      $stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert thing_thread_status
   */
  public function insertThreadStatus(Array $fields) {
    if (!$fields['tid']) {
      throw new Exception('tid is null...');
    }
  
    $resFields = array();
    $insertFields = array();
    $insertFields['tid'] = $fields['tid'];
    if ($fields['replies']) $insertFields['replies'] = $fields['replies'];
    if ($fields['votes']) $insertFields['votes'] = $fields['votes'];
    $insertFields['created_time'] = 'NONE';
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getThingConnection(), $this->getThreadStatusTableName($fields['tid']), $insertFields);
    if ($res) $resFields = $insertFields;
    return $resFields;
  }
  
  /**
   * @desc update thing_thread_status
   */
  public function updateThreadStatus($tid, Array $fields) {
    if (!$tid) {
      throw new Exception('tid is null...');
    }
  
    $resFields = array();
    $updateFields = array();
    if (isset($fields['replies'])) $updateFields['replies'] = $fields['replies'];
    if (isset($fields['votes'])) $updateFields['votes'] = $fields['votes'];
    if ($updateFields) {
      $stmt = $this->getThingConnection()->prepare("UPDATE {$this->getThreadStatusTableName($tid)} {$this->getUpdateSect($updateFields)} WHERE tid = :tid LIMIT 1");
      $stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $cacheKey = $this->getCacheKey('findThreadStatusBytid' . '_TID_' . $tid);
        $this->getMemcache()->delete($cacheKey);
        $resFields = $updateFields;
      }
    }
  
    return $resFields;
  }
  
  /**
   * @desc increase/decrease thing_thread_status
   */
  public function inDecreaseThreadStatusWithTid($tid, Array $fields) {
    if (!$tid) {
      throw new Exception('tid is null...');
    }
  
    try {
      $allowedFields = array('replies', 'votes');
      $updateSect = $this->getInDecreaseUpdateSect($fields, $allowedFields);
      if ($updateSect) {
        $stmt = $this->getThingConnection()->prepare("UPDATE {$this->getThreadStatusTableName($tid)} {$updateSect} WHERE tid = :tid LIMIT 1");
        $stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount) {
          $cacheKey = $this->getCacheKey('findThreadStatusBytid' . '_TID_' . $tid);
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
   * @return thing_thread_post
   */
  public function findThreadPostListBytid($tid, $offset = 0, $limit = 10) {
    if (!$tid || !$limit) {
      throw new Exception('tid or limit is null...');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_TID_' . $tid . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getThreadPostListScopeKey($tid);
    $rows = $this->getThingMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getThingConnection()->prepare("SELECT * FROM {$this->getThreadPostTableName($tid)} WHERE tid = :tid AND status = 0 ORDER BY created_time DESC LIMIT {$offset}, {$limit}");
      $stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      $cacheTime = self::CACHE_TIME;
      if (empty($rows)) {
        $rows = array();
        $cacheTime = self::NONE_CACHE_TIME;
      }
      $this->getThingMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, $cacheTime);
    }
    return $rows ? $rows : array();
  }
  
  /**
   * @return thing_thread_post (single)
   */
  public function findThreadPostById($pid, $tid) {
    if (!$pid || !$tid) {
      throw new Exception('pid or tid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_PID_' . $pid . '_TID_' . $tid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getThingConnection()->prepare("SELECT * FROM {$this->getThreadPostTableName($tid)} WHERE pid = :pid LIMIT 1");
      $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc insert thing_thread_post
   */
  public function insertThreadPost($pid, $tid, $uid, Array $fields) {
    if (!$pid || !$tid || !$uid || !$fields['content']) {
      throw new Exception('pid, tid, uid or content is null...');
    }
  
    $resFields = array();
    $insertFields = array();
    $insertFields['pid'] = $pid;
    $insertFields['tid'] = $tid;
    $insertFields['uid'] = $uid;
    $insertFields['content'] = $fields['content'];
    if ($fields['replied_uid']) $insertFields['replied_uid'] = $fields['replied_uid'];
    $res = $this->insert($this->getThingConnection(), $this->getThreadPostTableName($tid), $insertFields);
    if ($res) {
      $scopeKey = $this->getThreadPostListScopeKey($tid);
      $this->getThingMemcacheNameSpace()->removeBatchKeys($scopeKey);
      $resFields = $insertFields;
    }
    return $resFields;
  }
  
  /**
   * @desc update thing_thread_post
   */
  public function updateThreadPost($pid, $tid, Array $fields) {
    if (!$pid || !$tid || !$fields) {
      throw new Exception('pid or tid is null...');
    }
  
    $resFields = array();
    $updateFields = array();
    if ($fields['is_invisible']) $updateFields['is_invisible'] = $fields['is_invisible'];
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($updateFields) {
      $updateFields['updated_time'] = time();
    
      $stmt = $this->getThingConnection()->prepare("UPDATE {$this->getThreadPostTableName($tid)} {$this->getUpdateSect($updateFields)} WHERE pid = :pid LIMIT 1");
      $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $scopeKey = $this->getThreadPostListScopeKey($tid);
        $this->getThingMemcacheNameSpace()->removeBatchKeys($scopeKey);
        $resFields = $updateFields;
      }
    }
    return $resFields;
  }

  /**
   * @return thing_thread_vote_user
   */
  public function findThreadVoteUserWithTidAndUid ($tid, $uid){
    if (!$tid || !$uid) {
      throw new Exception('tid or uid is null...');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_TID' . $tid . '_UID_' . $uid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getThingConnection()->prepare("SELECT * FROM {$this->getThreadVoteUserTableName($tid)} WHERE tid = :tid AND uid = :uid LIMIT 1");
      $stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc insert thing_thread_vote_user
   */
  public function insertThreadVoteUser ($tid, $uid){
    if (!$tid || !$uid) {
      throw new Exception('tid or uid is null...');
    }
    
    $resFields = array();
    $insertFields = array();
    $insertFields['tid'] = $tid;
    $insertFields['uid'] = $uid;
    $insertFields['created_time'] = time();
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getThingConnection(), $this->getThreadVoteUserTableName($tid), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findThreadVoteUserWithTidAndUid' . '_TID' . $tid . '_UID_' . $uid);
      $this->getMemcache()->delete($cacheKey);
      $scopeKey = $this->getThreadVoteUserScopeKey($tid);
      $this->getThingMemcacheNameSpace()->removeBatchKeys($scopeKey);
      $resFields = $insertFields;
    }
    return $resFields;
  }
 
  /**
   * @desc select thing_thread_vote_user
   * @author chu
   * @return array
   */
  public function findThreadVoteUsersWithTid ($tid, $offset = 0, $limit = 30) {
    if (!$tid || !$limit) {
      throw new Exception('tid or limit is null...');
    }
    
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_TID_' . $tid . '_OFFSET_' . $offset . 'LIMIT' . $limit);
    $scopeKey = $this->getThreadVoteUserScopeKey($tid);
    $rows = $this->getThingMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getThingConnection()->prepare("SELECT * FROM {$this->getThreadVoteUserTableName($tid)} WHERE tid = :tid ORDER BY created_time DESC LIMIT :offset, :limit");
      $stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getThingMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows;
  }
}