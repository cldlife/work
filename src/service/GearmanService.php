<?php
/**
 * GearmanService
 */
class GearmanService extends BaseService {

  public function getGearmanWorker () {
    return GearmanFactory::getInstance()->getGearmanWorker(self::GEARMAN_SERVER_NODE);
  }

  private function getGearman () {
    return GearmanFactory::getInstance()->getGearman(self::GEARMAN_SERVER_NODE);
  }

  private function getUserService () {
    return ServiceFactory::getInstance()->createUserService();
  }

  /**
   * 添加发送消息任务 (无序)
   * @param array $msgs array(msg, msg, ...)
   * @return bool
   */
  public function addSendMsgJob ($msgs) {
    if ($msgs) {
      $gearman = $this->getGearman();
      return ($gearman && $gearman->returnCode() == GEARMAN_SUCCESS && $gearman->doBackground('send_msg', json_encode($msgs))) ? TRUE : FALSE;
    }
    return FALSE;
  }

  /**
   * 添加发送队列消息任务（有序,先进后出原则）
   * @param array $msgs array(msg, msg, ...)
   * @return bool
   */
  public function addSendMsgTaskJob ($msgs) {
    $res = FALSE;
    if ($msgs) {
      $gearman = $this->getGearman();
      if ($gearman && $gearman->returnCode() == GEARMAN_SUCCESS) {
        $gearman->addTaskBackground('send_msg', json_encode($msgs));
        $res = $gearman->runTasks();
      }
    }
    return $res;
  }

  /**
   * @desc 调用融云接口发送游戏息
   * @param array $msg
   * @return bool
   */
  public function addRcGameMsg ($msg) {
    if ($msg && $msg['gameMsg']) {
      $msg['fromUser'] = $msg['fromUser'] ?: $this->getUserService()->getOfficialUserInfo();
      $gearman = $this->getGearman();
      return ($gearman && $gearman->doBackground('rc_game_msg', json_encode($msg)) && $gearman->returnCode() == GEARMAN_SUCCESS);
    }
    return FALSE;
  }

  /**
   * @desc 调用融云接口发送聊天室消息
   * @param array $msg
   * @return bool
   */
  public function addRcChatRoomMsg ($msg) {
    if ($msg && $msg['roomIds'] && $msg['tplName']) {
      $msg['fromUser'] = $msg['fromUser'] ?: $this->getUserService()->getOfficialUserInfo();
      $gearman = $this->getGearman();
      return ($gearman && $gearman->doBackground('rc_chat_msg', json_encode($msg)) && $gearman->returnCode() == GEARMAN_SUCCESS);
    }
    return FALSE;
  }
}
