<?php
/**
 * @desc 我/TA的后宫
 */
class MineAction extends CAction {
  
  public function run () {
    $this->getController()->defaultURIDoAction = 'mine';
    $method = $this->getController()->getURIDoAction($this, 1);
    $this->$method();
  }

  //随机任务
  protected static $getRandTask = array ( 
    '啪啪啪',
    '嘿咻',
    '刺杀皇帝',
    '健身',
    '洗澡',
    '洗澡',
    '嫖',
    '相亲',
    '美容',
    '挖煤',
    '叫爸爸'
  );
 
  //标题
  private static $title = '***的后宫';
  
  //跳转二维码页面
  private static $msgLink = 'http://f.shiyi11.com/static/wx/hougong/qrcode.html';
  
  //跳转帮助页面  
  private static $helpLink = 'http://f.shiyi11.com/static/wx/hougong/help.html';

  //关系级别 $masterLevel-主人 $slaveLevel-奴隶
  const MASTER_LEVEL_ID = 1;
  const SLAVE_LEVEL_ID = 2;
  
  //后宫进入好友后宫固定链接
  const FRIEND_HG_LINK = 'hougong/mine/u';
  
  //金币以及身价规则
  const GRAB_SLAVE_COINS = 27;
  const ROBBED_SLAVE_COINS = 28;
  const GRAB_TASK_COINS = 29;
  const ROBBED_SLAVE_VALUES = 1;
  const TASK_OVER_TIME = 6;
  
  /**
   * @获取奴隶任务以及状态列表
   */
  private function getRelationSlaveList ($uid, $page, $pageSize) {
    //奴隶列表
    $relationSlaveList = $this->getController()->getHougongService()->getHgRelationSlaveListByUidAndLevel($uid, $page, $pageSize); 
    $list = array();
      if ($relationSlaveList) {
        foreach ($relationSlaveList as $val) {
          $ctUserInfo = $this->getController()->getUserService()->getUserByUid($val['relation_uid']);
          
          //获取奴隶身价
          $userStatus = $this->getController()->getUserFortuneService()->getUserFortuneStatusByUid($val['relation_uid']);
          $slaveList = array();
          $slaveList['nickname'] = $ctUserInfo['nickname'];
          $slaveList['avatar'] = $ctUserInfo['avatar'];
          $slaveList['values'] = $userStatus['values'];
          $slaveList['link'] = $this->getController()->getDeUrl(self::FRIEND_HG_LINK . $ctUserInfo['uid']);
          $slaveList['uid'] = $ctUserInfo['uid'];
          $slaveList['status'] = 0;
          
          //获取奴隶所执行任务(这一部分之后需要重写)
          $task = $this->getController()->getHougongService()->getHgTaskByUid($val['relation_uid']);
          if ($task) {
            
            //任务中，计算任务派发剩余时间
            if ($task['status'] == 1) {
              $time = $task['created_time'] + self::TASK_OVER_TIME;
              $now = strtotime(date('Y-m-d H:i:s'));
              $remainTime = $time - $now;
              if ($remainTime <= 0) {
                if ($this->getController()->getHougongService()->updateHgTask(array(
                  'uid' => $task['uid'],
                  'id' => $task['id'],
                  'status' => 2
                ))) $task['status'] == 2;
              }
            }
            
            //任务状态 0-休息中 1-派发中 获取金币总数，派发任务 2-任务完成 获取剩余金币数
            if ($task['status'] == 1) {
              $slaveList['remainTime'] = $remainTime;
              $slaveList['total_coins'] = $task['total_coins']; 
              $slaveList['task'] = '正在' .  json_decode($task['task'], TRUE) . '.....';
            } else if ($task['status'] == 2) {
              $slaveList['remain_coins'] = $task['remain_coins'];
            } else if ($task['status'] == 0) {
              $slaveList['coins_status'] = $task['coins_status'];
            }
            $slaveList['id'] = $task['id'];
            $slaveList['status'] = $task['status'];
          }
          
          $list[] = $slaveList;
          unset($val);
        }
      } 
      return $list;
  }

