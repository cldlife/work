<?php
/**
 * 微信支付API参数设置类
 */

class WxpaySetup extends WxpayConfig {

  /**
   * 微信支付API参数设置方法入口
   */
  public static function setup ($name, $params) {
    $method = 'setup' . ucfirst($name) . 'Params';
    if (method_exists(__CLASS__, $method)) {
      $apiParams = self::$method($name, $params);
      if ($apiParams) {
        $apiParams['sign'] = WxpayUtils::getSign($apiParams);
        return $apiParams;
      }
    }
    return FALSE;
  }

  /**
   * 普通红包参数设置
   * @param array params
   * @return array params
   * 商户订单号       mch_billno   String(28)   每个订单号必须唯一
   * 商户号           mch_id       String(32)   微信支付分配的商户号
   * 公众账号appid    wxappid      String(32)   公众账号ID,即企业号corpid,公众号的appid是mp.weixin.qq.com申请的,不能为APP的appid(在open.weixin.qq.com申请的)
   * 用户openid       re_openid    String(32)   接收红包的种子用户(首个用户)用户在wxappid下的openid
   * 付款金额         total_amount int          红包发放总金额,即一组红包金额总和,包括分享者的红包和裂变的红包,单位分
   * 红包发放总人数   total_num    int          红包发放总人数,即总共有多少人可以领到该组红包(包括分享者)
   * 随机字符串       nonce_str    String(32)
   * 签名             sign         String(32)
   * 商户名称         send_name    String(32)   红包发送者名称
   * 红包祝福语       wishing      String(128)  红包祝福语 '感谢您参加活动,祝您快乐！'
   * IP地址           client_ip    String(15)   调用接口的机器Ip地址'190.168.0.1'
   * 活动名称         act_name     String(32)   活动名称   '猜灯谜抢红包活动'
   * 备注             remark       String(256)  备注信息   '猜越多得越多,快来抢！'
   */
  private static function setupRedpackParams ($name = 'redpack', $params) {
    $redpackApiParams = self::initApiParams($name);
    $apiParams = array();
    //设置红包金额上限1000元
    if ($params && $params['openid'] && ($params['amount'] > 0 && $params['amount'] <= 100000)) {
      foreach ($redpackApiParams as $key => $val) {
        if ($key == 'wxappid') {
          $apiParams[$key] = $params['appid'] ? $params['appid'] : $val;
        } else if ($key == 're_openid') {
          $apiParams[$key] = $params['openid'];
        } else if ($key == 'total_amount') {
          $apiParams[$key] = $params['amount'];
          $apiParams['total_num'] = 1;
        } else if ($key == 'send_name' && $params[$key]) {
          $apiParams[$key] = WxpayUtils::getLimitLengthStr($params[$key]);
        } else if (!$apiParams[$key] && ($params[$key] || $val !== NULL)) {
          $apiParams[$key] = $params[$key] ? $params[$key] : $val;
        }
      }
    } else if (APP_DEBUG) {
      //for dev debug
      throw new Exception('openid is null, money amount is null or too big');
    }
    return $apiParams;
  }

