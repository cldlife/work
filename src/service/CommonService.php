<?php
/**
 * @desc 公共Service
 */
class CommonService extends BaseService {
  
  public static $alphabets  = array(
    'A', 'B', 'C', 'D',
    'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
    'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
    'U', 'V', 'W', 'X', 'Y', 'Z');

  //官方客服账号uid
  public static function getDefaultOfficialUid() {
    return self::DEFAULT_OFFICIAL_UID;
  }

  private static $wxConfig = array(
    //杭州逛吃
    array('appId' => 'wxf291838c0583510e', 'appSecret' => '978dae2852e9146ab33ccab056c4547b'),
    //每日优惠集合
    'mryhjh' => array('appId' => 'wxf291838c0583510e', 'appSecret' => '978dae2852e9146ab33ccab056c4547b'),
    //share
    'share' => array('appId' => 'wxf291838c0583510e', 'appSecret' => '978dae2852e9146ab33ccab056c4547b'),
    'share1' => array('appId' => 'wxf291838c0583510e', 'appSecret' => '978dae2852e9146ab33ccab056c4547b'),
  );

  private function getCommonDAO() {
    return DAOFactory::getInstance()->createCommonDAO();
  }

  /**
   * 获取微信支付service
   */
  private function getWxpayService() {
    return ServiceFactory::getInstance()->createWxpayService();
  }

  /**
   * @desc 获取memcache缓存
   */
  public function getFromMemcache ($cacheKey) {
    return $this->getMemcache()->get($cacheKey);
  }

  /**
   * @desc 写入memcache缓存
   */
  public function setToMemcache ($cacheKey, $value, $cacheTime = 0) {
    if (!$cacheTime) $cacheTime = self::CACHE_TIME;
    return $this->getMemcache()->set($cacheKey, $value, 0, $cacheTime);
  }
  
  /**
   * @desc 删除memcache缓存
   */
  public function deleteFromMemcache ($cacheKey) {
    return $this->getMemcache()->delete($cacheKey);
  }
  
  /**
   * @param $parentId 默认0（全球）, 返回 countries 
   * @return provinces or cities or districts by parent_id
   */
  public function getRegionsById($parentId = 0) {
    $cacheId = 'country_regions_by_id_' . $parentId;
    $regions = $this->getFileCache()->get($cacheId);
    if (!$regions) {
      $regions = $this->getCommonDAO()->findRegionsWithParentId($parentId);
      $this->getFileCache()->set($cacheId, $regions);
    }
    return $regions;
  }
  
  /**
   * @desc 根据区域(省,城市,地区)name获取region
   */
  public function getRegionInfo($id) {
    if (!$id) {
      return array();
    }
  
    return $this->getCommonDAO()->findRegionInfoWithId($id);
  }

  /**
   * @desc 根据区域(省,城市,地区)name获取region
   */
  public function getRegionByName($name, $category = 'city') {
    if (!$name) {
      return array();
    }
    
    $name = trim(str_replace(array('省', '市', '辖区'), '', urldecode($name)));
    $region = $this->getCommonDAO()->findRegionInfoWithName($name, $category);
    if ($region) $region['name'] = str_replace(array('市', '区', '县'), '', $region['name']);
    return $region;
  }

  /**
   * @desc 新增IOS终端设备
   * @param array $fields
   */
  public function addDeviceIOS($fields) {
    if (!$fields['device_token']) {
      throw new Exception('device_token is null...');
    }
    
    return $this->getCommonDAO()->insertDeviceIOS($fields);
  }

  /**
   * @desc 获取微信access_token
   * @param string appId
   * @param string appSecret
   * @param string accessToken
   * TODO 获取wx access_token,需要写入缓存,因此不能写入WeixinService
   *      用在jssdk签名, mall WxController initWxSdkConfig()也有读写缓存
   */
  public function getWxAccesstoken ($appId, $appSecret) {
    $accessToken = '';
    if ($appId && $appSecret) {
      $cacheKey = __FUNCTION__ . "_APPID_{$appId}_APPSECRET_{$appSecret}_2.9.3";
      $accessToken = $this->getFromMemcache($cacheKey);
      if (!$accessToken) {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $appId . '&secret=' . $appSecret;
        $token = HttpClient::curl($url);
        $decodeToken = json_decode($token['content'], TRUE);
        if ($decodeToken['access_token']) {
          $this->setToMemcache($cacheKey, $decodeToken['access_token'], 3000);
        }
      }
    }
    return $accessToken;
  }
  
  /**
   * @desc 获取微信js sdk apiticket
   * @param string appId
   * @param string appSecret
   * @return string jsapiTicket
   */
  private function getJsapiTicket ($appId, $appSecret) {
    $jsapiTicket = '';
    if ($appId && $appSecret) {
      $cacheKey = __FUNCTION__ . '_APPID_' . $appId . '_2.2';
      $jsapiTicket = $this->getFromMemcache($cacheKey);
      if (!$jsapiTicket) {
        $accessToken = $this->getWxAccesstoken($appId, $appSecret);
        if ($accessToken) {
          $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $accessToken . '&type=jsapi';
          $ticket = HttpClient::curl($url);
          $decodeTicket = json_decode($ticket['content'], TRUE);
          if ($decodeTicket['ticket']) {
            $this->getCommonService()->setToMemcache($cacheKey, $decodeTicket['ticket'], 1800);
          }
        }
      }
    }
    return $jsapiTicket;
  }

  /**
   * @desc 获取微信js sdk config
   * @param int wxConfig id
   * @return array js sdk config
   */
  public function getJssdkConfig ($configId) {
    $jssdkConfig = array();
    if (self::$wxConfig[$configId]) {
      $jssdkConfig = $this->getJssdkConfigByAppid(self::$wxConfig[$configId]['appId'], self::$wxConfig[$configId]['appSecret']);
    }
    return $jssdkConfig;
  }
  
  /**
   * @desc 获取微信js sdk config
   * @param $appId string
   * @param $appSecret string
   * @return array js sdk config
   */
  public function getJssdkConfigByAppid ($appId, $appSecret) {
  	$jssdkConfig = array();
  	if ($appId && $appSecret) {
  	  $jsapiTicket = $this->getJsapiTicket($appId, $appSecret);
  	  if ($jsapiTicket) {
  		$jssdkConfig['jsapi_ticket'] = $jsapiTicket;
  		$jssdkConfig['appid'] = $appId;
 		$jssdkConfig['noncestr'] = $this->getWxpayService()->getNonceStr();
  		$jssdkConfig['timestamp'] = time();
  		$jssdkConfig['url'] = Yii::app()->getRequest()->getHostInfo() . Yii::app()->getRequest()->getUrl();
  		$string = "jsapi_ticket={$jsapiTicket}&noncestr={$jssdkConfig['noncestr']}&timestamp={$jssdkConfig['timestamp']}&url={$jssdkConfig['url']}";
  		$jssdkConfig['signature'] = sha1($string);
  	  }
  	}
  	return $jssdkConfig;
  }
}
