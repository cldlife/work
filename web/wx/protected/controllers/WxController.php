<?php
/**
 * @desc 微信公众号开发者接口
 * @see 根据biz区分接入的不同公众号
 */
class WxController extends BaseController {

  //hougong 金币
  const INIT_HOUGONG_COIN_RULEID = 30;
  const INIT_VALUES_RULEID = 2;

  //whoisspy 金币
  const INIT_COIN_RULEID = 5;

  const MIN_COIN_START = 10;

  const MIN_ROOM_PLAYERS = 4;
  const MAX_ROOM_PLAYERS = 6;

  private static $WEIXIN_CONFIG = NULL;
  private static $self = NULL;
  private static function getSelfInstance () {
    if (!self::$self) {
      self::$self = new Self();
    }
    return self::$self;
  }

  /**
   * @desc actions 主入口
   * @see 初始化公众号配置
   */
  public function run ($actionID) {
    self::$WEIXIN_CONFIG = Yii::APP()->params['weixinConfig']['wanZhuyule'];
    $this->getWeixinService()->setWeixinConfig(self::$WEIXIN_CONFIG);
    $method = $this->getURIDoAction($this, 1);
    $this->$method();
  }

  /**
   * @desc 错误处理回调方法
   */
  public function errorRedirect () {
    Yii::app()->runController('site/error');
  }

  /**
   * @desc 微信用户授权获取用户信息
   */
  private function doAuthorize () {
    $state =  $this->getSafeRequest('state');
    $code =  $this->getSafeRequest('code');
    $rf =  $this->getSafeRequest('rf');

    $currentClientId = 3;
    $appid = self::$WEIXIN_CONFIG['WEIXIN_APP_ID'];
    $secret = self::$WEIXIN_CONFIG['WEIXIN_APP_SECRET'];
    $checkState = Utils::generateCSRFSecret(date('YmdH'));

    if ($rf && $appid && $secret && $code && ($state == $checkState)) {
      //微信登录授权
      $weixinGetAccessTokenUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code";
      $weixinAccessInfoContent = HttpClient::curl($weixinGetAccessTokenUrl);
      $weixinAccessInfoJson = trim($weixinAccessInfoContent['content']);

      $weixinAccessInfo = array();
      if ($weixinAccessInfoJson) $weixinAccessInfo = json_decode($weixinAccessInfoJson, TRUE);

      if ($weixinAccessInfo['access_token'] && $weixinAccessInfo['openid']) {
        //获取微信用户信息
        $weixinGetUserInfoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token={$weixinAccessInfo['access_token']}&openid={$weixinAccessInfo['openid']}";
        $weixinUserInfoContent = HttpClient::curl($weixinGetUserInfoUrl);
        $weixinAccessInfoJson = trim($weixinUserInfoContent['content']);

        $weixinUserInfo = array();
        if ($weixinAccessInfoJson) $weixinUserInfo = json_decode($weixinAccessInfoJson, TRUE);
        if ($weixinUserInfo) {
          //登录
          $user = $this->getUserService()->loginByWeixin(array(
            'appid' => $appid,
            'openid' => $weixinAccessInfo['openid'],
            'unionid' => $weixinAccessInfo['unionid'] ? $weixinAccessInfo['unionid'] : $weixinAccessInfo['openid'],
            'nickname' => $weixinUserInfo['nickname'],
            'gender' => $this->getUserService()->getGender($weixinUserInfo['sex']),
            'avatar' => $weixinUserInfo['headimgurl'],
            'access_token' => $weixinAccessInfo['access_token'],
            'expires_in' => $weixinAccessInfo['expires_in'],
            'refresh_token' => $weixinAccessInfo['refresh_token'],
            'location' => '',
            'sign' => '',
            'reg_ip' => Yii::app()->request->userHostAddress,
            'reg_from' => 1,
            'wx_from' => 2,
          ));

          //生成会话
         if ($user['user']['uid'] && $user['user']['private_key']) {

            //新用户首次赠送金币 & 身价
            if ($user['weixin_user']['is_new']) {
              if (!$this->getUserFortuneService()->getUserFortuneStatusByUid($user['user']['uid'])) $this->getUserFortuneService()->initUserFortuneStatus($user['user']);

              //whoisspy 金币
              $this->getUserFortuneService()->autoUserFortuneCoin($user['user']['uid'], self::INIT_COIN_RULEID);

              //hougong 金币 & 身价
              $this->getUserFortuneService()->autoUserFortuneCoin($user['user']['uid'], self::INIT_HOUGONG_COIN_RULEID);
              $this->getUserFortuneService()->autoUserFortuneValues($user['user']['uid'], self::INIT_VALUES_RULEID);
            }

            $suuid = $this->getUserService()->generateUserSessionToken($currentClientId, $user['user']['uid'], $user['user']['private_key']);
            $this->deleteCookie(self::WANZHU_SUUID_COOKIE_NAME);
            $this->setCookie(self::WANZHU_SUUID_COOKIE_NAME, $suuid);
            $this->redirect(urldecode($rf));
          }
        }
      }
    }
    exit;
  }

