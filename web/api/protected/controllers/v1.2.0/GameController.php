<?php
class GameController extends BaseController {

  //测试用的房间id
  const TEST_CHAT_ROOM_ID = 1688;

  //退出游戏需要的金币
  const QUIT_GAME_COINS = 10;

  //房间空闲状态
  const ROOM_IDLE_STATUS = 2;

  //谁是卧底gid
  const SPY_GAME_GID = 4;

  //游戏中退出房间扣除金币
  const FORCE_QUIT_COIN_RULEID = 31;

  /**
   * @desc 游戏房间信息
   */
  public function actionInfo () {
    $gid = $this->getSafeRequest('gid', 0, 'int');
    if (!$gid) $this->outputJsonData(1020);

    /********* TODO test code *********/
    if ($gid == 5) {
      $data = array('info' => array(
        'roomid' => self::TEST_CHAT_ROOM_ID,
        'name' => '你画我猜',
        'play_permission' => 1,
        'quit_coins' => self::QUIT_GAME_COINS,
        'im_config' => array('YuYin' => 1),
      ));
      $this->outputJsonData(0, $data);
    }
    /********* test code *********/

    $roomId = $this->getGameService()->getRoomIdByUid($this->currentUser['uid']);
    $room = $roomId ? $this->getGameService()->getRoomById($this->currentUser['uid']) : array();
    if ($room) {
      $this->getGameService()->deleteRoomUser($room['id'], $this->currentUser['uid']);
      unset($room);
    }

    $roomId = 0;
    $tmpRoom = array();
    $roomList = $this->getGameService()->getRoomList(1, 20, $gid);
    if ($roomList) {
      foreach ($roomList as $room) {
        if ($room['status'] < self::ROOM_IDLE_STATUS) continue;
        if ($room['players'] < AppSpyGame::JOIN_ROOM_MAX_NUM && $room['players'] > $tmpRoom['players']) $tmpRoom = $room;
      }
    }
    $roomId = $tmpRoom ? $tmpRoom['id'] : $this->getGameService()->addRoom(self::SPY_GAME_GID);
    if (!$roomId) $this->outputJsonData(1021);

    $response = array('info' => array(
      'roomid' => $roomId,
      'name' => '谁是卧底',
      //'play_permission' => ($this->currentUser['status']['coins'] < self::QUIT_GAME_COINS) ? 0 : 1,
      'play_permission' => 1,
      'quit_coins' => self::QUIT_GAME_COINS,
      'im_config' => array('YuYin' => 1),
    ));
    $this->outputJsonData(0, $response);
  }

  private static $genderNameMap = array('男' => '帅哥', '女' => '美女', '保密' => '');
  /**
   * @desc 游戏房间信息
   * @see 将用户加入到房间，同时sendRcChatRoomGameMessage已加入房间
   */
  public function actionSyncRoom () {
    $roomId = $this->getSafeRequest('roomid', 0, 'int');
    if (!$roomId) $this->outputJsonData(1022);

    /********* TODO test code *********/
    if ($roomId == self::TEST_CHAT_ROOM_ID) {
      $data = array(
        'room_users' => array(
          array(
            'uid' => '1', 'nickname' => '不清楚你是谁', 'is_readied' => 1,
            'avatar' => 'http://s.wanzhucdn.com/avatar/1/1949/64f0f8022896b7868885ce3108764912/200'
          ),
          array(
            'uid' => '2', 'nickname' => 'Miss、木', 'is_readied' => 1,
            'avatar' => 'http://s.wanzhucdn.com/avatar/1/1949/57248b679096a039bd9c41fca833663c/200'
          ),
          array(
            'uid' => '3', 'nickname' => '翻个球，消失的兔菲菲', 'is_readied' => 1,
            'avatar' => 'http://s.wanzhucdn.com/avatar/1/1948/97eab34f700e31eb950ba0cf31345af3/200'
          ),
          array(
            'uid' => '4', 'nickname' => '呵呵呵', 'is_readied' => 1,
            'avatar' => 'http://f1.shiyi11.com/avatar/1/1999/b9edeb00a230f6ab7995cafca06353fa/200'
          ),
        ),
      );
      $this->outputJsonData(0, $data);
    }
    /********* test code *********/

    $prevRoomId = $this->getGameService()->getRoomIdByUid($this->currentUser['uid']);
    $room = $prevRoomId ? $this->getGameService()->getRoomById($prevRoomId) : array();
    if ($room) {
      $this->getGameService()->deleteRoomUser($room['id'], $this->currentUser['uid']);
      unset($room);
    }

    $room = $this->getGameService()->getRoomById($roomId);
    if (!$room || $room['status'] < 2) $this->outputJsonData(1023);

    if (!$this->getGameService()->addRoomUser($roomId, $this->currentUser['uid'])) {
      $this->outputJsonData(1024);
    }

    $roomUsers = $this->getGameService()->getUserListByRoomId($roomId, TRUE);
    if (!$roomUsers) $this->outputJsonData(1025);

    $gameMsg = array(
      'roomid' => $roomId,
      'type' => 'room',
      'action' => 'join',
    );
    AppSpyGame::sendRcGameMsg($gameMsg, $this->currentUser);
    $region = trim($this->currentUser['region']['name'] ?: '');
    $replace = array(
      '%player%' => $this->currentUser['nickname'],
      '%region%' => $region ? $region . '的' : '',
      '%sex%' => self::$genderNameMap[$this->currentUser['gender_name']],
    );
    AppSpyGame::sendRcChatRoomMsg(array($roomId), 'game_spy_join', $replace);

    //检查房间当前玩家状态
    AppSpyGame::addRobotTask('app_check', $roomId);

    $response = array('status' => 0);
    $room_users = array();
    foreach ($roomUsers as $user) {
      if (!$user['status']) {
        $this->getGameService()->deleteRoomUser($roomId, $user['uid']);
        continue;
      }
      if ($user['uid'] == $this->currentUser['uid']) $response['status'] = 1;
      $room_users[] = array(
        'uid' => $user['uid'],
        'nickname' => $user['nickname'],
        'avatar' => $user['avatar'],
        'is_readied' => ($user['status'] == 2) ? 1 : 0,
      );
    }
    $response['room_users'] = $room_users;
    $this->outputJsonData(0, $response);
  }

