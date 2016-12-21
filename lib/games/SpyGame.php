<?php
final class SpyGame extends BaseGame {

  const SPYGAME_TYPE = 1;
  const UNSUPPORTED_MSG = "【收到不支持的消息类型，暂无法显示】";

  const SPY_GAME_LOSE_COIN_RULEID = 1;
  const SPY_WIN_COIN_RULEID = 2;
  const NORMAL_WIN_COIN_RULEID = 3;

  //array
  private $room = NULL;
  //array
  private $game = NULL;

  // array('uid' => user, ...)
  private $totalPlayers = NULL;
  // int uid
  private $currentPlayer = NULL;
  //array user
  private $currentUser = NULL;

  //array
  private $state = NULL;
  private $prevState = NULL;
  //string speak,vote,sum
  private $action = NULL;
  private $next = NULL;

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

  /**
   * @desc 设置当前玩家
   * @param array $currentUser
   * @return null
   */
  public function setCurrentUser ($currentUser) {
    if ($currentUser) {
      $this->currentUser = $currentUser;
      if ($this->action != 'speak') $this->currentPlayer = $currentUser['uid'];
    }
  }

  /**
   * @desc 获取当前玩家
   * @param bool
   * @return array/int
   */
  public function getCurrentPlayer ($info = TRUE) {
    return ($info) ? $this->totalPlayers[$this->currentPlayer] : $this->currentPlayer;
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
   * @desc 获取投票对象(脚本自动处理, 机器人)
   * 只有在next=vote时才执行
   * 除非房间中机器人只有一个, 否则机器人投票对象必须时机器人
   * @return int 玩家编号unum
   */
  public function getVotedUnum () {
    if (!$this->state['players']) return 'null';

    $playersFlip = array_flip($this->state['players']);
    unset($playersFlip[$this->currentUser['uid']]);

    $robots = self::getRobotsFromUsers($this->totalPlayers);
    if ($robots) {
      $tmpFlip = array();
      foreach ($robots as $robot) {
        if ($playersFlip[$robot['uid']]) {
          $tmpFlip[$robot['uid']] = $playersFlip[$robot['uid']];
        }
      }
      if ($tmpFlip) $playersFlip = $tmpFlip;
      unset($tmpFlip);
    }
    shuffle($playersFlip);
    return $playersFlip[0];
  }

  /**
   * @desc game对象初始化: room,game or current user 空时抛出异常
   * @param array $room
   * @param array $game
   * @param array $currentUser
   * @param array $state (optional)
   * @return null
   */
  public function __construct ($room = NULL, $game = NULL, $currentUser = NULL, $latestState = NULL) {
    if (!$room) throw new Exception('SpyGame:: room info is null');
    if (!$game) throw new Exception('SpyGame:: game info is null');
    if (!$currentUser) throw new Exception('SpyGame:: current user is null');
    if ($game['type'] != self::SPYGAME_TYPE) throw new Exception("SpyGame:: game type is worng, current type:{$game['type']}");
    if ($game['status'] == 0) throw new Exception('SpyGame:: game is ended');

    $this->room = $room;
    $this->game = $game;
    $this->currentUser = $currentUser;
    $this->totalPlayers = self::getUserWxInfoListByUids(self::getGameService()->getUserListByRoomId($this->room['id']));
    $this->processGameState($latestState);
  }

  /**
   * @desc 进行游戏,根据this->action调用不同方法
   * 方法不存在时抛出异常
   * @return string reply msg content
   */
  public function play ($msgContent = NULL, $isScript = FALSE) {
    if (!$msgContent) throw new Exception('SpyGame:: play, msg content is null');
    if (!$this->currentPlayer) throw new Exception('SpyGame:: play, current player is null');

    $method = 'do' . ucfirst($this->action);
    if (method_exists($this, $method)) {
      return $this->$method($msgContent, $isScript);
    }
    throw new Exception("SpyGame:: method not exist {$method} ");
  }

  /**
   * @desc 游戏处于发言状态
   * @param string $msgContent
   * @param bool is auto script
   * @return string
   */
  private function doSpeak ($content, $isScript) {
    if ($this->currentPlayer != $this->currentUser['uid']) return "";
    if ($content == self::UNSUPPORTED_MSG) {
      return GameMsg::getMsgContent('msg_unsupported');
    } else if (mb_strlen($content) > 200) {
      return GameMsg::getMsgContent('msg_too_large');
    }

    $fields = array(
      'game_id' => $this->game['id'],
      'uid' => $this->currentPlayer,
      'round' => $this->state['round'],
      'action' => $this->action,
      'content' => $content,
    );
    $fields['state'] = json_encode(array(
      'unum' => $this->state['unum'],
      'players' => $this->state['players'],
      'next' => $this->next,
      'count' => $this->state['count'],
    ));
    if (!self::getGameService()->addGameState($fields)) throw new Exception("SpyGame:: doSpeak add game state failed, fields:" . json_encode($fields));

    $replace = array(
      '%unum%' => $this->state['unum'],
      '%uname%' => $this->currentUser['nickname'],
      '%speak%' => $content,
      '%round%' => $this->state['round'],
    );

    if ($this->next == 'speak') {
      $nextPlayerUid = $this->state['players'][$this->state['unnum']];
      $replace['%unnum%'] = $this->state['unnum'];
      $replace['%unname%'] = ($this->totalPlayers[$nextPlayerUid]) ? $this->totalPlayers[$nextPlayerUid]['nickname'] : "";

      if ($this->totalPlayers[$nextPlayerUid]['is_robot'])
        self::addRobotTask('speak', $this->game['id'], $nextPlayerUid);

      if ($this->state['round'] > 1) {
        $replace['%prev-rounds%'] = self::getPrevRoundsSpeaks($this->game['id'], $this->currentPlayer, $this->state['round']);
        $msg = GameMsg::getMsgContent('msg_speak_rounds', $replace);
      } else {
        $msg = GameMsg::getMsgContent('msg_speak_first', $replace);
      }

      if ($isScript) {  //is robot
        $msgKey = ($this->state['round'] == 1 && $this->currentPlayer == $this->room['host']) ? 'msg_host_not_speak' : 'msg_not_speak';
        $msg = GameMsg::getMsgContent($msgKey, $replace);
      }

    } else if ($this->next == 'vote') {
      self::addRobotVoteTask(self::getRobotsFromUsers($this->totalPlayers), $this->state['players'], $this->game['id']);

      $replace['%speakers%'] = self::getCurRoundSpeakers($this->game['id'], $this->state['round'], $this->totalPlayers);
      $msg = ($isScript) ? GameMsg::getMsgContent('msg_not_speak_last_one', $replace) : GameMsg::getMsgContent('msg_speak_last_one', $replace);
    }

    if ($isScript) {
      GameMsg::sendAsyncMsg($this->totalPlayers, $msg);
    } else {
      unset($this->totalPlayers[$this->currentPlayer]);
      GameMsg::sendAsyncMsg($this->totalPlayers, $msg);
    }
    return $msg;
  }

  /**
   * @desc 游戏处于投票态
   * @param string $msgContent
   * @param bool is auto script
   * @return string
   */
  private function doVote ($content, $isScript) {
    $this->state['unum'] = array_flip($this->state['voters'])[$this->currentPlayer];
    if (!$this->state['unum']) return "";  //当前玩家已经投过票或出局
    if (!$this->game['info']['players'][$content]) return GameMsg::getMsgContent('msg_vote_not_exist'); //投票对象不存在
    if (!isset($this->state['players'][$content])) return GameMsg::getMsgContent('msg_vote_dead');      //投票对象已经出局
    if ($this->state['players'][$content] == $this->currentPlayer) return GameMsg::getMsgContent('msg_vote_self'); //投票对象是自己

    unset($this->state['voters'][$this->state['unum']]);
    $fields = array(
      'game_id' => $this->game['id'],
      'uid' => $this->currentPlayer,
      'round' => $this->state['round'],
      'action' => 'vote',
      'content' => $content,
    );
    $this->next = ($this->state['voters']) ? 'vote' : 'sum';
    $fields['state'] = json_encode(array(
      'unum' => $this->state['unum'],
      'players' => $this->state['players'],
      'voters' => $this->state['voters'],
      'next' => $this->next,
      'count' => $this->state['count'],
    ));
    if (!self::getGameService()->addGameState($fields))
      throw new Exception("SpyGame:: doVote add game state failed, fields:" . json_encode($fields));

    $replace = array(
      '%unum%' => $this->state['unum'],
      '%uname%' => $this->currentUser['nickname'],
    );
    if ($isScript) {
      $msg = GameMsg::getMsgContent('msg_not_vote', $replace);
      GameMsg::sendAsyncMsg($this->totalPlayers, $msg);
      usleep(500000);
    }

    if ($this->next == 'vote') { //下一步是投票
      if (!$isScript) {
        unset($this->state['players'][$this->state['unum']]);
        unset($this->totalPlayers[$this->currentPlayer]);

        $msg = GameMsg::getMsgContent('msg_vote', $replace);
        GameMsg::sendAsyncMsg($this->totalPlayers, $msg);

        //机器人还要使用这些数据
        $this->state['players'][$this->state['unum']] = $this->currentPlayer;
        $this->totalPlayers[$this->currentPlayer] = $this->currentUser;
        ksort($this->state['players']);
      }
      return $msg;

    } else if ($this->next == 'sum') { //统计投票数据
      $this->processGameState();
      return $this->doSum('sum players votes', $isScript);
    }
  }

  /**
   * @desc 游戏处于统计投票状态
   * @param string $msgContent
   * @param bool is auto script
   * @return string
   */
  private function doSum ($content, $isScript) {
    $voteResult = self::getVoteResult($this->game['id'], $this->state['round'], $this->state['count']);
    if (!$voteResult) $voteResult = array(mt_rand(0, count($this->state['players']) - 1));;
    $voteResultLen = count($voteResult);

    $replace = array(
      '%room_id%' => $this->room['id'],
      '%unum%' => $this->state['unum'],
      '%uname%' => $this->currentUser['nickname'],
      '%round%' => $this->state['round'],
      '%nround%' => $this->state['round'] + 1,
      '%remain%' => count($this->state['players']) - 1,
      '%spyword%' => $this->game['info']['words']['spy'],
      '%spyname%' => $this->totalPlayers[$this->game['info']['spy']]['nickname'],
      '%normalword%' => $this->game['info']['words']['normal'],
    );
    $replace['%spy%'] = array_flip($this->state['players'])[$this->game['info']['spy']]; //spy编号
    $replace['%votes%'] = self::getCurRoundVotes($this->game['id'], $this->state['round'], $this->state['count']);
    $replace['%voted%'] = self::getVoteMsgStr($voteResult);
    $replace['%syschoose%'] = "最多，";

    //保留当前全部玩家数据,不包括已经出局的
    $this->game['players'] = $this->state['players'];
    if ($this->state['count'] == 1 && $voteResultLen > 1) { //第一轮投票，出现同票情况
      $this->next = 'vote';
      $content = implode('|', $voteResult);
      $msg = GameMsg::getMsgContent('msg_vote_tie', $replace);

    } else if ($this->state['count'] == 1) { //第一轮投票，无同票
      $this->next = 'end';
      $content = $voteResult[0];
      unset($this->state['players'][$content]);

      if ($this->game['players'][$content] == $this->game['info']['spy']) { //投票结果是spy出局
        $msg = GameMsg::getMsgContent('msg_normal_win', $replace);

      } else if (($replace['%remain%'] - 1) <= $this->game['info']['remain']) { //投票结果平民人数不足,spy胜利
        $msg = GameMsg::getMsgContent('msg_spy_win', $replace);

      } else {
        $this->next = 'speak';
      }

    } else if ($this->state['count'] > 1) { //第二轮投票
      if ($voteResultLen > 1) { //出现同票
        $content = $voteResult[mt_rand(0, $voteResultLen - 1)];
        $replace['%syschoose%'] = "一样，系统随机选择{$content}号{$this->totalPlayers[$this->game['players'][$content]]['nickname']}，";
      } else {
        $content = $voteResult[0];
      }

      unset($this->state['players'][$content]);
      $this->next = 'end';

      if ($this->game['players'][$content] == $this->game['info']['spy']) { //投票结果是spy出局
        $msg = GameMsg::getMsgContent('msg_normal_win', $replace);

      } else if (($replace['%remain%'] - 1) <= $this->game['info']['remain']) { //投票结果平民人数不足,spy胜利
        $msg = GameMsg::getMsgContent('msg_spy_win', $replace);

      } else {
        $this->next = 'speak';
      }
    }

    //游戏继续,发言
    if ($this->next == 'speak') {
      $replace['%unnum%'] = array_slice(array_keys($this->state['players']), 0, 1)[0];
      $replace['%unname%'] = $this->totalPlayers[$this->state['players'][$replace['%unnum%']]]['nickname'];
      $msg = GameMsg::getMsgContent('msg_vote_goon', $replace);

      //机器人添加任务 speak,vote
      $nextPlayerUid = $this->state['players'][$replace['%unnum%']];
      if ($this->totalPlayers[$nextPlayerUid]['is_robot'])
        self::addRobotTask('speak', $this->game['id'], $nextPlayerUid);

    } else if ($this->next == 'vote' && self::getRobotsFromUsers($this->totalPlayers)) {
      self::addRobotVoteTask(self::getRobotsFromUsers($this->totalPlayers), $this->state['players'], $this->game['id']);
    }

    $fields = array(
      'game_id' => $this->game['id'],
      'uid' => $this->currentPlayer,
      'round' => $this->state['round'],
      'action' => 'sum',
      'content' => $content,
    );
    $fields['state'] = json_encode(array(
      'players' => $this->state['players'],
      'next' => $this->next,
      'count' => $this->state['count'],
    ));
    if (!self::getGameService()->addGameState($fields))
      throw new Exception("SpyGame:: doSum add game state failed, fields:" . json_encode($fields));

    GameMsg::sendAsyncMsg($this->totalPlayers, $msg); //发送投票结果的消息
    usleep(500000);

    //游戏结束
    if ($this->next == 'end') {
      self::getGameService()->updateGameById($this->game['id'], array('status' => 0));
      self::getGameService()->updateRoomByRoomId($this->room['id'], array('status' => 2));

      $gamePunishs = self::getGameService()->getGamesetPunishs();
      $punish = $gamePunishs ? $gamePunishs[mt_rand(0, count($gamePunishs) - 1)] : array();
      $replace['%punish%'] = $punish['content'];

      $replace['%normal%'] = "";
      foreach ($this->game['info']['players'] as $key => $player) {
        if ($player == $this->game['info']['spy']) continue;
        $replace['%normal%'] .= "【{$key}号{$this->totalPlayers[$player]['nickname']}】";
      }

      //处理胜负后金币问题
      $spyWin = FALSE;
      if ($this->game['players'][$content] == $this->game['info']['spy']) {  //spy出局, 平民胜利
        $lastMsg = GameMsg::getMsgContent('msg_last_normal_win', $replace);
        $spyCoinRuleId = self::SPY_GAME_LOSE_COIN_RULEID;
        $normalCoinRuleId = self::NORMAL_WIN_COIN_RULEID;

      } else {  //spy胜利
        $spyWin = TRUE;
        $lastMsg = GameMsg::getMsgContent('msg_last_spy_win', $replace);
        $spyCoinRuleId = self::SPY_WIN_COIN_RULEID;
        $normalCoinRuleId = self::SPY_GAME_LOSE_COIN_RULEID;
      }

      foreach ($this->game['info']['players'] as $player) {
        if ($player == $this->game['info']['spy']) {
          self::getUserFortuneService()->autoUserFortuneCoin($player, $spyCoinRuleId);
        } else {
          self::getUserFortuneService()->autoUserFortuneCoin($player, $normalCoinRuleId);
        }
      }

      //机器人添加任务 punish, chat
      $totalPlayers = $this->totalPlayers;
      if ($spyWin) unset($totalPlayers[$this->game['info']['spy']]);

      $robots = self::getRobotsFromUsers($totalPlayers);
      if ($robots && $spyWin) {
        self::addRobotTask('punish', $punish['id'], $robots[mt_rand(0, count($robots) - 1)]['uid']);

      } else if ($robots && $this->totalPlayers[$this->game['info']['spy']]['is_robot']) {
        self::addRobotTask('punish', $punish['id'], $this->game['info']['spy']);

      } else if ($this->totalPlayers[$this->game['info']['spy']]['is_robot']) {
        //消除上面unset的副作用
        $robots = TRUE;
      }

      if ($robots) {
        self::addRobotTask('chat_end', $this->room['id']);
      }

      GameMsg::sendAsyncMsg(array($this->totalPlayers[$this->room['host']]), $lastMsg . GameMsg::getMsgContent('msg_last_host_special'));
      unset($this->totalPlayers[$this->room['host']]);
      GameMsg::sendAsyncMsg($this->totalPlayers, $lastMsg);
    }
    return $isScript ? TRUE : "";
  }

  /**
   * @desc 避免游戏结束game status未修改,玩家困在游戏中
   * @param string $msgContent
   * @param bool is auto script
   * @return string
   */
  private function doEnd ($content, $isScript) {
    if (!self::getGameService()->updateGameById($this->game['id'], array('status' => 0))) throw new Exception("SpyGame:: doSum update game status failed, game_id:{$this->game['id']}");
    if (!self::getGameService()->updateRoomByRoomId($this->room['id'], array('status' => 2)))  throw new Exception("SpyGame:: doSum update room status failed, room_id:{$this->room['id']}");;
    return $isScript ? TRUE : "";
  }

  /**
   * @desc 处理游戏最新的状态,决定游戏下一步action
   * @param array $latest game state (optional)
   * @return array
   */
  public function processGameState ($latestState = NULL) {
    $latestState = ($latestState === NULL) ? self::getGameService()->getGameLatestStateByGameId($this->game['id']) : $latestState;
    $latestState = ($latestState && $latestState['state']) ? array_merge($latestState, $latestState['state']) : array();
    $this->prevState = ($latestState) ? $latestState : array();
    $this->action = ($latestState) ? $latestState['next'] : 'speak';

    if (!$latestState) { //无game state 数据，游戏刚开始
      $this->currentPlayer = $this->game['info']['players'][1];
      $this->next = ($this->currentPlayer == end($this->game['info']['players'])) ? 'vote' : 'speak';
      $this->state = array(
        'players' => $this->game['info']['players'],
        'unum' => 1,
        'unnum' => 2,
        'round' => 1,
        'count' => 1,
      );
    } else if ($latestState['next'] == 'speak') { //游戏下一步是发言
      $playerNumKeys = array_keys($latestState['players']);
      $playerNumKeysFlip = array_flip($playerNumKeys);
      //action: speak or sum
      $playerNum = ($latestState['action'] == 'speak') ? $playerNumKeys[$playerNumKeysFlip[$latestState['unum']] + 1] : array_slice($playerNumKeys, 0, 1)[0];
      $nextPlayerNum = $playerNumKeys[$playerNumKeysFlip[$playerNum] + 1];

      $this->currentPlayer = $latestState['players'][$playerNum];
      if ($this->currentPlayer) {
        $this->next = ($this->currentPlayer == end($latestState['players'])) ? 'vote' : 'speak';
        $this->state = array(
          'players' => $latestState['players'],
          'unum' => $playerNum,
          'unnum' => ($this->next == 'speak' && $latestState['players'][$nextPlayerNum]) ? $nextPlayerNum : 0,
          'count' => ($latestState['action'] == 'speak') ? $latestState['count'] : 1,
          'round' => ($latestState['action'] == 'speak') ? $latestState['round'] : $latestState['round'] + 1,
        );
      }

    } else if ($latestState['next'] == 'vote') { //游戏下一步是投票
      $this->currentPlayer = $this->currentUser['uid'];
      $this->state = array(
        'voters' => ($latestState['action'] == 'vote') ? $latestState['voters'] : $latestState['players'],
        'players' => $latestState['players'],
        'round' => $latestState['round'],
        'count' => ($latestState['action'] == 'sum') ? $latestState['count'] + 1 : $latestState['count'],
      );

    } else if ($latestState['next'] == 'sum') { //游戏统计投票数据
      $this->currentPlayer = $this->currentUser['uid'];
      $this->state = array(
        'unum' => $latestState['unum'],
        'players' => $latestState['players'],
        'round' => $latestState['round'],
        'count' => $latestState['count'],
      );
    }
  }

   /**
    * @desc 转换user list,用uid做索引分别对应玩家信息
    * @param array 用户信息数组
    * @return array
    */
  private static function getUserWxInfoListByUids ($userList) {
    $list = array();
    if ($userList) {
      foreach ($userList as $user) {
        $list[$user['uid']] = $user;
      }
    }
    return $list;
  }

  /**
   * @desc 获取玩家在游戏中的每一轮的描述内容
   * msg 中的 %prev-rounds%
   * @param int $gameId
   * @param int $uid
   * @param int $current round
   * @return string
   */
  private static function getPrevRoundsSpeaks ($gameId, $uid, $curRound) {
    $speaks = ($gameId && $uid) ? self::getGameService()->getGameStatesByGameIdAndUidAndAction($gameId, $uid, 'speak') : array();
    if ($speaks) {
      foreach ($speaks as $speak) {
        if ($speak['round'] == $curRound) continue;
        $prevRounds .= "【第{$speak['round']}轮说】{$speak['content']}\n";
      }
      return $prevRounds;
    }
    return "";
  }

  /**
   * @desc 获取当前一轮所有用户的描述内容
   * msg 中的 %speakers%
   * @param int $gameId
   * @param int $round
   * @param array $playersList
   * @return string
   */
  private static function getCurRoundSpeakers ($gameId, $round, $playersList) {
    $speakList = ($gameId && $round && $playersList) ? self::getGameService()->getGameStatesByGameIdAndRoundAndAction($gameId, $round, 'speak') : array();
    if ($speakList) {
      $speakers = "";
      foreach ($speakList as $speak) {
        $speakers .= "{$speak['state']['unum']}号{$playersList[$speak['uid']]['nickname']}说：{$speak['content']}\n";
      }
      return $speakers;
    }
    return "";
  }

  /**
   * @desc 获取游戏中一轮的投票内容:xxx投给xxx号
   * msg 中的 %votes%
   * @param int $gameId
   * @param int $round
   * @param int $count
   * @return string
   */
  private static function getCurRoundVotes ($gameId, $round, $count) {
    $voteList = ($gameId && $round && $count) ? self::getGameService()->getGameStatesByGameIdAndRoundAndAction($gameId, $round, 'vote') : array();
    $str = array();
    if ($voteList) {
      foreach ($voteList as $vote) {
        if ($vote['state']['count'] == $count) {
          $str[$vote['state']['unum']] = "{$vote['state']['unum']}号投给{$vote['content']}号\n";
        }
      }
      ksort($str);
    }
    return implode($str);
  }

  /**
   * @desc 根据统计后的投票结果生成消息内容: xx,xxx等
   * @param array $vote result
   * @return string
   */
  private static function getVoteMsgStr ($voteResult) {
    $str = "";
    if ($voteResult) {
      foreach ($voteResult as $vote) {
        $str .= "{$vote}号，";
      }
      $str = rtrim($str, "，");
    }
    return $str;
  }

  /**
   * @desc 统计游戏中一轮投票的结果: array(unum, ...),同票时array长度大于1
   * @param int $gameId
   * @param int $round
   * @param int $count
   * @return array
   */
  private static function getVoteResult ($gameId, $round, $count) {
    $votes = ($gameId && $round && $count) ? self::getGameService()->getGameStatesByGameIdAndRoundAndAction($gameId, $round, 'vote') : array();
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
      $max = $i = 0;
      $voteResult = array();
      foreach ($validVotes as $key => $val) {
        if ($i == 0) $max = $val;
        if ($val == $max) $voteResult[] = $key;
        else if ($max > $val) break;
        $i++;
      }
      return $voteResult;
    }
    return array();
  }

