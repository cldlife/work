<?php
class BaseController extends CController {

	public function getCommonService() {
		return ServiceFactory::getInstance ()->createCommonService ();
	}

	/**
	 * 拦截器(client_id, client_secret验证)及初始化参数
	 *
	 * @return boolean
	 */
	public function filters() {
		// 域名分组级别隔离
		if ($this->getId () == 'site')
			return FALSE;
	}

	// 获取随机title
	public function getRandShareTitle($nickname, $randTitles) {
		$randKey = mt_rand ( 0, (count ( $randTitles ['rands'] ) - 1) );
		$randTitle = str_replace ( '**', $nickname, $randTitles ['rands'] [$randKey] );
		return $randTitle ? $randTitle : $randTitles ['default'];
	}

	// 去掉重复项及无效项
	public function uniqueQaContent($qaContent) {
		$uniqueQaContent = array ();
		$qaContentExp = array_unique ( explode ( '|', $qaContent ) );
		if ($qaContentExp) {
			foreach ( $qaContentExp as $content ) {
				if (stripos ( $content, '_' ) === FALSE)
					continue;
				$uniqueQaContent [] = $content;
			}
		}

		return $uniqueQaContent;
	}

	/**
	 * 获取参数并进行安全过滤
	 */
	public function getSafeRequest($param, $defaultValue = '', $method = 'GET', $type = 'string') {
		if (! $method || APP_DEBUG)
			$method = 'GET';
		if ($method == 'GET') {
			$value = Yii::app ()->request->getParam ( $param, $defaultValue );
		} else {
			$value = Yii::app ()->request->getPost ( $param, $defaultValue );
		}

		// 参数类型验证&安全过滤
		// array OR object
		if ($type == 'array') {
			$tmp = array ();
			if ($value) {
				foreach ( $value as $k => $v ) {
					$tmpV = trim ( $v );
					if (! $tmpV)
						continue;
					$tmp [$k] = $tmpV;
				}
			}
			$value = $tmp;
			return $value;

			// string
		} elseif ($type == 'string') {
			$value = trim ( $value );
			return $value ? Utils::filterString ( $value ) : $defaultValue;

			// int
		} elseif ($type == 'int') {
			$value = trim ( $value );
			return (is_numeric ( $value )) ? $value : $defaultValue;
		}
	}

	/**
	 * 获取安全cookie值
	 */
	public function getCookie($name) {
		if (! $name)
			return '';
		return Yii::app ()->request->cookies [$name];
	}

	/**
	 * 设置安全cookie值
	 *
	 * @return 默认过期时间 30天
	 */
	public function setCookie($name, $value, $options = array()) {
		if ($name && $value) {
			$cookie = new CHttpCookie ( $name, $value, $options );
			$cookie->expire = $options ['expire'] ? time () + $options ['expire'] : time () + 86400 * 30;
			$cookie->domain = Utils::getHostDomainName ();
			Yii::app ()->request->cookies->add ( $name, $cookie );
		}
	}

	/**
	 * 删除安全cookie
	 */
	public function deleteCookie($name) {
		if ($name) {
			$options = array (
					'domain' => Utils::getHostDomainName ()
			);
			Yii::app ()->request->cookies->remove ( $name, $options );
		}
	}

	/**
	 * get Referrer Url
	 */
	public function getReferrerUrl($routeUrl = '', $filterUrl = '') {
		$refererUrl = $this->getSafeRequest ( 'refererUrl' );
		if (! $refererUrl) {
			$refererUrl = Yii::app ()->getRequest ()->getUrlReferrer ();
		}

		// 过滤当前自身的url
		if ($refererUrl) {
			$currentUri = Yii::app ()->getRequest ()->getUrl ();
			$currentUri = str_replace ( Yii::app ()->getUrlManager ()->urlSuffix, '', $currentUri );
			$currentUri = preg_replace ( '/\?(.*)/i', '', $currentUri );
			if (stripos ( $refererUrl, $currentUri ))
				$refererUrl = '';
			if (stripos ( $refererUrl, $filterUrl ))
				$refererUrl = '';
		}

		// 读取默认url
		if (! $refererUrl) {
			$refererUrl = $this->getDeUrl ( $routeUrl );
		}
		return urlencode ( urldecode ( $refererUrl ) );
	}

