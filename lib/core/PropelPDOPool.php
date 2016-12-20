<?php
/**
 * Propel PDO Pool.
 * @author VegaPunk
 * @since 2012/2/20
 */
if (!Class_Exists("PropelPDOPool", FALSE)) {
  require_once (dirname(__FILE__) . "/PropelPDOExt.php");
  class PropelPDOPool {

    private static $instance = null;

    private $connectionPool = array();

    //单实例模式
    public static function getInstance () {
      if (self::$instance === null || !is_object(self::$instance)) {
        self::$instance = new PropelPDOPool();
      }
      return self::$instance;
    }

    //将实例化的pdo对象缓存起来，如果config一样，将不重新产生pdo对象
    public function getConnection ($dsn, $username = '', $password = '', $character = 'utf8', array $driverOptions = array()) {
      if (empty($dsn)) {
        return null;
      } else {
        $key = md5($dsn . $username . $password);
        if (isset($this->connectionPool[$key]) && is_object($this->connectionPool[$key]) && $this->isActive($this->connectionPool[$key]) === TRUE) {
        } else {
          $this->connectionPool[$key] = $this->getPdoInstance($dsn, $username, $password, $character, $driverOptions);
        }
        return $this->connectionPool[$key];
      }
    }

    /**
     * @desc ping心跳检测$connection是否可用
     */
    private function isActive (&$connection) {
      if (is_callable(array($connection, "ping"))) {
        if ($connection->ping()) {
          return TRUE;
        } else {
          return FALSE;
        }
      } else {
        return TRUE;
      }
    }

    /**
     * @return PDO Instance
     */
    private function getPdoInstance ($dsn, $username, $password, $character, Array $driverOptions = array()) {
      try {
        $driverOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $driverOptions[PDO::ATTR_EMULATE_PREPARES] = FALSE;
        try {
          $connection = new PropelPDOExt($dsn, $username, $password, $driverOptions);
          //$connection->setAttribute(PropelPDOExt::PROPEL_ATTR_CACHE_PREPARES, TRUE);
          $connection->exec("set names {$character}");
        } catch (Exception $ex) {
          $exMessage = "Connection failed: ".__CLASS__.'::'.__FUNCTION__.", {$ex->getMessage()}\n";
          if (APP_DEBUG) {
            die($exMessage);
          } else {
            error_log($exMessage, 3, "../PDOConnection_".date('Ymd').".log");
            die('Please waitting...');
          }
        }
        return $connection;
      } catch (Exception $ex) {
        return null;
      }
    }
  }
}
