<?php
require_once APP_CONFIG_THIRDSDK_DIR . '/weixin/config.inc.php';
require_once APP_LIB_THIRD_SDK_DIR . '/weixin/lib/weixin.config.class.php';
require_once APP_LIB_THIRD_SDK_DIR . '/weixin/lib/weixin.api.class.php';
require_once APP_LIB_THIRD_SDK_DIR . '/weixin/lib/weixin.setup.class.php';
require_once APP_LIB_THIRD_SDK_DIR . '/weixin/lib/weixin.result.class.php';
require_once APP_LIB_THIRD_SDK_DIR . '/weixin/lib/weixin.utils.class.php';

/**
 * 微信接口类
 */
final class Weixin {

  /**
   * @desc 设置微信接口的access_token,最好在请求接口前都调用一次
   * @param string $access_token
   * @return bool
   */
  public function setAccessToken ($accessToken) {
    return $accessToken ? WeixinConfig::setWeixinAccessToken($accessToken) : FALSE;
  }

  /**
   * @desc 获取access_token并设置
   * @return string
   */
  public function getAccessToken ($params = array()) {
    $token = WeixinApi::api('get_access_token', $params);
    if ($token && $token['access_token'] && WeixinConfig::setWeixinAccessToken($token['access_token'])) {
      return $token['access_token'];
    }
    return '';
  }

  /**
   * @desc 设置微信js-sdk的jsapi_ticket,在使用js-sdk前调用一次
   * @param string $access_token
   * @return bool
   */
  public function setJsapiTicket ($jsapiTicket) {
    return $jsapiTicket ? WeixinConfig::setWxJsapiTicket($jsapiTicket) : FALSE;
  }

  /**
   * @desc 获取js-sdk的jsapi_ticket并设置
   * @param array empty not important
   * @param bool 是否同时返回access_token数据
   * @return string
   */
  public function getJsapiTicket ($params = array(), $getAccessToken = FALSE) {
    $token = WeixinConfig::$WEIXIN_ACCESS_TOKEN ? WeixinConfig::$WEIXIN_ACCESS_TOKEN : $this->getAccessToken();
    if ($token) {
      $jsapiTicket = WeixinApi::api('get_jsapi_ticket', $params);
      if ($jsapiTicket && $jsapiTicket['ticket'] && WeixinConfig::setWxJsapiTicket($jsapiTicket['ticket'])) {
        return $getAccessToken ? array('access_token' => $token, 'jsapi_ticket' => $jsapiTicket['ticket']) : $jsapiTicket['ticket'];
      }
    }
    return '';
  }

  /**
   * @desc 获取js-sdk签名
   * @param array $params
   * @return array $params with sign
   */
  public function getJssdkSign ($params = array()) {
    $jsapiTicket = WeixinConfig::$WEIXIN_JSAPI_TICKET ? WeixinConfig::$WEIXIN_JSAPI_TICKET : $this->getJsapiTicket();
    if ($jsapiTicket) {
      $signParams = array();
      $signParams['jsapi_ticket'] = $jsapiTicket;
      $signParams['noncestr'] = WeixinUtils::getNonceStr();
      $signParams['timestamp'] = $params['timestamp'] ? $params['timestamp'] : time();
      $signParams['url'] = $params['url'] ? $params['url'] : Yii::app()->getRequest()->getHostInfo() . Yii::app()->getRequest()->getUrl();

      $signStr = "jsapi_ticket={$signParams['jsapi_ticket']}&noncestr={$signParams['noncestr']}&timestamp={$signParams['timestamp']}&url={$signParams['url']}";
      $signParams['signature'] = sha1($signStr);
      $signParams['appid'] = WeixinConfig::$WEIXIN_APP_ID;
      return $signParams;
    }
    return array();
  }

  /**
   * @desc 设置微信配置
   * @param array weixin config
   * @param bool
   */
  public function setWeixinConfig ($weixinConfig) {
    return $weixinConfig ? WeixinConfig::setWeixinConfig($weixinConfig) : FALSE;
  }

  /**
   * @desc 微信后台修改服务器配置时验证
   * @param array: timestamp, nonce
   * @param string signature
   * @return bool
   */
  public function verifyWxServerConfig ($params, $signature) {
    return ($params && $signature) ? WeixinApi::checkWxConfigSignature($params, $signature) : FALSE;
  }

