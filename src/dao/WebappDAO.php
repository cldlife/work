<?php
/**
 * @desc WebappDAO
 */
class WebappDAO extends BaseDAO {
 
  //cache key prefix
  const CACHE_PREFIX = 'WEBAPP_CACHE_';
 
  //cache version
  const CACHE_VERSION = '1.0';


  //cache namespace
  const WEBAPP_SPACE_NAME = 'WEBAPP_SPACE_NAME';
  
  //game scope keys
  const PICTURES_PAINTS = 'SCOPE_PICTURES_PAINTS_';
  const PICTURES_PAINTS_STATUS = 'SCOPE_PICTURES_PAINTS_STATUS_';

  const TYPE_PICTURE = 0;
  const TYPE_DRWA = 1;

  const ERROR_CODE = 501;

  private function getCacheKey($id) {
    return self::CACHE_PREFIX . strtoupper($id) . '_' . self::CACHE_VERSION;
  }
  
  private function getWebappMemcacheNameSpace() {
    return $this->getMemcacheNameSpace(self::WEBAPP_SPACE_NAME, self::CACHE_TIME);
  }
  
  //get game scope keys
  private function getPicturesPaintsByPPidScopeKey ($ppid) {
    return self::PICTURES_PAINTS.$ppid;
  }

  private function getPicturesPaintsByTypeScopeKey ($type,$rid) {
    return self::PICTURES_PAINTS.$type."_".intval($rid);
  }

  private function getPicturesPaintsStatusScopeKey ($type) {
    return self::PICTURES_PAINTS_STATUS.$type;
  }
  
  private function getSevenHashTableName () {
    return 'wp_seven_deadly_sin';
  }

  private function getPicturesPaints () {
    return 'wp_paints';
  }

  private function getPicturePaintsStatus () {
    return 'wp_paints_status';
  }

