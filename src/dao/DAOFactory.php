<?php
require_once APP_SRC_BASE_DIR . '/BaseDAO.php';
require_once APP_SRC_BASE_DIR . '/ConnectionFactory.php';
final class DaoFactory {

  protected static $instance = null;

  protected static $daoSet = array();

  public static function getInstance () {
    if (!self::$instance) {
      self::$instance = new DaoFactory();
    }
    return self::$instance;
  }

  /**
   * @return CommonDAO
   */
  public function createCommonDAO () {
    if (!isset(self::$daoSet['CommonDAO'])) {
      require_once APP_SRC_DAO_DIR . '/CommonDAO.php';
      self::$daoSet['CommonDAO'] = new CommonDAO();
    }
    return self::$daoSet['CommonDAO'];
  }

}
