<?php
require_once APP_CONFIG_THIRDSDK_DIR . '/rongcloud/config.inc.php';
require_once APP_LIB_THIRD_SDK_DIR . '/rongcloud/lib/RongCloud.php';

/**
 * 融云sdk类
 */
final class RongCloudSdk {
  
  //App Key
  const RONGCLOUD_APPKEY = RONGCLOUD_APPKEY;
  
  //App Secret
  const RONGCLOUD_APPSECRET = RONGCLOUD_APPSECRET;
  
  //IM服务地址
  const RONGCLOUD_SERVERAPIURL = RONGCLOUD_SERVERAPIURL;
  
  //短信服务地址
  const RONGCLOUD_SMSURL = RONGCLOUD_SMSURL;

  //融云用户ID前缀
  const RONGCLOUD_USERID_PREFIX = RONGCLOUD_USERID_PREFIX;
  
  //融云用户token过期时间 (秒) (默认30天)
  const RONGCLOUD_USERTOKEN_EXPIRESIN = RONGCLOUD_USERTOKEN_EXPIRESIN;
  
  //是否开启日志
  const RONGCLOUD_DEBUG = RONGCLOUD_DEBUG;
  
  private $rest = NULL;
  
  //初始化$rest
  public function __construct() {
    if (!$this->rest) {
      $rest = new RongCloud(self::RONGCLOUD_APPKEY, self::RONGCLOUD_APPSECRET, self::RONGCLOUD_SERVERAPIURL, self::RONGCLOUD_SMSURL);
      $this->rest = $rest;
    }
  }
  
  /**
   * @desc 获取服务端Token过期时间 (秒)
   */
  public function getUserTokenExpiresin () {
    return self::RONGCLOUD_USERTOKEN_EXPIRESIN * 86400;
  }
  
  /**
   * @desc 获取融云UserId
   */
  public function getUserId ($uid) {
    return self::RONGCLOUD_USERID_PREFIX . $uid;
  }
  public function getUserIds ($uids) {
    $userIds = array();
    foreach ($uids as $uid) {
      $userIds[] = $this->getUserId($uid);
    }
    return $userIds;
  }

  /**
   * server api 签名
   * @param string $nonce
   * @param string $timeStamp
   * @return string
   */
  public function signServerApi ($nonce, $timeStamp) {
    return (self::RONGCLOUD_APPSECRET && $nonce && $timeStamp) ? sha1(self::RONGCLOUD_APPSECRET . $nonce . $timeStamp) : '';
  }

  /**
   * @desc 获取服务端Token
   * @param long int $uid 用户ID
   * @param string $nickname 用户昵称
   * @param string $avatar 用户头像
   */
  public function getUserToken ($uid, $nickname, $avatar) {
    $res = array();
    if ($uid && $nickname && $avatar) {
      $result = $this->rest->User()->getToken($this->getUserId($uid), $nickname, $avatar);
      if ($result) $res = json_decode($result, TRUE);
    }
    return $res;
  }
  