  /**
   * 裂变红包接口参数设置
   * @param array params
   * @return array params
   * 商户订单号       mch_billno   String(28)   每个订单号必须唯一
   * 商户号           mch_id       String(32)   微信支付分配的商户号
   * 公众账号appid    wxappid      String(32)   公众账号ID,即企业号corpid,公众号的appid是mp.weixin.qq.com申请的,不能为APP的appid(在open.weixin.qq.com申请的)
   * 用户openid       re_openid    String(32)   接收红包的种子用户(首个用户)用户在wxappid下的openid
   * 总金额           total_amount int          红包发放总金额,即一组红包金额总和,包括分享者的红包和裂变的红包,单位分
   * 红包发放总人数   total_num    int          红包发放总人数,即总共有多少人可以领到该组红包(包括分享者)
   * 随机字符串       nonce_str    String(32)
   * 签名             sign         String(32)
   * 商户名称         send_name    String(32)   红包发送者名称
   * 红包金额设置方式 amt_type     String(32)   ALL_RAND   红包金额设置方式:ALL_RAND—全部随机,商户指定总金额和红包发放总人数,由微信支付随机计算出各红包金额
   * 红包祝福语       wishing      String(128)  红包祝福语 '感谢您参加活动,祝您快乐！'
   * 活动名称         act_name     String(32)   活动名称   '猜灯谜抢红包活动'
   * 备注             remark       String(256)  备注信息   '猜越多得越多,快来抢！'
   */
  private static function setupGredpackParams ($name = 'gredpack', $params) {
    $gRedpackApiParams = self::initApiParams($name);
    $apiParams = array();
    //设置红包金额上限1000元
    if ($params && $params['openid'] && ($params['amount'] > 0 && $params['amount'] <= 100000) && $params['num']) {
      foreach ($gRedpackApiParams as $key => $val) {
        if ($key == 'wxappid') {
          $apiParams[$key] = $params['appid'] ? $params['appid'] : $val;
        } else if ($key == 're_openid') {
          $apiParams[$key] = $params['openid'];
        } else if ($key == 'total_amount') {
          $apiParams[$key] = $params['amount'];
        } else if ($key == 'total_num') {
          $apiParams['total_num'] = $params['num'];
        } else if ($key == 'send_name' && $params[$key]) {
          $apiParams[$key] = WxpayUtils::getLimitLengthStr($params[$key]);
        } else if (!$apiParams[$key] && ($params[$key] || $val !== NULL)) {
          $apiParams[$key] = $params[$key] ? $params[$key] : $val;
        }
      }
    } else if (APP_DEBUG) {
      //for dev debug
      throw new Exception('openid, num is null, money amount is null or too big');
    }
    return $apiParams;
  }

  /**
   * 代金券接口参数设置
   * @param array params
   * @return array params
   * 代金券批次id  coupon_stock_id  String      代金券批次id
   * openid记录数  openid_count     int         openid记录数(目前支持num=1)
   * 商户单据号    partner_trade_no String      商户此次发放凭据号(格式：商户id+日期+流水号),商户侧需保持唯一性
   * 用户openid    openid           String      Openid信息
   * 公众账号ID    appid            String(32)  微信分配的公众账号ID(企业号corpid即为此appId)
   * 商户号        mch_id           String(32)  微信支付分配的商户号
   * 随机字符串    nonce_str        String(32)  随机字符串,不长于32位
   * 签名          sign             String(32)  签名,具体参见3.2.1
   * 可选参数 XXX
   * 操作员        op_user_id       String(32)  操作员帐号, 默认为商户号,可在商户平台配置操作员对应的api权限
   * 设备号        device_info      String(32)  微信支付分配的终端设备号
   * 协议版本      version          String(32)  默认1.0
   * 协议类型      type             String(32)  XML【目前仅支持默认XML】
   */
  private static function setupCouponParams ($name = 'coupon', $params) {
    $couponApiParams = self::initApiParams($name);
    $apiParams = array();
    if ($params && $params['coupon_id'] && $params['openid']) {
      foreach ($couponApiParams as $key => $val) {
        if ($key == 'coupon_stock_id') {
          $apiParams[$key] = $params['coupon_id'];
        } else if (!$apiParams[$key] && ($params[$key] || $val !== NULL)) {
          $apiParams[$key] = $params[$key] ? $params[$key] : $val;
        }
      }
    } else if (APP_DEBUG) {
      //for dev debug
      throw new Exception('openid or coupon id is null');
    }
    return $apiParams;
  }

