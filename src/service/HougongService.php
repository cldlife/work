<?php
/**
 * @desc HougongService
 */
class HougongService extends BaseService {

  private function getHougongDAO () {
    return DAOFactory::getInstance()->createHougongDAO();
  }
 
  /**
   * @desc 根据用户uid和关系级别1获取主人信息 
   * @desc level 1-主人 2-奴隶
   * @param int $uid $level
   * @return array
   */
  public function getHgRelationMasterByUidAndLevel ($uid, $level = 1) {
    return ($uid && $level) ? $this->getHougongDAO()->findHgRelationMasterByUidAndLevel($uid, $level) : array();
  }

  /**
   * @desc 根据用户uid和关系级别1获取奴隶列表
   * @desc level 1-主人 2-奴隶
   * @param int $uid $level
   * @return array
   */
  public function getHgRelationSlaveListByUidAndLevel ($uid, $page = 1, $pageSize = 30, $level = 2) {
  	if ($uid && $level && $page && $pageSize) {
  	  $offset = ($page - 1) * $pageSize;
  	  return $this->getHougongDAO()->findHgRelationSlaveListByUidAndLevel($uid, $offset, $pageSize, $level);
  	}

  	return array();
  }

  /**
   * @desc 写入主人/奴隶关系表  
   * @return bool
   */
  public function addHgRelation ($fields) {
   	if (!$fields['uid'] || !$fields['level'] || !$fields['relation_uid']) {
       throw new Exception('uid, level and relation_uid is null...');
    }
     return $this->getHougongDAO()->insertHgRelation($fields);
   }


  /**
   * @desc 解除关系
   * @param uid relation_uid
   * @return bool
   */
  public function deleteHgRelation ($uid, $ruid) {
    if (!$uid || !$ruid) {
      throw new Exception('uid or ruid is null...');
    }
    return $this->getHougongDAO()->deleteHgRelation($uid, $ruid);
  }
  
  /**
   * @desc 根据id查询奴隶任务
   * @param int $taskId
   * @param longint $uid
   * @return array
   */
  public function getHgTaskByTaskId ($taskid, $uid) {
    return ($taskid && $uid) ? $this->getHougongDAO()->findHgTaskWithId($taskid, $uid) : array();
  }

  /**
   * @desc 奴隶任务查询
   * @param uid
   * @return array
   */
  public function getHgTaskByUid ($uid) {
  	return $uid ? $this->getHougongDAO()->findHgTaskWithUid($uid) : array();
  } 

  /**
   * @desc 奴隶任务派发
   * @desc status 0-休息中,1-任务中,2-任务完成
   * @param uid
   * @return bool
   */
  public function addHgTask ($fields) {
    if (!$fields['uid'] || !$fields['task'] || !$fields['total_coins'] || !$fields['remain_coins'] || !$fields['status']) {
     throw new Exception('uid, task, total_coins, remain_coins or status is null');
    }
    $fields['task'] = json_encode($fields['task']);
    return $this->getHougongDAO()->insertHgTask($fields);
  }

  /**
   * @desc 奴隶任务状态更改
   * @desc status 0-休息中,1-任务中,2-任务完成
   * @param array $fields
   */
  public function updateHgTask ($fields) {
    if (!$fields['uid'] || !$fields['id']) {
      throw new Exception('uid or id is null...');
    }
    return $this->getHougongDAO()->updateHgTask($fields);
  }

  /**
   * @desc 根据task_id AND uid获取抢金币纪录
   * @param task_id uid
   * @return array
   */
  public function getHgTaskGetcoinUsersByTaskidAndUid ($taskid, $uid) {
  	return ($taskid && $uid) ? $this->getHougongDAO()->findHgTaskGetcoinUsersByTaskidAndUid($taskid, $uid) : array();
  } 


  /**
   * @desc 写入抢金币记录表 
   * @return bool
   */
  public function addHgTaskGetcoinUsers ($fields) {
   	if (!$fields['task_id'] || !$fields['uid']) {
       throw new Exception('task_id or uid is null...');
    }
     return $this->getHougongDAO()->insertHgTaskGetcoinUsers($fields);
   }
  