  /**
   * @desc 获取当前游戏的状态
   * 公众号菜单"获取游戏当前状态"的接口
   * @return string $msg content
   */
  public function getStatus () {
    $replace = array();
    $replace['%players-status%'] = self::getPlayersStatus($this->totalPlayers, $this->state['players'], array_flip($this->game['info']['players']), $this->room['host']);
    $replace['%round%'] = $this->state['round'];
    if ($this->action == 'speak' && !$this->prevState) { //start->speak
      return GameMsg::getMsgContent('msg_status_start_speak', $replace);

    } else if ($this->action == 'speak' && $this->prevState['action'] == 'speak') { //speak->speak
      $replace['%speakers%'] = self::getCurRoundSpeakers($this->game['id'], $this->state['round'], $this->totalPlayers);
      return GameMsg::getMsgContent('msg_status_speak', $replace);

    } else if ($this->action == 'speak' && $this->prevState['action'] == 'sum') { //sum->speak
      -- $this->state['round'];
      $count = 2;
      $voteResult = self::getVoteResult($this->game['id'], $this->state['round'], $count);
      if ($voteResult && count($voteResult) > 1) {
        $replace['%syschoose%'] = "由于第2次投票结果同票，系统随机选择{$this->prevState['content']}号";
      } else if ($voteResult) {
        $replace['%syschoose%'] = "由于第1次投票结果同票，第2次投票结果{$this->prevState['content']}号";
      } else {
        $count = 1;
        $replace['%syschoose%'] = "投票结果{$this->prevState['content']}号";
      }
      $replace['%votes%'] = self::getCurRoundVotes($this->game['id'], $this->state['round'], $count);
      return GameMsg::getMsgContent('msg_status_sum_speak', $replace);

    } else if ($this->action == 'vote' && $this->prevState['action'] == 'speak') { //speak->vote
      $replace['%speakers%'] = self::getCurRoundSpeakers($this->game['id'], $this->state['round'], $this->totalPlayers);
      return GameMsg::getMsgContent('msg_status_speak_vote', $replace);

    } else if ($this->action == 'vote' && $this->prevState['action'] == 'vote') { //vote->vote
      $replace['%count%'] = ($this->state['count'] > 1) ? "第2次" : "";
      $replace['%votes%'] = self::getCurRoundVotes($this->game['id'], $this->state['round'], $this->state['count']);
      return GameMsg::getMsgContent('msg_status_vote', $replace);

    } else if ($this->action == 'vote' && $this->prevState['action'] == 'sum') { //sum->vote
      $replace['%votes%'] = self::getCurRoundVotes($this->game['id'], $this->state['round'], 1);
      return GameMsg::getMsgContent('msg_status_sum_vote', $replace);
    }
    return "";
  }

