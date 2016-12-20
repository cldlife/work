<?php
/**
 * @desc 融云路由消息回调处理
 * @see 融云内置消息中的extra（附加信息）约束定义（JSON格式）:
 * @param int roomid 游戏房间id
 * @param string type 消息类型，room-房间消息，game-游戏消息
 * @param string action 执行动作，join-加入房间
 * @param json content 详细信息
 * @param string v 版本号
 * @example
 *{"roomid":1688,"type":"room","action":"ready","content":{},"v":"1.2.0"}
 */
class RoutingController extends BaseController {

  /**
   * @desc main entry
   * @see it can not run BaseController filters
   */
  public function run ($actionId) {
    /**
     * 融云用户离线订阅
     * $os = $this->getSafeRequest('os', '', 'string');
     */
    $uid = self::trimRcUseridPrefix($this->getSafeRequest('userid'));
    $status = $this->getSafeRequest('status', NULL, 'int');
    $time = $this->getSafeRequest('time', '', 'string');

    /**
     * 融云路由
     * $channelType = $this->getSafeRequest('channelType', '', 'POST');
     * $toUserId = $this->getSafeRequest('toUserId', 0, 'POST', 'int');
     * $msgTimestamp = $this->getSafeRequest('msgTimestamp', '', 'POST');
     * $timestampPost = $this->getSafeRequest('timestamp', '', 'POST');
     * $objectName = $this->getSafeRequest('objectName', '', 'POST');
     * $msgUID = $this->getSafeRequest('msgUID', '', 'POST');
     */
    $fromUserId = $this->trimRcUseridPrefix($this->getSafeRequest('fromUserId'));
    $content = $this->getSafeRequest('content', array(), 'json');
    $extra = $content ? json_decode(trim($content['extra']), TRUE) : array();

    //get params
    $appKey = $this->safeRequestGetVal('appKey', '');
    $nonce = $this->safeRequestGetVal('nonce', '');
    $timestampGet = $this->safeRequestGetVal('timestamp', '');
    $signature = $this->safeRequestGetVal('signature', '');

    try {
      if (!$appKey || !$nonce || !$timestampGet || !$signature) {
        throw new Exception('appkey, nonce, timestamp or signature is null');
      } else if ($signature != $this->getRongCloudService()->signServerApi($nonce, $timestampGet)) {
        throw new Exception("signature is not valid, sig:{$signature},nonce:{$nonce},timestamp:{$timestampGet}");
      }

      //处理房间消息
      if ($fromUserId && $content && $extra) {
        try {

          $this->currentUser = $this->getUserService()->getUserByUid($fromUserId);
          if (!$this->currentUser) throw new Exception("current user is null, fromUserId:{$fromUserId}");

          $method = 'do' . strtoupper($extra['type']) . 'Msg';
          if (method_exists($this, $method)) {
            $this->$method($extra);
          } else {
            throw new Exception("RoutingController {$method} is no exist");
          }
        } catch (Exception $e) {
          throw new Exception("Routing:: {$e->getMessage()}:: content:{$content['content']}; extra:" . json_encode($extra));
        }

      //处理离线消息
      } else {
        $rawOfflineUsers = file_get_contents('php://input');
        $offlineUsers = $rawOfflineUsers ? json_decode($rawOfflineUsers, TRUE) : array();
        if (!$offlineUsers) throw new Exception("offline users is null, raw body:{$rawOfflineUsers}");

        $this->doUserOffline($offlineUsers);
      }

    } catch (Exception $e) {
      Utils::log("{$e->getMessage()}", 'rcloud_error');
    }
  }

