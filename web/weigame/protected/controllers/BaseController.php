<?php
class BaseController extends CController {
  
  //防洪间隔时间 (秒)
  const FLOOD_LIMIT_TIME = 10;
  
  //流量限制开头
  const LIMITED_TRAFFIC = FALSE;
  
  //html页面title
  public $title = '';
  
  //当前用户信息
  public $currentUser = array();
  
  //百度统计code
  public $baiduTongjiCode = '';
  
  //Google统计code
  public $googleAnalyticsCode = '';

  //链接来源
  public $fromkey = '';
  
  //游戏类型 1.你懂我么 2.测试付费
  public $type = '';
  
  //分组
  public $level = '';
  
  //当前域名
  public $curDomain = '';
  
  //域名分类
  public $domainCategory = 1;

  //jssdk域名分组
  const JSSDK_LEVEL = 5;

  //支付域名分组
  const PAY_LEVEL = 3;

  //支付域名分组
  const TAOZI_LEVEL = 4;

  //JSSDK公众号域名分类type值
  const JSSDK_MP_TYPE = 0;
  
  //授权公众号域名分类type值
  const SUUID_MP_TYPE = 1;
  
  //支付公众号域名分类type值
  const PAY_MP_TYPE = 2;

  //授权cookie name
  const SUUID_TYPE_COOKIE_NAME = '_s_uuid_type_v1_0_';

  //授权同步会话参数
  public $loginTypeSuuidParams = '';

  public function getCommonService () {
    return ServiceFactory::getInstance()->createCommonService();
  }
  
  public function getUserService () {
    return ServiceFactory::getInstance()->createUserService();
  }

  public function getWeigameService () {
    return ServiceFactory::getInstance()->createWeigameService();
  }

  public function getWxpayService() {
    return ServiceFactory::getInstance()->createWxpayService();
  }

  private function getCurrentLoginType () {
    $this->fromkey = $this->getSafeRequest('fromkey');
    if ($this->fromkey) {
      $game = $this->getWeigameService()->getKnowGameById($this->fromkey);
      if (!$game) $this->redirect($this->getDeUrl('site/error', array('from' => 'checkurl')));
    }  
    return $game['pay_mpid'];
  }

  private function isNotIsolateUri () {
    $isNotIsolateUri = FALSE;
    $curUrl = Yii::app()->getRequest()->getUrl();
    foreach (Yii::app()->params['paycallbackconfig'] as $uri) {
      if (stripos($curUrl, $uri) !== FALSE) {
        $isNotIsolateUri = TRUE;
        break;
      }
    }
    return $isNotIsolateUri;
  }

  //获取QaInfo配置信息
  public function getQaInfo () {
    $qaInfo = Yii::app()->params['qaInfo'] ? Yii::app()->params['qaInfo'] : array();
    if (Utils::isFromWeixin()) {
      $qaInfo = $qaInfo['weixin'];
    } else if (Utils::isFromQQ()) {
      $qaInfo = $qaInfo['QQ'];
    } else {
      $qaInfo = $qaInfo['weibo'];
    }  
    return $qaInfo;
  }

