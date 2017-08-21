<?php
class IndexController extends BaseController {

	/**
	 * 微信游戏集合首页
	 */
	public function actionIndex() {
		var_dump(Upload::setConfig(array('path'=>1)));
		echo 222;
	}
}