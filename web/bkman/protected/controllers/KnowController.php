<?php
/**
 * @desc  测测付费小游戏管理
 */
class knowController extends BaseController {
  /**
   * @desc actions 主入口
   */
  public function run () {
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
   * @desc  列表查询
   */
  private function doIndex() {
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');
    
    if (!$page) $page = 1;
    $pageSize = 10;
    $knowList = $this->getWeigameService()->getKnowgame($page, $pageSize);
    if ($knowList) {
      $list = array();
      foreach ($knowList as $key => $item) {
        $list[$key] = $item; 
        if ($item['pay_mpid']) {
          $mpInfo = $this->getWeigameService()->getWeigameMpinfoById($item['pay_mpid']);
          $list[$key]['mp_name'] = $mpInfo['mp_name'];
        }
        if ($item['level']) {
          $list[$key]['domain'] = $this->getWeigameService()->getRandDomain($item['level']);
        }
      }
      unset($knowList);
    }
    //分页处理
    $listPageCount = self::DEFAULT_PAGER_PAGESIZE * $pageSize;
    $listCount = count($list);
    if ($listCount < $pageSize) {
      $listPageCount = ($page - 1) * $pageSize + $listCount;
    }
    $show = array();
    $show['curPage'] = $page;
    $show['knowList'] = $list;
    $show['pager'] = $this->getPager($listPageCount, $page, $pageSize);
    $this->title = '你懂我吗系列游戏列表';
    $this->render('list', $show);
  }
  
  /**
   * @desc 添加/编辑游戏
   */
  private function doAddEdit() {
    $action = $this->getSafeRequest('action');

    $code = $this->getSafeRequest('code', 0, 'GET', 'int');
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');    
    $knowgameid = $this->getSafeRequest('knowgame_id', 0, 'GET', 'int');
    $level = $this->getSafeRequest('level', 0, 'POST', 'int');
    $title = $this->getSafeRequest('title', '', 'POST', 'string'); 
    $background_img = $this->getSafeRequest('background_img', '', 'POST', 'string');
    $center_img = $this->getSafeRequest('center_img', '', 'POST', 'string');
    $share_logo = $this->getSafeRequest('share_logo', '', 'POST', 'string');
    $ct_button = $this->getSafeRequest('ct_button', '', 'POST', 'string');
    $dt_button = $this->getSafeRequest('dt_button', '', 'POST', 'string');
    $share_center = $this->getSafeRequest('share_center', '', 'POST', 'string');
    $share_button = $this->getSafeRequest('share_button', '', 'POST', 'string');
    $jssdk_mpids = $this->getSafeRequest('jssdk_mpids', array(), 'POST', 'array');
    $pay_mpid = $this->getSafeRequest('pay_mpid', 0, 'POST', 'int');
    $is_qq = $this->getSafeRequest('is_qq', 0, 'POST', 'int');
    $color = $this->getSafeRequest('color', '', 'POST', 'string');
    $question = $this->getSafeRequest('question', '', 'POST', 'string');
    $answer = $this->getSafeRequest('answer', '', 'POST', 'string');
    $share_title = $this->getSafeRequest('share_title', '', 'POST', 'string');

    //获取游戏信息
    $knowgame = $this->getWeigameService()->getKnowGameById($knowgameid);
    //添加or编辑
    if ($action == 'submit') {
      $questionArr = explode("\r\n", $question);
      $answerArr = explode("\r\n", $answer);
      $shareTitleArr = explode("\r\n", $share_title);
      // //转化成相应的json格式
      $jsonQuestion = $this->qJsonData($questionArr);
      $jsonAnswer = json_encode($answerArr);
      $jsonShareTitle = json_encode($shareTitleArr);
      $jsonjssdk_mpids = $jssdk_mpids ? json_encode($jssdk_mpids) : '';
      if ($knowgame['id']) {
        //编辑游戏
        $updateFields = array();
        if ($level != $knowgame['level']) $updateFields['level'] = $level;
        if ($title != $knowgame['title']) $updateFields['title'] = $title;
        if ($background_img != $knowgame['background_img']) $updateFields['background_img'] = $background_img;
        if ($center_img != $knowgame['center_img']) $updateFields['center_img'] = $center_img;
        if ($share_logo != $knowgame['share_logo']) $updateFields['share_logo'] = $share_logo;
        if ($ct_button != $knowgame['ct_button']) $updateFields['ct_button'] = $ct_button;
        if ($dt_button != $knowgame['dt_button']) $updateFields['dt_button'] = $dt_button;
        if ($share_center != $knowgame['share_center']) $updateFields['share_center'] = $share_center;
        if ($share_button != $knowgame['share_button']) $updateFields['share_button'] = $share_button;
        if ($jsonjssdk_mpids != $knowgame['jssdk_mpids']) $updateFields['jssdk_mpids'] = $jsonjssdk_mpids;
        if ($pay_mpid != $knowgame['pay_mpid']) $updateFields['pay_mpid'] = $pay_mpid;
        if ($is_qq != $knowgame['is_qq']) $updateFields['is_qq'] = $is_qq;
        if ($color != $knowgame['color']) $updateFields['color'] = $color;
        if ($jsonQuestion != $knowgame['question']) $updateFields['question'] = $jsonQuestion;
        if ($jsonAnswer != $knowgame['answer']) $updateFields['answer'] = $jsonAnswer;
        if ($jsonShareTitle != $knowgame['share_title']) $updateFields['share_title'] = $jsonShareTitle;
        
        $this->getWeigameService()->updateKnowGame($knowgame['id'], $updateFields);
        $this->redirect($this->getDeUrl('know/addedit', array('id' => $this->permissionId, 'knowgame_id' => $knowgame['id'], 'code' => 1)));     
      } else {    
        //添加游戏 
        if ($title && $background_img && $center_img && $question && $answer) {
          //上传banner图片
          $knowgame = $this->getWeigameService()->addknowgame(array(
            'level' => $level,
            'title'  => $title, 
            'background_img' =>  $background_img,
            'center_img' => $center_img,
            'share_logo' => $share_logo,
            'ct_button' => $ct_button,
            'dt_button' => $dt_button,
            'share_center' => $share_center,
            'share_button' => $share_button,
            'share_title' => $share_title,
            'jssdk_mpids' => $jsonjssdk_mpids,
            'pay_mpid' => $pay_mpid,
            'is_qq' => $is_qq,
            'color' => $color,
            'question' => $jsonQuestion,
            'answer' => $jsonAnswer,
            'share_title' => $jsonShareTitle,
          ));
          if ($knowgame['id']) {
            $this->redirect($this->getDeUrl('know/addedit', array('id' => $this->permissionId, 'knowgame_id' => $knowgame['id'], 'code' => 1)));
          }
        }
      }
    }

    $show = array();
    $show['code'] = $code;
    $show['curPage'] = $page;
    //获取域名分组
    $groups = $this->getWeigameService()->getDomainGroups();
    $show['groups'] = $groups;
    //获取公众号信息
    $mpinfo = $this->getWeigameService()->getWeigameMpinfos();
    $jssdk_mpids = array();
    $pay_mpid = array();
    foreach ($mpinfo as $key => $value) {
      if ($value['type']) {
        $pay_mpid[] = $value;
      } else {
        $jssdk_mpids[] = $value;
      }
    }
    $show['pay_mpid'] = $pay_mpid;
    $show['jssdk_mpids'] = $jssdk_mpids;
    if ($knowgame) {
      $show['question']=$this->qJsonToString($knowgame['question']);
      $show['answer']=$this->aJsonToString($knowgame['answer']);
      $show['share_title'] = $this->aJsonToString($knowgame['share_title']);
      $show['knowgame'] = $knowgame;
      $show['jscheckwechat'] = json_decode($knowgame['jssdk_mpids'],TRUE);
    }
    $this->title = $knowgame['id'] ? '编辑游戏' : '添加游戏';
    $this->render('addedit', $show);
  }

  /**
   * @desc 题目格式拼装成json 
   * array $arr
   */
  public function qJsonData ($arr) {
    $list = array();
    $i = $j = 0;
    if (is_array($arr)) {
      foreach ($arr as $key => $item) {
      $list[$i][$j] = $item;
      if ($item == '') {
        unset($list[$i][$j]);
        $i++;
        $j = 0;
      }
      $j++;
    }
    $data = array();
    foreach ($list as $key => $item) {
      $data[$key]['id'] = $key+1;
      $data[$key]['img'] = reset($item);
      $data[$key]['title'] = next($item);
      $data[$key]['answers'] = array_slice($item,2);
    }
    return json_encode($data);
    }
  }

  /**
   * @desc 题目格式拼装成json 
   * array $arr
   */
  public function aJsonData ($arr) {
    $data = array();
    $i = $j = 0;
    if (is_array($arr)) {
      foreach ($arr as $key => $item) {
        $list[$i][$j] = $item;
        if ($item == '') {
          unset($list[$i][$j]);
          $i++;
          $j = 0;
        }
        $j++;
      }
      return json_encode($data);#
    }
  }

  /**
   * @desc 问题json格式转化成字符串 
   */
  public function qJsonToString ($json) {
    if ($json) {
      $question = json_decode($json,true);
      $list = array();
      $i = 0;
      if (is_array($question)) {
        foreach ($question as $key => $item) {
          $list[$i] = $item['img'];
          $i++;
          $list[$i]= $item['title'];
          $i++;
          foreach ($item['answers'] as $k => $v) {
            $list[$i] = $v;
            $i++;
          }
          $list[$i] = '';
          $i++;
        }
      }
      return implode("\r\n", $list);
    }
  }

  /**
   * @desc 答案和分享标题json格式转化成字符串 
   */
  public function aJsonToString ($json) {
    if($json){
      $list = json_decode($json,true);
      if (is_array($list)) {
        return implode("\r\n", $list); 
      }
    }
  }

  /**
   * @desc 出题答题游戏管理
   */
  private static $wxqaStatusDescs = array(0 => '正常', 1 => '已删除', 2 => '已隐藏');
  private function doQuestionAnswer () {
    $ref = $this->getSafeRequest('ref');
    $action = $this->getSafeRequest('action');
    $keyword = $this->getSafeRequest('keyword');
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');
    if (!$page) $page = 1;
    $pageSize = 20;
    
    $list = array();
    if ($action == 'search') {
      $matches = array();
      preg_match('/[a-z]{1}(\d+)[a-z]{1}(\d+)[a-z]{1}(\d{4})(\d+)/i', $keyword, $matches);

      //根据微信商户单号查询
      if ($matches) {
        $qid = $matches[1];
        $aid = $matches[2];
        
      //根据题目id查询
      } else {
        $qid = is_numeric($keyword) ? $keyword : 0;
        $aid = 0;
      }
      
      //获取题目信息
      $questionInfo = $this->getWeigameService()->getKnowQuestionById($qid);
      if ($questionInfo) {
        $userWxpayInfo = array();
        $weixinUserInfo = $this->getUserService()->getUserWeixinInfo($questionInfo['uid'], TRUE);

        //获取当前游戏信息
        $knowGame = $this->getWeigameService()->getKnowGameById($questionInfo['from']);
        $questionInfo['name'] = $knowGame['title'];
        
        //获取当前游戏支付公众号
        $knowMpinfo = $this->getWeigameService()->getWeigameMpinfoById($knowGame['pay_mpid']);
        if ($knowMpinfo) $userWxpayInfo = $this->getUserService()->getUserWeixinOpenidByUidAndAppid($questionInfo['uid'], $knowMpinfo['app_id']);

        $questionInfo['user_info'] = $weixinUserInfo;
        $questionInfo['cdate'] = date(DATE_FORMAT, $questionInfo['created_time']);
        $questionInfo['link'] = "/topic/wx{$questionInfo['from']}/tm{$questionInfo['id']}.html";
        $questionInfo['wxpay_info'] = $userWxpayInfo; 
      }
      
      //获取答案列表
      if ($aid) {
        $qa = $this->getWeigameService()->getKnowQuestionAnswerByAid($qid, $aid);
        $qas = array($qa);
      } else {
        $qas = $this->getWeigameService()->getKnowQuestionAnswers($qid, $page, $pageSize);
      }
      if ($qas) {
        foreach ($qas as $qa) {
          if (!$qa['uid']) continue;
          
          $weixinUserInfo = $this->getUserService()->getUserWeixinInfo($qa['uid'], TRUE);
          $qa['user_info'] = $weixinUserInfo;
          //$qa['wxpay_info'] = $this->getUserService()->getUserWxpayIndex($questionInfo['uid'], self::$appid);
          $qa['status_desc'] = self::$wxqaStatusDescs[$qa['status']];
          $list[] = $qa;
          unset($qa);
          unset($weixinUserInfo);
        }
        unset($qas);
      }
    }
    
    //分页处理
    $qaListPageCount = self::DEFAULT_PAGER_PAGESIZE * $pageSize;
    $qaListCount = count($list);
    if ($qaListCount < $pageSize) {
      $qaListPageCount = ($page - 1) * $pageSize + $qaListCount;
    }
    
    $show = array();
    $show['curPage'] = $page;
    $show['keyword'] = $keyword;
    $show['questionInfo'] = $questionInfo;
    $show['qaList'] = $list;
    $show['pager'] = $this->getPager($qaListPageCount, $page, $pageSize);
    $this->title = '题目查询';
    $this->render('qa', $show);
  }
  
  /**
   * @desc 更新答案状态 (ajax异步)
   */
  private function doQuestionAnswerUp () {
    $qid = $this->getSafeRequest('qid', 0, 'POST', 'int');
    $aid = $this->getSafeRequest('aid', 0, 'POST', 'int');
  
    $ret = array();
    $ret['code'] = 0;
    $ret['data'] = array();
  
    if ($qid && $aid) {
      //获取题目信息
      $questionInfo = $this->getWeigameService()->getKnowQuestionById($qid);
      //获取答案信息
      $qaInfo = $this->getWeigameService()->getKnowQuestionAnswerByAid($qid, $aid);
      
      if ($questionInfo && $qaInfo) {
        $fields = array(
          'id' => $qaInfo['id'],
          'qid' => $qaInfo['qid'],
          'uid' => $qaInfo['uid'],
          'status' => $qaInfo['status'] > 0 ? 0 : 1,
        );
        if ($qaInfo['is_pay'] && $qaInfo['status'] == 0) $fields['is_pay'] = 0;
        if ($this->getWeigameService()->updateKnowQuestionAnswer($fields)) {
          $ret['code'] = 1;
          $ret['data']['statusDesc'] = $qaInfo['status'] > 0 ? '已恢复' : '已删除';
        }
      }
    }
  
    $this->outputJsonData($ret);
  }
}
?>