  /**
   * @desc 获取游戏中当前各玩家的状态:房主,已出局等
   * msg 中的 %players-status%
   * @param array $total players list
   * @param array $state players 未出局的players array(unum => $uid, ...)
   * @param array $game info players flip array(uid=>unum, ..)
   * @param int $room host
   * @return string
   */
  private static function getPlayersStatus ($playersList, $curPlayers, $playerNums, $host) {
    $status = array();
    if ($playersList && $curPlayers && $playerNums && $host) {
      foreach ($playersList as $uid => $user) {
        $status[$playerNums[$uid]] = "{$playerNums[$uid]}号{$user['nickname']}";
        if ($uid == $host) $status[$playerNums[$uid]] .= "【房主】";
        if (!in_array($uid, $curPlayers)) $status[$playerNums[$uid]] .= "【已出局】";
        $status[$playerNums[$uid]] .= "\n";
      }
      ksort($status);
    }
    return implode($status);
  }

  /**
   * @desc 获取房主,玩家等消息替换字符串: 新用户加入房间等时候
   * msg 中的 %players%
   * @param array $players list
   * @param array $room
   * @param bool $player unum (optional) 是否显示玩家编号
   * @return string
   */
  public static function getPlayerReplaceStr ($playersList, $room, $playerNum = FALSE) {
    $str = "";
    if ($playersList && $room) {
      foreach ($playersList as $key => $player) {
        if ($playerNum) $str .= ($key + 1) . "号：";
        $str .= ($player['uid'] == $room['host']) ? "{$player['nickname']}【房主】\n" : "{$player['nickname']}\n";
      }
    }
    return $str;
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

  /**
   * @desc 添加机器人投票任务,每个机器人分别添加一个任务,已出局不参与
   * @param array $robots
   * @param array $players
   * @param int $gameId
   * @return bool
   */
  private static function addRobotVoteTask ($robots, $players, $gameId) {
    if ($robots && $players && $gameId) {
      foreach ($robots as $robot) {
        if (in_array($robot['uid'], $players))
          self::addRobotTask('vote', $gameId, $robot['uid']);
      }
      return TRUE;
    }
    return FALSE;
  }

  //'创建房间'时机器人加入房间
  const ROBOT_CREATE_TASK_TIMEOUT = 20;

  //机器人加入房间定时
  const ROBOT_JOIN_TASK_TIMEOUT = 10;

  //房间满4人后机器人提示开始游戏
  const ROBOT_CHAT_START_TASK_TIMEOUT = 8;

  //游戏结束后机器人提示开始游戏
  const ROBOT_CHAT_END_TASK_TIMEOUT = 25;

  //游戏中机器人发言
  const ROBOT_SPEAK_TASK_TIMEOUT = 10;

  //游戏中机器人投票最短时间
  const ROBOT_VOTE_TASK_MIN_TIMEOUT = 5;

  //游戏中机器人投票最长时间
  const ROBOT_VOTE_TASK_MAX_TIMEOUT = 10;

  //游戏结束后机器人接受惩罚
  const ROBOT_PUNISH_TASK_TIMEOUT = 10;

  //房间解散后机器人释放'正在使用'状态
  const ROBOT_RELEASE_TIMEOUT = 600;

  /**
   * @desc 统一添加机器人任务
   * @param string $taskType create/join/chat/speak/vote/sum/punish...
   * @param int $taskId room_id/game_id
   * @param int $uid optional
   * @return bool
   */
  public static function addRobotTask ($taskType, $taskId, $uid = 0) {
    if (!$taskType || !$taskId) return FALSE;

    $workload = array();
    switch ($taskType) {
    case 'create':
      $workload = array('room_id' => $taskId);
      $method = 'addJoinRoomTask';
      $timeOut = self::ROBOT_CREATE_TASK_TIMEOUT;
      break;

    case 'join':
      $workload = array('room_id' => $taskId);
      $method = 'addJoinRoomTask';
      $timeOut = self::ROBOT_JOIN_TASK_TIMEOUT;
      break;

    case 'chat_start':
      $workload = array('room_id' => $taskId);
      $method = 'addChatMsgTask';
      $timeOut = self::ROBOT_CHAT_START_TASK_TIMEOUT;
      break;

    case 'chat_end':
      $workload = array('room_id' => $taskId);
      $method = 'addChatMsgTask';
      $timeOut = self::ROBOT_CHAT_END_TASK_TIMEOUT;
      break;

    case 'speak':
      if (!$uid) break;
      $workload = array('game_id' => $taskId, 'uid' => $uid);
      $method = 'addRobotSpeakTask';
      $timeOut = self::ROBOT_SPEAK_TASK_TIMEOUT;
      break;

    case 'vote':
      //每个机器人投票时间错开,自然一点
      $workload = array('game_id' => $taskId, 'uid' => $uid);
      $method = 'addRobotVoteTask';
      $timeOut = ($uid % 3);
      $timeOut += mt_rand(self::ROBOT_VOTE_TASK_MIN_TIMEOUT, self::ROBOT_VOTE_TASK_MAX_TIMEOUT);
      break;

    case 'punish':
      $workload = array('punish_id' => $taskId, 'uid' => $uid);
      $method = 'addRobotPunishmentTask';
      $timeOut = self::ROBOT_PUNISH_TASK_TIMEOUT;
      break;

    case 'release':
      $workload = array('pseudo_uid' => $taskId);
      $method = 'addRobotReleaseIsusing';
      $timeOut = self::ROBOT_RELEASE_TIMEOUT;

    default: break;
    }
    return $workload ? self::getTaskService()->$method($workload, $timeOut) : FALSE;
  }
}
