<?php
/**
 * @desc 小游戏管理
 */
class DomainController extends BaseController {
  //微信支付appid
  private static $appid_mryhjh = 'wxf291838c0583510e';
  //烈手微信支付appid
  private static $appid_lieshou= 'wxb868c822b51c6a5a';
  //一堆微信支付appid
  private static $appid_yidui= 'wx5f497fd6eb2d3825';
  
  private static $domainStatus = array('已上线', '未使用', '已下线');

  //游戏集合页分类
  private static $gamelistCategories = array(1 => '游戏集合', 2 => '测试游戏', 3 => '1758', 4 => '小说集合'); 
  
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
   * @desc  分组列表
   */
  private function doGroupList() {
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');
    
    if (!$page) $page = 1;
    $pageSize = 10;
    $groupList = $this->getWeigameService()->getDomainGroup($page, $pageSize);
    //分页处理
    $listPageCount = self::DEFAULT_PAGER_PAGESIZE * $pageSize;
    $listCount = count($groupList);
    if ($listCount < $pageSize) {
      $listPageCount = ($page - 1) * $pageSize + $listCount;
    }
    $show = array();
    $show['curPage'] = $page;
    $show['groupList'] = $groupList;
    $show['pager'] = $this->getPager($listPageCount, $page, $pageSize);
    $this->title = '域名分组列表';
    $this->render('group/list', $show);
  }
  
