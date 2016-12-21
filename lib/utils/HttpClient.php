<?php
/**
 * @desc HttpClient工具类
 */
class HttpClient {

  static private $defaultUserAgent = array(
  	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36',
    'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_2 like Mac OS X) AppleWebKit/537.51.2 (KHTML, like Gecko) Mobile/11D257 MicroMessenger/6.3.16 NetType/WIFI'
  );
  
  /**
   * @desc init curl config
   */
  static private function initCurlConfig (Array $config) {
    $initConfig = array();
    foreach ($config as $k => $v) {
      if ($k == 'debug') {
        $initConfig['debug'] = $v ? TRUE : FALSE;
      } elseif ($k == 'mobile') {
        $initConfig['mobile'] = $v ? TRUE : FALSE;
      } elseif ($k == 'redirect') {
        $initConfig['redirect'] = $v ? TRUE : FALSE;
      } elseif ($k == 'method') {
        $initConfig['method'] = strtoupper($v) ? 'POST' : 'GET';
      } elseif ($k == 'timeout') {
        $initConfig['timeout'] = intval($v) ? intval($v) : 10;
      } elseif ($k == 'upload') {
        $initConfig['upload'] = $v ? TRUE : FALSE;
      } else {
        $initConfig[$k] = $v;
      }
    }
    
    //set default config
    if (!isset($initConfig['debug'])) $initConfig['debug'] = FALSE;
    if (!isset($initConfig['mobile'])) $initConfig['mobile'] = TRUE;
    if (!isset($initConfig['redirect'])) $initConfig['redirect'] = TRUE;
    if (!isset($initConfig['timeout'])) $initConfig['timeout'] = 10;
    if (!isset($initConfig['method'])) $initConfig['method'] = 'GET';
    return $initConfig;
  }
  
  /**
   * @desc http curl helper
   * @param string $url
   * @param array $config 配置 {'debug','method','timeout','upload','proxy','httpheader','cookie','redirect'}
   * @param array $data 请求参数 {}
   * @return array $output {'url','content','content_type'}
   */
  static public function curl ($url, $config = array(), $data = array()) {
    if (!$url) return array();
    
    //init config
    $initConfig = self::initCurlConfig($config);
    $urlInfo = parse_url($url);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, $initConfig['timeout'] * 2);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $initConfig['timeout']);
    curl_setopt($ch, CURLOPT_REFERER, $urlInfo['scheme'] . '://' . $urlInfo['host'] . '/'); 
    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate,sdch"); //兼容gzip数据
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    
    //debug::输出请求http header
    if ($initConfig['debug']) curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
    
    //模拟proxy请求(未设置默认随机)
    $isRand = TRUE;
    $proxyIp = mt_rand(8,255) .'.'. mt_rand(8,255) .'.'. mt_rand(8,255) .'.'. mt_rand(8,255);
    if ($initConfig['proxy'] && is_array($initConfig['proxy'])) {
      $isRand = FALSE;
      $key = array_rand($initConfig['proxy'], 1);
      $proxyIp = $initConfig['proxy'][$key];
      curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
      curl_setopt($ch, CURLOPT_PROXY, $proxyIp);
    }
    
    //set http header & cookie
    $httpHeader = array(
      'HOST: ' . $urlInfo['host'], 
      'Cache-Control: max-age=0', 
      'User-Agent: ' . ($initConfig['mobile'] ? self::$defaultUserAgent[1] : self::$defaultUserAgent[0]), 
      'X-FORWARDED-FOR: ' . $proxyIp,
      'CLIENT-IP: ' . $proxyIp
    );
    if ($initConfig['httpheader'] && is_array($initConfig['httpheader'])) {
      $httpHeader = array_merge($httpHeader, $initConfig['httpheader']);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader); 
    if ($initConfig['cookie']) curl_setopt($ch, CURLOPT_COOKIE, $initConfig['cookie']);
    
    //request
    if ($initConfig['method'] == "POST") {
      //非上传请求, $data需编码成urlencod字符串(避免curl,@开头的上传特性)
      if (is_array($data) && !$initConfig['upload']) {
        $postFields = '';
        foreach ($data as $k => $v) {
          $postFields .= $k . '=' . $v . '&';
        }
        $data = $postFields;
      }
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    } elseif ($initConfig['method'] == "GET") {
      //curl_setopt($ch, CURLOPT_POST, 0);
    }
    
    ob_start();
    curl_exec($ch);
    $content = ob_get_contents();
    ob_end_clean();
    $responseInfo = curl_getinfo($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    //debug::输出请求http header & error
    if ($initConfig['debug']) {
      $isRandString = $isRand ? '- Rand - ' : '';
      var_dump($error . "::{$isRandString}{$proxyIp}::");
      var_dump($responseInfo);
    }
    
    //http跳转(301/302)
    if ($responseInfo['redirect_url'] && $initConfig['redirect']) {
      return self::curl(Utils::getUrlWithParams($responseInfo['redirect_url'], array('redirected' => 1)));
    }
    
    //refresh跳转
    if (preg_match("/<meta[^\>]+http-equiv=[\"|\']?refresh[\"|\']?[^\>]+content=[\"|\'][^\>]+(url)=([^\>]+)[\"|\']\/?>/Ui", $content, $matches) || preg_match("/<script[^\>]{0,}>(document\.location|location|location\.href)=[\"|\'](.*)[\"|\'][^\<]{0,}<\/script>/Ui", $content, $matches)) {
      $redirectUrl = $matches[2];
      if ($redirectUrl) {
        
        //补全URL
        $redirectUrl = HtmlUtils::completeHttpUrl($url, $redirectUrl);
        $redirectUrlInfo = parse_url($redirectUrl);
        
        if (stripos($redirectUrl, 'from=noscript') === FALSE) {
          //避免自身url跳转
          if ($urlInfo['host'] . $urlInfo['path'] != $redirectUrlInfo['host'] . $redirectUrlInfo['path'] && $initConfig['redirect']) return self::curl(Utils::getUrlWithParams($redirectUrl, array('redirected' => 1)));
        }
      }
    }
    
    $output = array();
    $output['url'] = $url;
    $output['content'] = $content;
    $output['content_type'] = $responseInfo['content_type'];
    return $output;
  }
  
  static public function getAllHeaders(){
    $headers = array();
    $prefix = 'HTTP_';
    foreach ($_SERVER as $key => $value) {
      if (stripos($key, $prefix) === FALSE) continue;
      $tmpKey = str_replace($prefix, '', $key);
      $name = strtolower($tmpKey);
      
      if (stripos($name, '_') === FALSE) {
        $headers[$name] = urldecode($value);
      } else {
        $tmpName = str_replace("_", " ", $name);
        $name = ucwords($tmpName);
        $name = str_replace(" ", "-", $name);
        $headers[$name] = urldecode($value);
      }
    }
    
    return $headers;
  }
  
  static public function getHeader($key){
    $headers = self::getAllHeaders();
    return isset($headers[$key]) ? $headers[$key] : NULL;
  }
}