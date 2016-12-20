<?php
require_once APP_LIB_CORE_DIR . '/MemcachedPool.php';
require_once APP_LIB_CACHE_DIR . '/MemcacheNameSpace.php';
final class MemcacheFactory {

  const MEM_CACHE_TIME = 1800;
  
  protected static $instance = null;

  protected static $memcache = null;

  protected static $nameSpaceCache = array();

  protected static $config = array();
  
  protected function __construct () {
    if (!self::$config) {
      //read memcache config
      $config = str_replace('%level%', APP_SYSTEM_RUN_LEVEL, APP_CONFIG_MEMCACHE_FILE);
      self::$config = include ($config);
    }
  }

  /**
   * @return MemcacheFactory
   */
  public static function getInstance () {
    if (!self::$instance) {
      self::$instance = new MemcacheFactory();
    }
    return self::$instance;
  }

  public function __clone () {
    throw new Exception('不允许Clone.');
  }

  /**
   * @param $cacheNode cache节点别名
   * @return Memcache
   */
  public function getMemcache ($cacheNode) {
    if (!self::$config[$cacheNode]) {
      throw new Exception('Unable to find cache node.');
    }
    
    if (!self::$memcache[$cacheNode]) {
      self::$memcache[$cacheNode] = MemcachedPool::getInstance()->getMemcache(self::$config[$cacheNode]);
    }
    return self::$memcache[$cacheNode];
  }

  /**
   * @desc Create Space Cache
   * @param string $spaceName
   * @param int $cacheTime
   * @return NameSpaceCache
   */
  public function createMemcacheNameSpace ($spaceName, $cacheTime = self::MEM_CACHE_TIME, $cacheNode) {
    if (!self::$nameSpaceCache[$spaceName]) {
      self::$nameSpaceCache[$spaceName] = new MemcacheNameSpace($spaceName, $this->getMemcache($cacheNode), $cacheTime);
    }
    return self::$nameSpaceCache[$spaceName];
  }
}