<?php
/**
 * @desc 谁是卧底管理
 */
class GamesetController extends BaseController {

  /**
   * @desc actions 主入口
   */
  public function run ($actionID = NULL) {
    parent::filters();
    $this->defaultURIDoAction = 'base';
    $method = $this->getURIDoAction($this);
    $this->$method();
  }

  /**
   * @desc 卧底词管理
   */
  private function doSpyword () {
    $keyword = $this->getSafeRequest('keyword');
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');
    $pageSize = 20;

    if ($keyword) {
      $wordList = $this->getGameService()->getGamesetSpywordsLikeKeyword($keyword, $page, $pageSize);
    } else {
      $wordList = $this->getGameService()->getGamesetSpywords($page, $pageSize);
    }

    //分页处理
    $pageCount = self::DEFAULT_PAGER_PAGESIZE * $pageSize;
    $count = count($wordList);
    if ($count < $pageSize) $pageCount = ($page - 1) * $pageSize + $count;

    $data = array(
      'wordList' => $wordList,
      'page' => $page,
      'pager' => $this->getPager($pageCount, $page, $pageSize),
    );
    $this->render('spyword', $data);
  }

  /**
   * @desc 惩罚管理
   */
  private function doPunish () {
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');
    $pageSize = 20;
    $punishList = $this->getGameService()->getGamesetPunishs($page, $pageSize);

    //分页处理
    $pageCount = self::DEFAULT_PAGER_PAGESIZE * $pageSize;
    $count = count($punishList);
    if ($count < $pageSize) $pageCount = ($page - 1) * $pageSize + $count;

    $data = array(
      'punishList' => $punishList,
      'page' => $page,
      'pager' => $this->getPager($pageCount, $page, $pageSize),
    );
    $this->render('punish', $data);
  }

  /**
   * @desc 回复项管理
   */
  private function doResponse () {
    $rid = $this->getSafeRequest('rid', 0, 'GET', 'int');
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');
    $ref = $this->getSafeRequest('ref', '', 'GET', 'string');
    $pageSize = 20;

    $gameset = $this->getGameService()->getGamesetById($rid);
    if ($rid && $gameset) {
      $responseList = $this->getGameService()->getGamesetResponseList($rid, $page, $pageSize);

      //分页处理
      $pageCount = self::DEFAULT_PAGER_PAGESIZE * $pageSize;
      $count = count($responseList);
      if ($count < $pageSize) $pageCount = ($page - 1) * $pageSize + $count;

      $data = array(
        'gameset' => $gameset,
        'responseList' => $responseList,
        'ref' => $ref,
        'page' => $page,
        'pager' => $this->getPager($pageCount, $page, $pageSize),
      );
      $this->render('response', $data);
    }
  }

  /**
   * @desc add update spyword,punish,response
   */
  private function doAddupdate () {
    $id = $this->getSafeRequest('gid', 0, 'POST', 'int');
    $type = $this->getSafeRequest('type', NULL, 'POST', 'int');
    $rid = $this->getSafeRequest('rid', NULL, 'POST', 'int');
    $content = $this->getSafeRequest('content', '', 'POST', 'string');

    if (($type === NULL && $rid === NULL) || !$content) {
      $this->outputJsonData(array('code' => 1, 'msg' => '提交数据不完整'));
    }

    $typeStr = '回复';
    if ($type == 1) {
      $typeStr = '卧底词';
    } else if ($type == 2) {
      $typeStr = '惩罚';
    }

    if ($id) {
      $gameset = $this->getGameService()->getGamesetById($id);
      if (!$gameset) $this->outputJsonData(array('code' => 2, 'msg' => "该项{$typeStr}不存在"));
      if ($type == 1) {
        $words = json_decode($content, TRUE);
        if ($words && $words['spy'] == $gameset['words']['spy'] && $words['normal'] == $gameset['words']['normal']) {
          $this->outputJsonData(array('code' => 3, 'msg' => "{$typeStr}内容没有修改"));
        }
      } else if ($gameset['content'] == $content) {
        $this->outputJsonData(array('code' => 3, 'msg' => "{$typeStr}内容没有修改"));
      }

      if ($this->getGameService()->updateGamesetById($id, array('content' => $content))) {
        $this->outputJsonData(array('code' => 0));
      } else {
        $this->outputJsonData(array('code' => 4, 'msg' => "修改{$typeStr}失败"));
      }

    } else {
      $fields = array('content' => $content);
      if ($type) {
        $fields['type'] = $type;
      } else if ($rid) {
        $fields['rid'] = $rid;
      }

      if ($this->getGameService()->addGameset($fields)) {
        $this->outputJsonData(array('code' => 0));
      } else {
        $this->outputJsonData(array('code' => 2, 'msg' => "添加{$typeStr}失败"));
      }
    }
  }

