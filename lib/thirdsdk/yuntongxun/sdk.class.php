<?php
require_once APP_CONFIG_THIRDSDK_DIR . '/yuntongxun/config.inc.php';
require_once APP_LIB_THIRD_SDK_DIR . '/yuntongxun/lib/CCPRestSDK.php';

/**
 * 云通讯类
 */
final class YunTongxun {
  
  //主帐号
  const YUNTX_ACCOUNT_SID = YUNTX_ACCOUNT_SID;
  
  //主帐号Token
  const YUNTX_ACCOUNT_TOKEN = YUNTX_ACCOUNT_TOKEN;
  
  //应用Id
  const YUNTX_APPID = YUNTX_APPID;
  
  //请求地址，格式如下，不需要写https://
  const YUNTX_SERVER_IP = YUNTX_SERVER_IP;
  
  //请求端口
  const YUNTX_SERVER_PORT = YUNTX_SERVER_PORT;
  
  //REST版本号
  const YUNTX_SOFT_VERSIOIN = YUNTX_SOFT_VERSIOIN;
  
  //是否开启日志
  const YUNTX_DEBUG = YUNTX_DEBUG;
  
  //通知模板ID
  const NOTICE_TEMPLATE_ID = 0;
  
  //验证码模板ID (1:注册，2:找回密码，3:绑定手机，4:更新绑定手机)
  //模板:【玩主app】手机验证码为:{1},有效期{2}分钟。
  private static $templateIds = array(
  	1 => array('id' => 116392, 'desc' => ', 仅用于注册'),
    2 => array('id' => 116392, 'desc' => ', 仅用于找回密码'),
    3 => array('id' => 116392, 'desc' => ''),
    4 => array('id' => 116392, 'desc' => ''),
  );
  
  private $rest = NULL;
  
  //初始化REST SDK
  public function __construct() {
    if (!$this->rest) {
      $rest = new REST(self::YUNTX_SERVER_IP, self::YUNTX_SERVER_PORT, self::YUNTX_SOFT_VERSIOIN);
      $rest->enabeLog = self::YUNTX_DEBUG;
      $rest->setAccount(self::YUNTX_ACCOUNT_SID, self::YUNTX_ACCOUNT_TOKEN);
      $rest->setAppId(self::YUNTX_APPID);
      $this->rest = $rest;
    }
  }

  /**
   * 发送验证码短信
   * @param $to 手机号码集合, 用英文逗号分开
   * @param $code 验证码
   * @param $expire 过期时间 (秒)
   * @param $type 1-注册，2-找回密码
   */
  public function sendCodeSMS($to, $code, $expire, $type) {
    if (!$to || !$code || !$expire || !$type || !self::$templateIds[$type]) return FALSE;

    $minite = intval($expire/60);
    $result = $this->rest->sendTemplateSMS($to, array($code . self::$templateIds[$type]['desc'], $minite), self::$templateIds[$type]['id']);

    if ($result == NULL) {
      //echo "result error!";
      //break;
    }
    
    if ($result->statusCode != 0) {
      //TODO 添加错误处理逻辑
      //echo "error code :" . $result->statusCode . "<br>";
      //echo "error msg :" . $result->statusMsg . "<br>";
      
    } else {
      //TODO 添加成功处理逻辑
      return TRUE;
      
      //获取返回信息
      //$smsmessage = $result->TemplateSMS;
      //echo "dateCreated:".$smsmessage->dateCreated."<br/>";
      //echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
      
    }
    
    return FALSE;
  }
  
  /**
   * 发送通知短信
   * @param $to 手机号码集合, 用英文逗号分开
   * @param $notice 通知内容
   * @param int $templateId 模板ID
   * @param string $appId 云通讯appId
   */
  public function sendNoticeSMS($to, $notice, $templateId = self::NOTICE_TEMPLATE_ID, $appId = '') {
    if ($appId) $this->rest->setAppId($appId);

    $notice = is_array($notice) ? $notice : array($notice);
    $result = $this->rest->sendTemplateSMS($to, $notice, $templateId);

    if ($result == NULL) {
      //echo "result error!";
      //break;
    }
  
    if ($result->statusCode != 0) {
      //TODO 添加错误处理逻辑
      return FALSE;
      
      //echo "error code :" . $result->statusCode . "<br>";
      //echo "error msg :" . $result->statusMsg . "<br>";
  
    } else {
      //TODO 添加成功处理逻辑
      return TRUE;
  
      //获取返回信息
      //$smsmessage = $result->TemplateSMS;
      //echo "dateCreated:".$smsmessage->dateCreated."<br/>";
      //echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
    }
    
    return FALSE;
  }
}