  /**
   * @desc 添加/编辑游戏
   */
  private function doGroupAddEdit() {
    $action = $this->getSafeRequest('action');
    $code = $this->getSafeRequest('code', 0, 'GET', 'int');
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');    
    $groupid = $this->getSafeRequest('group_id', 0, 'GET', 'int');
    $name = $this->getSafeRequest('name', '', 'POST', 'string');
    $domainLevelUris = $this->getSafeRequest('domainLevelUris', '', 'POST', 'string');
    $is_twodomain = $this->getSafeRequest('is_twodomain', 0, 'POST', 'int');
    $is_move = $this->getSafeRequest('is_move', 0, 'POST', 'int');
    $is_random = $this->getSafeRequest('is_random', 0, 'POST', 'int');
    $domainCycleLens = $this->getSafeRequest('domainCycleLens', 0, 'POST', 'int');
    $domainCycleTimes = $this->getSafeRequest('domainCycleTimes', 0, 'POST', 'int');
    $baidu_code = $this->getSafeRequest('baidu_code', '', 'POST', 'string');
    $remark = $this->getSafeRequest('remark', '', 'POST', 'string');
    //获取分组
    $group = $this->getWeigameService()->getDomainGroupBylevel($groupid);
    //添加or编辑
    if ($action == "submit") {
      if ($group['level']) { 
        //编辑分组
        $updateFields = array();
        if ($name != $group['name']) $updateFields['name'] = $name;
        if ($domainLevelUris != $group['domain_level_uris']) $updateFields['domain_level_uris'] = $domainLevelUris;
        if ($is_move != $group['is_move']) $updateFields['is_move'] = $is_move;
        if ($is_twodomain != $group['is_twodomain']) $updateFields['is_twodomain'] = $is_twodomain;
        if ($is_random != $group['is_random']) $updateFields['is_random'] = $is_random;
        if ($domainCycleLens != $group['domain_cycle_lens']) $updateFields['domain_cycle_lens'] = $domainCycleLens;
        if ($domainCycleTimes != $group['domain_cycle_times']) $updateFields['domain_cycle_times'] = $domainCycleTimes;
        if ($remark != $group['remark']) $updateFields['remark'] = $remark;
        if ($baidu_code != $group['baidu_code']) $updateFields['baidu_code'] = $baidu_code;
        $this->getWeigameService()->updateDomainGroup($group['level'], $updateFields);
        $this->redirect($this->getDeUrl('domain/groupaddedit', array('id' => $this->permissionId, 'group_id' => $group['level'], 'code' => 1)));     
      } else {    
        //添加游戏 
        if ($name) {
          $group = $this->getWeigameService()->addDomainGroup(array(
            'name' => $name,
            'is_move' => $is_move,
            'is_twodomain' => $is_twodomain,
            'is_random' => $is_random,
            'domain_cycle_times' => $domainCycleTimes,
            'domain_cycle_lens' => $domainCycleLens,
            'domain_level_uris' => $domainLevelUris,
            'baidu_code'  => $baidu_code, 
            'remark'  => $remark, 
          ));
          if ($group['level']) {
            $this->redirect($this->getDeUrl('domain/groupaddedit', array('id' => $this->permissionId, 'group_id' => $group['level'], 'code' => 1)));
          }
        }
      }
    }

    $show = array();
    $show['code'] = $code;
    $show['curPage'] = $page;
    if ($group) $show['group'] = $group;
    $this->title = $group['level'] ? '编辑分组' : '添加分组';
    $this->render('group/addedit', $show);
  }

  
  /**
   * @desc 域名管理
   */
  private function doDomain () {
    $action = $this->getSafeRequest('action', 'search', 'POST', 'string');
    //查询
    if($action == 'search') {
      $status = $this->getSafeRequest('status', 0, 'GET', 'int');
      $level = $this->getSafeRequest('level', -1, 'GET', 'int');
      $category = $this->getSafeRequest('category', 0, 'GET', 'int');
      $page = $this->getSafeRequest('page', 1, 'GET', 'int');
      $pageSize = 20;
      $groups = $this->getWeigameService()->getDomainGroups();
      $groupAll = array();
      foreach ($groups as $key => $item) {
        $groupAll[$item['level']] = $item;
      }
      unset($groups);
      $queryFields = array();
      $queryFields['status'] = $status;
      if ($groupAll[$level]) $queryFields['level'] = $level;
      if ($level == 1 && $category) $queryFields['category'] = $category;
      $tmpDomainList = $this->getWeigameService()->getDomains($queryFields, $page, $pageSize);

      $domainList = array();
      if ($tmpDomainList) {
        foreach ($tmpDomainList as $tmpDomain) {
          $domain = array();
          $domain = $tmpDomain;
          $domain['domain_status'] = self::$domainStatus[$tmpDomain['status']] ? self::$domainStatus[$tmpDomain['status']] : self::$domainStatus[0];
          $domain['domain_level'] = $groupAll[$tmpDomain['level']] ? $groupAll[$tmpDomain['level']] : $groupAll[0];
          $domain['cdate'] = Utils::getDiffTime($tmpDomain['created_time']);
          $domain['update'] = $tmpDomain['updated_time'] ? Utils::getDiffTime($tmpDomain['updated_time']) : '未更新';
          $domainList[] = $domain;
          unset($domain);
        }
      } 
 
      //分页处理
      $count = self::DEFAULT_PAGER_PAGESIZE * $pageSize;
      $domainListCount = count($domainList);
      $domainListCount = $domainListCount ? $domainListCount : 1;
      if ($domainListCount < $pageSize) {
        $count = ($page - 1) * $pageSize + $domainListCount;
      }
      
      //获取不同级别当前正在轮换的域名（TODO shifttime同weigame配置）
      $curOnlineDomains = '';
      $onlineDomains = array();
      if ($level >= 0 && $groupAll[$level]['is_random'] == 1) $onlineDomains = $this->getWeigameService()->getOnlineDomains($level, array('level' => $level, 'shifttime' => $groupAll[$level]['domain_cycle_times'], 'shiftlen' => $groupAll[$level]['domain_cycle_lens']))[$level];
      if ($onlineDomains) $curOnlineDomains = implode(' , ', array_unique($onlineDomains));
      //获取游戏名称备注

      if ($level >= 0) {
        $domainGroup = $this->getWeigameService()->getDomainGroup($level);
        //获取单个分组
        $group = $this->getWeigameService()->getDomainGroupBylevel($level);
      }
      $data = array();
      $data['pager'] = $this->getPager($count, $page, $pageSize);
      $data['curLevel'] = $level;
      $data['curStatus'] = $status;
      $data['curOnlineDomains'] = $curOnlineDomains;
      $data['domainStatus'] = self::$domainStatus;
      $data['domainLevel'] = $groupAll;
      $data['category'] = $category;
      $data['gamelistCategories'] = self::$gamelistCategories;
      $data['domainLevelUris'] = $groupAll[$level]['domain_level_uris'];
      $data['domainCycleTimes'] = $groupAll[$level]['domain_cycle_times'];
      $data['domainCycleLens'] = $groupAll[$level]['domain_cycle_lens'];
      $data['is_twodomain'] = $groupAll[$level]['is_twodomain'];
      $data['remark'] = $groupAll[$level]['remark'];
      $data['domainGroup'] = $domainGroup;
      $data['is_move'] = $group['is_move'];
      $data['domainList'] = $domainList;
      $this->render('domain/domain', $data);
      
    } else if ($action == 'add') { 
      //添加域名
      $address = $this->getSafeRequest('address', '', 'POST', 'string');
      $status = $this->getSafeRequest('status', 1, 'POST', 'int');
      $expiring_date = $this->getSafeRequest('expiring_date', '', 'POST', 'string');
      $level = $this->getSafeRequest('level', 0, 'POST', 'int');
      $remarks = $this->getSafeRequest('remarks', '', 'POST', 'string');
      $category = $this->getSafeRequest('category', '', 'POST', 'int');
      if ($address) {
        $domain = $this->getWeigameService()->getDomainByAddress($address);
        if ($domain && $domain['level']) {
          //获取单个分组 
          $group = $this->getWeigameService()->getDomainGroupBylevel($domain['level']);
          if (!$group['is_move']) {
            $this->outputJsonData(array('code' => 3));
          }
        }
        $fields = array();
        $fields['address'] = $address;
        $fields['status'] = $status;
        $fields['level'] = $level;
        if ($level == 6 && $category) $fields['category'] = $category;
        $fields['expiring_date'] = $expiring_date;
        $fields['remarks'] = $remarks;
        if ($this->getWeigameService()->addDomain($fields))
          $this->outputJsonData(array('code' => 0));
        else
          $this->outputJsonData(array('code' => 2));
      } else {
        $this->outputJsonData(array('code' => 1));
      }
    } else if ($action == 'update') {
      //更新域名
      $id = $this->getSafeRequest('domain_id', 0, 'POST', 'int');
      $address = $this->getSafeRequest('address', '', 'POST', 'string');
      $status = $this->getSafeRequest('status', 1, 'POST', 'int');
      $level = $this->getSafeRequest('level', NULL, 'POST', 'int');
      $expiring_date = $this->getSafeRequest('expiring_date', '', 'POST', 'string');
      $remarks = $this->getSafeRequest('remarks', '', 'POST', 'string');
      $category = $this->getSafeRequest('category', '', 'POST', 'int');
      if ($id && $address) {
        $domain = $this->getWeigameService()->getDomainByAddress($address);
        if ($domain && $domain['level']) {
          //获取单个分组 
          $group = $this->getWeigameService()->getDomainGroupBylevel($domain['level']);
          if (!$group['is_move']) {
            $this->outputJsonData(array('code' => 3));
          }
        }  
        $fields = array();
        $fields['id'] = $id;
        $fields['status'] = $status;
        if ($address) $fields['address'] = $address;
        if ($category) $fields['category'] = $category;
        if ($level !== NULL) $fields['level'] = $level;
        if ($expiring_date) $fields['expiring_date'] = $expiring_date;
        $fields['remarks'] = $remarks;
        if ($this->getWeigameService()->updateDomain($fields))
          $this->outputJsonData(array('code' => 0));
        else
          $this->outputJsonData(array('code' => 2));
      } else {
        $this->outputJsonData(array('code' => 1));
      }
    } else if ($action == 'updategroup') {
      //更新游戏名称备注
      $level = $this->getSafeRequest('level', 0, 'POST', 'int'); 
      $remark_group = $this->getSafeRequest('remark_group', '', 'POST', 'string');
      if ($remark_group) {  
        $fields = array();
        $fields['level'] = $level;
        $fields['remark_group'] = $remark_group;
        if ($this->getWeigameService()->updateDomainGroup($fields))
          $this->outputJsonData(array('code' => 0));
        else
          $this->outputJsonData(array('code' => 2));
      } else {
        $this->outputJsonData(array('code' => 1));
      }
    }
  }
}
?>
