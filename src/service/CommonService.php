<?php
/**
 * @desc 公共Service
 */
class CommonService extends BaseService {

	private function getCommonDAO() {
		return DAOFactory::getInstance()->createCommonDAO();
	}

	public function getUid(){
		return $this->getCommonDAO()->findUid();
	}
}
