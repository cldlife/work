<?php
/**
 * @desc 你画我猜游戏
 * @see
 */
class DrawController extends BaseController {

  /**
   * @desc 评价题目
   */
  public function actionVoteTm () {
    $roomId = $this->getSafeRequest('word_id', 0, 'int');
    $type = $this->getSafeRequest('type', 0, 'int');
    $this->outputJsonData(0);
  }

  //TODO 以下是测试用发消息接口
  //测试结束需删除 run()
  private static $officialUserInfo = array();
  public function run ($ID) {
    self::$officialUserInfo = $this->getUserService()->getOfficialUserInfo();
    $method = 'action' . ucfirst($ID);
    $this->$method();
  }

  private static $gameWords = array(
    '1' => '西瓜',
    '2' => '冬瓜',
    '3' => '南瓜',
    '4' => '哈密瓜',
    '5' => '西瓜',
    '6' => '苹果',
    '7' => '葡萄',
    '8' => '李子',
    '9' => '桃子',
    '10' => '香蕉',
    '11' => '柿子',
    '12' => '草莓',
    '13' => '菠萝',
    '14' => '芒果',
    '15' => '榴莲',
    '16' => '地瓜',
  );
  private static function getGameWords () {
    $totalWords = self::$gameWords;
    uasort($totalWords, function ($a, $b) {
      return mt_rand(0, 2) - 1;
    });
    $words = array();
    $i = 0;
    foreach ($totalWords as $k => $v) {
      $words[$k] = $v;
      $i++;
      if ($i > 3) break;
    }
    return $words;
  }

  /**
   * @desc 换一批
   */
  public function actionChange () {
    $data = array('words' => self::getGameWords());
    $this->outputJsonData(0, $data);
  }

  /**
   * @desc 选好词
   */
  public function actionChoose () {
    $wordId = $this->getSafeRequest('word_id', 0, 'int');
    $this->outputJsonData(0);
  }

  /**
   * @desc 准备
   */
  public function actionShare () {
    $data = array(
      'share_url' => 'http://ww2.sinaimg.cn/mw600/69e6beddjw1fatnunf0cij20k00h9dhb.jpg',
    );
    $this->outputJsonData(0, $data);
  }

  /**
   * @desc 已加入房间
   */
  public function actionJoinroom () {
    $gameMsg = array(
      'roomid' => 1688,
      'type' => 'room',
      'action' => 'join',
      'content' => (object) array(),
      'v' => '2.0.0',
    );
    $res = $this->getMessageService()->sendRcChatRoomGameMessage(self::$officialUserInfo, $gameMsg);
    $this->outputJsonData(0, $res);
  }

  /**
   * @desc 已退出房间
   */
  public function actionQuitroom () {
    $gameMsg = array(
      'roomid' => 1688,
      'type' => 'room',
      'action' => 'quit',
      'content' => (object) array(),
      'v' => '2.0.0',
    );
    $res = $this->getMessageService()->sendRcChatRoomGameMessage(self::$officialUserInfo, $gameMsg);
    $this->outputJsonData(0, $res);
  }

  /**
   * @desc 已准备
   */
  public function actionReadyroom() {
    $gameMsg = array(
      'roomid' => 1688,
      'type' => 'room',
      'action' => 'ready',
      'content' => (object) array(),
      'v' => '2.0.0',
    );
    $res = $this->getMessageService()->sendRcChatRoomGameMessage(self::$officialUserInfo, $gameMsg);
    $this->outputJsonData(0, $res);
  }

  /**
   * @desc 离线（退出游戏）
   */
  public function actionOffline () {
    $gameMsg = array(
      'roomid' => 1688,
      'type' => 'room',
      'action' => 'offline',
      'content' => (object) array(),
      'v' => '2.0.0',
    );
    $res = $this->getMessageService()->sendRcChatRoomGameMessage(self::$officialUserInfo, $gameMsg);
    $this->outputJsonData(0, $res);
  }

  /**
   * @desc 开始游戏（并分配词组）
   */
  public function actionStart () {
    $gameMsg = array(
      'roomid' => 1688,
      'type' => 'game',
      'action' => 'start',
      'v' => '2.0.0',
      'content' => array(
        'game_id' => mt_rand(1, 3),
        'uid' => 'TWZ1',
        'words' => self::getGameWords(),
      ),
    );
    $res = $this->getMessageService()->sendRcChatRoomGameMessage(self::$officialUserInfo, $gameMsg);
    $this->outputJsonData(0, $res);
  }

