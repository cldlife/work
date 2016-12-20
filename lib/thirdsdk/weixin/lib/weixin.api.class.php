<?php
/**
 * PHP SDK for Weixin
 */

class WeixinApi extends WeixinConfig {

  /**
   * @desc 微信API接口调用统一入口
   * @param string $api 微信config array的key
   * @param array $params
   * @return array
   */
  public static function api ($api, $params) {
    if (self::$weixinApis[$api]) {
      $apiInfo = WeixinSetup::setup($api, $params);
      if ($apiInfo) {
        //请求接口数据并返回处理结果
        return WeixinResult::translate($api, WeixinUtils::requestWxApi($apiInfo['params'], $apiInfo['url'], $apiInfo['method']));
      }
    }
    return array();
  }

  /**
   * @desc 检查微信服务器配置签名
   * @param array $params
   * @param string
   * @return bool
   */
  public static function checkWxConfigSignature ($params, $signature) {
    if ($params && $signature && self::$WEIXIN_SERVER_TOKEN) {
      $params[] = self::$WEIXIN_SERVER_TOKEN;
      if ($signature == WeixinUtils::getWxSignature($params))
        return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc 检查微信加密后消息签名
   * @param array $params
   * @param string $msg(xml)
   * @param string
   * @return bool
   */
  public static function checkWxMsgSignature ($params, $msg, $signature) {
    if ($params && $msg && $signature && self::$WEIXIN_SERVER_TOKEN) {
      $arrayMsg = WeixinUtils::getArrayFromXml($msg);
      $params[] = $arrayMsg['Encrypt'];
      $params[] = self::$WEIXIN_SERVER_TOKEN;
      if ($signature == WeixinUtils::getWxSignature($params))
        return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc 微信消息接收并解析(如果已加密的消息则解密)
   * @param string $msg
   * @return array
   */
  public static function parseWxXmlMsg ($msg) {
    if ($msg) {
      $arrayMsg = WeixinUtils::getArrayFromXml($msg);
      return ($arrayMsg && !$arrayMsg['FromUserName'] && $arrayMsg['Encrypt']) ? WeixinUtils::decryptWxMsg($arrayMsg['Encrypt']) : $arrayMsg;
    }
    return array();
  }

  /**
   * @desc 生成xml格式的微信消息(由业务逻辑直接echo即可)
   * @param array $msg
   * @return string
   */
  public static function genWxXmlMsg ($msg, $encrypt) {
    $wxMsg = array();
    if ($msg && $msg['openid'] && $msg['content'] && self::$WEIXIN_ACCOUNT_NAME) {
      $wxMsg['ToUserName'] = $msg['openid'];
      $wxMsg['FromUserName'] = $msg['FromUserName'] ? $msg['FromUserName'] : self::$WEIXIN_ACCOUNT_NAME;
      $wxMsg['CreateTime'] = $msg['CreateTime'] ? $msg['CreateTime'] : time();
      switch ($msg['msgtype']) {
      case 'text':
        $wxMsg['MsgType'] = 'text';
        $wxMsg['Content'] = $msg['content'];
        break;
      case 'image':
      case 'voice':
      case 'mpnews':
        $wxMsg['MsgType'] = $msg['msgtype'];
        $wxMsg[ucfirst($msgtype['msgtype'])] = array('MediaId' => $msg['content']);
        break;
      case 'video':
      case 'music':
        $wxMsg['MsgType'] = $msg['msgtype'];
        $wxMsg[ucfirst($msg['msgtype'])] = $params['content'];
        break;
      case 'news':
        $wxMsg['MsgType'] = $msg['msgtype'];
        $wxMsg['ArticleCount'] = count($msg['content']);
        $wxMsg['Articles'] = $msg['content'];
        break;
      default:
        $wxMsg['MsgType'] = 'text';
        $wxMsg['Content'] = $msg['content'];
        break;
      }
    }
    $isSigValidate = ($msg['timestamp'] && $msg['nonce'] && self::$WEIXIN_SERVER_TOKEN);
    if ($encrypt && $isSigValidate && $wxMsg && $wxMsg['ToUserName'] && $wxMsg['MsgType']) {
      $tmpWxMsg = $wxMsg;
      $encryptedMsg = WeixinUtils::encryptWxMsg(WeixinUtils::getXmlFromArray($tmpWxMsg));
      $msgSignature = ($encryptedMsg) ? WeixinUtils::getWxSignature(array($encryptedMsg, $msg['timestamp'], $msg['nonce'], self::$WEIXIN_SERVER_TOKEN)) : '';
      $wxMsg = array();
      $wxMsg['ToUserName'] = $tmpWxMsg['ToUserName'];
      $wxMsg['Encrypt'] = $encryptedMsg;
      $wxMsg['MsgSignature'] = $msgSignature;
      $wxMsg['TimeStamp'] = $msg['timestamp'];
      $wxMsg['Nonce'] = $msg['nonce'];
    }
    $isWxMsgValidated = ($wxMsg && $wxMsg['ToUserName'] && (($wxMsg['Encrypt'] && $wxMsg['MsgSignature']) || $wxMsg['MsgType']));
    if ($isWxMsgValidated) {
      return WeixinUtils::getXmlFromArray($wxMsg);
    }
    return '';
  }
}
