<?php
/**
 * PHP SDK for Wxpay
 */

class WxpayApi extends WxpayConfig {

  //微信支付API统一调用入口
  public static function send ($name, $params) {
    $api = self::$wxpayApis[$name];
    if ($api) {
      $apiParams = WxpaySetup::setup($name, $params);
      if ($apiParams && (WxpayVerify::verify($name, $apiParams))) {
        //请求接口数据并返回处理结果
        return WxpayResult::translate($name, WxpayUtils::sendXmlToApi(WxpayUtils::getXmlFromArray($apiParams), $api['url'], $api['cert']), $apiParams);
      }
    }
    return FALSE;
  }

  //微信支付回调通知入口
  public static function notify ($msg, $needSign = TRUE) {
    $results = WxpayResult::translate('notify', WxpayUtils::getArrayFromXml($msg));
    if ($results) {
      if ($needSign) {
        if ($results['sign'] == WxpayUtils::getSign($results)) return $results;
      } else {
        return $results;
      }
    }
    return FALSE;
  }
}
