<?php
/**
 * @desc UserFortuneService
 * 
 */
class UserFortuneService extends BaseService {
  
  //金币兑换玫瑰的比率，1coin = 5roses
  const coinExchangeRoseRate = 5;
  public static function getCoinExchangeRoseRate () {
    return self::coinExchangeRoseRate;
  }
  //金币兑换鸡蛋的比率，10coin = 1egg
  const coinExchangeEggRate = 10;
  public static function getCoinExchangeEggRate () {
    return self::coinExchangeEggRate;
  }
  
  private function getUserFortuneDAO() {
    return DAOFactory::getInstance()->createUserFortuneDAO();
  }
  
  /**
   * @desc 积分规则
   */
  private static $pointRules = array(
    1 => array('rule_id' => 1, 'desc' => '注册', 'point' => 50),
    2 => array('rule_id' => 2, 'desc' => '每日登录（24小时周期）', 'point' => 30),
    3 => array('rule_id' => 3, 'desc' => '加好友', 'point' => 30),
    4 => array('rule_id' => 4, 'desc' => '发表喊话帖', 'point' => 100),
    5 => array('rule_id' => 5, 'desc' => '发布爆照帖', 'point' => 50),
    6 => array('rule_id' => 6, 'desc' => '评论', 'point' => 10),
    7 => array('rule_id' => 7, 'desc' => '删除喊话帖', 'point' => -100),
    8 => array('rule_id' => 8, 'desc' => '删除爆照帖', 'point' => -50),
    9 => array('rule_id' => 9, 'desc' => '删除评论', 'point' => -10),
    10 => array('rule_id' => 10, 'desc' => '完成一局游戏', 'point' => 30),
    11 => array('rule_id' => 11, 'desc' => '赢得一局游戏', 'point' => 50),
    12 => array('rule_id' => 12, 'desc' => '购买金币5088', 'point' => 2000),
    13 => array('rule_id' => 13, 'desc' => '购买金币23888', 'point' => 50000),
  );
  