  /**
   * @desc 获取用户信息，没有则添加新的用户信息
   * @param string $openid
   * @return array
   */
  private function getWxUserInfo ($openid) {
    $userInfo = array();
    if ($openid) {
      $wxIndex = $this->getUserService()->getUserWeixinOpenidIndex($openid);
      $userInfo = ($wxIndex) ? $this->getUserService()->getUserWeixinInfo($wxIndex['uid']) : array();
      if (!$userInfo) $token = $this->getCommonService()->getWxAccesstoken(self::$WEIXIN_CONFIG['WEIXIN_APP_ID'], self::$WEIXIN_CONFIG['WEIXIN_APP_SECRET']);
      if (!$userInfo && $token) {
        $this->getWeixinService()->setAccessToken($token);
        $userWxInfo = $this->getWeixinService()->getUserInfo(array('openid' => $openid));
      if ($userWxInfo) {
          if (!$userWxInfo['unionid']) $userWxInfo['unionid'] = $userWxInfo['openid'];
          $userWxInfo['appid'] = self::$WEIXIN_CONFIG['WEIXIN_APP_ID'];
          $userWxInfo['gender'] = $this->getUserService()->getGender($userWxInfo['sex']);
          $userWxInfo['avatar'] = $userWxInfo['headimgurl'];
          $userWxInfo['reg_ip'] = Yii::app()->request->userHostAddress;
          $userWxInfo['wx_from'] = 2;//来自公众号
          $userInfo = $this->getUserService()->loginByWeixin($userWxInfo);
          $userInfo = ($userInfo) ? $userInfo['weixin_user'] : array();

          //新用户首次赠送金币
          if ($userInfo['is_new']) {
            if (!$this->getUserFortuneService()->getUserFortuneStatusByUid($userInfo['uid'])) $this->getUserFortuneService()->initUserFortuneStatus($userInfo);
            $this->getUserFortuneService()->autoUserFortuneCoin($userInfo['uid'], self::INIT_COIN_RULEID);
            }
       }
      }
      if ($userInfo) {
        $userInfo['openid'] = $openid;
        $userInfo['user_status'] = $this->getUserFortuneService()->getUserFortuneStatusByUid($userInfo['uid']);
      }
    }
    return $userInfo;
  }

  private static $gameRules = array(
    4 => "房间里一共4人，1个卧底，3个平民。卧底被揪出，平民胜；剩下1个平民，卧底胜；",
    5 => "房间里一共5人，1个卧底，4个平民。卧底被揪出，平民胜；剩下2个平民，卧底胜；",
    6 => "房间里一共6人，1个卧底，5个平民。卧底被揪出，平民胜；剩下2个平民，卧底胜；",
  );
  /**
   * @desc 生成游戏信息(卧底)和规则(结束条件)
   * @param array $playersList
   * @return array
   */
  private static function getGameInfoAndRule ($playersList) {
    $len = count($playersList);
    if ($playersList && self::$gameRules[$len]) {
      $gameInfo = array();
      foreach ($playersList as $key => $player) {
        $gameInfo['players'][$key + 1] = $player['uid'];
      }
      $gameInfo['spy'] = self::pickSpyFromPlayers($playersList, $len);
      $gameInfo['remain'] = ($len == 4) ? 1 : 2;

      //1， 20起占位作用,对查询的数据不影响
      $spyWords = self::getSelfInstance()->getGameService()->getGamesetSpywords(1, 20, TRUE);
      $gameInfo['words'] = ($spyWords) ? $spyWords[mt_rand(0, count($spyWords) - 1)] : array();
      return array(
        'info' => $gameInfo,
        'rule' => self::$gameRules[$len],
      );
    }
    return array();
  }

