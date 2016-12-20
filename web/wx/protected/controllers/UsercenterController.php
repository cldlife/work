<?php
/**
 * @desc 微信我是卧底个人中心
 * @author dong
 */
class UsercenterController extends BaseController {

  //充值支付的desc
  private static $default = '谁是卧底';

  //金额对应金币数 金额=>金币规则ID 6=>188,7=>888,8=>5088,9=>23888
  private static $coinidRuleIdMap = array(6 => 6, 18 => 7, 88 => 8, 388 => 9);

  //分享标题
  private static $shareTitle = '***邀请你一起来玩在线谁是卧底！来互相伤害啊!';
  
  //微信支付配置（fee金额，单位分int）
  private static $wxpayExtraConfig = array(
    'cj' => array('nofity_uri' => '/usercenter/wxpayrecharge.html', 'out_trace_no_template' => 'C{UID}J', 'desc' => '充值金币'),
  );
  
  //分享链接
  private static $sharelink = 'hougong/mine/u';

  //游戏列表
  private static $gameList = array(
    //array('id' => '0', 'href' =>'hougong/index', 'img' => 'img/wx/usercenter/show_hougong.png'),
    array('id' => '1', 'href' =>'room/roomlist', 'img' => 'img/wx/usercenter/show_wodi.jpg'),
  );
  
  //个人中心页面
  public function actionIndex(){
    $this->layout = "main_usercenter";
    $this->title = "个人中心";
    $uid = $this->currentUser['uid'];
    $openid = $this->currentUser['openid'];

    if ($uid) {
      $step = $this->getSafeRequest('step', 0, 'GET', 'int'); 
      $data = array();
      $wxPayConfig = Yii::app()->params['wxPayConfig'];
      $data['weixinJssdkConfig'] = $this->getCommonService()->getJssdkConfigByAppid($wxPayConfig['WXPAY_APP_ID'], $wxPayConfig['WXPAY_APP_SECRET']); 
      $data['wxpayParams'] = json_encode(array('action' => 'cj', 'uid' => $uid, 'oid' => $openid, '_sh_token_' => Yii::app()->request->getCsrfToken())); 
      $userInfo = $this->getUserService()->getUserByUid($uid);
      $userStatus = $this->getUserFortuneService()->getUserFortuneStatusByUid($uid);
      $gameList = self::$gameList;
      $data['gameList'] = self::$gameList;
      $data['shareTitle'] = str_replace('***', $this->currentUser['nickname'], self::$shareTitle);
      $data['sharelink'] = self::$sharelink.$this->currentUser['uid'].'?share=1';
      $data['avatar'] = $userInfo['avatar'];
      $data['nickname'] = $userInfo['nickname'];
      $data['values'] = $userStatus['values'];
      $data['coins'] = $userStatus['coins'];
      $this->render('mine', $data);  
    }
  }

   /**
   * @desc 微信支付统一下单（ajax）
   */
  public function actionWxPay () {
    $action = $this->getSafeRequest('action', '', 'POST');
    $fee = $this->getSafeRequest('fee', 0, 'POST', 'int');
    $uid = $this->getSafeRequest('uid', 0, 'POST', 'int');
    $openid = $this->getSafeRequest('oid', '', 'POST');

    /*do test*/
    /*$action = 'cj';
    $default = '我是卧底';
    $fee = '20';
    $uid = '20';
    $openid = 'oWb3XwH--Rn5xmUXFzTefQ7ewq9Y';
    /**/  
    try {
      $wxpayExtraConfig = self::$wxpayExtraConfig[$action];
      if ($action && $wxpayExtraConfig && $fee && $uid && $openid) {
       
        //配置wxpayconfig
        $wxPayConfig = Yii::app()->params['wxPayConfig'];
        $this->getWxpayService()->setWxpayConfig($wxPayConfig);
        //调取微信支付（商户订单号不可重复）
        $outTradeNo = str_replace('{UID}', $uid, $wxpayExtraConfig['out_trace_no_template']) . time(). rand(10, 99);

        //获取金币数规则
        $ruleId = self::$coinidRuleIdMap[$fee];

        $order = array(
          'type' => 'JSAPI',
          'openid' => $openid,
          'fee' => $fee*100,
          'desc' => "《".self::$default."》" . $wxpayExtraConfig['desc'],
          'out_trade_no' => $outTradeNo,
          'attach' => json_encode(array(
            'uid' => $uid,
            'rule_id' => $ruleId,
            'state' => Utils::generateCSRFSecret($uid . $ruleId . $outTradeNo)
          ))
        );
        $orderRes = $this->getWxpayService()->sendUnifiedorder($order);
        if ($orderRes['prepay_id']) {
          $package = 'prepay_id=' . $orderRes['prepay_id'];
          $wxpayPre = $this->getWxpayService()->getWxpaySdkSign(array('package' => $package));
          $this->outputJsonData(1, array(
            'wxpayPre' => $wxpayPre,
          ));
        } else {
          $this->outputJsonData(-2);
        }
      }
  
    } catch (Exception $e) {
      $this->outputJsonData(-1);
    }
    $this->outputJsonData(0);
  }

  /**
   * @desc 充值支付回调（notify_url）
   */
  public function actionWxPayRecharge(){
    try {
      $notifyMsg = file_get_contents('php://input');
      //配置wxpayconfig
      $wxPayConfig = Yii::app()->params['wxPayConfig'];
      $this->getWxpayService()->setWxpayConfig($wxPayConfig);
      $results = $notifyMsg ? $this->getWxpayService()->getNotifyResults($notifyMsg) : array();
      //Utils::log($notifyMsg . ", json:". json_encode($results), 'wxpay_recharge');
  
      //TODO Test
      // $results = array();
      // $results['out_trade_no'] = 'C20J147419078037';
      // $results['attach']['uid'] = 20;
      // $results['attach']['coins'] = 6;
      // $results['attach']['state'] = Utils::generateCSRFSecret($results['attach']['uid'] . $results['attach']['coins'] . $results['out_trade_no']);
      /**/
      
      if ($results) {

        $outTradeNo = $results['out_trade_no'];
        $state = $results['attach']['state']; 
        $uid = $results['attach']['uid'];
        $ruleId = $results['attach']['rule_id'];
        
        //通知安全验证
        $checkState = ($results['attach'] && $state && $uid && $ruleId && $state == Utils::generateCSRFSecret($uid . $ruleId . $outTradeNo));
        if ($checkState) {
          //更改用户金币数
          $userStatus = $this->getUserFortuneService()->getUserFortuneStatusByUid($uid);
          if ($userStatus) {
            $this->getUserFortuneService()->autoUserFortuneCoin($uid, $ruleId);
          }
          echo $this->getWxpayService()->replyNotify(array(
            'return_code' => 'SUCCESS',
            'return_msg' => 'OK'
          ));
          exit;
        }
      }
    } catch (Exception $e) {}
  
    echo $this->getWxpayService()->replyNotify(array(
      'return_code' => 'FAIL',
      'return_msg' => '失败'
    ));
    exit;
  }
}
?>
