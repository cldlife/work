<?php
/**
 * @desc GearmanClient工厂类
 * 注:本工厂生产的对象都是GearmanClient类的
 */
final class GearmanFactory {

  protected static $instance = null;

  protected static $gearman = array(
    'clients' => array(),
    'workers' => array(),
  );

  protected static $config = array();

  protected function __construct () {
    if (!self::$config) {
      //read gearman config
      $config = str_replace('%level%', APP_SYSTEM_RUN_LEVEL, APP_CONFIG_GEARMAN_FILE);
      self::$config = include ($config);
    }
  }

  /**
   * @return object GearmanFactory
   */
  public static function getInstance () {
    if (!self::$instance) {
      self::$instance = new GearmanFactory();
    }
    return self::$instance;
  }

  public function __clone () {
    throw new Exception('不允许Clone.');
  }

  /**
   * @param array $serverNode 节点别名
   * @return object gearman
   */
  public function getGearman ($serverNode) {
    if (!self::$config[$serverNode]) {
      throw new Exception('Unable to find server node.');
    }

    if (!self::$gearman['clients'][$serverNode]) {
      $gearman = new GearmanClient();
      foreach (self::$config[$serverNode] as $cfg) {
        if ($cfg['host'] && $cfg['port']) {
          $gearman->addServer($cfg['host'], $cfg['port']);
        }
      }
      self::$gearman['clients'][$serverNode] = $gearman;
    }
    return self::$gearman['clients'][$serverNode];
  }

  /**
   * @param array $serverNode 节点别名
   * @return object gearman worker
   */
  public function getGearmanWorker ($serverNode) {
    if (!self::$config[$serverNode]) {
      throw new Exception('Unable to find server node.');
    }

    $gearman = new GearmanWorker();
    foreach (self::$config[$serverNode] as $cfg) {
      if ($cfg['host'] && $cfg['port']) {
        $gearman->addServer($cfg['host'], $cfg['port']);
      }
    }
    self::$gearman['workers'][$serverNode][] = $gearman;
    return $gearman;
  }
}