  /**
   * @desc 开始选词
   */
  public function actionStartchoose () {
    $gameMsg = array(
      'roomid' => 1688,
      'type' => 'game',
      'action' => 'choose',
      'v' => '2.0.0',
      'content' => array(
        'uid' => 'TWZ' . mt_rand(1, 4),
        'words' => self::getGameWords(),
      ),
    );
    $res = $this->getMessageService()->sendRcChatRoomGameMessage(self::$officialUserInfo, $gameMsg);
    $this->outputJsonData(0, $res);
  }

  /**
   * @desc 猜词(app send)
   */
   public function actionGuess () {
    $word = array_slice(self::$gameWords, mt_rand(0, count(self::$gameWords) - 1), 1)[0];
    $gameMsg = array(
      'roomid' => 1688,
      'type' => 'game',
      'action' => 'guess',
      'v' => '2.0.0',
      'content' => array(
        'word' => $word,
      ),
    );
    $res = $this->getMessageService()->sendRcChatRoomGameMessage(self::$officialUserInfo, $gameMsg);
    $this->outputJsonData(0, $res);
  }

  /**
   * @desc 猜词结果
   */
  public function actionGuessresult () {
    $word = array_slice(self::$gameWords, mt_rand(0, count(self::$gameWords) - 1), 1)[0];
    $no = mt_rand(1, 3);
    $gameMsg = array(
      'roomid' => 1688,
      'type' => 'game',
      'action' => 'guess_result',
      'v' => '2.0.0',
      'content' => array(
        'uid' => 'TWZ' . mt_rand(1, 4),
        'right' => mt_rand(0, 1),
        'word' => $word,
        'points' => 12 - (2*$no),
        'no' => $no,
      ),
    );
    $res = $this->getMessageService()->sendRcChatRoomGameMessage(self::$officialUserInfo, $gameMsg);
    $this->outputJsonData(0, $res);
  }

  /**
   * @desc 提示词
   */
  public function actionHint () {
    $type = mt_rand(1, 2);
    $hint = array(1 => '两个字', 2 => '水果')[$type];
    $gameMsg = array(
      'roomid' => 1688,
      'type' => 'game',
      'action' => 'hint',
      'v' => '2.0.0',
      'content' => array(
        'type' => $type,
        'clue' => $hint,
      ),
    );
    $res = $this->getMessageService()->sendRcChatRoomGameMessage(self::$officialUserInfo, $gameMsg);
    $this->outputJsonData(0, $res);
  }

  /**
   * @desc 画图结束
   */
  public function actionDrawover () {
    $gameMsg = array(
      'roomid' => 1688,
      'type' => 'game',
      'action' => 'draw_over',
      'v' => '2.0.0',
      'content' => array(
        'uid' => "TWZ" . mt_rand(1, 4),
        'points' => mt_rand(0, 8),
      ),
    );
    $res = $this->getMessageService()->sendRcChatRoomGameMessage(self::$officialUserInfo, $gameMsg);
    $this->outputJsonData(0, $res);
  }

  /**
   * @desc 一局结束 
   */
  public function actionRoundover () {
    $word = array_slice(self::$gameWords, mt_rand(0, count(self::$gameWords) - 1), 1)[0];
    $gameMsg = array(
      'roomid' => 1688,
      'type' => 'game',
      'action' => 'round_over',
      'v' => '2.0.0',
      'content' => array(
        'word' => $word,
      ),
    );
    $res = $this->getMessageService()->sendRcChatRoomGameMessage(self::$officialUserInfo, $gameMsg);
    $this->outputJsonData(0, $res);
  }

  /**
   * @desc 游戏结束返回结果
   */
  public function actionGameover () {
    $gameMsg = array(
      'roomid' => 1688,
      'type' => 'game',
      'action' => 'game_over',
      'v' => '2.0.0',
      'content' => array(
        'TWZ1' => self::getFakeOver(),
        'TWZ2' => self::getFakeOver(),
        'TWZ3' => self::getFakeOver(),
        'TWZ4' => self::getFakeOver(),
      ),
    );
    $res = $this->getMessageService()->sendRcChatRoomGameMessage(self::$officialUserInfo, $gameMsg);
    $this->outputJsonData(0, $res);
  }
  private static function getFakeOver () {
    $k = mt_rand(0, 2);
    return array(
      array('win' => -1, 'coin_desc' => '-20金币'),
      array('win' => 0, 'coin_desc' => '+10金币'),
      array('win' => 1, 'coin_desc' => '+20金币'),
    )[$k];
  }
}

