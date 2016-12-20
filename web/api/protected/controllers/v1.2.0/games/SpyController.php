<?php
/**
 * @desc 谁是卧底游戏
 * @see 融云路由消息回调处理
 */
class SpyController extends BaseController {

  /**
   * @desc 第一轮卧底死卧底猜词
   */
  public function actionGuessword () {
    $roomId = $this->getSafeRequest('roomid', 0, 'int');
    $word = $this->getSafeRequest('word', '');
    if (!$roomId) $this->outputJsonData(1022);

    $room = $this->getGameService()->getRoomById($roomId);
    if (!$room || $room['status'] != 1 || !$room['game_id']) $this->outputJsonData(1029);

    $game = $this->getGameService()->getGameById($room['game_id']);
    if (!$game || $game['status'] == 0) $this->outputJsonData(1030);

    $fields = array(
      'game_id' => $game['id'],
      'uid' => $this->currentUser['uid'],
      'round' => 1,
      'action' => 'guess',
      'content' => $word,
      'state' => json_encode(array('next' => 'end')),
    );
    if (!$this->getGameService()->addGameState($fields)) $this->outputJsonData(1031);
    AppSpyGame::addRobotTask('game_over', $game['id']);

    $this->outputJsonData(0);
  }

  /**
   * @desc 评价题目
   */
  public function actionVoteTm () {
    $wordsId = $this->getSafeRequest('words_id', 0, 'int');
    $type = $this->getSafeRequest('type', 0, 'int');
    if (!$wordsId || !$type) $this->outputJsonData(1033);

    Utils::log("vote words:: uid {$this->currentUser['uid']}; words_id {$wordsId}; vote {$type}.", 'vote_tm');
    $this->outputJsonData(0, array('apptip' => '感谢评价'));
  }
}