  /**
   * @desc后宫奴隶任务展示
   */
  public function doMineIndex (){
    $uid = $this->getController()->getSafeRequest('uid', 0, 'GET', 'int');
    $share = $this->getController()->getSafeRequest('share', 0, 'GET', 'int');
    
    $ctUserInfo = $this->getController()->getUserService()->getUserByUid($uid);
    if ($ctUserInfo) {
      $relationSlaveList = $this->getRelationSlaveList($ctUserInfo['uid'], 1,5);
      //主人信息
      $relationMaster = $this->getController()->getHougongService()->getHgRelationMasterByUidAndLevel($ctUserInfo['uid']); 
      $marsterCtUserInfo = $this->getController()->getUserService()->getUserByUid($relationMaster['relation_uid']);

      $show = array ();
      $show['uid'] = $ctUserInfo['uid'];
      $show['nickname'] = $ctUserInfo['nickname'];//用户昵称
      $show['avatar'] = $ctUserInfo['avatar'];//用户头像
      $show['values'] = $userStatus['values'];//用户身价
      $show['muid'] = $marsterCtUserInfo['uid'];
      $show['mnickname'] = $marsterCtUserInfo['nickname'];//主人昵称
      $show['mavatar'] = $marsterCtUserInfo['avatar'];//主人头像
      $show['mlink'] = $this->getController()->getDeUrl(self::FRIEND_HG_LINK . $marsterCtUserInfo['uid']);
      $randTask = self::$getRandTask;
      $show['randTask'] = json_encode($randTask);
      $show['helpLink'] = self::$helpLink;
      if ($ctUserInfo['uid'] == $this->getController()->currentUser['uid']) {
        try {
          $weixinConfig = Yii::app()->params['weixinConfig']['houGong'];
          $token = $this->getController()->getCommonService()->getWxAccesstoken($weixinConfig['WEIXIN_APP_ID'], $weixinConfig['WEIXIN_APP_SECRET']);
          if ($token) {
            $userWxOpenid = $this->getController()->getUserService()->getUserWeixinOpenidByUidAndAppid($this->getController()->currentUser['uid'], $weixinConfig['WEIXIN_APP_ID']);
            $this->getController()->getWeixinService()->setAccessToken($token);
            $userWxInfo = $this->getController()->getWeixinService()->getUserInfo(array('openid' => $userWxOpenid['openid']));
          }
        } catch (Exception $e) {}
        
        //验证是否已关注公众号 TODO
        if ($userWxInfo['subscribe']) {
          $this->getController()->title = str_replace('***', '我', self::$title);
          $show['values'] = $this->getController()->currentUser['status']['values'];//用户身价
          $this->getController()->render('mine', $show);
        } else {
          $show['targetUrl'] = self::$msgLink;
          $this->getController()->render('/redirect/index', $show);
        }
      } else {
        $show['share'] = $share;
        $show['isMaster'] = $this->getController()->isMaster;        
        //获取身价
        $userStatus = $this->getController()->getUserFortuneService()->getUserFortuneStatusByUid($ctUserInfo['uid']);
        $show['values'] = $userStatus['values'];//用户身价
        $this->getController()->title = str_replace('***', 'TA', self::$title);
        $this->getController()->render('his', $show);   
      }
    }
  }

  /**
   * @desc 奴隶列表ajax数据
   */
   public function doMineSlaveListajax (){
    $uid = $this->getController()->getSafeRequest('uid', 0, 'POST', 'int');
    $ctUserInfo = $this->getController()->getUserService()->getUserByUid($uid);
    if ($ctUserInfo) {
      $page = $this->getController()->getSafeRequest('page', 1, 'POST', 'int');
      $relationSlaveList = $this->getRelationSlaveList($ctUserInfo['uid'], $page, $pageSize = 5);
      echo json_encode($relationSlaveList);
    }
  }

