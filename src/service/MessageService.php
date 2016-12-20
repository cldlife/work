<?php
/**
 * @desc MessageService
 */
class MessageService extends BaseService {
  
  //短信验证码过期时间 (秒)
  const SMS_CODE_EXPIRESIN = 600;
  
  //融云IM消息内容模板
  private static $rcImContentTemplates = array(
    'new_user' => '欢迎加入玩主大家庭，在这里我们就是玩的来！送你%coins%金币，Enjoy！',
  	'friending' => '我加你为好友了',
    'friending_with_rose' => '我加你为好友了, 送你%roses%朵玫瑰',
    'friending_hello' => 'Hello，我已经成为你的好友啦！',
    'game_send_rose' => '%fromuser% 向你演唱的歌曲《%songname%》送了%roses%朵玫瑰',
    'thread_send_rose' => '%fromuser% 向你的帖子送了%roses%朵玫瑰',
    'reward_coins' => '你贡献的歌曲《%songname%》通过审核啦，奖励你%coins%金币！',
    'report' => '已收到你的举报，我们会在24小时内处理，谢谢你协助维护玩主良好氛围。',
    'painting' => '%nickname% 为你做了一幅新的画作，请进入“我拍你画” 欣赏！',
  );
  //融云聊天室消息内容模板
  private static $rcChatRoomContentTemplates = array(
    'game_play_song' => '新的歌曲播放咯，感谢玩友 %nickname% 提供。',
    'game_play_song_xiaozhu' => '现在请欣赏由 %singer% 演唱的歌曲片段。',
    'game_send_rose' => '%fromuser% 向演唱者 %touser% 送了%roses%朵玫瑰',
    'game_throw_egg' => '%fromuser% 向演唱者 %touser% 砸了一个鸡蛋',
    'game_first_win' => '恭喜玩友 %nickname% 第一个猜对歌名，送上%coins%金币！',
    //spy game
    'game_spy_readied' => '再等一会儿，再不来人我们就开始了',
    'game_spy_join' => '欢迎%region%%sex%%player%。',
    'game_spy_start_speak' => "游戏开始，请含蓄描述自己拿到的词",
    'game_spy_start_vote' => "现在开始投票",
    'game_spy_auto_vote' => '我代表%offline%，投给%voted%',
    'game_spy_normal_die' => "%voted%是平民，被冤死！游戏继续...",
    'game_spy_tie' => "%voted%同票，本轮平局，直接进入下一轮描述",
    'game_spy_pk_speak' => "%voted%同票，请PK，其他玩家等待他们描述",
    'game_spy_pk_tie' => "%voted%得票数仍旧一样，小主随机票死%choose%",
    'game_spy_guess' => "请卧底%spyname%猜平民词，如果猜对则卧底赢了，平民们请屏息以待！",
    'game_spy_normal_win' => "%spy-voted%本局平民胜利，%normals%加%normal-coin%金币，卧底%spyname%失败，减%spy-coin%金币",
    'game_spy_spy_win' => "本局卧底胜利，%spyname%加%spy-coin%金币；平民%normals%失败，减%normal-coin%金币",
  );
  
  private function getMessageDAO() {
    return DAOFactory::getInstance()->createMessageDAO();
  }

  /**
   * @desc 获取手机验证号记录
   */
  public function getMobileSMScode($mobile, $type) {
    $mobileSMScode = array();
    if ($mobile && $type) {
      $mobileSMScode = $this->getMessageDAO()->findMobileSMScode($mobile, $type);
      
      if ($mobileSMScode) {
        //判断已存在的验证是否过期
        $mobileSMScode['is_expired'] = FALSE;
        if (time() > ($mobileSMScode['created_time'] + self::SMS_CODE_EXPIRESIN)) {
          //删除过期记录
          $this->getMessageDAO()->deleteMobileSMScode($mobileSMScode['mobile'], $type);
          $mobileSMScode['is_expired'] = TRUE;
        }
      }
    }
    
    return $mobileSMScode;
  }
  
  /**
   * @desc 发送手机验证码
   * @param int $mobile 手机号
   * @param int $type 验证码类型：1-注册，2-找回密码，3-绑定手机，4-更新绑定手机
   */
  public function sendMobileSMScode($mobile, $type) {
    $res = FALSE;
    if ($mobile && $type) {
      //是否重新生成验证码并发送短信
      $needReNewCode = TRUE;
      
      //获取验证码记录 (未过期则重新发送短信)
      $mobileSMScode = $this->getMobileSMScode($mobile, $type);
      if ($mobileSMScode && $mobileSMScode['is_expired'] == FALSE) {
        $needReNewCode = FALSE;
        $res = $this->getYunTongxunService()->sendCodeSMS($mobileSMScode['mobile'], $mobileSMScode['code'] . " , 发送于".date('H:i'), self::SMS_CODE_EXPIRESIN, $type);
      }
      
      //生成验证码并发送短信
      if ($needReNewCode) {
        //随机生成6位验证码
        $newCode = mt_rand(100000, 999999);
        
        if ($this->getMessageDAO()->insertMobileSMScode($mobile, $type, $newCode)) {
          $res = $this->getYunTongxunService()->sendCodeSMS($mobile, $newCode, self::SMS_CODE_EXPIRESIN, $type);
        }
      }
    }
    
    return $res;
  }
  
