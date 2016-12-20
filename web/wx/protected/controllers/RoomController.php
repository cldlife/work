<?php
/**
 * @desc 微信我是卧底房间列表
 * @author dong
 */
class RoomController extends BaseController {

  //一个房间最多人数
  private static $maxRoomNum = 6;

  //一个房间的最少人数
  private static $minRoomNum = 4;

  //加入房间所需的最少金币数
  private static $minRoomCoin = 10;

  //每页显示数
  private static $pageSize = 10;

  //分享标题
  private static $shareTitle = '***邀请你一起来玩在线谁是卧底！来互相伤害啊!';

  //分享链接
  private static $sharelink = 'http://wx.wanzhuwenhua.com/room/roomlist.html';

  public $layout = 'main_wodi';

  //踢人
  public function actionSwipe () {
    $uids = $this->getSafeRequest('users', '', 'POST', 'string');
    $uids = $uids ? json_decode($uids, TRUE) : array();

    $room = $this->getGameService()->getRoomByUid($this->currentUser['uid']);
    $userList = ($room) ? $this->getGameService()->getUserListByRoomId($room['id']) : array();
    if ($room && $uids) {
      $game = ($room['game_id']) ? $this->getGameService()->getGameById($room['game_id']) : array();
      if ($game && $game['status']) $this->outputJsonData(1, array('msg' => '游戏正在进行中, 不能踢出玩家'));

      if (!$userList) $this->outputJsonData(2, array('msg' => '无法获取玩家列表'));

      $swipedUsers = array();
      $swiped = $players = "";
      foreach ($userList as $key => $user) {
        if (in_array($user['uid'], $uids) && $this->getGameService()->deleteRoomUser($room['id'], $user['uid'], $user['is_robot'])) {
          $swiped .= "【{$user['nickname']}】";
          $swipedUsers[] = $user;
          unset($userList[$key]);
        } else {
          $players .= ($room['host'] == $user['uid']) ? "{$user['nickname']}【房主】\n" : "{$user['nickname']}\n";
        }
      }
      if (!$swipedUsers) {
        $this->outputJsonData(3, array('msg' => '移除玩家失败'));
      }
      if (SpyGame::getRobotsFromUsers($userList, TRUE) && count($userList) < self::$minRoomNum) {
        SpyGame::addRobotTask('join', $room['id']);
      }

      $replace = array(
        '%room_id%' => $room['id'],
        '%swiped%' => $swiped,
        '%count%' => count($userList),
        '%players%' => $players,
      );
      GameMsg::sendAsyncMsg($userList, GameMsg::getMsgContent('swipe_room_others', $replace));
      GameMsg::sendAsyncMsg($swipedUsers, GameMsg::getMsgContent('swipe_room_swiped', $replace));
      $this->outputJsonData(0, array('msg' => '移除成功'));

    } else if ($room) {
      $this->title = '踢人';
      $data = array(
        'room' => $room,
        'user_list' => $userList,
      );
      $this->render('swipe', $data);
    }
  }

  //虚拟房间编号 & 随机房间数
  const PSEUDO_ROOM = "42_0110";
  private static $onlineGameCounts = array(8, 15, 20, 25);
  private function getPseudoRoomCount () {
    $roomCount = 0;
    $hour = date('G');
    if ($hour >= 18) {
      $roomCount = self::$onlineGameCounts[3];
    } else if ($hour >= 12) {
      $roomCount = self::$onlineGameCounts[2];
    } else if ($hour >= 7) {
      $roomCount = self::$onlineGameCounts[1];
    } else {
      $roomCount = self::$onlineGameCounts[0];
    }
    return $roomCount;
  }

