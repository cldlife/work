<?php
/**
 * 微信支付工具类
 */
class WxpayUtils {

  /**
   * 获取用户客户端ip
   * copied from CHttpRequest:getUserHostAddress(), modified though
   * @return string
   */
  public static function getHostAddress () {
    $ip = "127.0.0.1";
    $vars = array('REMOTE_ADDR', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_VIA');
    foreach ($vars as $var) {
      if (isset($_SERVER[$var]) && $_SERVER[$var]) {
        $ip = $_SERVER[$var];
        break;
      }
    }
    //获取到两个ip时，取第1个客户端ip，去掉第2个代理ip
    $ipExp = explode(',', $ip);
    $ip = trim($ipExp[0]);
    return $ip;
  }

  /**
   * 微信请求数据签名算法
   * @param array params
   * @return string sign
   */
  public static function getSign ($params) {
    //签名步骤1:按字典序排序参数
    ksort($params);
    //签名步骤2:根据参数数组生成字符串
    $paramsStr = '';
    foreach ($params as $key => $val) {
      if ($key != 'sign' && $val) {
        $paramsStr .= $key . '=' . $val . '&';
      }
    }
    //签名步骤3:在str后加入KEY
    $paramsStr = $paramsStr . "key=" . WxpayConfig::$WXPAY_API_KEY;
    //签名步骤4:MD5加密
    $paramsStr = md5($paramsStr);
    //签名步骤5:所有字符转为大写
    return strtoupper($paramsStr);
  }

  /**
   * 产生微信支付唯一订单号partner_trade_no || mch_billno || out_trade_no
   * 组成:mch_id+yyyymmdd+10位一天内不能重复的数字
   * 接口根据商户订单号支持重入,如出现超时可再调用
   * @return string mch bill number
   */
  public static function getMchBillno () {
    return WxpayConfig::$WXPAY_MCH_ID . date('Ymd') . (string)mt_rand(10, 99) . substr(microtime(), 2, 4) . (string)mt_rand(1000, 9999);
  }

  /**
   * 产生随机字符串，不长于32位
   * @param int length
   * @return string nonce str
   */
  public static function getNonceStr ($length = 32) {
    $keyspace = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $keyspaceLen = strlen($keyspace) - 1;
    $str = '';
    for ($i = 0; $i < $length; $i++)  {
      $str .= $keyspace[mt_rand(0, $keyspaceLen)];
    }
    return $str;
  }

  /**
   * 截取字符串，不长于32个字符
   * @return string $str
   * @param int $length
   */
  public static function getLimitLengthStr ($str, $length = 32, $charset = 'UTF-8', $defaultStr = '朋友') {
    if ($str) {
      if (preg_match_all('/[a-zA-Z\d\.\s-_\(\)\x{4e00}-\x{9fff}]+/u', $str, $matches)) {
        $str = implode('', $matches[0]);
      } else {
        $str = $defaultStr;
      }
      if (strlen($str) <= $length)
        return $str;

      $sublen = floor($length / 2);
      do {
        $str = mb_substr($str, 0, $sublen, $charset);
        --$sublen;
      } while (strlen($str) > $length);
    }
    return $str;
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
  public static function getXmlFromArray ($params) {
    $xml = "";
    if ($params) {
      $xml .= "<xml>";
      foreach ($params as $key => $val) {
        $xml .= "<{$key}><![CDATA[{$val}]]></{$key}>";
      }
      $xml.= "</xml>";
    }
    return $xml;
  }

  /**
   * 以post方式提交xml到对应的接口url
   *
   * @param string $xml 需要post的xml数据
   * @param string $url url
   * @param bool $useCert 是否需要证书,默认不需要
   * @param int $second url执行超时时间,默认30s
   * @return xml request data
   */
  public static function sendXmlToApi ($xml, $url, $useCert = FALSE, $second = 30) {
    if ($xml && $url) {
      $ch = curl_init();
      //设置超时
      curl_setopt($ch, CURLOPT_TIMEOUT, $second);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
      //设置header
      curl_setopt($ch, CURLOPT_HEADER, FALSE);
      //要求结果为字符串且输出到屏幕上
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

      if($useCert) {
        //设置证书 使用证书:cert 与 key 分别属于两个.pem文件
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLCERT, WxpayConfig::$WXPAY_SSLCERT_PATH);
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLKEY, WxpayConfig::$WXPAY_SSLKEY_PATH);
      }
      //post提交
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
      //运行curl
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
    }
    return FALSE;
  }
}
