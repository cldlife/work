<?php
/**
 * @desc TaskDAO
 */
class TaskDAO extends BaseDAO {

  //cache key prefix
  const CACHE_PREFIX = 'TASK_CACHE_';

  //cache version
  const CACHE_VERSION = '1.0';

  //cache namespace
  const TASK_SPACE_NAME = 'TASK_SPACE_NAME';

  //task scope keys
  const TASK_SCOPE_PREFIX = 'SCOPE_TASK_';

  private function getCacheKey ($id) {
    return self::CACHE_PREFIX . strtoupper($id) . '_' . self::CACHE_VERSION;
  }

  private function getTaskMemcacheNameSpace () {
    return $this->getMemcacheNameSpace(self::TASK_SPACE_NAME, self::CACHE_TIME);
  }

  //get task scope keys
  private function getTaskScopeKey () {
    return self::TASK_SCOPE_PREFIX;
  }

  //get task table names
  private function getTaskTableName () {
    return 'task';
  }

  /**
   * @desc select tasks where status order run_time
   * @param int $offset
   * @param int $limit
   * @return array
   */
  public function findTaskList ($offset = 0, $limit = 20) {
    if (!$limit) {
      throw new Exception('limit is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_OFFSET_{$offset}_LIMIT_{$limit}");
    $scopeKey = $this->getTaskScopeKey();
    $rows = $this->getTaskMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $status = 0;
      $stmt = $this->getTaskConnection()->prepare("SELECT * FROM `{$this->getTaskTableName()}` WHERE `status` = :status ORDER BY `run_time` ASC LIMIT :offset, :limit");
      $stmt->bindValue(':status', $status, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $rows = $rows ? $rows : array();
      $this->getTaskMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @desc select task where id
   * @param int $id
   * @return array
   */
  public function findTaskWithId ($id) {
    if (!$id) {
      throw new Exception('id is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_ID_{$id}");
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getTaskConnection()->prepare("SELECT * FROM `{$this-> getTaskTableName()}` WHERE `id` = :id LIMIT 1");
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $row = $row ? $row : array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc insert into task
   * @param array $fields
   * @return bool
   */
  public function insertTask ($fields) {
    if (!$fields || !$fields['type'] || !$fields['run_time'] || !$fields['workload']) {
      throw new Exception('type, run_time or workload is null');
    }

    $insertFields = array(
      'type' => $fields['type'],
      'run_time' => $fields['run_time'],
      'workload' => $fields['workload'],
    );
    if (isset($fields['status'])) $insertFields['status'] = $fields['status'];
    $id = $this->insert($this->getTaskConnection(), $this->getTaskTableName(), $insertFields, TRUE);
    if ($id) {
      $cacheKey = $this->getCacheKey("findTaskWithId_ID_{$id}");
      $this->getMemcache()->delete($cacheKey);
      $scopeKey = $this->getTaskScopeKey();
      $this->getTaskMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc update task where id
   * @param int $id
   * @param array $fields
   * @return bool
   */
  public function updateTaskWithId ($id, $fields) {
    if (!$id || !$fields) {
      throw new Exception('id or fields is null');
    }

    $updateFields = array();
    if ($fields['type']) $updateFields['type'] = $fields['type'];
    if ($fields['run_time']) $updateFields['run_time'] = $fields['run_time'];
    if ($fields['workload']) $updateFields['workload'] = $fields['workload'];
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($updateFields) {
      $updateFields['updated_time'] = time();
      $stmt = $this->getTaskConnection()->prepare("UPDATE `{$this->getTaskTableName()}` {$this->getUpdateSect($updateFields)} WHERE `id` = :id LIMIT 1");
      $this->bindValues($stmt, $updateFields);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount()) {
        $cacheKey = $this->getCacheKey("findTaskWithId_ID_{$id}");
        $this->getMemcache()->delete($cacheKey);
        $scopeKey = $this->getTaskScopeKey();
        $this->getTaskMemcacheNameSpace()->removeBatchKeys($scopeKey);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @desc delete from task where id
   * @param int $id
   * @return bool
   */
  public function deleteTaskWithId ($id, $fields) {
    if (!$id || !$fields) {
      throw new Exception('id or fields is null');
    }

    $stmt = $this->getTaskConnection()->prepare("DELETE FROM `{$this->getTaskTableName()}` WHERE `id` = :id LIMIT 1");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()) {
      $cacheKey = $this->getCacheKey("findTaskWithId_ID_{$id}");
      $this->getMemcache()->delete($cacheKey);
      $scopeKey = $this->getTaskScopeKey();
      $this->getTaskMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
    return FALSE;
  }
}
