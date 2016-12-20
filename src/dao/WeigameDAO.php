<?php
/**
 * @desc WeigameDao
 */
class WeigameDao extends BaseDao {
	
  //缓存key前缀
  const CACHE_PREFIX = 'WEIGAME_CACHE_';
  
  //缓存版本号
  const CACHE_VERSION = '1.0';
  
  //缓存 namespace
  const WEIGAME_SPACE_NAME = 'WEIGAME_SPACE_NAME';
  
  const WEIGAME_MPINFO_PREFIX = 'SCOPE_WEIGAME_MPINFO_';
  
  const WEIGAME_DOMAIN_SCOPE_PREFIX = 'SCOPE_WEIGAME_DOMAIN_PREFIX_';
  
  const WEIGAME_GROUP_SCOPE_PREFIX = 'SCOPE_WEIGAME_GROUP_PREFIX_';
  
  const KNOW_SCOPE_PREFIX = 'SCOPE_KNOW_FIELD';
  
  private function getCacheKey ($id) {
    return self::CACHE_PREFIX . strtoupper($id) . '_' . self::CACHE_VERSION;
  }
  
  private function getWgMemcacheNameSpace() {
    return $this->getWeigameMemcacheNameSpace(self::WEIGAME_SPACE_NAME, self::CACHE_TIME);
  }
  
  private function getWeigameMpinfoScopeKey () {
  	return self::WEIGAME_MPINFO_PREFIX;
  }
  private function getWeigameDomainScopeKey () {
  	return self::WEIGAME_DOMAIN_SCOPE_PREFIX;
  }
  private function getWeigameGroupScopeKey () {
  	return self::WEIGAME_GROUP_SCOPE_PREFIX;
  }
  private function getknowScopeKey () {
    return self::KNOW_SCOPE_PREFIX;
  }

  private function getWeigameDomainTableName () {
    return 'wg_domain';
  }
  private function getWeigameGroupsTableName () {
    return 'wg_groups';
  }
  private function getWeigameKnowTableName () {
    return 'wg_know';
  }
  private function getWeigameMpinfoTableName () {
    return 'wg_mp_info';
  }
  private function getWeigameMpDomainTableName () {
    return 'wg_mp_domain';
  }
  private function getKnowQuestionHashTableName ($qid) {
    return $this->getHashTableName($qid, 'wg_know_questions', self::LARGE_HASH_TABLE_NUM);
  }
  private function getKnowAnswerHashTableName ($qid) {
    return $this->getHashTableName($qid, 'wg_know_answers', self::LARGE_HASH_TABLE_NUM);
  }

