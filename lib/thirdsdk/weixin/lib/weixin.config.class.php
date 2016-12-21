<?php
/**
 * 微信接口配置类
 */

class WeixinConfig {

  public static $WEIXIN_APP_ID = WEIXIN_APP_ID;

  public static $WEIXIN_APP_SECRET = WEIXIN_APP_SECRET;

  public static $WEIXIN_AES_KEY = WEIXIN_AES_KEY;

  public static $WEIXIN_ACCOUNT_NAME = WEIXIN_ACCOUNT_NAME;

  public static $WEIXIN_SERVER_TOKEN = WEIXIN_SERVER_TOKEN;

  public static $WEIXIN_PREVIOUS_AES_KEY = '';

  public static $WEIXIN_ACCESS_TOKEN = 'uJH5Fl4nQDEOANk_yjs0PYb7pppeWzsXICKpQastjuWu7FJtQXDcZ0XALExR5jaf6QLXb8UPuemsZeuAenupPxoPYISeIEk68660fTdptIWX3tJuRp1bz3WsR3taKyGJFNHjACAOWQ';

  public static $WEIXIN_JSAPI_TICKET = '';

  /**
   * @desc 设置微信配置
   * @param array weixin config
   * @param bool
   */
  public static function setWeixinConfig ($weixinConfig) {
    if ($weixinConfig) {
      foreach ($weixinConfig as $key => $val) {
        if (!property_exists(__CLASS__, $key)) return FALSE;
        self::$$key = $val;
      }
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc 设置微信access_token
   * @param string $accessToken
   * @param bool
   */
  public static function setWeixinAccessToken ($accessToken) {
    if ($accessToken) {
      self::$WEIXIN_ACCESS_TOKEN = $accessToken;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc 设置微信js-sdk的jsapi_ticket
   * @param string $jsapiTicket
   * @param bool
   */
  public static function setWxJsapiTicket ($jsapiTicket) {
    if ($jsapiTicket) {
      self::$WEIXIN_JSAPI_TICKET = $jsapiTicket;
      return TRUE;
    }
    return FALSE;
  }

  //TODO add 'method' => 'GET'/'POST'
  public static $weixinApis = array(
    //获取access_token
    'get_access_token' => array(
      'url' => 'https://api.weixin.qq.com/cgi-bin/token',
      'method' => 'GET',
      'params' => array(
        'grant_type' => 'client_credential',
        'appid' => '',
        'secret' => '',
      ),
    ),
    //获取用户信息
    'get_user_info' => array(
      'url' => 'https://api.weixin.qq.com/cgi-bin/user/info',
      'method' => 'GET',
      'params' => array(
        'openid' => '',
      ),
    ),
    //创建临时二维码
    'get_tmp_qrcode' => array(
      'url' => 'https://api.weixin.qq.com/cgi-bin/qrcode/create',
      'qr_url' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode',
      'method' => 'POST',
      'params' => array(
        'expire_seconds' => 0,
        'action_name' => 'QR_SCENE',
        'action_info' => array(
          'scene' => array('scene_id' => 0),
        ),
      ),
    ),
    //创建永久二维码
    'get_pmt_qrcode' => array(
      'url' => 'https://api.weixin.qq.com/cgi-bin/qrcode/create',
      'qr_url' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode',
      'method' => 'POST',
      'params' => array(
        'expire_seconds' => 0,
        'action_name' => 'QR_LIMIT_STR_SCENE',
        'action_info' => array(
          'scene' => array('scene_str' => ''),
        ),
      ),
    ),
    //主动向用户发送消息
    'send_msg' => array(
      'url' => 'https://api.weixin.qq.com/cgi-bin/message/custom/send',
      'method' => 'POST',
      'params' => array(
        'touser' => '',
        'msgtype' => '',
      ),
    ),
    //创建菜单
    'create_menu' => array(
      'url' => 'https://api.weixin.qq.com/cgi-bin/menu/create',
      'method' => 'POST',
      'params' => array(
        'button' => array(),
      ),
    ),
    //获取当前菜单
    'get_menu' => array(
      'url' => 'https://api.weixin.qq.com/cgi-bin/menu/get',
      'method' => 'GET',
      'params' => array(
      ),
    ),
    //删除当前菜单
    'delete_menu' => array(
      'url' => 'https://api.weixin.qq.com/cgi-bin/menu/delete',
      'method' => 'GET',
      'params' => array(
      ),
    ),
    //获取jsapi_ticket
    'get_jsapi_ticket' => array(
      'url' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket',
      'method' => 'GET',
      'params' => array(
        'type' => 'jsapi',
      ),
    ),
    //添加永久素材文章的图片(不占用素材使用量)
    'add_article_img' => array(
      'url' => 'https://api.weixin.qq.com/cgi-bin/media/uploadimg',
      'method' => 'POST',
      'params' => array(
        'media' => array(),
      ),
    ),
    //添加永久素材
    'add_pmt_article' => array(
      'url' => 'https://api.weixin.qq.com/cgi-bin/material/add_news',
      'method' => 'POST',
      'params' => array(
        'type' => '',
      ),
    ),
    //添加永久素材:图片(image),语音(voice),视频(video),缩略图(thumb)
    'add_pmt_material' => array(
      'url' => 'https://api.weixin.qq.com/cgi-bin/material/add_material',
      'method' => 'POST',
      'types' => array('image', 'voice', 'video', 'thumb'),
      'params' => array(
        'type' => '',
        'media' => array(),
      ),
    ),
    //获取永久素材
    'get_pmt_material' => array(
      'url' => 'https://api.weixin.qq.com/cgi-bin/material/get_material',
      'method' => 'POST',
      'params' => array(
        'media_id' => '',
      ),
    ),
  );
}

