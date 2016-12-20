<?php
/**
 * @desc ThingService
 */
class ThingService extends BaseService {
  
  private function getThingDAO() {
    return DAOFactory::getInstance()->createThingDAO();
  }
  
  /**
   * @return 根据$category获取帖子列表关系 (mapping)
   */
  public function getThreadListMappingByCategory($category, $page = 1, $pageSize = 10) {
    $list = array();
    if ($category && $page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $list = $this->getThingDAO()->findThreadListWithCategory($category, $offset, $pageSize);
    }
    return $list;
  }
  
  /**
   * @return 根据$category获取status=0的帖子列表
   */
  public function getThreadListByCategory($category, $page = 1, $pageSize = 10) {
    $list = array();
    if ($category && $page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $threadList = $this->getThingDAO()->findThreadListWithCategory($category, $offset, $pageSize);
      if ($threadList) {
        foreach ($threadList as $item) {
          $thread = $this->getThreadBytid($item['tid']);
          if ($thread['status'] != 0) continue;
  
          $list[] = $thread;
          unset($tmpItem);
        }
      }
    }
    return $list;
  }
  
  /**
   * @return 添加帖子列表关联
   */
  public function addThreadList($fields) {
    if ($fields['tid'] && $fields['category']) {
      return $this->getThingDAO()->insertThreadList($fields);
    }
    return array();
  }
  
  /**
   * @desc 删除帖子列表关联
   */
  public function deleteThreadList($tid, $category) {
    if ($tid && $category) {
      return $this->getThingDAO()->deleteThreadList($tid, $category);
    }
    return FALSE;
  }
  
  /**
   * @return 获取帖子信息 (单条数据)
   */
  public function getThreadBytid($tid) {
    $thread = array();
    if ($tid) $thread = $this->getThingDAO()->findThreadBytid($tid);
    if ($thread) {
      //属性状态
      $thread['attr_status'] = $this->getThreadStatusBytid($tid);
      
      //获取图片列表
      $thread['images'] = array();
      if ($thread['attach_hashid']) $thread['images'] = $this->getAttachmentService()->getThreadImages($tid, $thread['attach_hashid']);
    }
    return $thread;
  }
  
  /**
   * @desc 发表帖子
   * @param array $fields
   * @param array $aids 附件aid集合
   */
  public function addThread(Array $fields, $aids = array()) {
    if (!$fields['category'] || !$fields['uid'] || !$fields['content']) {
      throw new Exception('category, uid or content is null...');
    }
    
    //生成tid,写入帖子
    $fields['tid'] = Utils::longIdGenerator();
    $thread = $this->getThingDAO()->insertThread($fields);
    
    //初始化帖子状态
    if ($thread && !$this->getThingDAO()->insertThreadStatus($fields)) $thread = array();
      
    //更新附件指定tid
    if ($thread && $aids && $fields['attach_hashid']) {
      if (!$this->getAttachmentService()->updateAttachmentsByAids($aids, $fields['attach_hashid'], array('tid' => $thread['tid']))) $thread = array();
    }
    
    //写入帖子索引列表
    if ($thread) $this->getThingDAO()->insertThreadList($fields);
    return $thread;
  }
  
  /**
   * @desc 删除帖子
   */
  public function deleteThread($tid, $attachHashId = 0, $isUserSelf = FALSE) {
    $res = array();
    if ($tid) {
      $delFields = array();
      $delFields['status'] = 1;
      
      //用户自己删除，更新最后操作时间
      if ($isUserSelf) $delFields['updated_time'] = time();
      $res = $this->getThingDAO()->updateThread($tid, $delFields);

      //删除图片
      if ($res && $attachHashId) $this->getAttachmentService()->deleteAttachmentsByTid($tid, $attachHashId);
    }
    return $res;
  }
  
  /**
   * @desc 获取帖子状态信息
   */
  public function getThreadStatusBytid($tid) {
    $threadStatus = array();
    if ($tid) {
      $threadStatus = $this->getThingDAO()->findThreadStatusBytid($tid);
    }
    return $threadStatus;
  }
  