  /**
   * @desc 退出游戏房间
   * @param int roomid 游戏房间id
   * @param int type 类型：0-游戏未开始，1-游戏中（扣金币提示）
   */
  public function actionQuitRoom () {
    $roomId = $this->getSafeRequest('roomid', 0, 'int');
    $type = $this->getSafeRequest('type', 0, 'int');
    if (!$roomId) $this->outputJsonData(1022);

    $room = $this->getGameService()->getRoomById($roomId);
    if (!$room || !$room['status']) $this->outputJsonData(1034);

    //if (($type == 1 && $room['status'] != 1) || ($type == 0 && $room['status'] != 2)) {
    //  //room status 和type 互相矛盾
    //  $this->outputJsonData(1026);
    //}

    if ($type) {
      $result = $this->getGameService()->updateRoomUserByRidAndUid($roomId, $this->currentUser['uid'], array('status' => 0));
      if ($result) {
        $this->getUserFortuneService()->autoUserFortuneCoin($this->currentUser['uid'], self::FORCE_QUIT_COIN_RULEID);
      }
    } else {
      $result = $this->getGameService()->deleteRoomUser($roomId, $this->currentUser['uid']);
    }
    if (!$result) $this->outputJsonData(1027);

    //检查房间当前玩家状态
    AppSpyGame::addRobotTask('app_check', $roomId);

    $gameMsg = array(
      'roomid' => $roomId,
      'type' => 'room',
      'action' => ($type) ? 'offline' : 'quit',
      'content' => (object) array(),
      'version' => '1.2.0',
    );
    AppSpyGame::sendRcGameMsg($gameMsg, $this->currentUser);
    $this->outputJsonData(0);
  }

  /**
   * @desc 准备开始游戏
   */
  public function actionReadyRoom () {
    $roomId = $this->getSafeRequest('roomid', 0, 'int');
    if (!$roomId) $this->outputJsonData(1022);

    $room = $this->getGameService()->getRoomById($roomId);
    if (!$room || ($room && $room['status'] == 1)) {
      //房间已经解散或正在游戏中
      $this->outputJsonData(1023);
    }

    $roomUser = $this->getGameService()->getRoomUserByRoomidAndUid($roomId, $this->currentUser['uid']);
    if (!$roomUser) $this->outputJsonData(1035);

    if ($roomUser['status'] == 2) {
      $result = TRUE;
    } else {
      $result = $this->getGameService()->updateRoomUserByRidAndUid($roomId, $this->currentUser['uid'], array('status' => 2));
    }
    if (!$result) $this->outputJsonData(1025);

    $userList = $this->getGameService()->getUserListByRoomId($roomId, TRUE);
    if (!$userList) $this->outputJsonData(1025);

    $gameMsg = array(
      'roomid' => $roomId,
      'type' => 'room',
      'action' => 'ready',
      'content' => (object) array(),
      'version' => '1.2.0',
    );
    AppSpyGame::sendRcGameMsg($gameMsg, $this->currentUser);

    $readiedCount = AppSpyGame::arePlayersReady($userList);
    //6人
    if ($readiedCount >= AppSpyGame::MAX_PLAYERS_READY_NUM) {
      AppSpyGame::addRobotTask('start_now', $roomId);

    //4,5人
    } else if ($readiedCount >= AppSpyGame::MIN_PLAYERS_READY_NUM) {
      if ($readiedCount > AppSpyGame::MIN_PLAYERS_READY_NUM) {
        $this->getCommonService()->setToMemcache("delay_start_game_{$roomId}", TRUE, 5);
      } else {
        AppSpyGame::sendRcChatRoomMsg(array($roomId), 'game_spy_readied');
      }
      AppSpyGame::addRobotTask('start_later', $roomId);

    }

    $this->outputJsonData(0);
  }
}

