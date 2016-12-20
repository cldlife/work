<?php

/**
 * 我拍你画 我的/ta的主页
 * chu
 */
class HomePageAction extends CAction {

  public function run () {
    $this->getController()->defaultURIDoAction = 'homepage';
    $method = $this->getController()->getURIDoAction($this, 1);
    $this->$method();
  }

  private function geListTime ($time) {
    isset($str) ? $str : $str = 'm-d';
    $way = time() - $time;
    $r = '';
    if ($way < 60) {
      $r = '刚刚';
    } elseif ($way >= 60 && $way < 3600) {
      $r = floor($way / 60) . '分钟前';
    } elseif ($way >= 3600 && $way < 86400) {
      $r = floor($way / 3600) . '小时前';
    } elseif ($way >= 86400 && $way < 2592000) {
      $r = floor($way / 86400) . '天前';
    } elseif ($way >= 2592000 && $way < 15552000) {
      $r = floor($way / 2592000) . '个月前';
    } else {
      $r = date("$str", $time);
    }
    return $r;
  }

  /**
   * 我/Ta主页模版渲染
   */
  public function doHomePageSelfPage () {
    $ppid = $this->getController()->getSafeRequest('ppid', 0, 'GET', 'int');
    if ($ppid) {
      $paints = $this->getController()->getWebappService()->getPicturesPaintsByppid($ppid);
      if ($paints['type'] == 1) $paints  =  $this->getController()->getWebappService()->getPicturesPaintsByppid($paints['relation_ppid']);

      $show = array();
      $picturesPaintsStatus = $this->getController()->getWebappService()->getPicturesPaintsStatusByPPid($paints['pp_id']);
      
      $show['file_uri'] = WEB_QW_APP_FILE_DOMAIN . $paints['file_uri'] . APP_DYNAMIC_FILE_RULE_750;
      $show['ppid'] = $paints['pp_id'];
      $show['paintings'] = ($picturesPaintsStatus['paintings']) ? $picturesPaintsStatus['paintings'] : 0;
      // 自己的主页
      if ($this->getController()->currentUser['uid'] == $paints['uid']) {
        $show['avatar'] = $this->getController()->currentUser['avatar'];
        $show['nickname'] = $this->getController()->currentUser['nickname'];
        $show['collectTitle'] = '我收到的画作';
        $this->getController()->render(FB_END_TPL_PATH . "/app/painting/pictures/homepage/selfpage.tpl", $show);
      } else {
        $ctUserInfo = $this->getController()->getUserService()->getUserByUid($paints['uid']);
        $show['avatar'] = $ctUserInfo['avatar'];
        $show['nickname'] = $ctUserInfo['nickname'];
        $show['collectTitle'] = 'TA收到的画作';
        $this->getController()->render(FB_END_TPL_PATH . "/app/painting/pictures/homepage/hispage.tpl", $show);
      }
    }
  }

  /**
   * 我/他主页接口
   */
  public function doHomePageSelfList () {
    $ppid = $this->getController()->getSafeRequest('ppid', 0, 'POST', 'int');
    $page = $this->getController()->getSafeRequest('page', 0, 'POST', 'int');
    if ($ppid) {
      $paints = $this->getController()->getWebappService()->getPicturesPaintsByType(1, $page, $pageSize = 10, $ppid);
      
      $list = array();
      if ($paints) {
        foreach ($paints as $val) {
          $paintsStatus = $this->getController()->getWebappService()->getPicturesPaintsStatusByPPid($val['pp_id']);
          // 用户信息
          $ctUserInfo = $this->getController()->getUserService()->getUserByUid($val['uid']);

          $voteUser = $this->getController()->getThingService()->getThreadVoteUsersByTid($val['relation_tid'], 1, 6);
          $PaintsByppid = $this->getController()->getWebappService()->getPicturesPaintsByppid($ppid);
          $paintsList = array();
          $paintsList['voavatar'] = array();
          $paintsList['is_votes'] = 0;
          foreach ($voteUser as $k => $vote) {
            $voteUserCtUserInfo = $this->getController()->getUserService()->getUserByUid($vote['uid']);
            $paintsList['voavatar'][$k]['uid'] = $voteUserCtUserInfo['uid'];
            $paintsList['voavatar'][$k]['avatar'] = $voteUserCtUserInfo['avatar'];
            if ($vote['uid'] == $this->getController()->currentUser['uid']) {
              $paintsList['is_votes'] = 1;
            } else {
              $paintsList['is_votes'] = 0;
            }
          }
          
          if ($PaintsByppid['uid'] == $this->getController()->currentUser['uid']) {
            $paintsList['is_mine'] = 1;
          } else {
            $paintsList['is_mine'] = 0;
          }

          $time = $this->geListTime($val['created_time']);
          $paintsList['pp_id'] = $val['pp_id'];
          $paintsList['uid'] = $val['uid'];
          $paintsList['votes'] = $paintsStatus['votes'];
          $paintsList['avatar'] = $ctUserInfo['avatar'];
          $paintsList['nickname'] = $ctUserInfo['nickname'];
          $paintsList['time'] = $time;
          $paintsList['file_uri'] = WEB_QW_APP_FILE_DOMAIN . $val['file_uri'] . APP_DYNAMIC_FILE_RULE_750;
          $paintsList['rfile_uri'] = WEB_QW_APP_FILE_DOMAIN . $PaintsByppid['file_uri'] . APP_DYNAMIC_FILE_RULE_360x360;
          $list[] = $paintsList;
          unset($paintsList);
        }
      }
      echo json_encode($list);
    }
  
  }
}