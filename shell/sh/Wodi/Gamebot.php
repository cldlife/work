<?php
/**
 * @desc 机器人脚本
 */
final class Gamebot extends BaseShell {

  // 一次查询数据数量
  const TRANSFER_PER_DATANUM = 50;

  //任务成功状态
  const TASK_SUCCESSED_STATUS = 1;
  //任务失败状态
  const TASK_FALIED_STATUS = 2;

  //游戏开始要求最低人数
  const MIN_ROOM_PLAYERS = 4;

  // 房间解散状态
  const UPDATE_ROOM_STATUS = 0;

  //房间正在等待'开始游戏'的状态
  const ROOM_WAITING_STATUS = 2;

  private static $WEIXIN_CONFIG = array(
    'WEIXIN_APP_ID' => 'wx318680eae930969f',
    'WEIXIN_APP_SECRET' => '5961e808c2339acc0aecd53802798f3c',
    'WEIXIN_AES_KEY' => 'CIwJbp3HMD9Vx7tfRJ9YByKqNFl3chiiMkepAuvb9Jh',
    'WEIXIN_ACCOUNT_NAME' => 'gh_85b2af0e4fb4',
    'WEIXIN_SERVER_TOKEN' => 'HELLOQUANWAI',
  );

  //通过self instance去调用WeixinService/WxpayService等
  private static $instance = null;
  private static function getSelfInstance () {
    if (!self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private static $genderNameMap = array('男' => '帅哥', '女' => '美女', '保密' => '');

  private function start () {
    $this->debug = TRUE;
    $page = 1;
    $todoTask = 0;
    $taskDone = 0;
    $taskFailed = 0;

    $pageSize = 20;
    while (TRUE) {
      $this->println("\n- From page::{$page}::size::{$pageSize} -");

      $taskList = self::getTaskService()->getTaskList($page, self::TRANSFER_PER_DATANUM);
      if (!$taskList) {
        if ($todoTask) {
          $this->println("- todo {$todoTask}, completed {$taskDone}, failed {$taskFailed}, tasks -");
          $todoTask = 0;
          $taskDone = 0;
          $taskFailed = 0;
        }
        $page = 1;
        $this->println("- sleep 3... -");
        sleep(3);
        continue;
      }

      foreach ($taskList as $task) {
        if (!$task['workload'])
          throw new Exception("task workload is null");

        $now = time();
        $todoTime = $task['run_time'] - $now;
        if ($todoTime > 1) {
          $this->println("- undo tasks, not running time now, sleep 2... -");
          sleep(2);
          break;
        }

        //执行任务总数
        $todoTask++;
        try {
          switch ($task['type']) {
          case 'send_wx_msg': //定时Gearman发送消息
            if (!$this->getGearmanService()->addSendMsgJob($task['workload'])) {
              throw new Exception('send wx msg task failed');
            }
            break;

          case 'join_room': //定时加入房间
            if (!$task['workload']['room_id'])
              throw new Exception("room id is null");

            $room = $this->getGameService()->getRoomById($task['workload']['room_id']);
            if (!$room || $room['status'] != self::ROOM_WAITING_STATUS)
              throw new Exception("room is null or room is dismissed or gaming");

            $users = $this->getGameService()->getUserListByRoomId($task['workload']['room_id']);
            $userCount = count($users);
            if ($userCount <= 0 || $userCount >= self::MIN_ROOM_PLAYERS)
              throw new Exception("room user is null or enough");

            $pseudoUser = $this->getGameService()->getLatestPseudoUser();
            if (!$pseudoUser)
              throw new Exception("no pseudo user to use");

            if (!$this->getGameService()->addRoomUser($task['workload']['room_id'], $pseudoUser['uid'], TRUE))
              throw new Exception("add new user failed");

            //机器人加入房间,发送消息
            $users[] = $pseudoUser;
            $userCount += 1;
            $msgKey = ($userCount < self::MIN_ROOM_PLAYERS) ? 'join_room_successed_not_enough' : 'join_room_successed_enough';
            $replace = array(
              '%new%' => $pseudoUser['nickname'],
              '%players%' => SpyGame::getPlayerReplaceStr($users, $room),
              '%count%' => $userCount,
              '%room_id%' => $task['workload']['room_id'],
            );
            GameMsg::sendAsyncMsg($users, GameMsg::getMsgContent($msgKey, $replace));

            //房间人数不够,继续加入房间,否则机器人发消息提示房主开始游戏
            if ($userCount < self::MIN_ROOM_PLAYERS) {
              SpyGame::addRobotTask('join', $task['workload']['room_id']);
            } else {
              SpyGame::addRobotTask('chat_start', $task['workload']['room_id']);
            }

            break;

          case 'robot_chat': //定时发送消息给房间的其他玩家
            if (!$task['workload']['room_id'])
              throw new Exception("room id is null");

            $room = $this->getGameService()->getRoomById($task['workload']['room_id']);
            if (!$room || $room['status'] != self::ROOM_WAITING_STATUS)
              throw new Exception("room is null or room is dismissed or gaming");

            $users = $this->getGameService()->getUserListByRoomId($task['workload']['room_id']);
            $robot = SpyGame::getRobotsFromUsers($users);
            if (!$robot)
              throw new Exception("room has no robot");

            $robot = $robot[mt_rand(0, count($robot) - 1)];
            $replace = array(
              '%player%' => $robot['nickname'],
              '%content%' => GameMsg::getRobotChatContent(),
            );
            GameMsg::sendAsyncMsg($users, GameMsg::getMsgContent('msg_players_chat', $replace));

            break;

          case 'robot_speak': //机器人游戏中"发言"
            if (!$task['workload']['game_id'] || !$task['workload']['uid'])
              throw new Exception("game_id or uid is null");

            $game = $this->getGameService()->getGameById($task['workload']['game_id']);
            $room = $this->getGameService()->getRoomById($game['room_id']);
            $currentUser = $this->getGameService()->getPseudoUserByUid($task['workload']['uid']);
            $response = $this->getGameService()->getGamesetNonRepeatResponse($game['info']['words']['words_id'], $game['id']);

            if (!$game || !$room || !$currentUser || !$response)
              throw new Exception("room, game, currentUser or responses is null");

            $curGame = new SpyGame($room, $game, $currentUser);
            $curGame->play($response);

            break;

          case 'robot_vote': //机器人游戏中"投票"
            if (!$task['workload']['game_id'] || !$task['workload']['uid'])
              throw new Exception("game_id or uid is null");

            $game = $this->getGameService()->getGameById($task['workload']['game_id']);
            $room = $game['room_id'] ? $this->getGameService()->getRoomById($game['room_id']) : array();
            $robot = $this->getGameService()->getPseudoUserByUid($task['workload']['uid']);

            if (!$game || !$room || !$robot)
              throw new Exception("room, game or robot is null");

            $curGame = new SpyGame($room, $game, $robot);
            $curGame->play($curGame->getVotedUnum());

            break;

          case 'robot_punish': //机器人在游戏结束后"接受惩罚"
            if (!$task['workload']['punish_id'] || !$task['workload']['uid'])
              throw new Exception("game_id or uid is null");

            $roomId = $this->getGameService()->getRoomIdByUid($task['workload']['uid']);
            $room = $roomId ? $this->getGameService()->getRoomById($roomId) : array();
            if (!$room || $room['status'] != self::ROOM_WAITING_STATUS)
              throw new Exception("room is null or room is dismissed or gaming");

            $users = $this->getGameService()->getUserListByRoomId($room['id']);
            $pseudoUser = $this->getGameService()->getPseudoUserByUid($task['workload']['uid']);
            $punish = $this->getGameService()->getGamesetNonRepeatResponse($task['workload']['punish_id'], $room['id']);

            if (!$users || !$pseudoUser || !$punish)
              throw new Exception("users, pseudo user or punish is null");

            $replace = array(
              '%player%' => $pseudoUser['nickname'],
              '%content%' => $punish,
            );
            GameMsg::sendAsyncMsg($users, GameMsg::getMsgContent('msg_players_chat', $replace));

            break;

          case 'robot_release': //释放"正在使用"的机器人
            if (!$task['workload']['pseudo_uid'])
              throw new Exception("pseudo_uid is null");

            $roomId = $this->getGameService()->getRoomIdByUid($task['workload']['pseudo_uid']);
            $room = $roomId ? $this->getGameService()->getRoomById($roomId) : array();
            if ($room && $room['status'] != self::UPDATE_ROOM_STATUS) {
              SpyGame::addRobotTask('release', $task['workload']['pseudo_uid']);
              throw new Exception("pseudouser is in a gameing or waiting room");
            }

            if (!$this->getGameService()->updatePseudoUserByUid($task['workload']['pseudo_uid'], array(
              'is_using' => 0
            )))
              throw new Exception('update pseudouser is_using failed');

            break;

          case 'send_rc_game_msg': //发送融云游戏消息
          case 'send_rc_chat_msg': //发送融云聊天室消息
            $method = ($task['type'] == 'send_rc_game_msg') ? 'addRcGameMsg' : 'addRcChatRoomMsg';
            if (!$this->getGearmanService()->$method($task['workload'])) {
              throw new Exception('send rc msg failed');
            }
            break;

          case 'app_robot_join': //app机器人加入房间
            if (!$task['workload']['room_id'])
              throw new Exception("room id is null");

            $room = $this->getGameService()->getRoomById($task['workload']['room_id']);
            if (!$room || $room['status'] != self::ROOM_WAITING_STATUS)
              throw new Exception("room is null or room is dismissed or gaming");

            $playersList = $this->getGameService()->getUserListByRoomId($task['workload']['room_id'], TRUE);
            $playersCount = count($playersList);
            if ($playersCount <= 0 || $playersCount >= AppSpyGame::JOIN_ROOM_MAX_NUM)
              throw new Exception("room user is null or enough");

            $readiedCount = AppSpyGame::arePlayersReady($playersList);
            if ($readiedCount >= AppSpyGame::MAX_PLAYERS_READY_NUM) {
              throw new Exception('room readied user is enough');
            }

            $pseudoUser = $this->getGameService()->getLatestPseudoUser();
            if (!$pseudoUser) throw new Exception("no pseudo user to use");

            $prevRoomId = $this->getGameService()->getRoomIdByUid($pseudoUser['uid']);
            //最后一个参数为false
            if ($prevRoomId) $this->getGameService()->deleteRoomUser($prevRoomId, $pseudoUser['uid'], FALSE);

            if (!$this->getGameService()->addRoomUser($room['id'], $pseudoUser['uid'], TRUE))
              throw new Exception("add new user failed");

            AppSpyGame::addRobotTask('app_ready', $room['id'], $pseudoUser['uid']);

            $gameMsg = array(
              'roomid' => $room['id'],
              'type' => 'room',
              'action' => 'join',
            );
            AppSpyGame::sendRcGameMsg($gameMsg, $pseudoUser);
            $replace = array(
              '%player%' => $pseudoUser['nickname'],
              '%region%' => '',
              '%sex%' => self::$genderNameMap[$pseudoUser['gender_name']],
            );
            AppSpyGame::sendRcChatRoomMsg(array($room['id']), 'game_spy_join', $replace);
            $playersCountPP = count($playersList) + 2;
            if ($playersCountPP == AppSpyGame::MAX_PLAYERS_READY_NUM) {
              $this->getCommonService()->setToMemcache("delay_start_game_{$room['id']}", TRUE, 5);
            }

            break;

          case 'app_robot_ready': //机器人已准备
            if (!$task['workload']['room_id'] || !$task['workload']['uid'])
              throw new Exception("room id or uid is null");

            $roomId = $this->getGameService()->getRoomIdByUid($task['workload']['uid']);
            $room = $roomId ? $this->getGameService()->getRoomById($roomId) : array();
            if (!$room || $room['status'] != self::ROOM_WAITING_STATUS)
              throw new Exception("room user is null or room is dismissed or gaming");

            $pseudoUser = $this->getGameService()->getPseudoUserByUid($task['workload']['uid']);
            if (!$pseudoUser) throw new Exception('pseudo user is null');

            $playersList = $this->getGameService()->getUserListByRoomId($room['id'], TRUE);
            if (!$playersList) throw new Exception('room players is null');

            $readiedCount = AppSpyGame::arePlayersReady($playersList);
            if ($readiedCount >= AppSpyGame::MAX_PLAYERS_READY_NUM) {
              throw new Exception('room readied user is enough');
            }

            if (!$this->getGameService()->updateRoomUserByRidAndUid($room['id'], $task['workload']['uid'],
              array('status' => 2)
            )) {
              throw new Exception('update robot ready failed');
            }
            $gameMsg = array(
              'roomid' => $room['id'],
              'type' => 'room',
              'action' => 'ready',
            );
            AppSpyGame::sendRcGameMsg($gameMsg, $pseudoUser);
            ++ $readiedCount;
            if ($readiedCount >=  AppSpyGame::MAX_PLAYERS_READY_NUM) {
              AppSpyGame::addRobotTask('start_now', $room['id']);
            } else if ($readiedCount >=  AppSpyGame::MIN_PLAYERS_READY_NUM) {
              if ($readiedCount == AppSpyGame::MIN_PLAYERS_READY_NUM) {
                AppSpyGame::sendRcChatRoomMsg(array($room['id']), 'game_spy_readied');
              }
              AppSpyGame::addRobotTask('start_later', $room['id']);
            }
            if ($readiedCount < AppSpyGame::MAX_PLAYERS_READY_NUM) {
              AppSpyGame::addRobotTask('app_check', $room['id']);
            }

            break;

          case 'app_robot_exit': //机器人退出房间
            if (!$task['workload']['room_id'] || !$task['workload']['uid'])
              throw new Exception("room id or uid is null");

            $roomId = $this->getGameService()->getRoomIdByUid($task['workload']['uid']);
            $room = $roomId ? $this->getGameService()->getRoomById($roomId) : array();
            if (!$room || $room['status'] != self::ROOM_WAITING_STATUS)
              throw new Exception("room user is null or room is dismissed or gaming");
            if ($task['workload']['room_id'] != $room['id'])
              throw new Exception('not at same room, task runing too late');

            $pseudoUser = $this->getGameService()->getPseudoUserByUid($task['workload']['uid']);
            if (!$pseudoUser) throw new Exception('pseudo user is null');

            if (!$this->getGameService()->deleteRoomUser($room['id'], $pseudoUser['uid'], TRUE)) {
              throw new Exception('robot exit room failed ');
            }
            //设置延时, 防止开始游戏再接收到消息
            $this->getCommonService()->setToMemcache("delay_start_game_{$room['id']}", TRUE, 3);
            $gameMsg = array(
              'roomid' => $room['id'],
              'type' => 'room',
              'action' => 'quit',
            );
            AppSpyGame::sendRcGameMsg($gameMsg, $pseudoUser);

            break;

          case 'app_robot_check':
            if (!$task['workload']['room_id'])
              throw new Exception("room id or uid is null");

            $room = $this->getGameService()->getRoomById($task['workload']['room_id']);
            if (!$room)
              throw new Exception("room user is null");

            $playersList = $this->getGameService()->getUserListByRoomId($room['id'], TRUE);
            if (!$playersList) throw new Exception('room players is null');

            AppSpyGame::checkRoomPlayers($room['id']);

            break;

          case 'app_game_start': //开始游戏
            if (!$task['workload']['room_id'])
              throw new Exception("room id is null");

            $room = $this->getGameService()->getRoomById($task['workload']['room_id']);
            if (!$room || $room['status'] != self::ROOM_WAITING_STATUS)
              throw new Exception("room is null or room is dismissed or gaming");

            $userList = $this->getGameService()->getUserListByRoomId($room['id']);
            if (!$userList) throw new Exception('room user is null');

            $key = "delay_start_game_{$room['id']}";
            if ($this->getCommonService()->getFromMemcache($key)) {
              $readiedCount = AppSpyGame::arePlayersReady($userList);
              if ($readiedCount >= AppSpyGame::MAX_PLAYERS_READY_NUM) {
                AppSpyGame::addRobotTask('start_now', $room['id']);
              } else if ($readiedCount >= AppSpyGame::MIN_PLAYERS_READY_NUM) {
                AppSpyGame::addRobotTask('start_later', $room['id']);
              }
              $this->getCommonService()->deleteFromMemcache($key);
              throw new Exception('start game delayed');
            }

            AppSpyGame::initGameStart($room['id']);

            break;

          case 'auto_play_offline':
            if (!$task['workload']['game_id'] || !$task['workload']['uid'])
              throw new Exception("game_id or uid is null");

            $game = $this->getGameService()->getGameById($task['workload']['game_id']);
            if (!$game || !$game['status']) throw new Exception("game is null or is ended");
            if ($game['type'] != AppSpyGame::APP_SPYGAME_TYPE) throw new Exception('not app spy game');
            if ($now - $game['created_time'] > 600) {
              AppSpyGame::cleanUpGame($game['id']);
              throw new Exception("this game is not properly overed");
            }

            $userInfo = $this->getUserService()->getUserByUid($task['workload']['uid']);
            if (!$userInfo) throw new Exception('user info is null');

            $latestState = $this->getGameService()->getGameLatestStateByGameId($game['id']);
            if (!$latestState || $latestState['state']['next'] == 'end')
              throw new Exception('latest game state is null or game is already over');

            $players = $latestState['players'];
            if (!in_array($userInfo['uid'], $latestState['state']['players']))
              throw new Exception('current user is out');

            $next = $latestState['state']['next'];
            if ($next == 'guess') throw new Exception('next action is guess');

            $content = '';
            if ($next == 'speak') {
              $content = ' ';
            } else if ($next == 'vote') {
              $content = 'vote';
            }
            if ($content) {
              $curGame = new AppSpyGame($game, $userInfo);
              $content = ($content == ' ') ? $content : $curGame->getVotedUid();
              $curGame->play($content, TRUE);
              AppSpyGame::addRobotTask('auto_play', $game['id'], $userInfo['uid']);
            }

            break;

          case 'app_check_speak': //检查有人长时间不发言
            if (!$task['workload']['game_id'])
              throw new Exception("game_id is null");

            $game = $this->getGameService()->getGameById($task['workload']['game_id']);
            if (!$game || !$game['status'])
              throw new Exception("game is null or is ended");

            $latestState = $this->getGameService()->getGameLatestStateByGameId($game['id']);
            if (!$latestState) {
              $latestState = array('state' => array(
                'speakers' => $game['info']['players'],
                'next' => 'speak',
                'round' => 1,
                'count' => 1,
              ));
            }
            if (!$latestState || $latestState['state']['next'] != 'speak')
              throw new Exception('game latest state is null or next action is not speak');
            if ($latestState['round'] != $task['workload']['round'] || $latestState['state']['count'] != $task['workload']['count'])
              throw new Exception('game state round or count is not right');

            $speakers = $latestState['state']['speakers'] ?: $latestState['state']['players'];
            if (!$speakers) throw new Exception('speakers is null');

            for ($i=0; $i<3; $i++) {
              foreach ($speakers as $speaker) {
                $userInfo = $this->getUserService()->getUserByUid($speaker);
                $userInfo = $userInfo ?: $this->getGameService()->getPseudoUserByUid($speaker);
                if (!$userInfo) continue;

                $curGame = new AppSpyGame($game, $userInfo);
                $curGame->play(' ', TRUE);
              }
              $latestState = $this->getGameService()->getGameLatestStateByGameId($game['id']);
              if ($latestState['state']['next'] != 'speak') break;
            }
            break;

          case 'app_check_vote': //检查有人长时间不投票
            if (!$task['workload']['game_id'])
              throw new Exception("game_id is null");

            $game = $this->getGameService()->getGameById($task['workload']['game_id']);
            if (!$game || !$game['status'])
              throw new Exception("game is null or is ended");

            $latestState = $this->getGameService()->getGameLatestStateByGameId($game['id']);
            if (!$latestState || $latestState['state']['next'] != 'vote')
              throw new Exception('game latest state is null or next action is not vote');
            if ($latestState['round'] != $task['workload']['round'] || $latestState['state']['count'] != $task['workload']['count'])
              throw new Exception('game state round or count is not right');

            $voters = $latestState['state']['voters'] ?: $latestState['state']['players'];
            if (!$voters) throw new Exception('voters is null');

            for ($i=0; $i<3; $i++) {
              foreach ($voters as $voter) {
                $userInfo = $this->getUserService()->getUserByUid($voter);
                $userInfo = $userInfo ?: $this->getGameService()->getPseudoUserByUid($voter);
                if (!$userInfo) continue;

                $curGame = new AppSpyGame($game, $userInfo);
                $curGame->play($curGame->getVotedUid(), 1);
              }
              $latestState = $this->getGameService()->getGameLatestStateByGameId($game['id']);
              if ($latestState['state']['next'] != 'vote') break;
            }
            break;

          case 'app_robot_speak': //机器人发言
            if (!$task['workload']['game_id'] || !$task['workload']['uid'])
              throw new Exception("game_id or uid is null");

            $game = $this->getGameService()->getGameById($task['workload']['game_id']);
            $currentUser = $this->getGameService()->getPseudoUserByUid($task['workload']['uid']);
            $response = $this->getGameService()->getGamesetNonRepeatResponse($game['info']['words']['words_id'], $game['id']);

            if (!$game || !$currentUser || !$response)
              throw new Exception("game, currentUser or responses is null");

            //$word = ($currentUser['uid'] == $game['info']['spy']) ? $game['info']['word']['spy'] : $game['info']['words']['normal'];
            //$responses = self::censorRobotSpeak($responses, $word);
            $curGame = new AppSpyGame($game, $currentUser);
            $curGame->play($response, TRUE);

            break;

          case 'app_robot_vote': //机器人投票
            if (!$task['workload']['game_id'] || !$task['workload']['uid'])
              throw new Exception("game_id or uid is null");

            $game = $this->getGameService()->getGameById($task['workload']['game_id']);
            $currentUser = $this->getGameService()->getPseudoUserByUid($task['workload']['uid']);

            if (!$game || !$currentUser)
              throw new Exception("game or currentUser is null");

            $curGame = new AppSpyGame($game, $currentUser);
            $curGame->play($curGame->getVotedUid(), TRUE);

            break;

          case 'app_cleanup_game': //延时更新game,room status
            if (!$task['workload']['game_id'])
              throw new Exception("game_id is null");

            AppSpyGame::cleanUpGame($task['workload']['game_id']);
            break;

          case 'app_game_over': //游戏结束
            if (!$task['workload']['game_id'])
              throw new Exception("game_id is null");

            $game = $this->getGameService()->getGameById($task['workload']['game_id']);
            if (!$game || $game['status'] < 1)
              throw new Exception('game is null or game is already over');

            AppSpyGame::goodGame($game['id']);

            break;

          default:
            throw new Exception("unknown task type");
            break;
          }
          self::getTaskService()->updateTaskById($task['id'], array('status' => self::TASK_SUCCESSED_STATUS));
          ++ $taskDone;

        //捕获异常,任务失败
        } catch (Exception $e) {
          self::getTaskService()->updateTaskById($task['id'], array('status' => self::TASK_FALIED_STATUS));
          $taskFailed++;
          $now = date("Y-m-d H:i:s", $now);
          $this->println("- time:{$now}, task failed id:{$task['id']}, msg:{$e->getMessage()} -");
        }
        unset($task);
      }
    }
  }

  private static function censorRobotSpeak ($response, $spyWord) {
    if ($response && $spyWord) {
      $replace = array();
      $wordLen = mb_strlen($spyWord);
      for ($i=0; $i<$wordLen; $i++) {
        $replace[] = mb_substr($spyWord, $i, 1);
      }
      return str_replace($replace, '*', $response);
    }
    return '';
  }

  //获取task service
  public static function getTaskService () {
    return ServiceFactory::getInstance()->createTaskService();
  }

  //init
  public static function run ($processNum, $currentProcessId) {
    $self = new self();

    //启动进程数
    $self->processNum = $processNum;
    //当前进程ID
    $self->currentProcessId = $currentProcessId;
    //自动分配每个进程处理数据量
    $self->generalPerProcessDataCount();

    $self->println("-------- Start, ".date(DATE_FORMAT)." --------");
    $self->start();
    $self->println("-------- End, ".date(DATE_FORMAT)." --------");
    unset($self);
  }

  //每个进程处理数据量上限（默认0：自动分配）
  private function generalPerProcessDataCount () {
    $this->dataCount = 0;
    $this->perProcessDataCount = intval(ceil($this->dataCount / $this->processNum));
  }
}
