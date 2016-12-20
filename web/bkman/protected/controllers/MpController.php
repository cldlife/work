<?php
/**
 * @desc 公众号管理
 */
class MpController extends BaseController {
  
  /**
   * @desc actions 主入口 
   */
  public function run ($actionID = NULL) {
    parent::filters();
    $this->defaultURIDoAction = '';
    $method = $this->getURIDoAction($this);
    $this->$method();
  }
  
  /**
   * @desc 错误处理回调方法
   */
  public function errorRedirect () {
    $this->redirect($this->getDeUrl('main/error', array('id' => -404)));
  }
  
  /**
   * @desc 公众号列表
   */
  private function doIndex() {
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');
    if (!$page) $page = 1;
    $pageSize = 20;
    
    //列表
    $mpList = array();
    $mps = $this->getWeigameService()->getWeigameMpinfo($page, $pageSize);
    if ($mps) {
      foreach ($mps as $mp) {
        $mp['jsdomains'] = $this->getWeigameService()->getWeigameMpDomainsByMpid($mp['mp_id'], 0);
        $mp['sqdomains'] = $this->getWeigameService()->getWeigameMpDomainsByMpid($mp['mp_id'], 1);
        $mp['paydomains'] = $this->getWeigameService()->getWeigameMpDomainsByMpid($mp['mp_id'], 2);
        $mp['cdate'] = Utils::getDiffTime($mp['created_time']);
        $mpList[] = $mp;
        unset($mp);
      }
    }
    //分页处理
    $listPageCount = self::DEFAULT_PAGER_PAGESIZE * $pageSize;
    $listCount = count($mpList); 
    if ($listCount < $pageSize) {
      $listPageCount = ($page - 1) * $pageSize + $listCount;
    }
    
    $show = array();
    $show['curPage'] = $page;
    $show['mpList'] = $mpList;
    $show['pager'] = $this->getPager($listPageCount, $page, $pageSize);
    $this->title = '公众号管理';
    $this->render('list', $show);
  }
   
  /**
   * @desc 添加/编辑公众号 (ajax异步)
   */
  private function doAddEdit() {
    $mpName = $this->getSafeRequest('mp_name', '', 'POST');
    $appId = $this->getSafeRequest('app_id', '', 'POST');
    $appSecret = $this->getSafeRequest('app_secret', '', 'POST');
    $type = $this->getSafeRequest('type', '', 'POST');
    
    //添加公众号
    $code = 0;
    if ($mpName && $appId && $appSecret) {
      $insertFields = array();
      $insertFields['mp_name'] = $mpName;
      $insertFields['app_id'] = $appId;
      $insertFields['app_secret'] = $appSecret;
      if ($type) $insertFields['type'] = $type;
      $res = $this->getWeigameService()->addWeigameMpinfo($insertFields);
      if ($res) $code = 1;
    }
    
    $ret = array();
    $ret['code'] = $code;
    $this->outputJsonData($ret);
  }
  
