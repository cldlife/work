<?php
/**
 * @desc 微信出题答题游戏
 * @author dong
 */
class KnowController extends BaseController {

  //背景图
  public $background_image = '';

  //微信单个红包金额（单位分int）
  private static $amount = 150;

  //微信红包简介
  private static $redpackRemark ='请在3日内领取，逾期未领取将会退回且无法再领取！（微信支付通道费已扣除）';
  
  //微信支付配置（fee金额，单位分int）
  private static $wxpayExtraConfig = array(
    'va' => array('nofity_uri' => '/know/wxpayviewanswer.html', 'out_trace_no_template' => 'V{QID}A{AID}E', 'desc' => '查看答案', 'fee' => 200),
    'da' => array('nofity_uri' => '/know/wxpaydelanswer.html', 'out_trace_no_template' => 'D{QID}A{AID}E', 'desc' => '删除答案', 'fee' => 5000),
  );
  
  //微信jssdk安全域名(分享页)
  private function randWxJssdkDomain($jssdk_mpids) {
    $mpJsInfo = json_decode($jssdk_mpids, TRUE);
    $randNum = mt_rand(0, count($mpJsInfo)-1);
    $mpid = $mpJsInfo[$randNum];
    $jssdkDomain = $this->getWeigameService()->getWeigameMpDomainsByMpid($mpid, self::JSSDK_MP_TYPE);
    if ($jssdkDomain) {
      $randId = mt_rand(0, count($jssdkDomain) - 1);
      $randDomain = $jssdkDomain[$randId];
    }
    return $randDomain['domain_address'];
  }
  
  /**
   * @desc 新浪云分享跳转域名
   * @param $key int 域名数据集合下标key, 为NUll则随机（默认）
   */
  private function randSinaSaeDomain($key = NULL) {
    $randDomain = '';
    $sinaSaeDomainLevel = self::TAOZI_LEVEL;
    $domainList = $this->getWeigameService()->getOnlineDomains($sinaSaeDomainLevel);
    if ($domainList) {
      $ctRandDomains = $domainList[$sinaSaeDomainLevel];
      $randId = $key === NULL ? mt_rand(0, count($ctRandDomains) - 1) : $key;
      $randDomain = $ctRandDomains[$randId];
    }
    return $randDomain;
  }
  
