<?php
class BaseController extends CController {

  //html页面title
  public $title = '';

  //当前用户信息
  public $currentUser = array();

  public $defaultURIDoAction = '';

  //防洪间隔时间 (秒)
  const FLOOD_LIMIT_TIME = 10;

  const WANZHU_SUUID_COOKIE_NAME = "_wanzhu_uuid_v1_0_";

  public function getCommonService () {
    return ServiceFactory::getInstance()->createCommonService();
  }
  public function getUserService () {
    return ServiceFactory::getInstance()->createUserService();
  }
  public function getUserFortuneService () {
    return ServiceFactory::getInstance()->createUserFortuneService();
  }
  public function getGameService () {
    return ServiceFactory::getInstance()->createGameService();
  }
  public function getWeixinService() {
    return ServiceFactory::getInstance()->createWeixinService();
  }
  public function getWxpayService() {
    return ServiceFactory::getInstance()->createWxpayService();
  }
  public function getGearmanService () {
    return ServiceFactory::getInstance()->createGearmanService();
  }
  public function getHougongService () {
    return ServiceFactory::getInstance()->createHougongService();
  }
  public function getWebappService () {
    return ServiceFactory::getInstance()->createWebappService();
  }
  public function getThingService () {
    return ServiceFactory::getInstance()->createThingService();
  }
  public function getAttachmentService() {
    return ServiceFactory::getInstance()->createAttachmentService();
  }
  public function getUserMineService() {
    return ServiceFactory::getInstance()->createUserMineService();
  }
  public function getMessageService() {
    return ServiceFactory::getInstance()->createMessageService();
  }

  /**
   * @desc 验证访问uri是否合法
   */
  public static $requestUrl;
  public function checkRequestUri ($uri) {
    if (!self::$requestUrl) {
      self::$requestUrl = str_replace(Yii::app()->getUrlManager()->urlSuffix, '', Yii::app()->getRequest()->getUrl());
      self::$requestUrl = preg_replace("/\?.*?/Ui", "", self::$requestUrl);
    }
    return (stripos(self::$requestUrl, '/' . $uri) === 0) ? TRUE : FALSE;
  }
  
  /**
   * @desc 拦截器(client_id, client_secret验证)及初始化参数
   * @return boolean
   */
  public function filters () {
    if ($this->checkRequestUri('usercenter/wxpayrecharge')) return FALSE;
    
    //来自微信授权
    $cookieName = self::WANZHU_SUUID_COOKIE_NAME;
    $suuid = $this->getCookie($cookieName);
    
    //来自app授权
    $isInApp = Utils::isFromWanZhu() || $this->getCookie("fromapp");
    if ($isInApp) {
      $headers = HttpClient::getAllHeaders();
      if ($headers['sid'] && $headers['sid'] != $suuid) $suuid = $headers['sid'];
      $this->setCookie("fromapp", TRUE);
    }

    if ($suuid) {
      $suuidDecoded = base64_decode($suuid);
      list($clientId, $uid, $authId) = explode('|', $suuidDecoded);
      //验证用户会话密匙$suuid && 初始化currentUser
      $authUserInfo = $this->getUserService()->authUserSessionToken($clientId, $uid, $authId);
      if ($isInApp) {
        $this->currentUser = $authUserInfo['uid'] ? $this->getUserService()->getUserByUid($authUserInfo['uid'], TRUE) : array();
      } else {
        $this->currentUser = $authUserInfo['uid'] ? $this->getGameService()->getWanzhuWxUserInfo($authUserInfo['uid']) : array();
      }
      if ($this->currentUser) {
         //获取用户状态数或初始化
        $userStatus = $this->getUserFortuneService()->getUserFortuneStatusByUid($authUserInfo['uid']);

        if (!$userStatus) {
          $userStatus = $this->getUserFortuneService()->initUserFortuneStatus($authUserInfo);
        }

        $this->currentUser['s_uuid'] = $suuid;
        $this->currentUser['status'] = $userStatus;
        $this->deleteCookie($cookieName);
        $this->setCookie($cookieName, $suuid);
      }
    }
    
    //验证用户是否已登录
    if (!$this->currentUser) {
      $this->checkUserLogin(urlencode($this->getCurrentUrl()));
      exit;
    }
  }

  /**
   * @desc 获取当前完整url
   */
  private function getCurrentUrl () {
    $uri = Yii::app()->getRequest()->getUrl();
    $uri = preg_replace('/(\?|\&)?(_s_uuid_|sn|t)=[^\&]{0,}/i', '', $uri);
    return Yii::app()->getRequest()->getHostInfo() . $uri;
  }