  /**
   * @desc find 七宗罪游戏
   */
  public function findSeven ($nickname) {
    if (!$nickname) {
      throw new Exception('nickname is null...');
    }
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_NICKNAME_' . $nickname);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getWebappConnection()->prepare("SELECT * FROM {$this->getSevenHashTableName()} WHERE name = :name LIMIT 1");
      $stmt->bindValue(':name', $nickname, PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }

   /**
   * @desc insert 七宗罪游戏
   */
  public function insertSeven ($fields) {
    if (!$fields['name'] || !$fields['content']) {
      throw new Exception('name, content uid is null...');
    }
    $insertFields = array();
    $insertFields['name'] = $fields['name']; 
    $insertFields['content'] = $fields['content'];
    $insertFields['created_time'] = 'NONE';
    $insertFields['updated_time'] = 'NONE';
    $lastInsertId = $this->insert($this->getWebappConnection(), $this->getSevenHashTableName(), $insertFields, TRUE);
    if ($lastInsertId) {
      $cacheKey = $this->getCacheKey('findSeven' . '_NICKNAME_' . $fields['name']);
      $this->getMemcache()->delete($cacheKey);
      $insertFields['id'] = $lastInsertId;
      return $insertFields;
    }
    return array();
  }

  /**
   * 添加自拍
   *
   * @param $file
   * @param $uid
   * @return array
   * @throws Exception
   */
  public function insertPicture($file,$uid){
    return $this->insertImg($file,$uid,self::TYPE_PICTURE);
  }

  /**
   * 添加画作
   *
   * @param $file
   * @param $uid
   * @param $relation_ppid
   * @return array
   * @throws Exception
   */
  public function insertDraw($file,$uid,$relation_ppid){
    return $this->insertImg($file,$uid,self::TYPE_DRWA,$relation_ppid);
  }

  /**
   * 添加图片
   *
   * @param $file
   * @param $uid
   * @param $type
   * @param int $relation_ppid
   * @return array
   * @throws Exception
   */
  public function insertImg($file,$uid,$type,$relation_ppid = 0){
    if(!$file || !isset($type) || !$uid){
      throw new Exception("[".__LINE__."]系统错误,请联系管理员",self::ERROR_CODE);
    }
    $now = time();
    $insertData = array();
    $insertData['uid'] = $uid;
    $insertData['file_uri'] = $file;
    $insertData['relation_tid'] = 0;
    $insertData['status'] = 0;
    $insertData['type'] = $type;
    $insertData['relation_ppid'] = $relation_ppid;
    $insertData['created_time'] = $now;
    $insertData['updated_time'] = $now;
    $id = $this->insert($this->getWebappConnection(), $this->getPicturesPaints(), $insertData, TRUE);
    if ($id) {
      $insertData['pp_id'] = $id;
      $cacheKey = $this->getCacheKey('findPicturesPaintsStatusByPPid' . '_PPID_' .  $id);
      $this->getMemcache()->delete($cacheKey);
      $scopeKey = $this->getPicturesPaintsByTypeScopeKey($type,$relation_ppid);
      $this->getWebappMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return $insertData;
    }else{
      throw new Exception("[".__LINE__."]系统错误,请联系管理员",self::ERROR_CODE);
    }
  }

  /**
   * 更新图片字段
   *
   * @param $pid
   * @param array $fields
   * @return array
   * @throws Exception
   */
  public function updatePictureByPid($pid,array $fields){
    if (!$pid) {
      throw new Exception("[".__LINE__."]系统错误,请联系管理员",self::ERROR_CODE);
    }

    $resFields = array();
    $updateFields = array();
    if ($fields['votes']) $updateFields['votes'] = trim($fields['votes']);
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($fields['tid']) $updateFields['relation_tid'] = $fields['tid'];
    if ($updateFields) {
      $stmt = $this->getWebappConnection()->prepare("UPDATE {$this->getPicturesPaints()} {$this->getUpdateSect($updateFields)} WHERE pp_id = :pp_id LIMIT 1");
      $stmt->bindValue(':pp_id', $pid, PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
//        $cacheKey = $this->getCacheKey('findPaintsByType' . '_TYPE_' .  $type);
//        $this->getMemcache()->delete($cacheKey);
        $paint = $this->findPicturesPaintsByppid($pid);
        $cacheKey = $this->getCacheKey('findPicturesPaintsStatusByPPid' . '_PPID_' .  $pid);
        $this->getMemcache()->delete($cacheKey);
        $scopeKey = $this->getPicturesPaintsByTypeScopeKey($paint['type'],$pid);
        $this->getWebappMemcacheNameSpace()->removeBatchKeys($scopeKey);
        $resFields = $updateFields;
      }
    }
    return $resFields;
  }

  /**
   * 添加图片汇总信息
   *
   * @param $pid
   * @param $type
   * @param $create_time
   * @return bool
   * @throws Exception
   */
  public function insertPictureStatus($pid,$type,$create_time){
    if(!$pid){
      throw new Exception("[".__LINE__."]系统错误,请联系管理员",self::ERROR_CODE);
    }
    $insertData = array();
    $insertData['pp_id'] = $pid;
    $insertData['paintings'] = 0;
    $insertData['votes'] = 0;
    $insertData['type'] = $type;
    $insertData['created_time'] = $create_time;
    $insertData['updated_time'] = 'NONE';
    $id = $this->insert($this->getWebappConnection(), $this->getPicturePaintsStatus(), $insertData, TRUE);
    if ($id) {
      $cacheKey = $this->getCacheKey('findPicturesPaintsStatusByPPid' . '_PPID_' . $id);
      $this->getMemcache()->delete($cacheKey);
      $scopeKey = $this->getPicturesPaintsStatusScopeKey($type);
      $this->getWebappMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }else{
      throw new Exception("[".__LINE__."]系统错误,请联系管理员",self::ERROR_CODE);
    }
  }

  /**
   * 根据图片ID进行汇总信息自增/减
   *
   * @param $pid
   * @param $type 类型
   * @param array $fields
   * @return bool
   * @throws Exception
   */
  public function inDecreasePaintsByPid($pid,$type,Array $fields){
    if(!$pid){
      throw new Exception("[".__LINE__."]系统错误,请联系管理员",self::ERROR_CODE);
    }
    $allowedFields = array('paintings', 'votes');
    $updateSect = $this->getInDecreaseUpdateSect($fields, $allowedFields);
    if ($updateSect) {
      $stmt = $this->getWebappConnection()->prepare("UPDATE {$this->getPicturePaintsStatus()} {$updateSect} WHERE pp_id = :pp_id LIMIT 1");
      $stmt->bindValue(':pp_id', $pid, PDO::PARAM_INT);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $cacheKey = $this->getCacheKey('findPicturesPaintsStatusByPPid' . '_PPID_' . $pid);
        $scopeKey = $this->getPicturesPaintsStatusScopeKey($type);
        $this->getWebappMemcacheNameSpace()->removeBatchKeys($scopeKey);
        $this->getMemcache()->delete($cacheKey);
        return TRUE;
      }
    }
  }
   