  /**
   * @desc 更新帖子状态信息
   * @param array $fields (replies or votes)
   */
  public function updateThreadStatus($tid, Array $fields) {
    $threadStatus = array();
    if ($tid) {
      $threadStatus = $this->getThingDAO()->updateThreadStatus($tid, $fields);
    }
    return $threadStatus;
  }
  
  /**
   * @desc 递增/递减帖子状态信息
   * @param array $fields (replies or votes)
   */
  public function inDecreaseThreadStatusByTid($tid, Array $fields) {
    if ($tid) {
      return $this->getThingDAO()->inDecreaseThreadStatusWithTid($tid, $fields);
    }
    return FALSE;
  }
  
  /**
   * @return 获取回复列表
   */
  public function getThreadPostListBytid($tid, $page = 1, $pageSize = 10) {
    $list = array();
    if ($tid && $page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $list = $this->getThingDAO()->findThreadPostListBytid($tid, $offset, $pageSize);
    }
    return $list;
  }
  
  /**
   * @return 获取回复信息 (单条数据)
   */
  public function getThreadPostById($pid, $tid) {
    $post = array();
    if ($pid && $tid) {
      $post = $this->getThingDAO()->findThreadPostById($pid, $tid);
    }
    return $post;
  }
  
  /**
   * @desc 发表回复
   */
  public function addThreadPost($tid, $uid, Array $fields) {
    if (!$tid || !$uid || !$fields['content']) {
      throw new Exception('tid, uid or content is null...');
    }
    
    //生成pid
    $pid = Utils::longIdGenerator();
    return $this->getThingDAO()->insertThreadPost($pid, $tid, $uid, $fields);
  }
  
  /**
   * @desc 删除单条回复
   */
  public function deleteThreadPost($pid, $tid) {
    if ($pid && $tid) {
      return $this->getThingDAO()->updateThreadPost($pid, $tid, array(
        'status' => 1
      ));
    }
    return FALSE;
  }
  
  /**
   * @desc 屏蔽单条回复
   */
  public function disableThreadPost($pid, $tid) {
    if ($pid && $tid) {
      return $this->getThingDAO()->updateThreadPost($pid, $tid, array(
        'is_invisible' => 1
      ));
    }
    return FALSE;
  }

  /**
   * @desc 添加送花用户
   * @param $tid 帖子ID
   * @param $uid
   * @return bool
   */
  public function addThreadVoteUser($tid, $uid){
    if ($tid && $uid) {
      if (!$this->getThingDAO()->findThreadVoteUserWithTidAndUid($tid, $uid)) {
        $this->getThingDAO()->insertThreadVoteUser($tid, $uid);
      }
      return TRUE;
    }
    return FALSE;
  }


  /**
   * @desc 根据帖子tid获取送花用户列表
   * @author Chu
   * @return array
   */
  public function getThreadVoteUsersByTid($tid, $page = 1, $pageSize = 30) {
    $list = array();
    if ($tid && $page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $list = $this->getThingDAO()->findThreadVoteUsersWithTid($tid, $offset, $pageSize);
    }
    return $list;
  }

  /**
   * @desc 获取扩展帖子url
   * @param int $extType 扩展帖子类型，1为拍照,2为画作
   * @param int $extId
   * @return string
   */
  public function getThreadExtendUrlByExtId($extType, $extId, $isMine) {
    switch ($extType) {
      case 1:
      case 2:
        //我拍你画
        $urlScheme = 'WanZhu://webview';
        $params = array(
          'title' => '我拍你画',
          'link' =>  WEB_QW_APP_WX_DOMAIN . "/pictures/homepage/selfpage?ppid=" . $extId,
          'hide_nav' => $isMine ? 0 : 1,
          'skin_color' => '#ff7733'
        );
        $extendUrl = $urlScheme . "?" . json_encode($params);
        break;
      default:
        $extendUrl = "";
        break;
    }
    return $extendUrl;
  }
}