<?php
final class AppSpyGame extends BaseGame {

  const APP_SPYGAME_TYPE = 4;

  //加入房间最多人数
  const JOIN_ROOM_MAX_NUM = 6;

  //一个房间里最多机器人个数
  const MAX_ROBOT_NUM = 5;

  //开始游戏最少的准备人数
  const MIN_PLAYERS_READY_NUM = 4;
  const MAX_PLAYERS_READY_NUM = 6;

  const SPY_WIN_COIN_RULEID = 32;
  const SPY_LOSE_COIN_RULEID = 33;
  const NORMAL_WIN_COIN_RULEID = 34;
  const NORMAL_LOSE_COIN_RULEID = 35;
  //卧底胜:卧底+40金币, 平民扣30金币;
  //平民胜:+30金币, 卧底扣20金币
  const SPY_LOSE_COIN_DESC = '20';
  const SPY_WIN_COIN_DESC = '40';
  const NORMAL_LOSE_COIN_DESC = '30';
  const NORMAL_WIN_COIN_DESC = '30';


  //array
  private $room = NULL;
  //array
  private $game = NULL;

  // array('uid' => user, ...)
  private $totalPlayers = NULL;
  //array user
  private $currentUser = NULL;

  //array
  private $state = NULL;
  //string speak,vote,sum
  private $action = NULL;
  private $next = NULL;

  //所有人都投一个人
  private $allVoteOne = FALSE;

  /**
   * @desc 获取service
   * @return object
   */
  final private static function getUserFortuneService () {
    return ServiceFactory::getInstance()->createUserFortuneService();
  }
  final private static function getTaskService () {
    return ServiceFactory::getInstance()->createTaskService();
  }
  final private static function getGearmanService () {
    return ServiceFactory::getInstance()->createGearmanService();
  }
  final private static function getRongCloudService () {
    return ServiceFactory::getInstance()->createRongCloudService();
  }

  /**
   * @desc 设置当前用户/玩家(发言时用户与玩家不同)
   * @param array $currentUser
   * @return null
   */
  public function setCurrentUser ($currentUser) {
    if ($currentUser) {
      $this->currentUser = $currentUser;
    }
  }

  /**
   * @desc 获取当前玩家
   * @param bool
   * @return array/int
   */
  public function getCurrentUser ($info = TRUE) {
    return ($info) ? $this->currentUser : $this->currentUser['uid'];
  }

  /**
   * @desc 获取所有玩家信息
   * @return array(uid => $info, ...)
   */
  public function getTotalPlayers () {
    return $this->totalPlayers;
  }

  /**
   * @desc 获取游戏状态
   * @return array
   */
  public function getGameState () {
    return $this->state;
  }

  /**
   * @desc 房间中的玩家是否准备好了
   * @param array $playersList
   * @param bool returnPlayers (default false)
   * @return int
   */
  public static function arePlayersReady ($playersList, $returnPlayers = FALSE) {
    if ($playersList) {
      if ($returnPlayers) $players = array();
      $readiedCount = 0;
      foreach ($playersList as $player) {
        if ($player['status'] == 2) {
          ++ $readiedCount;
          if ($returnPlayers) $players[] = $player;
        }
      }
      if ($returnPlayers) return $players;
      return $readiedCount;
    }
    return 0;
  }

  /**************************************************************
   *                                                            *
   * ---------- * 游戏开始, 设置游戏开始信息 start * ---------- *
   *                                                            *
   **************************************************************/