  /**
   * @desc 微信加密消息完整性验证
   * @param array: timestamp, nonce (GET)
   * @param string signature
   * @return bool
   */
  public function verifyWxMsgSignature ($params, $msg, $signature) {
    return ($params && $msg && $signature) ? WeixinApi::checkWxMsgSignature($params, $msg, $signature) : FALSE;
  }

  /**
   * @desc 获取收到的微信消息
   * @param string $msg
   * @return array
   */
  public function getWxMsg ($msg) {
    return $msg ? WeixinApi::parseWxXmlMsg($msg) : array();
  }

  /**
   * @desc 根据数据生成微信xml格式的消息
   * @param array $msg
   * @return string
   */
  public function setWxMsg ($msg, $encrypt = TRUE) {
    return $msg ? WeixinApi::genWxXmlMsg($msg, $encrypt) : '';
  }

  /**
   * @desc 获取用户信息
   * @param array $param:openid
   * @return array
   */
  public function getUserInfo ($params) {
    return $params ? WeixinApi::api('get_user_info', $params) : array();
  }

  /**
   * @desc 获取临时二维码
   * @param array $params
   * @return array
   * expire_seconds int
   * scene_id int/string 不超过32位
   */
  public function getTmpQrcode ($params) {
    return $params ? WeixinApi::api('get_tmp_qrcode', $params) : array();
  }

  /**
   * @desc 获取永久二维码
   * @param array $params
   * @return array
   * scene_id string/int
   */
  public function getPmtQrcode ($params) {
    return $params ? WeixinApi::api('get_pmt_qrcode', $params) : array();
  }

  /**
   * @desc 主动向用户发送消息
   * @param array $params
   * @return array
   * $msg:openid, content, msgtype
   */
  public function sendMsg ($params) {
    return $params ? WeixinApi::api('send_msg', $params) : array();
  }

  /**
   * @desc 创建菜单
   * @param array $params
   * @return array
   * array: button:
   *   array: name, type, key/url
   *   array: name, sub_button,
   *     array: ...
   */
  public function createMenu ($params) {
    return $params ? WeixinApi::api('create_menu', $params) : array();
  }

  /**
   * @desc 获取当前菜单
   * @param array $params:empty
   * @return array
   */
  public function getMenu ($params = array()) {
    return WeixinApi::api('get_menu', $params);
  }

  /**
   * @desc 删除当前菜单
   * @param array $params:empty
   * @return array
   */
  public function deleteMenu ($params = array()) {
    return WeixinApi::api('delete_menu', $params);
  }

  /**
   * @desc 添加永久图文消息使用的图片(不占用永久素材使用量)
   * @param array $params: media => $_FILES['file']
   * @return array: url(string)
   */
  public function addArticleImg ($params) {
    if ($params && $params['media'] && $params['media']['name'] && $params['media']['type'] && $params['media']['tmp_name'] && file_exists($params['media']['tmp_name'])) {
      return WeixinApi::api('add_article_img', $params);
    }
    return array();
  }

  /**
   * @desc 添加永久微信图文消息
   * @param array $params:
   *    articles:...
   * @return array: media_id(string)
   */
  public function addPmtArticle ($params) {
    return ($params && isset($params['articles']) && count($params['articles']) < 9) ? WeixinApi::api('add_pmt_article', $params) : array();
  }

  /**
   * @desc 添加微信永久素材
   * @param array $params:
   * media => $_FILES['file']
   * type (image, voice, video, thumb)
   * if (media = video) description:title, introduction
   * @return array: media_id(string),url(type == image)
   */
  public function addPmtMaterial ($params) {
    $valid = $params && $params['media'] && $params['media']['name'] && $params['media']['type'];
    $valid = $valid && $params['media']['tmp_name'] && file_exists($params['media']['tmp_name']) && $params['type'];
    if ($params['type'] == 'video') $valid = $valid && $params['description'] && $params['description']['title'] && $params['description']['introduction'];

    return $valid ? WeixinApi::api('add_pmt_material', $params) : array();
  }

  /**
   * @desc 获取微信永久素材
   * @param array $params: media_id(string)
   * @return array:
   * type == article
   *    array('articles' => array())
   * type == video
   *    array:title,description,down_url
   * else
   *    file content
   */
  public function getPmtMaterial ($params) {
    return ($params && $params['media_id']) ? WeixinApi::api('get_pmt_material', $params) : array();
  }
}