  /**
   * @desc 根据一定规则抽选卧底用户uid
   * 生成一个玩家池
   * 当真实玩家少于3人,由机器人补充成3人
   * 当真实玩家大于3人,由所有真实玩家组成
   * @param array $playersList
   * @param int count($playersList)
   * @param int $minPoolNum
   * @return int $uid (spy)
   */
  private static function pickSpyFromPlayers ($playersList, $len, $minPoolNum = 3) {
    $playersPool = SpyGame::getRobotsFromUsers($playersList, TRUE);
    $playersPoolNum = count($playersPool);
    if ($playersList && $playersPoolNum < $minPoolNum) {
      foreach ($playersList as $player) {
        if ($player['is_robot']) {
          $playersPool[] = $player;
          ++ $playersPoolNum;
        }
        if ($playersPoolNum >= $minPoolNum) break;
      }
    }
    -- $playersPoolNum;

    return $playersPool ? $playersPool[mt_rand(0, $playersPoolNum)]['uid'] : 0;
  }

  //用户发送0时的回复消息
  private static $replyHelpMsg = array('item' => array(
    'Title' => '如何与好友在线玩谁是卧底？',
    'Description' => '爆款社交游戏谁是卧底玩起来其实很简单。',
    'PicUrl' => 'https://mmbiz.qlogo.cn/mmbiz_jpg/zLo3EdXyibXVgY3QJyTU31XVgfwvibq630VpWUibh8reYJoJP4pXlD0fYcDVT2D6GP4ceflAdn82GaupY4t3QicAjg/0?wx_fmt=jpeg',
    'Url' => 'http://url.cn/418Eoj6',
  ));
  /**
   * @desc 接收并处理玩主公众号事件和消息
   */
  private function doWanzhu () {
    $signature = $this->getSafeRequest('signature');
    $timestamp = $this->getSafeRequest('timestamp');
    $nonce = $this->getSafeRequest('nonce');
    $echostr = $this->getSafeRequest('echostr');
    $msgSig = $this->getSafeRequest('msg_signature');
    $recvMsg = file_get_contents('php://input');

    try {
      if ($recvMsg && $msgSig && $timestamp && $nonce && $this->getWeixinService()->verifyWxMsgSignature(array($timestamp, $nonce), $recvMsg, $msgSig)) {

        $msg = $this->getWeixinService()->getWxMsg($recvMsg);
        if (!$msg || !$msg['FromUserName'] || !$msg['MsgType']) {
          echo 'success';
          exit;
        }

        //微信消息排重
        $cacheKey = ($msg['MsgType'] == 'event') ? $msg['FromUserName'] . $msg['CreateTime'] : $msg['MsgId'];
        if ($this->getCommonService()->getFromMemcache($cacheKey)) {
          echo 'success';
          exit;
        } else {
          $this->getCommonService()->setToMemcache($cacheKey, TRUE, 30);
        }

        //获取user info, uf_status
        $this->currentUser = $this->getWxUserInfo($msg['FromUserName']);
        if (!$this->currentUser) {
          echo 'success';
          exit;
        }

        $isNewsMsg = FALSE;
        $replyMsg = array();

        //处理事件或消息
        switch ($msg['MsgType']) {
        case 'event' :
          //event: subscribe, SCAN, CLICK, LOCATION, VIEW
          //subscribe: $msg['EventKey'] = 'qrscene_{$room_id}'; SCAN: $msg['EventKey'] = '{$room_id}'
          if (in_array($msg['Event'], array('subscribe', 'SCAN', 'CLICK')) && $msg['EventKey']) {
            if ($msg['Event'] == 'subscribe') $msg['EventKey'] = substr($msg['EventKey'], 8);
            $content = $this->handleWxEvent($msg);
          } else if ($msg['Event'] == 'subscribe' && !$msg['EventKey']) {
            $content = GameMsg::getMsgContent();
          }
          break;

        case 'text':
          $content = $this->handleWxMsg($msg);
          if (!$content && trim($msg['Content']) === "0") $isNewsMsg = TRUE;
          break;

        default:
          //msg type: image, voice, video, shortvideo, location, link
          break;
        }

        if ($isNewsMsg) {
          $replyMsg = array(
            'openid' => $msg['FromUserName'],
            'msgtype' => 'news',
            'timestamp' => $timestamp,
            'nonce' => $nonce,
            'content' => self::$replyHelpMsg,
          );
        } else if ($content) {
          $replyMsg = array(
            'openid' => $msg['FromUserName'],
            'msgtype' => 'text',
            'content' => $content,
            'timestamp' => $timestamp,
            'nonce' => $nonce,
          );
        }
        echo $replyMsg ? $this->getWeixinService()->setWxMsg($replyMsg) : 'success';

      } else if ($echostr && $signature && $this->getWeixinService()->verifyWxServerConfig(array($timestamp, $nonce), $signature)) {
        //微信服务器修改配置验证回复
        echo $echostr;

      } else {
        echo 'success';
      }
    } catch (Exception $e) {
      Utils::log("msg:". json_encode($msg) ."WanzhuError:{$e->getMessage()}", 'wanzhu_error');
      echo 'success';
    }
  }