  /**
   * @desc 刷新用户信息
   * @param long int $uid 用户ID
   * @param string $nickname 用户昵称
   * @param string $avatar 用户头像
   */
  public function refreshUserInfo ($uid, $nickname, $avatar) {
    if ($uid && $nickname && $avatar) {
      $result = $this->rest->User()->refresh($this->getUserId($uid), $nickname, $avatar);
      if ($result) return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc 封禁用户（每秒钟限 100 次）
   * @param long int $uid 用户ID
   * @param string $minute 封禁时长, 单位为分钟，最大值为43200分钟
   */
  public function blockUser ($uid, $minute) {
    if ($uid && $minute) {
      $result = $this->rest->User()->block($this->getUserId($uid), $minute);
      if ($result) return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc 解封用户（每秒钟限 100 次）
   * @param long int $uid 用户ID
   */
  public function unBlockUser ($uid) {
    if ($uid) {
      $result = $this->rest->User()->unBlock($this->getUserId($uid));
      if ($result) return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc 添加用户到黑名单
   * @param string $uid 用户ID
   * @param array $uids 用户IDs
   */
  public function addUserMessageBlackList($uid, $toUid) {
    if ($uid && $toUid) {
      $result = $this->rest->User()->addBlacklist($this->getUserId($uid), $this->getUserId($toUid));
      if ($result) return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc 获取携带用户信息的消息
   * @param array $message
   * @param array $user
   * @return $message
   */
  private function addMessageWithUser ($message, $user) {
    if (is_array($message) && isset($message['user']) && $user) {
      unset($message['user']);
      $message['user']['id'] = $this->getUserId($user['uid']);
      $message['user']['name'] = $user['nickname'];
      $message['user']['icon'] = $user['avatar'];
    }
    return $message;
  }
  
  /**
   * @desc 发送单聊消息
   * @param long int $fromUid 发送用户ID
   * @param array $toUids 接收用户IDs
   * @param array $body 消息实体，
   * - type: int, 消息类型id，未定义则读取自定义消息类型
   * - name: string, 消息类型名称
   * - content: array, 消息内容
   * @param string $pushContent push推送内容
   */
  public function sendMessage ($fromUid, Array $toUids = array(), Array $body = array(), $pushContent = '') {
    $res = array();
    if ($fromUid && $toUids && $body) {
      if (isset($body['type'])) {
        $message = $this->rest->Message()->messageTypes[$body['type'] - 1];
        if ($message) {
          //赋值消息content
          foreach ($message['content'] as $key => $value) {
            if (!$body['content'][$key]) {
              unset($message['content'][$key]);
              continue;
            }

            //消息携带用户信息
            if ($key == 'user') {
              $message['content'] = $this->addMessageWithUser($message['content'], $body['content']['user']);
            } else {
              $message['content'][$key] = $body['content'][$key];
            }
          }
          $messageTypeName = $message['name'];
          $messageTypeContent = json_encode($message['content']);
        }
      } else {
        if ($body['content']['user']) $body['content'] = $this->addMessageWithUser($body['content'], $body['content']['user']);
        $messageTypeName = $body['name'];
        $messageTypeContent = json_encode($body['content']);
      }

      $pushData = json_encode(array('pushData' => $pushContent));
      $result = $this->rest->Message()->publishPrivate($this->getUserId($fromUid), $this->getUserIds($toUids), $messageTypeName, $messageTypeContent, $pushContent, $pushData);
      if ($result) $res = json_decode($result, TRUE);
    }
    return $res;
  }
  
  /**
   * @desc 发送聊天室消息
   * @param long int $fromUid 发送用户ID
   * @param array $toChatroomIds 接收聊天室roomIds
   * @param array $body 消息实体，
   * - type: int, 消息类型id，未定义则读取自定义消息类型
   * - name: string, 消息类型名称
   * - content: array, 消息内容
   */
  public function sendChatroomMessage ($fromUid, Array $toChatroomIds = array(), Array $body = array()) {
    $res = array();
    if ($fromUid && $toChatroomIds && $body) {
      if (isset($body['type'])) {
        $message = $this->rest->Message()->messageTypes[$body['type'] - 1];
        if ($message) {
          //赋值消息content
          foreach ($message['content'] as $key => $value) {
            if (!$body['content'][$key]) {
              unset($message['content'][$key]);
              continue;
            }

            //消息携带用户信息
            if ($key == 'user') {
              $message['content'] = $this->addMessageWithUser($message['content'], $body['content']['user']);
            } else {
              $message['content'][$key] = $body['content'][$key];
            }
          }
          $messageTypeName = $message['name'];
          $messageTypeContent = json_encode($message['content']);
        }
      } else {
        if ($body['content']['user']) $body['content'] = $this->addMessageWithUser($body['content'], $body['content']['user']);
        $messageTypeName = $body['name'];
        $messageTypeContent = json_encode($body['content']);
      }
      
      $result = $this->rest->Message()->publishChatroom($this->getUserId($fromUid), $toChatroomIds, $messageTypeName, $messageTypeContent);
      if ($result) $res = json_decode($result, TRUE);
    }
    return $res;
  }
  
  /**
   * @desc 创建聊天室
   * @param array $chatrooms 聊天室实体
   * - id: string, 聊天室roomid
   * - name: string, 聊天室名称
   */
  public function createChatrooms (Array $chatrooms = array()) {
    if ($chatrooms) {
      $result = $this->rest->Chatroom()->create($chatrooms);
      if ($result) return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc 销毁聊天室
   * @param array $chatroomIds 聊天室ids
   */
  public function destroyChatrooms (Array $chatroomIds = array()) {
    if ($chatroomIds) {
      $chatroomIds = json_encode($chatroomIds);
      $result = $this->rest->Chatroom()->destroy($chatroomIds);
      if ($result) return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc 聊天室消息停止分发
   * @param string $chatroomId 聊天室id
   */
  public function blockChatroom ($chatroomId) {
    if ($chatroomId) {
      $result = $this->rest->Chatroom()->stopDistributionMessage($chatroomId);
      if ($result) return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc 聊天室消息恢复分发
   * @param string $chatroomId 聊天室id
   */
  public function unBlockChatroom ($chatroomId) {
    if ($chatroomId) {
      $result = $this->rest->Chatroom()->resumeDistributionMessage($chatroomId);
      if ($result) return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc 加入聊天室
   * @param array $uids 加入用户IDs（不超过50 个）
   * @param string $chatroomId 聊天室id
   */
  public function joinChatrooms (Array $uids = array(), $chatroomId) {
    if ($uids && $chatroomId) {
      $result = $this->rest->Chatroom()->join($this->getUserIds($uids), $chatroomId);
      if ($result) return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc 获取聊天室用户列表
   * @param string $chatroomId 聊天室id
   */
  public function getChatroomUsers ($chatroomId, $pageSize = 30, $orderType = 2) {
    $res = array();
    if ($chatroomId && $pageSize && $orderType) {
      $result = $this->rest->Chatroom()->queryUser($chatroomId, $pageSize, $orderType);
      if ($result) $res = json_decode($result, TRUE);
    }
    return $res;
  }
  
  /**
   * @desc 禁言聊天室用户
   * @param long int $uid 用户ID
   * @param string $chatroomId 聊天室id
   * @param string $minute 禁言时长, 单位为分钟，最大值为43200分钟
   */
  public function gagChatroomUser ($uid, $chatroomId, $minute) {
    if ($uid && $chatroomId && $minute) {
      $result = $this->rest->Chatroom()->addGagUser($this->getUserId($uid), $chatroomId, $minute);
      if ($result) return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc 解除禁言聊天室用户
   * @param long int $uid 用户ID
   * @param string $chatroomId 聊天室id
   */
  public function unGagChatroomUser ($uid, $chatroomId) {
    if ($uid && $chatroomId) {
      $result = $this->rest->Chatroom()->rollbackGagUser($this->getUserId($uid), $chatroomId);
      if ($result) return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc 封禁聊天室用户
   * @param long int $uid 用户ID
   * @param string $chatroomId 聊天室id
   * @param string $minute 封禁时长, 单位为分钟，最大值为43200分钟
   */
  public function blockChatroomUser ($uid, $chatroomId, $minute) {
    if ($uid && $chatroomId && $minute) {
      $result = $this->rest->Chatroom()->addBlockUser($this->getUserId($uid), $chatroomId, $minute);
      if ($result) return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc 解封聊天室用户
   * @param long int $uid 用户ID
   * @param string $chatroomId 聊天室id
   */
  public function unBlockChatroomUser ($uid, $chatroomId) {
    if ($uid && $chatroomId) {
      $result = $this->rest->Chatroom()->rollbackBlockUser($this->getUserId($uid), $chatroomId);
      if ($result) return TRUE;
    }
    return FALSE;
  }
  
  /**
   * @desc 添加聊天室白名单用户（单个聊天室最多不超过 5 个）
   * @param string $chatroomId 聊天室id
   * @param array $uids 用户IDs
   */
  public function addWhiteListChatroomUser ($chatroomId, $uids = array()) {
    if ($chatroomId && $uids) {
      $result = $this->rest->Chatroom()->addWhiteListUser($chatroomId, $this->getUserIds($uids));
      if ($result) return TRUE;
    }
    return FALSE;
  }
}
