<?php
/**
 * 微信支付数据验证类
 */

class WxpayVerify extends WxpayConfig {

  /**
   * 微信支付接口数据验证方法入口
   */
  public static function verify ($name, $params) {
    $method = 'verify' . ucfirst($name) . 'Params';
    if (method_exists(__CLASS__, $method)) {
      return self::$method($params);
    } else {
      return FALSE;
    }
  }

  /**
   * 普通红包接口数据验证
   * @param array params
   * @return bool result
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
  private static function verifyRedpackParams ($params) {
    if (!($params && $params['mch_billno'] && $params['mch_id'] && $params['wxappid'] && $params['re_openid'])) {
      return FALSE;
    }
    //设置红包金额上限1000元
    if (!($params['total_amount'] <= 100000 && $params['total_num'] == 1 && $params['nonce_str'] && $params['sign'])) {
      return FALSE;
    }
    return ($params['send_name'] && $params['wishing'] && $params['client_ip'] && $params['act_name'] && $params['remark']);
  }

  /**
   * 裂变红包接口数据验证
   * @param array params
   * @return bool result
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
  private static function verifyGredpackParams ($params) {
    if (!($params && $params['mch_billno'] && $params['mch_id'] && $params['wxappid'] && $params['re_openid'])) {
      return FALSE;
    }
    //设置红包金额上限1000元
    if (!($params['total_amount'] <= 100000 && $params['total_num'] && $params['nonce_str'] && $params['sign'])) {
      return FALSE;
    }
    return ($params['send_name'] && $params['amt_type'] && $params['wishing'] && $params['act_name'] && $params['remark']);
  }

  /**
   * 代金券接口数据验证
   * @param array params
   * @return bool result
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
  private static function verifyCouponParams ($params) {
    if (!($params && $params['coupon_stock_id'] && $params['openid_count'] == 1 && $params['partner_trade_no'] && $params['openid'] && $params['appid'] && $params['mch_id'])) {
      return FALSE;
    }
    /*
    if (isset($params['type']) && $params['type'] != 'XML') {
      return FALSE;
    } */
    return ($params['nonce_str'] && $params['sign']);
  }

  /**
   * 企业付款转账接口数据验证
   * @param array params
   * @return bool result
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
   *                                                 OPTION_CHECK:针对已实名认证的用户才校验真实姓名(未实名认证用户不校验); 如果check_name设置为FORCE_CHECK或OPTION_CHECK,则必填用户真实姓名
   * 可选参数 XXX
   * 收款用户姓名  re_user_name  可选  马花花  String  收款用户真实姓名
   * 设备号  device_info  否  013467007045764  String(32)  微信支付分配的终端设备号
   */
  private static function verifyTransferParams ($params) {
    if (!($params && $params['mch_appid'] && $params['mchid'] && $params['partner_trade_no'] && $params['openid'])) {
      return FALSE;
    }
    //设置红包金额上限1000元
    if (!($params['amount'] <= 100000 && $params['nonce_str'] && $params['sign'])) {
      return FALSE;
    }
    //是否填写用户真实姓名
    if (!((($params['check_name'] == 'FORCE_CHECK' || $params['OPTION_CHECK']) && $params['re_user_name']) || $params['check_name'] == 'NO_CHECK')) {
      return FALSE;
    }
    return ($params['desc'] && $params['spbill_create_ip']);
  }

  /**
   * 统一下单接口数据验证
   * @param array params
   * @return bool result
   * 公众账号appid  appid             String      微信分配的公众账号ID(企业号corpid即为此appId)
   * 商户号         mch_id            String(32)  微信支付分配的商户号
   * 商户订单号     out_trade_no      String(32)  商户系统内部的订单号,可用mch_billno
   * 金额           total_fee         int         企业付款金额,单位为分
   * 商品描述       body              String(128) 商品或支付单简要描述'Ipad mini  16G  白色'
   * 终端IP         spbill_create_ip  String(16)  APP和网页支付提交用户端IP,Native支付填调用微信支付API的机器IP
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
   * 附加数据       attach            String(127)  附加数据,在查询API和支付通知中原样返回,用于商户订单的自定义数据
   * 货币类型       fee_type          String(16)   符合ISO 4217标准的三位字母代码,默认人民币:CNY
   * 交易起始时间   time_start        String(14)   订单生成时间,格式为yyyyMMddHHmmss
   * 交易结束时间   time_expire       String(14)   订单失效时间,格式为yyyyMMddHHmmss,注意:最短失效时间间隔必须大于5分钟
   * 商品标记       goods_tag         String(32)   商品标记,代金券或立减优惠功能的参数,详见代金券或立减优惠
   * 指定支付方式   limit_pay         String(32)   no_credit:指定不能使用信用卡支付
   */
  private static function verifyUnifiedorderParams ($params) {
    if (!($params && $params['appid'] && $params['mch_id'] && $params['out_trade_no'] && $params['total_fee'] > 0)) {
      return FALSE;
    }
    if (!($params['body'] && $params['spbill_create_ip'] && $params['notify_url'] && $params['nonce_str'] && $params['sign'])) {
      return FALSE;
    }
    if (!($params['trade_type'] && (($params['trade_type'] == 'JSAPI' && $params['openid']) || ($params['trade_type'] == 'NATIVE' && $params['product_id']) || $params['trade_type'] == 'APP'))) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * 查询订单接口数据验证
   * @param array params
   * @return bool result
   * 公众账号appid  appid             String      微信分配的公众账号ID(企业号corpid即为此appId)
   * 商户号         mch_id            String(32)  微信支付分配的商户号
   * 商户订单号     out_trade_no      String      商户订单号,需保持唯一性
   * 随机字符串     nonce_str         String(32)  随机字符串,不长于32位
   * 签名           sign              String(32)  签名
   */
  private static function verifyOrderqueryParams ($params) {
    return ($params && $params['appid'] && $params['out_trade_no'] && $params['nonce_str'] && $params['sign']);
  }

  /**
   * 关闭订单接口数据验证
   * @param array params
   * @return array params
   * 公众账号appid  appid             String      微信分配的公众账号ID(企业号corpid即为此appId)
   * 商户号         mch_id            String(32)  微信支付分配的商户号
   * 商户订单号     out_trade_no      String      商户订单号,需保持唯一性
   * 随机字符串     nonce_str         String(32)  随机字符串,不长于32位
   * 签名           sign              String(32)  签名
   */
  private static function verifyCloseorderParams ($params) {
    return ($params && $params['appid'] && $params['out_trade_no'] && $params['nonce_str'] && $params['sign']);
  }

  /**
   * 申请退款接口数据验证
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
  private static function verifyRefundParams ($params) {
    if (!($params && $params['appid'] && $params['mch_id'] && $params['out_trade_no'] && $params['out_refund_no'])) {
      return FALSE;
    }
    if (!($params['total_fee'] > 0 && $params['refund_fee'] > 0 && $params['refund_fee'] <= $params['total_fee'])) {
      return FALSE;
    }
    return ($params['op_user_id'] && $params['nonce_str'] && $params['sign']);
  }

  /**
   * 查询退款接口数据验证
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
  private static function verifyRefundqueryParams ($params) {
    return ($params && $params['appid'] && $params['mch_id'] && $params['out_trade_no'] && $params['nonce_str'] && $params['sign']);
  }

  /**
   * 下载对账单接口数据验证
   * @param array params
   * @return array params
   * 公众账号ID  appid        是  String(32)  微信分配的公众账号ID(企业号corpid即为此appId)
   * 商户号      mch_id       是  String(32)  微信支付分配的商户号
   * 对账单日期  bill_date    是  String(8)   下载对账单的日期,格式:20140603
   * 随机字符串  nonce_str    是  String(32)  随机字符串,不长于32位
   * 签名        sign         是  String(32)  签名,详见签名生成算法
   * 设备号      device_info  否  String(32)  微信支付分配的终端设备号
   * 账单类型    bill_type    否  String(8)   ALL,返回当日所有订单信息,默认值;SUCCESS,返回当日成功支付的订单;REFUND,返回当日退款订单
   */
  private static function verifyDownloadbillParams ($params) {
    return ($params && $params['appid'] && $params['mch_id'] && $params['bill_date'] && is_numeric($params['bill_date']) && $params['nonce_str'] && $params['sign']);
  }
}