  /**
   * @desc 拦截器(client_id, client_secret验证)及初始化参数
   * @return boolean
   */
  public function filters () {
    //域名分组级别隔离
    if ($this->getId() == 'site') return FALSE;
    $this->isolateDomains();
    //获取用户会话cookie
    $cookieName = self::SUUID_TYPE_COOKIE_NAME . $this->getCurrentLoginType();
    $suuid = $this->getCookie($cookieName);
    //Get Request过来的用户授权，需验证sn，且7天内仅一次有效
    $suuidFromRequest = $this->getSafeRequest('_s_uuid_');

    if ($suuidFromRequest) {
      if (APP_DEBUG) {
        $suuid = $suuidFromRequest;
      } else {
        $sn = $this->getSafeRequest('sn');
        $checkSn = Utils::generateCSRFSecret($this->getSafeRequest('t') . $suuidFromRequest);
        $isUsed = $this->getCommonService()->getFromMemcache($sn);
        if ($sn == $checkSn && !$isUsed) {
          $this->getCommonService()->setToMemcache($sn, TRUE, 7*86400);
          $suuid = $suuidFromRequest;
        }
      }
    }

    //验证用户会话$suuid
    if ($suuid) {
      $suuidDecoded = base64_decode($suuid);
      list($clientId, $uid, $authId) = explode('|', $suuidDecoded);
      $authUserInfo = $this->getUserService()->authUserSessionToken($clientId, $uid, $authId);
      if ($authUserInfo['uid']) {
        $authUserInfo = $this->getUserService()->getUserWeixinInfo($authUserInfo['uid']);
        $this->currentUser = $authUserInfo;

        $this->deleteCookie($cookieName);
        $this->setCookie($cookieName, $suuid);
      } else {
        $this->deleteCookie($cookieName);
      }
    }
    //GET参数有_s_uuid_时，强制刷新去掉该参数
    if ($suuidFromRequest) {
      $url = Yii::app()->getRequest()->getUrl();
      $url = preg_replace('/_s_uuid_=[^\&]{0,}/i', '', $url);
      $this->redirect(Yii::app()->getRequest()->getHostInfo() . $url);
      exit;
    }
    
    //初始google analytics
    $qaInfo = $this->getQaInfo();
    $this->googleAnalyticsCode = $qaInfo['google_analytics'];
  }

  /**
   * @desc 域名分组级别隔离（仅当前级别下的域名可相互访问，不同级别的域名不能访问）
   * @param string ex 参数值不为空时，则再次跳转到相应域名分组级别下
   * @see 仅验证到二级域名
   * @see 微信支付和支付回调url不作验证
   */
  public function isolateDomains () {
    //隔离检测
    $this->level = $this->getSafeRequest('level');
    $this->type = $this->getSafeRequest('type');
    if ($this->isNotIsolateUri()) return FALSE;
    if ($this->level !== NULL) {
      //解析并获取二级域名
      $curHostName = parse_url(Yii::app()->getRequest()->getHostInfo())['host'];
      $curHostNameExp = explode('.', $curHostName);
      $curHostNameExpCount = count($curHostNameExp);
      $curDomain = '';
      if ($curHostNameExpCount > 3) {
        foreach ($curHostNameExp as $k => $v) {
          if ($k == 0) continue;
          if ($curDomain) $curDomain .= '.';
          $curDomain .= $v;
        }
      } else {
        $curDomain = $curHostName;
      }
      $this->curDomain = $curDomain;
      
      //不同分组域名禁止相互访问链接
      $domainInfo = $this->getWeigameService()->getDomainByAddress($curDomain);
      $this->domainCategory = $domainInfo['category'];
      $bool = TRUE;
      if ($domainInfo) { 
        if ($domainInfo['level'] == $this->level) {
          //普通域名
          $bool = FALSE;
        } elseif ($domainInfo['level'] == self::PAY_LEVEL || $domainInfo['level'] == self::JSSDK_LEVEL) {
          //支付域名,jssdk域名
          $mpDomainInfo = $this->getWeigameService()->getWeigameMpInfoByDomainAddress($curDomain);
          $curGame = $this->getCurDomainGame();
          if ($mpDomainInfo['mp_id'] == $curGame['pay_mpid'] || ($curGame['jssdk_mpids'] && in_array($mpDomainInfo['mp_id'], json_decode($curGame['jssdk_mpids'], TRUE)))) {
            $bool = FALSE;
          }
        }
      }
      if ($bool) $this->redirect($this->getDeUrl('site/error', array('from' => 'checkurl')));
    }
  }

  /**
   * @desc 获取当前域名分组级别和公众号mpid
   */
  public function getCurDomainGame () {
    $this->fromkey = $this->getSafeRequest('fromkey');
    if ($this->fromkey && $this->type) {
      if ($this->type == 1) $game = $this->getWeigameService()->getKnowGameById($this->fromkey);
      if (!$game) $this->redirect($this->getDeUrl('site/error', array('from' => 'checkurl')));
    }
    return $game;
  }
  
