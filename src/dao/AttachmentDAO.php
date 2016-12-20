<?php
/**
 * @desc Attachment DAO
 */
class AttachmentDAO extends BaseDao {
  
  //缓存key前缀
  const CACHE_PREFIX = 'ATTACHMENT_CACHE_';

  //缓存版本号
  const CACHE_VERSION = '1.0';
  
  private function getCacheKey($id) {
    return self::CACHE_PREFIX . strtoupper($id) . '_' . self::CACHE_VERSION;
  }
  
  private function getAttachmentTableName ($attachHashId) {
    return $this->getHashTableName($attachHashId, 'thing_attachment', self::LARGE_HASH_TABLE_NUM);
  }
  
  /**
   * @return thing_attachment
   */
  public function findAttachmentsBytid($tid, $attachHashId, $limit = 6) {
    if (!$tid || !$attachHashId || !$limit) {
      throw new Exception('tid or attachHashId is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $tid);
    $rows = $this->getMemcache()->get($cacheKey);
    if ($rows === FALSE) {
      $stmt = $this->getThingConnection()->prepare("SELECT * FROM {$this->getAttachmentTableName($attachHashId)} WHERE tid = :tid AND status = 0 ORDER BY order_id ASC LIMIT {$limit}");
      $stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getMemcache()->set($cacheKey, $rows, 0, self::CACHE_TIME);
    }
    return $rows ? $rows : array();
  }
  
  /**
   * @desc insert thing_attachment
   */
  public function insertAttachment($attachHashId, Array $fields) {
    if (!$attachHashId || !$fields['aid'] || !$fields['file_uri'] || !$fields['file_name'] || !$fields['width'] || !$fields['height'] || !$fields['order_id']) {
      throw new Exception('tid, attachHashId or fields is null...');
    }
  
    $attachments = array();
    if ($fields['tid']) $attachments['tid'] = $fields['tid'];
    $attachments['aid'] = $fields['aid'];
    $attachments['attach_hashid'] = $attachHashId;
    if (isset($fields['type'])) $attachments['type'] = $fields['type'];
    $attachments['file_uri'] = $fields['file_uri'];
    $attachments['file_name'] = $fields['file_name'];
    $attachments["width"] = $fields['width'];
    $attachments["height"] = $fields['height'];
    $attachments["order_id"] = $fields['order_id'];
    if ($this->insert($this->getThingConnection(), $this->getAttachmentTableName($attachHashId), $attachments)) {
      return $attachments;
    }
    
    return array();
  }
  
  /**
   * @desc update thing_attachment (with aids)
   */
  public function updateAttachmentsByAids($aids, $attachHashId, Array $fields) {
    if ($aids) {
      $aidsImp = implode(',', $aids);
      if ($aidsImp == 'Array') return FALSE;
        
      $updatedFields = array();
      if (isset($fields['tid'])) $updatedFields['tid'] = $fields['tid'];
      if (isset($fields['status'])) $updatedFields['status'] = $fields['status'];
      if ($updatedFields && $aidsImp) {
        $stmt = $this->getThingConnection()->prepare("UPDATE {$this->getAttachmentTableName($attachHashId)} {$this->getUpdateSect($updatedFields)} WHERE aid IN ({$aidsImp})");
        $this->bindValues($stmt, $updatedFields);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount) {
          $cacheKey = $this->getCacheKey('findAttachmentsBytid_' . $fields['tid']);
          $this->getMemcache()->delete($cacheKey);
          return TRUE;
        }
      }
    }
  
    return FALSE;
  }
  
  /**
   * @desc update thing_attachment (with aid)
   */
  public function updateAttachmentByAid($aid, $attachHashId, Array $fields) {
    if ($aid) {
      $updatedFields = array();
      if (isset($fields['tid'])) $updatedFields['tid'] = $fields['tid'];
      if (isset($fields['order_id'])) $updatedFields['order_id'] = $fields['order_id'];
      if ($updatedFields) {
        $stmt = $this->getThingConnection()->prepare("UPDATE {$this->getAttachmentTableName($attachHashId)} {$this->getUpdateSect($updatedFields)} WHERE aid = :aid LIMIT 1");
        $stmt->bindValue(':aid', $aid, PDO::PARAM_INT);
        $this->bindValues($stmt, $updatedFields);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount) {
          $cacheKey = $this->getCacheKey('findAttachmentsBytid_' . $fields['tid']);
          $this->getMemcache()->delete($cacheKey);
          return TRUE;
        }
      }
    }
  
    return FALSE;
  }
  
  /**
   * @desc update thing_attachment (with tid)
   */
  public function updateAttachmentsByTid($tid, $attachHashId, Array $fields) {
    if ($tid && $attachHashId ) {
      $updatedFields = array();
      if (isset($fields['status'])) $updatedFields['status'] = $fields['status'];
      if ($updatedFields) {
        $stmt = $this->getThingConnection()->prepare("UPDATE {$this->getAttachmentTableName($attachHashId)} {$this->getUpdateSect($updatedFields)} WHERE tid = :tid");
        $stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
        $this->bindValues($stmt, $updatedFields);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount) {
          $cacheKey = $this->getCacheKey('findAttachmentsBytid_' . $tid);
          $this->getMemcache()->delete($cacheKey);
          return TRUE;
        }
      }
    }
  
    return FALSE;
  }
}