  /**
   * @desc 随机controller name
   */
  private function randControllerName () {
    return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 8);
  }

  /**
   * @desc 随机url link
   */
  private function randUrlLink () {
    return "know{$this->fromkey}-gt{$this->type}-gp{$this->level}";
  }


  //获取随机title
  public function getKnowShareTitle($nickname, $randTitles) {
    $randKey = mt_rand(0, (count($randTitles) - 1));
    $randTitle = str_replace('**', $nickname, $randTitles[$randKey]);
    return $randTitle ? $randTitle : '';
  }

  //转换成json data
  private function answerJsonData($qa_content, $knowQuestion){
    if ($qa_content && $knowQuestion) {
      $qaContentExp = explode('+', $qa_content);
      $qa_content = $qaContentExp[0];
      $qa = explode('|', $qa_content);
      $knowQuestion = json_decode($knowQuestion,TRUE);
      $list = array();
      foreach ($qa as $key => $item) {
        list($question,$right) = explode('_', $item);
        $list[$key] = $knowQuestion[$question-1];
        $list[$key]['right'] = $right-1;
        unset($item);
        unset($qa);
      }
      return json_encode($list);
    }
  }

  //计算匹配度百分比
  private function calcMatchingPercent ($questionQaContent, $answerQaContent) {
    $questionQaContentExp = $this->uniqueQaContent($questionQaContent);
    $answerQaContentExp = $this->uniqueQaContent($answerQaContent);

    //通过计算差集，找到不在数组$questionQaContentExp中的值（找出答对的题目）
    $qaDiff = array_diff($questionQaContentExp, $answerQaContentExp);

    $questionCount = count($questionQaContentExp);
    $trueAnswerCount = $questionCount - count($qaDiff);

    return round(($trueAnswerCount / $questionCount) * 100);
  }

  //获取匹配度描述文案
  private function getQuestionAnswerDesc ($matchingPercent, $answerDescs) {
    if ($matchingPercent <= 0) {
      $answerDesc = $answerDescs[0];
    } elseif ($matchingPercent <= 10) {
      $answerDesc = $answerDescs[1];
    } elseif ($matchingPercent < 30) {
      $answerDesc = $answerDescs[2];
    } elseif ($matchingPercent < 40) {
      $answerDesc = $answerDescs[3];
    } elseif ($matchingPercent < 50) {
      $answerDesc = $answerDescs[4];
    } elseif ($matchingPercent < 60) {
      $answerDesc = $answerDescs[5];
    } elseif ($matchingPercent < 70) {
      $answerDesc = $answerDescs[6];
    } elseif ($matchingPercent < 80) {
      $answerDesc = $answerDescs[7];
    } elseif ($matchingPercent < 90) {
      $answerDesc = $answerDescs[8];
    } elseif ($matchingPercent < 100) {
      $answerDesc = $answerDescs[9];
    } else {
      $answerDesc = $answerDescs[10];
    }
    return $answerDesc;
  }
  
  //获取答案列表
  //答案状态：0-正常，1-删除，2-仅出题用户和自己可见
  private function getQuestionAnswerList ($question, $answerDescs) {
    $answerList = array();
    if ($question['id'] && $question['uid'] && $question['qa_content']) {
      $answers = $this->getWeigameService()->getKnowQuestionAnswers($question['id']);
      if ($answers) {
        foreach ($answers as $answer) {
          if (!$answer['uid'] || !$answer['qa_content'] || $answer['status'] == 1) continue;
          if ($answer['status'] == 2 && $this->currentUser['uid'] != $question['uid'] && $this->currentUser['uid'] != $answer['uid']) continue;
          
          $tmpList = array();
          $tmpList['id'] = $answer['id'];
          $tmpList['qid'] = $answer['qid'];
          $tmpList['status'] = $answer['status'];
          $tmpList['user_info'] = $this->getUserService()->getUserWeixinInfo($answer['uid']);
          $tmpList['matching_percent'] = $this->calcMatchingPercent($question['qa_content'], $answer['qa_content']);
          $tmpList['answer_desc'] = $this->getQuestionAnswerDesc($tmpList['matching_percent'], $answerDescs);
          $answerList[] = $tmpList;
          unset($tmpList);
          unset($answer);
        }
        unset($answers);
      }
    }
    return $answerList;
  }
  
  /**
   * @desc 获取题目信息（并解析出题来源, qa_fromqq: 1-QQ）
   */
  private function getQuestionInfo ($qid) {
    $question = array();
    if ($qid) {
      $question = $this->getWeigameService()->getKnowQuestionById($qid);
      $qaContentExp = explode('+', $question['qa_content']);
      $question['qa_content'] = $qaContentExp[0];
      $question['qa_fromqq'] = intval($qaContentExp[1]);
    }
    return $question;
  }

  /** 
   * @desc 出题（微信H5游戏）
   * @param string ct-出题, dt-答题
   * @param int step
   */
  public function actionQaQuestion () {  
    $this->fromkey = $this->getSafeRequest('fromkey', '', 'GET', 'int');
    $knowGame = $this->getWeigameService()->getKnowGameById($this->fromkey);
    
    if (!$knowGame) $this->redirect($this->getDeUrl());
    $step = $this->getSafeRequest('step', 0, 'GET', 'int');
    $this->baiduTongjiCode = $this->getBaiduTongjiCode($knowGame['level']);
    $this->layout = "main_wx_know";
    $this->title = $knowGame['title'];
    $this->background_image = $knowGame['background_img'];
    $randControllerName = $this->randControllerName();
    $randUrlLink = $this->randUrlLink();
    //登录验证
    if (!$this->currentUser && $knowGame['pay_mpid']) $this->checkUserLogin(urlencode($this->getDeUrl("{$randControllerName}/{$randUrlLink}", array('step' => $step))), $knowGame['pay_mpid']); 

    $qaContent = $this->getSafeRequest('qa_content', '', 'POST');
    if ($step == 2 && $qaContent) {
      
      //Flood Start 缓存 (间隔10秒)
      $cacheKey = __FUNCTION__ . $this->currentUser['uid'];
      $waiting = $this->getCommonService()->getFromMemcache($cacheKey);
      if ($waiting) $this->redirect($this->getDeUrl("{$randControllerName}/{$randUrlLink}"));
      
      //上传题目
      $qaContentArray = $this->uniqueQaContent($qaContent);
      $qaContent = implode('|', $qaContentArray);
      if ($qaContent) {
        //添加出题来源qa_fromqq（以+号分隔附加到qa_content上）
        if (Utils::isFromQQ()) $qaContent .= '+1';
        
        //从20160106开始区分from值
        //$from = $this->fromkey >= 20160106 ? $this->fromkey : '';
        $qid = $this->getWeigameService()->addKnowQuestion(array(
          'uid' => $this->currentUser['uid'],
          'qa_content' => $qaContent,
          'from' => $this->fromkey
        ));
        if ($qid) {
          //Flood End 缓存
          $this->getCommonService()->setToMemcache($cacheKey, TRUE, self::FLOOD_LIMIT_TIME/2);
    
          //设置出题cookie为第3步
          $cacheKey = "_know{$this->fromkey}_ct_tm_{$qid}";
          $this->setCookie($cacheKey, $qid);
          
          $isRedirectJssdkDomain = FALSE;
          if ($knowGame['jssdk_mpids']) {
            //$enableKnowJssdkFromkeys类游戏，域名不一致Cookie无法同步，需要用memcache
            $this->getCommonService()->setToMemcache($cacheKey, $qid);
            if ($this->randWxJssdkDomain($knowGame['jssdk_mpids']) && $this->randSinaSaeDomain()) $isRedirectJssdkDomain = TRUE;
          }
          
          //验证是否跳转到jssdk domain
          if ($isRedirectJssdkDomain) {
            $this->redirect("http://{$this->randWxJssdkDomain($knowGame['jssdk_mpids'])}/{$randControllerName}/{$randUrlLink}/tm{$qid}.html?{$this->getLoginTypeSuuidParams()}");
          } else {
            $this->redirect($this->getDeUrl("{$randControllerName}/{$randUrlLink}/tm{$qid}"));
          }
        }
      }
      
    } else {
      $data = array();
      $data['qaInfo'] = $this->getQaInfo();
      $data['share_logo'] = $knowGame['share_logo'];
      $data['center_img'] = $knowGame['center_img'];
      $data['ct_button'] = $knowGame['ct_button'];
      $data['color'] = $knowGame['color'];
      $data['randControllerName'] = $randControllerName;
      $data['randUrlLink'] = $randUrlLink;
      if (!$data['qaInfo']['qas']) $data['qaInfo']['qas'] = $knowGame['question'];
      if ($step == 1) {
        $this->render("ct_step2", $data);
      } else {
        $data['randDomain'] = $this->getWeigameService()->getRandDomain($this->level);
        $this->render("ct_step1", $data);
      }
    }
  }
  
  /**
   * @desc 答题（微信H5游戏）
   * @param string ct-出题, dt-答题
   * @param int step
   */
  public function actionQaAnswer () {
    $this->fromkey = $this->getSafeRequest('fromkey', '', 'GET', 'int');
    $testShareDomainKey = $this->getSafeRequest('tsdk', NULL, 'GET', 'int');
    
    $knowGame = $this->getWeigameService()->getKnowGameById($this->fromkey);
    if (!$knowGame) $this->redirect($this->getDeUrl());
    
    $qid = $this->getSafeRequest('qid', 0, 'GET', 'int');
    $step = $this->getSafeRequest('step', 0, 'GET', 'int');
    $this->baiduTongjiCode = $this->getBaiduTongjiCode($knowGame['level']); 
    $this->layout = "main_wx_know";
    $this->title = $knowGame['title'];
    $this->background_image = $knowGame['background_img'];
    $randControllerName = $this->randControllerName();
    $randUrlLink = $this->randUrlLink();
    //获取题目&出题用户信息
    $question = array();
    if ($qid) $question = $this->getQuestionInfo($qid);
    if (!$question) $this->redirect($this->getDeUrl("{$randControllerName}/{$randUrlLink}"));
    if (!APP_DEBUG && $question['from'] && $this->fromkey != $question['from']) $this->redirect($this->getDeUrl("{$randControllerName}/know{$question['from']}_gt{$this->type}_gp{$this->level}/tm{$question['id']}"));
    $question['user_info'] = $this->getUserService()->getUserWeixinInfo($question['uid']);
    $shareTitle = $this->getKnowShareTitle($question['user_info']['nickname'], json_decode($knowGame['share_title'],TRUE));
    $this->title = $shareTitle ? $shareTitle : $knowGame['title'];
    
    $data = array();
    $data['qaInfo'] = $this->getQaInfo();
    $data['share_logo'] = $knowGame['share_logo'];
    $data['knowGame'] = $knowGame;
    $data['question'] = $question;
    $data['isFromWeixin'] = Utils::isFromWeixin();
    $data['randControllerName'] = $randControllerName;
    $data['randUrlLink'] = $randUrlLink;
    $jsonQuqstion = $this->answerJsonData($question['qa_content'], $knowGame['question']);
    if (!$data['qaInfo']['qas']) $data['qaInfo']['qas'] = $jsonQuqstion;
    
    //出题完成后的分享引导页面
    $cacheKey = "_know{$this->fromkey}_ct_tm_{$question['id']}";
    
    //$enableKnowJssdkFromkeys类游戏，域名不一致Cookie无法同步，需要用memcache
    if ($knowGame['jssdk_mpids']) {
      $ctStepCookieQuestionId = $this->getCommonService()->getFromMemcache($cacheKey);
      $this->getCommonService()->deleteFromMemcache($cacheKey);
    } else {
      $ctStepCookie = $this->getCookie($cacheKey);
      $ctStepCookieQuestionId = $ctStepCookie->value;
      $this->deleteCookie($cacheKey);
    }

    if ($ctStepCookieQuestionId == $question['id']) {
      if ($knowGame['jssdk_mpids']) {
        //根据域名地址获取公众号信息 & weixin Jssdk Config
        $weixinJssdkConfig = array();
        $mpInfo = $this->getWeigameService()->getWeigameMpInfoByDomainAddress($this->curDomain);
        if ($mpInfo['app_id'] && $mpInfo['app_secret']) $weixinJssdkConfig = $this->getCommonService()->getJssdkConfigByAppid($mpInfo['app_id'], $mpInfo['app_secret']);
        
        $data['weixinJssdkConfig'] = $weixinJssdkConfig;
        $data['shareLink'] = $this->getWeigameService()->getRandDomain($this->level) . Yii::app()->request->getUrl();
        $data['sinaShareDomain'] = $this->randSinaSaeDomain($testShareDomainKey);
      }

      $this->render("ct_step3", $data);
      
    //题目结果页 
    } else {

      //判断是否是用户自己的题目
      if ($question['uid'] == $this->currentUser['uid']) {
        $data['randDomain'] = $this->getWeigameService()->getRandDomain($this->level);
        $data['answerList'] = $this->getQuestionAnswerList($question, json_decode($knowGame['answer'],TRUE));
        
        if ($knowGame['jssdk_mpids']) {
          //根据域名地址获取公众号信息 & weixin Jssdk Config
          $weixinJssdkConfig = array();
          $mpInfo = $this->getWeigameService()->getWeigameMpInfoByDomainAddress($this->curDomain);
          if ($mpInfo['app_id'] && $mpInfo['app_secret']) $weixinJssdkConfig = $this->getCommonService()->getJssdkConfigByAppid($mpInfo['app_id'], $mpInfo['app_secret']);
        
          $data['weixinJssdkConfig'] = $weixinJssdkConfig;
          $data['shareLink'] = $data['randDomain'] . Yii::app()->request->getUrl();
          $data['sinaShareDomain'] = $this->randSinaSaeDomain($testShareDomainKey);
        }
        
        $this->render("ct_result", $data);
        
      } else {

        //判断用户是否答过题目（仅答题后查看结果）
        $userAnswer = $this->getWeigameService()->getKnowQuestionAnswerByUid($question['id'], $this->currentUser['uid']);
        if ($userAnswer && $userAnswer['status'] != 1) {
          $this->title = "卧槽！{$this->currentUser['nickname']} 对 {$question['user_info']['nickname']} 的了解程度居然是…";
          
          $questionUser = $this->getUserService()->getUserWeixinInfo($question['uid']);
          $matchingPercent = $this->calcMatchingPercent($question['qa_content'], $userAnswer['qa_content']);
          $data['nickname'] = $questionUser['nickname'];
          $data['answerDesc'] = $this->getQuestionAnswerDesc($matchingPercent, json_decode($knowGame['answer'],TRUE));
          $data['matchingPercent'] = $matchingPercent;
          $data['answerList'] = $this->getQuestionAnswerList($question, json_decode($knowGame['answer'],TRUE));
          $data['isPay'] = $userAnswer['is_pay'];
          $data['status'] = $userAnswer['status'];
          $data['randDomain'] = $this->getWeigameService()->getRandDomain($this->level);
          if ($knowGame['jssdk_mpids']) {
            //根据域名地址获取公众号信息 & weixin Jssdk Config
            $weixinJssdkConfig = array();
            $mpInfo = $this->getWeigameService()->getWeigameMpInfoByDomainAddress($this->curDomain);
            if ($mpInfo['app_id'] && $mpInfo['app_secret']) $weixinJssdkConfig = $this->getCommonService()->getJssdkConfigByAppid($mpInfo['app_id'], $mpInfo['app_secret']);
            
            $data['weixinJssdkConfig'] = $weixinJssdkConfig;
            $data['shareLink'] = $this->getWeigameService()->getRandDomain($this->level) . Yii::app()->request->getUrl();
            $data['sinaShareDomain'] = $this->randSinaSaeDomain($testShareDomainKey);
          }
          if ($knowGame['pay_mpid']) {
            $randId = 0;
            $wxPayDomain = $this->getWeigameService()->getWeigameMpDomainsByMpid($knowGame['pay_mpid'], self::PAY_MP_TYPE);
            if ($wxPayDomain) $randId = mt_rand(0, count($wxPayDomain) - 1);
            $randDomain = $wxPayDomain[$randId];
            $data['wxpayDomain'] = $randDomain['domain_address'];
          }
          $this->render("dt_result", $data);
          
        //开始答题
        } else {
          
          if ($step) {
            //登录验证
            if (!$this->currentUser && $knowGame['pay_mpid']) $this->checkUserLogin(urlencode($this->getDeUrl("{$randControllerName}/{$randUrlLink}/tm{$question['id']}", array('step' => $step))), $knowGame['pay_mpid']);
            
            $qaContent = $this->getSafeRequest('qa_content', '', 'POST');
            if ($step == 2 && $qaContent) {
              $qaContentArray = $this->uniqueQaContent($qaContent);
              $qaContent = implode('|', $qaContentArray);
              if ($qaContent) {
                //更新答案（重新答题）
                if ($userAnswer) {
                  if ($this->getWeigameService()->updateKnowQuestionAnswer(array(
                    'id' => $userAnswer['id'],
                    'qid' => $qid,
                    'uid' => $this->currentUser['uid'],
                    'qa_content' => $qaContent,
                    'is_pay' => 0,
                    'reanswer_num' => $userAnswer['reanswer_num'] + 1,
                    'status' => 0,
                  ))) $this->redirect($this->getDeUrl("{$randControllerName}/{$randUrlLink}/tm{$question['id']}"));
                  
                //上传答案
                } else {
                  if ($this->getWeigameService()->addKnowQuestionAnswer(array(
                    'qid' => $qid,
                    'uid' => $this->currentUser['uid'],
                    'qa_content' => $qaContent,
                  ))) $this->redirect($this->getDeUrl("{$randControllerName}/{$randUrlLink}/tm{$question['id']}"));
                }
              }
              
            } else {
              $this->render("dt_step2", $data);
            }
          } else {
            $data['nickname'] = $question['user_info']['nickname'];
            $data['avatar'] = $question['user_info']['avatar'];
            $data['randDomain'] = $this->getWeigameService()->getRandDomain($this->level);
            $this->render("dt_step1", $data);
          }
        }
      }
    }
  }

  /**
   * @desc 正确答案列表（微信H5游戏）
   */
  public function actionQaTrueAnswer () {
    $this->fromkey = $this->getSafeRequest('fromkey', '', 'GET', 'int');
    
    $knowGame = $this->getWeigameService()->getKnowGameById($this->fromkey);
    if (!$knowGame) $this->redirect($this->getDeUrl());
    
    $qid = $this->getSafeRequest('qid', 0, 'GET', 'int');
    $uid = $this->getSafeRequest('uid', 0, 'GET', 'int');
    $this->baiduTongjiCode = $this->getBaiduTongjiCode(self::PAY_LEVEL);
    $this->layout = "main_wx_know";
    $this->title = $knowGame['title'];
    $this->background_image = $knowGame['background_img'];
    $randControllerName = $this->randControllerName();
    $randUrlLink = $this->randUrlLink();
    //获取题目&出题用户的信息
    $question = array();
    if ($qid) $question = $this->getQuestionInfo($qid);
    if (!$question) $this->redirect($this->getDeUrl("{$randControllerName}/{$randUrlLink}"));
    if (!APP_DEBUG && $question['from'] && $this->fromkey != $question['from']) $this->redirect($this->getDeUrl("{$randControllerName}/know{$question['from']}_gt{$this->type}_gp{$this->level}/tm{$question['id']}/qa"));
    $question['user_info'] = $this->getUserService()->getUserWeixinInfo($question['uid'], TRUE);
    $question['qa_content'] = $this->uniqueQaContent($question['qa_content']);
    
    $data = array();
    $data['qaInfo'] = $this->getQaInfo();
    $data['share_logo'] = $knowGame['share_logo'];
    $data['randControllerName'] = $randControllerName;
    $data['randUrlLink'] = $randUrlLink;
    if (!$data['qaInfo']['qas']) $data['qaInfo']['qas'] = json_decode($knowGame['question'],TRUE);
    $data['randDomain'] = $this->getWeigameService()->getRandDomain($this->level);
    $data['question'] = $question;
    $shareTitle = $this->getKnowShareTitle($question['user_info']['nickname'], json_decode($knowGame['share_title'],TRUE));
    $this->title = $shareTitle ? $shareTitle : $knowGame['title'];
    //出题用户自己查看答案
    if ($question['uid'] == $this->currentUser['uid']) {
      //获取TA的答案,对比显示
      $userAnswer = array();
      if ($uid) $userAnswer = $this->getWeigameService()->getKnowQuestionAnswerByUid($question['id'], $uid);
      if ($userAnswer && $userAnswer['status'] != 1) $userAnswer['qas'] = explode('|', $userAnswer['qa_content']);
      
      $data['isPay'] = 1;
      $data['userAnswer'] = $userAnswer;
      $data['ct_button'] = $knowGame['ct_button'];
      $this->render("qa_answers", $data);
      
    //其它用户查看答案
    } else {
      //登录验证
      if (!$this->currentUser && $knowGame['pay_mpid']) $this->checkUserLogin(urlencode($this->getDeUrl('wxpay/knowqa', array('fromkey' => $this->fromkey ,'qid' => $question['id'], 'type' => $this->type, 'level' => $this->level))), $knowGame['pay_mpid']);
      
      //判断用户是否答过题目（仅答题后查看结果）
      $userAnswer = $this->getWeigameService()->getKnowQuestionAnswerByUid($question['id'], $this->currentUser['uid']);
      if ($userAnswer && $userAnswer['status'] != 1) {
        //判断用户是否付过款
        $mpInfo = $this->getWeigameService()->getWeigameMpinfoById($knowGame['pay_mpid']);
        if ($userAnswer['is_pay'] == 0 && $mpInfo) {
          $weixinOpenid = $this->getUserService()->getUserWeixinOpenidByUidAndAppid($this->currentUser['uid'], $mpInfo['app_id']);
            //获取微信jssdk config
          $weixinJssdkConfig = $this->getCommonService()->getJssdkConfigByAppid($mpInfo['app_id'], $mpInfo['app_secret']);
          $data['qid'] = $question['id'];
          $data['weixinJssdkConfig'] = $weixinJssdkConfig;
          $data['wxpayParams'] = json_encode(array('action' => 'va', 'fromkey' => $this->fromkey, 'qid' => $question['id'], 'aid' => $userAnswer['id'], 'uid' => $this->currentUser['uid'], 'oid' => $weixinOpenid['openid'], 'appid' => $mpInfo['app_id'], '_sh_token_' => Yii::app()->request->getCsrfToken()));
        }
        $data['isPay'] = intval($userAnswer['is_pay']);
        $this->render("qa_answers", $data);
      } else {
        $this->redirect($this->getDeUrl("{$randControllerName}/{$randUrlLink}/tm{$question['id']}"));
      }
    }
  }
  
  /**
   * @desc 删除答案页
   */
  public function actionDelAnswer(){
    $this->fromkey = $this->getSafeRequest('fromkey', 0, 'GET', 'int');
    
    $knowGame = $this->getWeigameService()->getKnowGameById($this->fromkey);
    if (!$knowGame) $this->redirect($this->getDeUrl());
  
    $qid = $this->getSafeRequest('qid', 0, 'GET', 'int');
    
    $randControllerName = $this->randControllerName();
    $randUrlLink = $this->randUrlLink();

    //登录验证
    if (!$this->currentUser && $knowGame['pay_mpid']) $this->checkUserLogin(urlencode($this->getDeUrl('wxpay/knowdelanswer', array('fromkey' => $this->fromkey, 'qid' => $qid, 'type' => $this->type, 'level' => $this->level))), $knowGame['pay_mpid']);
  
    $uid = $this->currentUser['uid'];
    $this->baiduTongjiCode = $this->getBaiduTongjiCode(self::PAY_LEVEL);
    $this->layout = "main_wx_know";
    $this->background_image = $knowGame['background_img'];
    $this->title = $knowGame['title'];
  
    $userAnswer = $this->getWeigameService()->getKnowQuestionAnswerByUid($qid, $uid);
    if ($userAnswer && $userAnswer['status'] != 1 && $this->fromkey && $qid && $uid) {
      //判断用户是否付过款
      $mpInfo = $this->getWeigameService()->getWeigameMpinfoById($knowGame['pay_mpid']);
      $weixinOpenid = $this->getUserService()->getUserWeixinOpenidByUidAndAppid($this->currentUser['uid'],$mpInfo['app_id']);
      $data = array();
      $data['qid'] = $qid;
      $data['weixinJssdkConfig'] = $this->getCommonService()->getJssdkConfigByAppid($mpInfo['app_id'], $mpInfo['app_secret']);
      $data['wxpayParams'] = json_encode(array('action' => 'da', 'fromkey' => $this->fromkey, 'qid' => $qid, 'aid' => $userAnswer['id'], 'uid' => $uid, 'oid' => $weixinOpenid['openid'], 'default' => json_encode($knowGame['title']), 'appid' => $mpInfo['app_id'], '_sh_token_' => Yii::app()->request->getCsrfToken()));
      $data['randDomain'] = $this->getWeigameService()->getRandDomain($this->level);
      $data['randControllerName'] = $randControllerName;
      $data['randUrlLink'] = $randUrlLink;
      $this->render("del_answers", $data);
    } else {
      $this->redirect($this->getDeUrl("{$randControllerName}/{$randUrlLink}/tm{$qid}"));
    }
  }
  
  /**
   * @desc 微信支付统一下单（ajax）
   */
  public function actionWxPay () {
    $fromkey = $this->getSafeRequest('fromkey', 0, 'GET', 'int');
    $action = $this->getSafeRequest('action', '', 'POST');
    $qid = $this->getSafeRequest('qid', 0, 'POST', 'int');
    $aid = $this->getSafeRequest('aid', 0, 'POST', 'int');
    $uid = $this->getSafeRequest('uid', 0, 'POST', 'int');
    $appid = $this->getSafeRequest('appid', '', 'POST');
    $openid = $this->getSafeRequest('oid', '', 'POST');
    $default = $this->getSafeRequest('default', '', 'POST');
  
    try {
      $wxpayExtraConfig = self::$wxpayExtraConfig[$action];
      if ($action && $wxpayExtraConfig && $fromkey && $qid && $aid && $uid && $openid && $appid) {
        //配置wxpayconfig
        $wxPayConfig = Yii::app()->params['wxPayConfig'][$appid];
        $wxPayConfig['WXPAY_NOTIFY_URL'] = Yii::app()->params['wxPayNotifyUrlDomain'] . $wxpayExtraConfig['nofity_uri'];
        $this->getWxpayService()->setWxpayConfig($wxPayConfig);
        
        //调取微信支付（商户订单号不可重复）
        $outTradeNo = str_replace(array('{QID}', '{AID}'), array($qid, $aid), $wxpayExtraConfig['out_trace_no_template']) . mt_rand(1000, 9999) . $fromkey;
        
        $order = array(
          'type' => 'JSAPI',
          'openid' => $openid,
          'fee' => $wxpayExtraConfig['fee'],
          'desc' => $default . $wxpayExtraConfig['desc'],
          'out_trade_no' => $outTradeNo,
          'attach' => json_encode(array(
            'fromkey' => $fromkey,
            'uid' => $uid,
            'appid' => $appid,
            'state' => Utils::generateCSRFSecret($fromkey . $uid . $appid . $outTradeNo)
          ))
        );
        $orderRes = $this->getWxpayService()->sendUnifiedorder($order);
        if ($orderRes['prepay_id']) {
          $package = 'prepay_id=' . $orderRes['prepay_id'];
          $wxpayPre = $this->getWxpayService()->getWxpaySdkSign(array('package' => $package));
          $this->outputJsonData(1, array(
            'wxpayPre' => $wxpayPre,
          ));
        } else {
          $this->outputJsonData(-2);
        }
      }
  
    } catch (Exception $e) {
      $this->outputJsonData(-1);
    }
    $this->outputJsonData(0);
  }
  
  /**
   * @desc 查看答案支付回调（notify_url）
   */
  public function actionWxPayViewAnswer(){
    try {
      $notifyMsg = file_get_contents('php://input');
      $results = $notifyMsg ? $this->getWxpayService()->getNotifyResults($notifyMsg, FALSE) : array();
      //TODO Test
      /**
      $results = array();
      $results['out_trade_no'] = 'V161468948161516A5';
      $results['attach']['fromkey'] = 20160705;
      $results['attach']['uid'] = 1;
      $results['attach']['state'] = Utils::generateCSRFSecret($results['attach']['fromkey'] . $results['attach']['uid'] . $results['out_trade_no']);
       */
      
      if ($results) {
        $outTradeNo = $results['out_trade_no'];
        $state = $results['attach']['state'];
        $fromkey = $results['attach']['fromkey'];
        $uid = $results['attach']['uid'];
        $appid = $results['attach']['appid'];
  
        //通知安全验证
        $checkState = ($results['attach'] && $state && $fromkey && $uid && $appid && $state == Utils::generateCSRFSecret($fromkey . $uid . $appid . $outTradeNo));
        if ($outTradeNo && $checkState) {
          preg_match('/V(\d+)A(\d+)/i', $outTradeNo, $matches);
          $qid = $matches[1];
          $aid = $matches[2];
  
          //获取题目
          $question = $this->getQuestionInfo($qid);
          if ($question) {
            //判断答案是否存在
            $userAnswer = $this->getWeigameService()->getKnowQuestionAnswerByUid($qid, $uid);
            if ($userAnswer && $userAnswer['status'] != 1 && $userAnswer['is_pay'] == 0) {
              if ($this ->getWeigameService()->updateKnowQuestionAnswer(array(
                'id' => $userAnswer['id'],
                'qid' => $qid,
                'uid' => $uid,
                'is_pay' => 1,
              ))) {
  
                try {
                  $sendRedpack = FALSE;
                  $weixinOpenid = $this->getUserService()->getUserWeixinOpenidByUidAndAppid($question['uid'], $appid);
                  if ($weixinOpenid) {
                    $knowGame = $this->getWeigameService()->getKnowGameById($fromkey);
                    $aUserWeixinInfo = $this->getUserService()->getUserWeixinInfo($uid);
                    
                    //发红包
                    //配置wxpayconfig
                    $wxPayConfig = Yii::app()->params['wxPayConfig'][$appid];
                    $wxPayConfig['WXPAY_NOTIFY_URL'] = Yii::app()->params['wxPayNotifyUrlDomain'] . self::$wxpayExtraConfig['va']['nofity_uri'];
                    $this->getWxpayService()->setWxpayConfig($wxPayConfig);
                    $sendRedpack = $this->getWxpayService()->sendRedpack(array(
                      'openid' => $weixinOpenid['openid'],
                      'amount' => self::$amount,
                      'send_name' => $aUserWeixinInfo['nickname'] ? $aUserWeixinInfo['nickname'] : '好友',
                      'act_name' => $knowGame['title'] ? $knowGame['title'] : '',
                      'remark' => self::$redpackRemark,
                    ));
                  }
                  if (!$sendRedpack) {
                    Utils::log(__METHOD__ . ":: failed: fromkey:{$fromkey}, qid:{$qid}, uid:{$question['uid']}, openid:{$weixinOpenid['openid']}", "know_send_redpack_{$wxPayConfig['WXPAY_APP_ID']}");
                  }
                } catch(Exception $e){
                  Utils::log(__METHOD__ . ":: error:" . json_encode($e->getMessage()) . ", fromkey:{$fromkey}, qid:{$qid}, uid:{$question['uid']}, openid:{$qUserKnowpayIndex['openid']}", "know_send_redpack_{$wxPayConfig['WXPAY_APP_ID']}");
                }
  
                echo $this->getWxpayService()->replyNotify(array(
                  'return_code' => 'SUCCESS',
                  'return_msg' => 'OK'
                ));
                exit;
              }
            }
          }
        }
      }
  
    } catch (Exception $e) {}
  
    echo $this->getWxpayService()->replyNotify(array(
      'return_code' => 'FAIL',
      'return_msg' => '失败'
    ));
    exit;
  }
  
  /**
   * @desc 删除答案支付回调（notify_url）
   */
  public function actionWxPayDelAnswer(){
    try {
      $notifyMsg = file_get_contents('php://input');
      $results = $notifyMsg ? $this->getWxpayService()->getNotifyResults($notifyMsg, FALSE) : array();
      
      //TODO Test
      //{"appid":"wx318680eae930969f","attach":{"fromkey":"1","uid":"60","appid":"wx318680eae930969f","state":"dfced35c577b2d9730891e25f81c23fd"},"bank_type":"CFT","cash_fee":"200","fee_type":"CNY","is_subscribe":"Y","mch_id":"1254323501","nonce_str":"fdX7IQuKPvpYPMn5MnDsxKjGvKPVru2d","openid":"ozwqws0a43tMWN7Y8lQvOtPYmids","out_trade_no":"V14816362856531A2E25261","result_code":"SUCCESS","return_code":"SUCCESS","sign":"AB6AC6AA892F8CB056630F41AAA44085","time_end":"20161213214205","total_fee":"200","trade_type":"JSAPI","transaction_id":"4008732001201612132708029917"}
      /**
      $results = array();
      $results['out_trade_no'] = 'D161468948161516A5';
      $results['attach']['fromkey'] = 20160705;
      $results['attach']['uid'] = 1;
      $results['attach']['state'] = Utils::generateCSRFSecret($results['attach']['fromkey'] . $results['attach']['uid'] . $results['out_trade_no']);
       */
  
      if ($results) {
        $outTradeNo = $results['out_trade_no'];
        $state = $results['attach']['state'];
        $fromkey = $results['attach']['fromkey'];
        $uid = $results['attach']['uid'];
        $appid = $results['attach']['appid'];
        
  
        //通知安全验证
        $checkState = ($results['attach'] && $state && $fromkey && $uid && $appid && $state == Utils::generateCSRFSecret($fromkey . $uid . $appid . $outTradeNo));
        if ($outTradeNo && $checkState) {
          preg_match('/D(\d+)A(\d+)/i', $outTradeNo, $matches);
          $qid = $matches[1];
          $aid = $matches[2];
  
          //判断答案是否存在（更新为删除状态）
          $userAnswer = $this->getWeigameService()->getKnowQuestionAnswerByUid($qid, $uid);
          if ($userAnswer && $userAnswer['status'] != 1) {
            if ($this->getWeigameService()->updateKnowQuestionAnswer(array(
              'id' => $userAnswer['id'],
              'qid' => $qid,
              'uid' => $uid,
              'status' => 1,
            ))) {
              echo $this->getWxpayService()->replyNotify(array(
                'return_code' => 'SUCCESS',
                'return_msg' => 'OK'
              ));
              exit;
            } else {
              Utils::log(__METHOD__ . ":: failed: fromkey:{$fromkey}, qid:{$qid}, uid:{$uid}, aid:{$aid}", 'know_del_answer');
            }
          }
        }
      }
  
    } catch (Exception $e) {
      Utils::log(__METHOD__ . ":: error: " . json_encode($e->getMessage()) . ", fromkey:{$fromkey}, qid:{$qid}, uid:{$uid}, aid:{$aid}", 'know_del_answer');
    }
  
    echo $this->getWxpayService()->replyNotify(array(
      'return_code' => 'FAIL',
      'return_msg' => '失败'
    ));
    exit;
  }
}
