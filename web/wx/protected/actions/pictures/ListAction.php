<?php
/**
 * 我拍你画 最新/热门/求画列表
 * Chu
 */
class ListAction extends CAction {

  public function run () {
    $this->getController()->defaultURIDoAction = 'list';
    $method = $this->getController()->getURIDoAction($this, 1);
    $this->$method();
  }

  private function getListTime ($time) {
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

  private function getListData ($data) {
    $chiNum = array(
      '零',
      '一',
      '二',
      '三',
      '四',
      '五',
      '六',
      '七',
      '八',
      '九',
      '十' 
    );
    $time = date("Y-m-d", $data);
    // $str = ereg_replace('[^0-9]', $time);
    $newTime = "";
    for ($i = 0; $i < strlen($time); $i++) {
      if (isset($chiNum[$time[$i]])) {
        if ($i == 5 || $i == 8) {
          if ($time[$i] == 1) $newTime .= $chiNum[10];
          else if ($time[$i] == 0) {
            $newTime .= $chiNum[$time[$i]];
          } else {
            $newTime .= $chiNum[$time[$i]] . $chiNum[10];
          }
        } else if (($i == 6 || $i == 9) && $time[$i] == 0) {
        
        } else {
          $newTime .= $chiNum[$time[$i]];
        }
      }
      if ($i == 4) $newTime .= "年";
      else if ($i == 6) $newTime .= "月";
      else if ($i == 9) $newTime .= "日";
    }
    return $newTime;
  }

  /**
   * 根据type 获取照片/画作主体表list
   */
  public function getPaintsList ($type, $page, $pageSize) {
    $paintsList = $this->getController()->getWebappService()->getPicturesPaintsByType($type, $page, $pageSize, '');
    return ($paintsList) ? $paintsList : array();
  }

  /**
   * 最新页面渲染
   */
  public function doListHomepage () {
    $this->getController()->render(FB_END_TPL_PATH . '/app/painting/pictures/list/homepage.tpl');
  }

  /**
   * 最热页面渲染
   */
  public function doListHotHomepage () {
    $this->getController()->render(FB_END_TPL_PATH . '/app/painting/pictures/list/hothomepage.tpl');
  }

  /**
   * 最新&最热列表
   * is_new 最新 is_host 最热
   */
  public function doListHomepagelist () {
    $page = $this->getController()->getSafeRequest('page', 0, 'POST', 'int');
    // 1 is_hot 最热 2 is_new 最新
    $step = $this->getController()->getSafeRequest('step', 0, 'POST', 'int');
    $pageSize = 20;
    
    if ($step == 1) {
      $paintsStatus = $this->getController()->getWebappService()->getPicturesPaintsStatusByType(1, 'is_hot', $page, $pageSize);
    } elseif ($step == 2) {
      $paintsStatus = $this->getController()->getWebappService()->getPicturesPaintsStatusByType(1, 'is_new', $page, $pageSize);
    }
    $list = array();
    if ( $paintsStatus ) {
      foreach ( $paintsStatus as $val ) {
        $paintsStatusList = array();
        $paints = $this->getController()->getWebappService()->getPicturesPaintsByppid($val['pp_id']);
        
        // 用户信息
        $ctUserInfo = $this->getController()->getUserService()->getUserByUid($paints['uid']);
        $rpaints = $this->getController()->getWebappService()->getPicturesPaintsByppid($paints['relation_ppid']);
        $voteUser = $this->getController()->getThingService()->getThreadVoteUsersByTid($paints['relation_tid'], 1, 6);
        
        $paintsStatusList['voavatar'] = array();
        $paintsStatusList['is_votes'] = 0;
        foreach ($voteUser as $k => $vote) {
          $voteUserCtUserInfo = $this->getController()->getUserService()->getUserByUid($vote['uid']);
          $paintsStatusList['voavatar'][$k]['uid'] = $voteUserCtUserInfo['uid'];
          $paintsStatusList['voavatar'][$k]['avatar'] = $voteUserCtUserInfo['avatar'];
          if ($vote['uid'] == $this->getController()->currentUser['uid']) {
            $paintsStatusList['is_votes'] = 1;
          } else {
            $paintsStatusList['is_votes'] = 0;
          }
        }
        if ($rpaints['uid'] == $this->getController()->currentUser['uid']) {
          $paintsStatusList['is_mine'] = 1;
        } else {
          $paintsStatusList['is_mine'] = 0;
        }
        $time = $this->getListTime($val['created_time']);
        $paintsStatusList['uid'] = $paints['uid'];
        $paintsStatusList['time'] = $time;
        $paintsStatusList['pp_id'] = $val['pp_id'];
        $paintsStatusList['file_uri'] = WEB_QW_APP_FILE_DOMAIN . $paints['file_uri'] . APP_DYNAMIC_FILE_RULE_750;
        $paintsStatusList['votes'] = $val['votes'];
        $paintsStatusList['rfile_uri'] = WEB_QW_APP_FILE_DOMAIN . $rpaints['file_uri'] . APP_DYNAMIC_FILE_RULE_360x360;
        $paintsStatusList['r_ppid'] = $rpaints['pp_id'];
        
        $paintsStatusList['avatar'] = $ctUserInfo['avatar'];
        $paintsStatusList['nickname'] = $ctUserInfo['nickname'];
        $list[] = $paintsStatusList;
        unset($paintsStatusList);
      }
    }
    
    header("Content-type: application/json");
    echo json_encode($list);
  }

  /**
   * 求画列表模版渲染
   */
  public function doListbeDrawedPage () {
    $this->getController()->render(FB_END_TPL_PATH . "/app/painting/pictures/list/bedrawedpage.tpl");
  }

  /**
   * 求画列表
   * type 0-照片 1-画作
   */
  public function doListbeDrawed () {
    $page = $this->getController()->getSafeRequest('page', 0, 'POST', 'int');
    $pageSize = 20;
    $paints = $this->getPaintsList(0, $page, $pageSize);
    $list = array();
    if ($paints) {
      foreach ($paints as $val) {
        $picturesPaintsStatus = $this->getController()->getWebappService()->getPicturesPaintsStatusByPPid($val['pp_id']);
        // 用户信息
        $ctUserInfo = $this->getController()->getUserService()->getUserByUid($val['uid']);
        $paintingPicturesList = array();
        if ($val['uid'] == $this->getController()->currentUser['uid']) {
          $paintingPicturesList['is_mine'] = 1;
        } else {
          $paintingPicturesList['is_mine'] = 0;
        }
        $paintingPicturesList['ppid'] = $val['pp_id'];
        $paintingPicturesList['uid'] = $val['uid'];
        $paintingPicturesList['nickname'] = $ctUserInfo['nickname'];
        $paintingPicturesList['file_uri'] = WEB_QW_APP_FILE_DOMAIN . $val['file_uri'] . APP_DYNAMIC_FILE_RULE_360x360;
        $paintingPicturesList['paintings'] = $picturesPaintsStatus['paintings'];
        $list[] = $paintingPicturesList;
      }
    }
    
    header("Content-type: application/json");
    echo json_encode($list);
  }

  /**
   * 明信片模版渲染
   */
  public function doListPoster () {
    $ppid = $this->getController()->getSafeRequest('ppid', 0, 'GET', 'int');
    if ($ppid) {
      $paints = $this->getController()->getWebappService()->getPicturesPaintsByppid($ppid);
      $rpaints = $this->getController()->getWebappService()->getPicturesPaintsByppid($paints['relation_ppid']);
      
      // 判断是否是自己收到的画作
      if ($rpaints['uid'] == $this->getController()->currentUser['uid']) {
        $show = array();
        $show['ppid'] = $paints['ppid'];
        $show['file_url'] = WEB_QW_APP_FILE_DOMAIN . $paints['file_uri'];
        $show['rfile_url'] = WEB_QW_APP_FILE_DOMAIN . $rpaints['file_uri'];
        $show['author'] = '/pictures/list/postcard?ppid=' . $ppid;
        $this->getController()->render(FB_END_TPL_PATH . "/app/painting/pictures/list/poster.tpl", $show);
      }
    }
  }

  /**
   * 生成明信片
   */
  public function doListpostcard () {
    $ppid = $this->getController()->getSafeRequest('ppid', 0, 'GET', 'int');

    if ($ppid) {
      $paints = $this->getController()->getWebappService()->getPicturesPaintsByppid($ppid);
      if ($paints) {
        $rpaints = $this->getController()->getWebappService()->getPicturesPaintsByppid($paints['relation_ppid']);
      
        // 判断是否是自己收到的画作
        if ($rpaints['uid'] == $this->getController()->currentUser['uid']) {
      
          $im = imagecreatetruecolor(100, 340);
          $bgcolor = imagecolorallocatealpha($im, 0, 0, 0, 127);   
          $grey = imagecolorallocate($im, 255, 255, 255);
          
          // 时间
          $newText = '';
          $data = $this->getListData($paints['created_time']);
          $length = mb_strlen($data, APP_DEFAULT_CHARACTER);
          for ($i = 0; $i < $length; ++$i) {
            $newText .= mb_substr($data, $i, 1, APP_DEFAULT_CHARACTER) . "\n\r";
          }
          
          // 用户信息
          $byNickname = "By\n\r";
          $ctUserInfo = $this->getController()->getUserService()->getUserByUid($paints['uid']);
          $nickname = $ctUserInfo['nickname'];
          $length = mb_strlen($nickname, APP_DEFAULT_CHARACTER);
          for ($i = 0; $i < $length; ++$i) {
            $byNickname .= mb_substr($nickname, $i, 1, APP_DEFAULT_CHARACTER) . "\n\r";
          }
          $font = '/FB-End/files/ui/font/hyxmtj.ttf';
          
          imagealphablending($im , false);//关闭混合模式，以便透明颜色能覆盖原画板
          imagefill($im , 0 , 0 , $bgcolor);//填充透明背景
          imagettftext($im, 17, 0, 0, 20, $grey, $font, $newText);
          imagettftext($im, 17, 0, 50, 20, $grey, $font, $byNickname);
          imagesavealpha($im , true);//设置保存PNG时保留透明通道信息 
          header('Content-type:image/png');
          // 将图像保存到文件，并释放内存
          imagepng($im);
          imagedestroy($im);
        }
      }
    }
  }

  /**
   * 画作详情页模版渲染
   */
  public function doListPaintingsDetails () {
    $ppid = $this->getController()->getSafeRequest('ppid', 0, 'GET', 'int');
    if ($ppid) {
      $paints = $this->getController()->getWebappService()->getPicturesPaintsByppid($ppid);
      $rpaints = $this->getController()->getWebappService()->getPicturesPaintsByppid($paints['relation_ppid']);
      if ($rpaints) {
        // 用户信息
        $ctUserInfo = $this->getController()->getUserService()->getUserByUid($paints['uid']);
        $titles = array(
          $ctUserInfo['nickname'].'的画作'
        );
        $titleIndex = rand(0, count($titles) - 1);
        $show['title'] = $titles[$titleIndex];
        $show['ppid'] = $ppid;
        $show['r_ppid'] = $rpaints['pp_id'];
        $show['nickname'] = $ctUserInfo['nickname'];
      }
      $this->getController()->render(FB_END_TPL_PATH . "/app/painting/pictures/list/paintingsdetails.tpl", $show);
    }
  }

  /**
   * 画作详情页 异步请求列表
   */
  public function doListPaintingsDetailsList () {
    $ppid = $this->getController()->getSafeRequest('ppid', 0, 'POST', 'int');
    if ($ppid) {
      $paints = $this->getController()->getWebappService()->getPicturesPaintsByppid($ppid);
      
      // 用户信息
      $ctUserInfo = $this->getController()->getUserService()->getUserByUid($paints['uid']);
      $rpaints = $this->getController()->getWebappService()->getPicturesPaintsByppid($paints['relation_ppid']);
      $voteUser = $this->getController()->getThingService()->getThreadVoteUsersByTid($paints['relation_tid'], 1, 6);
      $paintsStatus = $this->getController()->getWebappService()->getPicturesPaintsStatusByPPid($paints['pp_id']);
      $list = array();
      $list['is_votes'] = 0;
      foreach ($voteUser as $k => $vote) {
        $voteUserCtUserInfo = $this->getController()->getUserService()->getUserByUid($vote['uid']);
        $list['voavatar'][$k]['uid'] = $voteUserCtUserInfo['uid'];
        $list['voavatar'][$k]['avatar'] = $voteUserCtUserInfo['avatar'];
        if ($vote['uid'] == $this->getController()->currentUser['uid']) {
          $list['is_votes'] = 1;
        } else {
          $list['is_votes'] = 0;
        }
      }
      
      $time = $this->getListTime($paints['created_time']);
      $list['uid'] = $paints['uid'];
      $list['time'] = $time;
      $list['pp_id'] = $paints['pp_id'];
      $list['file_uri'] = WEB_QW_APP_FILE_DOMAIN . $paints['file_uri'] . APP_DYNAMIC_FILE_RULE_750;
      $list['votes'] = $paintsStatus['votes'];
      $list['rfile_uri'] = WEB_QW_APP_FILE_DOMAIN . $rpaints['file_uri'] . APP_DYNAMIC_FILE_RULE_360x360;
      $list['r_ppid'] = $rpaints['pp_id'];
      $list['avatar'] = $ctUserInfo['avatar'];
      $list['nickname'] = $ctUserInfo['nickname'];
      
      header("Content-type: application/json");
      echo json_encode($list);
    }
  }

}