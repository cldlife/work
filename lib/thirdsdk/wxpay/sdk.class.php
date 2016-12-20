<?php
require_once APP_CONFIG_THIRDSDK_DIR . '/wxpay/config.inc.php';
require_once APP_LIB_THIRD_SDK_DIR . '/wxpay/lib/wxpay.config.class.php';
require_once APP_LIB_THIRD_SDK_DIR . '/wxpay/lib/wxpay.api.class.php';
require_once APP_LIB_THIRD_SDK_DIR . '/wxpay/lib/wxpay.setup.class.php';
require_once APP_LIB_THIRD_SDK_DIR . '/wxpay/lib/wxpay.verify.class.php';
require_once APP_LIB_THIRD_SDK_DIR . '/wxpay/lib/wxpay.result.class.php';
require_once APP_LIB_THIRD_SDK_DIR . '/wxpay/lib/wxpay.utils.class.php';

/**
 * 微信支付类
 */
final class Wxpay {

  /**
   * @desc 设置微信支付配置
   * @param array wxpay config
   * @param bool
   */
  public function setWxpayConfig ($wxpayConfig) {
    return WxpayConfig::setWxpayConfig($wxpayConfig);
  }

  /**
   * 生成随机字符串
   * @return string 随机字符串
   */
  public function getNonceStr () {
    return WxpayUtils::getNonceStr();
  }

  /**
   * 生成微信sdk支付签名 (公众号支付)
   * @param array 参数数组
   * @return array 生成的签名数组
   * string package 'prepay_id=****'
   */
  public function getWxpaySdkSign ($params) {
    $signParams = array();
    if ($params['package']) {
      $signParams['package'] = $params['package'];
      $signParams['appId'] = $params['appId'] ? $params['appId'] : WxpayConfig::$WXPAY_APP_ID;
      $signParams['timeStamp'] = $params['timeStamp'] ? $params['timeStamp'] : time();
      $signParams['nonceStr'] = WxpayUtils::getNonceStr();
      $signParams['signType'] = 'MD5';
      $signParams['paySign'] = WxpayUtils::getSign($signParams);
    }
    return $signParams;
  }
  
  /**
   * 生成微信sdk app支付签名 (APP支付)
   * @param array 参数数组
   * @return array 生成的签名数组
   * string package Sign=WXPay
   */
  public function getWxpaySdkAppSign ($params) {
    $signParams = array();
    $signParams['package'] = 'Sign=WXPay';
    $signParams['partnerid'] = $params['partnerid'];
    $signParams['prepayid'] = $params['prepayid'];
    $signParams['appid'] = $params['appid'] ? $params['appid'] : WxpayConfig::$WXPAY_APP_ID;
    $signParams['timestamp'] = $params['timestamp'] ? $params['timestamp'] : time();
    $signParams['noncestr'] = WxpayUtils::getNonceStr();
    $signParams['sign'] = WxpayUtils::getSign($signParams);
    return $signParams;
  }

  /**
   * 发送普通红包
   * @param array 请求接口必须传的参数
   * openid 用户的openid
   * amount 红包的金额,以分为单位(限制不超过1000元)
   * @return array or bool
   */
  public function sendRedpack ($params) {
    if ($params && $params['openid'] && ($params['amount'] > 0 && $params['amount'] < 100000)) {
      return WxpayApi::send('redpack', $params);
    }
    return FALSE;
  }

  /**
   * 发送裂变红包
   * @param array 请求接口必须传的参数
   * openid 用户的openid
   * amount 红包的金额,以分为单位(限制不超过1000元)
   * num 红包发放总人数
   * @return array or bool
   */
  public function sendGroupRedpack ($params) {
    if ($params && $params['openid'] && ($params['amount'] > 0 && $params['amount'] < 100000) && $params['num']) {
      return WxpayApi::send('gredpack', $params);
    }
    return FALSE;
  }

