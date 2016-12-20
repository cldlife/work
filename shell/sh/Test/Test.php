<?php
final class Test extends BaseShell {

  private static $WEIXIN_CONFIG = array(
    'WEIXIN_APP_ID' => '',
    'WEIXIN_APP_SECRET' => '',
    'WEIXIN_AES_KEY' => '',
    'WEIXIN_ACCOUNT_NAME' => '',
    'WEIXIN_SERVER_TOKEN' => '',
  );

  //通过self instance去调用WeixinService/WxpayService等
  private static $instance = null;
  private static function getSelfInstance () {
    if (!self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
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

  private function start () {
    /*
    $config = array(
      'WEIXIN_APP_ID' => 'wxf7c89def8c7c537b',
      'WEIXIN_APP_SECRET' => 'd4624c36b6795d1d99dcf0547af5443d',
      'WEIXIN_ACCOUNT_NAME' => 'gh_cd13d24a8cce',
    );
    $token = $this->getCommonService()->getWxAccesstoken($config['WEIXIN_APP_ID'], $config['WEIXIN_APP_SECRET']);
    if ($token) {
      $config['WEIXIN_ACCESS_TOKEN'] = $token;
      $this->getWeixinService()->setWeixinConfig($config);
      try {
        var_dump($this->getWeixinService()->getTmpQrcode(array('expire_seconds' => 3600, 'scene_id' => 'hello')));
      } catch (Exception $e) {
        echo "{$e->getMessage()}\n";
      }
    }
    */
    /*
    $room = $this->getGameService()->getRoomById(9);
    $game = $this->getGameService()->getGameById($room['game_id']);
    $user = $this->getGameService()->getWanzhuWxUserInfo(10);
    try {
      $spy = new SpyGame($room, $game, $user);
      $spy->getStatus();
    } catch (Exception $e) {
      echo "{$e->getMessage()}\n";
    }
    */
    $users = array(
      /*
      array('openid' => 'o9zG2wBoy06VN-QexfE9sSpk7WLY'),
      array('openid' => 'o9zG2wO-1pzV5NPZNF7Bd3cYQqUM'),
       */
      array('openid' => 'ozwqws_jEj9s7XvX3tcO_OrXSLHs'),
    );
    var_dump(GameMsg::sendAsyncMsg($users, "hello\nHI!"));
    /*
    $gearman = $this->getGearmanService()->getGearman();
    var_dump($gearman->returnCode());
    var_dump($gearman->ping('hello'));
    var_dump($gearman->doStatus());
    $wxConfig = self::$WEIXIN_CONFIG;
    var_dump(self::getSelfInstance()->getCommonService()->getWxAccesstoken($wxConfig['WEIXIN_APP_ID'], $wxConfig['WEIXIN_APP_SECRET']));
    */
  }
  /*
  private function getCommonService () {
    return ServiceFactory::getInstance()->createCommonService();
  }
  private function getWeixinService () {
    return ServiceFactory::getInstance()->createWeixinService();
  }
   */
}