  /**
   * @desc 处理接收到的事件:
   * create_room: 创建房间,至少10金币
   * start_game: 人数至少4人
   * exit_room: 退出房间
   * status: 当前游戏状态
   * room_id: 加入房间,人数4-6,房间状态
   */
  private function handleWxEvent ($msg) {
    if ($this->currentUser && $msg && $msg['EventKey']) {
      $room = $this->getGameService()->getRoomByUid($this->currentUser['uid']);

      switch ($msg['EventKey']) {
        case 'create_room': //创建房间
          return $this->createRoom($room);
          break;
        case 'start_game': //开始游戏
          return $this->startGame($room);
          break;
        case 'exit_room': //退出房间
          return $this->exitRoom($room);
          break;
        case 'status': //游戏状态
          return $this->getStatus($room);
          break;
        default: //join room: room_id
          return $this->joinRoom($room, $msg['EventKey']);
          break;
      }
    }
    return "";
  }

  /**
   * @desc 创建房间
   * @param array $room
   * @return string replay msg content
   */
  private function createRoom ($room) {
    if ($room) {
      return GameMsg::getMsgContent('create_room_already_in_room');
    }
    //else if ($this->currentUser['user_status']['coins'] < self::MIN_COIN_START) {
    //  return GameMsg::getMsgContent('create_room_coins_not_enough');
    //}
    $roomId = $this->getGameService()->generateNewRoom(array(
      'host' => $this->currentUser['uid'],
      'players' => 1,
      'type' => 1,
      'status' => 2,
    ));
    if ($roomId) {
      SpyGame::addRobotTask('create', $roomId);
      return GameMsg::getMsgContent('create_room_successed', array('%room_id%' => $roomId));
    } else {
      return GameMsg::getMsgContent('create_room_failed');
    }
  }