  /**
   * @desc 获取同步授权会话suuid的url参数串
   */
  public function getLoginTypeSuuidParams () {
    $loginTypeSuuidParams = '';
    $typeCookieName = self::SUUID_TYPE_COOKIE_NAME . $this->getCurrentLoginType();
    $suuidType = $this->getCookie($typeCookieName);
    if ($suuidType) {
      $time = time();
      $sn = Utils::generateCSRFSecret($time . $suuidType);
      $loginTypeSuuidParams = "sn={$sn}&t={$time}&_s_uuid_={$suuidType}";
    }
    return $loginTypeSuuidParams;
  }
  
  /**
   * @desc 获取游戏集合页跳转链接
   * @param array $game
   * @param string $rdFrom redirectJihe(集合页)(默认)，redirectCeshi(测试页)，redirectPCeshi(付费测试页)，
   */
  //$rdFromDomainLevels值同DomainConfig
  private static $rdFromDomainLevels = array('redirectJihe' => 6, 'redirectCeshi' => 2, 'redirectPCeshi' => 10);
  public function getGameRidirectUrl ($game, $rdFrom = 'redirectJihe') {
    if ($game && stripos($game['game_url'], 'http://') === FALSE && stripos($game['game_url'], 'https://') === FALSE) {
      $game['game_url'] = "/{$rdFrom}.html?level={$game['group_level']}&category={$game['category']}&tt=" . urlencode($game['game_url']);
    }
    return $game['game_url'];
  }
  
  /**
   * @desc 获取百度统计code
   * @see 数字id对应DomainConfig（默认: 0）
   */
 public function getBaiduTongjiCode ($level = '') {
    if ($level) {
      //读取当前域名分组级别
      $domainGroup = $this->getWeigameService()->getDomainGroupBylevel($level);
      $baidu_code = $domainGroup['baidu_code'];
    }
    return $baidu_code;
  }

  //获取随机title
  public function getRandShareTitle($nickname, $randTitles) {
    $randKey = mt_rand(0, (count($randTitles['rands']) - 1));
    $randTitle = str_replace('**', $nickname, $randTitles['rands'][$randKey]);
    return $randTitle ? $randTitle : $randTitles['default'];
  }

  //去掉重复项及无效项
  public function uniqueQaContent ($qaContent) {
    $uniqueQaContent = array();
    $qaContentExp = array_unique(explode('|', $qaContent));
    if ($qaContentExp) {
      foreach ($qaContentExp as $content) {
        if (stripos($content, '_') === FALSE) continue;
        $uniqueQaContent[] = $content;
      }
    }

    return $uniqueQaContent;
  }

  //用户登录验证（默认type=0）
  public function checkUserLogin ($rf, $mpid) {
    //state按小时更新
    $key = date('YmdH');
    $state = Utils::generateCSRFSecret($key);
    $openPlatformUrl = '';
    $openCallbackUrl = '';
    $openWeixinWapUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid={appid}&redirect_uri={redirect_uri}&response_type=code&scope=snsapi_userinfo';
    $mpWecahtInfo = $this->getWeigameService()->getWeigameMpinfoById($mpid);
    $domains = $this->getWeigameService()->getWeigameMpDomainsByMpid($mpid, self::SUUID_MP_TYPE);
    $wxAuthDomain = $domains[0]['domain_address'];
    $openPlatformUrl = $this->getDeUrl($openWeixinWapUrl, array('state' => $state), '&', FALSE);
    $openCallbackUrl = $this->getDeUrl("http://{$wxAuthDomain}/open/weixin", array('mpid' => $mpid,'rf' => $rf));
    $openPlatformUrl = str_replace(array('{redirect_uri}','{appid}'), array(urlencode($openCallbackUrl), $mpWecahtInfo['app_id']), $openPlatformUrl);
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
   * @desc 获取当前处理action方法
   * @param int $position URI 开始截取的"/"位置
   * @return bool $onlyReturn
   */
  public function getURIDoAction ($class, $URIPosition = 1, $onlyReturn = FALSE) {
    $action = '';
    
    //解析URI
    $URI = Yii::app()->getRequest()->getUrl();
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
      echo $retJsonData;
      //header("Content-Encoding: gzip");
      //echo gzencode($retJsonData, 9, FORCE_GZIP);
    } else {
      if ($requestGzip == FALSE) header("Content-Encoding: identity");
      echo $retJsonData;
    }
    exit;
  }
}
