<?php
/**
 * @desc 任务系统GearmanWorkers
 * 处理由GearmanClient提交的任务
 */
final class Gearman extends BaseShell {

  //gearman worker最多个数
  const GEARMAN_MAX_WORKERS = 50;

  //gearman worker最少个数
  const GEARMAN_MINIMAL_WORKERS = 3;

  private static $WEIXIN_CONFIG = array(
    'WEIXIN_APP_ID' => 'wx318680eae930969f',
    'WEIXIN_APP_SECRET' => '5961e808c2339acc0aecd53802798f3c',
    'WEIXIN_AES_KEY' => 'CIwJbp3HMD9Vx7tfRJ9YByKqNFl3chiiMkepAuvb9Jh',
    'WEIXIN_ACCOUNT_NAME' => 'gh_85b2af0e4fb4',
    'WEIXIN_SERVER_TOKEN' => 'HELLOQUANWAI',
  );

  //gearman worker任务配置
  private static $config = array(
    array('num' => 2, 'jobs' => array('send_msg' => 'Gearman::sendMsg')),
    array('num' => 1, 'jobs' => array('rc_game_msg' => 'Gearman::sendRcGameMsg', 'rc_chat_msg' => 'Gearman::sendRcChatRoomMsg')),
  );

  //记录gearman worker的pid
  private static $WorkerPids = array();

  //通过self instance去调用WeixinService/WxpayService等
  private static $instance = null;
  private static function getSelfInstance () {
    if (!self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private function start () {
    while (TRUE) {
      if (!self::$config) break;

      foreach (self::$config as $key => $workerConfig) {
        try {
          if (!($workerConfig['num'] && $workerConfig['jobs'] && count(self::$WorkerPids) <= self::GEARMAN_MAX_WORKERS)) {
            throw new Exception('config is worng or workers num overflowed!');
          }

          for ($i = 0; $i < $workerConfig['num']; $i++) {
            $worker = $this->getGearmanService()->getGearmanWorker();
            if (!$worker) {
              Utils::log('generate new GearmanWorker object failed!', 'gearman_failed');
              continue;
            }

            foreach ($workerConfig['jobs'] as $jobName => $funcName) {
              $worker->addFunction($jobName, $funcName);
            }

            //fork 子进程并保持运行接收任务
            $pid = pcntl_fork();
            if ($pid == -1) { //fork 失败
              Utils::log('fork child process failed', 'gearman_failed');
              continue;

            } else if ($pid) { //父进程
              self::$WorkerPids[] = $pid;
              unset($worker, $pid);

            } else { //子进程
              while (TRUE) {
                try {
                  if (!$worker->work()) throw new Exception('worker listening process failed');
                  if ($worker->returnCode() == GEARMAN_WORK_FAIL) throw new Exception($worker->error(), $worker->getErrno());
                } catch (Exception $e) {
                  Utils::log("Job Send Msg Failed, msg:{$e->getMessage()};", 'jobs_failed');
                }
              }
            }
          }
          unset($key, $workerConfig);
        } catch (Exception $e) {
          Utils::log("GearmanWorkers Failed:{$e->getMessage()}", 'gearman_failed');
        }
      }

      //父进程回收子进程
      while (self::$WorkerPids) {
        foreach (self::$WorkerPids as $key => $pid) {
          if (pcntl_waitpid($pid, $status, WNOHANG)) {
            unset(self::$WorkerPids[$key]);
          }
        }
        sleep(3);
      }
    }
  }

  /**
   * @desc 处理发消息任务的函数,必须声明为public
   * @param object $GearmanJob
   * @return bool
   */
  public static function sendMsg ($job) {
    $taskInfoJson = $job->workload();
    $taskInfo = $taskInfoJson ? json_decode($taskInfoJson, TRUE) : array();
    if (!$taskInfo) {
      Utils::log("Job Send Msg Failed, task info is null! task info:{$taskInfoJson}", 'jobs_failed');
      return FALSE;
    }

    $wxConfig = self::$WEIXIN_CONFIG;
    $token = self::getSelfInstance()->getCommonService()->getWxAccesstoken($wxConfig['WEIXIN_APP_ID'], $wxConfig['WEIXIN_APP_SECRET']);
    if (!$token) {
      Utils::log("Job Send Msg Failed, get access token failed!", 'jobs_failed');
      return FALSE;
    }
    $wxConfig['WEIXIN_ACCESS_TOKEN'] = $token;

    $wxService = self::getSelfInstance()->getWeixinService();
    $wxService->setWeixinConfig($wxConfig);
    foreach ($taskInfo as $task) {
      try {
        if (!$wxService->sendMsg($task)) throw new Exception('job failed');
      } catch (Exception $e) {
        Utils::log("Job Send Msg Failed, msg:{$e->getMessage()}; task info:" . json_encode($task), 'jobs_failed');
      }
    }
    return TRUE;
  }

  /**
   * @desc 发送融云游戏消息
   * @param object $GearmanJob
   * @return bool
   */
  public static function sendRcGameMsg ($job) {
    $taskInfoJson = $job->workload();
    $taskInfo = $taskInfoJson ? json_decode($taskInfoJson, TRUE) : array();
    if (!$taskInfo) {
      Utils::log("Job Send Msg Failed, task info is null! task info:{$taskInfoJson}", 'jobs_failed');
      return FALSE;
    }

    try {
      if (isset($taskInfo['gameMsg']['content'])) $taskInfo['gameMsg']['content'] = (object) $taskInfo['gameMsg']['content'];
      if (!self::getMessageService()->sendRcChatRoomGameMessage($taskInfo['fromUser'], $taskInfo['gameMsg'])) {
        throw new Exception('job failed');
      }
    } catch (Exception $e) {
      Utils::log("Job Send RC Game Msg Failed, msg:{$e->getMessage()}; task info:{$taskInfoJson}", 'jobs_failed');
    }
    return TRUE;
  }

  /**
   * @desc 发送融云聊天室消息
   * @param object $GearmanJob
   * @return bool
   */
  public static function sendRcChatRoomMsg ($job) {
    $taskInfoJson = $job->workload();
    $taskInfo = $taskInfoJson ? json_decode($taskInfoJson, TRUE) : array();
    if (!$taskInfo) {
      Utils::log("Job Send Msg Failed, task info is null! task info:{$taskInfoJson}", 'jobs_failed');
      return FALSE;
    }

    try {
      if (!self::getMessageService()->sendRcChatRoomMessage(
        $taskInfo['fromUser'], $taskInfo['roomIds'], $taskInfo['tplName'], $taskInfo['tplData'], TRUE
      )) {
        throw new Exception('job failed');
      }
    } catch (Exception $e) {
      Utils::log("Job Send Msg Failed, msg:{$e->getMessage()}; task info:{$taskInfoJson}", 'jobs_failed');
    }
    return TRUE;
  }

  //init
  public static function run ($processNum, $currentProcessId) {
    $self = new self();

    //启动进程数
    $self->processNum = $processNum;
    //当前进程ID
    $self->currentProcessId = $currentProcessId;
    //自动分配每个进程处理数据量
    $self->generalPerProcessDataCount();

    $self->println("-------- Start, ".date(DATE_FORMAT)." --------");
    $self->start();
    $self->println("-------- End, ".date(DATE_FORMAT)." --------");
    unset($self);
  }

  //每个进程处理数据量上限（默认0：自动分配）
  private function generalPerProcessDataCount () {
    $this->dataCount = 0;
    $this->perProcessDataCount = intval(ceil($this->dataCount / $this->processNum));
  }

  private static function getMessageService () {
    return ServiceFactory::getInstance()->createMessageService();
  }
}