  /**
   * @desc 开始游戏
   * @param array $room
   * @return string replay msg content
   */
  private function startGame ($room) {
    if (!$room) return GameMsg::getMsgContent('start_game_without_room');
    $playersList = $this->getGameService()->getUserListByRoomId($room['id']);
    if (!$playersList) return '';

    $game = ($room['game_id']) ? $this->getGameService()->getGameById($room['game_id']) : array();
    if ($game && $game['status'] > 0) return GameMsg::getMsgContent('start_game_when_gaming');

    $playersNum = count($playersList);
    if ($playersNum < self::MIN_ROOM_PLAYERS) {
      SpyGame::addRobotTask('join', $room['id']);
      return GameMsg::getMsgContent('start_game_players_not_enough', array('%room_id%' => $room['id']));
    }

    //不是房主,发送消息通知房主
    if ($room['host'] != $this->currentUser['uid']) {
      $hostInfo = self::getSelfInstance()->getGameService()->getWanzhuWxUserInfo($room['host']);
      $sendHostMsg = GameMsg::getMsgContent('start_game_players_cannot_wait', array('%player%' => $this->currentUser['nickname']));
      GameMsg::sendAsyncMsg((array($hostInfo)), $sendHostMsg);
      return GameMsg::getMsgContent('start_game_is_not_host');
    }

    //获取卧底词,游戏人数对应规则
    $infoAndRule = self::getGameInfoAndRule($playersList);
    if (!$infoAndRule) return GameMsg::getMsgContent('start_game_failed');

    $gameId = $this->getGameService()->addGame(array(
      'room_id' => $room['id'],
      'type' => 1,
      'status' => 1,
      'info' => json_encode($infoAndRule['info']),
    ));
    if (!$gameId) GameMsg::getMsgContent('start_game_failed');

    //生成游戏开始通知消息
    $replace = array(
      '%count%' => $playersNum,
      '%rule%' => $infoAndRule['rule'],
      '%first%' => $playersList[0]['nickname'],
      '%players%' => SpyGame::getPlayerReplaceStr($playersList, $room, TRUE),
    );
    $msg = "";
    $jobMsg = array();
    foreach ($playersList as $key => $player) {
      if ($player['is_robot']) continue;
      $replace['%word%'] = ($player['uid'] == $infoAndRule['info']['spy']) ? $infoAndRule['info']['words']['spy'] : $infoAndRule['info']['words']['normal'];
      $replace['%cur_no%'] = $key + 1;
      if ($player['uid'] == $this->currentUser['uid']) {
        $msg = GameMsg::getMsgContent('start_game_successed', $replace);
      } else {
        $jobMsg[$key]['openid'] = $player['openid'];
        $jobMsg[$key]['content'] = GameMsg::getMsgContent('start_game_successed', $replace);
      }
    }
    $this->getGearmanService()->addSendMsgJob($jobMsg);
    //添加机器人任务 speak
    if ($playersList[0]['is_robot']) SpyGame::addRobotTask('speak', $gameId, $playersList[0]['uid']);

    return $msg;
  }

  /**
   * @desc 退出房间
   * @param array $room
   * @return string replay msg content
   */
  private function exitRoom ($room) {
    if (!$room) return GameMsg::getMsgContent('exit_room_without_room');
    $game = ($room['game_id']) ? $this->getGameService()->getGameById($room['game_id']) : array();
    if ($game && $game['status']) return GameMsg::getMsgContent('exit_room_when_gaming');

    if (!$this->getGameService()->deleteRoomUser($room['id'], $this->currentUser['uid'])) return '';

    //last one exit room
    $playersList = $this->getGameService()->getUserListByRoomId($room['id']);
    $playersNum = count($playersList);
    if ($playersNum == 0) {
      $this->getGameService()->updateRoomIdByNumber($room['number']);
      $this->getGameService()->updateRoomByRoomId($room['id'], array('status' => 0));
      return ($room['host'] == $this->currentUser['uid']) ? GameMsg::getMsgContent('exit_room_host_successed_last') : GameMsg::getMsgContent('exit_room_successed_last');
    }

    //still have people in room
    $trueUsers = SpyGame::getRobotsFromUsers($playersList, TRUE);
    if ($trueUsers && $playersNum < self::MIN_ROOM_PLAYERS) {
      SpyGame::addRobotTask('join', $room['id']);

    } else if (!$trueUsers) {
      //剩下的都是机器人,解散房间
      $this->getGameService()->updateGameById($game['id'], array('status' => 0));
      $this->getGameService()->updateRoomIdByNumber($room['number']);
      $this->getGameService()->updateRoomByRoomId($room['id'], array('status' => 0));
      foreach ($playersList as $robot) {
        $this->getGameService()->deleteRoomUser($room['id'], $robot['uid'], TRUE);
      }
    }

    $replace = array(
      '%room_id%' => $room['id'],
      '%quitter%' => $this->currentUser['nickname'],
      '%count%' => $playersNum,
      '%players%' => SpyGame::getPlayerReplaceStr($playersList, $room),
    );
    if ($room['host'] == $this->currentUser['uid']) {
      $newHost = $trueUsers ? $trueUsers[0] : $playersList[0];
      $room['host'] = $newHost['uid'];
      $replace['%new_host%'] = $newHost['nickname'];

      $this->getGameService()->updateRoomByRoomId($room['id'], array('host' => $room['host']));
      $msg = GameMsg::getMsgContent('exit_room_host_successed_others', $replace);
      GameMsg::sendAsyncMsg($playersList, $msg);
      return GameMsg::getMsgContent('exit_room_host_successed');

    } else {
      $msg = ($replace['%count%'] >= self::MIN_ROOM_PLAYERS) ? GameMsg::getMsgContent('exit_room_successed_others_enough', $replace) : GameMsg::getMsgContent('exit_room_successed_others_not_enough', $replace);
      GameMsg::sendAsyncMsg($playersList, $msg);
      return GameMsg::getMsgContent('exit_room_successed');
    }
  }

