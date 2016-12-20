<?php
/**
 * @desc 我/TA的后宫
 */
class WxAction extends CAction {

  //默认金币
  const INIT_COIN_RULEID = 30;
  const INIT_VALUES_RULEID = 2;
  
  private static $WEIXIN_CONFIG = NULL;
  
  //关注默认回复消息
  private static $defaultMsg = array('item' => array(
    'Title' => '点击进入全民后宫',
    'Description' => '进入后宫将朋友抓为奴隶赚金币！',
    'PicUrl' => 'http://s.wanzhucdn.com/ui/img/wx/hougong/wxmsg_960x500.jpg',
    'Url' => '',
  ));
  
  /**
   * @desc 错误处理回调方法
   */
  public function errorRedirect () {
    Yii::app()->runController('site/error');
  }
  
  /**
   * @desc actions 主入口
   * @see 初始化公众号配置
   */
  public function run () {
    self::$WEIXIN_CONFIG = Yii::APP()->params['weixinConfig']['houGong'];
    $this->getController()->getWeixinService()->setWeixinConfig(self::$WEIXIN_CONFIG);
    $method = $this->getController()->getURIDoAction($this, 2);
    $this->$method();
  }
  
  /**
   * @desc 接收并处理全民后宫公众号事件和消息
   */
  private function doApi () {
    $signature = $this->getController()->getSafeRequest('signature');
    $timestamp = $this->getController()->getSafeRequest('timestamp');
    $nonce = $this->getController()->getSafeRequest('nonce');
    $echostr = $this->getController()->getSafeRequest('echostr');
    $msgSig = $this->getController()->getSafeRequest('msg_signature');
    $recvMsg = file_get_contents('php://input');
    
    try {
      if ($recvMsg && $msgSig && $timestamp && $nonce && $this->getController()->getWeixinService()->verifyWxMsgSignature(array($timestamp, $nonce), $recvMsg, $msgSig)) {
        //获取收到微信的消息
        $msg = $this->getController()->getWeixinService()->getWxMsg($recvMsg);
        if (!$msg || !$msg['FromUserName'] || !$msg['MsgType']) {
          echo 'success';
          exit;
        }

        //微信消息排重
        $cacheKey = ($msg['MsgType'] == 'event') ? $msg['FromUserName'] . $msg['CreateTime'] : $msg['MsgId'];
        if ($this->getController()->getCommonService()->getFromMemcache($cacheKey)) {
          echo 'success';
          exit;
        } else {
          $this->getController()->getCommonService()->setToMemcache($cacheKey, TRUE, 30);
        }
        
        $isNewsMsg = FALSE;
        $replyMsg = array();
        $content = '';
        
        //处理事件或消息
        switch ($msg['MsgType']) {
        case 'event' :
          //event: subscribe, SCAN, CLICK, LOCATION, VIEW
          //subscribe: $msg['EventKey'] = 'qrscene_{$room_id}'; SCAN: $msg['EventKey'] = '{$room_id}'
          //关注公众号
          if ($msg['Event'] == 'subscribe') {
            
            //授权微信用户信息
            $currentUser = $this->getWxUserInfo($msg['FromUserName']);
            if (!$currentUser) {
              echo 'success';
              exit;
            }
            
            //关注默认回复消息
            $isNewsMsg = TRUE;
            self::$defaultMsg['item']['Url'] = WEB_QW_APP_WX_DOMAIN . "/hougong/mine/u{$currentUser['uid']}.html";
          }
          break;

        case 'text':
          break;

        default:
          //msg type: image, voice, video, shortvideo, location, link
          break;
        }

        //处理回复消息
        if ($isNewsMsg) {
          $replyMsg = array(
            'openid' => $msg['FromUserName'],
            'msgtype' => 'news',
            'timestamp' => $timestamp,
            'nonce' => $nonce,
            'content' => self::$defaultMsg,
          );
        } else if ($content) {
          $replyMsg = array(
            'openid' => $msg['FromUserName'],
            'msgtype' => 'text',
            'content' => $content,
            'timestamp' => $timestamp,
            'nonce' => $nonce,
          );
        }
        echo $replyMsg ? $this->getController()->getWeixinService()->setWxMsg($replyMsg) : 'success';

      //微信服务器修改配置验证回复
      } else if ($echostr && $signature && $this->getController()->getWeixinService()->verifyWxServerConfig(array($timestamp, $nonce), $signature)) {
        echo $echostr;

      } else {
        echo 'success';
      }
    } catch (Exception $e) {
      Utils::log("msg:". json_encode($msg) ."HougongError:{$e->getMessage()}", 'hougong_error');
      echo 'success';
    }
    exit;
  }

  /**
   * @desc 获取用户信息，没有则添加新的用户信息
   * @param string $openid
   * @return array
   */
  private function getWxUserInfo ($openid) {
    $userInfo = array();
    if ($openid) {
      $wxIndex = $this->getController()->getUserService()->getUserWeixinOpenidIndex($openid);
      $userInfo = ($wxIndex) ? $this->getController()->getUserService()->getUserWeixinInfo($wxIndex['uid']) : array();
      $weixinConfig = Yii::APP()->params['weixinConfig']['houGong'];
      if (!$userInfo) $token = $this->getController()->getCommonService()->getWxAccesstoken($weixinConfig['WEIXIN_APP_ID'], $weixinConfig['WEIXIN_APP_SECRET']);
      if (!$userInfo && $token) {
        $this->getController()->getWeixinService()->setAccessToken($token);
        $userWxInfo = $this->getController()->getWeixinService()->getUserInfo(array('openid' => $openid));
        if ($userWxInfo) {
          if (!$userWxInfo['unionid']) $userWxInfo['unionid'] = $userWxInfo['openid'];
          $userWxInfo['appid'] = $weixinConfig['WEIXIN_APP_ID'];
          $userWxInfo['gender'] = $this->getController()->getUserService()->getGender($userWxInfo['sex']);
          $userWxInfo['avatar'] = $userWxInfo['headimgurl'];
          $userWxInfo['reg_ip'] = Yii::app()->request->userHostAddress;
          $userWxInfo['wx_from'] = 2;//来自公众号
          $userInfo = $this->getController()->getUserService()->loginByWeixin($userWxInfo);
          $userInfo = ($userInfo) ? $userInfo['weixin_user'] : array();
          $userInfo['openid'] = $openid;
          
          //新用户首次赠送金币
          if ($userInfo['is_new']) {
            //赠送默认金币
            $this->getController()->getUserFortuneService()->autoUserFortuneCoin($userInfo['uid'], self::INIT_COIN_RULEID);
            //赠送默认身价
            $this->getController()->getUserFortuneService()->autoUserFortuneValues($userInfo['uid'], self::INIT_VALUES_RULEID);
          }
        }
      }
    }
    return $userInfo;
  }
}
