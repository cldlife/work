<?php
class BaseController extends CController {

  //安全验证过期时间
  const SECRET_EXPIRE_TIME = 15;

  //防洪间隔时间 (秒)
  const FLOOD_LIMIT_TIME = 10;

  //全局变量属性
  public $globalAttributions = array();
  
  //当前请求api的client_id
  public $currentClientId = '';

  public $currentUser = array();
  
  public $currentAppVersion = '';
  
  public $network = '';
  
  public $system = '';
  
  //获取Service
  public function getCommonService() {
    return ServiceFactory::getInstance()->createCommonService();
  }
  public function getBkAdminService() {
    return ServiceFactory::getInstance()->createBkAdminService();
  }
  public function getAttachmentService() {
    return ServiceFactory::getInstance()->createAttachmentService();
  }
  public function getUserService() {
    return ServiceFactory::getInstance()->createUserService();
  }
  public function getUserFortuneService() {
    return ServiceFactory::getInstance()->createUserFortuneService();
  }
  public function getMessageService() {
    return ServiceFactory::getInstance()->createMessageService();
  }
  public function getThingService() {
    return ServiceFactory::getInstance()->createThingService();
  }
  public function getUserMineService() {
    return ServiceFactory::getInstance()->createUserMineService();
  }
  public function getRongCloudService() {
    return ServiceFactory::getInstance()->createRongCloudService();
  }
  public function getGameService() {
    return ServiceFactory::getInstance()->createGameService();
  }
  protected function getWxpayService() {
    return ServiceFactory::getInstance()->createWxpayService();
  }
  protected function getWebappService() {
    return ServiceFactory::getInstance()->createWebappService();
  }
  
  /**
   * @desc 验证访问uri是否合法
   */
  private static $requestUrl;
  private function checkRequestUri ($uri) {
    if (!self::$requestUrl) {
      self::$requestUrl = preg_replace("/^\/v\d{1}\.\d{1}\.?\d{0,1}/i", "", Yii::app()->getRequest()->getUrl());
    }
    return (stripos(self::$requestUrl, $uri) === 0) ? TRUE : FALSE;
  }
  