  //虚拟房间
  private function getPseudoGames ($number = 0) {
    $length = $this->getPseudoRoomCount();
    $pseudoUsers = Yii::app()->params['pseudoUsers'];

    $cacheTime = 300;
    $cacheKey = __FUNCTION__ . $start . '_v1';
    $start = floor(time() / $cacheTime);
    $list = $this->getCommonService()->getFromMemcache($cacheKey);
    if (!$list) {
      $userListKeys = array_rand($pseudoUsers, $length);
      shuffle($userListKeys);

      $list = array();
      $list['keys'] = $userListKeys;
      $list['number'] = $number;
      $this->getCommonService()->setToMemcache($cacheKey, $list, $cacheTime);
    }

    $pseudoGames = array();
    foreach ($list['keys'] as $key) {
      $user = $pseudoUsers[$key];
      $roomNumber = $list['number'] + 500 + $user['id'];
      $tmpGame = array(
        'nickname' => $user['nickname'],
        'avatar' => $user['avatar'],
        'status' => $user['id'] % 2 ? 1 : 2,
        'number' => $roomNumber,
        'pseudo' => TRUE,
        'gameNum' => 0
      );
      $pseudoGames[] = $tmpGame;
    }
    return $pseudoGames;
  }

  //读取房间列表
  public function actionRoomlist(){
    $this->title = "房间列表";
    $uid = $this->currentUser['uid'];
    $share = $this->getSafeRequest('share', '', 'GET');
    if ($uid) {
      $userRoom = $this->getGameService()->getRoomByUid($uid);
      $roomCount = $this->getGameService()->getActiveRoomCount();

      $data = array();
      if ($userRoom) $data['number'] = $userRoom['number'];
      $data['roomCount'] = $roomCount += $this->getPseudoRoomCount();
      $data['shareTitle'] = str_replace('***', $this->currentUser['nickname'], self::$shareTitle);
      $data['sharelink'] = self::$sharelink;
      $weixinConfig = Yii::app()->params['weixinConfig']['wanZhuyule'];
      $data['weixinJssdkConfig'] = $this->getCommonService()->getJssdkConfigByAppid($weixinConfig['WEIXIN_APP_ID'], $weixinConfig['WEIXIN_APP_SECRET']);
      $data['share'] = $share ? $share : '';
      $data['nickname'] = $this->currentUser['nickname'];
      $data['pseudoRoom'] = self::PSEUDO_ROOM;
      $this->render('roomlist',$data);
    }
  }

  //房间下啦列表
  public function actionRoomListAjax () {
    $page = $this->getSafeRequest('page', 0, 'POST', 'int');
    if ($page) {
      $gameList = $this->getGameService()->getRoomList($page, self::$pageSize);
      if ($page == 1) $gameList = array_merge($gameList, $this->getPseudoGames($gameList[0]['number']));
      if ($gameList) {
        $list = array();
        foreach ($gameList as $key => $item) {
          $joinNum = self::$maxRoomNum - $item['players'];//可加入的人数
          if (isset($item['gameNum'])) $joinNum = $item['gameNum'];

          $hostUid = $item['host'];//房主uid
          $userinfo = $this->getGameService()->getWanzhuWxUserInfo($hostUid);

          $itemlist = array();
          $itemlist['id'] = $item['id'];//房主头像
          $itemlist['avatar'] = ($item['avatar']) ? $item['avatar'] : $userinfo['avatar'];//房主头像
          $itemlist['nickname'] = ($item['nickname']) ? $item['nickname'] : $userinfo['nickname'];//房主昵称
          $itemlist['number'] = $item['number'];//4位数房间号
          $itemlist['gameState'] = $item['status'];//房间状态
          $itemlist['data'] = ($item['pseudo']) ? self::PSEUDO_ROOM : "{$item['id']}_{$item['number']}";
          $itemlist['gameNum'] = ($item['status'] == 1) ? 0 : $joinNum; //还可加入几人
          $list[] = $itemlist;
          unset($itemlist);
          unset($item);
        }
        header("Content-type: application/json");
        echo json_encode($list);
      }
    }
  }

