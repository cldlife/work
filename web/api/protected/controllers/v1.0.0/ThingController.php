<?php
class ThingController extends BaseController {

  //上传帖子照片数限制
  const UPLOAD_THREAD_IMAGE_LIMIT = 1;
  
  /**
   * @desc 社区帖子列表（喊话、爆照）
   */
  public function actionThreadList() {
    $category = $this->getSafeRequest('category', 1, 'int');
    $page = $this->getSafeRequest('page', 1, 'int');
    
    //参数验证
    if (!$category) $this->outputJsonData(1006);
    
    $list = array();
    $pageSize = 20;
    $threadList = $this->getThingService()->getThreadListByCategory($category, $page, $pageSize);
    if ($threadList) {
      
      //是否有点赞（送玫瑰）
      //TODO
      $userThreadVotes = array();
      //$userThreadVotes = $this->getUserMineService()->getMineVoteThreadsByTids($this->currentUser['uid'], $threadList);
      
      //是否屏蔽用户
      $disappearUsers = array();
      $disappearUids = array();
      foreach ($threadList as $thread) {
        $disappearUids[] = $thread['uid'];
      }
      if ($disappearUids) $disappearUsers = $this->getUserMineService()->getMineDisappearUsersByUids($this->currentUser['uid'], $disappearUids);
      
      foreach ($threadList as $thread) {
        if (in_array($thread['uid'], $disappearUsers)) continue;

        //获取用户信息（用户状态，用户等级）
        $user = $this->getUserService()->getUserByUid($thread['uid']);
        if (!$user['uid'] || $user['status'] != 0) continue;
        $user['status'] = $this->getUserFortuneService()->getUserFortuneStatusByUid($user['uid']);
        $userLevel = $this->getUserService()->getUserLevel($user['status']['points']);
        
        //附件图片信息
        $images = array();
        if ($thread['category'] == 1) {
          $images['s_url'] = preg_replace("/\/200$/", APP_DYNAMIC_FILE_RULE_750x300, $user['avatar']);
          $images['b_url'] = preg_replace("/\/200$/", APP_DYNAMIC_FILE_RULE_960, $user['avatar']);
        } else {
          $images['s_url'] = $thread['images'][0]['m_url'];
          $images['b_url'] = $thread['images'][0]['b_url'];
        }
        
        //回复列表
        $posts = array();
        $postList = $this->getThingService()->getThreadPostListBytid($thread['tid'], 1, 30);
        if ($postList) {
          foreach ($postList as $post) {
            if (!$post['uid']) continue;
            
            $postUser = $this->getUserService()->getUserByUid($post['uid']);
            if (!$postUser['uid'] || $postUser['status'] != 0) continue;
        
            $repliedUser = array();
            if ($post['replied_uid']) $repliedUser = $this->getUserService()->getUserByUid($post['replied_uid']);
            
            $item = array();
            $item['is_mine'] = ($this->currentUser['uid'] == $post['uid'] ? 1 : 0);
            $item['pid'] = $post['pid'];
            $item['content'] = $post['content'];
            $item['user_info'] = array('uid' => $postUser['uid'], 'nickname' => $postUser['nickname']);
            $item['replied_user_info'] = $repliedUser ? array('uid' => $repliedUser['uid'], 'nickname' => $repliedUser['nickname']) : (object) $repliedUser;
            $posts[] = $item;
            unset($item);
            unset($repliedUser);
            unset($postUser);
            unset($post);
          }
          unset($postList);
        }
        
        $tmpItem = array();
        $tmpItem['has_sent_rose'] = rand(0, 1);//in_array($thread['tid'], $userThreadVotes) ? 1 : 0;
        $tmpItem['is_mine'] = ($this->currentUser['uid'] == $thread['uid'] ? 1 : 0);
        
        $tmpItem['thread_info'] = array();
        $tmpItem['thread_info']['tid'] = $thread['tid'];
        $tmpItem['thread_info']['category'] = $thread['category'];
        $tmpItem['thread_info']['content'] = $thread['content'];
        $tmpItem['thread_info']['images'] = $images;
        $tmpItem['thread_info']['roses'] = $thread['attr_status']['votes'];
        $tmpItem['thread_info']['ctime'] = $thread['created_time'];
        $tmpItem['thread_info']['share_link'] = Utils::getThingThreadLink($thread['tid']);
        $tmpItem['thread_info']['extend_url'] = $this->getThingService()->getThreadExtendUrlByExtId($thread['extend_type'], $thread['extend_id'], $this->getExtendTypeIsMine($thread, $this->currentUser['uid']));
        
        $tmpItem['thread_info']['user_info'] = array();
        $tmpItem['thread_info']['user_info']['uid'] = $user['uid'];
        $tmpItem['thread_info']['user_info']['nickname'] = $user['nickname'];
        $tmpItem['thread_info']['user_info']['avatar'] = $user['avatar'];
        $tmpItem['thread_info']['user_info']['level_num'] = intval($userLevel['id']);
        $tmpItem['thread_info']['user_info']['desc'] = $thread['category'] == 1 ? '购买了23888金币' : '';//TODO 需读取喊话特权

        $tmpItem['posts'] = $posts;
        
        $list[] = $tmpItem;
        unset($tmpItem);
        unset($user);
        unset($userLevel);
      }
    }
    
    $data = array();
    $data['list'] = $list;
    $this->outputJsonData(0, $data);
  }

