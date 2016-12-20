<?php
/**
 * Propel Memcache Pool.
 * @author VegaPunk
 * @since 2012/2/20
 */
if (!Class_Exists("MemcachedPool", FALSE)) {
  require_once (dirname(__FILE__) . "/MemcachedExt.php");
  class MemcachedPool {

    private static $instance = null;

    private $memcachePool = array();

    //单实例模式
    public static function getInstance () {
      if (self::$instance === null || !is_object(self::$instance)) {
        self::$instance = new MemcachedPool();
      }
      return self::$instance;
    }

    //将实例化的memcache对象缓存起来，如果config一样，将不重新产生memcache对象
    public function getMemcache ($config = null) {
      if (empty($config)) {
        return $this->getMemcachedExtInstance($config);
      } else {
        $key = md5(serialize($config));
        if (isset($this->memcachePool[$key]) && is_object($this->memcachePool[$key])) {
        } else {
          $this->memcachePool[$key] = $this->getMemcachedExtInstance($config);
        }
        return $this->memcachePool[$key];
      }
    }

    /**
     * @return Memcache object
     */
    private function getMemcachedExtInstance (&$config) {
      $memcache = new MemcachedExt();
      if (!empty($config)) {
        foreach ($config as $cfg) {
          if ((isset($cfg['host']) && !empty($cfg['host'])) && (isset($cfg['port']) && !empty($cfg['port']))) {
            $memcache->addServer($cfg['host'], $cfg['port'], false);
          }
        }
        $memcache->setCompressThreshold(102400, 0.1);
      }
      return $memcache;
    }
  }
}


/*
$config=array(
        array('host' => '127.0.0.1', 'port' => '12345'),
        array('host' => '127.0.0.1', 'port' => '12346'),
);

$obj=MemcachedPool::getInstance()->getMemcache($config);
$obj->set("111","1111");
var_dump($obj->get("111"));
$obj->set("222","2222");
var_dump($obj->get(array("111","222")));
*/