  /**
   * 企业付款转账接口参数设置
   * @param array params
   * @return array params
   * 公众账号appid     mch_appid         String      微信分配的公众账号ID(企业号corpid即为此appId)
   * 商户号            mchid             String(32)  微信支付分配的商户号
   * 商户订单号        partner_trade_no  String      商户订单号,需保持唯一性
   * 用户openid        openid            String      商户appid下,某用户的openid
   * 金额              amount            int         企业付款金额,单位为分
   * 随机字符串        nonce_str         String(32)  随机字符串,不长于32位
   * 签名              sign              String(32)  签名
   * 企业付款描述信息  desc              String      企业付款操作说明信息'理赔'
   * Ip地址            spbill_create_ip  String(32)  调用接口的机器Ip地址'192.168.0.1'
   * 校验用户姓名选项  check_name        String      NO_CHECK:不校验真实姓名; FORCE_CHECK:强校验真实姓名(未实名认证的用户会校验失败,无法转账);
   *                                                 OPTION_CHECK:针对已实名认证的用户才校验真实姓名(未实名认证用户不校验);
   *                                                 如果check_name设置为FORCE_CHECK或OPTION_CHECK,则必填用户真实姓名
   * 可选参数 XXX
   * 收款用户姓名  re_user_name  可选  马花花  String  收款用户真实姓名
   * 设备号  device_info  否  013467007045764  String(32)  微信支付分配的终端设备号
   */
  private static function setupTransferParams ($name = 'transfer', $params) {
    $transferApiParams = self::initApiParams($name);
    $apiParams = array();
    //设置转账金额上限1000元
    if ($params && $params['openid'] && ($params['amount'] > 0 && $params['amount'] <= 100000)) {
      foreach ($transferApiParams as $key => $val) {
        if (!$apiParams[$key] && ($params[$key] || $val !== NULL)) {
          $apiParams[$key] = $params[$key] ? $params[$key] : $val;
        }
      }
      //是否填写用户真实姓名
      if ($apiParams['check_name'] != 'NO_CHECK') {
        if ($params['user_name']) {
          $apiParams['re_user_name'] = $params['user_name'];
        } else {
          if (APP_DEBUG) throw new Exception('recieved transfer user name is null');
          return array();
        }
      }
    } else if (APP_DEBUG) {
      //for dev debug
      throw new Exception('openid or amount is null');
    }
    return $apiParams;
  }
  /**
   * 统一下单接口参数设置
   * @param array params
   * @return array params
   * 公众账号appid  appid             String      微信分配的公众账号ID(企业号corpid即为此appId)
   * 商户号         mch_id            String(32)  微信支付分配的商户号
   * 商户订单号     out_trade_no      String(32)  商户系统内部的订单号,可用mch_billno
   * 金额           total_fee         int         企业付款金额,单位为分
   * 商品描述       body              String(128) 商品或支付单简要描述'Ipad mini  16G  白色'
   * 终端IP         spbill_create_ip  String(16)  APP和网页支付提交用户端IP，Native支付填调用微信支付API的机器IP
   * 通知地址       notify_url        String(256) 接收微信支付异步通知回调地址,通知url必须为直接可访问的url,不能携带参数'http://www.weixin.qq.com/wxpay/pay.php'
   * 交易类型       trade_type        String(16)  取值如下:JSAPI,NATIVE,APP
   * 随机字符串     nonce_str         String(32)  随机字符串,不长于32位
   * 签名           sign              String(32)  签名
   *
   * 可选参数 XXX
   * 用户openid     openid            String       trade_type = JSAPI时,此参数必传
   * 商品ID         product_id        String(32)   trade_type = NATIVE,此参数必传.为二维码中的商品ID,商户自行定义
   * 设备号         device_info       String(32)   终端设备号(门店号或收银设备ID),注意:PC网页或公众号内支付请传"WEB"
   * 商品详情       detail            String(8192) 商品名称明细列表
   * 附加数据       attach            String(127)  附加数据,在查询API和支付通知中原样返回，用于商户订单的自定义数据
   * 货币类型       fee_type          String(16)   符合ISO 4217标准的三位字母代码,默认人民币:CNY
   * 交易起始时间   time_start        String(14)   订单生成时间,格式为yyyyMMddHHmmss
   * 交易结束时间   time_expire       String(14)   订单失效时间,格式为yyyyMMddHHmmss,注意:最短失效时间间隔必须大于5分钟
   * 商品标记       goods_tag         String(32)   商品标记,代金券或立减优惠功能的参数,详见代金券或立减优惠
   * 指定支付方式   limit_pay         String(32)   no_credit:指定不能使用信用卡支付
   */
  private static function setupUnifiedorderParams ($name = 'unifiedorder', $params) {
    $unifiedorderApiParams = self::initApiParams($name);
    $apiParams = array();
    $validate = (($params['type'] == 'JSAPI' && $params['openid']) || ($params['type'] == 'NATIVE' && $params['goods_id']) || $params['type'] == 'APP');
    if ($params && $params['type'] && $validate && $params['fee'] && $params['desc']) {
      foreach ($unifiedorderApiParams as $key => $val) {
        if ($key == 'trade_type') {
          $apiParams[$key] = $params['type'];
          $apiParams['total_fee'] = $params['fee'];
          if ($params['type'] == 'NATIVE') {
            $apiParams['product_id'] = $params['goods_id'];
          }
        } else if ($key == 'body') {
          $apiParams[$key] = $params['desc'];
        } else if (!$apiParams[$key] && ($params[$key] || $val !== NULL)) {
          $apiParams[$key] = $params[$key] ? $params[$key] : $val;
        }
      }
    } else if (APP_DEBUG) {
      //for dev debug
      throw new Exception('trade type or fee is error or null');
    }
    return $apiParams;
  }
  /**
   * 查询订单接口参数设置
   * @param array params
   * @return array params
   * 公众账号appid  appid             String      微信分配的公众账号ID(企业号corpid即为此appId)
   * 商户号         mch_id            String(32)  微信支付分配的商户号
   * 商户订单号     out_trade_no      String      商户订单号,需保持唯一性
   * 随机字符串     nonce_str         String(32)  随机字符串,不长于32位
   * 签名           sign              String(32)  签名
   */
  private static function setupOrderqueryParams ($name = 'orderquery', $params) {
    $orderqueryApiParams = self::initApiParams($name);
    $apiParams = array();
    if ($params && $params['trade_no']) {
      foreach ($orderqueryApiParams as $key => $val) {
        if ($key == 'out_trade_no') {
          $apiParams[$key] = $params['trade_no'];
        } else if (!$apiParams[$key] && ($params[$key] || $val !== NULL)) {
          $apiParams[$key] = $params[$key] ? $params[$key] : $val;
        }
      }
    } else if (APP_DEBUG) {
      //for dev debug
      throw new Exception('trade number is null');
    }
    return $apiParams;
  }
  /**
   * 关闭订单接口参数设置
   * @param array params
   * @return array params
   * 公众账号appid  appid             String      微信分配的公众账号ID(企业号corpid即为此appId)
   * 商户号         mch_id            String(32)  微信支付分配的商户号
   * 商户订单号     out_trade_no      String      商户订单号,需保持唯一性
   * 随机字符串     nonce_str         String(32)  随机字符串,不长于32位
   * 签名           sign              String(32)  签名
   */
  private static function setupCloseorderParams ($name = 'closeorder', $params) {
    $closeorderApiParams = self::initApiParams($name);
    $apiParams = array();
    if ($params && $params['trade_no']) {
      foreach ($closeorderApiParams as $key => $val) {
        if ($key == 'out_trade_no') {
          $apiParams[$key] = $params['trade_no'];
        } else if (!$apiParams[$key] && ($params[$key] || $val !== NULL)) {
          $apiParams[$key] = $params[$key] ? $params[$key] : $val;
        }
      }
    } else if (APP_DEBUG) {
      //for dev debug
      throw new Exception('trade number is null');
    }
    return $apiParams;
  }
  /**
   * 申请退款接口参数设置
   * @param array params
   * @return array params
   * 公众账号appid  appid             String      微信分配的公众账号ID(企业号corpid即为此appId)
   * 商户号         mch_id            String(32)  微信支付分配的商户号
   * 商户订单号     out_trade_no      String      商户订单号,需保持唯一性
   * 商户退款单号   out_refund_no     String(32)  商户系统内部的退款单号,商户系统内部唯一,同一退款单号多次请求只退一笔
   * 总金额         total_fee         Int         订单总金额,单位为分
   * 退款金额       refund_fee        Int         退款总金额,订单总金额,单位为分,只能为整数
   * 操作员         op_user_id        String(32)  操作员帐号,默认为商户号
   * 随机字符串     nonce_str         String(32)  随机字符串,不长于32位
   * 签名           sign              String(32)  签名
   *
   * 可选参数 XXX
   * 货币类型       refund_fee_type   String(16)  符合ISO 4217标准的三位字母代码,默认人民币:CNY
   */
  private static function setupRefundParams ($name = 'refund', $params) {
    $refundApiParams = self::initApiParams($name);
    $apiParams = array();
    if ($params && $params['trade_no']) {
      foreach ($refundApiParams as $key => $val) {
        if ($key == 'out_trade_no') {
          $apiParams[$key] = $params['trade_no'];
        } else if (!$apiParams[$key] && ($params[$key] || $val !== NULL)) {
          $apiParams[$key] = $params[$key] ? $params[$key] : $val;
        }
      }
    } else if (APP_DEBUG) {
      //for dev debug
      throw new Exception('trade number is null');
    }
    return $apiParams;
  }
  /**
   * 查询退款接口参数设置
   * @param array params
   * @return array params
   * 公众账号appid  appid             String      微信分配的公众账号ID(企业号corpid即为此appId)
   * 商户号         mch_id            String(32)  微信支付分配的商户号
   * 商户订单号     out_trade_no      String      商户订单号,需保持唯一性
   * 随机字符串     nonce_str         String(32)  随机字符串,不长于32位
   * 签名           sign              String(32)  签名
   *
   * 可选参数 XXX
   * 设备号         device_info       String(32)  终端设备号(门店号或收银设备ID),注意:PC网页或公众号内支付请传"WEB"
   */
  private static function setupRefundqueryParams ($name = 'refundquery', $params) {
    $refundqueryApiParams = self::initApiParams($name);
    $apiParams = array();
    if ($params && $params['trade_no']) {
      foreach ($refundqueryApiParams as $key => $val) {
        if ($key == 'out_trade_no') {
          $apiParams[$key] = $params['trade_no'];
        } else if (!$apiParams[$key] && ($params[$key] || $val !== NULL)) {
          $apiParams[$key] = $params[$key] ? $params[$key] : $val;
        }
      }
    } else if (APP_DEBUG) {
      //for dev debug
      throw new Exception('trade number is null');
    }
    return $apiParams;
  }
  /**
   * 下载对账单接口参数设置
   * @param array params
   * @return array params
   * 公众账号ID  appid        是  String(32)  微信分配的公众账号ID(企业号corpid即为此appId)
   * 商户号      mch_id       是  String(32)  微信支付分配的商户号
   * 随机字符串  nonce_str    是  String(32)  随机字符串,不长于32位
   * 签名        sign         是  String(32)  签名,详见签名生成算法
   * 对账单日期  bill_date    是  String(8)   下载对账单的日期,格式:20140603
   * 设备号      device_info  否  String(32)  微信支付分配的终端设备号
   * 账单类型    bill_type    否  String(8)   ALL,返回当日所有订单信息,默认值;SUCCESS,返回当日成功支付的订单;REFUND,返回当日退款订单
   */
  private static function setupDownloadbillParams ($name = 'downloadbill', $params) {
    $downloadbillApiParams = self::initApiParams($name);
    $apiParams = array();
    if ($params && $params['bill_date']) {
      foreach ($downloadbillApiParams as $key => $val) {
        if (!$apiParams[$key] && ($params[$key] || $val !== NULL)) {
          $apiParams[$key] = $params[$key] ? $params[$key] : $val;
        }
      }
    } else if (APP_DEBUG) {
      //for dev debug
      throw new Exception('trade number is null');
    }
    return $apiParams;
  }
}

