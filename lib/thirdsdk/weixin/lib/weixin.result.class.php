<?php
/**
 * 微信支付结果处理类
 */

class WeixinResult extends WeixinConfig {

  /**
   * 微信支付接口结果处理方法入口
   * @param string api name
   * @param string weixin result
   * @return array
   */
  public static function translate ($api, $results) {
    $method = "translate_{$api}_results";
    $apiParams = self::$weixinApis[$api];

    if ($apiParams && $results) {
      $decodedResults = json_decode($results, TRUE);
      if (isset($decodedResults['errcode']) && $decodedResults['errcode']) {
        $logMsg = "weixin api failed, api:{$api};msg:{$decodedResults['errmsg']};errcode:{$decodedResults['errcode']}";
        if (APP_DEBUG) throw new Exception($logMsg);
        else Utils::log($logMsg, 'WeixinSdk');
      } else if ($api == 'get_pmt_material') {
        return (method_exists(__CLASS__, $method) && (isset($decodedResults['news_item']) || isset($decodedResults['title']))) ? self::$method($apiParams, $decodedResults) : $results;
      } else {
        return method_exists(__CLASS__, $method) ? self::$method($apiParams, $decodedResults) : $decodedResults;
      }
    } else if (APP_DEBUG) {
      throw new Exception('sending request failed, please check it.');
    }
    return array();
  }

  /**
   * @desc 获取微信用户信息
   * @param array $apiParams
   * @param array $results
   * @return array
   */
  private static function translate_get_user_info_results ($apiParams, $results) {
    return ($results) ? $results : array();
  }

  /**
   * @desc 创建临时二维码
   * @param array $apiParams 微信api config array key
   * @param array $results 请求api的结果
   * @return array
   */
  private static function translate_get_tmp_qrcode_results ($apiParams, $results) {
    if ($apiParams && $results && $results['ticket']) {
      $results['qr_url'] = $apiParams['qr_url'] . '?ticket=' . urlencode($results['ticket']);
      return $results;
    }
    return array();
  }

  /**
   * @desc 创建永久二维码
   * @param array $apiParams api预设的参数
   * @param array $results 请求api的结果
   * @return array
   */
  private static function translate_get_pmt_qrcode_results ($apiParams, $results) {
    if ($apiParams && $results && $results['ticket']) {
      $results['qr_url'] = $apiParams['qr_url'] . '?ticket=' . urlencode($results['ticket']);
      return $results;
    }
    return array();
  }

  /**
   * @desc 获取微信js-sdk的jsapi_ticket
   * @param array $apiParams api预设的参数
   * @param array $results 请求api的结果
   * @return array
   */
  private static function translate_get_jsapi_ticket_results ($apiParams, $results) {
    if ($apiParams && $results && $results['ticket']) {
      $results['jsapi_ticket'] = $results['ticket'];
      return $results;
    }
    return array();
  }

  /**
   * @desc 获取微信永久素材
   * @param array $apiParams api预设的参数
   * @param array $results 请求api的结果
   * @return array
   */
  private static function translate_get_pmt_material_results ($apiParams, $results) {
    if ($apiParams && $results) {
      if (isset($results['news_item']) && $results['news_item']) {
        $outResults['articles'] = array('articles' => array());
        foreach ($results['news_item'] as $article) {
          $outResults['articles'][] = $article;
        }
        $results = json_encode($outResults, JSON_UNESCAPED_UNICODE);
        unset($outResults);
      }
      return $results;
    }
    return array();
  }
}
