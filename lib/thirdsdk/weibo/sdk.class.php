<?php
require_once APP_CONFIG_THIRDSDK_DIR . '/weibo/config.inc.php';
require_once APP_LIB_THIRD_SDK_DIR . '/weibo/lib/saetv2.ex.class.php';

/**
 * 微博类
 */
final class Weibo {

  //ACCESS_ID
  const WEIBO_ACCESS_KEY = WEIBO_ACCESS_KEY;

  //ACCESS_KEY
  const WEIBO_SECRET_KEY = WEIBO_SECRET_KEY;

  //回调地址
  const WEIBO_CALLBACK_URL = WEIBO_CALLBACK_URL;

  private $oAuth = NULL;

  public $accessToken = NULL;

  public $refreshToken = NULL;

  public $debug = FALSE;

  //初始化 SDK
  public function __construct() {
    if (!$this->oAuth) {
      $oAuth = new SaeTOAuthV2(self::WEIBO_ACCESS_KEY, self::WEIBO_SECRET_KEY, $this->accessToken, $this->refreshToken, $this->debug);
      $this->oAuth = $oAuth;
    }
  }

  /**
   * @desc 获取微博授权链接
   * @param string redirect_uri
   * @param string csrf token
   * @param string display: mobile, defautl
   * @param string response_type: code
   * @return string oauth_url
   */
  public function getOAthUrl ($url, $csrfToken, $display = 'default', $response_type = 'code') {
    return $this->oAuth->getAuthorizeURL($url, $csrfToken, $display, $response_type);
  }

  /**
   * @desc 获取授权后的token信息
   * @param string $code weibo授权成功后返回的code
   */
  public function getOAthTokenInfo ($code, $url = self::WEIBO_CALLBACK_URL) {
    $keys = array();
    $keys['code'] = $code;
    $keys['redirect_uri'] = $url;
    try {
      return $this->oAuth->getAccessToken('code', $keys) ;
    } catch (OAuthException $e) {
    }
  }

  /**
   * @desc 刷新token, 获取新的token信息
   * @param string $refreshToken weibo授权成功后返回的刷新token
   */
  public function getRefreshToken ($refreshToken) {
    $keys = array();
    $keys['refresh_token'] = $refreshToken;
    try {
      return $this->oAuth->getAccessToken('token', $keys) ;
    } catch (OAuthException $e) {
    }
  }

  /**
   * @desc 获取用户信息
   */
  public function getUserInfo ($uid, $accessToken) {
    if ($uid) {
      $url = 'users/show';
      $params = array();
      $params['uid'] = $uid;
      $params['access_token'] = $accessToken;
      try {
        return $this->oAuth->get($url, $params);
      } catch (OAuthException $e) {
      }
    }
  }

  /**
   * @desc 获取解析后的signed_request
   */
  public function getParsedSignedRequest ($signedRequest, $secretKey = self::WEIBO_SECRET_KEY) {
    $parsedSignedRequest = array();
    if ($signedRequest && $secretKey) {
      $parsedSignedRequest = $this->oAuth->parseSignedRequest($signedRequest, $secretKey);
    }
    return $parsedSignedRequest;
  }
}

