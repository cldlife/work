<?php
class ThingController extends BaseController {

  /**
   * @desc 帖子详细页
   */
  public function actiondetail () {
    $tid = $this->getSafeRequest('tid', 0, 'GET', 'int');
    
    $thread = array();
    if ($tid) $thread = $this->getThingService()->getThreadBytid($tid);
    if ($thread) {
      
      $user = $this->getUserService()->getUserByUid($thread['uid'], TRUE);
      $user['status'] = $this->getUserFortuneService()->getUserFortuneStatusByUid($user['uid']);
      $userLevel = $this->getUserService()->getUserLevel($user['status']['points']);
      
      $threadPostList = $this->getThingService()->getThreadPostListBytid($tid);
      if ($threadPostList) {
        $repliedlist = array();
        foreach ($threadPostList as $key => $item) {
          $user = $this->getUserService()->getUserByUid($item['uid']);
          $repliedlist[$key]['user_nickname'] = $user['nickname'];
          if ($item['replied_uid']) {
            $replied_user = $this->getUserService()->getUserByUid($item['replied_uid']);
            $repliedlist[$key]['replied_nickname'] = $replied_user['nickname'];
          }
          $repliedlist[$key]['content'] = $item['content'];
          unset($item);
        }
        unset($threadPostList);
      }
      
      $data = array();
      $data['replied'] = $repliedlist;
      $data['thread'] = $thread;
      $data['user'] = $user;
      $data['grade'] = $userLevel['id'] ? $userLevel['id'] : '';
      
      if ($thread['category'] == 2) {
        // 爆照
        $baozhao = $this->getThingService()->getThreadListByCategory(2, 1, 20);
        if ($baozhao) {
          shuffle($baozhao);
          $baozhaolist = array();
          foreach ($baozhao as $key => $item) {
            $baozhaolist[$key]['img'] = WEB_QW_APP_DYNAMIC_FILE_DOMAIN . $item['images'][0]['uri'] . APP_DYNAMIC_FILE_RULE_360x360;
            $baozhaolist[$key]['href'] = Utils::getThingThreadLink($item['tid']);
            unset($item);
          }
          unset($baozhao);
          $data['baozhaolist'] = $baozhaolist;
        }
        $this->render('baozhao', $data);
      
      } elseif ($thread['category'] == 1) {
        // 喊话
        $hanhua = $this->getThingService()->getThreadListByCategory(1, 1, 20);
        if ($hanhua) {
          shuffle($hanhua);
          $hanhualist = array();
          foreach ($hanhua as $key => $item) {
            $hanhualist[$key]['href'] = Utils::getThingThreadLink($item['tid']);
            $hanhualist[$key]['content'] = $item['content'];
            $user = $this->getUserService()->getUserByUid($item['uid']);
            $hanhualist[$key]['nickname'] = $user['nickname'];
            unset($item);
          }
          unset($hanhua);
          $data['hanhualist'] = $hanhualist;
        }
        $this->render('hanhua', $data);
      }
    } else {
      $this->redirect(WEB_QW_APP_DOMAIN);
    }
  }

  /**
   * @desc 歌曲详细页
   */
  public function actionSong () {
    $songId = $this->getSafeRequest('song_id', 0, 'GET', 'int');
    if ($songId) {
      $song = $this->getGameService()->getGameTgqcgmTmById($songId);
      if ($song && $song['uid']) {
        $user = $this->getUserService()->getUserByUid($song['uid'], TRUE);
        $user['status'] = $this->getUserFortuneService()->getUserFortuneStatusByUid($user['uid']);
        $userLevel = $this->getUserService()->getUserLevel($user['status']['points']);
        
        $data = array();
        $data['avatar'] = $user['avatar'];
        $data['nickname'] = $user['nickname'];
        $data['grade'] = intval($userLevel['id']);
        $data['song_name'] = $song['song_name'];
        $data['duration'] = $song['duration'];
        $data['song_url'] = WEB_QW_APP_FILE_DOMAIN . preg_replace('/\..*?$/i', '.mp3', $song['uri']);
      }
      
      $this->render('song', $data);
    } else {
      $this->redirect(WEB_QW_APP_DOMAIN);
    }
  }
}
?>