  /**
   * @desc 抢奴隶
   */
  public function doMineRelation () {
    $ruid = $this->getController()->getSafeRequest('uid', 0, 'POST', 'int');
    if ($ruid) {
      //自己不可以抢自己
      if ($ruid == $this->getController()->currentUser['uid']) {
        $this->getController()->outputJsonData(5);
        exit;
      }
      $relationMaster = $this->getController()->getHougongService()->getHgRelationMasterByUidAndLevel($ruid);
      //判断不是自己的奴隶
      if ($relationMaster['relation_uid'] != $this->getController()->currentUser['uid']) {

        //获取奴隶信息状态
        $ruserStatus = $this->getController()->getUserFortuneService()->getUserFortuneStatusByUid($ruid);
        //获取自己信息状态
        $userStatus = $this->getController()->getUserFortuneService()->getUserFortuneStatusByUid($this->getController()->currentUser['uid']);

        if ($userStatus && $ruserStatus) {
          if ($ruserStatus['values'] == 0) {
            $this->getController()->outputJsonData(5);
            exit;
          }
          //当前用户金币要大于被抢人身价
          if ($userStatus['coins'] >= $ruserStatus['values']) {
            //当前用户扣除抢走奴隶身价金币
            if ($this->getController()->getUserFortuneService()->autoUserFortuneCoin($this->getController()->currentUser['uid'], self::GRAB_SLAVE_COINS, '-' . $ruserStatus['values'])) {

              //被抢用户获得50％的金币
              $rcoin = $ruserStatus['values'] * 0.5;

              if ($this->getController()->getUserFortuneService()->autoUserFortuneCoin($ruid, self::ROBBED_SLAVE_COINS, $rcoin)) {

                //存在主人uid
                if ($relationMaster) {
                  //解除之前主人奴隶关系
                    $this->getController()->getHougongService()->deleteHgRelation($relationMaster['relation_uid'], $ruid);
                    $this->getController()->getHougongService()->deleteHgRelation($ruid, $relationMaster['relation_uid']);
                } 
                
                //添加新的关系 
                if ($this->getController()->getHougongService()->addHgRelation (array(
                  'uid' => $ruid,
                  'relation_uid' => $this->getController()->currentUser['uid'],
                  'level' =>self::MASTER_LEVEL_ID
                  )
                )) {
                  if ($this->getController()->getHougongService()->addHgRelation (array(
                      'uid' => $this->getController()->currentUser['uid'],
                      'relation_uid' => $ruid,
                      'level' => self::SLAVE_LEVEL_ID
                      ) 
                  )) {    

                    //更新身价
                    if ($this->getController()->getUserFortuneService()->autoUserFortuneValues($ruid, self::ROBBED_SLAVE_VALUES)) {
                      
                      $rvalues = $ruserStatus['values'] + 50;

                      //写入消息表
                      $this->getController()->getHougongService()->addHgNotice (array(
                        'uid' => $ruid,
                        'content' => '我被<string><a href=' . $this->getController()->getDeUrl(self::FRIEND_HG_LINK . $this->getController()->currentUser['uid']) .'>【'. $this->getController()->currentUser['nickname'] . '】</a></string>' .'抢走了,获得' . $rcoin . '金币,身价涨至' . $rvalues 
                      ));
                      if ($relationMaster['relation_uid']) {
                        $ctUserInfo = $this->getController()->getUserService()->getUserByUid($ruid);
                        //写入消息表
                        $this->getController()->getHougongService()->addHgNotice (array(
                          'uid' => $relationMaster['relation_uid'],
                          'content' => '你的奴隶<string><a href=' . $this->getController()->getDeUrl(self::FRIEND_HG_LINK.$ctUserInfo['uid']) .'>【' . $ctUserInfo['nickname'] . '】</a></string>被<string><a href='. $this->getController()->getDeUrl(self::FRIEND_HG_LINK . $this->getController()->currentUser['uid']) .'>【' . $this->getController()->currentUser['nickname'] . '】</a></string>抢走了'
                        ));
                      }
                      //添加成功
                      $this->getController()->outputJsonData(1, array(
                        'rvalues' => $rvalues,
                      ));
                    }     
                  }                  
                } else {
                  $this->getController()->outputJsonData(2);
                }

              }
            }             
          } else {
            // '您的金币不足';
           $this->getController()->outputJsonData(0);//金币不足
          }
        } else {
          $this->getController()->outputJsonData(3);//奴隶信息状态不存在
        }
      } else {
       $this->getController()->outputJsonData(4);//是自己的奴隶不能抢
      }
    }
  }

  /**
   * @desc 派发任务
   * @desc status 0-休息中 1-任务中 3-任务完成
   */
  public function doMineTask () {
     $ruid = $this->getController()->getSafeRequest('ruid', 0, 'POST');
     $task = $this->getController()->getSafeRequest('info', '', 'POST', 'string');
     if ($ruid && $task) {

      //只限其主人才能派发任务
      $relationMaster = $this->getController()->getHougongService()->getHgRelationMasterByUidAndLevel($ruid);
      if ($relationMaster['relation_uid'] == $this->getController()->currentUser['uid']) {
        
        //判断奴隶是否在休息中
        $taskStatus = $this->getController()->getHougongService()->getHgTaskByUid($ruid);
        if (!$taskStatus || $taskStatus['status'] == 0) {
          //获取奴隶信息状态
          $ruserStatus = $this->getController()->getUserFortuneService()->getUserFortuneStatusByUid($ruid);
          $rvalues = $ruserStatus['values'] ? $ruserStatus['values'] * 0.2 : 10;
          $taskInfo = $this->getController()->getHougongService()->addHgTask(array(
            'uid' => $ruid,
            'task' => $task,
            'total_coins' => $rvalues,
            'remain_coins' => $rvalues,
            'status' => 1,
          ));
          if ($taskInfo) {
            //写入消息表
            $this->getController()->getHougongService()->addHgNotice(array(
              'uid' => $ruid,
              'content' => '我被主人<string><a href=' . $this->getController()->getDeUrl(self::FRIEND_HG_LINK .$this->getController()->currentUser['uid']) .'>【' . $this->getController()->currentUser['nickname'] . '】</a></string>派去' . $task .'!'
            ));
            
            //任务派发剩余时间
            $time = time() + self::TASK_OVER_TIME;
            $now = strtotime(date('H:i:s'));
            $remainTime = $time - $now;
            
            $taskList = array();
            $taskList['task'] = '正在'. json_decode($taskInfo['task'], TRUE) . '....';
            $taskList['total_coins'] = $taskInfo['total_coins'];
            $taskList['remainTime'] = $remainTime;
            echo json_encode($taskList); 
          }
        
        } else {
          //echo '对不起,上一个任务还在进行中,不可以进行下一个任务哟';
        }

      } else {
        $this->getController()->outputJsonData(0);
        //echo '对不起,您没有这个权限！';
      }
    }
  }

