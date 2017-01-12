<?php

require_once APP_SRC_BASE_DIR . '/BaseConstant.php';
require_once APP_SRC_BASE_DIR . '/BaseService.php';
require_once APP_SRC_DAO_DIR . '/DAOFactory.php';

final class ServiceFactory {
	//单例模式
	protected static $instance = null;
	protected static $services = array ();
	public static function getInstance() {
		if (! self::$instance) {
			self::$instance = new ServiceFactory ();
		}
		return self::$instance;
	}

	/**
	 * @return CommonService
	 */
	public function createCommonService() {
		if (! self::$services ["CommonService"]) {
			require_once APP_SRC_SERVICE_DIR . '/CommonService.php';
			self::$services ["CommonService"] = new CommonService ();
		}
		return self::$services ["CommonService"];
	}

	/**
	 * @return CommonService
	 */
	public function createBkAdminService() {
		if (! self::$services ["BkAdminService"]) {
			require_once APP_SRC_SERVICE_DIR . '/BkAdminService.php';
			self::$services ["BkAdminService"] = new BkAdminService ();
		}
		return self::$services ["BkAdminService"];
	}

	/**
	 * @return ArticleService
	 */
	public function createArticleService() {
		if (! self::$services ["ArticleService"]) {
			require_once APP_SRC_SERVICE_DIR . '/ArticleService.php';
			self::$services ["ArticleService"] = new ArticleService ();
		}
		return self::$services ["ArticleService"];
	}

}
