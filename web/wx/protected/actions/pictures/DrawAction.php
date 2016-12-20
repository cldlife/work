<?php
/**
 * 我拍你画 照片主体以及画作处理
 */
class DrawAction extends CAction {

  const ERROR_CODE = 500;

  const EXTEND_TYPE_PICTURE = 1;

  const EXTEND_TYPE_DRAW = 2;

  public function run () {
    $this->getController()->defaultURIDoAction = 'draw';
    $method = $this->getController()->getURIDoAction($this, 1);
    $this->$method();
  }

  /**
   * 上传照片至实体,添加我的动态,添加帖子,添加附件,回更实体帖子ID
   */
  public function doDrawPicture () {
    $baseController = $this->getController();
    try {
      $img64 = $baseController->getSafeRequest("img64", "", "POST", "string");
      $imgFileInfo = $baseController->getSafeRequest("img_file_info", '', "POST", "json");
      
      if (!$img64 && !$imgFileInfo) {
        throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
      }
      
      //$imgFileInfo参数优先提取
      if ($imgFileInfo) {
        $res = $imgFileInfo;
      } else {
        $res = $baseController->getAttachmentService()->uploadImage($img64, 2);
        if ($res['code'] != 1) {
          throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
        }
      }
      
      $webappService = $baseController->getWebappService();
      $picture = $webappService->addPicture($res['fileInfo']['file_uri'] . $res['fileInfo']['file_name'], $baseController->currentUser['uid']);
      if (!$picture['pp_id']) {
        throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
      }
      
      //Flood Start 缓存 (间隔10秒)
      $cacheKey = __FUNCTION__ . '_UID_' . $baseController->currentUser['uid'];
      $waiting = $baseController->getCommonService()->getFromMemcache($cacheKey);
      if ($waiting) $baseController->json(1, "亲，请歇会稍后再试");
      
      // 添加帖子
      $attachHashID = time();
      $thingService = $baseController->getThingService();
      $thingContent = "求被画#我拍你画#";
      $thread = $thingService->addThread(array(
        'tid' => Utils::longIdGenerator(),
        'category' => 2, // 爆照
        'uid' => $baseController->currentUser['uid'],
        'content' => $thingContent,
        'attach_hashid' => $attachHashID,
        'extend_type' => self::EXTEND_TYPE_PICTURE,
        'extend_id' => $picture['pp_id'],
        'status' => 0,
        'create_time' => $attachHashID,
        'update_time' => $attachHashID 
      ));
      
      if (!$thread['tid']) {
        throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
      }
      // 回更实体
      if (!$webappService->updatePictureByPid($picture['pp_id'], array(
        'tid' => $thread['tid'] 
      ))) {
        throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
      }
      // 添加附件
      $attachmentService = $baseController->getAttachmentService();
      $attachInfo = array(
        'tid' => $thread['tid'],
        'aid' => Utils::longIdGenerator(),
        'attach_hashid' => $attachHashID,
        'type' => 0,
        'file_name' => $res['fileInfo']['file_name'],
        'file_uri' => $res['fileInfo']['file_uri'],
        'width' => $res['fileInfo']['width'],
        'height' => $res['fileInfo']['height'],
        'status' => 0,
        'order_id' => 1,
        'created_time' => $attachHashID,
        'updated_time' => $attachHashID 
      );
      if (!$attachmentService->addAttachment($attachHashID, $attachInfo)) {
        throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
      }
      
      //添加到我的帖子
      $baseController->getUserMineService()->addMineThread($baseController->currentUser['uid'], $thread['tid']);
      
      //Flood End 缓存
      $baseController->getCommonService()->setToMemcache($cacheKey, TRUE, $baseController::FLOOD_LIMIT_TIME);
      $baseController->json(0, "上传自拍成功", array(
        "pid" => $picture['pp_id'] 
      ));
    } catch (Exception $e) {
      $baseController->json($e->getCode(), $e->getMessage());
    }
  }

