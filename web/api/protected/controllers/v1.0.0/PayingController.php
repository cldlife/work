<?php
/**
 * @desc 第3方支付接口
 */
class PayingController extends BaseController {
  
  //微信支付额外选项配置（fee金额，单位分int）
  private static $wxpayExtraConfig = array(
    'cz' => array('out_trace_no_template' => 'CZ{OID}U{UID}', 'desc' => '《玩主APP》购买金币'),
  );
  
  //Apple内购Url配置
  private static $applePayInappUrls = array(
    'https://buy.itunes.apple.com/verifyReceipt',
  	'https://sandbox.itunes.apple.com/verifyReceipt',
  );
  
  /**
   * @desc 微信支付统一下单
   * @return json
   */  
  public function actionWxpay () {
    $coinId = $this->getSafeRequest('coin_id', 0, 'int');
  
    try {
      $wxpayExtraConfig = self::$wxpayExtraConfig['cz'];
      $coinInfo = $this->globalAttributions['coins_list'][$coinId-1];
      if ($wxpayExtraConfig && $coinInfo) {
        
        //配置wxpayconfig
        $wxPayConfig = Yii::app()->params['wx_open_pay_config'];
        $this->getWxpayService()->setWxpayConfig($wxPayConfig);
        
        //调取微信支付（商户订单号不可重复）
        $outTradeNo = str_replace(array('{OID}', '{UID}'), array(Utils::longIdOnTimeGenerator(), $this->currentUser['uid']), $wxpayExtraConfig['out_trace_no_template']);
        $order = array(
          'type' => 'APP',
          'fee' => (int) bcmul($coinInfo['fee'], 100),
          'desc' => $wxpayExtraConfig['desc'],
          'out_trade_no' => $outTradeNo,
          'attach' => json_encode(array(
            'uid' => $this->currentUser['uid'],
            'coin_id' => $coinInfo['id'],
            'state' => Utils::generateCSRFSecret($coinInfo['id'] . $this->currentUser['uid'] . $outTradeNo)
          ))
        );
        $orderRes = $this->getWxpayService()->sendUnifiedorder($order);
        if ($orderRes['prepay_id']) {
          $data = array();
          $data['wxpayPre'] = $this->getWxpayService()->getWxpaySdkAppSign(array('partnerid' => $wxPayConfig['WXPAY_MCH_ID'], 'prepayid' => $orderRes['prepay_id']));
          $this->outputJsonData(0, $data);
        } else {
          $this->outputJsonData(701);
        }
      }
  
    } catch (Exception $e) {}
    $this->outputJsonData(702);
  }
  
  /**
   * @desc 微信支付回调接口
   * @return json
   */
  public function actionWxpayCallback () {
    try {
      $notifyMsg = file_get_contents('php://input');
      
      //配置wxpayconfig
      $wxPayConfig = Yii::app()->params['wx_open_pay_config'];
      $this->getWxpayService()->setWxpayConfig($wxPayConfig);
      $results = $notifyMsg ? $this->getWxpayService()->getNotifyResults($notifyMsg) : array();
    
      //TODO Test
      /**
      $results = array();
      $results['out_trade_no'] = 'CZ16102172637402610U1';
      $results['attach']['coin_id'] = 1;
      $results['attach']['uid'] = 1;
      $results['attach']['state'] = Utils::generateCSRFSecret($results['attach']['coin_id'] . $results['attach']['uid'] . $results['out_trade_no']);
      */
      
      if ($results) {
        $outTradeNo = $results['out_trade_no'];
        $state = $results['attach']['state'];
        $uid = $results['attach']['uid'];
        $coinId = $results['attach']['coin_id'];
        $coinInfo = $this->globalAttributions['coins_list'][$coinId-1];
        
        // 通知安全验证 & 防止多次执行
        $checkState = ($results['attach'] && $state && $state == Utils::generateCSRFSecret($coinId . $uid . $outTradeNo));
        $checkStateHit = $this->getCommonService()->getFromMemcache($checkState);
        if ($outTradeNo && $coinInfo && $checkState && !$checkStateHit) {
          try {
            $this->getCommonService()->setToMemcache($checkState, TRUE, self::FLOOD_LIMIT_TIME);
            $this->updateUserFortuneStatus($uid, $coinInfo);
          } catch (Exception $e) {
            Utils::log(__METHOD__ . ":: error:" . json_encode($e->getMessage()) . ", coin_id:{$coinId}, trade_no:{$outTradeNo}", "wxpay_buycoin_inapp");
          }
          
          echo $this->getWxpayService()->replyNotify(array(
            'return_code' => 'SUCCESS',
            'return_msg' => 'OK'
          ));
          exit();
        }
      }
    } catch (Exception $e) {}
    
    echo $this->getWxpayService()->replyNotify(array(
      'return_code' => 'FAIL',
      'return_msg' => '失败' 
    ));
    exit();
  }
  
  /**
   * @desc Apple内购验证收据
   * @return json
   * 21000 App Store不能读取你提供的JSON对象
   * 21002 receipt-data域的数据有问题
   * 21003 receipt无法通过验证
   * 21004 提供的shared secret不匹配你账号中的shared secret
   * 21005 receipt服务器当前不可用
   * 21006 receipt合法，但是订阅已过期。服务器接收到这个状态码时，receipt数据仍然会解码并一起发送
   * 21007 receipt是Sandbox receipt，但却发送至生产系统的验证服务
   * 21008 receipt是生产receipt，但却发送至Sandbox环境的验证服务
   */
  public function actionAppleInapp () {
    $coinId = $this->getSafeRequest('coin_id', 0, 'int');
    $receiptData = $this->getSafeRequest("receipt_data");
    $issandbox = $this->getSafeRequest("issandbox", NULL, 'int');
    
    $receiptUrl = self::$applePayInappUrls[$issandbox];
    $coinInfo = $this->globalAttributions['coins_list'][$coinId-1];
    if (!$coinInfo || !$receiptUrl || !$receiptData || strlen($receiptData) < 20) $this->outputJsonData(1);
    try {
      $result = HttpClient::curl($receiptUrl, array('method' => 'POST'), json_encode(array("receipt-data" => $receiptData)))['content'];
      $result = json_decode($result, TRUE);
      if ($result && $result['status'] == 0) {
        try {
          if (!$issandbox) $this->updateUserFortuneStatus($this->currentUser['uid'], $coinInfo);
        } catch (Exception $e) {
          Utils::log(__METHOD__ . ":: error:" . json_encode($e->getMessage()) . ", coin_id:{$coinId}, receipt_data:{$receiptData}", "apple_buycoin_inapp");
        }
        $this->outputJsonData();
      }
      
    } catch (Exception $e) {}
    $this->outputJsonData(1);
  }
  
  /**
   * @desc 更新用户财富状态
   */
  private function updateUserFortuneStatus ($uid, $coinInfo) {
    if (!$coinInfo) return FALSE;
    
    //更新喊话特权
    if ($coinInfo['privilege_public_num']) {
      $this->getUserFortuneService()->inDecreaseUserFortuneStatusByUid($uid, array(
        array('key' => 'privilege_public_num', 'value' => 1, 'in_de' => '+')
      ));
    }
    
    //更新金币
    $this->getUserFortuneService()->autoUserFortuneCoin($uid, $coinInfo['rule_id']);
    
    //更新积分
    if ($coinInfo['point']) {
      $coinIdPointRuleIdMap = array(3 => 12, 4 => 13); 
      $this->getUserFortuneService()->autoUserFortunePoint($uid, $coinIdPointRuleIdMap['rule_id']);
    }
    return TRUE;
  }
}
?>