  /**
   * @desc 获取状态
   * @param array $room
   * @return string replay msg content
   */
  private function getStatus ($room) {
    if (!$room) return GameMsg::getMsgContent('status_without_room');

    $playersList = $this->getGameService()->getUserListByRoomId($room['id']);
    $replace = array(
      '%room_id%' => $room['id'],
      '%count%' => count($playersList),
      '%players%' => SpyGame::getPlayerReplaceStr($playersList, $room),
    );
    if ($replace['%count%'] < self::MIN_ROOM_PLAYERS) {
      SpyGame::addRobotTask('join', $room['id']);
      return GameMsg::getMsgContent('status_players_not_enough', $replace);
    }

    $game = ($room['game_id']) ? $this->getGameService()->getGameById($room['game_id']) : array();
    if ($game && $game['status'] == 1 && $game['type'] == 1) {
      try {
        $game = new SpyGame($room, $game, $this->currentUser);
        return $game->getStatus();
      } catch (Exception $e) {
        Utils::log("{$e->getMessage()}", 'game_error');
        return GameMsg::getMsgContent('status_failed');
      }
    } else {
      return GameMsg::getMsgContent('status_new_game', $replace);
    }
  }

  /**
   * @desc 加入房间
   * @param array $prevRoom 是否已经在房间里面
   * @param int $roomId
   * @return string replay msg content
   */
  private function joinRoom ($prevRoom, $roomId) {
    $room = $this->getGameService()->getRoomById($roomId);
    if (!$room || $room['status'] == 0) {
      return GameMsg::getMsgContent('join_room_wrong_id');
    } else if ($prevRoom && ($prevRoom['number'] == $room['number'])) {
      return GameMsg::getMsgContent('join_room_in_same_room', array('%number%' => $room['number']));
    } else if ($prevRoom) {
      return GameMsg::getMsgContent('join_room_in_room');
    }

    //if ($this->currentUser['user_status']['coins'] < self::MIN_COIN_START) return GameMsg::getMsgContent('join_room_coins_not_enough');
    $game = ($room['game_id']) ? $this->getGameService()->getGameById($room['game_id']) : array();
    if ($game && $game['status'] == 1) return GameMsg::getMsgContent('join_room_when_gaming');
    if ($room['players'] >= self::MAX_ROOM_PLAYERS) return GameMsg::getMsgContent('join_room_filled');

    $playersList = $this->getGameService()->getUserListByRoomId($room['id']);
    $playersList[] = $this->currentUser;
    $replace = array(
      '%room_id%' => $room['id'],
      '%players%' => SpyGame::getPlayerReplaceStr($playersList, $room),
      '%count%' => count($playersList),
      '%new%' => $this->currentUser['nickname'],
    );
    array_pop($playersList); //去掉该数据，防止重复发送消息

    $res = $this->getGameService()->addRoomUser($room['id'], $this->currentUser['uid']);
    if ($res && (($room['players'] + 1) < self::MIN_ROOM_PLAYERS)) {
      $msg = GameMsg::getMsgContent('join_room_successed_not_enough', $replace);
      GameMsg::sendAsyncMsg($playersList, $msg);
      return GameMsg::getMsgContent('join_room_successed_self_not_enough', $replace);

    } else if ($res) {
      $msg = GameMsg::getMsgContent('join_room_successed_enough', $replace);
      GameMsg::sendAsyncMsg($playersList, $msg);
      return GameMsg::getMsgContent('join_room_successed_self_enough', $replace);

    } else {
      return GameMsg::getMsgContent('join_room_failed');
    }
  }