  /**
   * 发送代金券
   * @param array 请求接口必须传的参数
   * coupon_id  代金券批次id
   * openid     用户的openid
   * @return array or bool
   */
  public function sendCoupon ($params) {
    if ($params && $params['coupon_id'] && $params['openid']) {
      return WxpayApi::send('coupon', $params);
    }
    return FALSE;
  }

  /**
   * 发起转账
   * @param array 请求接口必须传的参数
   * openid 用户的openid
   * amount 红包的金额,以分为单位(限制不超过1000元)
   * @return array or bool
   */
  public function sendTransfer ($params) {
    if ($params && $params['openid'] && $params['amount']) {
      return WxpayApi::send('transfer', $params);
    }
    return FALSE;
  }

  /**
   * 统一下单接口
   * @param array 请求接口必须传的参数
   * type  交易类型,默认JSAPI时,必填用户openid
   *    openid   用户的openid
   * 为NATIVE时必填商品ID
   *    goods_id String(32) 商品ID
   * fee  商品金额(以分为单位)
   * desc String(128) 商品描述
   * detail String(8192) 商品名称明细列表
   * attach String(127) 附加数据 （在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据）
   * @return array or bool
   */
  public function sendUnifiedorder ($params) {
    if ($params && $params['type'] && (($params['type'] == 'JSAPI' && $params['openid']) || ($params['type'] == 'NATIVE' && $params['goods_id']) || $params['type'] == 'APP') && $params['fee'] > 0 && $params['desc']) {
      return WxpayApi::send('unifiedorder', $params);
    }
    return FALSE;
  }


  /**
   * 查询订单接口
   * @param array 请求接口必须传的参数
   * trade_no 交易单号,请求下单时生成并返回
   * @return array or bool
   */
  public function sendOrderquery ($params) {
    if ($params && $params['trade_no']) {
      return WxpayApi::send('orderquery', $params);
    }
    return FALSE;
  }

  /**
   * 关闭订单接口
   * @param array 请求接口必须传的参数
   * trade_no 交易单号,请求下单时生成并返回
   * @return array or bool
   */
  public function sendCloseorder ($params) {
    if ($params && $params['trade_no']) {
      return WxpayApi::send('closeorder', $params);
    }
    return FALSE;
  }

  /**
   * 申请退款接口
   * @param array 请求接口必须传的参数
   * trade_no 交易单号,请求下单时生成并返回
   * @return array or bool
   */
  public function sendRefund ($params) {
    if ($params && $params['trade_no']) {
      return WxpayApi::send('refund', $params);
    }
    return FALSE;
  }

  /**
   * 查询退款请求接口
   * @param array 请求接口必须传的参数
   * trade_no 交易单号,请求下单时生成并返回
   * @return array or bool
   */
  public function sendRefundquery ($params) {
    if ($params && $params['trade_no']) {
      return WxpayApi::send('refundquery', $params);
    }
    return FALSE;
  }

  /**
   * 下载对账单接口(某一天的账单)
   * @param array 请求接口必须传的参数
   * bill_date 对账单日期,格式:20140603
   * @return array or bool
   */
  public function sendDownloadbill ($params) {
    if ($params && $params['bill_date']) {
      return WxpayApi::send('downloadbill', $params);
    }
    return FALSE;
  }

  /**
   * 获取支付结果通知消息
   * @param string xml $msg
   * @param bool $needSign 是否需要校验签名
   * @return array
   */
  public function getNotifyResults ($xmlMsg, $needSign = TRUE) {
    $results = array();
    if ($xmlMsg) {
      $msg = WxpayResult::translate('notify', $xmlMsg);
      if ($msg && (!$needSign || ($needSign && $msg['sign'] && $msg['sign'] == WxpayUtils::getSign($msg)))) {
        $msg['attach'] = $msg['attach'] ? json_decode($msg['attach'], TRUE) : array();
        return $msg;
      }
    }
    return $results;
  }

  /**
   * 生成支付结果通知消息
   * @param array $params
   * @return string xml
   */
  public function replyNotify ($reply) {
    return $reply ? WxpayUtils::getXmlFromArray($reply) : '';
  }
}
