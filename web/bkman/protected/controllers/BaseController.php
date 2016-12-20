<?php
class BaseController extends CController {

  //默认分页500页
  const DEFAULT_PAGER_PAGESIZE = 1000;

  //管理后台密码key (账号密码 + key)
  const BKMAN_PASSWORD_KEY = '';

  //系统内置管理员(is_admin=1,拥有最高权限)
  private static $systemAdminId = 1;
  //是否是系统内置管理员
  public $isSystemAdmin = 0;

  public $permissionId = 0;

  public $title = '错误提示';

  public $layout = 'main_bkman';

  public $bkAdminUser = array();

  //管理员用户uid
  public $uid = 0;

  //URIDoAction 默认action
  public $defaultURIDoAction = 'index';

  //获取CommonService
  public function getCommonService() {
    return ServiceFactory::getInstance()->createCommonService();
  }

  //附件中心 Service
  public function getAttachmentService() {
    return ServiceFactory::getInstance()->createAttachmentService();
  }

  //获取AliyunOssService
  public function getAliyunOssService() {
    return ServiceFactory::getInstance()->createAliyunOssService();
  }

  //获取BkAdminService
  public function getBkAdminService() {
    return ServiceFactory::getInstance()->createBkAdminService();
  }

  //获取Userservice
  public function getUserService() {
    return ServiceFactory::getInstance()->createUserService();
  }

  //获取UserFortuneService
  public function getUserFortuneService() {
    return ServiceFactory::getInstance()->createUserFortuneService();
  }

  //获取UserMinesService
  public function getUserMineService() {
    return ServiceFactory::getInstance()->createUserMineService();
  }

  //获取MessageService
  public function getMessageService() {
    return ServiceFactory::getInstance()->createMessageService();
  }

  //获取NoticeService
  public function getNoticeService() {
    return ServiceFactory::getInstance()->createNoticeService();
  }

  //获取UmengpushService
  public function getUmengpushService() {
    return ServiceFactory::getInstance()->createUmengpushService();
  }

  //获取WeixinService
  public function getWeixinService() {
    return ServiceFactory::getInstance()->createWeixinService();
  }
  
  //获取WeixinService
  protected function getGameService () {
    return ServiceFactory::getInstance()->createGameService();
  }
  
  //获取WeigameService
  protected function getWeigameService () {
    return ServiceFactory::getInstance()->createWeigameService();
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
    return (stripos(self::$requestUrl, $uri) === 0) ? TRUE : FALSE;
  }

  //过滤器(必须登录访问)
  public function filters () {
    $controllerId = $this->getId();
    if ($controllerId == 'site') return FALSE;

    $bkAdminUid = $this->getCookie('_bk_admin_uid')->value;
    $bkAdminPassword = $this->getCookie('_bk_admin_accesskey')->value;

    //验证访问uri是否合法
    $loginPage = $this->checkRequestUri('/permission/login');
    $noRedirectPage = !($this->checkRequestUri('/permission/login') || $this->checkRequestUri('/permission/logout') || $this->checkRequestUri('/main/index') || $this->checkRequestUri('/main/error'));
    
    //session会话验证
    $bkAdminUser = array();
    //获取当前管理用户
    if ($bkAdminUid) $bkAdminUser = $this->getUserService()->getBkAdminUserByUid($bkAdminUid);
    if ($bkAdminUser && $bkAdminPassword == md5($bkAdminUser['user_info']['password'] . self::BKMAN_PASSWORD_KEY)) {

      //添加用户默认权限
      $bkAdminUser['permission_ids'] = array_merge($bkAdminUser['permission_ids'], array(9999));

      //是否是最高权限管理员
      $this->isSystemAdmin = ($bkAdminUser['is_admin'] == self::$systemAdminId) ? TRUE : FALSE;
      $this->bkAdminUser = $bkAdminUser;

      if ($noRedirectPage) {
        //权限点验证 & 系统管理员拥有最高权限(is_admin = 1), 如is_admin > 1则拥有全国站点的管理权限(但仍需验证管理权限点)
        $id = $this->getSafeRequest('id', -403);
        if ($id) {
          $this->permissionId = $id;
          if (!$this->isSystemAdmin && !in_array($id, $bkAdminUser['permission_ids'])) {
            $this->redirect($this->getDeUrl('main/error', array('id' => -403)));
          } else {
            //获取权限点
            $permission = $this->getBkAdminService()->getPermission($id);
            if ($id > 0) $this->title = $permission['name'];
          }
        }
      }

      //更新会话session时间（过期时间，2小时）
      $options = array('expire' => 7200);
      $this->setCookie('_bk_admin_uid', $bkAdminUser['uid'], $options);
      $this->setCookie('_bk_admin_accesskey', $bkAdminPassword, $options);

      if ($loginPage) $this->redirect($this->getDeUrl('main/index'));
    } else {
      if (!$loginPage) $this->redirect($this->getDeUrl('permission/login'));
    }
    return FALSE;
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
      return $value ? $value : $defaultValue;

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
  public function outputJsonData($data = array(), $gzip = FALSE) {
    $retJsonData = '';
    $this->checkArray($data);
    $retJsonData = json_encode($data);

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
    exit();
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
      $cookie->expire = $options['expire'] ? time() + $options['expire'] : time() + 86400 * 90;
      Yii::app()->request->cookies[$name] = $cookie;
    }
  }