  /**
   * @desc 处理接收到的消息
   * 游戏未开始,玩家互相聊天
   * 游戏已经开始,玩家发送的内容:
   *   speak string
   *   vote  int
   */
  private function handleWxMsg ($msg) {
    if ($msg && $msg['FromUserName'] && $msg['MsgType'] && $msg['MsgType'] == 'text' && $this->currentUser) {
      $room = $this->getGameService()->getRoomByUid($this->currentUser['uid']);
      if (!$room) return "";
      $game = ($room['game_id']) ? $this->getGameService()->getGameById($room['game_id']) : array();

      if (!$game || $game['status'] == 0) {  //互相发送可见的消息,聊天功能
        $playersList = $this->getGameService()->getUserListByRoomId($room['id']);
        foreach ($playersList as $key => $val) {
          if ($val['uid'] == $this->currentUser['uid']) {
            unset($playersList[$key]);
            break;
          }
        }
        $replace = array(
          '%player%' => $this->currentUser['nickname'],
          '%content%' => $msg['Content'],
        );
        $chat = GameMsg::getMsgContent('msg_players_chat', $replace);
        GameMsg::sendAsyncMsg($playersList, $chat);
        return $chat;

      } else if ($game['type'] == 1) {  //谁是卧底游戏
        try {
          $game = new SpyGame($room, $game, $this->currentUser);
          return $game->play($msg['Content']);
        } catch (Exception $e) {
          Utils::log("msg:". json_encode($msg) .";{$e->getMessage()}", 'game_error');
        }
      }
    }
    return "";
  }

  /**
   * @desc 创建/修改公众号菜单
   */
  private static function getWxMenus () {
    return array(
      'button' => array(
        array('name' => '开始玩',
          'sub_button' => array(
            array('type' => 'click', 'name' => '创建房间', 'key' => 'create_room'),
            array('type' => 'click', 'name' => '开始游戏', 'key' => 'start_game'),
            array('type' => 'click', 'name' => '退出房间', 'key' => 'exit_room'),
            array('type' => 'view', 'name' => '个人中心', 'url' => "http://wx.wanzhuwenhua.com/usercenter.html"),
            array('type' => 'view', 'name' => '游戏帮助', 'url' => "http://url.cn/418Eoj6"),
          ),
        ),
        array('type' => 'view', 'name' => '游戏大厅', 'url' => "http://wx.wanzhuwenhua.com/room/roomlist.html?share=gzh"),
        array('type' => 'click', 'name' => '刷新', 'key' => 'status'),
      ),
    );
  }
  /**
   * @desc 获取/创建/修改公众号菜单
   * $domain/wx/menus.html?key=shihuo123&action=...
   */
  private function doMenus () {
    $token = $this->getCommonService()->getWxAccesstoken(self::$WEIXIN_CONFIG['WEIXIN_APP_ID'], self::$WEIXIN_CONFIG['WEIXIN_APP_SECRET']);
    $this->getWeixinService()->setAccessToken($token);
    $key = $this->getSafeRequest('key');
    $action = $this->getSafeRequest('action');
    if ($key == 'shihuo123') {
      if ($action == 'get') {
        $menus = $this->getWeixinService()->getMenu();
        if ($menus) echo (json_encode($menus));
      } else if ($action == 'create') {
        if ($this->getWeixinService()->createMenu(self::getWxMenus())) {
          echo '修改菜单成功';
        }
      } else if ($action == 'delete') {
        if ($this->getWeixinService()->deleteMenu()) {
          echo '删除菜单成功';
        }
      }
    }
  }
}

