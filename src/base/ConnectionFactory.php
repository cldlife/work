<?php
/**
 * @desc DB Connection
 * @author VegaPunk
 * @since 2012/04/14
 */
require_once APP_LIB_CORE_DIR . '/PropelPDOPool.php';
final class ConnectionFactory {

  //默认端口
  const CONNECTION_PORT = 3306;
  
  //默认编码 (支持emoji必须用 utf8mb4)
  const CONNECTION_CHARACTER = 'utf8';

  protected static $instance = array();

  protected static $config = array();

  private $dbSymbol = '';

  /**
   * @desc loading DB config
   */
  protected function __construct () {
    if (!self::$config) {
      $config = str_replace('%level%', APP_SYSTEM_RUN_LEVEL, APP_CONFIG_DATABASE_FILE);
      self::$config = include ($config);
    }
  }

  /**
   * @return ConnectionFactory
   */
  public static function getInstance () {
    if (!self::$instance) {
      self::$instance = new ConnectionFactory();
    }
    return self::$instance;
  }

  public function __clone () {
    throw new Exception('不允许Clone.');
  }

  /**
   * @return PDO Connection
   * @param $dbSymbol 数据库别名
   */
  public function getConnection ($dbSymbol, $dbMS = 'master', $dbType = 'mysql') {
    $this->dbSymbol = $dbSymbol;
    if (empty($this->dbSymbol)) {
      throw new Exception(__CLASS__ . '::' . __FUNCTION__ . ', Can`t find DB Symbol ...');
    }
    
    $dbConfig = self::$config[$this->dbSymbol][$dbMS];
    if (empty($dbConfig)) {
      throw new Exception(__CLASS__ . '::' . __FUNCTION__ . ', Can`t find DB Config ...');
    }
    
    //Mysql Connection
    if ($dbType == 'mysql') {
      $dbPort = $dbConfig['port'] ? $dbConfig['port'] : self::CONNECTION_PORT;
      $dbCharacter = $dbConfig['character'] ? $dbConfig['character'] : self::CONNECTION_CHARACTER;
      $dns = sprintf('mysql:host=%s;dbname=%s;port=%s;charset=%s', $dbConfig['host'], $dbConfig['dbname'], $dbPort, $dbCharacter);
      return PropelPDOPool::getInstance()->getConnection($dns, $dbConfig['user'], $dbConfig['password'], $dbCharacter, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
    }
  }
}
