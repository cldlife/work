<?php
/**
 * @desc face++ api接口调用类
 */
class FaceppApi extends FaceppConfig {

  /**
   * @desc face++ api入口
   * @param string api map key
   * @param array api request param
   * @return array when success, bool when fail
   */
  public static function send ($api, $params) {
    $apiInfo = self::$apis[$api];
    if ($apiInfo) {
      $apiUrl = FaceppConfig::$apiBaseUrl . $apiInfo['api'];
      $requestParams = FaceppSetup::setup($api, $params);
      if ($apiUrl && $requestParams) {
        return FaceppResult::translate($api, self::request($apiUrl, $requestParams));
      }
    }
  }

  /**
   * @desc face++ http请求api方法
   * @param string request url
   * @param array/string request params
   * @return array
   */
  private static function request ($url, $params) {
    if ($url && $params) {
      $curl_handle = curl_init();
      curl_setopt($curl_handle, CURLOPT_URL, $url);
      curl_setopt($curl_handle, CURLOPT_FILETIME, true);
      curl_setopt($curl_handle, CURLOPT_FRESH_CONNECT, false);
      curl_setopt($curl_handle, CURLOPT_MAXREDIRS, 5);
      curl_setopt($curl_handle, CURLOPT_HEADER, false);
      curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl_handle, CURLOPT_TIMEOUT, 5184000);
      curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 120);
      curl_setopt($curl_handle, CURLOPT_NOSIGNAL, true);
      curl_setopt($curl_handle, CURLOPT_REFERER, $url);
      curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Faceplusplus PHP SDK/1.1');

      if (extension_loaded('zlib')) curl_setopt($curl_handle, CURLOPT_ENCODING, '');
      if (array_key_exists('img', $params)) {
        $params['img'] = curl_file_create($params['img']);
      } else {
        $params = http_build_query($params);
      }
      curl_setopt($curl_handle, CURLOPT_POST, TRUE);
      curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $params);
      $response = json_decode(curl_exec($curl_handle), TRUE);
      $response_header = curl_getinfo($curl_handle);
      curl_close($curl_handle);
      return array (
        'http_code' => $response_header['http_code'],
        'response' => $response,
      );
    }
    return array();
  }
}