 /**
  * @return wg_mp_info
  */
  public function findWeigameMpinfo ($offset = 0, $limit = 20) {
    if (!$limit) {
      throw new Exception('limit is null');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getWeigameMpinfoScopeKey();
    $rows = $this->getWgMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getWeigameMpinfoTableName()} LIMIT :offset, :limit");
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getWgMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::ONE_DAY_CACHE_TIME);
    }
    return $rows;
  }
  
  /**
   * @return wg_mp_info
   */
  public function findWeigameMpinfos () {
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_MPINFO_' . '1.0');
    $rows = $this->getMemcache()->get($cacheKey);
    if ($rows === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getWeigameMpinfoTableName()}");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getMemcache()->set($cacheKey, $rows);
    }
    return $rows;
  }

  /**
   * @return wg_mp_info (with mp_id)
   */
  public function findWeigameMpinfoWithMpid ($mpid) {
    if (!$mpid) {
      throw new Exception('mp_id is null');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_MPID_' . $mpid);
    $row = $this->getWeigameMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getWeigameMpinfoTableName()} WHERE mp_id = :mp_id LIMIT 1");
      $stmt->bindValue(':mp_id', $mpid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getWeigameMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }
  
  /**
   * @desc insert wg_mp_info
   * @param array $fields
   */
  public function insertWeigameMpinfoWithMpid ($fields) {
    if (!$fields['mp_name'] || !$fields['app_id'] || !$fields['app_secret']) {
      throw new Exception('mp_name app_id app_secret is null');
    }
  
    $insertFields = array();
    $insertFields['mp_name'] = $fields['mp_name'];
    $insertFields['app_id'] = $fields['app_id'];
    $insertFields['app_secret'] = $fields['app_secret'];
    if ($fields['type']) $insertFields['type'] = $fields['type'];
    $res = $this->insert($this->getWeigameConnection(), $this->getWeigameMpinfoTableName(), $insertFields);
    if ($res) {
      $scopeKey = $this->getWeigameMpinfoScopeKey();
      $this->getWgMemcacheNameSpace()->removeBatchKeys($scopeKey);
      $cacheKey = $this->getCacheKey('findWeigameMpinfos' . '_MPINFO_' . '1.0');
      $this->getWeigameMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @return wg_mp_info (with mp_id)
   */
  public function findWeigameMpDomainsWithMpid ($mpid, $type) {
    if (!$mpid) {
      throw new Exception('mp_id is null');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_MPID_' . $mpid . '_TYPE_' . $type);
    $rows = $this->getWeigameMemcache()->get($cacheKey);
    if ($rows === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getWeigameMpDomainTableName()} WHERE mp_id = :mp_id AND type = :type LIMIT 3");
      $stmt->bindValue(':mp_id', $mpid, PDO::PARAM_INT);
      $stmt->bindValue(':type', $type, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getWeigameMemcache()->set($cacheKey, $rows, 0, self::CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @return wg_mp_info (with domain_address)
   */
  public function findWeigameMpDomainWithAddress ($domainAddress) {
    if (!$domainAddress) {
      throw new Exception('domain_address is null');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_DOMAIN_ADDRESS_' . $domainAddress);
    $row = $this->getWeigameMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getWeigameMpDomainTableName()} WHERE domain_address = :domain_address LIMIT 1");
      $stmt->bindValue(':domain_address', $domainAddress, PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getWeigameMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc insert wg_mp_info
   * @param array $fields
   */
  public function insertWeigameMpDomain ($fields) {
    if (!$fields['mp_id'] || !$fields['domain_address']) {
      throw new Exception('mp_id or domain_address is null');
    }
    $insertFields = array();
    $insertFields['mp_id'] = $fields['mp_id'];
    $insertFields['domain_address'] = $fields['domain_address'];
    $insertFields['type'] = $fields['type'];
    $res = $this->insert($this->getWeigameConnection(), $this->getWeigameMpDomainTableName(), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findWeigameMpDomainsWithMpid' . '_MPID_' . $fields['mp_id'] . '_TYPE_' . $fields['type']);
      $this->getWeigameMemcache()->delete($cacheKey);
      $cacheKey = $this->getCacheKey('findWeigameMpDomainWithAddress' . '_DOMAIN_ADDRESS_' . $fields['domain_address']);
      $this->getWeigameMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc delete wg_mp_info
   * @param int $id
   * @return string $address
   */
  public function deleteWeigameMpDomain ($id, $address, $mpId, $type) {
    if (!$id || !$address) {
      throw new Exception('id or domain_address is null');
    }
    $stmt = $this->getWeigameConnection()->prepare("DELETE FROM {$this->getWeigameMpDomainTableName()} WHERE `id` = :id LIMIT 1 ");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()) {
      $cacheKey = $this->getCacheKey('findWeigameMpDomainsWithMpid' . '_MPID_' . $mpId . '_TYPE_' . $type);
      $this->getWeigameMemcache()->delete($cacheKey);
      $cacheKey = $this->getCacheKey('findWeigameMpDomainWithAddress' . '_DOMAIN_ADDRESS_' . $address);
      $this->getWeigameMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc select wg_domain (for bkman)
   */
  public function findDomains ($fields, $offset = 0, $limit = 20) {
    $queryFields = array();
    $queryFields['status'] = $fields['status'] ? $fields['status'] : 0;

    $sql = '';
    $cacheKeyId = __FUNCTION__ . '_STATUS_' . $queryFields['status'];
    if (isset($fields['level'])) {
      $queryFields['level'] = $fields['level'];
      $sql .= ' AND level = :level ';
      $cacheKeyId .= '_LEVEL_' . $fields['level'];
    }
    if (isset($fields['category'])) {
      $queryFields['category'] = $fields['category'];
      $sql .= ' AND category = :category ';
      $cacheKeyId .= '_CATEGORY_' . $fields['category'];
    }

    $cacheKey = $this->getCacheKey($cacheKeyId . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getWeigameDomainScopeKey();
    $rows = $this->getWgMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getWeigameDomainTableName()} WHERE status = :status {$sql} ORDER BY `updated_time` DESC LIMIT :offset, :limit");
      $stmt->bindValue(':status', $queryFields['status'], PDO::PARAM_INT);
      if (isset($queryFields['level'])) $stmt->bindValue(':level', $queryFields['level'], PDO::PARAM_INT);
      if (isset($queryFields['category'])) $stmt->bindValue(':category', $queryFields['category'], PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getWgMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::ONE_HOUR_CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @desc select wg_domain (with status = 0)
   * @param int level
   * @param int $category
   */
  public function findOnlineDomains ($level = -1, $category = 0) {
    $whereSQL = $withLevelCacheKey = '';
    if ($level >= 0) {
      $whereSQL .= ' AND level = :level ';
      $withLevelCacheKey .= '_LEVEL_' . $level;
    }
    if ($category) {
      $whereSQL .= ' AND category = :category ';
      $withLevelCacheKey .= '_CATEGORY_' . $category;
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . $withLevelCacheKey);
    $rows = $this->getWeigameMemcache()->get($cacheKey);
    if ($rows === FALSE) {
      $status = 0;
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getWeigameDomainTableName()} WHERE status = :status {$whereSQL} ORDER BY `updated_time` DESC LIMIT 30");
      $stmt->bindValue(':status', $status, PDO::PARAM_INT);
      if ($level >= 0) $stmt->bindValue(':level', $level, PDO::PARAM_INT);
      if ($category) $stmt->bindValue(':category', $category, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getWeigameMemcache()->set($cacheKey, $rows, 0, self::ONE_HOUR_CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @desc select wg_domain
   */
  public function findDomainWithAddress ($address) {
    if (!$address) {
      throw new Exception('address is null');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_ADDRESS_' . $address);
    $row = $this->getWeigameMemcache()->get($cacheKey);
    if ($row === FALSE) {       
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getWeigameDomainTableName()} WHERE address = :address LIMIT 1");
      $stmt->bindValue(':address', $address, PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getWeigameMemcache()->set($cacheKey, $row, 0, self::ONE_HOUR_CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc insert wg_domain
   */
  public function insertDomain ($fields) {
    if (!$fields['address']) {
      throw new Exception('domain address is null');
    }

    $insertFields = array();
    $insertFields['address'] = $fields['address'];
    $insertFields['status'] = $fields['status'] ? $fields['status'] : 0;
    $insertFields['level'] = $fields['level'] ? $fields['level'] : 0;
    $insertFields['remarks'] = $fields['remarks'] ? $fields['remarks'] : '';
    if ($fields['category']) $insertFields['category'] = $fields['category'];
    if ($fields['expiring_date']) $insertFields['expiring_date'] = $fields['expiring_date'];
    $insertFields['created_time'] = $fields['created_time'] ? $fields['created_time'] : time();
    $insertFields['updated_time'] = $fields['updated_time'] ? $fields['updated_time'] : time();
    $res = $this->insert($this->getWeigameConnection(), $this->getWeigameDomainTableName(), $insertFields);
    if ($res) {
      $scopeKey = $this->getWeigameDomainScopeKey();
      $this->getWgMemcacheNameSpace()->removeBatchKeys($scopeKey);
      $cacheKey = $this->getCacheKey('findOnlineDomains');
      $this->getWeigameMemcache()->delete($cacheKey);
      $cacheKey = $this->getCacheKey('findOnlineDomains' . '_LEVEL_' . $fields['level']);
      $this->getWeigameMemcache()->delete($cacheKey);
      $cacheKey = $this->getCacheKey('findOnlineDomains' . '_LEVEL_' . $fields['level'] . '_CATEGORY_' . $fields['category']);
      $this->getWeigameMemcache()->delete($cacheKey);
      $cacheKey = $this->getCacheKey('findDomainWithAddress' . '_ADDRESS_' . $fields['address']);
      $this->getWeigameMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc update wg_domain
   */
  public function updateDomain ($fields) {
    if (!$fields['id']) {
      throw new Exception('id or fields is null');
    }

    $updateFields = array();
    if ($fields['address']) $updateFields['address'] = $fields['address'];
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if (isset($fields['level'])) $updateFields['level'] = $fields['level'];
    if (isset($fields['expiring_date'])) $updateFields['expiring_date'] = $fields['expiring_date'];
    if (isset($fields['remarks'])) $updateFields['remarks'] = $fields['remarks'];
    if (isset($fields['category'])) $updateFields['category'] = $fields['category'];
    if ($updateFields) {
      $updateFields['updated_time'] = $fields['updated_time'] ? $fields['updated_time'] : time();
      $stmt = $this->getWeigameConnection()->prepare("UPDATE {$this->getWeigameDomainTableName()} {$this->getUpdateSect($updateFields)} WHERE id = :id LIMIT 1");
      $stmt->bindValue(':id', $fields['id'], PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $scopeKey = $this->getWeigameDomainScopeKey();
        $this->getWgMemcacheNameSpace()->removeBatchKeys($scopeKey);
        $cacheKey = $this->getCacheKey('findOnlineDomains');
        $this->getWeigameMemcache()->delete($cacheKey);
        $cacheKey = $this->getCacheKey('findOnlineDomains' . '_LEVEL_' . $fields['level']);
        $this->getWeigameMemcache()->delete($cacheKey);
        $cacheKey = $this->getCacheKey('findOnlineDomains' . '_LEVEL_' . $fields['level'] . '_CATEGORY_' . $fields['category']);
        $this->getWeigameMemcache()->delete($cacheKey);
        $cacheKey = $this->getCacheKey('findDomainWithAddress' . '_ADDRESS_' . $fields['address']);
        $this->getWeigameMemcache()->delete($cacheKey);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
  * @return wg_groups
  */
  public function findDomainGroup ($offset = 0, $limit = 10) {
    if (!$limit) {
      throw new Exception('limit is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getWeigameGroupScopeKey();
    $rows = $this->getWgMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getWeigameGroupsTableName()} ORDER BY level DESC LIMIT :offset, :limit");
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getWgMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::ONE_DAY_CACHE_TIME);
    }
    return $rows;
  }

  /**
  * @return wg_groups
  */
  public function findDomainGroups () {
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_GROUPS_' . '1.0');
    $rows = $this->getWeigameMemcache()->get($cacheKey);
    if ($rows === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getWeigameGroupsTableName()}");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getWeigameMemcache()->set($cacheKey, $rows);
    }
    return $rows;
  }

  /**
   * @desc select wg_groups
   * @param int level
   */
  public function findDomainGroupBylevel ($level) {
    if ($level === NULL) {
      throw new Exception('level is null');
    }
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_LEVEL_' . $level);
    $row = $this->getWeigameMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getWeigameGroupsTableName()} WHERE level = :level LIMIT 1");
      $stmt->bindValue(':level', $level, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getWeigameMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc insert wg_groups
   */
  public function insertDomainGroup ($fields) {
    if (!$fields['name']) {
      throw new Exception('name is null');
    }
    $insertFields = array();
    $insertFields['name'] = $fields['name'];
    if ($fields['is_twodomain']) $insertFields['is_twodomain'] = $fields['is_twodomain'];
    if ($fields['is_move']) $insertFields['is_move'] = $fields['is_move'];
    if ($fields['is_random']) $insertFields['is_random'] = $fields['is_random'];
    if ($fields['domain_cycle_lens']) $insertFields['domain_cycle_lens'] = $fields['domain_cycle_lens'];
    if ($fields['domain_cycle_times']) $insertFields['domain_cycle_times'] = $fields['domain_cycle_times'];
    if ($fields['domain_level_uris']) $insertFields['domain_level_uris'] = $fields['domain_level_uris'];
    if ($fields['remark']) $insertFields['remark'] = $fields['remark'];
    if ($fields['baidu_code']) $insertFields['baidu_code'] = $fields['baidu_code'];
    if ($insertFields) $id = $this->insert($this->getWeigameConnection(), $this->getWeigameGroupsTableName(), $insertFields, TRUE);
    if ($id) {
      $scopeKey = $this->getWeigameGroupScopeKey();
      $this->getWgMemcacheNameSpace()->removeBatchKeys($scopeKey);
      $cacheKey = $this->getCacheKey('findDomainGroups' . '_GROUPS_' . '1.0');
      $this->getWeigameMemcache()->delete($cacheKey);
      $insertFields['level'] = $id;
      return $insertFields;
    }
    return FALSE;
  }

  /**
   * @desc update wg_groups
   */
  public function updateDomainGroup ($level, $fields) {
    if (!$fields || !$level) {
      throw new Exception('name level is null');
    }
    $updateFields = array();
    if ($fields['name']) $updateFields['name'] = $fields['name'];
    if (isset($fields['domain_level_uris'])) $updateFields['domain_level_uris'] = $fields['domain_level_uris'];
    if (isset($fields['is_twodomain'])) $updateFields['is_twodomain'] = $fields['is_twodomain'];
    if (isset($fields['is_move'])) $updateFields['is_move'] = $fields['is_move'];
    if (isset($fields['is_random'])) $updateFields['is_random'] = $fields['is_random'];
    if (isset($fields['domain_cycle_lens'])) $updateFields['domain_cycle_lens'] = $fields['domain_cycle_lens'];
    if (isset($fields['domain_cycle_times'])) $updateFields['domain_cycle_times'] = $fields['domain_cycle_times'];
    if (isset($fields['baidu_code'])) $updateFields['baidu_code'] = $fields['baidu_code'];
    if (isset($fields['remark'])) $updateFields['remark'] = $fields['remark'];
    if ($updateFields) {
      $updateFields['updated_time'] = time();
      $stmt = $this->getWeigameConnection()->prepare("UPDATE {$this->getWeigameGroupsTableName()} {$this->getUpdateSect($updateFields)} WHERE level = :level LIMIT 1");
      $stmt->bindValue(':level', $level, PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $cacheKey = $this->getCacheKey('findDomainGroupBylevel' . '_LEVEL_' . $level);
        $this->getWeigameMemcache()->delete($cacheKey);
        $scopeKey = $this->getWeigameGroupScopeKey();
        $this->getWgMemcacheNameSpace()->removeBatchKeys($scopeKey);
        $cacheKey = $this->getCacheKey('findDomainGroups' . '_GROUPS_' . '1.0');
        $this->getWeigameMemcache()->delete($cacheKey);
        return TRUE;
      }
      return FALSE;
    }
  }

  /**
   * @desc find 你懂我吗游戏系列   
   */
  public function findknowgame ($offset = 0, $limit = 10) { 
    $cacheKey = $this->getCacheKey(__FUNCTION__  . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
    $scopeKey = $this->getknowScopeKey();
    $rows = $this->getWgMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getWeigameKnowTableName()} ORDER BY id DESC LIMIT :offset, :limit");
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
      $this->getWgMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::ONE_HOUR_CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @desc insert 你懂我吗游戏系列
   */
  public function insertknowgame ($fields) {
    if (!$fields) {
      throw new Exception('fields is null...');
    }
    $insertFields = array();
    $insertFields['level'] = $fields['level'];
    $insertFields['title'] = $fields['title'];
    $insertFields['background_img'] = $fields['background_img'];
    $insertFields['center_img'] = $fields['center_img'];
    $insertFields['share_logo'] = $fields['share_logo'];
    $insertFields['ct_button'] = $fields['ct_button'];
    $insertFields['dt_button'] = $fields['dt_button'];
    $insertFields['share_center'] = $fields['share_center'];
    $insertFields['share_button'] = $fields['share_button'];
    $insertFields['color'] = $fields['color'];
    $insertFields['question'] = $fields['question'];
    $insertFields['answer'] = $fields['answer'];
    $insertFields['share_title'] = $fields['share_title'];
    if ($fields['jssdk_mpids']) $insertFields['jssdk_mpids'] = $fields['jssdk_mpids'];
    if ($fields['pay_mpid']) $insertFields['pay_mpid'] = $fields['pay_mpid'];
    if ($fields['is_qq']) $insertFields['is_qq'] = $fields['is_qq'];
    $lastInsertId = $this->insert($this->getWeigameConnection(), $this->getWeigameKnowTableName(), $insertFields, TRUE);
    if ($lastInsertId) {
      $scopeKey = $this->getknowScopeKey();
      $this->getWgMemcacheNameSpace()->removeBatchKeys($scopeKey);
      $insertFields['id'] = $lastInsertId;
      return $insertFields;
    }
  
    return array();
  }

  /**
   * @desc find 测试付费游戏
   */
  public function findknowGameById ($knowgameid) {
    if (!$knowgameid) {
      throw new Exception('id is null...');
    }
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_KNOWGAMEID_' . $knowgameid);
    $row = $this->getWeigameMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getWeigameKnowTableName()} WHERE id = :id LIMIT 1");
      $stmt->bindValue(':id', $knowgameid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
      $this->getWeigameMemcache()->set($cacheKey, $row, 0, self::ONE_HOUR_CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc update 测测付费游戏
   */
  public function updateKnowGame ($knowgameid, $fields) {
    if (!$knowgameid && !$fields) {
      throw new Exception('knowgameid fields is null...');
    } 
    $updateFields = array();
    if ($fields['level']) $updateFields['level'] = $fields['level'];
    if ($fields['title']) $updateFields['title'] = $fields['title'];
    if ($fields['background_img']) $updateFields['background_img'] = $fields['background_img'];
    if ($fields['center_img']) $updateFields['center_img'] = $fields['center_img'];
    if ($fields['share_logo']) $updateFields['share_logo'] = $fields['share_logo'];
    if ($fields['ct_button']) $updateFields['ct_button'] = $fields['ct_button'];
    if ($fields['dt_button']) $updateFields['dt_button'] = $fields['dt_button'];
    if ($fields['share_center']) $updateFields['share_center'] = $fields['share_center'];
    if ($fields['share_button']) $updateFields['share_button'] = $fields['share_button'];
    if ($fields['share_title']) $updateFields['share_title'] = $fields['share_title'];
    if ($fields['color']) $updateFields['color'] = $fields['color'];
    if ($fields['question']) $updateFields['question'] = $fields['question'];
    if ($fields['answer']) $updateFields['answer'] = $fields['answer'];
    if (isset($fields['jssdk_mpids'])) $updateFields['jssdk_mpids'] = $fields['jssdk_mpids'];
    if (isset($fields['pay_mpid'])) $updateFields['pay_mpid'] = $fields['pay_mpid'];
    if (isset($fields['is_qq'])) $updateFields['is_qq'] = $fields['is_qq'];
    if ($updateFields) {
      $updateFields['updated_time'] = time();
      $sql = "UPDATE {$this->getWeigameKnowTableName()} {$this->getUpdateSect($updateFields)} WHERE id = :id LIMIT 1";
      $stmt = $this->getWeigameConnection()->prepare($sql);
      $stmt->bindValue(':id', $knowgameid, PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $cacheKey = $this->getCacheKey('findknowGameById' . '_KNOWGAMEID_' . $knowgameid);
        $this->getWeigameMemcache()->delete($cacheKey);
        $scopeKey = $this->getknowScopeKey();
        $this->getWgMemcacheNameSpace()->removeBatchKeys($scopeKey);
      }
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @return wg_know_questions
   */
  public function findKnowQuestionById($id) {
    if (!$id) {
      throw new Exception('id is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_v1.0_' . $id);
    $row = $this->getWeigameMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getKnowQuestionHashTableName($id)} WHERE id = :id");
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (empty($row)) $row = array();
      $this->getWeigameMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }
  
  /**
   * @desc insert wg_know_questions
   */
  public function insertKnowQuestion($fields) {
    if (!$fields['id'] || !$fields['uid'] || !$fields['qa_content']) {
      throw new Exception('id, uid or qa_content is null...');
    }
  
    $insertFields = array();
    $insertFields['id'] = $fields['id'];
    $insertFields['uid'] = $fields['uid'];
    $insertFields['qa_content'] = $fields['qa_content'];
    if ($fields['from']) $insertFields['from'] = $fields['from'];
    if ($fields['created_time']) $insertFields['created_time'] = $fields['created_time'];
    $insertFields['updated_time'] = 'NONE';
    return $this->insert($this->getWeigameConnection(), $this->getKnowQuestionHashTableName($fields['id']), $insertFields);
  }
  
  /**
   * @desc update wg_know_questions
   */
  public function updateKnowQuestion($fields) {
    if (!$fields['id'] || !$fields['uid'] || !$fields['qa_content']) {
      throw new Exception('id, uid or qa_content is null...');
    }
  
    $updateFields = array();
    $updateFields['qa_content'] = $fields['qa_content'];
    $sql = "UPDATE {$this->getKnowQuestionHashTableName($fields['id'])} {$this->getUpdateSect($updateFields)} WHERE id = :id LIMIT 1";
    $stmt = $this->getWeigameConnection()->prepare($sql);
    $stmt->bindValue(':id', $fields['id'], PDO::PARAM_INT);
    $this->bindValues($stmt, $updateFields);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if ($rowCount) {
      $cacheKey = $this->getCacheKey('findKnowQuestionById_v1.0_' . $fields['id']);
      $this->getWeigameMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @return wg_know_answers
   */
  public function findKnowQuestionAnswers($qid, $offset = 0, $limit = 20) {
    if (!$qid || !$limit) {
      throw new Exception('qid or limit is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_' . $qid);
    $rows = $this->getWeigameMemcache()->get($cacheKey);
    if ($rows === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getKnowAnswerHashTableName($qid)} WHERE qid = :qid ORDER BY created_time DESC LIMIT {$offset}, {$limit}");
      $stmt->bindValue(':qid', $qid, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      if (empty($rows)) $rows = array();
      $this->getWeigameMemcache()->set($cacheKey, $rows, 0, self::CACHE_TIME);
    }
    return $rows;
  }
  
  /**
   * @return wg_know_answers
   */
  public function findKnowQuestionAnswerByUid($qid, $uid) {
    if (!$qid || !$uid) {
      throw new Exception('qid or uid is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_QID_' . $qid . '_UID_' . $uid);
    $row = $this->getWeigameMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getKnowAnswerHashTableName($qid)} WHERE qid = :qid AND uid = :uid LIMIT 1");
      $stmt->bindValue(':qid', $qid, PDO::PARAM_INT);
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
      if (empty($row)) $row = array();
      $this->getWeigameMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }
  
  /**
   * @return wg_know_answers
   */
  public function findKnowQuestionAnswerByAid($qid, $aid) {
    if (!$qid || !$aid) {
      throw new Exception('qid or id is null...');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . '_QID_' . $qid . '_AID_' . $aid);
    $row = $this->getWeigameMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getWeigameConnection()->prepare("SELECT * FROM {$this->getKnowAnswerHashTableName($qid)} WHERE id = :id LIMIT 1");
      $stmt->bindValue(':id', $aid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
      if (empty($row)) $row = array();
      $this->getWeigameMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc insert wg_know_answers
   */
  public function insertKnowQuestionAnswer($fields) {
    if (!$fields['qid'] || !$fields['uid'] || !$fields['qa_content']) {
      throw new Exception('qid, uid or qa_content is null...');
    }
  
    $insertFields = array();
    $insertFields['qid'] = $fields['qid'];
    $insertFields['uid'] = $fields['uid'];
    $insertFields['qa_content'] = $fields['qa_content'];
    if ($fields['created_time']) $insertFields['created_time'] = $fields['created_time'];
    $insertFields['updated_time'] = 'NONE';
    $res = $this->insert($this->getWeigameConnection(), $this->getKnowAnswerHashTableName($fields['qid']), $insertFields);
    if ($res) {
      $cacheKey = $this->getCacheKey('findKnowQuestionAnswers' . '_' . $fields['qid']);
      $this->getWeigameMemcache()->delete($cacheKey);
      
      $cacheKey = $this->getCacheKey('findKnowQuestionAnswerByUid' . '_QID_' . $fields['qid'] . '_UID_' . $fields['uid']);
      $this->getWeigameMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc update wg_know_answers
   */
  public function updateKnowQuestionAnswer($fields) {
    if (!$fields['qid'] || !$fields['uid'] ) {
      throw new Exception('qid, uid or qa_content is null...');
    }
  
    $updateFields = array();
    if (isset($fields['qa_content'])) $updateFields['qa_content'] = $fields['qa_content'];
    if (isset($fields['is_pay'])) $updateFields['is_pay'] = $fields['is_pay'];
    if (isset($fields['reanswer_num'])) $updateFields['reanswer_num'] = $fields['reanswer_num'];
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($updateFields) {
      $sql = "UPDATE {$this->getKnowAnswerHashTableName($fields['qid'])} {$this->getUpdateSect($updateFields)} WHERE qid = :qid AND uid = :uid LIMIT 1";
      $stmt = $this->getWeigameConnection()->prepare($sql);
      $stmt->bindValue(':qid', $fields['qid'], PDO::PARAM_INT);
      $stmt->bindValue(':uid', $fields['uid'], PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      if ($rowCount) {
        $cacheKey = $this->getCacheKey('findKnowQuestionAnswers' . '_' . $fields['qid']);
        $this->getWeigameMemcache()->delete($cacheKey);
        $cacheKey = $this->getCacheKey('findKnowQuestionAnswerByUid' . '_QID_' . $fields['qid'] . '_UID_' . $fields['uid']);
        $this->getWeigameMemcache()->delete($cacheKey);
        $cacheKey = $this->getCacheKey('findKnowQuestionAnswerByAid' . '_QID_' . $fields['qid'] . '_AID_' . $fields['id']);
        $this->getWeigameMemcache()->delete($cacheKey);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @desc delete wg_know_answers
   */
  public function deleteKnowQuestionAnswer($id, $qid, $uid) {
    if (!$id || !$qid || !$uid) {
      throw new Exception('id or qid or uid is null...');
    }
    $sql = "DELETE FROM {$this->getKnowAnswerHashTableName($qid)} WHERE id = :id LIMIT 1";
    $stmt = $this->getWeigameConnection()->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if ($rowCount) {
      $cacheKey = $this->getCacheKey('findKnowQuestionAnswers' . '_' . $qid);
      $this->getWeigameMemcache()->delete($cacheKey);
      $cacheKey = $this->getCacheKey('findKnowQuestionAnswerByUid' . '_QID_' . $qid . '_UID_' . $uid);
      $this->getWeigameMemcache()->delete($cacheKey);
      $cacheKey = $this->getCacheKey('findKnowQuestionAnswerByAid' . '_QID_' . $qid . '_AID_' . $id);
      $this->getWeigameMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }
}