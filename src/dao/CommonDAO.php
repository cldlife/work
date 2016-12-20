<?php
/**
 * @desc Common DAO
 */
class CommonDAO extends BaseDao {
  
  //缓存key前缀
  const CACHE_PREFIX = 'COMMON_CACHE_';

  //缓存版本号
  const CACHE_VERSION = '1.0';
  
  private function getCacheKey($id) {
    return self::CACHE_PREFIX . strtoupper($id) . '_' . self::CACHE_VERSION;
  }
  
  /**
   * @return provinces, cities or districts by parent_id
   */
  public function findRegionsWithParentId($parentId = 0) {
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $parentId);
    $rows = $this->getMemcache()->get($cacheKey);
    if (!$rows) {
      $stmt = $this->getConfigConnection()->prepare("SELECT * FROM cf_region WHERE parent_id = :parentId ORDER BY order_id");
      $stmt->bindValue(':parentId', $parentId, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if ($rows) $this->getMemcache()->set($cacheKey, $rows);
    }
    return $rows ? $rows : array();
  }
  
  /**
   * @return region
   */
  public function findRegionInfoWithId($id) {
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $id);
    $row = $this->getMemcache()->get($cacheKey);
    if (!$row) {
      $stmt = $this->getConfigConnection()->prepare("SELECT * FROM cf_region WHERE id = :id LIMIT 1");
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) $this->getMemcache()->set($cacheKey, $row);
    }
    return $row ? $row : array();
  }
  
  /**
   * @desc 根据区域(省,城市,地区)name获取region
   */
  public function findRegionInfoWithName($name, $category = 'city') {
    if (!$name) {
      return array();
    }
    
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $name .  '_' . $category);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $whereSQL = '';
      if ($category && in_array($category, array('province', 'city', 'district'))) $whereSQL = " AND category = '{$category}'";
      $sql = "SELECT * FROM `cf_region` WHERE `name` = :name {$whereSQL} LIMIT 1";
      try {
        $stmt = $this->getConfigConnection()->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) $row = array();
        $this->getMemcache()->set($cacheKey, $row);
      } catch (PDOException $e) {
        Utils::log(__METHOD__ . ": " . $sql . " error: " . $e->getMessage(), 'DB');
      }
    }
    
    return $row ? $row : array();
  }
  
  /**
   * @desc insert device_ios
   */
  public function insertDeviceIOS($fields) {
    if (!$fields['device_token']) {
      throw new Exception('device_token is null...');
    }
  
    if (isset($fields['receive_msg'])) $fields['receive_msg'] = intval($fields['receive_msg']);
    return $this->insert($this->getUcenterAccountConnection(), 'device_ios', $fields);
  }
  
}