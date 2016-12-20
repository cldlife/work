<?php
/**
 * @desc 歌曲管理
 */
class SongsController extends BaseController {

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
   * @desc 歌曲审核
   */
  private function doAudit () {
    $action = $this->getSafeRequest('action', 'search');
    $keyword = $this->getSafeRequest('keyword');
    $status = $this->getSafeRequest('status', 0, 'GET', 'int');
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');
    $pageSize = 20;

    $list = array();
    if ($action == 'search' && $keyword) {
      $list = $this->getGameService()->getGameTgqcgmTmsLikeSongName($keyword, $status, $page, $pageSize);
    } else if ($action == 'search') {
      $list = $this->getGameService()->getGameTgqcgmTms($status, $page, $pageSize);
    }

    $songList = array();
    if ($list) {
      foreach ($list as $song) {
        if (!$song['uid']) continue;
        $tmpSong = $song;
        $tmpSong['user_info'] = $this->getUserService()->getUserByUid($song['uid']);
        $tmpSong['online_link'] = WEB_QW_APP_M_DOMAIN . "/s/{$song['tm_id']}.html";
        $songList[] = $tmpSong;
      }
    }

    //分页处理
    $pageCount = self::DEFAULT_PAGER_PAGESIZE * $pageSize;
    $count = count($songList);
    if ($count < $pageSize) $pageCount = ($page - 1) * $pageSize + $count;

    $data = array();
    $data['keyword'] = $keyword;
    $data['status'] = $status;
    $data['curPage'] = $page;
    $data['songList'] = $songList;
    $data['pager'] = $this->getPager($pageCount, $page, $pageSize);
    $this->render('audit', $data);
  }

  const CONTRIBUTE_SONG_RULEID = 13;
  //通过,拒绝歌曲审核
  private function doAuditOnoff () {
    $tmId = $this->getSafeRequest('tm_id', 0, 'POST', 'int');
    $status = $this->getSafeRequest('status', NULL, 'POST', 'int');
    if ($tmId && $status !== NULL) {
      $song = $this->getGameService()->getGameTgqcgmTmById($tmId);
      if (!$song) $this->outputJsonData(array('code' => 1));

      $status = ($status == 0) ? 1 : 0;
      if ($this->getGameService()->updateGameTgqcgmTmById($tmId, array('status' => $status))) {
        $desc = ($status == 1 ? "已通过" : "未通过") . "，于" . Utils::getDiffTime(time()) . "更新";
        if ($status == 1)  {
          //加金币 & 发消息
          $res = $this->getUserFortuneService()->autoUserFortuneCoin($song['uid'], self::CONTRIBUTE_SONG_RULEID);
          if ($res) {
            $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
            $this->getMessageService()->sendRcImMessage($officialUserInfo, array($song['uid']), 'reward_coins', array($song['song_name'], $res['coin']));
          }
        }
        $this->outputJsonData(array('code' => 0, 'status' => $status, 'desc' => $desc));
      } else {
        $this->outputJsonData(array('code' => 2));
      }
    }
    $this->outputJsonData(array('code' => -1));
  }

  /**
   * @desc 歌曲上传
   */
  private function doUpload () {
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');

    $pageSize = 50;
    $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
    
    $vestUserList = array();
    $vestUsers = $this->getUserService()->getBkAdminUserVests($officialUserInfo['uid'], $page, $pageSize);
    if ($vestUsers) {
      foreach ($vestUsers as $vestUser) {
        if (!$vestUser['online_uid']) continue;
        $vestUserList[] = $this->getUserService()->getUserByUid($vestUser['online_uid'], TRUE);
        unset($user);
      }
    }

    $data = array();
    $data['vestUserList'] = $vestUserList;
    $this->render('upload', $data);
  }

  private function doUploadFile () {
    $name = $file = '';
    foreach ($_POST as $key => $val) {
      if ($key == '_sh_token_' || !$val) continue;
      $key = trim(str_replace('－', '-', $key));
      $key = trim(str_replace('__', '_', $key));
      $val = trim($val, ' _');
      $name = trim($key, '_');
      $file = $val;
      break;
    }
    
    if (!$name || !$file) $this->outputJsonData(array('code' => 1, 'msg' => '获取歌曲数据失败!'));
    $songInfo = $this->formateFileName($name);
    if (!$songInfo) $this->outputJsonData(array('code' => 2, 'msg' => '歌曲名称格式错误!', 'name' => $name));

    $tmpFile = "/tmp/{$songInfo['full_name']}";
    if (!file_put_contents($tmpFile, base64_decode($file))) $this->outputJsonData(array('code' => 3, 'msg' => '生成歌曲文件失败!'));
    $res = $this->getAttachmentService()->uploadAttach($tmpFile);
    unlink($tmpFile);
    if (!$res || $res['code'] != 1) $this->outputJsonData(array('code' => 4, 'msg' => '上传到服务器失败!'));

    $officialUserInfo = $this->getUserService()->getOfficialUserInfo();
    if ($this->getGameService()->addGameTgqcgmTm(array(
      'uid' => $_POST['vest_uid'] ? $_POST['vest_uid'] : $officialUserInfo['uid'],
      'song_name' => $songInfo['song'],
      'singer' => $songInfo['singer'],
      'uri' => $res['fileInfo']['file_uri'] . $res['fileInfo']['file_name'],
      'ori_name' => $res['fileInfo']['ori_name'],
      'duration' => $songInfo['duration'],
      'status' => 1,
    ))) {
      $this->outputJsonData(array('code' => 0, 'msg' => '上传歌曲成功!'));
    } else {
      $this->outputJsonData(array('code' => 5, 'msg' => '添加猜歌题目失败!'));
    }
  }

  private function formateFileName ($name) {
    if ($name) {
      list($singer, $name) = explode('-', $name);
      $singer = trim($singer, ' _');
      $name = trim($name, ' _');
      if ($singer && $name) {
        list($song, $ignore, $duration, $ext) = explode('_', $name);
        $song = trim($song, ' _');
        $duration = trim($duration, ' _s');
        $ext = trim($ext, ' ._');
        if ($song && $duration && $ext) {
          $fullName = "{$singer}-{$song}_{$ignore}_{$duration}.{$ext}";
          return array(
            'singer' => $singer,
            'song' => $song,
            'duration' => $duration,
            'full_name' => $fullName,
          );
        }
      }
    }
    return array();
  }

  /**
   * @desc 错误处理回调方法
   */
  public function errorRedirect () {
    $this->redirect($this->getDeUrl('main/error', array('id' => -404)));
  }
}