  /**
   * @desc 拦截器(client_id, client_secret验证)及初始化参数
   * @return boolean
   */
  public function filters () {
    //初始化全局变量属性
    $this->globalAttributions = Yii::app()->params['GlobalAttributions'];

    //检查是否是来自site/* 或 第3方回调接口 (TRUE,则不进行拦截)
    if ($this->getId() == 'site') return FALSE;
    if ($this->checkRequestUri('/games/tgqcgm/init')) return FALSE;
    if ($this->checkRequestUri('/paying/wxpaycallback')) return FALSE;
    
    $currentClientIds = Yii::app()->params['client_ids'];
    $currentClientSecrets = Yii::app()->params['client_secrets'];
    
    //当前客户端请求参数
    $suuid = $this->getSafeRequest('s_uuid');
    $version = $this->getSafeRequest('version');
    $network = $this->getSafeRequest('network');
    $system = $this->getSafeRequest('system');
    $clientId = (int) $this->getSafeRequest('client_id', 0, 'int');
    $clientSecret = $this->getSafeRequest('client_secret');
    $t = (int) $this->getSafeRequest('t', 0, 'int');
    
    $this->currentClientId = $clientId;
    $this->currentAppVersion = str_replace(array('.', 'v'), '', $version);
    $this->network = $network;
    $this->system = $system;
    
    //检查$isInitAction, $isNotNeedLoginAction (TRUE,则不进行$suuid验证)
    $requestUrl = Yii::app()->getRequest()->getUrl();
    $isInitAction = $this->checkRequestUri('/setting/generatesign');
    $isNotNeedLoginAction = $this->checkRequestUri('/setting/global') || $this->checkRequestUri('/user/login') || $this->checkRequestUri('/user/signup') || $this->checkRequestUri('/user/findpasswd') || $this->checkRequestUri('/message/sendsmscode');
    
    //验证client_id和client_secret是否合法
    if (!in_array($clientId, $currentClientIds) || !$clientSecret) {
      return $this->outputJsonData(601);
    }
    
    //静态secret验证（签名api或初始化api）
    if ($isInitAction) {
      if ($clientSecret != $currentClientSecrets[$clientId]) return $this->outputJsonData(601);
      
    //动态Secret验证
    } else {
      if (!APP_DEBUG) {
        $isUploadAttachAction = $this->checkRequestUri('/thing/uploadattach') || $this->checkRequestUri('/games/tgqcgm/uploadtm');
        $checkTime = time() - ($isUploadAttachAction ? 2 : 1) * self::SECRET_EXPIRE_TIME;
        if ($t < $checkTime || $clientSecret != md5(Yii::app()->params['SIG_PREFIX_KEY'] . $clientId . $version . $t)) {
          $this->outputJsonData(600, array(
            't' => time(),
            'apptip' => '网络超时，请稍后再试！'
          ));
        }
      }
      
      //用户会话密匙$suuid验证,并获取用户信息
      if ($suuid) {
        $suuidDecoded = base64_decode($suuid);
        list($clientId, $uid, $authId) = explode('|', $suuidDecoded);
      
        //验证用户会话密匙$suuid
        $authUserInfo = $this->getUserService()->authUserSessionToken($clientId, $uid, $authId);
        if (!$authUserInfo['uid'] || $authUserInfo['status'] != 0) {
          return $this->outputJsonData(403);
        }
        
        //TODO 禁用账号（设备）
        if (in_array($authUserInfo['uid'], array())) {
          return $this->outputJsonData(403);
        }
        
        //获取用户状态数或初始化
        $userStatus = $this->getUserFortuneService()->getUserFortuneStatusByUid($authUserInfo['uid']);
        if (!$userStatus) {
          $userStatus = $this->getUserFortuneService()->initUserFortuneStatus($authUserInfo);
        }
        
        //新用户送金币（验证是否安装了APP）
        if (!$userStatus['is_app_installed']) {
          if ($this->getUserFortuneService()->autoUserFortuneCoin($authUserInfo['uid'], 18)) {
            $this->getUserFortuneService()->updateUserFortuneStatusByUid($authUserInfo['uid'], array(
              'is_app_installed' => 1,
            ));
            $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
            $this->getMessageService()->sendRcImMessage($officialUserInfo, array($authUserInfo['uid']), 'new_user', array(100));
          }
        }
        
        $authUserInfo['s_uuid'] = $suuid;
        $authUserInfo['status'] = $userStatus;
        $this->currentUser = $authUserInfo;
      } else {
        if (!$isNotNeedLoginAction) return $this->outputJsonData(403);
      }
    }
    
    return FALSE;
  }

  /**
   * @desc 获取参数并进行安全过滤
   * @author Aaron
   * @date 2012/07/16
   */
  public function getSafeRequest($param, $defaultValue = '', $type = 'string') {
    //DEBUG模式允许GET请求
    if (APP_DEBUG) {
      $value = Yii::app()->request->getParam($param, $defaultValue);
    } else {
      $value = Yii::app()->request->getPost($param, $defaultValue);
    }
    
    //int 参数类型验证
    if ($type == 'int') {
      return is_numeric($value) ? trim($value) : $defaultValue;
    }
    
    //json 参数类型验证
    if ($type == 'json') {
      return $value ? json_decode(trim($value), TRUE) : $value;
    }
    
    //string 参数类型安全过滤
    return $value ? Utils::filterString($value) : $defaultValue;
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
    $defaultData['description'] = Yii::app()->params['ApiResponseCodes'][$code];
    $defaultData['apptip'] = '';
    if ($data['apptip']) {
      $defaultData['apptip'] = $data['apptip'];
      unset($data['apptip']);
    }
    
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
}