  //加入房间
  public function actionRoomJoinAjax () {
    $info = $this->getSafeRequest('info', '', 'POST', 'string');
    $share = $this->getSafeRequest('share', '', 'POST');
    $uid = $this->currentUser['uid'];//要加入的用户uid

    if ($uid && $info) {
      list($room_id, $number, $beforeRoomID, $status) = explode('_', $info);
      $beforeRoom = $this->getGameService()->getRoomByUid($uid);//查询玩家是否已在房间中
      if ($beforeRoom['status'] == 1) {
        $this->outputJsonData(-6);
      } else {
        if ($uid == $beforeRoom['host']) {
          $isHost = 1;
        }
      }
      if (!$status) {
        $userStatus = $this->getUserFortuneService()->getUserFortuneStatusByUid($uid);
        // if ($userStatus['coins'] < self::$minRoomCoin)  $this->outputJsonData(-1); //金币不足无法加入
        $roomUser = $this->getGameService()->getRoomByUid($uid);//查询玩家是否已在房间中
        $room = $this->getGameService()->getRoomById($room_id);//查询房间状态
        if ($room['status'] == 1) $this->outputJsonData(-3); //游戏已经开始
        if($room['players'] == self::$maxRoomNum) $this->outputJsonData(-4); //房间人数已满
        if(!$roomUser){
          //不在房间
          if (!$share) {
             $ticket = $this->getRoomTicket($room);
             $this->outputJsonData(3, array(
              'ticket' =>  $ticket,
            )); //加入成功
          }
          $addRoomUser = $this->getGameService()->addRoomUser($room_id, $uid);
          if ($addRoomUser) {
            $list = $this->sendUserList($uid, $room_id);
            $this->roomSendMsg($list);//发消息
            $this->outputJsonData(1); //加入成功
          }
        } else {
          if ($roomUser['id'] == $room_id) {
            $this->outputJsonData(-2);//你已经在该房间内
          } else {
            $this->outputJsonData(2, array(
              'RoomInfo' =>  $room_id . '_' . $number . '_' . $roomUser['id'] . '_' . 2,
              'beforeRoomNum' => $roomUser['number'],
              'afterRoomNum' => $number
            ));//你当前在$roomUser['number']房间，要确定要加入$number房间么
          }
        }
      } else {
        $room = $this->getGameService()->getRoomById($room_id);//查询房间状态
        if ($room['status'] == 1) $this->outputJsonData(-3); //游戏已经开始
        if ($room['players'] == self::$maxRoomNum) $this->outputJsonData(-4); //房间人数已满
        if (!$share) {
          $ticket = $this->getRoomTicket($room);
          $this->outputJsonData(3, array(
            'ticket' =>  $ticket,
          )); //加入成功
        }
        $deleteRoomUser = $this->getGameService()->deleteRoomUser($beforeRoomID, $uid);
        if ($deleteRoomUser) {
          $addRoomUser = $this->getGameService()->addRoomUser($room_id, $uid);
          if($addRoomUser){
            if($isHost){
              $userlist = $this->getGameService()->getUserListByRoomId($beforeRoomID);
              if(!$userlist){
                //房间只有他本人,解散房间
                $this->getGameService()->updateRoomByRoomId($beforeRoomID,array('status' => 0));
                $isHost = 0;//不用发消息
              } else {
                //房间还有人,移交房主给房间的第一个
                $newHostUid = $userlist[0]['uid'];
                $newHostName = $userlist[0]['nickname'];
                $this->getGameService()->updateRoomByRoomId($beforeRoomID,array('host' => $newHostUid));
              }
            }
            $oldMsg = $this->sendUserList($uid, $beforeRoomID, 1, $isHost, $newHostName);//旧房间的用户消息
            $newMsg  = $this->sendUserList($uid, $room_id);//新房间的用户消息
            if ($newMsg && $oldMsg) {
              $list = array_merge($oldMsg, $newMsg);
            } else {
              $list = $newMsg;
            }
            $this->roomSendMsg($list);//发消息
            $this->outputJsonData(1); //加入成功
          }
        }
      }
      $this->outputJsonData(-5); //加入失败
    }
  }

