<?php
/**
* 文件上传类
* @date: 2017年4月11日 上午11:46:39
* @author: dong
*/
class Upload {
	private static $path = '/upload';
	private $allowtype = array('jpg','gif','png'); //设置限制上传文件的类型
    private $maxsize = 2097152;           //限制文件上传大小（字节）
    private $israndname = true;           //设置是否随机重命名文件， false不随机

    /**
    * 设置上传类配置
    * @param: array('path'=> , 'allowtype' => , 'maxsize' => , 'israndname' => )
    * @return: boolean
    */
    static public function setConfig($params){
		return $params ? self::set($params) : FALSE;
    }

    /**
    * 用于设置成员属性（$path, $allowtype,$maxsize, $israndname）
    * @param: array('path'=> , 'allowtype' => , 'maxsize' => , 'israndname' => )
    * @return:boolean
    */
    static private function set($param){
		if ($param) {
			foreach ($param as $key => $value) {
				$key = strtolower($key);
				if (!property_exists(__CLASS__, $key)) return FALSE;
				self::$$key = $value;
			}
			return TRUE;
		}
		return FALSE;
    }

    static  private function errorMsg(){

    }

    /**
    * 文件上传
    * @param: filename
    * @return:
    */
    static public function uploadfile($filename){
		if (!filename) return;
    }
}