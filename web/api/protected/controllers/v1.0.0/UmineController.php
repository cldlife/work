<?php
class UmineController extends BaseController {
  
  /**
   * @desc 我（TA）的动态列表
   */
  public function actionTimeLine() {
    $uid = $this->getSafeRequest('uid', 0, 'int');
    $page = $this->getSafeRequest('page', 1, 'int');
  
    $user = $this->currentUser;
    if ($uid && $this->currentUser['uid'] != $uid) {
      $user = $this->getUserService()->getUserByUid($uid);
      if (!$user) $pseudoUser = $this->getGameService()->getPseudoUserByUid($uid);//机器人信息
      if ($pseudoUser) $user = $pseudoUser;
      if (!$user) $this->outputJsonData(400, array(
        'apptip' => '该用户不存在或已删除'
      ));
    }
    
    //获取我的帖子
    $list = array();
    $pageSize = 10;
    $threads = $this->getUserMineService()->getMineThreadsByUid($user['uid'], $page, $pageSize);
    if ($threads) {
      foreach ($threads as $item) {
        $thread = $this->getThingService()->getThreadBytid($item['tid'], TRUE);
        if (!$thread['tid'] || $thread['status'] == 1) continue;
      
        $images = array();
        if ($thread['images']) {
          $images['s_url'] = $thread['images'][0]['s_url'];
          $images['b_url'] = $thread['images'][0]['b_url'];
        }
        
        $tmpItem = array();
        $tmpItem['tid'] = $thread['tid'];
        $tmpItem['category'] = $thread['category'];
        $tmpItem['content'] = $thread['content'];
        $tmpItem['images'] = (object) $images;
        $tmpItem['roses'] = $thread['attr_status']['roses'];
        $tmpItem['ctime'] = $thread['created_time'];
        $tmpItem['share_link'] = Utils::getThingThreadLink($thread['tid']);
        $tmpItem['user_info'] = (object) array();
        $list[] = $tmpItem;
        unset($tmpItem);
        unset($images);
      }
    }
    
    $data = array();
    $data['list'] = $list;
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 添加好友
   * @return int friend_status 好友状态，0: 非好友，1: 好友
   */
  public function actionFriending() {
    $uid = $this->getSafeRequest('uid', 0, 'int');
    
    $apptip = '';
    $code = 0;
    $friendStatus = 0;
    if ($uid && $this->currentUser['uid'] != $uid) {
      $user = $this->getUserService()->getUserByUid($uid);
      if (!$user) $pseudoUser = $this->getGameService()->getPseudoUserByUid($uid);//机器人信息
      if ($pseudoUser) {
        $this->outputJsonData($code, array(
          'apptip' => '玫瑰和金币数量不足，请充值哦',
          'friend_status' => 1
        ));
      }
      
      if (!$user) $this->outputJsonData(400, array(
        'apptip' => '该用户不存在或已删除'
      ));
      
      //验证用户关系
      //已好友，则解除好友关系
      $friend = $this->getUserMineService()->getMineFriendByFriendUid($this->currentUser['uid'], $user['uid']);
      if ($friend['uid']) {
        if ($this->getUserMineService()->deleteMineFriend($this->currentUser['uid'], $user['uid'])) {
          $this->getUserMineService()->deleteMineFriend($user['uid'], $this->currentUser['uid']);
        }
        
      //非好友，添加好友
      } else {
        
        //获取对方设置的加好友所需玫瑰数
        $user['status'] = $this->getUserFortuneService()->getUserFortuneStatusByUid($user['uid']);
        if ($user['status']['friending_roses']) {
          
          //根据汇率计算金币数
          $friendingCoins = intval($user['status']['friending_roses'] / $this->getUserFortuneService()->getCoinExchangeRoseRate());
          
          //验证用户财富
          //优先使用玫瑰
          if ($this->currentUser['status']['roses'] >= $user['status']['friending_roses']) {
            if ($this->getUserMineService()->addMineFriend($this->currentUser['uid'], $user['uid'])) {
              $this->getUserMineService()->addMineFriend($user['uid'], $this->currentUser['uid']);
              
              $this->getUserFortuneService()->autoUserFortuneRose($this->currentUser['uid'], 1, -$user['status']['friending_roses']);
              $this->getUserFortuneService()->autoUserFortuneRose($user['uid'], 2, $user['status']['friending_roses']);
              
              $friendStatus = 1;
              $apptip = "共消耗了{$user['status']['friending_roses']}朵玫瑰";
            }
            
          //玫瑰不足则使用金币
          } elseif ($this->currentUser['status']['coins'] >= $friendingCoins) {
            if ($this->getUserMineService()->addMineFriend($this->currentUser['uid'], $user['uid'])) {
              $this->getUserMineService()->addMineFriend($user['uid'], $this->currentUser['uid']);
              
              $this->getUserFortuneService()->autoUserFortuneCoin($this->currentUser['uid'], 17, -$friendingCoins);
              $this->getUserFortuneService()->autoUserFortuneRose($user['uid'], 2, $user['status']['friending_roses']);
            
              $friendStatus = 1;
              $apptip = "共消耗了{$friendingCoins}个金币";
            }
            
          //玫瑰金币不足
          } else {
            $code = 1;
            $apptip = "玫瑰和金币数量不足，请充值哦";
          }
          
        //无需玫瑰
        } else {
          if ($this->getUserMineService()->addMineFriend($this->currentUser['uid'], $user['uid'])) {
            $this->getUserMineService()->addMineFriend($user['uid'], $this->currentUser['uid']);
            $friendStatus = 1;
          }
        }
      }
      
      //加好友成功, 发IM消息
      if ($friendStatus) {
        if ($user['status']['friending_roses']) {
          $this->getMessageService()->sendRcImMessage($this->currentUser, array($user['uid']), 'friending_with_rose', array($user['status']['friending_roses']));
        } else {
          $this->getMessageService()->sendRcImMessage($this->currentUser, array($user['uid']), 'friending');
        }
        $this->getMessageService()->sendRcImMessage($user, array($this->currentUser['uid']), 'friending_hello');
        $apptip = 'TA是你的好友了';
      }
    }
    
    $this->outputJsonData($code, array(
      'apptip' => $apptip,
      'friend_status' => $friendStatus
    ));
  }
  
  /**
   * @desc 拉黑好友
   */
  public function actionBlackFriend() {
    $uid = $this->getSafeRequest('uid', 0, 'int');
    
    //禁止拉黑小主
    $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
    if ($uid == $officialUserInfo['uid']) $this->outputJsonData(0, array(
      'apptip' => '小主不让你拉黑TA哦'
    ));
    
    if ($uid && $this->currentUser['uid'] != $uid) {
      $user = $this->getUserService()->getUserByUid($uid);
      if (!$user) $this->outputJsonData(400, array(
          'apptip' => '该用户不存在或已删除'
      ));
    
      //验证用户关系
      //已好友，则解除好友关系
      $friend = $this->getUserMineService()->getMineFriendByFriendUid($this->currentUser['uid'], $user['uid']);
      if ($friend['uid']) {
        if ($this->getUserMineService()->deleteMineFriend($this->currentUser['uid'], $user['uid'])) {
          $this->getUserMineService()->deleteMineFriend($user['uid'], $this->currentUser['uid']);
          
          //添加好友到黑名单
          $this->getRongCloudService()->addUserMessageBlackList($this->currentUser['uid'], $user['uid']);
          $this->getRongCloudService()->addUserMessageBlackList($user['uid'], $this->currentUser['uid']);
          
          $this->outputJsonData(0, array(
            'apptip' => '已拉黑，你将不再收到TA的消息'
          ));
        }
      }
    }
  
    $this->outputJsonData(1);
  }
  
  /**
   * @desc 我的好友列表
   */
  public function actionFriends() {
    $page = $this->getSafeRequest('page', 1, 'int');
    
    $list = array();
    $pageSize = 20;
    
    //默认好友（小主）
    $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
    $officialUserStatus = $this->getUserFortuneService()->getUserFortuneStatusByUid($officialUserInfo['uid']);
    $officialUserLevel = $this->getUserService()->getUserLevel($officialUserStatus['points']);
    $list[] = array('uid' => $officialUserInfo['uid'], 'nickname' => $officialUserInfo['nickname'], 'avatar' => $officialUserInfo['avatar'], 'level_num' => intval($officialUserLevel['id']));
        
    //获取我的好友列表
    $friends = $this->getUserMineService()->getMineFriendsByUid($this->currentUser['uid'], $page, $pageSize);
    if ($friends) {
      foreach ($friends as $friend) {
        if (!$friend['friend_uid']) continue;
        
        $friendUser = $this->getUserService()->getUserByUid($friend['friend_uid']);
        $friendUserStatus = $this->getUserFortuneService()->getUserFortuneStatusByUid($friendUser['uid']);
        $friendUserLevel = $this->getUserService()->getUserLevel($friendUserStatus['points']);
        
        $tmpList = array();
        $tmpList['uid'] = $friendUser['uid'];
        $tmpList['nickname'] = $friendUser['nickname'];
        $tmpList['avatar'] = $friendUser['avatar'];
        $tmpList['level_num'] = intval($friendUserLevel['id']);
        
        $list[] = $tmpList;
        unset($tmpList);
        unset($friendUser);
        unset($friendUserStatus);
        unset($friendUserLevel);
      }
    }
    
    $data = array();
    $data['list'] = $list;
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 删除我的帖子
   */
  public function actionDelThread() {
    $tid = $this->getSafeRequest('tid', 0, 'int');
  
    //参数验证
    if (!$tid) $this->outputJsonData(1009);
    $thread = $this->getThingService()->getThreadBytid($tid);
    if (!$thread['tid'] || $thread['status'] == 1) {
      $this->outputJsonData(400, array(
        'apptip' => '帖子不存在或已删除'
      ));
    }
    if ($thread['uid'] != $this->currentUser['uid']) {
      $this->outputJsonData(400, array(
        'apptip' => '只能删除自己的帖子'
      ));
    }
  
    //删除帖子
    if ($this->getThingService()->deleteThread($thread['tid'], $thread['attach_hashid'], TRUE)) {
      //删除我的帖子记录
      if ($this->getUserMineService()->deleteMineThread($this->currentUser['uid'], $thread['tid'])) {

        //获得积分
        $ruleId = 0;
        if ($category == 1) $ruleId = 7;
        if ($category == 2) $ruleId = 8;
        $this->getUserFortuneService()->autoUserFortunePoint($this->currentUser['uid'], $ruleId);
      }
    }
  
    $this->outputJsonData(0, array(
      'apptip' => '删除成功'
    ));
  }
  
  /**
   * @desc 删除我的评论
   */
  public function actionDelThreadPost() {
    $tid = $this->getSafeRequest('tid', 0, 'int');
    $pid = $this->getSafeRequest('pid', 0, 'int');
    
    //参数验证
    if (!$tid || !$pid) $this->outputJsonData(1010);
    $post = $this->getThingService()->getThreadPostById($pid, $tid);
    if (!$post || $post['status'] == 1) $this->outputJsonData(1010);
    if ($post['uid'] != $this->currentUser['uid']) {
      $this->outputJsonData(400, array(
        'apptip' => '只能删除自己的评论'
      ));
    }
    
    //删除回复
    $newReplies = 0;
    if ($this->getThingService()->deleteThreadPost($post['pid'], $post['tid'])) {
      $thread = $this->getThingService()->getThreadBytid($post['tid']);
      if ($thread && $thread['attr_status']['replies']) {
        $newReplies = $thread['attr_status']['replies'] - 1;
        $this->getThingService()->inDecreaseThreadStatusByTid($thread['tid'], array(
          array('key' => 'replies', 'value' => 1, 'in_de' => '-')
        ));
      }
      
      //获得积分
      $this->getUserFortuneService()->autoUserFortunePoint($this->currentUser['uid'], 9);
    }
    
    $this->outputJsonData(0, array(
      'replies' => $newReplies,
    ));
  }
  
  /**
   * @desc 屏蔽用户帖子（屏蔽后用户所有帖子不可见）
   */
  public function actionDisappearUser() {
    $uid = $this->getSafeRequest("uid", 0, 'int');
    if (!$uid) $this->outputJsonData(1);

    $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
    if ($uid == $officialUserInfo['uid']) $this->outputJsonData(1, array(
      'apptip' => '小主不让你屏蔽TA哦'
    ));
    $user = $this->getUserService()->getUserByUid($uid);
    if (!$user) $this->outputJsonData(400, array(
      'apptip' => '该用户不存在或已删除'
    ));
  
    //判断是否已屏蔽
    $disappearUser = $this->getUserMineService()->getMineDisappearUsersByUid($this->currentUser['uid'], $user['uid']);
    if ($disappearUser) $this->outputJsonData(1, array(
      'apptip' => '该用户已屏蔽'
    ));
  
    //添加到我的屏蔽用户记录
    $this->getUserMineService()->addMineDisappearUser($this->currentUser['uid'], $user['uid']);
    $this->outputJsonData();
  }
}
?>