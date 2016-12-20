<?php
class MessageController extends BaseController {

  //每次发送的时间间隔
  const SEND_SMS_ONCE_WAITINGTIME = 60;
  
  //每天最多发送10次
  const SEND_SMS_COUNT_LIMIT = 10; 
  
  /**
   * @desc 发送短信验证码
   * 验证码类型，1:注册，2:找回密码，3:绑定手机，4:更新绑定手机
   * @param int $mobile 手机号（type等于4时，可空）
   * 
   * 运营商限制：TODO coding...
   * 每天限定给同一手机号只能发送1条内容相同的短信
   * 每天限定给同一手机号只能发送10条内容不同的短信
   */  
  public function actionSendSMSCode () {
    $mobile = $this->getSafeRequest("mobile", 0, 'int');
    $type = $this->getSafeRequest("type", 0, 'int');
    
    //参数验证
    if (!$type || $type > 4) $this->outputJsonData(1);
    if ($type != 4) {
      if (!Utils::checkMobile($mobile)) $this->outputJsonData(1, array(
        'apptip' => '手机号格式错误'
      ));
      
      $userMobileIndex = $this->getUserService()->getUserByMobile($mobile);
      if ($type == 1 && $userMobileIndex['uid']) $this->outputJsonData(1, array(
      	'apptip' => '该手机号已注册，请直接登录！'
      ));
      if ($type == 2 && !$userMobileIndex['uid']) $this->outputJsonData(1, array(
      	'apptip' => '该手机号不存在，请先注册！'
      ));
    }

    //type等于3或4时，必须验证用户登录权限
    if ($type == 3 || $type == 4) {
      if (!$this->currentUser['uid']) $this->outputJsonData(403);

      if ($type == 3) {
        if ($userMobileIndex['uid'] || $this->currentUser['status']['is_binded_mobile'] == 1) $this->outputJsonData(1, array(
          'apptip' => '该手机号已绑定'
        ));
      }
      
      if ($type == 4) {
        $mobile = $this->currentUser['mobile'];
        if (!$this->currentUser['status']['is_binded_mobile']) $this->outputJsonData(1, array(
          'apptip' => '请先绑定手机'
        ));
      }
    }

    //Flood Start 缓存 (间隔60秒) (每天最多发送10次)
    $waitingCacheKey = __FUNCTION__ . '_MOBILE_' . $mobile . '_TYPE_' . $type;
    $sentCountCacheKey = __FUNCTION__ . '_SENDCOUNT_MOBILE_' . $mobile . '_TYPE_' . $type;
    $waiting = $this->getCommonService()->getFromMemcache($waitingCacheKey);
    $sentCount = $this->getCommonService()->getFromMemcache($sentCountCacheKey);
    if ($waiting || $sentCount > self::SEND_SMS_COUNT_LIMIT) $this->outputJsonData(1, array(
      'apptip' => '亲，请歇会稍后再试'
    ));

    //发送短信验证码
    $this->getMessageService()->sendMobileSMScode($mobile, $type);
    
    //Flood End 缓存
    $this->getCommonService()->setToMemcache($waitingCacheKey, TRUE, self::SEND_SMS_ONCE_WAITINGTIME);
    $this->getCommonService()->setToMemcache($sentCountCacheKey, (int) $sentCount+1, 86400);
    
    $data = array();
    $data['apptip'] = '发送成功';
    $this->outputJsonData(0, $data);
  }
}
?>