  /**
   * @desc 用户离线订阅通知
   *   userid String 用户Id
   *   status 0-在线, 1-离线, 2-logout
   *   os string 操作系统:iOS,Android,Websocket
   *   time
   * @param int $uid
   * @return null
   */
  private function doUserOffline ($offlineUsers) {
    foreach ($offlineUsers as $offline) {
      try {
        if (!$offline['status']) {
          throw new Exception("user is backing online, not care");
        }

        $uid = (int) $this->trimRcUseridPrefix($offline['userid']);
        if (!$uid) throw new Exception("uid is null");

        $userInfo = $this->getUserService()->getUserByUid($uid);
        if (!$userInfo) throw new Exception("user info is null");

        $room = $this->getGameService()->getRoomByUid($uid);
        if (!$room) throw new Exception("user is not in a room");

        $roomUser = $this->getGameService()->getRoomUserByRoomidAndUid($room['id'], $uid);
        if (!$roomUser) throw new Exception("room user not exist, room_id:{$room['id']}");

        $action = '';
        //房间未开始游戏
        if ($room['status'] == 2) {
          if (!$this->getGameService()->deleteRoomUser($room['id'], $uid)) {
            throw new Exception("delete room user failed, room_id:{$room['id']}");
          }

          $action = 'quit';

        //房间正在游戏中
        } else if ($room['status'] == 1)  {
          if ($roomUser['status'] == 0)
            throw new Exception("room user status already offline, room_id:{$room['id']}");

          if (!$this->getGameService()->updateRoomUserByRidAndUid($room['id'], $uid,
            array('status' => 0)
          )) {
            throw new Exception("update room user status offline failed, room_id:{$room['id']}");
          }

          $action = 'offline';
          AppSpyGame::addRobotTask('auto_play', $room['game_id'], $uid);

        //房间已解散
        } else if ($room['status'] == 0)  {
          $this->getGameService()->deleteRoomUser($room['id'], $uid);
        }

        if ($action) {
          $gameMsg = array(
            'roomid' => $room['id'],
            'type' => 'room',
            'action' => $action,
          );
          AppSpyGame::sendRcGameMsg($gameMsg, $userInfo);
        }
      } catch (Exception $e) {
        Utils::log("Offline:: {$e->getMessage()}, offline user:" . json_encode($offline), 'rcloud_error');
      }
    }
  }

  /**
   * @desc 房间消息处理(接口修改, 不进行任何操作)
   * @param string $content
   * @param array $extra
   * @return null
   */
  private function doRoomMsg ($extra) {
    return NULL;
  }

  /**
   * @desc 游戏消息处理
   * @param string $content
   * @param array $extra
   * @return null
   */
  private function doGameMsg ($extra) {
    if ($extra['roomid']) {
      $room = $this->getGameService()->getRoomById($extra['roomid']);
      if (!$room || !$room['game_id']) throw new Exception('room or game id is null');
      $game = $this->getGameService()->getGameById($room['game_id']);
      if (!$game || !$game['status']) throw new Exception('game is null or game is over');

      if ($extra['action'] == 'desc') {
        $content = $extra['content']['word'] ?: "未投票, 跳过";
      } else if ($extra['action'] == 'vote') {
        $content = $this->trimRcUseridPrefix($extra['content']['uid']);
      }
      $game = new AppSpyGame($game, $this->currentUser);
      $game->play($content);
    } else {
      throw new Exception("room id is null");
    }
  }

  /**
   * @desc 去除uid带着的融云前缀
   * @param string $rongCloudPrefixUid
   * @return int $uid
   */
  private function trimRcUseridPrefix ($uid) {
    $rcs = $this->getRongCloudService();
    $rcsUseridPrefix = $rcs::RONGCLOUD_USERID_PREFIX ?: RONGCLOUD_USERID_PREFIX;
    return ($uid && $rcsUseridPrefix) ? (int) str_replace($rcsUseridPrefix, '', $uid) : 0;
  }

  /**
   * @desc 获取$_GET参数
   * @param string $name
   * @param mixed $defaultValue
   * @return mixed
   */
  private function safeRequestGetVal ($name, $defaultValue = NULL) {
    if ($name && isset($_GET[$name])) {
      return (trim($_GET[$name])) ? Utils::filterString($_GET[$name]) : $defaultValue;
    }
    return $defaultValue;
  }
}