  /**
   * 上传画片至实体,添加我的动态,添加被画汇总
   */
  public function doDrawDraw () {
    $baseController = $this->getController();
    try {
      $img64 = $baseController->getSafeRequest("img64", "", "POST", "string");
      $rid = $baseController->getSafeRequest("rid", 0, "POST", "string");
      // $img64 = "iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAYAAACtWK6eAAAGeElEQVR4Xu3d0ZEbVRhE4esQIAYgBOeAUzDhQQo4BhMDMRADpardKteCvJrR9Mzcno9nqaU+Paf+B6PaD8N/CCBwl8AHbBBA4D4Bgng6EPgOAYJ4PBAgiGcAgXUEXJB13LzrIgQIcpGh1VxHgCDruHnXRQgQ5CJDq7mOwBJBPo8xvowx/ln3Ud6FwHwElgjy+xjj1zHG3y+i/DnG+Gu+yr4xAo8TWCLIa+rHF1E+jTF+epHldllcl8e5e+UkBNYI8m21H19kuV0W12WS0X3Nxwk8K8jbT3JdHmfvlRMQ2FoQ12WC0X3FxwkkBXFdHt/BK09KYE9BXJeTPgS+1n0CRwniungqpyBwFkFclykel+t9yTMK4rpc7zk8beMZBHFdTvv49H+x2QRxXfqfyVM1nF0Q1+VUj1Pfl2kSxHXpez4Pb9QsiOty+OM1/xe4iiCuy/zP6iENriqI63LI4zbfhxLkv5v5P5Lne45j35gg30d77/cutx+HfY2tIvg0BAiybIrX63L7cdjPfk25DN6MrybI+tVcl/XspnknQbabynXZjuVpkgiSmcJ1yXDdPZUg+yB3XfbhvPmnEGRzpO8Gui7vIjrPCwhy/Bauy/Eb3P0GBDnXOK7LufYYBDnZIG++juty8D4EOXiABR/vuiyAtdVLCbIVyf1zXJcdmBNkB8g7fITrEoJMkBDYg2Ndl40GIMhGIE8c47o8MQ5BnoA36VtdlwXDEWQBrMKXui7vjEqQwqf+iUquyxt4BHniaSp/q+syhn9JL3/It6z37XX5ZYxx+yOu9X+b0gXZ8hG6TtYPY4zbH3F9+7cp636rT5DrPNTJpv93XX5LfuBe2QTZi/R1Puf1uvzRUJkgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECNAkBhawQ0ECNKwog4xAgSJoRXcQIAgDSvqECPwL7vgockC0mbgAAAAAElFTkSuQmCC";
      if (!$img64) {
        throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
      }
      $res = $baseController->getAttachmentService()->uploadImage($img64, 2);
      if ($res['code'] != 1) {
        throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
      }
      $webappService = $baseController->getWebappService();
      $picture = $webappService->addDraw($res['fileInfo']['file_uri'] . $res['fileInfo']['file_name'], $baseController->currentUser['uid'], $rid);
      if (!$picture['pp_id']) {
        throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
      }
      $beDrawPicInfo = $webappService->getPicturesPaintsByRppid($rid);
      if (!$beDrawPicInfo) {
        throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
      }
      $beDrawUserInfo = $baseController->getUserService()->getUserByUid($beDrawPicInfo['uid']);
      if (!$beDrawUserInfo['nickname']) {
        throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
      }
      
      //Flood Start 缓存 (间隔10秒)
      $cacheKey = __FUNCTION__ . '_UID_' . $baseController->currentUser['uid'] . '_RID_' . $rid;
      $waiting = $baseController->getCommonService()->getFromMemcache($cacheKey);
      if ($waiting) $baseController->json(1, "亲，请歇会稍后再试");
      
      // 添加帖子
      $attachHashID = time();
      $thingService = $baseController->getThingService();
      $thingContent = "我给" . $beDrawUserInfo['nickname'] . "作的画像 #我拍你画#";
      $thread = $thingService->addThread(array(
        'tid' => Utils::longIdGenerator(),
        'category' => 2, // 爆照
        'uid' => $baseController->currentUser['uid'],
        'content' => $thingContent,
        'attach_hashid' => $attachHashID,
        'extend_type' => self::EXTEND_TYPE_DRAW,
        'extend_id' => $picture['pp_id'],
        'status' => 0,
        'create_time' => $attachHashID,
        'update_time' => $attachHashID 
      ));
      
      if (!$thread['tid']) {
        throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
      }
      
      // 回更实体
      if (!$webappService->updatePictureByPid($picture['pp_id'], array(
        'tid' => $thread['tid'] 
      ))) {
        throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
      }
      // 添加附件
      $attachmentService = $baseController->getAttachmentService();
      $attchInfo = array(
        'tid' => $thread['tid'],
        'aid' => Utils::longIdGenerator(),
        'attach_hashid' => $attachHashID,
        'type' => 0,
        'file_name' => $res['fileInfo']['file_name'],
        'file_uri' => $res['fileInfo']['file_uri'],
        'width' => $res['fileInfo']['width'],
        'height' => $res['fileInfo']['height'],
        'status' => 0,
        'order_id' => 1,
        'created_time' => $attachHashID,
        'updated_time' => $attachHashID 
      );
      if (!$attachmentService->addAttachment($attachHashID, $attchInfo)) {
        throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
      }
      
      //添加到我的帖子
      $baseController->getUserMineService()->addMineThread($baseController->currentUser['uid'], $thread['tid']);
      
      //发聊天消息
      $officialUserInfo = $baseController->getUserService()->getOfficialUserInfo();
      $baseController->getMessageService()->sendRcImMessage($officialUserInfo, array($beDrawPicInfo['uid'] ), 'painting', array($baseController->currentUser['nickname']));
      
      //Flood End 缓存
      $baseController->getCommonService()->setToMemcache($cacheKey, TRUE, $baseController::FLOOD_LIMIT_TIME);
      $baseController->json(0, "上传画作成功");
    } catch (Exception $e) {
      $baseController->json($e->getCode(), $e->getMessage());
    }
  }

