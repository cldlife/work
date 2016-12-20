<?php
require_once APP_CONFIG_THIRDSDK_DIR . '/qq/config.inc.php';
require_once APP_LIB_THIRD_SDK_DIR . '/qq/lib/Oauth.class.php';

/**
 * @desc QQ类
 */
final class QQ {

  //ACCESS_ID
  private $QQ_ACCESS_KEY = QQ_ACCESS_KEY;
  
  //ACCESS_KEY
  private $QQ_SECRET_KEY = QQ_SECRET_KEY;
  
  //回调地址
  private $QQ_CALLBACK_URL = QQ_CALLBACK_URL;
  
  private $oAuth = NULL;
  
  public $accessToken;

  public $refreshToken;
  
  //初始化 SDK
  public function __construct($fromQQConnect = FALSE) {
    //QQ Connect
    if ($fromQQConnect) {
      $this->QQ_ACCESS_KEY = QQ_CONNECT_ACCESS_KEY;
      $this->QQ_SECRET_KEY = QQ_CONNECT_SECRET_KEY;
      $this->QQ_CALLBACK_URL = QQ_CONNECT_CALLBACK_URL;
    }
    
    if (!$this->oAuth) {
      $oAuth = new Oauth($this->QQ_ACCESS_KEY, $this->QQ_SECRET_KEY, $this->accessToken, $this->refreshToken);
      $this->oAuth = $oAuth;
    }
  }

  //生成登录授权url
  public function getOAthUrl ($url, $csrfToken, $display = NULL, $response_type = 'code') {
    return $this->oAuth->getAuthorizeURL($url, $csrfToken, $display, $response_type);
  }

  /**
   * @desc 获取授权后的token信息
   * @param string $code weibo授权成功后返回的code
   * @return array
   */
  public function getOAthTokenInfo ($code, $url = '') {
    if (!$url) $url = $this->QQ_CALLBACK_URL;
    
    $keys = array();
    $keys['code'] = $code;
    $keys['redirect_uri'] = $url;
    try {
      return $this->oAuth->getAccessToken('code', $keys) ;
    } catch (QQoAuthException $e) {
    }
  }

  /**
   * @desc 刷新token, 获取新的token信息
   * @param string $refreshToken weibo授权成功后返回的刷新token
   * @return array
   */
  public function getRefreshToken ($refreshToken) {
    $keys = array();
    $keys['refresh_token'] = $refreshToken;
    try {
      return $this->oAuth->getAccessToken('token', $keys) ;
    } catch (QQoAuthException $e) {
    }
  }

  /**
   * @desc 获取用户openid
   * @param string $accessToken
   * @return array 
   */
  public function getUserOpenid ($accessToken) {
    $url = 'https://graph.qq.com/oauth2.0/me';
    $params = array();
    $params['access_token'] = $accessToken;
    try {
      $response = $this->oAuth->oAuthRequest($url, 'GET', $params);
      $response = str_replace(array('callback( ', ' );'), '', $response);
      return json_decode($response, TRUE);
    } catch (QQoAuthException $e) {
    }
  }

  /**
   * @desc 获取用户信息
   * @param string $openid
   * @param string $accessToken
   * @return array
   */
  public function getUserInfo ($openid, $accessToken) {
    if ($openid && $accessToken) {
      $url = 'get_user_info';
      $params = array();
      $params['openid'] = $openid;
      $params['oauth_consumer_key'] = $this->QQ_ACCESS_KEY;
      $params['access_token'] = $accessToken;
      try {
        return $this->oAuth->get($url, $params);
      } catch (QQoAuthException $e) {
      }
    }
  }
}