  /**
   * @desc 用户登录验证
   */
  public function checkUserLogin ($rf) {
    //state按小时更新
    $key = date('YmdH');
    $state = Utils::generateCSRFSecret($key);

    $openCallbackUrl = $this->getDeUrl(WEB_QW_APP_WX_DOMAIN . '/wx/authorize', array('rf' => $rf));
    $openPlatformUrl = $this->getDeUrl(Yii::app()->params['openWeixinWapUrl'], array('state' => $state), '&', FALSE);
    $openPlatformUrl = str_replace('{redirect_uri}', urlencode($openCallbackUrl), $openPlatformUrl);
    $this->redirect($openPlatformUrl);
    exit;
  }

  /**
   * @desc 获取参数并进行安全过滤
   */
  public function getSafeRequest ($param, $defaultValue = '', $method = 'GET', $type = 'string') {
    if (!$method || APP_DEBUG) $method = 'GET';
    if ($method == 'GET') {
      $value = Yii::app()->request->getParam($param, $defaultValue);
    } else {
      $value = Yii::app()->request->getPost($param, $defaultValue);
    }

    //参数类型验证&安全过滤
    //array OR object
    if ($type == 'array') {
      $tmp = array();
      if ($value) {
        foreach ($value as $k => $v) {
          $tmpV = trim($v);
          if (!$tmpV) continue;
          $tmp[$k] = $tmpV;
        }
      }
      $value = $tmp;
      return $value;

    //string
    } elseif ($type == 'string') {
      $value = trim($value);
      return $value ? Utils::filterString($value) : $defaultValue;
      
    //json 参数类型验证
    } elseif ($type == 'json') {
      return $value ? json_decode(trim($value), TRUE) : $value;
      
    //int
    } elseif ($type == 'int') {
      $value = trim($value);
      return (is_numeric($value)) ? $value : $defaultValue;
    }
  }

  /**
   * @desc 获取安全cookie值
   */
  public function getCookie ($name) {
    if (!$name) return '';
    return Yii::app()->request->cookies[$name];
  }

  /**
   * @desc 设置安全cookie值
   * @return 默认过期时间 30天
   */
  public function setCookie ($name, $value, $options = array()) {
    if ($name && $value) {
      $cookie = new CHttpCookie($name, $value, $options);
      $cookie->expire = $options['expire'] ? time() + $options['expire'] : time() + 86400 * 30;
      $cookie->domain = Utils::getHostDomainName();
      Yii::app()->request->cookies->add($name, $cookie);
    }
  }

  /**
   * @desc 删除安全cookie
   */
  public function deleteCookie ($name) {
    if ($name) {
      $options = array('domain' => Utils::getHostDomainName());
      Yii::app()->request->cookies->remove($name, $options);
    }
  }

  /**
   * @desc get Referrer Url
   */
  public function getReferrerUrl($routeUrl = '', $filterUrl = '') {
    $refererUrl = $this->getSafeRequest('refererUrl');
    if (!$refererUrl) {
      $refererUrl = Yii::app()->getRequest()->getUrlReferrer();
    }

    //过滤当前自身的url
    if ($refererUrl) {
      $currentUri = Yii::app()->getRequest()->getUrl();
      $currentUri = str_replace(Yii::app()->getUrlManager()->urlSuffix, '', $currentUri);
      $currentUri = preg_replace('/\?(.*)/i', '', $currentUri);
      if (stripos($refererUrl, $currentUri)) $refererUrl = '';
      if (stripos($refererUrl, $filterUrl)) $refererUrl = '';
    }

    //读取默认url
    if (!$refererUrl) {
      $refererUrl = $this->getDeUrl($routeUrl);
    }
    return urlencode(urldecode($refererUrl));
  }

  /**
   * @desc 获取Url(支持参数拼接)
   */
  public function getDeUrl ($rule = '', $params = array(), $start = '?', $suffixed = TRUE) {
    if ($rule == '#') {
      return 'javascript:void(0);';
    }

    $requestParams = '';
    if ($params) {
      foreach ($params as $id => $param) {
        if (!$requestParams) {
          $requestParams .= $start . ($id . '=' . $param);
        } else {
          $requestParams .= '&' . ($id . '=' . $param);
        }
      }
    }

    $suffix = $suffixed ? Yii::app()->getUrlManager()->urlSuffix : '';
    $rule = $rule ? $rule . $suffix : '';

    //判断是http://开头的则直接返回
    $deUrl = $rule;
    if (stripos($rule, 'http://') === FALSE && stripos($rule, 'https://') === FALSE) {
      $deUrl = Yii::app()->getRequest()->getHostInfo() . '/' . $rule;
    }
    return $deUrl . $requestParams;
  }