  /**
   * 送花,汇总,添加最近送花人列表
   */
  public function doDrawVote () {
    $baseController = $this->getController();
    try {
      $pid = $baseController->getSafeRequest("pid", "0", "POST", "string");
      if (!$pid) throw new Exception("[" . __LINE__ . "]系统错误,请联系管理员", self::ERROR_CODE);
      $webappService = $baseController->getWebappService();
      $paint = $webappService->getPicturesPaintsByRppid($pid);
      if (!$paint['pp_id'] || $paint['status'] == 1) throw new Exception("此画作不存在或已删除", self::ERROR_CODE);
      if ($paint['uid'] == $baseController->currentUser['uid']) throw new Exception("不能给自己送玫瑰哦", self::ERROR_CODE);
      if (!$baseController->currentUser['status']['roses'] && !$baseController->currentUser['status']['coins']) throw new Exception("玫瑰和金币数量不足，请充值哦", self::ERROR_CODE);
      $user = $baseController->getUserService()->getUserByUid($paint['uid']);
      if (!$user) throw new Exception("该用户不存在或已删除", self::ERROR_CODE);
      
      // 验证用户财富
      // 优先使用玫瑰
      $roses = 0;
      if ($baseController->currentUser['status']['roses']) {
        $roses = 1;
        $baseController->getUserFortuneService()->autoUserFortuneRose($baseController->currentUser['uid'], 3);
        $baseController->getUserFortuneService()->autoUserFortuneRose($user['uid'], 4);
        
        // 玫瑰不足则使用金币
      } elseif ($baseController->currentUser['status']['coins']) {
        $roses = $baseController->getUserFortuneService()->getCoinExchangeRoseRate();
        $baseController->getUserFortuneService()->autoUserFortuneCoin($baseController->currentUser['uid'], 16);
        $baseController->getUserFortuneService()->autoUserFortuneRose($user['uid'], 5, $roses);
      }
      
      if ($roses) {
        // 我拍你画送花
        $baseController->getWebappService()->inDecreasePaintsByPid($pid, $paint['type'], array(
          array(
            'key' => 'votes',
            'value' => $roses,
            'in_de' => '+' 
          ) 
        ));
        // 帖子送花
        $baseController->getThingService()->inDecreaseThreadStatusByTid($paint['relation_tid'], array(
          array(
            'key' => 'votes',
            'value' => $roses,
            'in_de' => '+' 
          ) 
        ));
        // 送花列表
        $baseController->getThingService()->addThreadVoteUser($paint['relation_tid'], $baseController->currentUser['uid']);
      }
      $baseController->json(0, '献花成功', array(
        'uid' => $baseController->currentUser['uid'],
        'avatar' => $baseController->currentUser['avatar'],
        'num' => $roses 
      ));
    } catch (Exception $e) {
      $baseController->json($e->getCode(), $e->getMessage());
    }
  }

  /**
   * 画板页面
   */
  public function doDrawBoard () {
    $baseController = $this->getController();
    $data['pid'] = $baseController->getSafeRequest("ppid", "0", "GET", "string");
    $data['wx'] = $baseController->getSafeRequest("wx", "0", "GET", "string");
    $webappService = $baseController->getWebappService();
    $paint = $webappService->getPicturesPaintsByRppid($data['pid']);
    $data['image'] = WEB_QW_APP_FILE_DOMAIN . $paint['file_uri'] . APP_DYNAMIC_FILE_RULE_750;
    $this->getController()->render(FB_END_TPL_PATH . "/app/painting/pictures/draw/board.tpl", $data);
  }

  /**
   * 微信上传照片
   */
  public function doDrawWxpic () {
    $this->getController()->render(FB_END_TPL_PATH . "/app/painting/pictures/draw/wxpic.tpl");
  }

  /**
   * 微信之个人主页
   */
  public function doDrawWxpage () {
    $baseController = $this->getController();
    $id = $baseController->getSafeRequest("ppid", "0", "GET", "string");
    $paint = $baseController->getWebappService()->getPicturesPaintsByRppid($id);
    $paintStatus = $baseController->getWebappService()->getPicturesPaintsStatusByPPid($id);
    $paintUser = $baseController->getUserService()->getUserByUid($paint['uid']);
    $titles = array(
      '这是'.$paintUser['nickname'].'的画作,给TA送花吧'
    );
    $titleIndex = rand(0,count($titles)-1);
    $data['img'] = WEB_QW_APP_FILE_DOMAIN . $paint['file_uri'] . APP_DYNAMIC_FILE_RULE_750;
    $data['ppid'] = $id;
    $data['paintings'] = $paintStatus['paintings'];
    $data['title'] = $titles[$titleIndex];
    $data['nickname'] = $paintUser['nickname'];
    if ($paint['uid'] == $baseController->currentUser['uid']) {
      $this->getController()->render(FB_END_TPL_PATH . "/app/painting/pictures/draw/wxmy.tpl", $data);
    } else {
      $this->getController()->render(FB_END_TPL_PATH . "/app/painting/pictures/draw/wxother.tpl", $data);
    }
  }

}