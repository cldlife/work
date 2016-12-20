<?php
/**
 * @desc HougongDAO
 */
class HougongDAO extends BaseDAO {
 
  //cache key prefix
  const CACHE_PREFIX = 'HOUGONG_CACHE_';
 
  //cache version
  const CACHE_VERSION = '3333.1';


  //cache namespace
  const HOUGONG_SPACE_NAME = 'HOUGONG_SPACE_NAME';

  //game scope keys
  const HG_RELATION_SLAVELIST_PREFIX = 'SCOPE_HG_RELATION_SLAVELIST_';
  const HG_VISITORLIST_PREFIX = 'SCOPE_HG_VISITORLIST_PREFIX';
  const HG_NOTICELIST_PREFIX = 'SCOPE_HG_NOTICE_PREFIX';

  private function getCacheKey($id) {
    return self::CACHE_PREFIX . strtoupper($id) . '_' . self::CACHE_VERSION;
  }
  private function getHougongMemcacheNameSpace() {
    return $this->getMemcacheNameSpace(self::HOUGONG_SPACE_NAME, self::CACHE_TIME);
  }

  //get game scope keys
  private function getRelationSlaveListScopeKey ($uid) {
    return self::HG_RELATION_SLAVELIST_PREFIX.$uid;
  }
  
  private function getVisitorListScopeKey ($uid) {
    return self::HG_VISITORLIST_PREFIX.$uid;
  }

  private function getNoticeListScopeKey ($uid) {
    return self::HG_NOTICELIST_PREFIX.$uid;
  }
  //get hougong table names
  private function getHgRelationTableName ($uid) {
    return $this->getHashTableName($uid, 'hg_relation', self::HASH_TABLE_NUM);
  }
  
  private function getHgTaskTableName ($uid) {
    return $this->getHashTableName($uid, 'hg_task', self::HASH_TABLE_NUM);
  }

  private function getHgTaskGetcoinUsersTableName ($uid) {
    return $this->getHashTableName($uid, 'hg_task_getcoin_users', self::HASH_TABLE_NUM);
  }
  
  private function getHgVisitorTableName ($taskid) {
    return $this->getHashTableName($taskid, 'hg_visitor', self::HASH_TABLE_NUM);
  }

