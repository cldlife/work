<?php
/**
 * 微信支付API参数设置类
 */

class WeixinSetup extends WeixinConfig {

  /**
   * 微信API参数设置入口
   * @param string api
   * @param array $params
   * @return array
   */
  public static function setup ($api, $params) {
    $method = "setup_{$api}_params";
    if (method_exists(__CLASS__, $method)) {
      $apiParams = self::$weixinApis[$api];
      return self::$method($apiParams, $params);
    }
    return array();
  }

  /**
   * @desc 微信获取access_token接口
   * @param array $apiParams api预设的参数
   * @param array $params 传入参数
   * @return array
   */
  private static function setup_get_access_token_params ($apiParams, $params) {
    if (self::$WEIXIN_APP_ID && self::$WEIXIN_APP_SECRET) {
      $url = $apiParams['url'] . '?grant_type=client_credential&appid=' . self::$WEIXIN_APP_ID . '&secret=' . self::$WEIXIN_APP_SECRET;
      return array(
        'url' => $url,
        'method' => $apiParams['method'],
        'params' => array(),
      );
    }
    return array();
  }

  /**
   * @desc 获取微信用户信息
   * @param array $apiParams api预设的参数
   * @param array $params 传入参数
   * @return array
   */
  private static function setup_get_user_info_params ($apiParams, $params) {
    if (self::$WEIXIN_ACCESS_TOKEN && $params && $params['openid']) {
      $url = "{$apiParams['url']}?access_token=" . self::$WEIXIN_ACCESS_TOKEN . "&openid={$params['openid']}&lang=zh_CN";
      return array(
        'url' => $url,
        'method' => $apiParams['method'],
        'params' => array(),
      );
    }
  }

  /**
   * @desc 创建临时二维码
   * @param array $apiParams api预设的参数
   * @param array $params 传入参数
   * @return array
   * expire_seconds int 二维码的有效期,秒数
   * scene_id int 自定义参数,32位非0整数
   */
  private static function setup_get_tmp_qrcode_params ($apiParams, $params) {
    if (self::$WEIXIN_ACCESS_TOKEN && $params && $params['expire_seconds'] && $params['scene_id']) {
      $url = $apiParams['url'] . '?access_token=' . self::$WEIXIN_ACCESS_TOKEN;

      $apiParams['params']['expire_seconds'] = (int)$params['expire_seconds'];
      $apiParams['params']['action_info']['scene']['scene_id'] = (int)$params['scene_id'];
      return array(
        'url' => $url,
        'method' => $apiParams['method'],
        'params' => $apiParams['params'],
      );
    }
    return array();
  }

  /**
   * @desc 创建永久二维码
   * @param array $apiParams api预设的参数
   * @param array $params 传入参数
   * @return array
   * scene_id string/int 自定义参数
   */
  private static function setup_get_pmt_qrcode_params ($apiParams, $params) {
    if (self::$WEIXIN_ACCESS_TOKEN && $params && $params['scene_id']) {
      $url = $apiParams['url'] . '?access_token=' . self::$WEIXIN_ACCESS_TOKEN;

      $apiParams['params']['action_info']['scene']['scene_str'] = (string)$params['scene_id'];
      return array(
        'url' => $url,
        'method' => $apiParams['method'],
        'params' => $apiParams['params'],
      );
    }
    return array();
  }

  /**
   * @desc 主动向用户发送消息
   * @param array $apiParams api预设的参数
   * @param array $params 传入参数
   * @return array
   * string touser 用户openid
   * string msgtype: text, image, voice, mpnews, video, music, news, wxcard
   * array  '$msgtype' => array()
   */
  private static function setup_send_msg_params ($apiParams, $params) {
    if (self::$WEIXIN_ACCESS_TOKEN && $params && $params['openid'] && $params['content']) {
      $apiParams['params']['touser'] = $params['openid'];
      if ($params['msgtype']) $apiParams['params']['msgtype'] = $params['msgtype'];
      switch ($params['msgtype']) {
      case 'text':
        $apiParams['params']['msgtype'] = 'text';
        $apiParams['params']['text'] = array('content' => $params['content']);
        break;
      case 'image':
      case 'voice':
      case 'mpnews':
        $apiParams['params']['msgtype'] = $params['msgtype'];
        $apiParams['params'][$params['msgtype']] = array('media_id' => $params['content']);
        break;
      case 'video':
      case 'music':
      case 'news':
      case 'wxcard':
        $apiParams['params'][$params['msgtype']] = $params['content'];
        break;
      default:
        $apiParams['params']['msgtype'] = 'text';
        $apiParams['params']['text'] = array('content' => $params['content']);
        break;
      }
      if ($params['customservice']) $apiParams['params']['customservice'] = $params['customservice'];

      $url = $apiParams['url'] . '?access_token=' . self::$WEIXIN_ACCESS_TOKEN;
      return array(
        'url' => $url,
        'method' => $apiParams['method'],
        'params' => $apiParams['params'],
      );
    }
    return array();
  }

  /**
   * @desc 创建菜单
   * @param array $apiParams api预设的参数
   * @param array $params 传入参数
   * @return array
   * array button: name, type, key/url
   *    array: name, sub_button, ...
   */
  private static function setup_create_menu_params ($apiParams, $params) {
    if (self::$WEIXIN_ACCESS_TOKEN && $params && $params['button']) {
      $url = $apiParams['url'] . '?access_token=' . self::$WEIXIN_ACCESS_TOKEN;

      return array(
        'url' => $url,
        'method' => $apiParams['method'],
        'params' => $params,
      );
    }
    return array();
  }

