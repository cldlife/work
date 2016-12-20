<?php
/**
 * @desc 听歌猜歌游戏
 */
class TgqcgmController extends BaseController {
  
  //游戏房间id
  const CHAT_ROOM_ID = 'tgqcgm10001';
  //当前游戏缓存key
  const CURRENT_GAME_STATUS_INFO_CACHEKEY = 'CURRENT_GAME_STATUS_INFO';
  //当前游戏用户答题缓存key
  const CURRENT_GAME_USER_PLAYSTATUS_CACHEKEY = 'CURRENT_GAME_USER_PLAYSTATUS';
  
  private static function getCurrentGameStatusInfoCacheKey () {
    return self::CURRENT_GAME_STATUS_INFO_CACHEKEY;
  }
  private static function getCurrentUserPlayStatusCacheKey ($uid) {
    return self::CURRENT_GAME_USER_PLAYSTATUS_CACHEKEY . '_UID_' . $uid;
  }
  
  /**
   * @desc 初始化 & 当前游戏状态
   * @see 仅作检测调用
   */
  public function actionInit () {
    $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
    //生成融云Token
    $token = $this->getMessageService()->getRcUserToken($officialUserInfo);
    $officialUserInfo['token'] = $token;
    
    //创建房间
    $this->getRongCloudService()->createChatrooms(array(self::CHAT_ROOM_ID => '听歌曲猜歌名'));
  
    //法官加入房间
    $this->getRongCloudService()->joinChatrooms(array($officialUserInfo['uid']), self::CHAT_ROOM_ID);
  
    //将法官加入白名单
    $this->getRongCloudService()->addWhiteListChatroomUser(self::CHAT_ROOM_ID, array($officialUserInfo['uid']));
    
    //获取房间内的用户
    $list = $this->getRongCloudService()->getChatroomUsers(self::CHAT_ROOM_ID);
  
    //验证当前游戏状态
    $statusInfo = $this->getCommonService()->getFromMemcache(self::getCurrentGameStatusInfoCacheKey());
    $curTime = time();
    $statusInfo['waiting_sec'] = $statusInfo['start_time'] + $statusInfo['round_time'] - $curTime;
    $statusInfo['ing'] = ($curTime - $statusInfo['start_time'] > 3) ? 0 : 1;
    
    $data = array();
    $data['officialUserInfo'] = $officialUserInfo;
    $data['list'] = $list;
    $data['status'] = $statusInfo;
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 同步状态
   * @return 
   * status - 0-等待，1-可进入游戏(放歌:<=歌曲时间s, 抢答:10s, 公布答案:5s，等待开始:3秒)
   */  
  public function actionSync () {
    //当前游戏状态
    $status = 1;
    $appWaitTime = 18;//app本地逻辑时间
    $waitExtraTime = 3;//考虑的各个用户的网络情况，游戏开始后额外预留3秒的时间可以加入游戏
    $statusInfo = $this->getCommonService()->getFromMemcache(self::getCurrentGameStatusInfoCacheKey());
    $needSendChatRoomThanksMessage = FALSE;//是否发送感谢贡献房间消息
    if ($statusInfo) {
      $curTime = time();
      $tmInfo = $statusInfo['tm_info'];
      $delayedTime = $curTime - $statusInfo['start_time'];
      $waitingSec = $statusInfo['start_time'] + $statusInfo['round_time'] - $curTime;
      $status = ($delayedTime > $waitExtraTime) ? 0 : 1;
      
      //纠正同步延时问题
      if ($status == 1) $tmInfo['duration'] = $tmInfo['duration'] - $delayedTime;
    } else {
      $needSendChatRoomThanksMessage = TRUE;
      
      //随机题目算法
      //获取题目总页数，随机页数提取题目再随机
      $page = 1;
      $pageSize = 20;
      $tmsCount = $this->getGameService()->getGameTgqcgmOnlineTmsCount();
      if (!$tmsCount) $this->outputJsonData(500);
      
      $pages = ceil($tmsCount / $pageSize);
      $randPage = mt_rand($page, $pages);
      $tms = $this->getGameService()->getGameTgqcgmTms(1, $randPage, $pageSize);
      $tmCount = count($tms);
      $tmInfo = $tms[rand(1, $tmCount) - 1];
      
      //计算一轮总时长（歌曲时长 + $appWaitTime）
      $roundTime = $tmInfo['duration'] + $appWaitTime;
      
      //记录游戏状态
      $statusInfo = array();
      $statusInfo['tm_info'] = $tmInfo;
      $statusInfo['round_id'] = time();
      $statusInfo['start_time'] = time();
      $statusInfo['round_time'] = $roundTime;
      $statusInfo['right_count'] = 0;
      $statusInfo['wrong_count'] = 0;
      $this->getCommonService()->setToMemcache(self::getCurrentGameStatusInfoCacheKey(), $statusInfo, $roundTime);
    }
    
    //贡献题目用户
    $user = $this->getUserService()->getUserByUid($tmInfo['uid']);
    $user['status'] = $this->getUserFortuneService()->getUserFortuneStatusByUid($user['uid']);
    $userLevel = $this->getUserService()->getUserLevel($user['status']['points']);
    
    //发送房间消息(小主&用户贡献歌曲消息)
    $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
    if ($needSendChatRoomThanksMessage) {
      if ($officialUserInfo['uid'] == $user['uid']) {
        $this->getMessageService()->sendRcChatRoomMessage($officialUserInfo, array(self::CHAT_ROOM_ID), 'game_play_song_xiaozhu', array($tmInfo['singer']));
      } else {
        $this->getMessageService()->sendRcChatRoomMessage($officialUserInfo, array(self::CHAT_ROOM_ID), 'game_play_song', array($user['nickname']));
      }
    }
    
    $data = array();
    $data['status'] = $status;
    $data['waiting_sec'] = $status ? 0 : $waitingSec;
    $data['tm_info'] = array();
    $data['tm_info']['tm_id'] = $tmInfo['tm_id'];
    $data['tm_info']['media'] = array('url' => WEB_QW_APP_FILE_DOMAIN . $tmInfo['uri'], 'duration' => $tmInfo['duration']);
    $data['tm_info']['user_info'] = array('uid' => $user['uid'], 'nickname' => $user['nickname'], 'avatar' => $user['avatar'], 'level_num' => $userLevel['id']);
    $data['tm_info']['share_link'] = WEB_QW_APP_M_DOMAIN . "/s/{$tmInfo['tm_id']}.html";
    $data['tm_info']['share_singer'] = $officialUserInfo['uid'] == $user['uid'] ? $tmInfo['singer'] : $user['nickname'];
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 退出游戏
   */
  public function actionQuit () {
    $tmId = $this->getSafeRequest('tm_id', 0, 'int');

    //参数验证
    if (!$tmId) $this->outputJsonData(1);
    
    //获取当前游戏状态
    $statusInfo = $this->getCommonService()->getFromMemcache(self::getCurrentGameStatusInfoCacheKey());
    if ($statusInfo && $tmId == $statusInfo['tm_info']['tm_id']) {
      
      //扣金币
      $this->getUserFortuneService()->autoUserFortuneCoin($this->currentUser['uid'], 19);
    }
    
    $data = array();
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 送玫瑰 & 砸鸡蛋
   * @param int tm_id 游戏题目id
   * @param int type 1-送玫瑰，2-砸鸡蛋
   */
  public function actionVote () {
    $tmId = $this->getSafeRequest('tm_id', 0, 'int');
    $type = $this->getSafeRequest('type', 0, 'int');
  
    //参数验证
    if (!$tmId || !$type) $this->outputJsonData(1);

    //获取当前游戏状态
    $tmInfo = array();
    $statusInfo = $this->getCommonService()->getFromMemcache(self::getCurrentGameStatusInfoCacheKey());
    if ($statusInfo) $tmInfo = $statusInfo['tm_info'];
    if (!$tmInfo || $tmInfo['tm_id'] != $tmId) $this->outputJsonData(1);
    
    $userInfo = $this->getUserService()->getUserByUid($tmInfo['uid']);
    $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
    $msgToChatRoomUserName = $officialUserInfo['uid'] == $userInfo['uid'] ? $tmInfo['singer'] : $userInfo['nickname'];
    
    //验证用户财富
    //1-送玫瑰，2-砸鸡蛋
    $apptip = '';
    $sendRes = FALSE;
    if ($type == 1) {
      $roses = $this->getUserFortuneService()->getCoinExchangeRoseRate();
      if ($this->currentUser['status']['roses']) {
        $this->getUserFortuneService()->autoUserFortuneRose($this->currentUser['uid'], 6);
        $this->getUserFortuneService()->autoUserFortuneRose($userInfo['uid'], 7);
        $sendRes = TRUE;
        
      //玫瑰不足则使用金币
      } elseif ($this->currentUser['status']['coins']) {
        $this->getUserFortuneService()->autoUserFortuneCoin($this->currentUser['uid'], 16);
        $this->getUserFortuneService()->autoUserFortuneRose($userInfo['uid'], 5, $roses);
        $sendRes = TRUE;
      } else {
        $apptip = '玫瑰和金币数量不足，请充值哦';
      }
      
      if ($sendRes) {
        //发房间消息
        $this->getMessageService()->sendRcChatRoomMessage($officialUserInfo, array(self::CHAT_ROOM_ID), 'game_send_rose', array($this->currentUser['nickname'], $msgToChatRoomUserName, $roses));
        
        //发聊天消息
        $this->getMessageService()->sendRcImMessage($officialUserInfo, array($userInfo['uid']), 'game_send_rose', array($this->currentUser['nickname'], $tmInfo['song_name'], $roses));
      }
      
    } else {
      
      //10金币换1个鸡蛋
      if ($this->getUserFortuneService()->autoUserFortuneCoin($this->currentUser['uid'], 14)) {
        $sendRes = TRUE;
      } else {
        $apptip = '金币数量不足，请充值哦';
      }
      
      //发房间消息
      if ($sendRes) $this->getMessageService()->sendRcChatRoomMessage($officialUserInfo, array(self::CHAT_ROOM_ID), 'game_throw_egg', array($this->currentUser['nickname'], $msgToChatRoomUserName));
    }
  
    $this->outputJsonData($sendRes ? 0 : 1, array(
      'apptip' => $apptip
    ));
  }
  
  /**
   * @desc 抢答
   * @param int tm_id 游戏题目id
   */
  public function actionAnswer () {
    $tmId = $this->getSafeRequest('tm_id', 0, 'int');
    $content = $this->getSafeRequest('content', '', 'string');
  
    //参数验证
    if (!$tmId || !$content) $this->outputJsonData(1);
    //验证用户是否答对过
    $userPlayStatus = $this->getCommonService()->getFromMemcache(self::getCurrentUserPlayStatusCacheKey($this->currentUser['uid']));
    if ($userPlayStatus) $this->outputJsonData(0, array(
      'result' => 0,
      'desc' => '你已经答过了',
      'apptip' => '你已经答过了'
    ));
    
    //获取当前游戏状态
    $tmInfo = array();
    $statusInfo = $this->getCommonService()->getFromMemcache(self::getCurrentGameStatusInfoCacheKey());
    if ($statusInfo) $tmInfo = $statusInfo['tm_info'];
    if (!$tmInfo || $tmInfo['tm_id'] != $tmId) $this->outputJsonData(0, array(
      'apptip' => '本轮已经结束了！'
    ));
    
    //答题
    $isRight = FALSE;
    if (trim($tmInfo['song_name']) == trim($content)) {
      $isRight = TRUE;
      $statusInfo['right_count'] ++;
    } else {
      $isRight = FALSE;
      $statusInfo['wrong_count'] ++;
    }
    
    //更新游戏状态数 & 用户游戏状态（rank: 答对排名）
    $remainRountTime = $statusInfo['start_time'] + $statusInfo['round_time'] - time();
    $this->getCommonService()->setToMemcache(self::getCurrentGameStatusInfoCacheKey(), $statusInfo, $remainRountTime);
    if ($isRight) $this->getCommonService()->setToMemcache(self::getCurrentUserPlayStatusCacheKey($this->currentUser['uid']), array('rank' => $statusInfo['right_count']), $remainRountTime);

    if ($isRight) {
      //判断用户是否是第1个答对
      if ($statusInfo['right_count'] == 1) {
        
        //答对获得金币
        $res = $this->getUserFortuneService()->autoUserFortuneCoin($this->currentUser['uid'], 22);
        
        //发房间消息
        $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
        $this->getMessageService()->sendRcChatRoomMessage($officialUserInfo, array(self::CHAT_ROOM_ID), 'game_first_win', array($this->currentUser['nickname'], $res['coin']));
        
      } else {
        $res = $this->getUserFortuneService()->autoUserFortuneCoin($this->currentUser['uid'], 20);
      }
      
      //记录答对用户
      $this->getGameService()->addGameTgqcgmRightUser(array(
        'tm_id' => $tmInfo['tm_id'],
        'uid' => $this->currentUser['uid'],
        'rank' => $statusInfo['right_count'],
      ));
    } 
    
    //答错扣金币(暂时不做处理)
    //$this->getUserFortuneService()->autoUserFortuneCoin($this->currentUser['uid'], 21);
    
    $data = array();
    $data['result'] = $isRight ? 1 : 0;
    $data['desc'] = $isRight ? "获得{$res['coin']}金币" : '不对，继续猜...';
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 公布答案
   * @param int tm_id 游戏题目id
   */
  public function actionResult () {
    $tmId = $this->getSafeRequest('tm_id', 0, 'int');
  
    //参数验证
    if (!$tmId) $this->outputJsonData(1);
    
    //获取当前游戏状态
    $tmInfo = array();
    $statusInfo = $this->getCommonService()->getFromMemcache(self::getCurrentGameStatusInfoCacheKey());
    if ($statusInfo) $tmInfo = $statusInfo['tm_info'];
    if (!$tmInfo || $tmInfo['tm_id'] != $tmId) $this->outputJsonData(1);
  
    //验证用户是否答对过
    $resDesc = '';
    $userPlayStatus = $this->getCommonService()->getFromMemcache(self::getCurrentUserPlayStatusCacheKey($this->currentUser['uid']));
    if ($userPlayStatus) $resDesc = $userPlayStatus['rank'] == 1 ? '你猜对了，获得30金币。' : '你猜对了，获得20金币。';
    
    //答对比例计算
    $correctPercent = mt_rand(20, 90);//ceil($statusInfo['right_count'] / ($statusInfo['right_count'] + $statusInfo['wrong_count']) * 100);
    
    //答题结果记录
    //TODO
    
    $data = array();
    $data['info']['media_name'] = $tmInfo['song_name'];
    $data['info']['media_singer'] = $tmInfo['singer'];
    $data['info']['correct_percent'] = intval($correctPercent);
    $data['info']['desc'] = $resDesc;
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 上传题目
   */
  public function actionUploadTm () {
    $mediaName = $this->getSafeRequest('media_name', '', 'string');
    $mediaSinger = $this->getSafeRequest('media_singer', '', 'string');
    $mediaDuration = $this->getSafeRequest('media_duration', 0, 'int');
    
    //参数验证
    if (!$mediaName || !$mediaDuration) $this->outputJsonData(1);

    //上传音频附件
    $file = $this->currentClientId == 1 ? Yii::app()->params['upload_token'] : Yii::app()->params['upload_token_android'];
    $uploadRes = $this->getAttachmentService()->uploadAttach($file, 2, 'FOPS_TO_MP3');
    if ($uploadRes['code'] != 1 || !$uploadRes['fileInfo']) $this->outputJsonData(1);
    
    //写入题目库
    $tmInfo = $this->getGameService()->addGameTgqcgmTm(array(
      'uid' => $this->currentUser['uid'],
      'song_name' => $mediaName,
      'singer' => $mediaSinger,
      'uri' => $uploadRes['fileInfo']['file_uri'] . $uploadRes['fileInfo']['file_name'],
      'ori_name' => $uploadRes['fileInfo']['ori_name'],
      'duration' => $mediaDuration,
    ));
    if ($tmInfo) {
      $data = array();
      $data['tm_id'] = (int) $tmInfo['tm_id'];
      $data['share_link'] = WEB_QW_APP_M_DOMAIN . "/s/{$tmInfo['tm_id']}.html";
      $this->outputJsonData(0, $data);
    }
    $this->outputJsonData(500);
  }
}
?>