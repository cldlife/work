<?php
/**
 * 微信接口工具类
 */
class WeixinUtils {

  private static $BLOCK_SIZE = 32;

  /**
   * 对需要加密的明文进行填充补位
   * @param string $text 需要进行填充补位操作的明文
   * @return string 补齐的明文字符串
   */
  private static function padText ($text) {
    if ($text) {
      $blockSize = self::$BLOCK_SIZE;
      $textLen = strlen($text);
      //计算需要填充的位数
      $amount2Pad = $blockSize - ($textLen % $blockSize);
      if ($amount2Pad == 0) $amount2Pad = $blockSize;
      //获得补位所用的字符
      $padChar = chr($amount2Pad);

      $tmp = '';
      for ($i = 0; $i < $amount2Pad; $i++) {
        $tmp .= $padChar;
      }
      return $text . $tmp;
    }
    return '';
  }

  /**
   * 对解密后的明文进行补位删除
   * @param string $text 解密后的明文
   * @return string 删除填充补位后的明文
   */
  private static function unPadText ($text) {
    $pad = ord(substr($text, -1));
    if ($pad < 1 || $pad > 32) $pad = 0;
    return substr($text, 0, (strlen($text) - $pad));
  }

  /**
   * 随机生成16位字符串
   * @return string 生成的字符串
   */
  public static function getNonceStr ($length = 16) {
    $keyspace = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $keyspaceLen = strlen($keyspace) - 1;
    $str = '';
    for ($i = 0; $i < 16; $i++) {
      $str .= $keyspace[mt_rand(0, $keyspaceLen)];
    }
    return $str;
  }

  /**
   * 对明文进行加密
   * @param string $text 需要加密的明文
   * @return string 加密后的密文
   */
  public static function encryptWxMsg ($text) {
    if ($text) {
      //获得16位随机字符串，填充到明文之前
      $nonceStr = self::getNonceStr();
      $text = $nonceStr . pack("N", strlen($text)) . $text . WeixinConfig::$WEIXIN_APP_ID;
      //网络字节序
      $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
      $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');

      $aesKey = base64_decode(WeixinConfig::$WEIXIN_AES_KEY . '=');
      $iv = substr($aesKey, 0, 16);
      //使用自定义的填充方式对明文进行补位填充
      $text = self::padText($text);
      mcrypt_generic_init($module, $aesKey, $iv);
      //加密
      $encrypted = mcrypt_generic($module, $text);
      mcrypt_generic_deinit($module);
      mcrypt_module_close($module);
      //使用BASE64对加密后的字符串进行编码
      return base64_encode($encrypted);
    }
    return '';
  }

  /**
   * 对密文进行解密
   * @param string $encryptedText 需要解密的密文
   * @return string 解密得到的明文
   */
  public static function decryptWxMsg ($encryptedText) {
    if ($encryptedText) {
      //使用BASE64对需要解密的字符串进行解码
      $cipherText = base64_decode($encryptedText);
      $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');

      $aesKey = base64_decode(WeixinConfig::$WEIXIN_AES_KEY . '=');
      $iv = substr($aesKey, 0, 16);
      mcrypt_generic_init($module, $aesKey, $iv);
      //解密
      $decrypted = mdecrypt_generic($module, $cipherText);
      mcrypt_generic_deinit($module);
      mcrypt_module_close($module);
      //去除补位字符
      $result = self::unPadText($decrypted);

      //去除16位随机字符串,网络字节序和AppId
      if (strlen($result) < 16) return '';

      $content = substr($result, 16, strlen($result));
      $xmlLen = unpack("N", substr($content, 0, 4));
      $xmlLen = $xmlLen[1];
      $xmlContent = substr($content, 4, $xmlLen);
      $fromAppid = substr($content, $xmlLen + 4);
      if ($fromAppid != WeixinConfig::$WEIXIN_APP_ID) return '';

      return self::getArrayFromXml($xmlContent);
    }
    return '';
  }

  /**
   * 微信签名signature验证
   * @param array params
   * @return string sign
   */
  public static function getWxSignature ($params) {
    if ($params) {
      sort($params, SORT_STRING);
      return sha1(implode($params));
    }
    return '';
  }

  /**
   * 将xml转为array
   * @param string $xml
   * @return array converted xml
   */
  public static function getArrayFromXml ($xml) {
    $arr = array();
    if ($xml) {
      //将XML转为array,禁止引用外部xml实体
      libxml_disable_entity_loader(true);
      $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
    return $arr;
  }

  /**
   * 根据array参数输出xml字符
   * @param array params
   * @return string xml
   */
  public static function getXmlFromArray ($params, $node = 'xml') {
    $xml = "";
    if ($params) {
      $xml .= "<{$node}>";
      foreach ($params as $key => $val) {
        if (is_array($val)) {
          $xml .= self::getXmlFromArray($val, $key);
        } else {
          $xml .= "<{$key}><![CDATA[{$val}]]></{$key}>";
        }
      }
      $xml.= "</{$node}>";
    }
    return $xml;
  }

  /**
   * 向微信接口发送请求
   * @param string $content 请求数据
   * @param string $url url
   * @param string $method 请求方式,默认GET
   * @param int $second url执行超时时间,默认30s
   * @return string response data
   * TODO json_encode 默认会转义为unicode,微信不支持(php > 5.4.0)
   */
  public static function requestWxApi ($content, $url, $method, $second = 30) {
    if ($url && (($content && $method == 'POST') || (!$content && $method == 'GET'))) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_TIMEOUT, $second);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
      curl_setopt($ch, CURLOPT_HEADER, FALSE);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      if ($method == 'POST') {
        if (isset($content['media'])) {
          $content['media'] = curl_file_create($content['media']['path'], $content['media']['type'], $content['media']['name']);
        } else {
          $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        }
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
      }
      curl_setopt($ch, CURLOPT_URL, $url);
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
    }
    return FALSE;
  }
}
