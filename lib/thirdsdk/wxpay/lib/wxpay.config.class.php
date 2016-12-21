<?php
/**
 * 微信支付配置类
 */

class WxpayConfig {

  public static $WXPAY_APP_ID = WXPAY_APP_ID;

  public static $WXPAY_APP_SECRET = WXPAY_APP_SECRET;

  public static $WXPAY_MCH_ID = WXPAY_MCH_ID;

  public static $WXPAY_API_KEY = WXPAY_API_KEY;

  public static $WXPAY_SSLCERT_PATH = WXPAY_SSLCERT_PATH;

  public static $WXPAY_SSLKEY_PATH = WXPAY_SSLKEY_PATH;

  public static $WXPAY_NOTIFY_URL = WXPAY_NOTIFY_URL;
  
  public static $WXPAY_SERVER_IP = WXPAY_SERVER_IP;

  /**
   * @desc 设置微信支付配置
   * @param array wxpay config
   * @param bool
   */
  public static function setWxpayConfig ($wxpayConfig) {
    if ($wxpayConfig) {
      foreach ($wxpayConfig as $key => $val) {
        if (!property_exists(__CLASS__, $key)) return FALSE;
        self::$$key = $val;
      }
      return TRUE;
    }
    return FALSE;
  }

  public static $wxpayApis = array(
    //微信支付付钱接口
    //普通红包
    'redpack' => array(
      'url' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack',
      'cert' => TRUE,
      'params' => array(
        'mch_billno' => '',
        'mch_id' => '',
        'wxappid' => '',
        're_openid' => '',
        'total_amount' => 0,
        'total_num' => 0,
        'nonce_str' => '',
        'sign' => NULL,
        'client_ip' => '',
        'send_name' => '赚钱宝',
        'wishing' => '感谢您的参与，祝您快乐！',
        'act_name' => '赚钱宝活动',
        'remark' => '快来赚钱吧！',
      ),
    ),
    //裂变红包
    'gredpack' => array(
      'url' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack',
      'cert' => TRUE,
      'params' => array(
        'mch_billno' => '',
        'mch_id' => '',
        'wxappid' => '',
        're_openid' => '',
        'total_amount' => 0,
        'total_num' => 0,
        'nonce_str' => '',
        'sign' => NULL,
        'amt_type' => 'ALL_RAND',
        'send_name' => '赚钱宝',
        'wishing' => '感谢您的参与，祝您快乐！',
        'act_name' => '赚钱宝活动',
        'remark' => '快来赚钱吧！',
      ),
    ),
    //代金券
    'coupon' => array(
      'url' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/send_coupon',
      'cert' => TRUE,
      'params' => array(
        'coupon_stock_id' => '',
        'partner_trade_no' => '',
        'openid' => '',
        'appid' => '',
        'mch_id' => '',
        'openid_count' => 1,
        'nonce_str' => '',
        'sign' => NULL,
        'op_user_id' => NULL,
        'device_info' => NULL,
        'version' => NULL,
        'type' => NULL,
      ),
    ),
    //微信转账
    'transfer' => array(
      'url' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers',
      'cert' => TRUE,
      'params' => array(
        'mch_appid' => '',
        'mchid' => '',
        'partner_trade_no' => '',
        'openid' => '',
        'amount' => 0,
        'nonce_str' => '',
        'sign' => NULL,
        'spbill_create_ip' => '',
        'desc' => '玩主团队向您转账,感谢您的参与',
        'check_name' => 'NO_CHECK',
        're_user_name' => NULL,
        'device_info' => NULL,
      ),
    ),
    //微信支付收钱接口
    //统一下单
    'unifiedorder' => array(
      'url' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
      'cert' => FALSE,
      'params' => array(
        'appid' => '',
        'mch_id' => '',
        'body' => '',
        'out_trade_no' => '',
        'total_fee' => 0,
        'spbill_create_ip' => '',
        'notify_url' => '',
        'trade_type' => 'JSAPI',
        'nonce_str' => '',
        'sign' => NULL,
        'device_info' => NULL,
        'detail' => NULL,
        'attach' => NULL,
        'fee_type' => NULL,
        'time_start' => NULL,
        'time_expire' => NULL,
        'goods_tag' => NULL,
        'product_id' => NULL,
        'limit_pay' => NULL,
        'openid' => NULL,
      ),
    ),
    //订单查询
    'orderquery' => array(
      'url' => 'https://api.mch.weixin.qq.com/pay/orderquery',
      'cert' => FALSE,
      'params' => array(
        'appid' => '',
        'mch_id' => '',
        'out_trade_no' => '',
        'nonce_str' => '',
        'sign' => NULL,
      ),
    ),
    //关闭订单
    'closeorder' => array(
      'url' => 'https://api.mch.weixin.qq.com/pay/closeorder',
      'cert' => FALSE,
      'params' => array(
        'appid' => '',
        'mch_id' => '',
        'out_trade_no' => '',
        'nonce_str' => '',
        'sign' => NULL,
      ),
    ),
    //申请退款
    'refund' => array(
      'url' => 'https://api.mch.weixin.qq.com/secapi/pay/refund',
      'cert' => TRUE,
      'params' => array(
        'appid' => '',
        'mch_id' => '',
        'transaction_id' => '',
        'out_trade_no' => '',
        'out_refund_no' => '',
        'total_fee' => 0,
        'refund_fee' => 0,
        'op_user_id' => '',
        'nonce_str' => '',
        'sign' => NULL,
        'device_info' => NULL,
        'refund_fee_type' => NULL,
      )
    ),
    //退款查询
    'refundquery' => array(
      'url' => 'https://api.mch.weixin.qq.com/pay/refundquery',
      'cert' => FALSE,
      'params' => array(
        'appid' => '',
        'mch_id' => '',
        'out_trade_no' => '',
        'nonce_str' => '',
        'sign' => NULL,
        'device_info' => NULL,
      ),
    ),
    //下载对账单
    'downloadbill' => array(
      'url' => 'https://api.mch.weixin.qq.com/pay/downloadbill',
      'cert' => FALSE,
      'params' => array(
        'appid' => '',
        'mch_id' => '',
        'bill_date' => '',
        'nonce_str' => '',
        'sign' => NULL,
        'device_info' => NULL,
        'bill_type' => 'ALL',
      ),
    ),
  );

  /**
   * 初始化API参数
   */
  public static function initApiParams ($api) {
    $params = array();
    $tmParams = self::$wxpayApis[$api]['params'];
    if ($tmParams) {
      foreach ($tmParams as $key => $val) {
        switch ($key) {
          case 'mch_billno':
          case 'partner_trade_no':
          case 'out_trade_no':
          case 'out_refund_no':
            $params[$key] = WxpayUtils::getMchBillno();
            break;
          case 'mch_id':
          case 'mchid':
          case 'op_user_id':
            $params[$key] = self::$WXPAY_MCH_ID;
            break;
          case 'wxappid':
          case 'appid':
          case 'mch_appid':
            $params[$key] = self::$WXPAY_APP_ID;
            break;
          case 'client_ip':
            $params[$key] = self::$WXPAY_SERVER_IP;
            break;
          case 'spbill_create_ip':
            $params[$key] = WxpayUtils::getHostAddress();
            break;
          case 'refund_fee_type':
            $params[$key] = 'CNY';
            break;
          case 'notify_url':
            $params[$key] = self::$WXPAY_NOTIFY_URL;
            break;
          case 'nonce_str':
            $params[$key] = WxpayUtils::getNonceStr();
            break;
          default:
            $params[$key] = $val;
            break;
        }
      }
    }
    return $params;
  }
}

