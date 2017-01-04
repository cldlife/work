<?php
class IndexController extends BaseController {

	/**
	 * 微信游戏集合首页
	 */
	public function actionIndex() {
		$a = $this->getCommonService()->getUid();
		var_dump($a);
	}
}