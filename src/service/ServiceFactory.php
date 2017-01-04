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

}
