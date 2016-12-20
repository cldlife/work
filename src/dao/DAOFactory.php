<?php
require_once APP_SRC_BASE_DIR . '/BaseDAO.php';
require_once APP_SRC_BASE_DIR . '/ConnectionFactory.php';
require_once APP_SRC_BASE_DIR . '/MemcacheFactory.php';
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
  
  /**
   * @return AttachmentDAO
   */
  public function createAttachmentDAO () {
    if (!isset(self::$daoSet['AttachmentDAO'])) {
      require_once APP_SRC_DAO_DIR . '/AttachmentDAO.php';
      self::$daoSet['AttachmentDAO'] = new AttachmentDAO();
    }
    return self::$daoSet['AttachmentDAO'];
  }
  
  /**
   * @return BkAdminDAO
   */
  public function createBkAdminDAO () {
    if (!isset(self::$daoSet['BkAdminDAO'])) {
      require_once APP_SRC_DAO_DIR . '/BkAdminDAO.php';
      self::$daoSet['BkAdminDAO'] = new BkAdminDAO();
    }
    return self::$daoSet['BkAdminDAO'];
  }
  
  /**
   * @return UserDAO
   */
  public function createUserDAO () {
  	if (!isset(self::$daoSet['UserDAO'])) {
      require_once APP_SRC_DAO_DIR . '/UserDAO.php';
      self::$daoSet['UserDAO'] = new UserDAO();
  	}
  	return self::$daoSet['UserDAO'];
  }
  
  /**
   * @return UserFortuneDAO
   */
  public function createUserFortuneDAO () {
    if (!isset(self::$daoSet['UserFortuneDAO'])) {
      require_once APP_SRC_DAO_DIR . '/UserFortuneDAO.php';
      self::$daoSet['UserFortuneDAO'] = new UserFortuneDAO();
    }
    return self::$daoSet['UserFortuneDAO'];
  }
  
  /**
   * @return UserMineDAO
   */
  public function createUserMineDAO () {
  	if (!isset(self::$daoSet['UserMineDAO'])) {
      require_once APP_SRC_DAO_DIR . '/UserMineDAO.php';
      self::$daoSet['UserMineDAO'] = new UserMineDAO();
  	}
  	return self::$daoSet['UserMineDAO'];
  }
  
  /**
   * @return MessageDAO
   */
  public function createMessageDAO () {
    if (!isset(self::$daoSet['MessageDAO'])) {
      require_once APP_SRC_DAO_DIR . '/MessageDAO.php';
      self::$daoSet['MessageDAO'] = new MessageDAO();
    }
    return self::$daoSet['MessageDAO'];
  }

  /**
   * @return ThingDAO
   */
  public function createThingDAO () {
    if (!isset(self::$daoSet['ThingDAO'])) {
      require_once APP_SRC_DAO_DIR . '/ThingDAO.php';
      self::$daoSet['ThingDAO'] = new ThingDAO();
    }
    return self::$daoSet['ThingDAO'];
  }
  
  /**
   * @return GameDAO
   */
  public function createGameDAO () {
    if (!isset(self::$daoSet['GameDAO'])) {
      require_once APP_SRC_DAO_DIR . '/GameDAO.php';
      self::$daoSet['GameDAO'] = new GameDAO();
    }
    return self::$daoSet['GameDAO'];
  }
 
  /**
   * @return HougongDAO
   */
  public function createHougongDAO () {
    if (!isset(self::$daoSet['HougongDAO'])) {
      require_once APP_SRC_DAO_DIR . '/HougongDAO.php';
      self::$daoSet['HougongDAO'] = new HougongDAO();
    }
    return self::$daoSet['HougongDAO'];
  }

  /**
   * @return HougongDAO
   */
  public function createWeigameDAO () {
    if (!isset(self::$daoSet['WeigameDAO'])) {
      require_once APP_SRC_DAO_DIR . '/WeigameDAO.php';
      self::$daoSet['WeigameDAO'] = new WeigameDAO();
    }
    return self::$daoSet['WeigameDAO'];
  }
  
  /**
   * @return WebappDAO
   */
  public function createWebappDAO () {
    if (!isset(self::$daoSet['WebappDAO'])) {
      require_once APP_SRC_DAO_DIR . '/WebappDAO.php';
      self::$daoSet['WebappDAO'] = new WebappDAO();
    }
    return self::$daoSet['WebappDAO'];
  }
  
  /**
   * @return TaskDAO
   */
  public function createTaskDAO () {
    if (!isset(self::$daoSet['TaskDAO'])) {
      require_once APP_SRC_DAO_DIR . '/TaskDAO.php';
      self::$daoSet['TaskDAO'] = new TaskDAO();
    }
    return self::$daoSet['TaskDAO'];
  }
}
