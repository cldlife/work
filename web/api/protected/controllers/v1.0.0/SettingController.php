<?php
class SettingController extends BaseController {
  
  /**
   * @desc 服务器时间戳
   */
  public function actionGenerateSign () {
    $data = array();
    $data['t'] = time();
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 全局设置(APP每次启动调用)
   */  
  public function actionGlobal () {
    $data = array();
    
    //金币购买列表
    $data['coins_list'] = $this->globalAttributions['coins_list'];
    
    //好友门槛设置列表
    $data['friending_roses'] = $this->globalAttributions['friending_roses'];
    
    //购买金币微信支付开关: 0-关闭，1-开启（仅iOS有效，默认关闭开启iOS内购）
    $data['enabled_wxpay'] = 1;
    
    //Apple内购环境设置
    $data['apple_issandbox'] = 0;
    
    //H5链接配置
    //怎么玩
    $data['howtoplay_url'] = WEB_QW_APP_M_DOMAIN . '/app/webview/help.html';
    //邀请好友
    $data['invite_url'] = WEB_QW_APP_DOMAIN . '/d/{{uid}}.html';
    
    //版本更新设置
    //iPhone
    $data['update_setting'] = (object) array();
    if ($this->currentClientId == 1) {
      
      //审核中的版本设置
      if ($this->currentAppVersion >= '130') {
        $data['enabled_wxpay'] = 0;
        $data['apple_issandbox'] = 1;
      }
      
      //版本更新设置
      if ($this->currentAppVersion < '131') {
        $data['update_setting'] = array();
        $data['update_setting']['version'] = '1.3.1';
        $data['update_setting']['updated_title'] = '玩主APP1.3.1更新啦';
        $data['update_setting']['updated_content'] = array('全新上线了我拍你画游戏', '可以找附近的小伙伴啦', '私聊功能升级可以群聊啦');
        $data['update_setting']['download_link'] = '';
      }
    
      //Android
    } elseif ($this->currentClientId == 2) {
    
    }
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 意见反馈
   */
  public function actionFeedback () {
    $content = $this->getSafeRequest('content');
    $contactInfo = $this->getSafeRequest('contact_info');
  
    //参数验证
    if (!$content || !$contactInfo) $this->outputJsonData(1, array(
      'apptip' => '请填写您的宝贵建议和联系方式，帮助我们改进产品和服务，谢谢！'
    ));
  
    //Flood Start 缓存 (1天内仅提交1次)
    $cacheKey = __FUNCTION__ . '_UID_' . $this->currentUser['uid'];
    $waiting = $this->getCommonService()->getFromMemcache($cacheKey);
    if ($waiting) $this->outputJsonData(1, array(
      'apptip' => '我们已经收到你的反馈信息，目前正在处理中，非常谢谢！'
    ));
  
    //提交反馈
    $this->getBkAdminService()->addFeedback(array(
      'uid' => $this->currentUser['uid'],
      'content' => $content,
      'contact_info' => $contactInfo,
    ));
  
    //Flood End 缓存
    $this->getCommonService()->setToMemcache($cacheKey, TRUE, 86400);
  
    $this->outputJsonData(0, array(
      'apptip' => '提交成功，谢谢！'
    ));
  }
  
  /**
   * @desc 举报
   * @param int report_id, 1-爆照 2-用户主页
   * @param content json 1-{"tid": "6546454546789"}, 2-{"uid": "1234564564646"}
   */
  private static $reportFieldMap = array(1 => 'tid', 2 => 'uid');
  public function actionReport () {
    $reportId = $this->getSafeRequest('report_id', 0, 'int');
    $content = $this->getSafeRequest('content', array(), 'json');
    
    //参数验证
    $isvalid = $reportId && $content;
    if ($isvalid) $isvalid = self::$reportFieldMap[$reportId] && $content[self::$reportFieldMap[$reportId]];
    if (!$isvalid) $this->outputJsonData(1, array(
      'apptip' => '请选择您要举报的内容，谢谢！'
    ));
    
    //小主不可举报
    $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
    if ($content[self::$reportFieldMap[$reportId]] == $officialUserInfo['uid']) $this->outputJsonData(0, array(
      'apptip' => '小主已经收到您的举报了！'
    ));
    
    //Flood Start 缓存 (1天内仅对同一类型举报1次)
    $cacheKey = __FUNCTION__ . '_UID_' . $this->currentUser['uid'] . '_RELATIONID_' . $content[self::$reportFieldMap[$reportId]];
    $waiting = $this->getCommonService()->getFromMemcache($cacheKey);
    if (!$waiting) {
      if ($this->getBkAdminService()->addReport(array(
        'uid' => $this->currentUser['uid'],
        'report_id' => $reportId,
        'relation_id' => $content[self::$reportFieldMap[$reportId]]
      ))) {
        //Flood End 缓存
        $this->getCommonService()->setToMemcache($cacheKey, TRUE, 86400);
      
        //举报（系统消息）
        $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
        $this->getMessageService()->sendRcImMessage($officialUserInfo, array($this->currentUser['uid']), 'report');
      }
    }
    
    $this->outputJsonData(0, array(
      'apptip' => '感谢举报，我们会在24小时内处理！'
    ));
  }
}
?>