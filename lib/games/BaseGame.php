<?php
/**
 * @desc 游戏基础类
 */
abstract class BaseGame {

  abstract public function __construct($room = NULL, $game = NULL, $currentUser = NULL, $latestState = NULL);
  abstract public function play($action = NULL, $isHuman = TRUE);

  final protected static function getGameService () {
    return ServiceFactory::getInstance()->createGameService();
  }
}

/*
class GamePlayers extends Base {

  private $totalNum = 0;
  private $players = NULL;

  public function __construct ($totalPlayers) {
    if ($totalPlayers) {
      $this->totalNum = count($totalPlayers);
      $playersKeys = array_keys($totalPlayers);

      foreach ($playersKeys as $key => $num) {
        $uid = $totalPlayers[$num];
        $userInfo = self::getGameService()->getWanzhuWxUserInfo($uid);

        $this->players[$num] = $userInfo;
        if ($key > 0) $this->players[$playersKeys[$key-1]]['next'] = $this->players[$num];
        $this->players[$uid] = &$this->players[$num];
      }
    }
  }

  public function getPlayersNum () {
    return $this->totalNum;
  }

  public function getPlayerInfo ($num) {
    return $this->players ? $this->players[$num] : NULL;
  }

  public function getNextPlayerInfo ($num) {
    return $this->players ? $this->players[$num]['next'] : NULL;
  }

  public function getTotalPlayers ($num = NULL) {
    $totalPlayers = $this->players;
    if ($totalPlayers) {
      foreach ($totalPlayers as $key => $val) {
        unset($totalPlayers[$key]['next']);
        unset($totalPlayers[$val['uid']]);
        if ($num && isset($totalPlayers[$num])) unset($totalPlayers[$num]);
      }
    }
    return $totalPlayers;
  }
}
*/