  /**
   * @desc 帖子详细页
   */
  public function actionThreadDetail() {
    $tid = $this->getSafeRequest("tid", 0, 'int');
    
    //参数验证
    if (!$tid) $this->outputJsonData(1009);
    $thread = $this->getThingService()->getThreadBytid($tid);
    if (!$thread['tid'] || $thread['status'] == 1) {
      $this->outputJsonData(400, array(
        'apptip' => '帖子不存在或已删除'
      ));
    }
    
    //获取用户信息（用户状态，用户等级）
    $isMine = ($this->currentUser['uid'] == $thread['uid'] ? 1 : 0);
    $user = $this->getUserService()->getUserByUid($thread['uid']);
    if (!$user['uid'] || $user['status'] != 0) continue;
    $user['status'] = $this->getUserFortuneService()->getUserFortuneStatusByUid($user['uid']);
    $userLevel = $this->getUserService()->getUserLevel($user['status']['points']);
    
    //附件图片信息
    $images = array();
    if ($thread['category'] == 1) {
      $images['s_url'] = preg_replace("/\/200$/", APP_DYNAMIC_FILE_RULE_750x300, $user['avatar']);
      $images['b_url'] = preg_replace("/\/200$/", APP_DYNAMIC_FILE_RULE_960, $user['avatar']);
    } else {
      $images['s_url'] = $thread['images'][0]['m_url'];
      $images['b_url'] = $thread['images'][0]['b_url'];
    }
    
    //帖子信息
    $threadInfo = array();
    $threadInfo['tid'] = $thread['tid'];
    $threadInfo['category'] = $thread['category'];
    $threadInfo['content'] = $thread['content'];
    $threadInfo['images'] = $images;
    $threadInfo['roses'] = $thread['attr_status']['votes'];
    $threadInfo['ctime'] = $thread['created_time'];
    $threadInfo['share_link'] = Utils::getThingThreadLink($thread['tid']);
    $threadInfo['extend_url'] = $this->getThingService()->getThreadExtendUrlByExtId($thread['extend_type'], $thread['extend_id'], $this->getExtendTypeIsMine($thread, $this->currentUser['uid']));
    $threadInfo['user_info'] = array();
    $threadInfo['user_info']['uid'] = $user['uid'];
    $threadInfo['user_info']['nickname'] = $user['nickname'];
    $threadInfo['user_info']['avatar'] = $user['avatar'];
    $threadInfo['user_info']['level_num'] = intval($userLevel['id']);
    $threadInfo['user_info']['desc'] = $thread['category'] == 1 ? '购买了23888金币' : '';//TODO 需读取喊话特权
    
    $data = array();
    $data['is_mine'] = $isMine;
    $data['has_sent_rose'] = rand(0, 1);//in_array($thread['tid'], $userThreadVotes) ? 1 : 0;
    $data['thread_info'] = $threadInfo;
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 帖子回复列表
   */
  public function actionThreadPostList() {
    $tid = $this->getSafeRequest("tid", 0, 'int');
    $page = $this->getSafeRequest('page', 1, 'int');
    
    //参数验证
    if (!$tid || !$page) $this->outputJsonData(1009);
    $thread = $this->getThingService()->getThreadBytid($tid);
    if (!$thread['tid'] || $thread['status'] == 1) {
      $this->outputJsonData(400, array(
        'apptip' => '帖子不存在或已删除'
      ));
    }
    
    //回复列表
    $pageSize = 30;
    $posts = array();
    $postList = $this->getThingService()->getThreadPostListBytid($thread['tid'], $page, $pageSize);
    if ($postList) {
      foreach ($postList as $post) {
        if (!$post['uid']) continue;
    
        $postUser = $this->getUserService()->getUserByUid($post['uid']);
        if (!$postUser['uid'] || $postUser['status'] != 0) continue;
    
        $repliedUser = array();
        if ($post['replied_uid']) $repliedUser = $this->getUserService()->getUserByUid($post['replied_uid']);
    
        $item = array();
        $item['is_mine'] = ($this->currentUser['uid'] == $post['uid'] ? 1 : 0);
        $item['pid'] = $post['pid'];
        $item['content'] = $post['content'];
        $item['user_info'] = array('uid' => $postUser['uid'], 'nickname' => $postUser['nickname']);
        $item['replied_user_info'] = $repliedUser ? array('uid' => $repliedUser['uid'], 'nickname' => $repliedUser['nickname']) : (object) $repliedUser;
        $posts[] = $item;
        unset($item);
        unset($repliedUser);
        unset($postUser);
        unset($post);
      }
      unset($postList);
    }
  
    $data = array();
    $data['list'] = $posts;
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 上传帖子图片
   */
  public function actionUploadAttach() {
    $orderId = $this->getSafeRequest("order_id", 0, 'int');
    $attachHashId = $this->getSafeRequest("t_key", 0, 'int');
  
    //参数验证
    if (!$orderId || !$attachHashId) {
      $this->outputJsonData(1);
    }
  
    //上传文件
    $info = array();
    $attachment = array();
    $file = $this->currentClientId == 1 ? Yii::app()->params['upload_token'] : Yii::app()->params['upload_token_android'];
    $res = $this->getAttachmentService()->uploadImage($_FILES[$file]['tmp_name'],0);
    if ($res['code'] == 1 && $res['fileInfo']) {
      $res['fileInfo']["order_id"] = $orderId;
      $attachment = $this->getAttachmentService()->addAttachment($attachHashId, $res['fileInfo']);
      $info = array($attachment['order_id'] => $attachment['aid']);
    }
  
    $this->outputJsonData(0, array(
      'info' => $info
    ));
  }
  
  /**
   * @desc 仅上传图片(webview上传)
   */
  public function actionWebviewUpload() {
    $attachHashId = $this->getSafeRequest("t_key", 0, 'int');
  
    //参数验证
    if (!$attachHashId) {
      $this->outputJsonData(1);
    }
  
    //上传文件
    $info = array();
    $attachment = array();
    $file = $this->currentClientId == 1 ? Yii::app()->params['upload_token'] : Yii::app()->params['upload_token_android'];
    $res = $this->getAttachmentService()->uploadImage($_FILES[$file]['tmp_name'], 0);
    if ($res['code'] == 1 && $res['fileInfo']) {
      $this->outputJsonData(0, array(
        'info' => json_encode($res)
      ));
    }
    $this->outputJsonData(1);
  }
  
  /**
   * @desc 发布帖子
   * @param int category 帖子类型，1-喊话，2-爆照
   */
  public function actionPublishThread() {
    $category = $this->getSafeRequest("category", 0, 'int');
    $content = $this->getSafeRequest("content");
    $attachHashId = $this->getSafeRequest("t_key", 0, 'int');
    $aids = $this->getSafeRequest("aids", array(), 'json');
    
    //参数验证
    if (!$category || !$content) $this->outputJsonData(1015);
    if ($this->currentUser['status']['is_need_edit']) $this->outputJsonData(1, array(
      'apptip' => '请先完善资料哦！'
    ));
    if ($category == 2 && count($aids) > self::UPLOAD_THREAD_IMAGE_LIMIT) $this->outputJsonData(1, array(
      'apptip' => '最多上传'.self::UPLOAD_THREAD_IMAGE_LIMIT.'张图片哦！'
    ));
    
    //喊话特权验证
    if ($category == 1 && !$this->currentUser['status']['privilege_public_num']) $this->outputJsonData(1, array(
      'apptip' => '你没有权限发布喊话'
    ));
      
    //Flood Start 缓存 (间隔10秒)
    $cacheKey = __FUNCTION__ . '_UID_' . $this->currentUser['uid'];
    $waiting = $this->getCommonService()->getFromMemcache($cacheKey);
    if ($waiting) $this->outputJsonData(1, array(
      'apptip' => '亲，请歇会稍后再试'
    ));
    
    //写入帖子
    $thread = $this->getThingService()->addThread(array(
      'category' => $category,
      'uid' => $this->currentUser['uid'],
      'content' => $content,
      'attach_hashid' => $attachHashId,
    ), $aids);
    if ($thread['tid']) {
      
      //添加到我的帖子
      if ($this->getUserMineService()->addMineThread($this->currentUser['uid'], $thread['tid'])) {
        
        //获得积分 & 更新喊话特权
        $ruleId = 0;
        if ($category == 1) {
          $ruleId = 4;
          $this->getUserFortuneService()->inDecreaseUserFortuneStatusByUid($this->currentUser['uid'], array(
            array('key' => 'privilege_public_num', 'value' => 1, 'in_de' => '-')
          ));
        }
        if ($category == 2) $ruleId = 5;
        $autoPointInfo = $this->getUserFortuneService()->autoUserFortunePoint($this->currentUser['uid'], $ruleId);
      }
      
      //Flood End 缓存
      $this->getCommonService()->setToMemcache($cacheKey, TRUE, self::FLOOD_LIMIT_TIME);
      
    } else {
      $this->outputJsonData(500);
    }
    
    $data = array();
    $data['tid'] = $thread['tid'];
    if ($autoPointInfo) $data['apptip'] = "发布成功 积分+{$autoPointInfo['point']}";
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 回复帖子
   */
  public function actionPostThread() {
    $tid = $this->getSafeRequest("tid", 0, 'int');
    $content = $this->getSafeRequest('content');
    $repliedUid = $this->getSafeRequest('replied_uid', 0, 'int');
    
    //参数验证
    if (!$tid || !$content) {
      $this->outputJsonData(1007);
    }
    //不能回复自己
    if ($this->currentUser['uid'] == $repliedUid) {
      $this->outputJsonData(1008);
    }
    
    //Flood Start 缓存 (间隔10秒)
    $cacheKey = __FUNCTION__ . '_TID_' . $tid . '_UID_' . $this->currentUser['uid'];
    $waiting = $this->getCommonService()->getFromMemcache($cacheKey);
    if ($waiting) $this->outputJsonData(1, array(
      'apptip' => '亲，请歇会稍后再试'
    ));
    
    //获取食记信息
    $thread = $this->getThingService()->getThreadBytid($tid);
    if (!$thread['tid'] || $thread['status'] == 1) $this->outputJsonData(1007, array(
      'apptip' => '该帖子不存在或已删除'
    ));
    
    //回复数
    $newReplies = $thread['attr_status']['replies'] + 1;
    $newPost = $this->getThingService()->addThreadPost($thread['tid'], $this->currentUser['uid'], array(
      'content' => $content,
      'replied_uid' => $repliedUid
    ));
    if ($newPost) {
      //更新回复数
      $this->getThingService()->inDecreaseThreadStatusByTid($thread['tid'], array(
        array('key' => 'replies', 'value' => 1, 'in_de' => '+'),
      ));
      
      //获得积分
      $autoPointInfo = $this->getUserFortuneService()->autoUserFortunePoint($this->currentUser['uid'], 6);
      
      //Flood End 缓存
      $this->getCommonService()->setToMemcache($cacheKey, TRUE, intval(self::FLOOD_LIMIT_TIME/3));
      
      $this->outputJsonData(0, array(
        'pid' => $newPost['pid'],
      	'apptip' => $autoPointInfo ? "评论成功 积分+{$autoPointInfo['point']}" : '评论成功'
      ));
    }
    
    $this->outputJsonData(500);
  }
  
  /**
   * @desc 点赞帖子（并给楼主送玫瑰）
   */
  public function actionVoteThread() {
    $tid = $this->getSafeRequest("tid", 0, 'int');
    
    //获取食记信息
    $thread = $this->getThingService()->getThreadBytid($tid);
    if (!$thread['tid'] || $thread['status'] == 1) $this->outputJsonData(1, array(
      'apptip' => '该帖子不存在或已删除'
    ));
    if ($thread['uid'] == $this->currentUser['uid']) $this->outputJsonData(1, array(
      'apptip' => '不能给自己送玫瑰哦'
    ));
    if (!$this->currentUser['status']['roses'] && !$this->currentUser['status']['coins']) $this->outputJsonData(1, array(
      'apptip' => '玫瑰和金币数量不足，请充值哦'
    ));
      
    $user = $this->getUserService()->getUserByUid($thread['uid']);
    if (!$user) $this->outputJsonData(400, array(
      'apptip' => '该用户不存在或已删除'
    ));
    
    //验证用户财富
    //优先使用玫瑰
    $roses = 0;
    if ($this->currentUser['status']['roses']) {
      $roses = 1;
      $this->getUserFortuneService()->autoUserFortuneRose($this->currentUser['uid'], 3);
      $this->getUserFortuneService()->autoUserFortuneRose($user['uid'], 4);
      
    //玫瑰不足则使用金币
    } elseif ($this->currentUser['status']['coins']) {
      $roses = $this->getUserFortuneService()->getCoinExchangeRoseRate();
      $this->getUserFortuneService()->autoUserFortuneCoin($this->currentUser['uid'], 16);
      $this->getUserFortuneService()->autoUserFortuneRose($user['uid'], 5, $roses);
    }
    
    if ($roses) {
      //更新帖子点赞数
      $this->getThingService()->inDecreaseThreadStatusByTid($thread['tid'], array(
        array('key' => 'votes', 'value' => $roses, 'in_de' => '+'),
      ));
      
      //添加至送花列表
      $this->getThingService()->addThreadVoteUser($thread['tid'], $this->currentUser['uid']);
      
      /** 扩展帖子处理 start */
      //我拍你画送花,extend_type 1为拍照,2为画作
      if ($thread['category'] == 2 && ($thread['extend_type'] == 1 || $thread['extend_type'] == 2)){
        //转换成我拍你画实体type (0为拍照,1为画作)
        $type = $thread['extend_type'] - 1;
        $this->getWebappService()->inDecreasePaintsByPid($thread['extend_id'], $type, array( 
          array('key' => 'votes', 'value' => $roses, 'in_de' => '+')
        ));
      }
      /** 扩展帖子处理 end */

      //发聊天消息
      $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
      $this->getMessageService()->sendRcImMessage($officialUserInfo, array($thread['uid']), 'thread_send_rose', array($this->currentUser['nickname'], $roses));
    }
    
    $this->outputJsonData(0, array(
      'votes' => $thread['attr_status']['votes'] + $roses,
    ));
  }

  /**
   * 判断扩展对象是否是当前用户所有(extend_type 扩展帖子类型，1为拍照, 2为画作)
   * @param array $thread
   * @param $uid
   * @return int
   */
  protected function getExtendTypeIsMine(array $thread, $uid){
     $isMine = 0;
     switch($thread['extend_type']){
       case 1:
         if ($uid == $thread['uid']) $isMine = 1;
         break;
       case 2:
         $paint = $this->getWebappService()->getPicturesPaintsByppid($thread['extend_id']);
         if ($paint) $relationPaint = $this->getWebappService()->getPicturesPaintsByppid($paint['relation_ppid']);
         if ($relationPaint && $relationPaint['uid'] == $uid) $isMine = 1;
     }
    return $isMine;
  }

}