  /**
   * @desc 获取路由前的URI
   * @return string
   */
  public function getBeforeRoutedURI () {
    $URI = Yii::app()->getRequest()->getUrl();
    $URI = str_replace(Yii::app()->getUrlManager()->urlSuffix, '', $URI);
    $routeRules = Yii::app()->getUrlManager()->rules;
    if ($routeRules) {

      foreach ($routeRules as $routeRule => $oriURI) {
        $pattern = '\/' . str_replace('/', '\/', preg_replace("/<\w+:\((.*)\)>/Ui", '$1', $routeRule));
        if (preg_match("/{$pattern}/Ui", $URI)) {
          $URI = $oriURI;
          break;
        }

      }
    }

    return $URI;
  }

  /**
   * @desc 获取当前处理action方法
   * @param int $position URI 开始截取的"/"位置
   * @return bool $onlyReturn
   */
  public function getURIDoAction ($class, $URIPosition = 1, $onlyReturn = FALSE) {
    $action = '';

    //解析URI
    $URI = $this->getBeforeRoutedURI();
    $params = explode('/', $URI);
    foreach ($params as $k => $param) {
      if ($k > $URIPosition) {
        $action .= ucfirst($param);
      }
    }

    //验证$action, or Error Notice
    $action = str_replace(Yii::app()->getUrlManager()->urlSuffix, '', $action);
    $action = preg_replace('/\?(.*)/i', '', $action);

    //设置默认action
    if (!$action) $action = $this->defaultURIDoAction;

    //仅返回action name
    if ($onlyReturn) return $action;

    //plus do prefix
    $action = 'do' . $action;

    if (is_object($class) && method_exists($class, $action)) {
      return $action;
    }
    //错误处理
    if (method_exists($class, 'errorRedirect')) {
      $class->errorRedirect();
    } else {
      Yii::app()->runController('site/error');
    }
    die();
  }

  /**
   * @desc 去除数组中的null
   */
  public function checkArray(&$a) {
    if (is_array($a)) {
      foreach ( $a as $k => $v ) {
        if (is_array($v)) {
          $this->checkArray($a[$k]);
        } else {
          $a[$k] = $v === NULL ? "" : $v;
        }
      }
    }
  }

  /**
   * @desc 输出json格式的数据
   * @param int $code 返回码
   * @param array $data 返回数据
   * @param bool $gzip 是否使用gzip压缩输出
   */
  public function outputJsonData($code = 0, $data = array(), $gzip = FALSE) {
    //返回数据（默认code、description）
    $retJsonData = '';
    $code = is_numeric($code) ? $code : 0;
    $defaultData['code'] = $code;

    $retData = array_merge($defaultData, $data);
    $this->checkArray($retData);
    $retJsonData = json_encode($retData);

    //判断客户端是否是GZIP请求
    preg_match('/gzip/', $_SERVER['HTTP_ACCEPT_ENCODING'], $matches);
    $requestGzip = $matches[0] ? TRUE : FALSE;
    if ($requestGzip == TRUE || $gzip == TRUE) {
      header("Content-type: application/json");
      header("Content-Encoding: gzip");
      echo gzencode($retJsonData, 9, FORCE_GZIP);
    } else {
      if ($requestGzip == FALSE) header("Content-Encoding: identity");
      echo $retJsonData;
    }
    exit;
  }

  public function json($code = 0, $msg = '',$extra = '', $gzip = FALSE) {
    //返回数据（默认code、description）
    $data = array();
    $data['code'] = is_numeric($code) ? $code : 0;
    $data['desc'] = $msg;
    $data['extra'] = $extra;

    $retJsonData = json_encode($data);

    //判断客户端是否是GZIP请求
    preg_match('/gzip/', $_SERVER['HTTP_ACCEPT_ENCODING'], $matches);
    $requestGzip = $matches[0] ? TRUE : FALSE;
    $requestGzip = FALSE;
    header("Content-type: application/json");
    if(APP_DEBUG) {
      echo $retJsonData;
      exit;
    }
    if ($requestGzip == TRUE || $gzip == TRUE) {
      header("Content-Encoding: gzip");
      echo gzencode($retJsonData, 9, FORCE_GZIP);
    } else {
      if ($requestGzip == FALSE) header("Content-Encoding: identity");
      echo $retJsonData;
    }
    exit;
  }
}