  /**
   * @desc 删除安全cookie
   */
  public function deleteCookie ($name) {
    if ($name) {
      $cookie = Yii::app()->request->getCookies();
      unset($cookie[$name]);
    }
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
  public function getDeUrl ($rule = '', $params = array(), $start = '?') {
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
    $rule = $rule ? $rule . Yii::app()->getUrlManager()->urlSuffix : '';

    //判断是http://开头的则直接返回
    $deUrl = $rule;
    if (stripos($rule, 'http://') === FALSE) {
      $deUrl = Yii::app()->getRequest()->getHostInfo() . '/' . $rule;
    }
    return $deUrl . $requestParams;
  }

  /**
   * @desc 分页器
   * @param int $count 总记录数
   * @param int $curPage 当前页数
   * @param int $perPageSize 每页显示数
   * @param bool $goPageBtn 是否启用跳转功能
   * @param string $UIClass 样式css class name
   */
  public function getPager ($count, $curPage = 1, $perPageSize = 20, $goPageBtn = TRUE, $UIClass = 'UI-pager') {
    $pagerHtml = $prePageHtml = '';
    if ($count && $perPageSize && ($count > $perPageSize)) {
      $pageAlias = '{page}';

      //当前URL
      $curUrl = Yii::app()->getRequest()->getUrl();
      if (stripos($curUrl, '?') !== FALSE && stripos($curUrl, '?page') === FALSE) {
        $pageParam = "&page={$pageAlias}";
      } else {
        $pageParam = "?page={$pageAlias}";
      }

      //过滤掉page参数(避免重复参数)
      //$pageParamReg = str_replace(array('?', '&'), '[&|?]', $pageParam);
      $pageParamReg = str_replace($pageAlias, '\d+', $pageParam) . '|' . str_replace($pageAlias, '\w+', $pageParam);
      $pageParamReg = Utils::replaceSlashes($pageParamReg);
      $replaceUrl = preg_replace("/({$pageParamReg})/i", $pageParam, $curUrl);
      $curUrl = ($replaceUrl == $curUrl) ? $curUrl . $pageParam : $replaceUrl;

      //总页数
      $totalPages = ceil($count/$perPageSize);
      if ($curPage > $totalPages) $curPage = $totalPages;

      //上一页
      if ($curPage > 1) {
        $prePageUrl = str_replace($pageAlias, $curPage - 1, $curUrl);
        $prePageHtml = "<a class='prev' href='{$prePageUrl}'>上一页</a>";
      }

      //中间页(显示5页)
      $middlePageSize = 5;
      $offset = ($middlePageSize + 1) / 2;
      $middlePageHtml = '';
      for ($i=1; $i<=$totalPages; $i++) {
        if ($totalPages >= $middlePageSize) {
          //上游隐藏页数
          if ($curPage >= $middlePageSize) {
            if ($i == 1) {//第1页, 如 "1..."
              $middlePageUrl = str_replace($pageAlias, $i, $curUrl);
              $middlePageHtml .= "<a href='{$middlePageUrl}'>{$i}...</a>";
            }

            if ($curPage > ($totalPages - $offset)) {
              if ($i < ($totalPages - $middlePageSize)) continue;
            } else {
              if ($i <= ($curPage - $offset)) continue;
            }
          }

          //下游隐藏页数
          if ($curPage < ($totalPages - $offset)) {
            if ($i == $totalPages) {//最后1页, 如 "...20"
              $middlePageUrl = str_replace($pageAlias, $i, $curUrl);
              $middlePageHtml .= "<a href='{$middlePageUrl}'>...{$totalPages}</a>";
            }

            if ($curPage < $middlePageSize) {
              if ($i > ($middlePageSize + 1)) continue;
            } else {
              if ($i > ($curPage + $offset - 1)) continue;
            }
          }
        }

        if ($curPage == $i) {
          $middlePageHtml .= "<strong>{$i}</strong>";
        } else {
          $middlePageUrl = str_replace($pageAlias, $i, $curUrl);
          $middlePageHtml .= "<a href='{$middlePageUrl}'>{$i}</a>";
        }
      }

      if ($curPage < $totalPages) {
        //下一页
        $nextPageUrl = $curPage <= $totalPages ? str_replace($pageAlias, $curPage + 1, $curUrl) : '#page';
        $nextPageHtml = "<a class='next' href='{$nextPageUrl}'>下一页</a>";

        //最后一页
        $lastPageUrl = str_replace($pageAlias, $totalPages, $curUrl);
        $lastPageHtml = "<a class='last' href='{$lastPageUrl}'>{$curPage}/{$totalPages}页</a>";
      }

      //跳页
      $goPageHtml = '';
      if ($goPageBtn) {
        $goPageHtml = "<div class='go'>
        <span>到第</span>
        <input class='txt' type='text' value='{$curPage}' name='pageNumber' onkeyup=\"this.value=this.value.replace(/\D/g,'')\" onafterpaste=\"this.value=this.value.replace(/\D/g,'')\" >
        <span>页</span>
        <button onclick='var turnPageUrl = \"{$curUrl}\".replace(\"{$pageAlias}\", parseInt($(this).parent().find(\"input[name=pageNumber]\").val()));location.href=turnPageUrl' type='button'>确定</button>
      </div>";
      }

      $pagerHtml .= "<div class='{$UIClass}'>
        {$prePageHtml}{$middlePageHtml}{$nextPageHtml}{$lastPageHtml}
        {$goPageHtml}
      </div>";
    }
    return $pagerHtml;
  }
}
?>