  private static $gameRules = array(4 => 1, 5 => 2, 6 => 2, 7 => 2, 8 => 3, 9 => 3);
  /**
   * @desc 生成游戏信息(卧底)和规则(结束条件)
   * @param array $playersList
   * @return array
   */
  private static function getGameInfoAndWords ($playersList) {
    $len = count($playersList);
    if ($playersList && self::$gameRules[$len]) {
      $gameInfo = array();
      $gameInfo['remain'] = self::$gameRules[$len];
      $gameInfo['spy'] = self::pickSpyFromPlayers($playersList, $len);

      //1， 20起占位作用,对查询的数据不影响
      $spyWords = self::getGameService()->getGamesetSpywords(1, 20, TRUE);
      $gameInfo['words'] = ($spyWords) ? $spyWords[mt_rand(0, count($spyWords) - 1)] : array();

      $userWords = array();
      foreach ($playersList as $key => $player) {
        $gameInfo['players'][$key + 1] = $player['uid'];
        $rcUid = self::getRongCloudService()->getUserId($player['uid']);
        $userWords[$rcUid] = ($player['uid'] == $gameInfo['spy']) ? $gameInfo['words']['spy'] : $gameInfo['words']['normal'];
      }
      return array(
        'info' => $gameInfo,
        'user_words' => $userWords,
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
  private static function pickSpyFromPlayers ($playersList, $len, $minPoolNum = 4) {
    $playersPool = self::getRobotsFromUsers($playersList, TRUE);
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

  /**
   * @desc 开始游戏并初始化
   *   清理房间里面未准备和离线的人
   * @param int $roomId
   * @return bool
   */
  public static function initGameStart ($roomId) {
    if (!$roomId) throw new Exception('room id is null');
    $room = self::getGameService()->getRoomById($roomId);
    if (!$room || $room['status'] != 2) throw new Exception('room status is dismissed or gaming');

    $totalPlayers = self::getGameService()->getUserListByRoomId($roomId, TRUE);
    if (!$totalPlayers) throw new Exception('room players is null');

    $playersList = self::arePlayersReady($totalPlayers, TRUE);
    $playersCount = count($playersList);
    $robots = self::getRobotsFromUsers($playersList);
    $robotsCount = count($robots);

    if (!$playersCount || $playersCount < self::MIN_PLAYERS_READY_NUM) {
      if ($robotsCount < self::MAX_ROBOT_NUM) {
        self::addRobotTask('app_join', $room['id']);
      }
      throw new Exception('readied players is null or not enough');
    }

    //获取卧底词,游戏人数对应规则
    $infoAndWords = self::getGameInfoAndWords($playersList);
    if (!$infoAndWords) throw new Exception('init game info failed');

    $gameId = self::getGameService()->addGame(array(
      'room_id' => $room['id'],
      'type' => self::APP_SPYGAME_TYPE,
      'status' => 1,
      'info' => json_encode($infoAndWords['info']),
    ));
    if (!$gameId) throw new Exception('start game failed');
    self::cleanUpRoomPlayers($roomId, $totalPlayers);

    $gameMsg = array(
      'roomid' => $roomId,
      'action' => 'start',
      'content' => array('user_words' => $infoAndWords['user_words']),
    );
    self::sendRcGameMsg($gameMsg);
    self::sendRcChatRoomMsg(array($roomId), 'game_spy_start_speak' );

    self::addRobotTask('check_speak', $gameId, array('round' => 1, 'count' => 1));
    //添加机器人任务 speak
    self::addBatchRobotSpeakTask($robots, $infoAndWords['info']['players'], $gameId);
    return TRUE;
  }

  /**************************************************************
   *                                                            *
   *  ---------- * 游戏开始, 设置游戏开始信息 end * ----------  *
   *                                                            *
   **************************************************************/

  /**
   * @desc 卧底猜词,手动结束
   * @param int $gameId
   * @return bool
   */
  public static function goodGame ($gameId) {
    if (!$gameId) throw new Exception('game id is null');
    $gameService = self::getGameService();

    $game = $gameService->getGameById($gameId);
    if (!$game || $game['status'] == 0) throw new Exception('game or game id is null');

    $totalPlayers = self::addUserListUidIndex($gameService->getUserListByRoomId($game['room_id'], TRUE));
    if (!$totalPlayers) throw new Exception('room players is null');

    $latestState = $gameService->getGameLatestStateByGameId($gameId);
    if (!$latestState)
      throw new Exception('game state is null');

    if ($latestState['state']['next'] == 'guess') $latestState['action'] = 'guess';

    //卧底猜词时,卧底是机器人
    if ($latestState['action'] == 'guess' && $totalPlayers[$game['info']['spy']]['is_robot']) {
      $spyWin = FALSE;

    //卧底猜词时,卧底是真实玩家
    } else if ($latestState['action'] == 'guess') {
      $spyWin = ($latestState['content'] == $game['info']['words']['normal']) ? TRUE : FALSE;

    } else {
      throw new Exception('an error occured in game state');
    }

    if (!$gameService->addGameState(array(
      'game_id' => $game['id'],
      'uid' => $game['info']['spy'],
      'round' => $latestState['round'],
      'action' => 'end',
      'content' => $latestState['content'],
      'state' => json_encode(array('next' => 'end')),
    ))) {
      throw new Exception('add game end state failed');
    }

    if (!$gameService->updateGameById($game['id'], array('status' => 0))) {
      throw new Exception("good game update game status failed, game_id:{$game['id']}");
    }
    if (!$gameService->updateRoomByRoomId($game['room_id'], array('status' => 2))) {
      throw new Exception("good game update room status failed, room_id:{$game['room_id']}");
    }

    $gameMsgContent = array('result' => array(
      'words_id' => $game['info']['words']['words_id'],
      'win' => $spyWin ? 1 : 0,
      'spy' => array(
        'word' => $game['info']['words']['spy'],
        'uid' => self::getRongCloudService()->getUserId($game['info']['spy']),
      ),
      'normal' => array(
        'word' => $game['info']['words']['normal'],
        'uids' => array(),
      ),
    ));
    $replace = array(
      '%spyname%' => $totalPlayers[$game['info']['spy']]['nickname'],
      '%normals%' => '',
      '%normal-coin%' => '',
      '%spy-coin%' => '',
      '%spy-voted%' => '',
    );

    //平民uids
    $allPlayers = $game['info']['players'];
    unset($allPlayers[array_flip($allPlayers)[$game['info']['spy']]]);
    $normalUids = array_values($allPlayers);
    $gameMsgContent['result']['normal']['uids'] = self::getRongCloudService()->getUserIds($normalUids);
    $replace['%normals%'] = self::joinPlayerNames($totalPlayers, $normalUids);

    //卧底win
    if ($spyWin) {
      $spyCoinRuleId = self::SPY_WIN_COIN_RULEID;
      $normalCoinRuleId = self::SPY_LOSE_COIN_RULEID;
      $replace['%spy-coin%'] = self::SPY_WIN_COIN_DESC;
      $replace['%normal-coin%'] = self::NORMAL_LOSE_COIN_DESC;
      $gameMsgContent['result']['spy']['coin_desc'] = '+' . $replace['%spy-coin%'] . '金币';
      $gameMsgContent['result']['normal']['coin_desc'] = '-' . $replace['%normal-coin%'] . '金币';

    //卧底lose
    } else {
      $spyCoinRuleId = self::SPY_LOSE_COIN_RULEID;
      $normalCoinRuleId = self::NORMAL_WIN_COIN_RULEID;
      $replace['%spy-coin%'] = self::SPY_LOSE_COIN_DESC;
      $replace['%normal-coin%'] = self::NORMAL_WIN_COIN_DESC;
      $gameMsgContent['result']['spy']['coin_desc'] = '-' . $replace['%spy-coin%'] . '金币';
      $gameMsgContent['result']['normal']['coin_desc'] = '+' . $replace['%normal-coin%'] . '金币';
    }

    $roomIds = array($game['room_id']);
    $tplName = $spyWin ? 'game_spy_spy_win' : 'game_spy_normal_win';
    self::sendRcChatRoomMsg($roomIds, $tplName, $replace);

    $gameMsg = array(
      'roomid' => $game['room_id'],
      'action' => 'over',
      'content' => $gameMsgContent,
    );
    self::sendRcGameMsg($gameMsg);

    $roomId = $game['room_id'];
    foreach ($totalPlayers as $player) {
      if ($player['status'] < 2 || !$player['status']) {
        $gameService->deleteRoomUser($roomId, $player['uid']);
      } else if ($player['status'] == 2) {
        $gameService->updateRoomUserByRidAndUid($roomId, $player['uid'], array('status' => 1));
      }
      if ($player['is_robot'] && $player['status']) {
        self::addRobotTask('app_ready_later', $game['room_id'], $player['uid']);
      }
    }

    foreach ($game['info']['players'] as $player) {
      if ($player == $game['info']['spy']) {
        self::getUserFortuneService()->autoUserFortuneCoin($player, $spyCoinRuleId);
      } else {
        self::getUserFortuneService()->autoUserFortuneCoin($player, $normalCoinRuleId);
      }
    }

    return TRUE;
  }

  /**
   * @desc 清理房间里面未准备或离线的用户
   * @param int $roomId
   * @param array $playersList
   * @return bool
   */
  public static function cleanUpRoomPlayers ($roomId, $playersList) {
    if ($roomId && $playersList) {
      foreach ($playersList as $player) {
        if ($player['status'] < 2) {
          self::getGameService()->deleteRoomUser($roomId, $player['uid'], $player['is_robot']);
        }
      }
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc 检查房间里面未准备或离线的用户
   *   1. 如果房间里面全是机器人, 机器人退出房间解散
   *   2. 如果还有玩家, 机器人不足, 则加入机器人
   * @param int $roomId
   * @return bool
   */
  public static function checkRoomPlayers ($roomId) {
    if ($roomId) {
      $gameService = self::getGameService();
      $room = $gameService->getRoomById($roomId);
      if (!$room || $room['status'] == 0) return FALSE;

      $game = $gameService->getGameById($room['game_id']);
      $playersList = $gameService->getUserListByRoomId($roomId, TRUE);
      if (!$playersList) {
        if ($game) $gameService->updateGameById($game['id'], array('status' => 0));
        $gameService->updateRoomByRoomId($roomId, array('status' => 0));
        return TRUE;
      }
      $robots = array();
      $readiedCount = $robotsCount = $playersCount = 0;
      foreach ($playersList as $player) {
        if ($player['status']) {
          ++ $playersCount;
        }
        if ($player['is_robot']) {
          $robots[] = $player;
          ++ $robotsCount;
        }
      }
      if ($robotsCount == $playersCount) {
        foreach ($robots as $robot) {
          $gameService->deleteRoomUser($roomId, $robot['uid'], TRUE);
        }
        $gameService->updateGameById($room['game_id'], array('status' => 0));
        $gameService->updateRoomByRoomId($roomId, array('status' => 0));
        return TRUE;
      }
      if ($room['status'] != 2) return TRUE;

      if ($readiedCount >= self::MAX_PLAYERS_READY_NUM) {
        AppSpyGame::addRobotTask('start_now', $room['id']);

      } else if ($readiedCount >= self::MIN_PLAYERS_READY_NUM) {
        AppSpyGame::addRobotTask('start_later', $room['id']);
      }
      if ($readiedCount < self::MAX_PLAYERS_READY_NUM && $robotsCount <= self::MAX_ROBOT_NUM){
        self::addRobotTask('app_join', $roomId);
      }
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc game对象初始化: game or current user 空时抛出异常
   * @param array $game
   * @param array $currentUser
   * @param array $state (optional)
   * @return null
   */
  public function __construct ($game = NULL, $currentUser = NULL, $latestState = NULL, $room = NULL) {
    if (!$game) throw new Exception('game is null');
    if (!$currentUser) throw new Exception('current user is null');
    if ($game['type'] != self::APP_SPYGAME_TYPE) throw new Exception("game type is worng: {$game['type']}");
    if ($game['status'] == 0) throw new Exception('game is over');

    $this->game = $game;
    $this->currentUser = $currentUser;
    $this->totalPlayers = self::addUserListUidIndex(self::getGameService()->getUserListByRoomId($this->game['room_id'], TRUE));
    $this->processGameState($latestState);
  }

  /**
   * @desc 进行游戏,根据this->action调用不同方法
   * 方法不存在时抛出异常
   * @return string reply msg content
   */
  public function play ($msgContent = NULL, $isRobot = FALSE) {
    if (!$msgContent) throw new Exception('SpyGame:: play, msg content is null');

    $method = 'do' . ucfirst($this->action);
    if (method_exists($this, $method)) {
      return $this->$method($msgContent, $isRobot);
    }
    throw new Exception("SpyGame:: method not exist {$method} ");
  }

  /**
   * @desc 游戏处于发言状态
   * @param string $msgContent
   * @param bool is auto script
   * @return string
   */
  private function doSpeak ($content, $isRobot = FALSE) {
    if (!$this->state['speakers'][$this->state['unum']]) throw new Exception('current player already speaked');;
    if (mb_strlen($content) > 200) throw new Exception('speak content is too large');

    unset($this->state['speakers'][$this->state['unum']]);
    $this->next = ($this->state['speakers']) ? 'speak' : 'vote';

    $tmpState = array(
      'players' => $this->state['players'],
      'speakers' => $this->state['speakers'],
      'next' => $this->next,
      'count' => $this->state['count'],
    );
    if ($this->state['voters']) {
      $tmpState['voters'] = $this->state['voters'];
    }
    $fields = array(
      'game_id' => $this->game['id'],
      'uid' => $this->currentUser['uid'],
      'round' => $this->state['round'],
      'action' => $this->action,
      'content' => $content,
      'state' => json_encode($tmpState),
    );

    if (!self::getGameService()->addGameState($fields))
      throw new Exception("doSpeak add game state failed, fields:" . json_encode($fields));

    if ($isRobot) {
      $gameMsg = array(
        'roomid' => $this->game['room_id'],
        'type' => 'game',
        'action' => 'desc',
        'content' => array('word' => $content),
      );
      self::sendRcGameMsg($gameMsg, $this->currentUser);
    }

    if ($this->next == 'vote' && $this->state['count'] > 1) {
      $voters = $tmpState['voters'];
      $action = 'startpkvote';
    } else if ($this->next == 'vote') {
      $voters = $this->state['players'];
      $action = 'startvote';
    }

    if ($this->next == 'vote') {
      self::sendRcGameMsg(array(
        'roomid' => $this->game['room_id'],
        'action' => $action,
        'content' => array(),
      ));
      self::sendRcChatRoomMsg(array($this->game['room_id']), 'game_spy_start_vote');

      self::addRobotTask('check_vote', $this->game['id'], array(
        'round' => $this->state['round'],
        'count' => $this->state['count'],
      ));

      $robots = self::getRobotsFromUsers($this->totalPlayers);
      if (!$robots) return TRUE;
      self::addBatchRobotVoteTask($robots, $voters, $this->game['id']);
    }
    return TRUE;
  }

  /**
   * @desc 游戏处于投票态
   * @param string $msgContent
   * @param bool is robot
   * @return string
   */
  private function doVote ($content, $isRobot = FALSE) {
    if (!$this->state['voters'][$this->state['unum']]) throw new Exception('current player already voted');
    if (!in_array($content, $this->game['info']['players'])) throw new Exception('the one you voted is not exist');
    if (!in_array($content, $this->state['players'])) throw new Exception('the one you voted is out');

    unset($this->state['voters'][$this->state['unum']]);
    $this->next = ($this->state['voters']) ? 'vote' : 'sum';

    $fields = array(
      'game_id' => $this->game['id'],
      'uid' => $this->currentUser['uid'],
      'round' => $this->state['round'],
      'action' => 'vote',
      'content' => $content,
    );
    $fields['state'] = json_encode(array(
      'players' => $this->state['players'],
      'voters' => $this->state['voters'],
      'next' => $this->next,
      'count' => $this->state['count'],
    ));

    if (!self::getGameService()->addGameState($fields))
      throw new Exception("doVote add game state failed, fields:" . json_encode($fields));

    if ($isRobot) {
      //发送XXX替XXX投票消息
      if ($isRobot === 1) {
        $replace = array(
          '%offline%' => $this->currentUser['nickname'],
          '%voted%' => $this->totalPlayers[$content]['nickname'],
        );
        self::sendRcChatRoomMsg(array($this->game['room_id']), 'game_spy_auto_vote', $replace);
      }

      $gameMsg = array(
        'roomid' => $this->game['room_id'],
        'type' => 'game',
        'action' => 'vote',
        'content' => array('uid' => self::getRongCloudService()->getUserId($content)),
      );
      self::sendRcGameMsg($gameMsg, $this->currentUser);
    }

    if ($this->next == 'sum') { //下一步是统计
      $this->processGameState();
      return $this->doSum('sum players votes');
    }
  }

  /**
   * @desc 游戏处于统计投票状态
   * @param string $msgContent
   * @param bool is auto script
   * @return string
   */
  private function doSum ($content, $isRobot = FALSE) {
    $voteResult = $this->getVoteResult();
    if (!$voteResult) throw new Exception('vote result is null');
    //$voteResult = array(mt_rand(0, count($this->state['players']) - 1));;

    //data init set
    $voteResultLen = count($voteResult);
    $gameMsg = array(
      'roomid' => $this->game['room_id'],
      'action' => 'dogfall',
      'type' => 'game',
    );
    $this->next = 'speak';
    $playerOuted = FALSE;

    //聊天室消息数据
    $roomIds = array($this->game['room_id']);
    $tplName = '';
    $replace = array(
      '%voted%' => self::joinPlayerNames($this->totalPlayers, $voteResult),
      '%spyname%' => $this->totalPlayers[$this->game['info']['spy']]['nickname'],
      '%normals%' => '',
      '%normal-coin%' => '',
      '%spy-coin%' => '',
      '%spy-voted%' => '卧底被投出，',
    );

    //同票超过2人
    if ($voteResultLen > 2) {
      ++ $this->state['round'];
      $this->state['count'] = 1;
      $gameMsg['content'] = array('uids' => self::getRongCloudService()->getUserIds($voteResult));
      $content = implode('|', $voteResult);
      $tplName = 'game_spy_tie';

    //pk时同票
    } else if ($voteResultLen == 2 && $this->state['count'] > 1) {
      ++ $this->state['round'];
      $this->state['count'] = 1;
      $playerOuted = TRUE;
      $gameMsg['action'] = 'die';
      $replace['%voted%'] = self::joinPlayerNames($this->totalPlayers, $voteResult);
      $outedIndex = mt_rand(0, 1);
      array_splice($voteResult, $outedIndex, 1);
      $content = $voteResult[0];
      $replace['%choose%'] = $this->totalPlayers[$content]['nickname'];
      $tplName = 'game_spy_pk_tie';


    //同票, 进行pk
    } else if ($voteResultLen == 2) {
      ++ $this->state['count'];
      $this->setPlayersForPk($voteResult);
      $content = implode('|', $voteResult);
      $gameMsg['action'] = 'pk';
      $gameMsg['content'] = array('uids' => self::getRongCloudService()->getUserIds($voteResult));
      $tplName = 'game_spy_pk_speak';

    //无同票, 有人出局
    } else {
      $playerOuted = TRUE;
      $content = $voteResult[0];
      if ($this->state['round'] == 1 && $this->allVoteOne && $this->game['info']['spy'] == $content) {
        $this->next = 'guess';
        $tplName = 'game_spy_guess';
      } else if ($this->game['info']['spy'] != $content) {
        $tplName = 'game_spy_normal_die';
      }
      ++ $this->state['round'];
      $this->state['count'] = 1;
    }

    //有人出局
    if ($playerOuted) {
      $gameMsg['action'] = 'die';
      $gameMsg['content'] = array('user' => array(
        'uid' => self::getRongCloudService()->getUserId($content),
        'is_spy' => ($this->game['info']['spy'] == $content) ? 1 : 0,
      ));

      $votedUnum = array_flip($this->state['players'])[$content];
      unset($this->state['players'][$votedUnum]);
      $playersCount = count($this->state['players']);

      //投票结果时卧底
      if ($gameMsg['content']['user']['is_spy'] && $this->next != 'guess') {
        $tplName = 'game_spy_normal_win';
        $this->next = 'end';

      //投票结果是平民, 且平民人数不足
      } else if ($playersCount <= ($this->game['info']['remain'] + 1)) {
        $tplName = 'game_spy_spy_win';
        $this->next = 'end';
      }
    }

    $tmpState = array(
      'players' => $this->state['players'],
      'next' => $this->next,
      'count' => $this->state['count'],
    );
    if ($this->state['speakers'] && $this->state['voters']) {
      $tmpState['speakers'] = $this->state['speakers'];
      $tmpState['voters'] = $this->state['voters'];
    }
    $fields = array(
      'game_id' => $this->game['id'],
      'uid' => $this->currentUser['uid'],
      'round' => $this->state['round'],
      'action' => 'sum',
      'content' => $content,
      'state' => json_encode($tmpState),
    );
    if (!self::getGameService()->addGameState($fields))
      throw new Exception("SpyGame:: doSum add game state failed, fields:" . json_encode($fields));

    //投票结果rc通知
    if ($this->next != 'end') {
      self::addRobotTask('game_msg', $this->game['id'], 0, array('gameMsg' => $gameMsg));
      if ($tplName) self::sendRcChatRoomMsg($roomIds, $tplName, $replace);
    } else if ($this->next == 'end' && $tplName == 'game_spy_pk_tie') {
      // pk时同票
      self::sendRcChatRoomMsg($roomIds, $tplName, $replace);
    }

    //添加机器人发言任务
    if ($this->next == 'speak') {
      $nextSpeakers = $this->state['speakers'] ?: $this->state['players'];
      self::addRobotTask('check_speak', $this->game['id'], array(
        'round' => $this->state['round'],
        'count' => $this->state['count'],
      ));
      AppSpyGame::addBatchRobotSpeakTask(self::getRobotsFromUsers($this->totalPlayers), $nextSpeakers, $this->game['id']);

    //下一步猜词, 且是机器人猜, 直接延时一会儿结束游戏
    } else if ($this->next == 'guess') {
      if ($this->totalPlayers[$content]['is_robot']) {
        self::addRobotTask('game_over', $this->game['id'], $content);
      } else {
        self::addRobotTask('over_later', $this->game['id']);
      }

    //游戏结束
    } else if ($this->next == 'end') {
      //usleep(500000);
      $spyWin = ($content == $this->game['info']['spy']) ? FALSE : TRUE;
      $gameMsgContent = array('result' => array(
        'words_id' => $this->game['info']['words']['words_id'],
        'win' => $spyWin ? 1 : 0,
        'spy' => array(
          'word' => $this->game['info']['words']['spy'],
          'uid' => self::getRongCloudService()->getUserId($this->game['info']['spy']),
        ),
        'normal' => array(
          'word' => $this->game['info']['words']['normal'],
          'uids' => array(),
        ),
      ));

      //平民uids
      $allPlayers = $this->game['info']['players'];
      unset($allPlayers[array_flip($allPlayers)[$this->game['info']['spy']]]);
      $normalUids = array_values($allPlayers);
      $gameMsgContent['result']['normal']['uids'] = self::getRongCloudService()->getUserIds($normalUids);
      $replace['%normals%'] = self::joinPlayerNames($this->totalPlayers, $normalUids);

      //卧底win
      if ($spyWin) {
        $spyCoinRuleId = self::SPY_WIN_COIN_RULEID;
        $normalCoinRuleId = self::SPY_LOSE_COIN_RULEID;
        $replace['%spy-coin%'] = self::SPY_WIN_COIN_DESC;
        $replace['%normal-coin%'] = self::NORMAL_LOSE_COIN_DESC;
        $gameMsgContent['result']['spy']['coin_desc'] = '+' . $replace['%spy-coin%'] . '金币';
        $gameMsgContent['result']['normal']['coin_desc'] = '-' . $replace['%normal-coin%'] . '金币';

      //卧底lose
      } else {
        $spyCoinRuleId = self::SPY_LOSE_COIN_RULEID;
        $normalCoinRuleId = self::NORMAL_WIN_COIN_RULEID;
        $replace['%spy-coin%'] = self::SPY_LOSE_COIN_DESC;
        $replace['%normal-coin%'] = self::NORMAL_WIN_COIN_DESC;
        $gameMsgContent['result']['spy']['coin_desc'] = '-' . $replace['%spy-coin%'] . '金币';
        $gameMsgContent['result']['normal']['coin_desc'] = '+' . $replace['%normal-coin%'] . '金币';
      }

      $gameMsg = array(
        'roomid' => $this->game['room_id'],
        'action' => 'over',
        'content' => $gameMsgContent,
      );
      self::addRobotTask('game_msg', $this->game['id'], 0, array('gameMsg' => $gameMsg));
      $chatWorkload = array(
        'roomIds' => $roomIds,
        'tplName' => $tplName,
        'tplData' => $replace,
      );
      self::addRobotTask('chat_msg', $this->game['id'], 0, $chatWorkload);

      foreach ($this->game['info']['players'] as $player) {
        if ($player == $this->game['info']['spy']) {
          self::getUserFortuneService()->autoUserFortuneCoin($player, $spyCoinRuleId);
        } else {
          self::getUserFortuneService()->autoUserFortuneCoin($player, $normalCoinRuleId);
        }
      }
      self::addRobotTask('cleanup_game', $this->game['id']);
      //$this->doEnd($content);
      return TRUE;
    }

    return TRUE;
  }

  /**
   * @desc 避免游戏结束game status未修改,玩家困在游戏中
   * @param string $msgContent
   * @param bool is auto script
   * @return string
   */
  private function doEnd ($content, $isRobot = FALSE) {
    $gameService = self::getGameService();
    if (!$gameService->updateGameById($this->game['id'], array('status' => 0))) {
      throw new Exception("doEnd update game status failed, game_id:{$this->game['id']}");
    }
    if (!$gameService->updateRoomByRoomId($this->game['room_id'], array('status' => 2))) {
      throw new Exception("doEnd update room status failed, room_id:{$this->game['room_id']}");
    }
    $playersList = $gameService->getUserListByRoomId($this->game['room_id'], TRUE);
    if ($playersList) {
      $roomId = $this->game['room_id'];
      foreach ($playersList as $player) {
        if ($player['status'] < 2 || !$player['status']) {
          $gameService->deleteRoomUser($roomId, $player['uid']);
        } else if ($player['status'] == 2) {
          $gameService->updateRoomUserByRidAndUid($roomId, $player['uid'], array('status' => 1));
        }
        if ($player['is_robot'] && $player['status']) {
          self::addRobotTask('app_ready_later', $this->game['room_id'], $player['uid']);
        }
      }
    }
    return TRUE;
  }

  /**
   * @desc 设置延时去修改游戏/房间状态
   * @param int $game_id
   * @return bool
   */
  public static function cleanUpGame ($gameId) {
    if ($gameId) {
      $gameService = self::getGameService();
      $game = $gameService->getGameById($gameId);
      if (!$game || !$game['status']) {
        throw new Exception('game is null or game is over');
      }
      $room = $gameService->getRoomById($game['room_id']);
      if ($room['game_id'] != $game['id']) {
        $gameService->updateGameById($game['id'], array('status' => 0));
        throw new Exception('room game id is not this game ');
      }

      if (!$gameService->updateGameById($game['id'], array('status' => 0))) {
        throw new Exception("clean up game update game status failed, game_id:{$game['id']}");
      }
      if (!$gameService->updateRoomByRoomId($game['room_id'], array('status' => 2))) {
        throw new Exception("clean up game update room status failed, room_id:{$game['room_id']}");
      }
      $playersList = $gameService->getUserListByRoomId($game['room_id'], TRUE);
      if ($playersList) {
        $robotsExitCount = 2;
        $roomId = $game['room_id'];
        foreach ($playersList as $player) {
          if ($player['status'] < 2 || !$player['status']) {
            $gameService->deleteRoomUser($roomId, $player['uid']);
          } else if ($player['status'] == 2) {
            $gameService->updateRoomUserByRidAndUid($roomId, $player['uid'], array('status' => 1));
          }
          if ($player['is_robot'] && $player['status']) {
            if ($robotsExitCount && mt_rand(0, 1)) {
              -- $robotsExitCount;
              self::addRobotTask('app_exit', $game['room_id'], $player['uid']);
              self::addRobotTask('app_join', $game['room_id']);
            } else {
              self::addRobotTask('app_ready', $game['room_id'], $player['uid']);
            }
          }
        }
      }
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc 获取投票对象(脚本自动处理, 机器人)
   * 只有在next=vote时才执行
   * 除非房间中机器人只有一个, 否则机器人投票对象必须是机器人
   * @return int 玩家编号unum
   */
  public function getVotedUid () {
    if (!$this->state['players']) return 'null';

    $voted = 0;
    //处于pk状态
    if ($this->state['count'] > 1) {
      $votes = self::getGameService()->getGameStatesByGameIdAndRoundAndAction($this->game['id'], $this->state['round'], 'sum');
      $pkers = explode('|', end($votes)['content']);
      if ($pkers) $voted = $pkers[mt_rand(0, count($pkers) - 1)];
    }

    //其他状态
    if (!$voted) {
      $players = $this->state['players'];
      $playersFlip = array_flip($players);
      $currentUnum = $playersFlip[$this->currentUser['uid']];
      unset($players[$currentUnum], $playersFlip[$this->currentUser['uid']]);

      $robots = self::getRobotsFromUsers($this->totalPlayers);
      if ($robots) {
        $tmpPlayers = array();
        foreach ($robots as $robot) {
          if ($playersFlip[$robot['uid']]) {
            $tmpPlayers[] = $robot['uid'];
          }
        }
        if ($tmpPlayers) $players = $tmpPlayers;
        unset($tmpPlayers, $playersFlip);
      }
      shuffle($players);
      return $players[0];
    } else {
      return $voted;
    }
  }

  /**
   * @desc 检查自动 发言/投票 操作的完整性
   * @param string $action
   * @return null
   * TODO
  private function fixAutoActionIntegrity ($action) {
    if (!$action || !in_array($action, array('speak', 'vote'))) return;
    if ($this->action == 'speak' && $this->next != 'speak') return;
    if ($this->action == 'vote' && $this->next != 'vote') return;
    $actions = self::getGameService()->
    $votes = self::getGameService()->getGameStatesByGameIdAndRoundAndAction($this->game['id'], $this->state['round'], 'sum');

  }
   */

  /**
   * @desc 处理游戏最新的状态,决定游戏下一步action
   * @param array $latest game state (optional)
   * @return array
   */
  public function processGameState ($latestState = NULL) {
    $latestState = ($latestState === NULL) ? self::getGameService()->getGameLatestStateByGameId($this->game['id']) : $latestState;
    $latestState = ($latestState && $latestState['state']) ? array_merge($latestState, $latestState['state']) : array();
    $this->action = ($latestState) ? $latestState['next'] : 'speak';

    if (!$latestState) { //无game state 数据，游戏刚开始
      $this->state = array(
        'players' => $this->game['info']['players'],
        'speakers' => $this->game['info']['players'],
        'round' => 1,
        'count' => 1,
        'unum' => array_flip($this->game['info']['players'])[$this->currentUser['uid']],
      );

    } else if ($latestState['next'] == 'speak') { //游戏下一步是发言
      $this->state = array(
        'players' => $latestState['players'],
        'speakers' => ($latestState['action'] != 'speak' && $latestState['count'] == 1) ? $latestState['players'] : $latestState['speakers'],
        'round' => $latestState['round'],
        'count' => $latestState['count'],
        'unum' => array_flip($this->game['info']['players'])[$this->currentUser['uid']],
      );
      if ($latestState['count'] > 1) $this->state['voters'] = $latestState['voters'];

    } else if ($latestState['next'] == 'vote') { //游戏下一步是投票
      $this->state = array(
        'players' => $latestState['players'],
        'voters' => ($latestState['action'] != 'vote' && $latestState['count'] == 1) ? $latestState['players'] : $latestState['voters'],
        'round' => $latestState['round'],
        'count' => $latestState['count'],
        'unum' => array_flip($this->game['info']['players'])[$this->currentUser['uid']],
      );

    } else if ($latestState['next'] == 'sum') { //游戏统计投票数据
      $this->state = array(
        'players' => $latestState['players'],
        'round' => $latestState['round'],
        'count' => $latestState['count'],
      );
    }
  }

  /**
   * @desc 根据uid连接玩家姓名字符串
   * @param array $uids
   * @return string
   */
  private static function joinPlayerNames ($totalPlayers, $uids) {
    if ($totalPlayers && $uids) {
      $len = count($uids);
      $glue = ($len > 2) ? '、' : (($len > 1) ? '和' : '');
      $names = array();
      foreach ($uids as $uid) {
        $names[] = "{$totalPlayers[$uid]['nickname']}";
      }
      return implode($glue, $names);
    }
    return '';
  }

   /**
    * @desc 转换user list,用uid做索引分别对应玩家信息
    * @param array 用户信息数组
    * @return array
    */
  private static function addUserListUidIndex ($userList) {
    $list = array();
    if ($userList) {
      foreach ($userList as $user) {
        $list[$user['uid']] = $user;
      }
    }
    return $list;
  }

  /**
   * @desc 从当前投票结果里面获取pk的speaker和voters
   * 注意, 只在doSum时调用
   * @param array $voteResult
   * @return null
   */
  private function setPlayersForPk ($voteResult) {
    if ($voteResult) {
      $this->state['speakers'] = $this->state['voters'] = array();
      foreach ($this->state['players'] as $k => $v) {
        if (in_array($v, $voteResult)) {
          $this->state['speakers'][$k] = $v;
        } else {
          $this->state['voters'][$k] = $v;
        }
      }
    }
  }

  /**
   * @desc 统计游戏中一轮投票的结果: array(uid, ...),同票时array长度大于1
   * @param int $gameId
   * @param int $round
   * @param int $count
   * @return array
   */
  private function getVoteResult () {
    $gameId = $this->game['id'];
    $round = $this->state['round'];
    $count = $this->state['count'];
    if (!$gameId || !$round || !$count) return array();

    $votes = self::getGameService()->getGameStatesByGameIdAndRoundAndAction($gameId, $round, 'vote');
    if ($votes) {
      $validVotes = array();
      foreach ($votes as $vote) {
        if ($vote['state']['count'] == $count) {
          $validVotes[$vote['content']] = (isset($validVotes[$vote['content']])) ? $validVotes[$vote['content']]+1 : 1;
        }
      }
      //sort desc order
      uasort($validVotes, function ($a, $b) {
        return ($a < $b) ? 1 : -1;
      });
      $this->allVoteOne = FALSE;
      $max = $i = 0;
      $voteResult = array();
      $totalPlayersCount = count($this->totalPlayers);
      foreach ($validVotes as $key => $val) {
        if ($i == 0) {
          ++ $i;
          $max = $val;
          if ($val == ($totalPlayersCount - 1)) {
            $this->allVoteOne = TRUE;
          }
        }
        if ($val == $max) {
          $voteResult[] = $key;
        } else if ($max > $val) {
          break;
        }
      }
      return $voteResult;
    }
    return array();
  }

  /**
   * @desc 获取用户列表中的机器人
   * @param array $users
   * @param bool $get user (机器人 or 真实用户)
   * @return array
   */
  public static function getRobotsFromUsers ($users, $getUser = FALSE) {
    if ($users) {
      $robots = $trueUsers = array();
      foreach ($users as $user) {
        if ($user['is_robot']) {
          $robots[] = $user;
        } else {
          $trueUsers[] = $user;
        }
      }
      return $getUser ? $trueUsers : $robots;
    }
    return array();
  }

  /**************************************************************
   *                                                            *
   *  ---------- * 添加发送gearman 融云消息 start * ----------  *
   *                                                            *
   **************************************************************/

  const APP_SPY_GAME_API_VERSION = '1.2.0';
  /**
   * @desc app 谁是卧底发送消息
   * @param array $gameMsg
   * @param string $content (default array)
   * @param array $fromUser (default NULL)
   * @return bool
   */
  public static function sendRcGameMsg ($gameMsg, $fromUser = NULL) {
    if ($gameMsg && $gameMsg['roomid'] && $gameMsg['action']) {
      $gameMsg['content'] = (object) ($gameMsg['content'] ?: array());
      $gameMsg['v'] = $gameMsg['v'] ?: self::APP_SPY_GAME_API_VERSION;
      $gameMsg['type'] = $gameMsg['type'] ?: 'game';
      return self::getGearmanService()->addRcGameMsg(array(
        'gameMsg' => $gameMsg,
        'fromUser' => $fromUser,
      ));
    }
    return FALSE;
  }

  /**
   * @desc app 谁是卧底发送消息
   * @param array $roomIds
   * @param string $templateName
   * @param array $templateData (default null)
   * @param array $fromUser (default null)
   * @return bool
   */
  public static function sendRcChatRoomMsg ($roomIds, $tplName, $tplData = NULL, $fromUser = NULL) {
    if ($roomIds && $tplName) {
      return self::getGearmanService()->addRcChatRoomMsg(array(
        'roomIds' => $roomIds, 'tplName' => $tplName, 'tplData' => $tplData, 'fromUser' => $fromUser,
      ));
    }
    return FALSE;
  }

  /**************************************************************
   *                                                            *
   *   ---------- * 添加发送gearman 融云消息 end * ----------   *
   *                                                            *
   **************************************************************/


  /**************************************************************
   *                                                            *
   *  ---------- * 添加app谁是卧底游戏任务, start * ----------  *
   *                                                            *
   **************************************************************/

  //机器人即时任务1s
  const ROBOT_INSTANT_TIMEOUT = 1;

  //机器人短时任务5s
  const ROBOT_SHORT_TIMEOUT = 5;

  //机器人加入房间定时
  const ROBOT_JOIN_TASK_TIMEOUT = 3;

  //机器人准备房间定时
  const ROBOT_READY_TASK_TIMEOUT = 2;
  const ROBOT_READY_LATER_TASK_TIMEOUT = 16;

  //游戏开始定时
  const GAME_START_LATER_TIMEOUT = 9;

  //游戏中机器人发言最短时间
  const ROBOT_SPEAK_MIN_TIMEOUT = 8;
  //游戏中机器人发言最长时间
  const ROBOT_SPEAK_MAX_TIMEOUT = 16;

  //检查是否有人长时间不发言
  const ROBOT_CHECK_SPEAK_TIMEOUT = 40;

  //检查是否有人长时间不投票
  const ROBOT_CHECK_VOTE_TIMEOUT = 20;

  //游戏中机器人投票最短时间
  const ROBOT_VOTE_MIN_TIMEOUT = 3;
  //游戏中机器人投票最长时间
  const ROBOT_VOTE_MAX_TIMEOUT = 8;

  //长时间不猜词游戏结束
  const GAME_OVER_LATER_TIMEOUT = 20;

  //游戏中机器人是卧底猜词
  const ROBOT_GAME_OVER_TIMEOUT = 8;

  /**
   * @desc 统一添加机器人任务
   * @param string $taskType msg/join/ready/speak/vote...
   * @param int $taskId room_id
   * @param int $uid optional
   * @return bool
   */
  public static function addRobotTask ($taskType, $taskId, $uid = 0, $msg = NULL) {
    if (!$taskType || !$taskId) return FALSE;

    $workload = array();
    switch ($taskType) {
    case 'game_msg':
      if (!$msg) break;
      $workload = $msg;
      $type = 'send_rc_game_msg';
      $timeOut = self::ROBOT_SHORT_TIMEOUT;
      break;

    case 'chat_msg':
      if (!$msg) break;
      $workload = $msg;
      $type = 'send_rc_chat_msg';
      $timeOut = self::ROBOT_SHORT_TIMEOUT;
      break;

    case 'app_join':
      $workload = array('room_id' => $taskId);
      $type = 'app_robot_join';
      $timeOut = mt_rand(0, 2);
      break;

    case 'app_ready':
      $workload = array('room_id' => $taskId, 'uid' => $uid);
      $type = 'app_robot_ready';
      $timeOut = mt_rand(2, 3);
      break;

    case 'app_exit':
      $workload = array('room_id' => $taskId, 'uid' => $uid);
      $type = 'app_robot_exit';
      $timeOut = mt_rand(1, 2);
      break;

    case 'app_ready_later':
      $workload = array('room_id' => $taskId, 'uid' => $uid);
      $type = 'app_robot_ready';
      $timeOut = self::ROBOT_READY_LATER_TASK_TIMEOUT + ($uid % 3);
      break;

    case 'app_check':
      $workload = array('room_id' => $taskId);
      $type = 'app_robot_check';
      $timeOut = self::ROBOT_INSTANT_TIMEOUT;
      break;

    case 'start_later':
      $workload = array('room_id' => $taskId);
      $type = 'app_game_start';
      $timeOut = self::GAME_START_LATER_TIMEOUT;
      break;

    case 'start_now':
      $workload = array('room_id' => $taskId);
      $type = 'app_game_start';
      $timeOut = self::ROBOT_INSTANT_TIMEOUT;
      break;

    case 'auto_play':
      $workload = array('game_id' => $taskId, 'uid' => $uid);
      $type = 'auto_play_offline';
      $timeOut = self::ROBOT_SHORT_TIMEOUT;
      break;

    case 'app_speak':
      $workload = array('game_id' => $taskId, 'uid' => $uid);
      $type = 'app_robot_speak';
      $timeOut = ($uid % 3) + mt_rand(self::ROBOT_SPEAK_MIN_TIMEOUT, self::ROBOT_SPEAK_MAX_TIMEOUT);
      break;

    case 'check_speak':
      $workload = array('game_id' => $taskId, 'round' => $uid['round'], 'count' => $uid['count']);
      $type = 'app_check_speak';
      $timeOut = self::ROBOT_CHECK_SPEAK_TIMEOUT;
      break;

    case 'check_vote':
      $workload = array('game_id' => $taskId, 'round' => $uid['round'], 'count' => $uid['count']);
      $type = 'app_check_vote';
      $timeOut = self::ROBOT_CHECK_VOTE_TIMEOUT;
      break;

    case 'app_vote':
      //每个机器人投票时间错开,自然一点
      $workload = array('game_id' => $taskId, 'uid' => $uid);
      $type = 'app_robot_vote';
      $timeOut = ($uid % 3) + mt_rand(self::ROBOT_VOTE_MIN_TIMEOUT, self::ROBOT_VOTE_MAX_TIMEOUT);
      break;

    case 'cleanup_game': //游戏doSum等app ui, 然后清理游戏状态
      $workload = array('game_id' => $taskId);
      $type = 'app_cleanup_game';
      $timeOut = self::ROBOT_SPEAK_MAX_TIMEOUT + 3;
      break;

    case 'over_later': //长时间不猜词结束
      $workload = array('game_id' => $taskId);
      $type = 'app_game_over';
      $timeOut = self::GAME_OVER_LATER_TIMEOUT;
      break;

    case 'game_over':
      $workload = array('game_id' => $taskId);
      $type = 'app_game_over';
      //机器人猜词时uid 延时结束
      $timeOut = $uid ? self::ROBOT_GAME_OVER_TIMEOUT : self::ROBOT_INSTANT_TIMEOUT;
      break;

    default: break;
    }

    if ($workload) {
      $fields = array(
        'type' => $type,
        'workload' => $workload,
        'run_time' => time() + $timeOut,
      );
      return self::getTaskService()->addTask($fields);
    }
    return FALSE;
  }

  /**
   * @desc 添加机器人发言任务,每个机器人分别添加一个任务,已出局不参与
   * @param array $robots
   * @param array $players
   * @param int $gameId
   * @return bool
   */
  private static function addBatchRobotSpeakTask ($robots, $players, $gameId) {
    if ($robots && $players && $gameId) {
      foreach ($robots as $robot) {
        if (in_array($robot['uid'], $players))
          self::addRobotTask('app_speak', $gameId, $robot['uid']);
      }
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc 添加机器人投票任务,每个机器人分别添加一个任务,已出局不参与
   * @param array $robots
   * @param array $players
   * @param int $gameId
   * @return bool
   */
  private static function addBatchRobotVoteTask ($robots, $players, $gameId) {
    if ($robots && $players && $gameId) {
      foreach ($robots as $robot) {
        if (in_array($robot['uid'], $players))
          self::addRobotTask('app_vote', $gameId, $robot['uid']);
      }
      return TRUE;
    }
    return FALSE;
  }
}
