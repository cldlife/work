<?php
/**
 * TaskService
 */
class TaskService extends BaseService {

  private function getTaskDAO () {
    return DAOFactory::getInstance()->createTaskDAO();
  }

  /**
   * @desc 获取待做任务列表
   * @param int $page
   * @param int $pageSize
   * @return array
   */
  public function getTaskList ($page = 1, $pageSize = 20) {
    $taskList = array();
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $list = $this->getTaskDAO()->findTaskList($offset, $pageSize);
      if ($list) {
        foreach ($list as $task) {
          $task['workload'] = json_decode($task['workload'], TRUE);
          $taskList[] = $task;
        }
      }
      unset($list);
    }
    return $taskList;
  }

  /**
   * @desc 根据id获取任务信息
   * @param int $id
   * @return array
   */
  public function getTaskById ($id) {
    $task = array();
    if ($id) {
      $task = $this->getTaskDAO()->findTaskWithId($id);
      if ($task) $task['workload'] = json_decode($task['workload'], TRUE);
    }
    return $task;
  }

  /**
   * @desc 通用的添加任务方法
   * @param array $fields
   * @return bool
   */
  public function addTask ($fields) {
    if ($fields && $fields['type'] && $fields['workload']) {
      $fields['run_time'] = $fields['run_time'] ? $fields['run_time'] : time();
      if (is_array($fields['workload'])) $fields['workload'] = json_encode($fields['workload']);
      return $this->getTaskDAO()->insertTask($fields);
    }
    return FALSE;
  }

  /**
   * @desc 更新任务信息
   * @param int $id
   * @param array $fields
   * @return bool
   */
  public function updateTaskById ($id, $fields) {
    return ($id && $fields) ? $this->getTaskDAO()->updateTaskWithId($id, $fields) : FALSE;
  }

  /**
   * @desc 删除任务信息
   * @param int $id
   * @param array $fields
   * @return bool
   */
  public function deleteTaskById ($id) {
    return ($id) ? $this->getTaskDAO()->deleteTaskWithId($id) : FALSE;
  }

  /**
   * @desc 添加定时发送微信消息任务
   * @param array $msg array($msg, $msg)
   * @param int $timeOut
   * @return bool
   */
  public function addSendWxMsgTask ($msg, $timeOut) {
    if ($msg && $timeOut) {
      $fields = array(
        'type' => 'send_wx_msg',
        'run_time' => time() + $timeOut,
        'workload' => json_encode($msg),
        'status' => 0,
      );
      return $this->getTaskDAO()->insertTask($fields);
    }
    return FALSE;
  }

  /**
   * @desc 添加定时加入房间任务
   * @param array $join array(roomid)
   * @param int $timeOut
   * @return bool
   */
  public function addJoinRoomTask ($join, $timeOut) {
    if ($join && $timeOut) {
      $fields = array(
        'type' => 'join_room',
        'run_time' => time() + $timeOut,
        'workload' => json_encode($join),
        'status' => 0,
      );
      return $this->getTaskDAO()->insertTask($fields);
    }
    return FALSE;
  }

  /**
   * @desc 添加定时发送聊天消息
   * @param array $join array(roomid)
   * @param int $timeOut
   * @return bool
   */
  public function addChatMsgTask ($chat, $timeOut) {
    if ($chat && $timeOut) {
      $fields = array(
        'type' => 'robot_chat',
        'run_time' => time() + $timeOut,
        'workload' => json_encode($chat),
        'status' => 0,
      );
      return $this->getTaskDAO()->insertTask($fields);
    }
    return FALSE;
  }

  /**
   * @desc 添加定时发言任务
   * @param array $speak array(gameid)
   * @param int $timeOut
   * @return bool
   */
  public function addRobotSpeakTask ($speak, $timeOut) {
    if ($speak && $timeOut) {
      $fields = array(
        'type' => 'robot_speak',
        'run_time' => time() + $timeOut,
        'workload' => json_encode($speak),
        'status' => 0,
      );
      return $this->getTaskDAO()->insertTask($fields);
    }
    return FALSE;
  }

  /**
   * @desc 添加定时投票任务
   * @param array $vote array(gameid)
   * @param int $timeOut
   * @return bool
   */
  public function addRobotVoteTask ($vote, $timeOut) {
    if ($vote && $timeOut) {
      $fields = array(
        'type' => 'robot_vote',
        'run_time' => time() + $timeOut,
        'workload' => json_encode($vote),
        'status' => 0,
      );
      return $this->getTaskDAO()->insertTask($fields);
    }
    return FALSE;
  }

  /**
   * @desc 添加定时接受惩罚任务
   * @param array $punishment array(gameid, uid)
   * @param int $timeOut
   * @return bool
   */
  public function addRobotPunishmentTask ($punishment, $timeOut) {
    if ($punishment && $timeOut) {
      $fields = array(
        'type' => 'robot_punish',
        'run_time' => time() + $timeOut,
        'workload' => json_encode($punishment),
        'status' => 0,
      );
      return $this->getTaskDAO()->insertTask($fields);
    }
    return FALSE;
  }

  /**
   * @desc 释放机器人'解散房间'后'正在使用'的状态
   * @param array $release
   * @param int $timeOut
   * @return bool
   */
  public function addRobotReleaseIsusing ($release, $timeOut) {
    if ($release && $timeOut) {
      $fields = array(
        'type' => 'robot_release',
        'run_time' => time() + $timeOut,
        'workload' => json_encode($release),
        'status' => 0,
      );
      return $this->getTaskDAO()->insertTask($fields);
    }
    return FALSE;
  }
}

