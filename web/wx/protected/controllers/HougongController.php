<?php
/**
 * @desc 后宫
 * @desc Chu
 */
Yii::import('application.actions.hougong.*');
class HougongController extends BaseController {

  public $defaultAction = 'index';
  
  public $layout = 'main_hougong';
  
  public $defaultURIDoAction = '';
  
  //当前用户信息
  public $isMaster = '';
  
  //当前访问量是否有红点
  public $isVisitor = '';

  //当前消息是否有红点
  public $isNotice = '';
 
  //当前微信jssdk
  public $weixinJssdkConfig = '';
 
  //分享链接
  private static $mineLink = 'hougong/mine/u';
 
  //分享标题
  private static $mineTitle = '***邀请你一起来玩后宫！来互相伤害啊!';
  
  //分享链接
  public $shareLink = '';

  //分享
  public $shareTitle = '';
  //关系级别 $masterLevel-主人 $slaveLevel-奴隶
  const MASTER_LEVEL_ID = 1;
  const SLAVE_LEVEL_ID = 2;
  
  //后宫进入好友后宫固定链接
  const FRIEND_HG_LINK = 'hougong/mine/u';
 
  //过滤器（初始化）
  public function filters() {
    if ($this->checkRequestUri('hougong/weixin/api')) return FALSE;
    
    //BaseController filters
    parent::filters();
    
    $uid = $this->getSafeRequest('uid', 0, 'GET', 'int');
    $share = $this->getSafeRequest('share', 0, 'GET', 'int');
    if ($uid) {
      //好友通过分享链接访问
      if ($uid != $this->currentUser['uid']) {
        if ($share) {
          $relationMaster = $this->getHougongService()->getHgRelationMasterByUidAndLevel($this->currentUser['uid']);     
          //不存在主人uid
          if (!$relationMaster) {
          
            if ($this->getHougongService()->addHgRelation (array(
              'uid' => $uid,
              'relation_uid' => $this->currentUser['uid'],
              'level' => self::SLAVE_LEVEL_ID
              )
            )) {
              if (
                $this->getHougongService()->addHgRelation (array(
                  'uid' => $this->currentUser['uid'],
                  'relation_uid' => $uid,
                  'level' => self::MASTER_LEVEL_ID
                  )
                )) {
                  $this->isMaster = 'TRUE';
                  //写入消息表
                  $this->getHougongService()->addHgNotice (array(
                    'uid' => $uid,
                    'content' => '<string><a href=' . $this->getDeUrl(self::FRIEND_HG_LINK .$this->currentUser['uid']) .'>【' . $this->currentUser['nickname'] . '】</a></string>是你的人了!'
                  ));
              }
            }      
          } 
        }

        $todayVisitor = $this->getHougongService()->getHgVisitorByUidAndViuid($uid, $this->currentUser['uid']);
        //今日未访问
        if (!$todayVisitor) {
          $this->getHougongService()->addHgVisitor (array(
            'uid' => $uid,
            'visitor_uid' => $this->currentUser['uid']
            )
          );
        } else {
          $this->getHougongService()->updateHgVisitor (array(
            'uid' => $uid,
            'visitor_uid' => $this->currentUser['uid'],
            'status' => 0,
            )
          );
        }
      } 
    }
    //TODO

    //查询是否有新消息
    $isVisitor = $this->getHougongService()->getHgVisitorListByUid($this->currentUser['uid']);
    $this->isVisitor = $isVisitor[0]['status'];
    $isNotice = $this->getHougongService()->getHgNoticeListByUid($this->currentUser['uid']);
    $this->isNotice = $isNotice[0]['status'];
    $weixinConfig = Yii::app()->params['weixinConfig']['houGong'];
    $this->weixinJssdkConfig = $this->getCommonService()->getJssdkConfigByAppid($weixinConfig['WEIXIN_APP_ID'], $weixinConfig['WEIXIN_APP_SECRET']);
    $this->shareLink = self::$mineLink.$this->currentUser['uid'];
    $this->shareTitle = str_replace('***', $this->currentUser['nickname'], self::$mineTitle);
  }
  
  /**
   * @desc Actions Map
   */
  public function actions () {
    return array(
      'index' => 'IndexAction',
      'mine' => 'MineAction',
      'notice' => 'NoticeAction',
      'weixin' => 'WxAction',
    );
  }
}