  /**
   * @desc 获取当前菜单
   * @param array $apiParams api预设的参数
   * @param array $params 传入参数
   * @return array
   */
  private static function setup_get_menu_params ($apiParams, $params) {
    if (self::$WEIXIN_ACCESS_TOKEN) {
      $url = $apiParams['url'] . '?access_token=' . self::$WEIXIN_ACCESS_TOKEN;

      return array(
        'url' => $url,
        'method' => $apiParams['method'],
        'params' => array(),
      );
    }
    return array();
  }

  /**
   * @desc 主动向用户发送消息
   * @param array $apiParams api预设的参数
   * @param array $params 传入参数
   * @return array
   */
  private static function setup_delete_menu_params ($apiParams, $params) {
    if (self::$WEIXIN_ACCESS_TOKEN) {
      $url = $apiParams['url'] . '?access_token=' . self::$WEIXIN_ACCESS_TOKEN;

      return array(
        'url' => $url,
        'method' => $apiParams['method'],
        'params' => array(),
      );
    }
    return array();
  }

  /**
   * @desc 获取微信js-sdk的jsapi_ticket
   * @param array $apiParams api预设的参数
   * @param array $params 传入参数
   * @return array
   */
  private static function setup_get_jsapi_ticket_params ($apiParams, $params) {
    if (self::$WEIXIN_ACCESS_TOKEN) {
      $url = $apiParams['url'] . '?access_token=' . self::$WEIXIN_ACCESS_TOKEN . '&type=jsapi';

      return array(
        'url' => $url,
        'method' => $apiParams['method'],
        'params' => array(),
      );
    }
    return array();
  }

  /**
   * @desc 添加微信图文消息使用的图片
   * @param array $apiParams api预设的参数
   * @param array $params 传入参数
   * media => $_FILES['file']
   * @return array
   */
  private static function setup_add_article_img_params ($apiParams, $params) {
    if (self::$WEIXIN_ACCESS_TOKEN && $params['media']['name'] && $params['media']['type']  && $params['media']['tmp_name'] && file_exists($params['media']['tmp_name'])) {
      $url = "{$apiParams['url']}?access_token=" . self::$WEIXIN_ACCESS_TOKEN;
      $outParams = array();
      $outParams['media']['path'] = $params['media']['tmp_name'];
      $outParams['media']['type'] = $params['media']['type'];
      $outParams['media']['name'] = $params['media']['name'];

      return array(
        'url' => $url,
        'method' => $apiParams['method'],
        'params' => $outParams,
      );
    }
    return array();
  }

  /**
   * @desc 添加永久图文消息
   * @param array $apiParams api预设的参数
   * @param array $params 传入参数
   * articles (可以有多个)
   *    title 标题
   *    thumb_media_id 息的封面图片素材id(必须是永久media_id)
   *    author	作者
   *    digest 图文消息的摘要,仅有单图文消息才有摘要,多图文此处为空
   *    show_cover_pic 是否显示封面,0不显示,1显示
   *    content 具体内容,支持HTML标签,必须少于2万字符,小于1M,且会去除JS
   *    content_source_url	原文地址,点击"阅读原文"的URL
   * @return array
   */
  private static function setup_add_pmt_article_params ($apiParams, $params) {
    $valid = self::$WEIXIN_ACCESS_TOKEN && $params && $params['articles'];
    foreach ($params['articles'] as $article) {
      $valid = $valid && $article['title'] && $article['thumb_media_id'] && $article['author'];
      $valid = $valid && isset($article['show_cover_pic']) && $article['content'] && $article['content_source_url'];
    }
    if ($valid) {
      $url = "{$apiParams['url']}?access_token=" . self::$WEIXIN_ACCESS_TOKEN;

      return array(
        'url' => $url,
        'method' => $apiParams['method'],
        'params' => $params,
      );
    }
    return array();
  }

  /**
   * @desc 添加微信永久素材
   * @param array $apiParams api预设的参数
   * @param array $params 传入参数
   * media => $_FILES['file']
   * type (image, voice, video, thumb)
   * if (media = video) description:title, introduction
   * @return array
   */
  private static function setup_add_pmt_material_params ($apiParams, $params) {
    $valid = self::$WEIXIN_ACCESS_TOKEN && $params['media'] && $params['media']['name'] && $params['media']['type'];
    $valid = $valid && $params['media']['tmp_name'] && file_exists($params['media']['tmp_name']);
    $valid = $valid && $params['type'] && in_array($params['type'], $apiParams['types']);
    if ($params['type'] == 'video') $valid = $valid && $params['description'] && $params['description']['title'] && $params['description']['introduction'];

    if ($valid) {
      $url = "{$apiParams['url']}?access_token=" . self::$WEIXIN_ACCESS_TOKEN . "&type{$params['type']}";
      $outParams = array();
      $outParams['media']['path'] = $params['media']['tmp_name'];
      $outParams['media']['type'] = $params['media']['type'];
      $outParams['media']['name'] = $params['media']['name'];
      if ($params['type'] == 'video') $outParams['description'] = json_encode($params['description'], JSON_UNESCAPED_UNICODE);

      return array(
        'url' => $url,
        'method' => $apiParams['method'],
        'params' => $outParams,
      );
    }
    return array();
  }

  /**
   * @desc 获取微信永久素材
   * @param array $apiParams api预设的参数
   * @param array $params 传入参数
   * media_id string
   * @return array
   */
  private static function setup_get_pmt_material_params ($apiParams, $params) {
    if (self::$WEIXIN_ACCESS_TOKEN && $params['media_id']) {
      $url = "{$apiParams['url']}?access_token=" . self::$WEIXIN_ACCESS_TOKEN;
      $outParams = array('media_id' => $params['media_id']);

      return array(
        'url' => $url,
        'method' => $apiParams['method'],
        'params' => $outParams,
      );
    }
    return array();
  }

}