  /**
   * @desc 删除手机验证码
   */
  public function deleteMobileSMScode($mobile, $type) {
    if ($mobile && $type) {
      return $this->getMessageDAO()->deleteMobileSMScode($mobile, $type);
    }
    return FALSE;
  }
  
  /**
   * @desc 获取融云用户token
   * @param array $user
   */
  public function getRcUserToken($user) {
    $rcUserToken = array();
    if ($user) {
      $rcUserToken = $this->getMessageDAO()->findRcUserToken($user['uid']);
      if ($rcUserToken) {
        
        //验证是否过期，过期则重新获取token
        if (time() > ($rcUserToken['updated_time'] + $this->getRongCloudService()->getUserTokenExpiresin())) {
          $rcTokenInfo = $this->getRongCloudService()->getUserToken($user['uid'], $user['nickname'], $user['avatar']);
          if ($rcTokenInfo && $rcTokenInfo['token']) {
            $this->getMessageDAO()->updateRcUserToken($user['uid'], $rcTokenInfo['token']);
            $rcUserToken['token'] = $rcTokenInfo['token'];
          }
        }
      } else {
        
        //获取token
        $rcTokenInfo = $this->getRongCloudService()->getUserToken($user['uid'], $user['nickname'], $user['avatar']);
        if ($rcTokenInfo && $rcTokenInfo['token']) {
          $rcUserToken = $this->getMessageDAO()->insertRcUserToken($user['uid'], $rcTokenInfo['token']);
        }
      }
    }
    return $rcUserToken;
  }
  
  /**
   * @desc 刷新融云用户信息
   */
  public function refreshRcUserInfo($user) {
    if ($user) {
      $rcUserToken = $this->getMessageDAO()->findRcUserToken($user['uid']);
      if ($rcUserToken) return $this->getRongCloudService()->refreshUserInfo($user['uid'], $user['nickname'], $user['avatar']);
    }
    return FALSE;
  }
  
  /**
   * @desc 发送融云IM消息
   * @param array $fromUser 发消息的用户
   * @param array $toUser 收消息的用户
   * @param string $templateName 模板名称
   * @param array $templateData 模板数据 (依次替换的数据集合)
   * @param int $messageType 消息模板类型（对应RongCloudService）
   */
  public function sendRcImMessage($fromUser, $toUids, $templateName, $templateData = array(), $messageType = 1) {
    //去除自己的uid,不给自己发消息
    if ($fromUser && $toUids) $toUids = array_diff($toUids, array($fromUser['uid']));
    if ($fromUser && $toUids && $templateName) {
      $content = self::$rcImContentTemplates[$templateName];
      
      $matches = array();
      if ($templateData) preg_match_all('/(%.*?%)/', $content, $matches);
      if ($matches) $content = str_replace($matches[0], $templateData, $content);
      
      return $this->getRongCloudService()->sendMessage($fromUser['uid'], $toUids, array(
        'type' => $messageType,
        'content' => array(
          'content' => $content,
          'user' => $fromUser
        )
      ), $content);
    }
    return FALSE;
  }
  
  /**
   * @desc 发送融云聊天室消息（房间）
   * @param array $fromUser 发消息的用户
   * @param array $toChatroomIds 收消息的聊天室id
   * @param string $templateName 模板名称
   * @param array $templateData 模板数据 (依次替换的数据集合)
   */
  public function sendRcChatRoomMessage($fromUser, $toChatroomIds, $templateName, $templateData = array(), $useReplace = FALSE) {
    if ($fromUser && $toChatroomIds && $templateName) {
      $content = self::$rcChatRoomContentTemplates[$templateName];
  
      if ($useReplace && $templateData) {
        $content = str_replace(array_keys($templateData), array_values($templateData), $content);

      } else if ($templateData) {
        $matches = array();
        preg_match_all('/(%.*?%)/', $content, $matches);
        if ($matches) $content = str_replace($matches[0], $templateData, $content);
      }
  
      return $this->getRongCloudService()->sendChatroomMessage($fromUser['uid'], $toChatroomIds, array(
        'type' => 1,
        'content' => array(
          'content' => $content, 
          'user' => $fromUser
        )
      ));
    }
    return FALSE;
  }
  
  /**
   * @desc 发送融云聊天室消息（房间游戏通信）
   * @param array $fromUser 发消息的用户
   * @param array $gameMsg 游戏信息
   */
  public function sendRcChatRoomGameMessage($fromUser, $gameMsg = array()) {
    if ($fromUser && $gameMsg && $gameMsg['roomid']) {
      return $this->getRongCloudService()->sendChatroomMessage($fromUser['uid'], array($gameMsg['roomid']), array(
        'type' => 1,
        'content' => array(
          'content' => '',
          'user' => $fromUser,
          'extra' => json_encode($gameMsg)
        )
      ));
    }
    return FALSE;
  }
}