  /**
   * @desc 任务完成抢金币记录
   * @desc status 0-休息中 1-任务中 3-任务完成
   */
  public function doMinegetcoinUsers () {
    //对应的任务id
    $taskid = $this->getController()->getSafeRequest('taskid', 0, 'GET', 'int');
    $uid = $this->getController()->getSafeRequest('uid', 0, 'GET', 'int');
    //奴隶uid
    $suid = $this->getController()->getSafeRequest('suid', 0, 'GET', 'int');
    if ($uid && $taskid) {
      //奴隶之前的任务在完成后才可以被抢
      $taskStatus = $this->getController()->getHougongService()->getHgTaskByTaskId($taskid, $suid);

      //任务状态为已完成剩余金币大于0
      if ($taskStatus['status'] == 2 && $taskStatus['remain_coins'] > 0) {
        //自己抢规则
        if ($uid == $this->getController()->currentUser['uid']) {
          $remainCoins = $taskStatus['remain_coins'] - $taskStatus['total_coins'];
          //更新剩余金币
          if ($this->getController()->getHougongService()->updateHgTask(array (
                'uid' => $suid,
                'id' => $taskid,
                'status' => 0,
                'remain_coins' => $remainCoins
          ))) {
            if ($this->getController()->getUserFortuneService()->autoUserFortuneCoin($this->getController()->currentUser['uid'], self::GRAB_TASK_COINS, $taskStatus['remain_coins'])) {
              $list = array();
              $list['remain_coins'] = $taskStatus['remain_coins']; 
              echo json_encode($list); 
              }
          }
        
        //别人抢金币规则
        } else {
          //判断是否有抢过
          $taskGetcoinUsers = $this->getController()->getHougongService()->getHgTaskGetcoinUsersByTaskidAndUid($taskid, $this->getController()->currentUser['uid']);
          if (!$taskGetcoinUsers) {

            $totalCoins = $taskStatus['total_coins'] * 0.1;
            $remainCoins = $taskStatus['remain_coins'] - $totalCoins;

            if ($remainCoins == 0) {
              //金币状态改为被抢完
              if ($this->getController()->getHougongService()->updateHgTask(array (
                'uid' => $suid,
                'id' => $taskid,
                'remain_coins' => $remainCoins,
                'status' => 0,
                'coins_status' => 1
              ))) {
                $ctUserInfo = $this->getController()->getUserService()->getUserByUid($ruid);
                //写入消息表
                $this->getController()->getHougongService()->addHgNotice (array(
                  'uid' => $uid,
                  'content' => '你的奴隶<string><a href=' . $this->getController()->getDeUrl(self::FRIEND_HG_LINK .$ctUserInfo['uid']) .'>【' . $ctUserInfo['nickname'] . '】</a></string>' . '产生的任务金币被偷完了!'
                ));
              }

            } else {
              //更新剩余金币
              $this->getController()->getHougongService()->updateHgTask(array (
                'uid' => $suid,
                'id' => $taskid,
                'remain_coins' => $remainCoins
              ));
            }
            //写入抢金币记录表
            if ($this->getController()->getHougongService()->addHgTaskGetcoinUsers(array(
              'uid' => $this->getController()->currentUser['uid'],
              'task_id' => $taskid
            ))) {
              
              if ($this->getController()->getUserFortuneService()->autoUserFortuneCoin($this->getController()->currentUser['uid'], self::GRAB_TASK_COINS, $totalCoins)) {
                $list = array();
                $list['rob_coins'] = $totalCoins;
                $list['remain_coins'] = $remainCoins;
                if ($list) {
                  echo json_encode($list);
                }
                //写入消息表
                $this->getController()->getHougongService()->addHgNotice (array(
                  'uid' => $uid,
                  'content' => '<string><a href=' . $this->getController()->getDeUrl(self::FRIEND_HG_LINK .$this->getController()->currentUser['uid']) .'>【' . $this->getController()->currentUser['nickname'] . '】</a></string>' . '从我的后宫偷走了' . $totalCoins .'金币'
                ));
              }
            }

          } else {
           echo json_encode(2);
           // echo '您已经抢过了，给主人留一点吧';
          }
        }

      } else {
        echo json_encode(0);
        //echo '任务还未完成';
      }
    }
  }
}
