<?php
require_once APP_CONFIG_THIRDSDK_DIR . '/umengpush/config.inc.php';
require_once APP_LIB_THIRD_SDK_DIR. '/umengpush/lib/UmengNotification.php';
require_once APP_LIB_THIRD_SDK_DIR. '/umengpush/lib/ios/IOSNotification.php';
require_once APP_LIB_THIRD_SDK_DIR. '/umengpush/lib/android/AndroidNotification.php';

/**
 * @desc 友盟消息推送类
 */
class Umengpush {
  
  //0-全部
  const NOTIFICATION_TYPE_BROADCAST = 0;
  
  //1-组播
  const NOTIFICATION_TYPE_GROUPCAST = 1;
  
  //2-单播
  const NOTIFICATION_TYPE_UNICAST = 2;
  
  //3-文件播
  const NOTIFICATION_TYPE_FILECAST = 3;
  
  //4-自定义播
  const NOTIFICATION_TYPE_CUSTOMIZEDCAST = 4;
  
  //close setting
  const UMENTPUSH_CLOSE_NOTIFICATION = UMENTPUSH_CLOSE_NOTIFICATION;
  
  //消息别名类型（固定）
  const NOTIFICATION_ALIAS_TYPE = 'ShiHuo';

  protected $timestamp = NULL;

  protected $validation_token = NULL;

  //对应$notificationType
  private static $classNameMaps = array(
  	'ios' => array(
  	  'IOSBroadcast', 'IOSGroupcast', 'IOSUnicast', 'IOSFilecast', 'IOSCustomizedcast'
    ),
    'android' => array(
      'AndroidBroadcast', 'AndroidGroupcast', 'AndroidUnicast', 'AndroidFilecast', 'AndroidCustomizedcast'
    )
  );
  
  //description maps
  private static $descriptionMaps = array(
  	1 => '话题推送', 2 => '食记推送', 3 => '专题推送', 4 => '评论通知推送', 5 => '私信通知推送'
  );
  
  public function __construct () {
    $this->timestamp = strval(time());
  }
  
  /**
   * @desc init dependent libs
   * @param string $deviceType: ios or android 
   * @param int $notificationType 0-全部，1-组播，2-单播，3-文件播，4-自定义播
   * @return object notification class
   */
  private function init ($deviceType, $notificationType, Array $fields) {
    $classNameMaps = self::$classNameMaps[$deviceType];
    if (!$classNameMaps) throw new Exception('Please select send device type.');
    $notificationClassName = $classNameMaps[$notificationType];
    if (!$notificationClassName) throw new Exception('Please select send notification type.');
    if (!$fields['title']) throw new Exception('Please input send notification title.');

    //require dependent class
    foreach ($classNameMaps as $className) {
      require_once APP_LIB_THIRD_SDK_DIR . "/umengpush/lib/{$deviceType}/{$className}.php";
    }
    
    //new notification class
    $Notification = new $notificationClassName();
    $Notification->setPredefinedKeyValue("timestamp", $this->timestamp);
    if ($fields['customize'] && $fields['customize']['type']) $Notification->setPredefinedKeyValue("description", self::$descriptionMaps[$fields['customize']['type']]);
    if ($deviceType == 'ios') {
      $Notification->setPredefinedKeyValue("alert", $fields['title']);
      $Notification->setPredefinedKeyValue("badge", intval($fields['badge']) ? intval($fields['badge']) : 1);
      $Notification->setPredefinedKeyValue("sound", "chime");
      if ($fields['customize'] && is_array($fields['customize'])) {
        foreach ($fields['customize'] as $key => $value) {
          $Notification->setCustomizedField($key, $value);
        }
      }
    } else {
      $Notification->setPredefinedKeyValue("ticker", $fields['ticker'] ? $fields['ticker'] : $fields['title']);
      $Notification->setPredefinedKeyValue("title", $fields['title']);
      $Notification->setPredefinedKeyValue("text", $fields['content'] ? $fields['content'] : '点击查看详情');
      $Notification->setPredefinedKeyValue("after_open", "go_custom");
      $Notification->setPredefinedKeyValue("custom", "[]");
      if ($fields['customize'] && is_array($fields['customize'])) {
        foreach ($fields['customize'] as $key => $value) {
          $Notification->setExtraField($key, $value);
        }
      }
    }
    return $Notification;
  }

  /**
   * @desc 广播
   * @param unknown $deviceType
   * @param array $fields
   * @return json ret.data.error_code
   */
  public function sendBroadcast($deviceType, Array $fields) {
    try {
      if (self::UMENTPUSH_CLOSE_NOTIFICATION) {
      } else {
        $Notification = $this->init($deviceType, self::NOTIFICATION_TYPE_BROADCAST, $fields);
        return $Notification->send();
      }
    } catch (Exception $e) {
      if (APP_DEBUG) throw new Exception($e->getMessage());
    }
  }
  
  /**
   * @desc 自定义播
   * @param unknown $deviceType
   * @param array $fields
   * $fields['alias'] 不超过50个alias,多个alias以英文逗号间隔
   *  或
   * $fields['device_tokens'] 不超过50个device_token,多个device_token以英文逗号间隔
   * @return json ret.data.error_code，2010:与alias对应的device_tokens为空
   */
  public function sendCustomizedcast ($deviceType, Array $fields) {
    try {
      if (self::UMENTPUSH_CLOSE_NOTIFICATION) {
      } else {
        $Notification = $this->init($deviceType, self::NOTIFICATION_TYPE_CUSTOMIZEDCAST, $fields);
        $Notification->setPredefinedKeyValue("alias_type", self::NOTIFICATION_ALIAS_TYPE);
        if ($fields['alias']) $Notification->setPredefinedKeyValue("alias", $fields['alias']);
        if ($fields['device_tokens']) $Notification->setPredefinedKeyValue("device_tokens", $fields['device_tokens']);
        return $Notification->send();
      }
    } catch (Exception $e) {
      if (APP_DEBUG) throw new Exception($e->getMessage());
    }
  }
}