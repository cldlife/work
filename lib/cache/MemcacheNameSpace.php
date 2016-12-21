<?php
/**
 * Cache of Namespace 
 * @author VegaPunk 
 */
if (!Class_Exists("MemcacheNameSpace", FALSE)) {
  class MemcacheNameSpace {

    private $scope = null;

    private $memcache = null;

    private static $CLOSE_CACHE = false;
    
    private function rebuildKey ($key) {
      return md5($key);
    }

    function __construct ($scope, $memcache, $cacheTime) {
      if (!$scope || $scope == '' || !$memcache) {
        throw new Exception("scope or memcache not set properly, initialization error!");
      }
      
      $this->setScope($scope);
      $this->setMemcache($memcache);
      $this->cacheTime = $cacheTime;
    }

    public function setScope ($scope) {
      $this->scope = $scope;
    }

    public function getScope () {
      return $this->scope;
    }

    public function setMemcache ($memcache) {
      $this->memcache = $memcache;
    }

    public function getMemcache () {
      return $this->memcache;
    }

    public function getFromCache ($key, $scopeKey) {
      if (self::$CLOSE_CACHE) return false;
      $key = $this->rebuildKey($key);
      if ($this->inBatchKeys($scopeKey, $key))
        return $this->getMemcache()->get($key);
      else
        return false;
    }

    public function saveToCache ($key, $scopeKey, $value, $_cacheTime) {
      if (self::$CLOSE_CACHE) return false;
      $key = $this->rebuildKey($key);
      //设置缓存时间
      $this->cacheTime = $_cacheTime;
      $keys = $this->getBatchKeys($scopeKey);
      if (!$keys) $keys = array();
      if (!in_array($key, $keys)) {
        array_push($keys, $key);
        $this->setBatchKeys($scopeKey, $keys);
      }
      $this->getMemcache()->set($key, $value, 0, $this->cacheTime);
    }

    /**
     * 把某个key从namespace中移除
     * @param String $cacheKey
     * @param String $scopeKey
     */
    public function removeFromCache ($cacheKey, $scopeKey) {
      if (self::$CLOSE_CACHE) return false;
      $cacheKey = $this->rebuildKey($cacheKey);
      $keys = $this->getBatchKeys($scopeKey);
      if ($keys) {
        if (is_array($keys) && in_array($cacheKey, $keys)) {
          //从$keys中删除改元素
          //交换数组中的键和值
          $reserveArray = array_flip($keys);
          //定位$keys中$cacheKey对应的key
          $keyArray = $reserveArray[$cacheKey];
          unset($keys[$keyArray]);
          //从$keys中删除改元素，然后再次放到cache中
          $this->setBatchKeys($scopeKey, $keys);
        }
      }
      //移出改$cacheKey对应的cache
      $this->getMemcache()->delete($cacheKey);
      return;
    }

    /**
     * @param string $key 已经rebuild的key
     */
    private function inBatchKeys ($scopeKey, $key) {
      $batchKeys = $this->getBatchKeys($scopeKey);
      if (!$batchKeys) return false;
      return in_array($key, $batchKeys);
    }

    public function getBatchKeys ($scopeKey) {
      return $this->getMemcache()->get('scope_' . $this->scope . '_' . $scopeKey);
    }

    private function setBatchKeys ($scopeKey, $keys) {
      $this->getMemcache()->set('scope_' . $this->scope . '_' . $scopeKey, $keys, 0, $this->cacheTime);
    }

    public function removeBatchKeys ($scopeKey) {
      if (self::$CLOSE_CACHE) return false;
      $keys = $this->getMemcache()->get('scope_' . $this->scope . '_' . $scopeKey);
      if (!$keys) return false;
      foreach ($keys as $key) {
        $this->getMemcache()->delete($key);
      }
      $this->getMemcache()->delete('scope_' . $this->scope . '_' . $scopeKey);
    }

    /**
     * @desc 	加入 $page (当前页面page值), 指定$keys中以key => value(相当于$page => $key)的形式缓存
     * 
     * 调用方法：
     * getFromCachePage() 				取当前page值对应的cache
     * saveToCacheWithPageKey()			以page值为Array的key, 保存cache
     * removeFromCacheWithPage()		清除当前page值对应的cache
     * removeFromCacheAfterPage()		清除当前page值, 及其之后page值对应的cache
     */
    const CACHE_PAGE_KEY = 'CACHE_PAGE_KEY';

    /**
     * @param string $key 已经rebuild的key
     */
    private function inBatchKeysByCachePage ($scopeKey, $key) {
      $batchKeys = $this->getBatchKeys($scopeKey);
      if (!$batchKeys[self::CACHE_PAGE_KEY]) return false;
      return in_array($key, $batchKeys[self::CACHE_PAGE_KEY]);
    }

    public function getFromCachePage ($key, $scopeKey) {
      if (self::$CLOSE_CACHE) return false;
      $key = $this->rebuildKey($key);
      if ($this->inBatchKeysByCachePage($scopeKey, $key))
        return $this->getMemcache()->get($key);
      else
        return false;
    }

    public function saveToCacheWithPageKey ($page, $key, $scopeKey, $value, $_cacheTime) {
      if (self::$CLOSE_CACHE) return false;
      $key = $this->rebuildKey($key);
      $page = abs(intval($page));
      if ($page == 0) return false;
      //设置缓存时间
      $this->cacheTime = $_cacheTime;
      $keys = $this->getBatchKeys($scopeKey);
      if (!$keys[self::CACHE_PAGE_KEY]) $keys[self::CACHE_PAGE_KEY] = array();
      if (!in_array($key, $keys[self::CACHE_PAGE_KEY])) {
        //array_push($keys[self::CACHE_PAGE_KEY], array($page => $key));
        $keys[self::CACHE_PAGE_KEY][$page] = $key;
        $this->setBatchKeys($scopeKey, $keys);
      }
      $this->getMemcache()->set($key, $value, 0, $this->cacheTime);
    }

    //Romove当前page的Cache
    public function removeFromCacheWithPage ($page, $scopeKey) {
      if (self::$CLOSE_CACHE) return false;
      $page = abs(intval($page));
      if ($page == 0) return false;
      $keys = $this->getBatchKeys($scopeKey);
      $cachePageKeys = $keys[self::CACHE_PAGE_KEY];
      if (!is_array($cachePageKeys)) return false;
      if (isset($cachePageKeys[$page])) {
        $this->getMemcache()->delete($cachePageKeys[$page]);
        unset($keys[self::CACHE_PAGE_KEY][$page]);
        $this->setBatchKeys($scopeKey, $keys);
      }
      return true;
    }

    //Romove当前page及其之后的Cache
    public function removeFromCacheAfterPage ($page, $scopeKey) {
      if (self::$CLOSE_CACHE) return false;
      $page = abs(intval($page));
      if ($page == 0) return false;
      if ($page == 1) {
        $this->removeBatchKeys($scopeKey);
      } else {
        $keys = $this->getBatchKeys($scopeKey);
        $cachePageKeys = $keys[self::CACHE_PAGE_KEY];
        if (!is_array($cachePageKeys)) return false;
        //取出$keys中需delete的page值
        ksort($cachePageKeys, SORT_NUMERIC);
        $pages = array_keys($cachePageKeys);
        $pagesFlip = array_flip($pages);
        if ($pagesFlip[$page] === NULL) return false;
        $deletePages = array_slice($pages, $pagesFlip[$page]);
        foreach ($deletePages as $page) {
          $this->getMemcache()->delete($cachePageKeys[$page]);
          unset($keys[self::CACHE_PAGE_KEY][$page]);
          $this->setBatchKeys($scopeKey, $keys);
        }
      }
      return true;
    }
  }
}