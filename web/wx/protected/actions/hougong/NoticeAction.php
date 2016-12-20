<?php
/**
 * @desc 我/TA的后宫
 */
class NoticeAction extends CAction {

  //分享标题
  private static $title = '***的后宫';
  
  //后宫进入好友后宫固定链接
  const FRIEND_HG_LINK = 'hougong/mine/u';
  
  public function run () {
    $this->getController()->defaultURIDoAction = 'notice';
    $method = $this->getController()->getURIDoAction($this, 1);
    $this->$method();
  }
  
  //关系级别 $masterLevel-主人 $slaveLevel-奴隶
  const MASTER_LEVEL_ID = 1;
  const SLAVE_LEVEL_ID = 2;

  private function getNoticeTime ($time) {
    isset($str)?$str:$str='m-d';
    $way = time() - $time;
    $r = '';
    if($way < 60){
        $r = '刚刚';
    }elseif($way >= 60 && $way <3600){
        $r = floor($way/60).'分钟前';
    }elseif($way >=3600 && $way <86400){
        $r = floor($way/3600).'小时前';
    }elseif($way >=86400 && $way <2592000){
        $r = floor($way/86400).'天前';
    }elseif($way >=2592000 && $way <15552000){
        $r = floor($way/2592000).'个月前';
    }else{
        $r = date("$str",$time);
    }
    return $r;
  }
  /**
   * @desc 消息展示
   */
  public function doNoticeIndex () {     
    $show = array();
    $this->getController()->title = str_replace('***', '我', self::$title);
    $this->getController()->render('notice', $show); 
  }

  /**
   * @desc 消息列表ajax
   */
  public function doNoticeNoticeAjax () {
    $page = $this->getController()->getSafeRequest('page', 1, 'GET', 'int');
    $noticeList = $this->getController()->getHougongService()->getHgNoticeListByUid($this->getController()->currentUser['uid'], $page, $pageSize = 10); 
    $list = array();
    if ($noticeList) {
      foreach ($noticeList as $notice) {
        $tmpNoticeList['content'] = $notice['content'];
        $tmpNoticeList['status'] = $notice['status'];
        $tmpNoticeList['time'] = $this->getNoticeTime($notice['created_time']);
        $list[] = $tmpNoticeList;
        unset($tmpNoticeList);
      }
    }
    echo json_encode($list);
    if ($list) {
      //更改状态
      $this->getController()->getHougongService()->updateHgNotice(array(
        'uid' => $this->getController()->currentUser['uid'],
        'status' => 1, 
      ));
    }
  }

  /**
   * @desc 访问展示
   */
  public function doNoticeVisitor () {
    //今日访客量
    $todayCountVisitor = $this->getController()->getHougongService()->getHgVisitorCountByUidAndTime ($this->getController()->currentUser['uid']);  
    //总访问量
    $countVisitor = $this->getController()->getHougongService()->getHgVisitorCountByUid ($this->getController()->currentUser['uid']);
    $show = array();
    $show['todayCountVisitor'] = $todayCountVisitor;
    $show['countVisitor'] = $countVisitor;
    $this->getController()->title = str_replace('***', '我', self::$title);
    $this->getController()->render('visitor', $show); 
  }
  
  /**
   * @desc 访问列表ajax
   */
  public function doNoticeVisitorAjax () {
    $page = $this->getController()->getSafeRequest('page', 1, 'POST', 'int');
    $VisitorList = $this->getController()->getHougongService()->getHgVisitorListByUid($this->getController()->currentUser['uid'], $page, $pageSize = 10);
    $list = array();
    if ($VisitorList) {
      foreach ($VisitorList as $val) {
        $relationMaster = $this->getController()->getHougongService()->getHgRelationMasterByUidAndLevel($val['visitor_uid']);
        $ctUserInfo = $this->getController()->getUserService()->getUserByUid($val['visitor_uid']);
        $marsterctUserInfo = $this->getController()->getUserService()->getUserByUid($relationMaster['relation_uid']);
        //获取奴隶身价
        $userStatus = $this->getController()->getUserFortuneService()->getUserFortuneStatusByUid($val['visitor_uid']);
        
        $VisitorList = array();
        $VisitorList['avatar'] = $ctUserInfo['avatar'];
        $VisitorList['nickname'] = $ctUserInfo['nickname'];
        //isrel 是否可抢为奴隶 0-不可抢 1-可抢
        if ($relationMaster) {
          if ($relationMaster['relation_uid'] == $this->getController()->currentUser['uid']) {
            $VisitorList['mnickname'] = 'TA是你的奴隶';
            $VisitorList['isrel'] = 0;
          } else {
            $VisitorList['mnickname'] = 'TA是' . $marsterctUserInfo['nickname'] . '的奴隶';
            $VisitorList['isrel'] = 1;
            $VisitorList['values'] = $userStatus['values'];
          }
        } else {
          $VisitorList['mnickname'] = 'TA是自由人';
          $VisitorList['isrel'] = 1;
          $VisitorList['values'] = $userStatus['values'];
        }
        $VisitorList['status'] = $val['status'];
        $VisitorList['viuid'] = $val['visitor_uid'];
        $VisitorList['link'] = $this->getController()->getDeUrl(self::FRIEND_HG_LINK . $val['visitor_uid']);
        $VisitorList['time'] = $this->getNoticeTime($val['updated_time']);
        unset($val);
        $list[] = $VisitorList;
      }
    }
    echo json_encode($list);
    if ($list) {
      //更改状态
      $this->getController()->getHougongService()->updateHgVisitorStatus(array(
        'uid' => $this->getController()->currentUser['uid'],
        'status' => 1, 
      ));
    }
  }

}