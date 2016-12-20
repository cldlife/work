<?php
/**
 * @desc 管理后台 DAO
 */
class BkAdminDAO extends BaseDao {
  
  const CACHE_TIME = 300;
  
  //缓存key前缀
  const CACHE_PREFIX = 'BK_ADMIN_CACHE_';
  
  //缓存版本号
  const CACHE_VERSION = '1.0';
  
  //缓存 namespace
  const AUDIT_THREAD_SPACE_NAME = 'AUDIT_THREAD_SPACE_NAME';
  
  const WEBFRONT_SPACE_NAME = 'WEBFRONT_SPACE_NAME';
  
  //缓存 scope key前缀
  const FEEDBACK_LIST_SCOPE_REFIX = 'SCOPE_FEEDBACK_LIST_';
  const REPORT_LIST_SCOPE_REFIX = 'SCOPE_REPORT_LIST_';
  const BKATTACHMENT_LIST_SCOPE_REFIX = 'SCOPE_BKATTACHMENT_LIST_';

  private function getCacheKey ($id) {
    return self::CACHE_PREFIX . strtoupper($id) . '_' . self::CACHE_VERSION;
  }
  
  //get web front node memcache
  private function getBkWebfrontMemcacheNameSpace() {
    return $this->getMemcacheNameSpace(self::WEBFRONT_SPACE_NAME, self::CACHE_TIME);
  }
  
  private function getBkMemcacheNameSpace() {
    return $this->getBkAdminMemcacheNameSpace(self::AUDIT_THREAD_SPACE_NAME, self::CACHE_TIME);
  }
  
  private function getFeedbackListScopeKey() {
    return self::FEEDBACK_LIST_SCOPE_REFIX;
  }
  private function getReportListScopeKey() {
    return self::REPORT_LIST_SCOPE_REFIX;
  }
  private function getBkattachmentListScopeKey() {
    return self::BKATTACHMENT_LIST_SCOPE_REFIX;
  }

  private function getAttachmentTableName () {
    return 'bk_attachment';
  }

  /**
   * @desc bk_menus
   */
  public function findBkAdminMenus () {
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_VERSION_' . '2.6');
    $rows = $this->getBkAdminMemcache()->get($cacheKey);
    if (!$rows) {
      $stmt = $this->getBackendConnection()->prepare("SELECT * FROM bk_menus ORDER BY id ASC");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $this->getBkAdminMemcache()->set($cacheKey, $rows);
    }
    return $rows ? $rows : array();
  }
  
  /**
   * @desc insert feedback
   */
  public function insertFeedback($fields) {
    if (!$fields['uid'] || !$fields['content'] || !$fields['contact_info']) {
      throw new Exception('uid, content or contact_info is null...');
    }
    return $this->insert($this->getBackendConnection(), 'bk_feedback', $fields);
  }
  
  /**
   * @return feedback
   */
  public function findFeedbacks($status = -1, $offset = 0, $limit = 10) {
    if (!$limit) {
      throw new Exception('limit is null...');
    }
  
    $sqlWhere = $status >= 0 ? ' WHERE status = :status' : '';
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_STATUS_' . $status . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getFeedbackListScopeKey();
    $rows = $this->getBkMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getBackendConnection()->prepare("SELECT * FROM bk_feedback {$sqlWhere} ORDER BY id DESC LIMIT :offset, :limit");
      if ($status >= 0) $stmt->bindValue(':status', $status, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $cacheTime = self::CACHE_TIME;
      if (empty($rows)) {
        $rows = array();
        $cacheTime = self::NONE_CACHE_TIME;
      }
      $this->getBkMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, $cacheTime);
    }
    return $rows;
  }
  
  /**
   * @desc insert report
   */
  public function insertReport($fields) {
    if (!$fields['uid'] || !$fields['report_id'] || !$fields['relation_id']) {
      throw new Exception('uid, report_id or relation_id is null...');
    }
    return $this->insert($this->getBackendConnection(), 'bk_report', $fields);
  }
  
  /**
   * @return report
   */
  public function findReports($status = -1, $offset = 0, $limit = 10) {
    if (!$limit) {
      throw new Exception('limit is null...');
    }
  
    $sqlWhere = $status >= 0 ? ' WHERE status = :status' : '';
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_STATUS_' . $status . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getReportListScopeKey();
    $rows = $this->getBkMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getBackendConnection()->prepare("SELECT * FROM bk_report {$sqlWhere} ORDER BY id DESC LIMIT :offset, :limit");
      if ($status >= 0) $stmt->bindValue(':status', $status, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $cacheTime = self::CACHE_TIME;
      if (empty($rows)) {
        $rows = array();
        $cacheTime = self::NONE_CACHE_TIME;
      }
      $this->getBkMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, $cacheTime);
    }
    return $rows;
  }

  /**
   * @return bk_attachment
   */
  public function findBkAttachments($status = 0, $offset = 0, $limit = 20) {
    if (!$limit) {
      throw new Exception('limit is null...');
    }
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_STATUS_' . $status . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getBkattachmentListScopeKey();
    $rows = $this->getBkMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getBackendConnection()->prepare("SELECT * FROM {$this->getAttachmentTableName($attachHashId)} WHERE status = :status ORDER BY `created_time` DESC LIMIT :offset, :limit");
      $stmt->bindValue(':status', $status, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getBkMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows ? $rows : array();
  }
  
  /**
   * @desc insert bk_attachment
   */
  public function insertBkAttachment(Array $fields) {
    if (!$fields['aid'] || !$fields['type'] || !$fields['file_uri'] || !$fields['file_name'] || !$fields['width'] || !$fields['height']) {
      throw new Exception('fields is null...');
    }
    $attachments = array();
    $attachments['aid'] = $fields['aid'];
    $attachments['type'] = $fields['type'];
    $attachments['local_name'] = $fields['local_name'];
    $attachments['file_uri'] = $fields['file_uri'];
    $attachments['file_name'] = $fields['file_name'];
    $attachments["width"] = $fields['width'];
    $attachments["height"] = $fields['height'];
    if ($this->insert($this->getBackendConnection(), $this->getAttachmentTableName(), $attachments)) {
      $scopeKey = $this->getBkattachmentListScopeKey();
      $this->getBkMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return $attachments;
    }
    
    return array();
  }

}
