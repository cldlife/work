<?php
/**
 * 微信支付结果处理类
 */

class WxpayResult {

  /**
   * 微信支付接口结果处理方法入口
   * @param string api name
   * @param string weixin result
   * @return array or bool
   */
  public static function translate ($name, $results, $apiParams = array()) {
    $method = 'translate' . ucfirst($name) . 'Results';
    if (method_exists(__CLASS__, $method) && $results) {
      $resArray = WxpayUtils::getArrayFromXml($results);
      if ($resArray['return_code'] != 'SUCCESS' && $name != 'downloadbill') {
        $logMsg = "wxpay return_code failed, api:{$name},msg:{$resArray['return_msg']};params:". json_encode($apiParams);
        if (APP_DEBUG) throw new Exception($logMsg);
        else Utils::log($logMsg, 'WxpaySdk');
      } else if ($resArray['result_code'] != 'SUCCESS' && $name != 'downloadbill') {
        $logMsg = "wxpay result_code failed, api:{$name},msg:{$resArray['err_code_des']};params:". json_encode($apiParams);
        if (APP_DEBUG) throw new Exception($logMsg);
        else Utils::log($logMsg, 'WxpaySdk');
      } else {
        return self::$method($resArray);
      }
    } else if (APP_DEBUG) {
      throw new Exception('request params may have some problem, please check it.');
    }
    return FALSE;
  }

  /**
   * 普通红包接口返回结果处理
   * @param array results
   * @return array result
   * trade_no 交易单号
   * amount 红包金额
   * send_time 红包发送时间
   * wx_trade_no 微信内部单号
   */
  private static function translateRedpackResults ($results) {
    $resArray = array();
    if ($results) {
      $resArray['trade_no'] = $results['mch_billno'];
      $resArray['amount'] = $results['total_amount'];
      $resArray['send_time'] = $results['send_time'];
      $resArray['wx_trade_no'] = $results['send_listid'];
    }
    return $resArray;
  }

  /**
   * 裂变红包接口返回结果处理
   * @param array results
   * @return array result
   * trade_no 交易单号
   * amount 红包金额
   * send_time 红包发送时间
   * wx_trade_no 微信内部单号
   */
  private static function translateGredpackResults ($results) {
    $resArray = array();
    if ($results) {
      $resArray['trade_no'] = $results['mch_billno'];
      $resArray['amount'] = $results['total_amount'];
      $resArray['send_time'] = $results['send_time'];
      $resArray['wx_trade_no'] = $results['send_listid'];
    }
    return $resArray;
  }

  /**
   * 代金券接口返回结果处理
   * @param array results
   * @return array result
   * coupon_id 代金券ID
   */
  private static function translateCouponResults ($results) {
    $resArray = array();
    if ($results && $results['ret_code'] != 'SUCCESS') {
      if (APP_DEBUG) throw new Exception('user get coupon failed, msg:' . $results['ret_msg']);
    } else {
      $resArray['coupon_id'] = $results['coupon_id'];
    }
    return $resArray;
  }

  /**
   * 企业付款转账接口返回结果处理
   * @param array results
   * @return array result
   * trade_no      交易单号
   * wx_trade_no   微信内部订单号
   * wx_pay_time   微信支付成功时间
   */
  private static function translateTransferResults ($results) {
    $resArray = array();
    if ($results) {
      $resArray['trade_no'] = $results['partner_trade_no'];
      $resArray['wx_trade_no'] = $results['payment_no'];
      $resArray['wx_pay_time'] = $results['payment_time'];
    }
    return $resArray;
  }

  /**
   * 统一下单接口返回结果处理
   * @param array results
   * @return array result
   * type  交易类型
   * prepay_id   预支付交易会话标识
   * code_url    微信支付二维码链接
   */
  private static function translateUnifiedorderResults ($results) {
    $resArray = array();
    if ($results) {
      $resArray['type'] = $results['trade_type'];
      $resArray['prepay_id'] = $results['prepay_id'];
      if ($results['trade_type'] == 'NATIVE') $resArray['code_url'] = $results['code_url'];
    }
    return $resArray;
  }

  /**
   * 查询订单接口返回结果处理
   * @param array results
   * @return array result
   * trade_no     交易订单号
   * wx_trade_no  微信内部交易订单号
   * type         交易类型:JSAPI,NATIVE,APP,MICROPAY
   * state        交易状态:SUCCESS-支付成功,REFUND-转入退款,NOTPAY-未支付,CLOSED-已关闭,REVOKED-已撤销(刷卡支付),USERPAYING-用户支付中,PAYERROR-支付失败(其他原因,如银行返回失败)
   * state_desc   交易状态描述
   * bank         付款银行类型,如CNC
   * fee          订单总金额,单位为分
   * cash         现金支付金额
   * attach       附加数据:附加数据，原样返回
   * time         订单支付时间,格式为yyyyMMddHHmmss
   * is_subscribe 用户是否关注公众账号,Y-关注,N-未关注,仅在公众账号类型支付才出现
   */
  private static function translateOrderqueryResults ($results) {
    $resArray = array();
    if ($results) {
      $resArray['trade_no'] = $results['out_trade_no'];
      $resArray['wx_trade_no'] = $results['transaction_id'];
      $resArray['type'] = $results['trade_type'];
      $resArray['state'] = $results['trade_state'];
      $resArray['state_desc'] = $results['trade_state_desc'];
      $resArray['bank'] = $results['bank_type'];
      $resArray['fee'] = $results['total_fee'];
      $resArray['cash'] = $results['cash_fee'];
      $resArray['attach'] = $results['attach'];
      $resArray['time'] = $results['time_end'];
      if ($results['is_subscribe']) $resArray['is_subscribe'] = $resArray['is_subscribe'];
    }
    return $resArray;
  }

