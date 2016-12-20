<?php
class BaseController extends CController {
  
  public $title = '';
  
  public $keywords = '';
  
  public $description = '';
  
  public $currentPage = '';

  public function getUserService() {
    return ServiceFactory::getInstance()->createUserService();
  }
  public function getUserFortuneService() {
    return ServiceFactory::getInstance()->createUserFortuneService();
  }
  public function getThingService() {
    return ServiceFactory::getInstance()->createThingService();
  }
  public function getGameService() {
    return ServiceFactory::getInstance()->createGameService();
  }
  public function getWebappService() {
    return ServiceFactory::getInstance()->createWebappService();
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
   * @desc 去除数组中的null
   */
  public function checkArray(&$a) {
    if (is_array($a)) {
      foreach ($a as $k => $v) {
        if (is_object($v)) $v = (array) $v;
  
        if (is_array($v)) {
          $this->checkArray($a[$k]);
        } else {
          $len = strlen($v);
          if ($len > 10) $v = "{$v}";
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
      echo $retJsonData;exit;
      header("Content-type: application/json");
      header("Content-Encoding: gzip");
      echo gzencode($retJsonData, 9, FORCE_GZIP);
    } else {
      if ($requestGzip == FALSE) header("Content-Encoding: identity");
      echo $retJsonData;
    }
    exit;
  }
}
?>