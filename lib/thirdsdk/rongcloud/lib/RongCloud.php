<?php
class RongCloud {

  protected static $serviceSet = array();
  
  protected $sendRequest = null;

  /**
   * @desc 参数初始化
   * @param $appKey
   * @param $appSecret
   * @param $serverApiUrl
   * @param $smsUrl
   * @param string $format
   */
  public function __construct ($appKey, $appSecret, $serverApiUrl, $smsUrl, $format = 'json') {
    if (!$this->sendRequest) {
      require_once APP_LIB_THIRD_SDK_DIR . '/rongcloud/lib/SendRequest.php';
      $this->sendRequest = new SendRequest($appKey, $appSecret, $serverApiUrl, $smsUrl);
    }
  }

  /**
   * @desc User Service
   */
  public function User () {
    if (!isset(self::$serviceSet['User'])) {
      require_once APP_LIB_THIRD_SDK_DIR . '/rongcloud/lib/service/User.php';
      self::$serviceSet['User'] = new User($this->sendRequest);
    }
    return self::$serviceSet['User'];
  }

  /**
   * @desc Message Service
   */
  public function Message () {
    if (!isset(self::$serviceSet['Message'])) {
      require_once APP_LIB_THIRD_SDK_DIR . '/rongcloud/lib/service/Message.php';
      self::$serviceSet['Message'] = new Message($this->sendRequest);
    }
    return self::$serviceSet['Message'];
  }

  /**
   * @desc Wordfilter Service
   */
  public function Wordfilter () {
    if (!isset(self::$serviceSet['Wordfilter'])) {
      require_once APP_LIB_THIRD_SDK_DIR . '/rongcloud/lib/service/Wordfilter.php';
      self::$serviceSet['Wordfilter'] = new Wordfilter($this->sendRequest);
    }
    return self::$serviceSet['Wordfilter'];
  }

  /**
   * @desc Group Service
   */
  public function Group () {
    if (!isset(self::$serviceSet['Group'])) {
      require_once APP_LIB_THIRD_SDK_DIR . '/rongcloud/lib/service/Group.php';
      self::$serviceSet['Group'] = new Group($this->sendRequest);
    }
    return self::$serviceSet['Group'];
  }

  /**
   * @desc Chatroom Service
   */
  public function Chatroom () {
    if (!isset(self::$serviceSet['Chatroom'])) {
      require_once APP_LIB_THIRD_SDK_DIR . '/rongcloud/lib/service/Chatroom.php';
      self::$serviceSet['Chatroom'] = new Chatroom($this->sendRequest);
    }
    return self::$serviceSet['Chatroom'];
  }

  /**
   * @desc Push Service
   */
  public function Push () {
    if (!isset(self::$serviceSet['Push'])) {
      require_once APP_LIB_THIRD_SDK_DIR . '/rongcloud/lib/service/Push.php';
      self::$serviceSet['Push'] = new Push($this->sendRequest);
    }
    return self::$serviceSet['Push'];
  }

  /**
   * @desc SMS Service
   */
  public function SMS () {
    if (!isset(self::$serviceSet['SMS'])) {
      require_once APP_LIB_THIRD_SDK_DIR . '/rongcloud/lib/service/SMS.php';
      self::$serviceSet['SMS'] = new SMS($this->sendRequest);
    }
    return self::$serviceSet['SMS'];
  }
}