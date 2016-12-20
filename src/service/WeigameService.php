<?php
/**
 * @desc WeigameService
 */
class WeigameService extends BaseService {
  
  private function getWeigameDAO () {
    return DAOFactory::getInstance()->createWeigameDAO();
  }

  /**
   * @return 获取公众号列表
   */
  public function getWeigameMpinfo($page = 1, $pageSize = 10) {
    $mpList = array();
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $mpList = $this->getWeigameDAO()->findWeigameMpinfo($offset, $pageSize);
    }
    return $mpList;
  }

  /**
   * @return 获取公众号列表
   */
  public function getWeigameMpinfos() {
    $mpList = array();
    $mpList = $this->getWeigameDAO()->findWeigameMpinfos();
    return $mpList;
  }
  
  /**
   * @desc 获取公众号信息
   */
  public function getWeigameMpinfoById ($mpid) {
    $mpInfo = array();
    if ($mpid) {
      $mpInfo = $this->getWeigameDAO()->findWeigameMpinfoWithMpid($mpid);
    }
    return $mpInfo;
  }
  
  /**
   * @desc 添加公众号
   * @param array $fields
   * @return bool
   */
  public function addWeigameMpinfo ($fields) {
    if ($fields['mp_name'] && $fields['app_id'] && $fields['app_secret']) {
      return $this->getWeigameDAO()->insertWeigameMpinfoWithMpid($fields);
    }
    return FALSE;
  }
  
  /**
   * @desc 根据域名地址获取公众号信息
   */
  public function getWeigameMpInfoByDomainAddress ($domainAddress) {
    $mpJsDomain = array();
    if ($domainAddress) $mpJsDomain = $this->getWeigameDAO()->findWeigameMpDomainWithAddress($domainAddress);
    return ($mpJsDomain && $mpJsDomain['mp_id']) ? $this->getWeigameMpinfoById($mpJsDomain['mp_id']) : array();
  }
  
  /**
   * @desc 获取公众号域名列表 
   * @type 0.jssdk, 1.授权, 2.支付
   */
  public function getWeigameMpDomainsByMpid ($mpid, $type) {
    $mpJsDomains = array();
    if ($mpid) {
      $mpJsDomains = $this->getWeigameDAO()->findWeigameMpDomainsWithMpid($mpid, $type);
    }
    return $mpJsDomains;
  }
  
  /**
   * @desc 添加公众号JSSDK安全域名
   * @param array $fields
   * @return bool
   */
  public function addWeigameMpDomain ($fields) {
    if ($fields['mp_id'] && $fields['domain_address']) {
      return $this->getWeigameDAO()->insertWeigameMpDomain($fields);
    }
    return FALSE;
  }
  
  /**
   * @desc 删除公众号JSSDK安全域名
   * @param array $fields
   * @return bool
   */
  public function deleteWeigameMpDomain ($id, $address, $mpId, $type) {
    if ($id && $address && $mpId) {
      return $this->getWeigameDAO()->deleteWeigameMpDomain($id, $address, $mpId, $type);
    }
    return FALSE;
  }

  /**
   * @desc 获取域名
   */
  public function getDomains ($fields, $page = 1, $pageSize = 20) {
    $domain = array();
    $offset = ($page - 1) * $pageSize;
    $limit = $pageSize;
    $domain = $this->getWeigameDAO()->findDomains($fields, $offset, $limit);
    return $domain;
  }
  
  /**
   * @desc 获取上线的域名
   * @param int $domainLevel 域名级别，-1-全部，0-3
   * @param array $shiftConfig 域名轮换配置
   * @param int $domainCategory 域名级别分类，0-无
   * @see $shiftConfig {'level':0, 'shifttime': 1800, 'shiftlen':2}
   * level int 开启轮换的level
   * shifttime int 域名轮换时间,秒数,默认10分钟
   * shiftlen int 同时上线的域名个数
   * 
   * @return array
   */
  const DEFAULT_SHIFTTIME = 1800;
  const DEFAULT_SHIFTLEN = 2;
  public function getOnlineDomains ($domainLevel = -1, $shiftConfig = array(), $domainCategory = 0) {
    $domainList = $this->getWeigameDAO()->findOnlineDomains($domainLevel, $domainCategory);
    if ($domainList) {
      foreach ($domainList as $domain) {
        $onlineDomainList[$domain['level']][] = trim($domain['address']); //按level分组
      }
      unset($domainList);
     
      //域名轮换规则
      if ($shiftConfig && $shiftConfig['level'] >= 0) {
        $shifTtime = $shiftConfig['shifttime'] ? $shiftConfig['shifttime'] : self::DEFAULT_SHIFTTIME;
        $sametimeDomains = $shiftConfig['shiftlen'] ? $shiftConfig['shiftlen'] : self::DEFAULT_SHIFTLEN;
        
        $curShiftTimeStamp = (int) (time() / $shifTtime); 
        $onlineLevelDomainsLen = count($onlineDomainList[$shiftConfig['level']]);

        //获取数组切片起点的下标位置
        $onlineLevelDomainsStart = (int) ($curShiftTimeStamp % $onlineLevelDomainsLen);
        $levelOnlineDomains = array_slice($onlineDomainList[$shiftConfig['level']], $onlineLevelDomainsStart, $sametimeDomains);
        if (count($levelOnlineDomains) < $sametimeDomains) {
          $levelOnlineDomains = array_merge($levelOnlineDomains, array_slice($onlineDomainList[$shiftConfig['level']], 0, $sametimeDomains - count($levelOnlineDomains)));
        }
        unset($onlineDomainList[$shiftConfig['level']]);
        $onlineDomainList[$shiftConfig['level']] = $levelOnlineDomains;
      }
      
      return $onlineDomainList;
    }
    return array();
  }

  /**
   * @desc 根据$address获取level
   */
  public function getDomainByAddress ($address) {
    $domain = array();
    if ($address) {
      $domain = $this->getWeigameDAO()->findDomainWithAddress($address);
    }
    return $domain;
  }

  /**
   * @desc 添加新域名
   */
  public function addDomain ($fields) {
    if ($fields['address']) {
      $fields['address'] = trim($fields['address']);
      return $this->getWeigameDAO()->insertDomain($fields);
    }
    return FALSE;
  }

  /**
   * @desc 更新域名
   */
  public function updateDomain ($fields) {
    if ($fields['id']) {
      if ($fields['address']) $fields['address'] = trim($fields['address']);
      return $this->getWeigameDAO()->updateDomain($fields);
    }
    return FALSE;
  }

   /**
   * @desc 新建分组
   */
  public function addDomainGroup ($fields) {
    $domainGroup = array();
    if ($fields) {
      $domainGroup = $this->getWeigameDAO()->insertDomainGroup($fields);
    }
    return $domainGroup;
  }

  /**
   * @desc 获取全部分组,分页
   */
  public function getDomainGroup($page = 1, $pageSize = 10) {
    $domainGroup = array();
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $domainGroup = $this->getWeigameDAO()->findDomainGroup($offset, $pageSize);
    }
    return $domainGroup;
  }

  /**
   * @desc 获取全部分组
   */
  public function getDomainGroups() {
    $domainGroup = $this->getWeigameDAO()->findDomainGroups();
    return $domainGroup ? $domainGroup : array();
  }

  /**
   * @desc 获取分组by $level
   */
  public function getDomainGroupBylevel ($level) {
    $domain = array();
    if ($level >= 0) {
      $domain = $this->getWeigameDAO()->findDomainGroupBylevel($level);
    }
    return $domain;
  }

  /**
   * @desc 更新更新分组
   */
  public function updateDomainGroup ($level, $fields) {
    if ($fields && $level) {
      return $this->getWeigameDAO()->updateDomainGroup($level, $fields);
    }
    return FALSE;
  }

  /**
   * @desc 分页获取你懂我吗系列游戏列表
   * @param int page pagesize
   */
  public function getKnowgame ($page = 1, $pageSize = 10) {
    $list = array();
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $list = $this->getWeigameDAO()->findknowgame($offset, $pageSize);
    }
    return $list;
  }

  /**
   * @desc 增加你懂我吗系列游戏
   * @param array $fields
   */
  public function addKnowgame ($fields) {
    if (!$fields) {
      throw new Exception('array fields is null...');
    }
    return $this->getWeigameDAO()->insertknowgame($fields);
  }

  /**
   * @desc 根据ID获取你懂我吗系列游戏
   * @param int $checkgameid
   */
  public function getKnowGameById ($knowgameid) {
    $knowgame = array();
    if ($knowgameid) {
      $knowgame = $this->getWeigameDAO()->findknowGameById($knowgameid);
    }
    return $knowgame;
  }

  /**
   * @desc 更新你懂我吗系列游戏
   */
  public function updateKnowGame ($knowgameid, $fields) {
    if ($knowgameid) {
      $checkgame = $this->getWeigameDAO()->updateKnowGame($knowgameid, $fields);
    }
    return $checkgame;
  }

  /**
   * @desc 根据id获取你懂我吗题目信息
   */
  public function getKnowQuestionById($id) {
    $question = array();
    if ($id) {
      $question = $this->getWeigameDAO()->findKnowQuestionById($id);
    }
    return $question;
  }
  
  /**
   * @desc 新增你懂我吗题目信息
   * @param array $fields
   */
  public function addKnowQuestion($fields) {
    if (!$fields['uid'] || !$fields['qa_content']) {
      throw new Exception('uid or qa_content is null...');
    }
    
    //没有id，则生成qid
    if (!$fields['id']) {
      $qid = Utils::getMillisecond();
      $fields['id'] = $qid;
    }
    if ($this->getWeigameDAO()->insertKnowQuestion($fields)) {
      return $fields['id'];
    }
    
    return 0;
  }
  
  /**
   * @desc 更新你懂我吗题目信息
   * @param array $fields
   */
  public function updateKnowQuestion($fields) {
    if (!$fields['id'] || !$fields['uid'] || !$fields['qa_content']) {
      throw new Exception('qid or uid or qa_content is null...');
    }

    return $this->getWeigameDAO()->updateKnowQuestion($fields);
  }
  
  /**
   * @desc 根据题目qid获取你懂我吗答案列表
   */
  public function getKnowQuestionAnswers ($qid, $page = 1, $pageSize = 30) {
    $answers = array();
    if ($qid && $page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $answers = $this->getWeigameDAO()->findKnowQuestionAnswers($qid, $offset, $pageSize);
    }
    return $answers;
  }
  
  /**
   * @return 根据题目uid获取你懂我吗单个答案
   */
  public function getKnowQuestionAnswerByUid($qid, $uid) {
    $answer = array();
    if ($qid && $uid) {
      $answer = $this->getWeigameDAO()->findKnowQuestionAnswerByUid($qid, $uid);
    }
    return $answer;
  }
  
  /**
   * @return 根据答案id获取答案（单个）
   */
  public function getKnowQuestionAnswerByAid ($qid, $aid) {
    $answer = array();
    if ($qid && $aid) {
      $answer = $this->getWeigameDAO()->findKnowQuestionAnswerByAid($qid, $aid);
    }
    return $answer;
  }
  
  /**
   * @desc 新增你懂我吗题目答案信息
   * @param array $fields
   */
  public function addKnowQuestionAnswer($fields) {
    if (!$fields['qid'] || !$fields['uid'] || !$fields['qa_content']) {
      throw new Exception('qid, uid or qa_content is null...');
    }
  
    return $this->getWeigameDAO()->insertKnowQuestionAnswer($fields);
  }
  
  /**
   * @desc 更新你懂我吗题目答案信息
   * @param array $fields
   */
  public function updateKnowQuestionAnswer($fields) {
    if (!$fields['qid'] || !$fields['uid'] ) {
      throw new Exception('qid, uid  is null...');
    }
  
    return $this->getWeigameDAO()->updateKnowQuestionAnswer($fields);
  }

  /**
   * @desc 删除你懂我吗答案信息
   * @param int $id $qid $uid
   */
  public function deleteKnowQuestionAnswer($id, $qid, $uid) {
    if (!$id || !$qid || !$uid) {
      throw new Exception('id qid uid is null...');
    }
    return $this->getWeigameDAO()->deleteKnowQuestionAnswer($id, $qid, $uid);
  }

  /**
   * @desc 根据分组得到随机域名
   * @param int $level 分组; int $category 域名分站分类
   */
  public function getRandDomain ($level, $category = 0) {
    if($level) {
      $domainGroup = $this->getDomainGroupBylevel($level);
      //当前域名分组级别为集合页（level=2）,则按域名分类随机域名
      $curDomainCategory = 0;
      //$category参数优先直接指定$curDomainCategory
      if ($level == 2) $curDomainCategory = $category ? $category : $this->domainCategory;
      if ($domainGroup['is_random']) {
        $domainList = $this->getOnlineDomains($level, array('level' => $level, 'shifttime' => $domainGroup['domain_cycle_times'], 'shiftlen' => $domainGroup['domain_cycle_lens']), $curDomainCategory);
      } else {
        $domainList = $this->getOnlineDomains($level, array(), $curDomainCategory);
      }
      
      //从域名列表中随机提取
      $randDomain = '';
      if ($domainList) {
        $ctRandDomains = $domainList[$level];
        $randId = mt_rand(0, count($ctRandDomains) - 1);
        $randDomain = $ctRandDomains[$randId];
        
        if ($randDomain) {
          //生成随机字符的3级域名
          $thirdLevelDomain = '';
          if ($domainGroup['is_twodomain']) {
            $thirdLevelDomain = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 6);
          }
          
          //设置同步授权会话suuid的参数串
          if (stripos($randDomain, Utils::getHostDomainName()) === FALSE) {
            $this->loginTypeSuuidParams = $this->getLoginTypeSuuidParams(); 
          }
          
          //添加http://前缀
          $randDomain = $thirdLevelDomain ? "http://{$thirdLevelDomain}.{$randDomain}" : "http://{$randDomain}";
        }
      }
      return $randDomain;
    }
  }
}