  /**
   * @desc 金币规则
   * @see coin为0时，必须调用传入金币数
   */
  private static $coinRules = array(
    //H5应用
    //微信谁是卧底
    1 => array('rule_id' => 1, 'desc' => '我是卧底,输了', 'coin' => -5),
    2 => array('rule_id' => 2, 'desc' => '我是卧底,卧底赢了', 'coin' => 10),
    3 => array('rule_id' => 3, 'desc' => '我是卧底,平民赢', 'coin' => 5),
    4 => array('rule_id' => 4, 'desc' => '我是卧底15秒没有描述', 'coin' => -3),
    5 => array('rule_id' => 5, 'desc' => '我是卧底初始化赠送金币', 'coin' => 200),
    //购买金币
    6 => array('rule_id' => 6, 'desc' => '6元购买金币188', 'coin' => 188),
    7 => array('rule_id' => 7, 'desc' => '18元购买金币888', 'coin' => 888),
    8 => array('rule_id' => 8, 'desc' => '88元购买金币5088', 'coin' => 5088),
    9 => array('rule_id' => 9, 'desc' => '388元购买金币23888', 'coin' => 23888),
      
    //APP
    //连续登陆
    10 => array('rule_id' => 10, 'desc' => '连续登录基础奖励', 'coin' => 50),
    11 => array('rule_id' => 11, 'desc' => '每连续登录一天增加奖励', 'coin' => 10),
    //绑定手机号
    12 => array('rule_id' => 12, 'desc' => '绑定手机号', 'coin' => 50),
    //听歌猜歌名
    13 => array('rule_id' => 13, 'desc' => '贡献题目', 'coin' => 30),
    14 => array('rule_id' => 14, 'desc' => '砸鸡蛋', 'coin' => -10),
    //15 => array('rule_id' => 0, 'desc' => '', 'coin' => 0),
    16 => array('rule_id' => 16, 'desc' => '送玫瑰（玫瑰不足）', 'coin' => -1),
    17 => array('rule_id' => 17, 'desc' => '加好友（玫瑰不足）', 'coin' => 0),
    18 => array('rule_id' => 18, 'desc' => '新注册用户', 'coin' => 100),
    19 => array('rule_id' => 19, 'desc' => '退出游戏扣金币', 'coin' => -10),
    20 => array('rule_id' => 20, 'desc' => '猜对歌名', 'coin' => 20),
    21 => array('rule_id' => 21, 'desc' => '猜错歌名', 'coin' => 0),
    22 => array('rule_id' => 22, 'desc' => '第1个猜对歌名', 'coin' => 30),

    //购买金币
    23 => array('rule_id' => 23, 'desc' => '6元购买金币188', 'coin' => 188),
    24 => array('rule_id' => 24, 'desc' => '18元购买金币888', 'coin' => 888),
    25 => array('rule_id' => 25, 'desc' => '88元购买金币5088', 'coin' => 5088),
    26 => array('rule_id' => 26, 'desc' => '388元购买金币23888', 'coin' => 23888),

    //微信后宫
    27 => array('rule_id' => 27, 'desc' => '抢奴隶', 'coin' => 0),
    28 => array('rule_id' => 28, 'desc' => '被抢做奴隶', 'coin' => 0),
    29 => array('rule_id' => 29, 'desc' => '抢任务金币', 'coin' => 0),
    30 => array('rule_id' => 30, 'desc' => '后宫初始化金币', 'coin' => 200),

    //谁是卧底
    31 => array('rule_id' => 31, 'desc' => '游戏中退出房间', 'coin' => -30),
    32 => array('rule_id' => 32, 'desc' => 'App谁是卧底,卧底胜利', 'coin' => 40),
    33 => array('rule_id' => 33, 'desc' => 'App谁是卧底,卧底失败', 'coin' => -20),
    34 => array('rule_id' => 34, 'desc' => 'App谁是卧底,平民胜利', 'coin' => 30),
    35 => array('rule_id' => 35, 'desc' => 'App谁是卧底,平民失败', 'coin' => -30),
  );

  /**
   * @desc 玫瑰规则
   * @see rose为0时，必须调用传入玫瑰数
   */
  private static $roseRules = array(
    1 => array('rule_id' => 1, 'desc' => '加好友', 'rose' => 0),
    2 => array('rule_id' => 2, 'desc' => '被加好友', 'rose' => 0),
    3 => array('rule_id' => 3, 'desc' => '送玫瑰', 'rose' => -1),
    4 => array('rule_id' => 4, 'desc' => '被送玫瑰', 'rose' => 1),
    5 => array('rule_id' => 5, 'desc' => '被送玫瑰（金币兑换）', 'rose' => 0),
    6 => array('rule_id' => 6, 'desc' => '游戏中送玫瑰', 'rose' => -5),
    7 => array('rule_id' => 7, 'desc' => '游戏中被送玫瑰', 'rose' => 5),
  );
  
  /**
   * @desc 身价规则
   */
  private static $valuesRules = array(
    1 => array('rule_id' => 1, 'desc' => '被抢做奴隶', 'value' => 50),
    2 => array('rule_id' => 2, 'desc' => '初始化身价', 'value' => 100),
  );
  /**
   * @desc 初始化我的状态信息
   */
  public function initUserFortuneStatus($user) {
    $initRes = array();
    if ($user['uid']) {
      $fields = array();
      $fields['is_need_edit'] = 1;
      if ($user['mobile']) {
        $fields['is_binded_mobile'] = 1;
        $fields['is_setted_passwd'] = 1;
      }
      $initRes = $this->getUserFortuneDAO()->insertUserFortuneStatus($user['uid'], $fields);
    }
    return $initRes;
  }
  
