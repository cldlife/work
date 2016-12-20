<?php
/**
 * @desc 我是卧底,30分钟解散房间,10秒系统投票,15秒未描述扣金币 (脚本)
 * @author chenlidong
 */
final class WodiGame extends BaseShell {

  // 游戏结果金币奖惩
  const SPY_GAME_LOSE_COIN_RULEID = 1;

  const SPY_WIN_COIN_RULEID = 2;

  const NORMAL_WIN_COIN_RULEID = 3;

  // 一次查询数据数量
  const TRANSFER_PER_DATANUM = 50;

  // 房间5分钟间隔,单位为秒
  const DISSOLVE_ROOM_TIME = 300;

  // 投票间隔,单位为秒
  const VOTE_TIME = 20;

  // 房主未描述,单位为秒
  const HOST_SPEAK_TIME = 60;

  // 未描述,单位为秒
  const SPEAK_TIME = 40;

  // 未描述,提示时间40/60 - 15s
  const SPEAK_NOTICE_TIME = 25;
  const SPEAK_HOST_NOTICE_TIME = 45;

  // 房间解散状态
  const UPDATE_ROOM_STATUS = 0;

  // 游戏类型(1=>我是卧底)
  const GAME_TYPE = 1;

  // 每个进程处理数据量上限（默认0：自动分配）
  private function generalPerProcessDataCount () {
    $this->dataCount = 0;
    $this->perProcessDataCount = intval(ceil($this->dataCount / $this->processNum));
  }

  public static function run ($processNum, $currentProcessId) {
    $self = new self();

    // 启动进程数
    $self->processNum = $processNum;
    // 当前进程ID
    $self->currentProcessId = $currentProcessId;
    // 自动分配每个进程处理数据量
    $self->generalPerProcessDataCount();

    $self->println("-------- Start, " . date(DATE_FORMAT) . " --------");
    $self->start();
    $self->println("-------- End, " . date(DATE_FORMAT) . " --------");
    unset($self);
  }

  // start
  private function start () {
    $this->debug = TRUE;
    $page = 1;
    $pageSize = self::TRANSFER_PER_DATANUM;
    $onlineRooms = 0;
    $playingRooms = 0;

    while (TRUE) {
      $this->println("\n- From page::{$page}::size::{$pageSize} -");

      $roomList = $this->getGameService()->getRoomList($page, $pageSize);
      if (!$roomList) {
        $page = 1;
        if ($onlineRooms) {
          $this->println("- Online {$onlineRooms} rooms, playing {$playingRooms} rooms -");
          $onlineRooms = 0;
          $playingRooms = 0;
        }
        $this->println("- sleep 3... -");
        sleep(3);
        continue;
      }

      foreach ($roomList as $room) {
        $onlineRooms++;//总在线房间数

        try {

          if ($room['status'] == 2) { //游戏等待中, 5分钟未开始就解散并发送消息
            if ($room['updated_time'] > time() - self::DISSOLVE_ROOM_TIME) continue;
            if (!$this->getGameService()->updateRoomByRoomId($room['id'], array('status' => 0))) continue;

            $userList = $this->getGameService()->getUserListByRoomId($room['id']);
            if (!$userList) continue;

            $list = array();
            foreach ($userList as $value) {
              if ($this->getGameService()->deleteRoomUser($room['id'], $value['uid'], $value['is_robot'])) $list[] = $value;
            }
            GameMsg::sendAsyncMsg($list, GameMsg::getMsgContent('dissolve_room'));
            unset($userList, $list);

          } else if ($room['status'] == 1) { //游戏进行中
            $playingRooms++; //游戏中房间数

            $game = $this->getGameService()->getGameById($room['game_id']);
            $userList = $this->getGameService()->getUserListByRoomId($room['id']);
            if (!$userList) continue;

            $now = time();
            $latestState = $this->getGameService()->getGameLatestStateByGameId($room['game_id']);
            $spy = new SpyGame($room, $game, $userList[0], $latestState);

            if (!$latestState || $latestState['state']['next'] == 'speak') {
              //60,30s未描述
              if ($latestState) {
                $latestGameTime = $latestState['created_time'];
                $speakTime = self::SPEAK_TIME;
                $noticeTime = self::SPEAK_NOTICE_TIME;

              } else {
                $latestGameTime = $game['created_time'];
                $speakTime = self::HOST_SPEAK_TIME;
                $noticeTime = self::SPEAK_HOST_NOTICE_TIME;
              }
              $diffTime = $now - $latestGameTime;

              $currentPlayer = $spy->getCurrentPlayer();
              $cacheKey = "MSG_SPEAK_NOTICE_{$room['id']}_{$currentPlayer['uid']}";
              $spy->setCurrentUser($currentPlayer);
              if ($diffTime > $speakTime) { //未描述
                if ($spy->play('未描述,跳过', TRUE)) break;

              } else if ($diffTime > $noticeTime && (!$this->getCommonService()->getFromMemcache($cacheKey))) { //15s
                GameMsg::sendAsyncMsg(array($currentPlayer), GameMsg::getMsgContent('msg_speak_notice'));
                //15s提醒发言只提醒1次
                $this->getCommonService()->setToMemcache($cacheKey, TRUE, 20);
              }
              unset($latestState, $currentPlayer, $cacheKey);

            } else if ($latestState['state']['next'] == 'vote' && ($now - $latestState['created_time']) > self::VOTE_TIME) {
              //15s未投票
              $totalPlayers = $spy->getTotalPlayers();
              if (!$latestState['state']['voters']) $latestState['state']['voters'] = $latestState['state']['players'];
              $i = 0;
              foreach ($latestState['state']['voters'] as $num => $uid) {
                $spy->setCurrentUser($totalPlayers[$uid]);
                if ($i > 0) $spy->processGameState();
                $spy->play($spy->getVotedUnum(), TRUE);
                $i++;
              }
              unset($latestState, $totalPlayers, $i);

            } else if ($latestState['state']['next'] == 'sum' && ($now - $latestState['created_time']) > self::VOTE_TIME) {
              //至少超出15s,说明游戏出现问题了
              $spy->play('sum votes', TRUE);

            } else if ($latestState['state']['next'] == 'end') {
              //游戏已经结束,status却没有设置
              $spy->play('set status = 0', TRUE);
            }
            unset($spy);
          }
        } catch (Exception $e) {
          $this->println("- ERROR ::{$e->getMessage()}:: - ");
        }
      }
      unset($roomList);
      $page++;
    }
  }
}