	/**
	 * 获取Url(支持参数拼接)
	 */
	public function getDeUrl($rule = '', $params = array(), $start = '?', $suffixed = TRUE) {
		if ($rule == '#') {
			return 'javascript:void(0);';
		}

		$requestParams = '';
		if ($params) {
			foreach ( $params as $id => $param ) {
				if (! $requestParams) {
					$requestParams .= $start . ($id . '=' . $param);
				} else {
					$requestParams .= '&' . ($id . '=' . $param);
				}
			}
		}

		$suffix = $suffixed ? Yii::app ()->getUrlManager ()->urlSuffix : '';
		$rule = $rule ? $rule . $suffix : '';

		// 判断是http://开头的则直接返回
		$deUrl = $rule;
		if (stripos ( $rule, 'http://' ) === FALSE && stripos ( $rule, 'https://' ) === FALSE) {
			$deUrl = Yii::app ()->getRequest ()->getHostInfo () . '/' . $rule;
		}
		return $deUrl . $requestParams;
	}

	/**
	 * 获取当前处理action方法
	 *
	 * @param int $position
	 *        	URI 开始截取的"/"位置
	 * @return bool $onlyReturn
	 */
	public function getURIDoAction($class, $URIPosition = 1, $onlyReturn = FALSE) {
		$action = '';

		// 解析URI
		$URI = Yii::app ()->getRequest ()->getUrl ();
		$params = explode ( '/', $URI );
		foreach ( $params as $k => $param ) {
			if ($k > $URIPosition) {
				$action .= ucfirst ( $param );
			}
		}

		// 验证$action, or Error Notice
		$action = str_replace ( Yii::app ()->getUrlManager ()->urlSuffix, '', $action );
		$action = preg_replace ( '/\?(.*)/i', '', $action );

		// 设置默认action
		if (! $action)
			$action = $this->defaultURIDoAction;

			// 仅返回action name
		if ($onlyReturn)
			return $action;

			// plus do prefix
		$action = 'do' . $action;

		if (is_object ( $class ) && method_exists ( $class, $action )) {
			return $action;
		}

		// 错误处理
		if (method_exists ( $class, 'errorRedirect' )) {
			$class->errorRedirect ();
		} else {
			Yii::app ()->runController ( 'site/error' );
		}
		die ();
	}

	/**
	 * 去除数组中的null
	 */
	public function checkArray(&$a) {
		if (is_array ( $a )) {
			foreach ( $a as $k => $v ) {
				if (is_array ( $v )) {
					$this->checkArray ( $a [$k] );
				} else {
					$a [$k] = $v === NULL ? "" : $v;
				}
			}
		}
	}

	/**
	 * 输出json格式的数据
	 *
	 * @param int $code
	 *        	返回码
	 * @param array $data
	 *        	返回数据
	 * @param bool $gzip
	 *        	是否使用gzip压缩输出
	 */
	public function outputJsonData($code = 0, $data = array(), $gzip = FALSE) {
		// 返回数据（默认code、description）
		$retJsonData = '';
		$code = is_numeric ( $code ) ? $code : 0;
		$defaultData ['code'] = $code;

		$retData = array_merge ( $defaultData, $data );
		$this->checkArray ( $retData );
		$retJsonData = json_encode ( $retData );

		// 判断客户端是否是GZIP请求
		preg_match ( '/gzip/', $_SERVER ['HTTP_ACCEPT_ENCODING'], $matches );
		$requestGzip = $matches [0] ? TRUE : FALSE;
		if ($requestGzip == TRUE || $gzip == TRUE) {
			header ( "Content-type: application/json" );
			echo $retJsonData;
			// header("Content-Encoding: gzip");
			// echo gzencode($retJsonData, 9, FORCE_GZIP);
		} else {
			if ($requestGzip == FALSE)
				header ( "Content-Encoding: identity" );
			echo $retJsonData;
		}
		exit ();
	}
}
