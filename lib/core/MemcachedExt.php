<?php
/**
 * Propel Memcache Ext.
 * @author VegaPunk
 * @since 2012/2/20
 */
if (!Class_Exists("MemcachedExt", FALSE)) {
  if (Class_Exists("Memcached", TRUE)) {
    class MemcachedExt extends Memcached {

      private $cacheKeys = array();

      public function __construct ($persistent_id = '') {
        $persistent_id = trim($persistent_id);
        if ($persistent_id === '') {
          parent::__construct();
        } else {
          parent::__construct($persistent_id);
        }
        parent::setOption(Memcached::OPT_COMPRESSION, true);
        parent::setOption(Memcached::OPT_NO_BLOCK, true);
        parent::setOption(Memcached::OPT_SEND_TIMEOUT, 100);
        parent::setOption(Memcached::OPT_HASH, Memcached::HASH_CRC);
        parent::setOption(Memcached::OPT_CONNECT_TIMEOUT, 100);
        //parent::setOption(Memcached::OPT_HASH,Memcached::HASH_MD5);
        parent::setOption(Memcached::OPT_DISTRIBUTION,Memcached::DISTRIBUTION_CONSISTENT);
      }

      private function reBuildKey ($key) {
        if (is_array($key)) {
          foreach ($key as $v) {
            $md5Key[$v] = md5($v);
          }
        } else {
          $md5Key = md5($key);
          //@error_log("{$md5Key} {$key}\n",3,"/tmp/memcachedExtOut.txt");
        }
        return $md5Key;
      }

      public function getForDebug ($key) {
        return parent::get($key);
      }

      /**
       * @TODO 重写get有异常
       */
      public function get ($key, $cache_cb = NULL, $cas_token = NULL, $get_flag = NULL) {
        $key = $this->reBuildKey($key);
        if (is_array($key)) {
          $value = parent::getMulti($key);
          if (!is_array($value)) {
            return $value;
          }
          $key = array_flip($key);
          $value2 = array();
          foreach ($value as $k => $v) {
            $value2[$key[$k]] = $v;
          }
          return $value2;
        } else {
          if (PHP_SAPI == 'cli') {
            $value = parent::get($key);
          } else {
            if (isset($this->cacheKeys[$key])) {
              return $this->cacheKeys[$key];
            }
            $value = parent::get($key);
            if (!empty($value)) {
              $this->cacheKeys[$key] = $value;
            }
          }
        }
        return $value;
      }

      public function delete ($key, $time = NULL) {
        $key = $this->reBuildKey($key);
        unset($this->cacheKeys[$key]);
        return parent::delete($key);
      }

      public function set ($key, $value, $flag = 0, $expire = '') {
        $key = $this->reBuildKey($key);
        unset($this->cacheKeys[$key]);
        if ($expire == '') {
          $res = parent::set($key, $value);
        } else {
          $res = parent::set($key, $value, $expire);
        }
        return $res;
      }

      public function add ($key, $value, $flag = 0, $expire = '') {
        $key = $this->reBuildKey($key);
        unset($this->cacheKeys[$key]);
        if ($expire == '') {
          $res = parent::add($key, $value);
        } else {
          $res = parent::add($key, $value, $expire);
        }
        return $res;
      }

      public function replace ($key, $value, $flag = 0, $expire = '') {
        $key = $this->reBuildKey($key);
        unset($this->cacheKeys[$key]);
        if ($expire == '') {
          $res = parent::replace($key, $value);
        } else {
          $res = parent::replace($key, $value, $expire);
        }
        return $res;
      }

      public function addServer ($host, $ip, $persistent = false, $weight = 0) {
        return parent::addServer($host, $ip);
      }

      public function setCompressThreshold ($x, $y) {
        return;
      }
    }
  } else {
    class MemcachedExt extends Memcache {

      public function __construct () {
        //parent::__construct();
        //parent::setCompressThreshold(102400, 0.1);
      }
    }
  }
}