  /**
   * @desc 保存公众号JsDomains (ajax异步)
   */
  private function doSaveJsDomains() {
    $mpId = $this->getSafeRequest('mp_id', '', 'POST');
    $type = $this->getSafeRequest('type', 0, 'POST');
    //JSSDK域名
    $jsdoman1 = $this->getSafeRequest('jsdoman1', '', 'POST');
    $jsdoman2 = $this->getSafeRequest('jsdoman2', '', 'POST');
    $jsdoman3 = $this->getSafeRequest('jsdoman3', '', 'POST');
    $jsdomans = array($jsdoman1, $jsdoman2, $jsdoman3);
    //授权域名
    $mpsqdomain = $this->getSafeRequest('mpsqdomain', '', 'POST');
    //支付安全目录
    $paydomain1 = $this->getSafeRequest('paydomain1', '', 'POST');
    $paydomain2 = $this->getSafeRequest('paydomain2', '', 'POST');
    $paydomain3 = $this->getSafeRequest('paydomain3', '', 'POST');
    $paydomain = array($paydomain1, $paydomain2, $paydomain3);
    $code = 0;
    //获取公众号
    // $jsdomans = array('','weigame.wanzhu.com','');
    // $mpId = 3;
    // $type = 0;
    $mpInfo = $this->getWeigameService()->getWeigameMpinfoById($mpId);
    //JsSDK域名
    if ($mpInfo && $jsdomans && $type == 0){
      $mpInfo['jsdomains'] = $this->getWeigameService()->getWeigameMpDomainsByMpid($mpInfo['mp_id'], $type);
      $domainAddress = array();
      if ($mpInfo['jsdomains']) foreach ($mpInfo['jsdomains'] as $domain) {
        $domainAddress[$domain['id']] = $domain['domain_address'];
      }
      //新增域名
      $addedDomains = array_diff($jsdomans, $domainAddress); 
      if ($addedDomains) {
        foreach ($addedDomains as $addedDomain) {
          if ($this->getWeigameService()->addWeigameMpDomain(array(
            'mp_id' => $mpInfo['mp_id'],
            'domain_address' => $addedDomain,
            'type' => $type
          ))) {
            //同步添加到域名系统(微信jssdk域名级别 & 未使用)
            $this->getWeigameService()->addDomain(array(
              'address' => $addedDomain,
              'level' => 5,
              'status' => 1,
            ));
          }
        }
      }
      
      //删除域名
      $deledDomains = array_diff($domainAddress, $jsdomans);
      if ($mpInfo['jsdomains'] && $deledDomains) {
        $domainAddressIds = array_flip($domainAddress);
        foreach ($deledDomains as $deledDomain) {
          $this->getWeigameService()->deleteWeigameMpDomain($domainAddressIds[$deledDomain], $deledDomain, $mpInfo['mp_id'], $type);
        }
      }
      $code = 1;
    }

    //授权域名
    if ($mpInfo && $mpsqdomain && $type == 1){
      $sqdomains = $this->getWeigameService()->getWeigameMpDomainsByMpid($mpInfo['mp_id'], $type);
      if ($sqdomains) $this->getWeigameService()->deleteWeigameMpDomain($sqdomains[0]['id'], $sqdomains[0]['domain_address'], $mpInfo['mp_id'], $type);
      if($this->getWeigameService()->addWeigameMpDomain(array(
            'mp_id' => $mpInfo['mp_id'],
            'domain_address' => $mpsqdomain,
            'type' => $type
      ))){
        $code = 1;
      }
    }
    
    //支付域名
    if ($mpInfo && $paydomain && $type == 2){
      $mpInfo['paydomain'] = $this->getWeigameService()->getWeigameMpDomainsByMpid($mpInfo['mp_id'], $type);
      $domainAddress = array();
      if ($mpInfo['paydomain']) foreach ($mpInfo['paydomain'] as $domain) {
        $domainAddress[$domain['id']] = $domain['domain_address'];
      }
      
      //新增域名
      $addedDomains = array_diff($paydomain, $domainAddress);
      if ($addedDomains) {
        foreach ($addedDomains as $addedDomain) {
          if ($this->getWeigameService()->addWeigameMpDomain(array(
            'mp_id' => $mpInfo['mp_id'],
            'domain_address' => $addedDomain,
            'type' => $type
          ))) {
            //同步添加到域名系统(微信jssdk域名级别 & 未使用)
            $this->getWeigameService()->addDomain(array(
              'address' => $addedDomain,
              'level' => 3,
              'status' => 1,
            ));
          }
        }
      }
      
      //删除域名
      $deledDomains = array_diff($domainAddress, $paydomain);
      if ($mpInfo['paydomain'] && $deledDomains) {
        $domainAddressIds = array_flip($domainAddress);
        foreach ($deledDomains as $deledDomain) {
          $this->getWeigameService()->deleteWeigameMpDomain($domainAddressIds[$deledDomain], $deledDomain, $mpInfo['mp_id'], $type);
        }
      }
      $code = 1;
    }
    
    $ret = array();
    $ret['code'] = $code;
    $this->outputJsonData($ret);
  }
}
?>
