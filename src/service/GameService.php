<?php
/**
 * @desc GameService
 */
class GameService extends BaseService {

  //玩主公众号id
  const WANZHU_APPID = 'wx318680eae930969f';

  private function getGameDAO () {
    return DAOFactory::getInstance()->createGameDAO();
  }

  private function getUserService () {
    return ServiceFactory::getInstance()->createUserService();
  }

  private function getTaskService () {
    return ServiceFactory::getInstance()->createTaskService();
  }

  public function getWanzhuWxUserInfo ($uid) {
    if ($uid) {
      $userWxOpenid = $this->getUserService()->getUserWeixinOpenidByUidAndAppid($uid, $appid = self::WANZHU_APPID);
      $userWxInfo = $this->getUserService()->getUserWeixinInfo($uid, TRUE);
      if ($userWxOpenid && $userWxInfo) {
        $userWxInfo['openid'] = $userWxOpenid['openid'];
        return $userWxInfo;
      }
    }
    return array();
  }

  /**
   * @desc 获取房间列表
   * @param int $gid app game id
   * @return array
   */
  public function getRoomList ($page = 1, $pageSize = 20, $gid = 0) {
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      return $this->getGameDAO()->findRoomList($gid, $offset, $pageSize);
    }
    return array();
  }

  /**
   * @desc 获取在线房间总数
   * @return int
   */
  public function getActiveRoomCount () {
    return $this->getGameDAO()->findActiveRoomCount();
  }

  /**
   * @desc 根据玩家id获取所在房间
   * @param int $uid
   * @return array
   */
  public function getRoomByUid ($uid) {
    $roomId = $uid ? $this->getRoomIdByUid($uid) : 0;
    $room = $roomId ? $this->getRoomById($roomId) : array();
    return ($room && $room['status'] > 0) ? $room : array();
  }

  /**
   * @desc 根据id获取房间
   * @param int $id
   * @return array
   */
  public function getRoomById ($roomId) {
    return ($roomId) ? $this->getGameDAO()->findRoomWithId($roomId) : array();
  }

  /**
   * @desc 生成新的房间
   * @param array $fields
   * @return int
   */
  public function generateNewRoom ($fields) {
    if ($fields && $fields['host'] && $fields['players'] && $fields['status']) {
      $fields['number'] = ($fields['number']) ? $fields['number'] : $this->getIdleRoomNumber();
      if ($fields['number']) {

        $fields['room_id'] = $this->getGameDAO()->insertRoom($fields);
        if ($fields['room_id'] && $this->getGameDAO()->insertRoomUser($fields['room_id'], $fields['host'])) {
          $this->getGameDAO()->updateRoomIdWithNumber($fields['number'], $fields['room_id']);
          return $fields['room_id'];
        } else if ($fields['room_id']) {
          $this->getGameDAO()->deleteRoomWithRoomId($fields['room_id']);
        }
      }
    }
    return 0;
  }

  /**
   * @desc 添加一个空的房间
   * @return int $roomId
   */
  public function addRoom ($gid = 0) {
    $fields = array(
      'host' => $this->getUserService()->getOfficialUserInfo()['uid'],
      'players' => 0,
      'gid' => $gid,
      'status' => 2,
      'number' => $this->getIdleRoomNumber(),
    );
    $roomId = ($fields['host']) ? $this->getGameDAO()->insertRoom($fields) : 0;
    if ($roomId) $this->getGameDAO()->updateRoomIdWithNumber($fields['number'], $roomId);
    return $roomId;
  }

  /**
   * @desc 根据房间id更新房间信息
   * @param int $id
   * @return bool
   */
  public function updateRoomByRoomId ($roomId, $fields) {
    return ($roomId && $fields) ? $this->getGameDAO()->updateRoomWithRoomId($roomId, $fields) : FALSE;
  }

  /**
   * @desc 根据房间id删除房间(注:生成房间失败时才执行该操作)
   * @param int $roomId
   * @return bool
   */
  public function deleteRoomByRoomId ($roomId) {
    return ($roomId) ? $this->getGameDAO()->deleteRoomWithRoomId($roomId) : FALSE;
  }

  /**
   * @desc 根据房间id增加/降低房间里玩家的数量
   * @param int $id
   * @param array $fields
   * @return bool
   */
  public function indeRoomPlayersByRoomId ($roomId, $fields) {
    return ($roomId && $fields) ? $this->getGameDAO()->indeRoomPlayersWithRoomId($roomId, $fields) : FALSE;
  }

  /**
   * @desc 根据房间id获取玩家列表
   * @param int $id
   * @return array
   */
  public function getUserListByRoomId ($roomId, $fromApp = FALSE, $page = 1, $pageSize = 30) {
    $list = array();
    if ($roomId && $page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $userList = $this->getGameDAO()->findUserListWithRoomId($roomId, $offset, $pageSize);
      if ($userList) {
        foreach ($userList as $user) {
          if (!$user['uid']) continue;
          if ($user['is_robot']) {
            $userInfo = $this->getPseudoUserByUid($user['uid']);
            $userInfo['is_robot'] = 1;
          } else if ($fromApp) {
            $userInfo = $this->getUserService()->getUserByUid($user['uid']);
          } else {
            $userInfo = $this->getWanzhuWxUserInfo($user['uid']);
          }
          $userInfo['status'] = $user['status'];

          if ($userInfo) $list[] = $userInfo;
        }
      }
    }
    return $list;
  }

  /**
   * @desc 根据uid获取房间id
   * @param int $uid
   * @return int
   */
  public function getRoomIdByUid ($uid) {
    return $uid ? $this->getGameDAO()->findRoomIdWithUid($uid) : 0;
  }

  /**
   * @desc 根据room_id,uid获取room_user
   * @param int $roomId
   * @param int $uid
   * @return array
   */
  public function getRoomUserByRoomidAndUid ($roomId, $uid) {
    return ($roomId && $uid) ? $this->getGameDAO()->findRoomUserWithRoomidAndUid($roomId, $uid) : array();
  }

  /**
   * @desc 房间里添加一位新玩家
   * @param int $roomid
   * @param int $uid
   * @param bool $isRobot
   * @return bool
   */
  public function addRoomUser ($roomId, $uid, $isRobot = FALSE) {
    if ($roomId && $uid) {
      $isAdded = $this->getGameDAO()->insertRoomUser($roomId, $uid, $isRobot);
      if ($isAdded) {
        $fields = array(array('in_de' => '+', 'key' => 'players'));
        if ($this->getGameDAO()->indeRoomPlayersWithRoomId($roomId, $fields)) {
          if ($isRobot) $this->updatePseudoUserByUid($uid, array('is_using' => 1));
          return TRUE;

        } else {
          $this->getGameDAO()->deleteRoomUser($roomId, $uid);
        }
      } else if ($isAdded) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @desc 更新room_user信息
   * @param int $roomId
   * @param array $fields
   * @return bool
   */
  public function updateRoomUserByRidAndUid ($roomId, $uid, $fields) {
    return ($roomId && $uid && $fields) ? $this->getGameDAO()->updateRoomUserWithRidAndUid($roomId, $uid, $fields) : FALSE;
  }

  /**
   * @desc 玩家退出房间
   * @param int $roomid
   * @param int $uid
   * @param bool $isRobot
   * @return bool
   */
  public function deleteRoomUser ($roomId, $uid, $isRobot = FALSE) {
    if ($roomId && $uid) {
      $isDeleted = $this->getGameDAO()->deleteRoomUser($roomId, $uid);
      if ($isDeleted) {
        $fields = array(array('in_de' => '-', 'key' => 'players'));
        if ($this->getGameDAO()->indeRoomPlayersWithRoomId($roomId, $fields)) {
          if ($isRobot) SpyGame::addRobotTask('release', $uid);
          return TRUE;

        } else {
          $this->getGameDAO()->insertRoomUser($roomId, $uid);
        }
      } else if ($isDeleted) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @desc 获取空闲的4位房间号
   * @return int
   */
  public function getIdleRoomNumber () {
    $number = $this->getGameDAO()->findIdleRoomNumber();
    //随机设置不为空的roomId,作为临时锁
    //TODO 还是有发生冲突的可能
    $lock = mt_rand(1, 9);
    if ($number) {
      if ($this->getGameDAO()->updateRoomIdWithNumber($number, $lock)) return $number;
    } else {
      return $this->getGameDAO()->insertRoomNumber($lock);
    }
    return 0;
  }

  /**
   * @desc 生成新的4位房间号
   * @return int
   */
  public function addRoomNumber ($roomId = 0) {
    return $this->getGameDAO()->insertRoomNumber($roomId);
  }

  /**
   * @desc 更新已有的4位房间号对应的roomId
   * @param int $number
   * @param int $roomId
   * @return bool
   */
  public function updateRoomIdByNumber ($number, $roomId = 0) {
    return ($number) ? $this->getGameDAO()->updateRoomIdWithNumber($number, $roomId) : FALSE;
  }

  /**
   * @desc 根据id获取游戏
   * @param int $gameid
   * @return array
   */
  public function getGameById ($gameId) {
    $game = array();
    if ($gameId) {
      $game = $this->getGameDAO()->findGameWithId($gameId);
      if ($game) $game['info'] = ($game['info']) ? json_decode($game['info'], TRUE) : array();
    }
    return $game;
  }

  /**
   * @desc 生成新的游戏
   * @param array $fields
   * @return int
   */
  public function addGame ($fields) {
    if ($fields && $fields['room_id'] && $fields['status'] && $fields['info'] && $fields['type']) {
      $gameId = $this->getGameDAO()->insertGame($fields);
      if ($gameId && $this->getGameDAO()->updateRoomWithRoomId($fields['room_id'],
        array('game_id' => $gameId, 'status' => 1))
      ) return $gameId;
    }
    return 0;
  }

  /**
   * @desc 更新游戏信息
   * @param int $gameId
   * @param array $fields
   * @return bool
   */
  public function updateGameById ($gameId, $fields) {
    return ($gameId && $fields) ? $this->getGameDAO()->updateGameWithId($gameId, $fields) : FALSE;
  }

  /**
   * @desc 获取游戏状态
   * @param int $gameId
   * @param int $round
   * @param string $action
   * @return array
   */
  public function getGameStatesByGameIdAndRoundAndAction ($gameId, $round, $action, $page = 1, $pageSize = 20) {
    $stateList = array();
    if ($gameId && $round && $action) {
      $offset = ($page - 1) * $pageSize;
      $gameStates = $this->getGameDAO()->findGameStatesWithGameIdAndRoundAndAction($gameId, $round, $action, $offset, $pageSize);
      if ($gameStates) {
        foreach ($gameStates as $state) {
          if (!$state) continue;
          $state['state'] = ($state['state']) ? json_decode($state['state'], TRUE) : array();
          $stateList[] = $state;
        }
      }
    }
    return $stateList;
  }

  /**
   * @desc 获取游戏状态
   * @param int $gameId
   * @param int $uid
   * @param string $action
   * @return array
   */
  public function getGameStatesByGameIdAndUidAndAction ($gameId, $uid, $action, $page = 1, $pageSize = 20) {
    $stateList = array();
    if ($gameId && $uid && $action) {
      $offset = ($page - 1) * $pageSize;
      $gameStates = $this->getGameDAO()->findGameStatesWithGameIdAndUidAndAction($gameId, $uid, $action, $offset, $pageSize);
      if ($gameStates) {
        foreach ($gameStates as $state) {
          if (!$state) continue;
          $state['state'] = ($state['state']) ? json_decode($state['state'], TRUE) : array();
          $stateList[] = $state;
        }
      }
    }
    return $stateList;
  }

  /**
   * @desc 根据游戏id获取最新的状态
   * @param int $gameId
   * @return array
   */
  public function getGameLatestStateByGameId ($gameId) {
    $state = array();
    if ($gameId) {
      $state = $this->getGameDAO()->findGameLatestStateWithGameId($gameId);
      if ($state) $state['state'] = ($state['state']) ? json_decode($state['state'], TRUE) : array();
    }
    return $state;
  }

  /**
   * @desc 添加游戏新的步骤或操作
   * @param array $fields
   * @return bool
   */
  public function addGameState ($fields) {
    if ($fields && $fields['game_id'] && $fields['uid'] && $fields['round'] && $fields['action'] && $fields['state'] && $fields['content']) {
      if (is_array($fields['state'])) $fields['state'] = json_encode($fields['state']);
      return $this->getGameDAO()->insertGameState($fields);
    }
    return FALSE;
  }

  /**
   * @desc 获取听歌曲猜歌名题目库
   * @return array
   */
  public function getGameTgqcgmTms ($status = -1, $page = 1, $pageSize = 20) {
    $list = array();
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $list = $this->getGameDAO()->findGameTgqcgmTms($status, $offset, $pageSize);
    }
    return $list;
  }

  /**
   * @desc 获取听歌曲猜歌名题目列表(TODO 模糊查询)
   * @param int $status 状态：-1-全部，0-未通过审核, 1-已通过审核
   * @param string $songName 歌曲名称
   * @return array $list
   */
  public function getGameTgqcgmTmsLikeSongName ($songName, $status = -1,$page = 1, $pageSize = 20) {
    $list = array();
    if ($songName && $page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $list = $this->getGameDAO()->findGameTgqcgmTmLikeSongName($songName, $status, $offset, $pageSize);
    }
    return $list;
  }

  /**
   * @desc 获取听歌曲猜歌名题目库总数
   * @return array
   */
  public function getGameTgqcgmOnlineTmsCount () {
    return $this->getGameDAO()->findGameTgqcgmOnlineTmsCount();
  }

  /**
   * @desc 获取听歌曲猜歌名单个题目
   * @param int $tmId 题目id
   * @return array $tmInfo
   */
  public function getGameTgqcgmTmById ($tmId) {
    $tmInfo = array();
    if ($tmId) {
      $tmInfo = $this->getGameDAO()->findGameTgqcgmTmWithId($tmId);
    }
    return $tmInfo;
  }

  /**
   * @desc 添加听歌曲猜歌名题目
   * @param array $fields
   * @return array $tmInfo
   */
  public function addGameTgqcgmTm ($fields) {
    $tmInfo = array();
    if ($fields && $fields['uid'] && $fields['song_name'] && $fields['uri'] && $fields['duration']) {
      $tmInfo = $this->getGameDAO()->insertGameTgqcgmTm($fields);
    }
    return $tmInfo;
  }

  /**
   * @desc 更新听歌曲猜歌名题目
   * @param int $tmid
   * @param array $fields
   * @return bool
   */
  public function updateGameTgqcgmTmById ($tmId, $fields) {
    if ($tmId && $fields) {
      return $this->getGameDAO()->updateGameTgqcgmTmWithId($tmId, $fields);
    }
    return FALSE;
  }

  /**
   * @desc 听歌曲猜歌名:添加答对用户记录
   * @param array $fields
   * @return bool
   */
  public function addGameTgqcgmRightUser ($fields) {
    if ($fields && $fields['tm_id'] && $fields['uid'] && $fields['rank']) {
      return $this->getGameDAO()->insertGameTgqcgmRightUser($fields);
    }
    return FALSE;
  }

  /**
   * @desc 谁是卧底管理,获取后台配置
   * @param $type 0:回复; 1,卧底词; 2,惩罚
   * @return array
   */
  public function getGamesetListByTypeAndRid ($type = 0, $rid = 0, $page = 1, $pageSize = 20) {
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      return $this->getGameDAO()->findGamesetListWithTypeAndRid($type, $rid, $offset, $pageSize);
    }
    return array();
  }

  /**
   * @desc 谁是卧底管理,获取卧底词和平民词
   * @param int $page
   * @param int $pageSize
   * @param bool $isRandom 开始游戏时随机生成卧底词
   * @return array
   */
  public function getGamesetSpywords ($page = 1, $pageSize = 20, $isRandom = FALSE) {
    $wordsList = array();
    if ($pageSize) {

      if ($isRandom) {
        $pageCount = $this->getGameDAO()->findGamesetCountWithType(1, $pageSize);
        $page = $pageCount ? mt_rand(1, $pageCount) : 1;
      }

      $words = $this->getGamesetListByTypeAndRid(1, 0, $page, $pageSize);
      if ($words) {
        foreach ($words as $word) {
          if (!$word['content']) continue;
          $tmpWords = json_decode($word['content'], TRUE);
          $tmpWords['words_id'] = $word['id'];
          $wordsList[] = $tmpWords;
          unset($word);
        }
      }
    }
    return $wordsList;
  }
  
  /**
   * @desc 谁是卧底管理,获取卧底词和平民词 (模糊查询)
   * @param string $keyword
   * @param int $page
   * @param int $pageSize
   * @return array
   */
  public function getGamesetSpywordsLikeKeyword ($keyword, $page = 1, $pageSize = 20) {
    $wordsList = array();
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $words = $this->getGameDAO()->findGamesetWithTypeLikeContent(1, $keyword, $offset, $pageSize);
      if ($words) {
        foreach ($words as $word) {
          if (!$word['content']) continue;
          $tmpWords = json_decode($word['content'], TRUE);
          $tmpWords['words_id'] = $word['id'];
          $wordsList[] = $tmpWords;
          unset($word);
        }
      }
    }
    return $wordsList;
  }

  /**
   * @desc 谁是卧底管理,获取惩罚
   * @return array
   */
  public function getGamesetPunishs ($page = 1, $pageSize = 20) {
    return ($page && $pageSize) ? $this->getGamesetListByTypeAndRid(2, 0, $page, $pageSize) : array();
  }

  /**
   * @desc 谁是卧底管理,获取后台配置的回复列表
   * @param $rid 卧底词/惩罚id
   * @return array
   */
  public function getGamesetResponseList ($rid, $page = 1, $pageSize = 20) {
    return ($rid && $page && $pageSize) ? $this->getGamesetListByTypeAndRid(0, $rid, $page, $pageSize) : array();
  }

  /**
   * @desc 机器人一段时间内获取不重复的回复
   f @param $rid 卧底词/惩罚id
   * @param $qid 去重id game_id/room_id
   * @return string
   */
  public function getGamesetNonRepeatResponse ($rid, $qid, $page = 1, $pageSize = 20) {
    $cacheKey = __FUNCTION__ . "_RID_{$rid}_QID_{$qid}";
    $responseList = $this->getMemcache()->get($cacheKey);
    $responseList = $responseList ?: $this->getGamesetListByTypeAndRid(0, $rid, $page, $pageSize);
    if ($responseList) {
      $responseKey = mt_rand(0, count($responseList) - 1);
      $response = array_splice($responseList, $responseKey, 1);
      $this->getMemcache()->set($cacheKey, $responseList, 0, self::FM_CACHE_TIME);
      return $response ? $response[0]['content'] : '';
    }
    return '';
  }

  /**
   * @desc 谁是卧底管理,根据id获取后台配置
   * @param $id 配置id
   * @return array
   */
  public function getGamesetById ($id) {
    $set = array();
    if ($id) {
      $set = $this->getGameDAO()->findGamesetWithId($id);
      if ($set && $set['type'] == 1) {
        $set['words'] = json_decode($set['content'], TRUE);
        unset($set['content']);
      }
    }
    return $set;
  }

  /**
   * @desc 谁是卧底管理,添加游戏设置
   * @param array $fields
   * @return bool
   */
  public function addGameset ($fields) {
    return ($fields && $fields['content']) ? $this->getGameDAO()->insertGameset($fields) : FALSE;
  }

  /**
   * @desc 谁是卧底管理,修改游戏设置
   * @param int $id
   * @param array $fields
   * @return bool
   */
  public function updateGamesetById ($id, $fields) {
    return ($id && $fields) ?  $this->getGameDAO()->updateGamesetWithId($id, $fields) : FALSE;
  }

  /**
   * @desc 谁是卧底管理,删除游戏设置
   * @param int $id
   * @return bool
   */
  public function deleteGamesetById ($id) {
    return ($id) ?  $this->getGameDAO()->deleteGamesetWithId($id) : FALSE;
  }

  /**
   * @desc 获取假用户列表
   * @return array
   */
  public function getPseudoUserListByUsing ($isUsing, $page = 1, $pageSize = 20) {
    $list = array();
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $userList = $this->getGameDAO()->findPseudoUserListWithUsing($isUsing, $offset, $pageSize);
      if ($userList) {
        foreach ($userList as $user) {
          $user['gender_name'] = $this->getUserService()->getGender($user['gender']);
          $user['birthday_desc'] = $this->getUserService()->getBirthdayDesc($user['birthday']);
          $user['age'] = Utils::getAgeFromDate($user['birthday']);
          $list[] = $user;
        }
      }
    }
    return $list;
  }

  /**
   * @desc 根据id获取假用户信息
   * @param int $uid
   * @return array
   */
  public function getPseudoUserByUid ($uid) {
    $userInfo = array();
    if ($uid) {
      $userInfo = $this->getGameDAO()->findPseudoUserWithUid($uid);
      if ($userInfo) {
        $userInfo['gender_name'] = $this->getUserService()->getGender($userInfo['gender']);
        $userInfo['birthday_desc'] = $this->getUserService()->getBirthdayDesc($userInfo['birthday']);
        $userInfo['age'] = Utils::getAgeFromDate($userInfo['birthday']);
      }
    }
    return $userInfo;
  }

  /**
   * @desc 获取第1个未使用的假用户
   * @return array
   */
  public function getLatestPseudoUser () {
    $userInfo = $this->getGameDAO()->findLatestPseudoUser(); 
    if ($userInfo) {
      $userInfo['gender_name'] = $this->getUserService()->getGender($userInfo['gender']);
      $userInfo['birthday_desc'] = $this->getUserService()->getBirthdayDesc($userInfo['birthday']);
      $userInfo['age'] = Utils::getAgeFromDate($userInfo['birthday']);
    }
    return $userInfo;
  }

  /**
   * @desc 添加假用户信息
   * @param array $fields
   * @return int
   */
  public function addPseudoUser ($fields) {
    if ($fields && $fields['nickname'] && $fields['birthday']) {
      return $this->getGameDAO()->insertPseudoUser($fields);
    }
    return 0;
  }

  /**
   * @desc 更新假用户信息
   * @param int $uid
   * @param array $fields
   * @return bool
   */
  public function updatePseudoUserByUid ($uid, $fields) {
    return ($uid && $fields) ? $this->getGameDAO()->updatePseudoUserWithUid($uid, $fields) : FALSE;
  }

  /**
   * @desc 删除假用户信息
   * @param int $uid
   * @return bool
   */
  public function deletePseudoUserByUid ($uid) {
    return $uid ? $this->getGameDAO()->deletePseudoUserWithUid($uid) : array();
  }
}