  /**
   * @desc 根据uid and viuid and time 判断今日是否已访问该好友
   * @param uid visitor_uid time
   * @return array
   */
  public function getHgVisitorByUidAndViuid ($uid, $viuid) {
    return ($uid && $viuid) ? $this->getHougongDAO()->findHgVisitorByUidAndViuid($uid, $viuid) : array();
  } 

  /**
   * @desc 根据uid和时间获取今日访问人次 hg_visitor
   * @param uid
   * @return count
   */
  public function getHgVisitorCountByUidAndTime ($uid, $time = 0) {
    if (!$time) {
      $today = date('Y-m-d');
      $time = strtotime($today);    
    }
    return ($uid && $time) ? $this->getHougongDAO()->findHgVisitorCountByUidAndTime($uid, $time) : array();
  } 

  /**
   * @desc 根据用户uid获取访客信息 hg_visitor
   * @param uid
   * @return array
   */
  public function getHgVisitorListByUid ($uid, $page = 1, $pageSize = 30) {
  if ($uid && $page && $pageSize) {
  	  $offset = ($page - 1) * $pageSize;

  	  return $this->getHougongDAO()->findHgVisitorListByUid($uid, $offset, $pageSize);
  	}
  	return array();
  }
 
  /**
   * @desc 根据uid和时间统计总访问人次 hg_visitor
   * @param uid
   * @return count
   */
  public function getHgVisitorCountByUid ($uid) {                                
    return ($uid) ? $this->getHougongDAO()->findHgVisitorCountByUid($uid) : array();
  }

  /**
   * @desc 今日访客写入访客记录表 
   * @desc status 0-未查看 1-已查看
   * @return bool
   */
  public function addHgVisitor ($fields) {
    if (!$fields['uid'] || !$fields['visitor_uid']) {
       throw new Exception('uid or visitor_uid is null...');
    }
    if (!$time) {
      $today = date('Y-m-d');
      $time = strtotime($today);    
    }
    if (!$fields['status']) $fields['status'] = 0;
    return $this->getHougongDAO()->insertHgVisitor($fields, $time);
   }
  
  /**
   * @desc 修改访客查看状态
   * @desc 0-未查看,1-已查看
   * @param uid
   * @return bool
   */
  public function updateHgVisitorStatus ($fields) {
    if (!$fields['uid'] || !$fields['status']) {
      throw new Exception('uid or status is null...');
    }
    return $this->getHougongDAO()->updateHgVisitorStatus($fields);
  }

  /**
   * @desc 更新同一访客在今日访问记录
   * @desc status 0-未查看 1-已查看 
   * @param array $fields
   */
  public function updateHgVisitor ($fields) {
    if (!$fields['uid']) {
      throw new Exception('uid is null...');
    }
    if (!$fields['status']) $fields['status'] = 0;
    return $this->getHougongDAO()->updateHgVisitor($fields);
  }

  /**
   * @desc 根据用户uid读取最新通知 hg_notice
   * @param uid
   * @return array
   */
  public function getHgNoticeListByUid ($uid, $page = 1, $pageSize = 20) {
    $noticeList = array();
    if ($uid && $page && $pageSize) {
  	  $offset = ($page - 1) * $pageSize;
  	  $list = $this->getHougongDAO()->findHgNoticeListByUid($uid, $offset, $pageSize);
      if ($list) {
        foreach ($list as $val) {
          $val['content'] = json_decode($val['content'], true);
          $noticeList[] = $val;
          unset($val);
        }
      }
  	}
  	return $noticeList;
  }

  /**
   * @desc 写入最新通知 hg_notice
   * @param uid
   * @return bool
   */
  public function addHgNotice ($fields) {
   	if (!$fields['uid'] || !$fields['content']) {
      throw new Exception('uid or content is null...');
    }
    $fields['content'] = json_encode($fields['content']);
    return $this->getHougongDAO()->insertHgNotice($fields);
   }

  /**
   * @desc 修改消息查看状态
   * @desc 0-未查看,1-已查看
   * @param uid
   * @return bool
   */
  public function updateHgNotice ($fields) {
    if (!$fields['uid'] || !$fields['status']) {
      throw new Exception('uid or status is null...');
    }
    return $this->getHougongDAO()->updateHgNotice($fields);
  }

}