  /**
   * 关闭订单接口返回结果处理
   * @param array results
   * @return array results
   * code 错误码 0无错误,大于0有错
   */
  private static function translateCloseorderResults ($results) {
    $resArray = array();
    if ($results) {
      $resArray['code'] = 0;
    }
    return $resArray;
  }

  /**
   * 申请退款接口返回结果处理
   * @param array results
   * @return array results
   * trade_no        微信订单号 transaction_id
   * wx_trade_no     商户订单号 out_trade_no
   * refund_no       商户退款单号 out_refund_no
   * wx_refund_no    微信退款单号 refund_id
   * refund_channel  退款渠道 refund_channel
   * refund_fee      退款金额
   * fee             订单总金额 total_fee
   * cash            现金支付金额 cash_fee
   * 不是一定会返回
   * fee_type        订单金额货币种类 fee_type
   * refund_cash_fee  现金退款金额 cash_refund_fee
   */
  private static function translateRefundResults ($results) {
    $resArray = array();
    if ($resArray) {
      $resArray['trade_no'] = $results['transaction_id'];
      $resArray['wx_trade_no'] = $results['out_trade_no'];
      $resArray['refund_no'] = $results['out_refund_no'];
      $resArray['wx_refund_no'] = $results['refund_id'];
      $resArray['refund_channel'] = $results['refund_channel'];
      $resArray['refund_fee'] = $results['refund_fee'];
      $resArray['fee'] = $results['total_fee'];
      $resArray['cash'] = $results['cash_fee'];
      if ($results['fee_type']) $resArray['fee_type'] = $results['fee_type'];
      if ($results['refund_cash_fee']) $resArray['refund_cash_fee'] = $results['refund_cash_fee'];
    }
    return $resArray;
  }

  /**
   * 查询退款接口返回结果处理
   * @param array results
   * @return array results
   * trade_no       商户订单号        out_trade_no       商户系统内部的订单号
   * wx_trade_no    微信订单号        transaction_id     微信订单号
   * fee            订单总金额        total_fee          订单总金额,单位为分,只能为整数,详见支付金额
   * cash           现金支付金额      cash_fee           现金支付金额,单位为分,只能为整数,详见支付金额
   * refund_count   退款笔数          refund_count       退款记录数
   * refund_no      商户退款单号      out_refund_no      商户退款单号
   * wx_refund_no   微信退款单号      refund_id          微信退款单号
   * refund_fee     退款金额          refund_fee         退款总金额,单位为分,可以做部分退款
   * status         退款状态          refund_status      退款状态:SUCCESS-退款成功 FAIL-退款失败 PROCESSING-退款处理中 NOTSURE-未确定,需要商户原退款单号重新发起
   *                                                           CHANGE-转入代发,发现用户卡作废或冻结,原路退款银行卡失败,资金回流到商户的现金帐号,需要商户人工干预,通过线下或者财付通转账的方式进行退款
   * recv_account   退款入账账户      refund_recv_accout  退款单的退款入账方,1,退回银行卡:{银行名称}{卡类型}{卡尾号} 2,退回支付用户零钱:支付用户零钱
   * 不是一定会返回
   * fee_type       订单金额货币种类  fee_type             CNY  订单金额货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
   * refund_channel 退款渠道          refund_channel      ORIGINAL—原路退款 BALANCE—退回到余额
   */
  private static function translateRefundqueryResults ($results) {
    $resArray = array();
    if ($results) {
      $resArray['trade_no'] = $results['trade_no'];
      $resArray['wx_trade_no'] = $results['wx_trade_no'];
      $resArray['fee'] = $results['total_fee'];
      $resArray['cash'] = $results['cash_fee'];
      $resArray['refund_count'] = $results['refund_count'];
      $resArray['refund_no'] = $results['out_refund_no'];
      $resArray['wx_refund_no'] = $results['refund_id'];
      $resArray['refund_fee'] = $results['refund_fee'];
      $resArray['status'] = $results['refund_status'];
      $resArray['recv_account'] = $results['recv_account'];
      if ($results['fee_type']) $resArray['fee_type'] = $results['fee_type'];
      if ($results['refund_channel']) $resArray['refund_channel'] = $results['refund_channel'];
    }
    return $resArray;
  }

  /**
   * 下载对账单接口返回结果处理
   * @param array results
   * @return string 账单数据表格
   */
  private static function translateDownloadbillResults ($results) {
    return $results ? $results : '';
  }
  
  /**
   * 微信支付回调通知返回结果处理
   * @param array results
   * @return array()
   */
  private static function translateNotifyResults ($results) {
    return $results ? $results : array();
  }
}
