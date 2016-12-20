<?php
/**
 * @desc 第三方开放平台回调处理
 */
class OpenController extends BaseController {

  private static $cookieName = '_s_uuid_mpid_';
  
  public function run () {
    $method = $this->getURIDoAction($this, 1);
    $this->$method();
  }
  
  /**
   * @desc 微信授权成功回调
   */
  private function doWeixin() {
    $state =  $this->getSafeRequest('state');
    $code =  $this->getSafeRequest('code');
    $rf =  urldecode($this->getSafeRequest('rf'));
    $mpid =  $this->getSafeRequest('mpid');
    $currentClientId = 3;
    $checkState = Utils::generateCSRFSecret(date('YmdH'));

    if ($rf && $mpid && $code && ($state == $checkState)) {
      $cookieName = self::$cookieName . $mpid;
      
      //已经授权的不用再授权（减小wx授权接口调用次数）
      $suuid = $this->getCookie($cookieName);
      if ($suuid) {
        $time = time();
        $sn = Utils::generateCSRFSecret($time . $suuid);
        $rf = $rf . (stripos($rf, '?') === FALSE ? '?' : '&') . "sn={$sn}&t={$time}&_s_uuid_={$suuid}";
        $this->redirect($rf);
      }
      
      //获取公众号信息
      $mpInfo = $this->getWeigameService()->getWeigameMpinfoById($mpid);
      if (!$mpInfo) return ;
      
      $appid = $mpInfo['app_id'];
      $secret = $mpInfo['app_secret'];

      //微信登录授权
      $weixinGetAccessTokenUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code";
      $weixinAccessInfoContent = HttpClient::curl($weixinGetAccessTokenUrl);
      $weixinAccessInfoJson = trim($weixinAccessInfoContent['content']);

      $weixinAccessInfo = array();
      if ($weixinAccessInfoJson) $weixinAccessInfo = json_decode($weixinAccessInfoJson, TRUE);
      if ($weixinAccessInfo['access_token'] && $weixinAccessInfo['openid']) {
        //获取微信用户信息
        $weixinGetUserInfoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token={$weixinAccessInfo['access_token']}&openid={$weixinAccessInfo['openid']}";
        $weixinUserInfoContent = HttpClient::curl($weixinGetUserInfoUrl);
        $weixinAccessInfoJson = trim($weixinUserInfoContent['content']);

        $weixinUserInfo = array();
        if ($weixinAccessInfoJson) $weixinUserInfo = json_decode($weixinAccessInfoJson, TRUE);
        if ($weixinUserInfo) {
          //登录
          $user = $this->getUserService()->loginByWeixin(array(
            'appid' => $appid,
            'openid' => $weixinAccessInfo['openid'],
            'unionid' => $weixinAccessInfo['unionid'] ? $weixinAccessInfo['unionid'] : $weixinAccessInfo['openid'],
            'nickname' => $weixinUserInfo['nickname'],
            'gender' => $this->getUserService()->getGender($weixinUserInfo['sex']),
            'avatar' => $weixinUserInfo['headimgurl'],
            'access_token' => $weixinAccessInfo['access_token'],
            'expires_in' => $weixinAccessInfo['expires_in'],
            'refresh_token' => $weixinAccessInfo['refresh_token'],
            'location' => '',
            'sign' => '',
            'reg_ip' => Yii::app()->request->userHostAddress,
            'reg_from' => 1,
            'wx_from' => 2,
          ));

          //生成会话
          if ($user['user']['uid'] && $user['user']['private_key']) {
            $suuid = $this->getUserService()->generateUserSessionToken($currentClientId, $user['user']['uid'], $user['user']['private_key']);
            $this->deleteCookie($cookieName);
            $this->setCookie($cookieName, $suuid, array('expire' => 86400 * 7));
            if ($suuid) {
              $time = time();
              $sn = Utils::generateCSRFSecret($time . $suuid);
              $rf = $rf . (stripos($rf, '?') === FALSE ? '?' : '&') . "sn={$sn}&t={$time}&_s_uuid_={$suuid}";
              $this->redirect($rf);
            }
          }
        }
      }
    }
    exit;
  }

  /**
   * @desc 微博授权成功回调
   */
  private function doWeibo() {
  }

  
  /**
   * @desc 微博授权失败回调
   */
  private function doWeiboCancel() {
  }

  /**
   * @desc QQ登录授权回调
   */
  private function doQQ() {
  }

  /**
   * @desc QQ授权失败回调
   */
  private function doQQCancel() {
  }
}
?>