  //二维码邀请页面
  public function actionInvite(){
    $room_id = $this->getSafeRequest('roomid', 0, 'GET', 'int');
    $this->title = "邀请码";
    if ($room_id) {
      $room = $this->getGameService()->getRoomById($room_id);//查询房间状态
      $hostUid = $room['host'];
      $userinfo = $this->getUserService()->getUserByUid($hostUid);
      $ticket = $this->getRoomTicket($room);
      $data = array();
      $weixinConfig = Yii::app()->params['weixinConfig']['wanZhuyule'];
      $data['weixinJssdkConfig'] = $this->getCommonService()->getJssdkConfigByAppid($weixinConfig['WEIXIN_APP_ID'], $weixinConfig['WEIXIN_APP_SECRET']);
      $data['shareTitle'] = str_replace('***', $this->currentUser['nickname'], self::$shareTitle);
      $data['sharelink'] = "http://wx.wanzhuwenhua.com/room/invite/{$room_id}.html";
      $data['qr_url'] = $ticket ? $ticket : '';
      $data['nickname'] = $userinfo['nickname']? $userinfo['nickname'] : '好友';
      $this->render('ewm',$data);
    }
  }

  //生成二维码
  private function getRoomTicket($room){
    if ($room && $room['ticket']) {
      return $room['ticket'];

    } else if ($room) {
      $wxConfig = Yii::app()->params['weixinConfig']['wanZhuyule'];
      $token = $this->getCommonService()->getWxAccesstoken($wxConfig['WEIXIN_APP_ID'], $wxConfig['WEIXIN_APP_SECRET']);
      if ($token) {
        $wxConfig['WEIXIN_ACCESS_TOKEN'] = $token;
        $this->getWeixinService()->setWeixinConfig($wxConfig);
        $invite = $this->getWeixinService()->getTmpQrcode(array(
          'expire_seconds' => 2592000,
          'scene_id' => $room['id'],
        ));
        $this->getGameService()->updateRoomByRoomId($room['id'], array('ticket' => $invite['qr_url']));
        $ticket = $invite['qr_url'];
      }
      return $ticket;
    }
    return "";
  }

  //拼接发送uid数组
  private  function sendUserList($uid, $room_id, $old = FALSE, $isHost = FALSE, $newHostName = ''){
    $uid = $this->currentUser['uid'];
    if ($room_id && $uid) {
      $userlist = $this->getGameService()->getUserListByRoomId($room_id);
      $nickname = $this->currentUser['nickname'];
      if ($userlist && $nickname) {
        $roomCount = count($userlist);
        $room = $this->getGameService()->getRoomByUid($uid);
        $players = SpyGame::getPlayerReplaceStr($userlist, $room);
        if ($isHost) {
          $hostlist = array();
          foreach ($userlist as $item) {
            if (!$item['openid']) continue;
            if ($isHost) {
              $typeMsg = 'exit_room_host_successed_others';
              $msgContent = GameMsg::getMsgContent($typeMsg, array(
                '%count%' => $roomCount,
                '%new%' => $nickname,
                '%new_host%'=> $newHostName,
                '%players%' => $players
              ));
            }
            $hostlist[] = array('openid' => $item['openid'], 'content' => $msgContent);
          }
        }
        $guestlist = array();
        foreach ($userlist as $item) {
          if (!$item['openid']) continue;

          if ($uid == $item['uid']){
            $typeMsg = ($roomCount < self::$minRoomNum) ? $typeMsg = 'join_room_successed_self_not_enough' : $typeMsg = 'join_room_successed_self_enough';
          } else {
            if ($roomCount < self::$minRoomNum) {
              $typeMsg = $old ? 'exit_room_successed_others_not_enough' : 'join_room_successed_not_enough';
            } else {
              $typeMsg = $old ? 'exit_room_successed_others_enough' : 'join_room_successed_enough';
            }
          }
          $msgContent = GameMsg::getMsgContent($typeMsg, array(
            '%count%' => $roomCount,
            '%new%' => $nickname,
            '%players%' => $players,
            '%room_id%' => $room_id
          ));
          $guestlist[] = array('openid' => $item['openid'], 'content' => $msgContent);
        }
        if ($hostlist && $guestlist) return array_merge($hostlist, $guestlist);
        return $guestlist ? $guestlist : array();
      }
    }
  }

  //发送消息（加入房间,离开房间）
  private function roomSendMsg($jobMsg){
    $this->getGearmanService()->addSendMsgJob($jobMsg);
  }
}