  /**
   * @desc select wp_paints
   * @desc Chu
   * @param $type
   * @return array
   */
  public function findPaintsByType ($type, $offset = 0, $limit = 30, $rppid = 0) {
    if (!isset($type) && !$offset && !$limit) {
      throw new Exception('type, offset, limt is null...');
    }
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_TYPE_' . $type . '_OFFSET_' . $offset . '_LIMIT_' . $limit. "_RID_".$rppid);
    if ($rppid) {
      $sql = "And `relation_ppid` = {$rppid}";
    }
    $scopeKey = $this->getPicturesPaintsByTypeScopeKey($type, $rppid);
    $rows = $this->getWebappMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getWebappConnection()->prepare("SELECT * FROM `{$this->getPicturesPaints()}` WHERE `type` = :type {$sql} ORDER BY `created_time` DESC LIMIT :offset, :limit");
      $stmt->bindValue(':type', $type, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT); 
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getWebappMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows;
  }
  
  /**
   * @desc selelct wp_paints
   * @desc Chu
   */
  public function findPicturesPaintsByppid ($ppid) {
    if (!$ppid) {
       throw new Exception('ppid is null');
    }

    $cachekey = $this->getCacheKey(__FUNCTION__ . '_PPID_' . $ppid);
    $row = $this->getMemcache()->get($cachekey);
    if ($row === FALSE) {
      $stmt = $this->getWebappConnection()->prepare("SELECT * FROM {$this->getPicturesPaints()} WHERE pp_id = :ppid LIMIT 1");
      $stmt->bindValue(':ppid', $ppid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cachekey, $row, 0, self::CACHE_TIME);
    }
    return $row;

  }
  
  /**
   * @desc select  wp_paints_status 
   * @desc Chu
   * @param $ppid 
   * @return array
   */
  public function findPicturesPaintsStatusByPPid ($ppid) {
    if (!$ppid) {
       throw new Exception('ppid is null');
    }
    $cachekey = $this->getCacheKey(__FUNCTION__ . '_PPID_' . $ppid);
    $row = $this->getMemcache()->get($cachekey);
    if ($row === FALSE) {
      $stmt = $this->getWebappConnection()->prepare("SELECT * FROM {$this->getPicturePaintsStatus()} WHERE pp_id = :ppid LIMIT 1");
      $stmt->bindValue(':ppid', $ppid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getMemcache()->set($cachekey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }


  /**
   * @desc select wp_paints_status
   * @desc Chu
   * @param $type
   * @return array
   */
  public function findPicturesPaintingStatusByType ($type, $status = '', $offset = 0, $limit = 30) {
    if (!isset($type) && !$offset && !$limit) {
      throw new Exception('type, offset, limt is null...');
    }
    if ($status == 'is_hot') {
      $sort = 'votes';
    } elseif ($status == 'is_new') {
      $sort = 'created_time';
    } else {
      $sort = 'created_time';
    }
    
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_TYPE_' . $type . '_STATUS_'  . $status .  '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getPicturesPaintsStatusScopeKey($type);
    $rows = $this->getWebappMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getWebappConnection()->prepare("SELECT * FROM `{$this->getPicturePaintsStatus()}` WHERE `type` = :type ORDER BY {$sort} DESC LIMIT :offset, :limit");
      $stmt->bindValue(':type', $type, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT); 
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getWebappMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows;
  }

}