  /**
   * @desc 递增/递减我的状态信息
   */
  public function inDecreaseUserFortuneStatusByUid($uid, Array $fields) {
    if ($uid && $fields) {
      return $this->getUserFortuneDAO()->inDecreaseUserFortuneStatusWithUid($uid, $fields);
    }
    return FALSE;
  }
  
  /**
   * @desc 获取我的状态信息
   */
  public function getUserFortuneStatusByUid($uid) {
    $userStatus = array();
    if ($uid) {
      $userStatus = $this->getUserFortuneDAO()->findUserFortuneStatusWithUid($uid);
    }
    return $userStatus;
  }
  
  /**
   * @desc 更新我的状态信息
   */
  public function updateUserFortuneStatusByUid ($uid, $fields) {
    if ($uid) {
      return $this->getUserFortuneDAO()->updateUserFortuneStatusWithUid($uid, $fields);
    }
    return FALSE;
  }
  
  /**
   * @return 按积分规则更新积分
   * @param int $ruleId 规则id
   * @param int $points 积分数（默认0, 根据金币规则决定是否需要传入值）
   * @param string $reason 积分奖励理由
   */
  public function autoUserFortunePoint($uid, $ruleId, $points = 0, $reason = '') {
    $res = array();
    if ($uid && $ruleId && self::$pointRules[$ruleId]) {
      //积分规则
      $pointRule = self::$pointRules[$ruleId];
      $points = $pointRule['point'] ? $pointRule['point'] : $points;
      
      //加减玫瑰检查
      $inDe = '+';
      if ($roses < 0) {
        $inDe = '-';
      
        //获取用户财富状态
        $userFortuneStatus = $this->getUserFortuneStatusByUid($uid);
        if ($userFortuneStatus['points'] < -$points) $roses = -$userFortuneStatus['points'];
      }
      
      //写入记录&更新积分
      if ($points) {
        $res = $this->addUserFortunePoint($uid, array(
          'rule_id' => $ruleId,
          'point' => $points,
          'reason' => $reason ? $reason : ''
        ));
        if ($res) {
          $this->inDecreaseUserFortuneStatusByUid($uid, array(
            array('key' => 'points', 'value' => abs($points), 'in_de' => $inDe)
          ));
        }
      }
    }
    return $res;
  }
  
  /**
   * @return 添加积分记录
   */
  public function addUserFortunePoint($uid, $fields = array()) {
    if ($uid && $fields['rule_id'] && $fields['point']) {
      return $this->getUserFortuneDAO()->insertUserFortunePoint($uid, $fields);
    }
    return array();
  }
  
  /**
   * @return 查找金币记录
   */
  public function getUserFortuneCoinByUidAndRuleId($uid, $ruleId) {
    $userCoin = array();
    if ($uid && $ruleId) {
      $userCoin = $this->getUserFortuneDAO()->findUserFortuneCoinWithUidAndRuleId($uid, $ruleId);
    }
    return $userCoin;
  }
  
  /**
   * @return 按金币规则更新金币
   * @param int $ruleId 规则id
   * @param int $coins 金币数（默认0, 根据金币规则决定是否需要传入值）
   * @param string $reason 金币变更理由
   */
  public function autoUserFortuneCoin($uid, $ruleId, $coins = 0, $reason = '') {
    $res = array();
    if ($uid && $ruleId && self::$coinRules[$ruleId]) {
      //金币规则
      $coinRule = self::$coinRules[$ruleId];

      $coins = $coinRule['coin'] ? $coinRule['coin'] : $coins;

      //加减金币检查
      $inDe = '+';
      if ($coins < 0) {
        $inDe = '-';
        
        //获取用户财富状态
        $userFortuneStatus = $this->getUserFortuneStatusByUid($uid);
        if ($userFortuneStatus['coins'] < -$coins) $coins = -$userFortuneStatus['coins'];
      }
      
      //写入记录&更新金币
      if ($coins) {
        $res = $this->addUserFortuneCoin($uid, array(
          'rule_id' => $ruleId,
          'coin' => $coins,
          'reason' => $reason ? $reason : ''
        ));
        if ($res) {
          $this->inDecreaseUserFortuneStatusByUid($uid, array(
            array('key' => 'coins', 'value' => abs($coins), 'in_de' => $inDe)
          ));
        }
      }
    }
    return $res;
  }
  