  private function getHgNoticeTableName ($uid) {
    return $this->getHashTableName($uid, 'hg_notice', self::HASH_TABLE_NUM);
  }
  /**
   * @desc select from hg_relation
   * @desc level 1-主人 2-奴隶
   * @param int $uid $level
   * @return array
   */
  public function findHgRelationMasterByUidAndLevel ($uid, $level = 1) {
    if (!$uid || !$level) {
      throw new Exception('uid and level is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getHougongConnection()->prepare("SELECT * FROM `{$this->getHgRelationTableName($uid)}` WHERE `uid` = :uid and `level` = :level ORDER BY created_time DESC LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->bindValue(':level', $level, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $row = $row ? $row : array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }


  /**
   * @desc select from hg_relation 
   * @desc level 1-主人 2-奴隶
   * @param int $uid $level
   * @return array
   */ 
  public function findHgRelationSlaveListByUidAndLevel ($uid, $offset = 0, $limit = 30, $level = 2) {
    if (!$uid || !$level) {
      throw new Exception('uid and level is null');
    }
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_LEVEL_' . $level . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getRelationSlaveListScopeKey($uid);
    $rows = $this->getHougongMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getHougongConnection()->prepare("SELECT * FROM `{$this->getHgRelationTableName($uid)}` WHERE `uid` = :uid and `level` = :level ORDER BY created_time DESC LIMIT :offset, :limit");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->bindValue(':level', $level, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getHougongMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @desc insert hg_relation 
   * @desc level 1-主人 2-奴隶
   * @param $field
   * @return bool
   */
  public function insertHgRelation ($fields) {
    if (!$fields['uid'] || !$fields['level'] || !$fields['relation_uid']) {
       throw new Exception('uid, level and relation_uid is null...');
     }
    
    $insertFields = array();
    $insertFields['uid'] = $fields['uid'];
    $insertFields['level'] = $fields['level'];
    $insertFields['relation_uid'] =  $fields['relation_uid'];
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getHougongConnection(), $this->getHgRelationTableName($fields['uid']), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findHgRelationMasterByUidAndLevel' . '_UID_' . $fields['uid']);
      $this->getMemcache()->delete($cacheKey);
      $scopeKey = $this->getRelationSlaveListScopeKey($fields['uid']);
      $this->getHougongMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
    return FALSE;
  }    

  /**
   * @desc delete hg_relation
   * @param uid reUid-关系uid
   * @return bool
   */
  public function deleteHgRelation($uid, $ruid) {
    if (!$uid || !$ruid) {
      throw new Exception('uid or ruid is null...');
    }
    $sql = "DELETE FROM {$this->getHgRelationTableName($uid)} WHERE uid = :uid AND relation_uid = :ruid LIMIT 1";
    $stmt = $this->getHougongConnection()->prepare($sql);
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->bindValue(':ruid', $ruid, PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if ($rowCount) {
      $cacheKey = $this->getCacheKey('findHgRelationMasterByUidAndLevel' . '_UID_' . $uid);
      $this->getMemcache()->delete($cacheKey);
      $scopeKey = $this->getRelationSlaveListScopeKey($uid);
      $this->getHougongMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc select from hg_Task where task_id
   * @return array
   */
  public function findHgTaskWithId ($taskid, $uid) {
    if (!$taskid) {
      throw new Exception('task_id is null');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_TASKID_' . $taskid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getHougongConnection()->prepare("SELECT * FROM `{$this->getHgTaskTableName($uid)}` WHERE `id` = :task_id LIMIT 1");
      $stmt->bindValue(':task_id', $taskid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $row = $row ? $row : array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }
  
  /**
   * @desc select from hg_Task where uid
   * @param int $uid
   * @return array
   */
  public function findHgTaskWithUid ($uid) {
    if (!$uid) {
      throw new Exception('uid is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getHougongConnection()->prepare("SELECT * FROM `{$this->getHgTaskTableName($uid)}` WHERE `uid` = :uid ORDER BY created_time DESC LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $row = $row ? $row : array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }
  

  /**
   * @desc insert hg_Task 
   * @desc status 0-休息中,1-任务中,2-任务完成
   * @param $field
   * @return bool
   */
  public function insertHgTask ($fields) {
    if (!$fields['uid'] || !$fields['task'] || !$fields['total_coins'] || !$fields['remain_coins'] || !$fields['status']) {
     throw new Exception('uid, task, total_coins, remain_coins or status is null');
    }
    
    $resFields = array();
    $insertFields = array();
    $insertFields['uid'] = $fields['uid'];
    $insertFields['task'] = $fields['task'];
    $insertFields['total_coins'] =  $fields['total_coins'];
    $insertFields['remain_coins'] = $fields['remain_coins'];
    $insertFields['status'] = $fields['status'];
    $taskId = $this->insert($this->getHougongConnection(), $this->getHgTaskTableName($fields['uid']), $insertFields, TRUE);
    if ($taskId) {
      $cacheKey = $this->getCacheKey('findHgTaskWithId' . '_TASKID_' . $taskId);
      $this->getMemcache()->delete($cacheKey);
      $cacheKey = $this->getCacheKey('findHgTaskWithUid' . '_UID_' . $fields['uid']);
      $this->getMemcache()->delete($cacheKey);
      
      $insertFields['id'] = $taskId;
      $resFields = $insertFields;
    }
    return $resFields;
  }    

  /**
   * @desc update hg_task
   * @desc status 0-休息中,1-任务中,2-任务完成
   * @param $field
   * @return bool
   */
  public function updateHgTask($fields) {
    if (!$fields['uid'] || !$fields['id'] ) {
      throw new Exception('uid or id is null...');
    }

    $updateFields = array();
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if (isset($fields['remain_coins'])) $updateFields['remain_coins'] = $fields['remain_coins'];
    if (isset($fields['coins_status'])) $updateFields['coins_status'] = $fields['coins_status'];
    if ($updateFields) {
      if (!$fields['updated_time']) $updateFields['updated_time'] = time();
      $sql = "UPDATE {$this->getHgTaskTableName($fields['uid'])} {$this->getUpdateSect($updateFields)} WHERE id = :id LIMIT 1";
      $stmt = $this->getHougongConnection()->prepare($sql);
      $stmt->bindValue(':id', $fields['id'], PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $cacheKey = $this->getCacheKey('findHgTaskWithId' . '_TASKID_' . $fields['id']);
        $this->getMemcache()->delete($cacheKey);
        $cacheKey = $this->getCacheKey('findHgTaskWithUid' . '_UID_' . $fields['uid']);
        $this->getMemcache()->delete($cacheKey);
        return TRUE;
      }
    }
    return FALSE;
  }


  /**
   * @desc select from hg_task_getcoinusers
   * @param $task_id $uid
   * @return array
   */
  public function findHgTaskGetcoinUsersByTaskidAndUid ($taskid, $uid) {
    if (!$taskid || !$uid) {
      throw new Exception('taskid or uid is null');
    } 
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_TASKID_' . $taskid . '_UID_' . $uid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getHougongConnection()->prepare("SELECT * FROM `{$this->getHgTaskGetcoinUsersTableName($taskid)}` WHERE `task_id` = :taskid AND `uid` = :uid LIMIT 1");
      $stmt->bindValue(':taskid', $taskid, PDO::PARAM_INT);
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $row = $row ? $row : array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
     return $row;
  }

  /**
   * @desc insert hg_task_getcoin_users
   * @param $field
   * @return bool
   */
  public function insertHgTaskGetcoinUsers ($fields) {
    if (!$fields['task_id'] || !$fields['uid']) {
     throw new Exception('task_id or uid is null');
    }
    
    $insertFields = array();
    $insertFields['task_id'] = $fields['task_id'];
    $insertFields['uid'] = $fields['uid'];
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getHougongConnection(), $this->getHgTaskGetcoinUsersTableName($fields['task_id']), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findHgTaskGetcoinUsersByTaskidAndUid' . '_TASKID_' . $fields['task_id'] . '_UID_' . $fields['uid']);
      $this->getMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }    
 
  /**
   * @desc select from hg_visitor
   * @param $uid $viuid
   * @return array
   */
  public function findHgVisitorByUidAndViuid ($uid, $viuid) {
    if (!$uid || !$viuid) {
      throw new Exception('uid, viuid is null');
    } 

    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_VIUID_' . $viuid);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getHougongConnection()->prepare("SELECT * FROM `{$this->getHgVisitorTableName($uid)}` WHERE `uid` = :uid AND `visitor_uid` = :viuid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->bindValue(':viuid', $viuid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $row = $row ? $row : array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
   }
     return $row;
  }

  /**
   * @desc select from hg_visitor
   * @param int $uid $time
   * @return array
   */
  public function findHgVisitorCountByUidAndTime ($uid, $time) {
    if (!$uid || !$time) {
      throw new Exception('uid or time is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_TIME_' . $time);
    $count = $this->getMemcache()->get($cacheKey);
    if ($count === FALSE) {
      $stmt = $this->getHougongConnection()->prepare("SELECT COUNT(*) FROM {$this->getHgVisitorTableName($uid)} WHERE `uid` = :uid AND `updated_time` >= :time LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->bindValue(':time', $time, PDO::PARAM_INT);
      $stmt->execute();
      $count = $stmt->fetchColumn();
      $count = $count ? $count : 0;
      $this->getMemcache()->set($cacheKey, $count, 0, self::THIRTY_CACHE_TIME);
    }
    return $count;
  }

  /**
   * @desc select from hg_visitor
   * @param $uid
   * @return array
   */
  public function findHgVisitorListByUid ($uid, $offset = 0, $limit = 30) {
    if (!$uid) {
      throw new Exception('uid is null');
    }
 
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getVisitorListScopeKey($uid);
    $rows = $this->getHougongMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getHougongConnection()->prepare("SELECT * FROM `{$this->getHgVisitorTableName($uid)}` WHERE `uid` = :uid ORDER BY updated_time DESC LIMIT :offset, :limit");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getHougongMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @desc select from hg_visitor
   * @param $uid 
   * @return bool
   */
  public function findHgVisitorCountByUid ($uid) {
    if (!$uid) {
      throw new Exception('uid is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid);
    $count = $this->getMemcache()->get($cacheKey);
    if ($count === FALSE) {
      $stmt = $this->getHougongConnection()->prepare("SELECT COUNT(*) FROM `{$this->getHgVisitorTableName($uid)}` WHERE `uid` = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $count = $stmt->fetchColumn();
      $count = $count ? $count : 0;
      $this->getMemcache()->set($cacheKey, $count, 0, self::FM_CACHE_TIME);
    }
    return $count;
  }

  /**
   * @desc insert hg_visitor
   * @desc status 0-未查看 1-已查看
   * @param $field
   * @return bool
   */
  public function insertHgVisitor ($fields) {
    if (!$fields['uid'] || !$fields['visitor_uid']) {
      throw new Exception('uid or visitor_uid is null...');
    }
    
    $insertFields = array();
    $insertFields['uid'] = $fields['uid'];
    $insertFields['visitor_uid'] = $fields['visitor_uid'];
    $insertFields['status'] =  $fields['status'];
    $res = $this->insert($this->getHougongConnection(), $this->getHgVisitorTableName($fields['uid']), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findHgVisitorByUidAndViuid' . '_UID_' . $fields['uid']. '_VIUID_' . $fields['visitor_uid']);
      $this->getMemcache()->delete($cacheKey);
      $scopeKey = $this->getVisitorListScopeKey($fields['uid']);
      $this->getHougongMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
    return FALSE;
  }    

  /**
   * @desc update hg_visitor
   * @desc status 0-未查看,1-已查看
   * @param $field
   * @return bool
   */
  public function updateHgVisitorStatus($fields) {
    if (!$fields['uid'] || !$fields['status'] ) {
      throw new Exception('uid or status is null...');
    }
    $updateFields = array();
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($updateFields) {
      $sql = "UPDATE {$this->getHgVisitorTableName($fields['uid'])} {$this->getUpdateSect($updateFields)} WHERE uid = :uid";
      $stmt = $this->getHougongConnection()->prepare($sql);
      $stmt->bindValue(':uid', $fields['uid'], PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $scopeKey = $this->getVisitorListScopeKey($fields['uid']);
        $this->getHougongMemcacheNameSpace()->removeBatchKeys($scopeKey);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @desc update hg_visitor
   * @desc status 0-未查看 1-已查看
   * @param $field
   * @return bool
   */
  public function updateHgVisitor($fields) {

    if (!$fields['uid']) {
      throw new Exception('uid is null...');
    }
  
    $updateFields = array();
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if (!$fields['updated_time']) $updateFields['updated_time'] = time();
    if ($updateFields) {
      $sql = "UPDATE {$this->getHgVisitorTableName($fields['uid'])} {$this->getUpdateSect($updateFields)} WHERE uid = :uid AND visitor_uid = :viuid LIMIT 1";
      $stmt = $this->getHougongConnection()->prepare($sql);
      $stmt->bindValue(':uid', $fields['uid'], PDO::PARAM_INT);
      $stmt->bindValue(':viuid', $fields['visitor_uid'], PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $scopeKey = $this->getVisitorListScopeKey($fields['uid']);
        $this->getHougongMemcacheNameSpace()->removeBatchKeys($scopeKey);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @desc select from hg_notice
   * @param $uid
   * @return array
   */
  public function findHgNoticeListByUid ($uid, $offset = 0, $limit = 20) {
    if (!$uid || !$limit) {
      throw new Exception('uid or limit is null');
    }
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_UID_' . $uid . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getNoticeListScopeKey($uid);
    $rows = $this->getHougongMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getHougongConnection()->prepare("SELECT * FROM `{$this->getHgNoticeTableName($uid)}` WHERE `uid` = :uid ORDER BY created_time DESC LIMIT :offset, :limit");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getHougongMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @desc insert hg_notice
   * @param $field
   * @return bool
   */
  public function insertHgNotice ($fields) {
    if (!$fields['uid'] || !$fields['content']) {
      throw new Exception('uid or content is null...');
    }
  
    $insertFields = array();
    $insertFields['uid'] = $fields['uid'];
    $insertFields['content'] = $fields['content'];
    if (isset($fields['status'])) $insertFields['status'] = $fields['status'];
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getHougongConnection(), $this->getHgNoticeTableName($fields['uid']), $insertFields);
    if ($res) {
      $scopeKey = $this->getNoticeListScopeKey($fields['uid']);
      $this->getHougongMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
    return FALSE;
  }    
  
  /**
   * @desc update hg_notice
   * @desc status 0-未查看,1-已查看
   * @param $field
   * @return bool
   */
  public function updateHgNotice($fields) {
    if (!$fields['uid'] || !$fields['status'] ) {
      throw new Exception('uid or status is null...');
    }
    $updateFields = array();
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($updateFields) {
      $sql = "UPDATE {$this->getHgNoticeTableName($fields['uid'])} {$this->getUpdateSect($updateFields)} WHERE uid = :uid";
      $stmt = $this->getHougongConnection()->prepare($sql);
      $stmt->bindValue(':uid', $fields['uid'], PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $scopeKey = $this->getNoticeListScopeKey($fields['uid']);
        $this->getHougongMemcacheNameSpace()->removeBatchKeys($scopeKey);
        return TRUE;
      }
    }
    return FALSE;
  }
}