  /**
   * @desc delete spyword,punish,response
   */
  private function doDelete () {
    $id = $this->getSafeRequest('gid', 0, 'POST', 'int');
    if ($id) {
      if ($this->getGameService()->deleteGamesetById($id)) {
        $this->outputJsonData(array('code' => 0));
      } else {
        $this->outputJsonData(array('code' => 1, 'msg' => '删除失败'));
      }
    }
  }

  /**
   * @desc 游戏假用户/机器人管理
   */
  private function doPseudouser () {
    $isUsing = $this->getSafeRequest('is_using', 0, 'GET', 'int');
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');
    $pageSize = 20;

    $userList = $this->getGameService()->getPseudoUserListByUsing($isUsing, $page, $pageSize);

    //分页处理
    $pageCount = self::DEFAULT_PAGER_PAGESIZE * $pageSize;
    $count = count($userList);
    if ($count < $pageSize) $pageCount = ($page - 1) * $pageSize + $count;

    $data = array(
      'isUsing' => $isUsing,
      'userList' => $userList,
      'page' => $page,
      'pager' => $this->getPager($pageCount, $page, $pageSize),
    );

    $this->render('pseudouser', $data);
  }

  /**
   * @desc 添加/修改游戏假用户
   */
  private function doPseudouserAddedit () {
    $uid = $this->getSafeRequest('uid', 0, 'GET', 'int');
    $code = $this->getSafeRequest('code', 0, 'GET', 'int');
    $ref = $this->getSafeRequest('ref', '', 'GET', 'string');

    $action = $this->getSafeRequest('action', '', 'POST', 'string');
    $nickname = $this->getSafeRequest('nickname', '', 'POST', 'string');
    $birthday = $this->getSafeRequest('birthday', '', 'POST', 'string');
    $gender = $this->getSafeRequest('gender', 0, 'POST', 'int');
    $isUsing = $this->getSafeRequest('is_using', 0, 'POST', 'int');
    $isUpload = $this->getSafeRequest('is_upload', 0, 'POST', 'int');

    $userInfo = $uid ? $this->getGameService()->getPseudoUserByUid($uid) : array();
    if ($nickname) {
      $fields = array();
      if ($nickname != $userInfo['nickname']) $fields['nickname'] = $nickname;
      if ($isUsing != $userInfo['is_using']) $fields['is_using'] = $isUsing;
      if ($birthday && $birthday != $userInfo['birthday']) $fields['birthday'] = $birthday;
      if ($gender != $userInfo['gender']) $fields['gender'] = $gender;
    }

    $avatarSize = '200';
    if ($action == 'post' && ($fields || $isUpload)) {

      if (!$uid) {
        $uid = $this->getGameService()->addPseudoUser($fields);
        if (!$uid) {
          $this->redirect($this->getDeUrl('gameset/pseudouser/addedit', array(
              'id' => $this->permissionId, 'uid' => 0, 'ref' => urlencode($ref), 'code' => 2)));
          exit;
        }
        $fields = array();
      }

      if ($uid && $isUpload) {
        $res = $this->getAttachmentService()->uploadAvatar($_FILES['avatar_img']['tmp_name'], 0);
        if ($res['code'] == 1 && $res['fileInfo']) {
          $fields['avatar'] = WEB_QW_APP_FILE_DOMAIN . "/{$res['fileInfo']['file_uri']}{$res['fileInfo']['file_name']}/{$avatarSize}";
        }
      }

      if ($uid && $fields) {
        if ($this->getGameService()->updatePseudoUserByUid($uid, $fields)) {
          $this->redirect($this->getDeUrl('gameset/pseudouser/addedit', array(
                'id' => $this->permissionId, 'uid' => $uid, 'ref' => urlencode($ref), 'code' => 1)));
          exit;
        } else {
          $userInfo = $fields;
          $code = 2;
        }
      }
    }

    $data = array(
      'userInfo' => $userInfo,
      'uid' => $uid,
      'ref' => $ref,
      'code' => $code,
    );
    $this->render('pseudouser_addedit', $data);
  }

  /**
   * @desc delete pseudouser
   */
  private function doPseudouserDelete () {
    $uid = $this->getSafeRequest('uid', 0, 'POST', 'int');
    if ($uid) {
      if ($this->getGameService()->deletePseudoUserByUid($uid)) {
        $this->outputJsonData(array('code' => 0));
      } else {
        $this->outputJsonData(array('code' => 1, 'msg' => '删除失败'));
      }
    }
  }

  /**
   * @desc 错误处理回调方法
   */
  public function errorRedirect () {
    $this->redirect($this->getDeUrl('main/error', array('id' => -404)));
  }
}