  /**
   * @return 添加金币记录
   */
  public function addUserFortuneCoin($uid, $fields = array()) { 
    if ($uid && $fields['rule_id'] && $fields['coin']) { 
      return $this->getUserFortuneDAO()->insertUserFortuneCoin($uid, $fields);
    }
    return array();
  }

  /**
   * @return 按玫瑰规则更新玫瑰
   * @param int $ruleId 规则id
   * @param int $roses 玫瑰数（默认0, 根据玫瑰规则决定是否需要传入值）
   * @param string $reason 玫瑰变更理由
   */
  public function autoUserFortuneRose($uid, $ruleId, $roses = 0, $reason = '') {
    $res = array();
    if ($uid && $ruleId && self::$roseRules[$ruleId]) {
      //玫瑰规则
      $roseRule = self::$roseRules[$ruleId];
      $roses = $roseRule['rose'] ? $roseRule['rose'] : $roses;
      
      //加减玫瑰检查
      $inDe = '+';
      if ($roses < 0) {
        $inDe = '-';
      
        //获取用户财富状态
        $userFortuneStatus = $this->getUserFortuneStatusByUid($uid);
        if ($userFortuneStatus['roses'] < -$roses) $roses = -$userFortuneStatus['roses'];
      }
      
      //写入记录&更新玫瑰
      if ($roses) {
        $res = $this->addUserFortuneRose($uid, array(
          'rule_id' => $ruleId,
          'rose' => $roses,
          'reason' => $reason ? $reason : ''
        ));
        if ($res) {
          $this->inDecreaseUserFortuneStatusByUid($uid, array(
            array('key' => 'roses', 'value' => abs($roses), 'in_de' => $inDe)
          ));
        }
      }
    }
    return $res;
  }
  
  /**
   * @return 添加玫瑰记录
   */
  public function addUserFortuneRose($uid, $fields = array()) { 
    if ($uid && $fields['rule_id'] && $fields['rose']) { 
      return $this->getUserFortuneDAO()->insertUserFortuneRose($uid, $fields);
    }
    return array();
  }

  /**
   * @return 查找身价记录
   */
  public function getUserFortuneValuesByUidAndRuleId($uid, $ruleId) {
    $userValues = array();
    if ($uid && $ruleId) {
      $userValues = $this->getUserFortuneDAO()->findUserFortuneValuesWithUidAndRuleId($uid, $ruleId);
    }
    return $userValues;
  }

  /**
   * @return 按身价规则更新身价
   * @param int $ruleId 规则id
   * @param string $reason 身价增加理由
   */
  public function autoUserFortuneValues($uid, $ruleId, $reason = '') {
    $res = array();

    //身价规则
    $valuesRule = self::$valuesRules[$ruleId];
    if ($uid && $ruleId && $valuesRule) {
  
      //写入记录&更新身价
      $values = $valuesRule['value'];
      $res = $this->addUserFortuneValues($uid, array(
        'rule_id' => $ruleId,
        'values' => $values,
        'reason' => $reason ? $reason : ''
      ));
      if ($res) {
        $this->inDecreaseUserFortuneStatusByUid($uid, array(
          array('key' => 'values', 'value' => $values, 'in_de' => '+')
        ));
      }
    }
    return $res;
  }
  
  /**
   * @return 添加身价记录
   */
  public function addUserFortuneValues($uid, $fields = array()) {
    if ($uid && $fields['rule_id'] && $fields['values']) {
      return $this->getUserFortuneDAO()->insertUserFortuneValues($uid, $fields);
    }
    return array();
  }

}

