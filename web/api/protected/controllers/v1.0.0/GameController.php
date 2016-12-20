<?php
class GameController extends BaseController {
  
  //推荐用户uids
  private static function getRecommendUids () {
    return array(1, 2, 3, 4, 5, 6, 7, 8);
  }
  
  //根据当前整点数获取随机在线人数
  private function getPseudoOnlinePlayer () {
    $res = array();
    $res['factor'] = 2;
    $hour = date('G');
    if ($hour >= 21) {
      $res['pseudo_count'] = mt_rand(1500, 4300);
    } else if ($hour >= 19) {
      $res['pseudo_count'] = mt_rand(3000, 3400);
    } else if ($hour >= 17) {
      $res['pseudo_count'] = mt_rand(2500, 3000);
    } else if ($hour >= 14) {
      $res['pseudo_count'] = mt_rand(2000, 2500);
    } else if ($hour >= 11) {
      $res['pseudo_count'] = mt_rand(1700, 2000);
    } else if ($hour >= 6) {
      $res['pseudo_count'] = mt_rand(1300, 1700);
    } else if ($hour >= 4) {
      $res['pseudo_count'] = mt_rand(1000, 1200);
    } else if ($hour >= 2) {
      $res['pseudo_count'] = mt_rand(1100, 1300);
    } else {
      $res['pseudo_count'] = mt_rand(1400, 1500);
    }
    return $res;
  }
  
  /**
   * @desc 游戏首页
   */  
  public function actionIndex () {
    //轮播图banner
    $banners = array();
    $banners[0]['img'] = WEB_QW_APP_DYNAMIC_FILE_DOMAIN . '/ui/img/app/banner/top_690x200_20161208.jpg';
    $banners[0]['link'] = 'WanZhu://gongxiangequ';
    $banners[0]['type'] = rand(0, 2);
    
    //游戏banner列表
    $games = array();
    $games[0]['gid'] = 1;
    $games[0]['name'] = '听歌曲猜歌名';
    $games[0]['players'] = $this->getPseudoOnlinePlayer()['pseudo_count'];
    $games[0]['img'] = WEB_QW_APP_DYNAMIC_FILE_DOMAIN . '/ui/img/app/game/index_710x480_1.png';
    
    //推荐用户
    $recommendUsers = array();
    foreach (self::getRecommendUids() as $uid) {
      $user = $this->getUserService()->getUserByUid($uid);
      if (!$user) continue;
      $tmpUser = array();
      $tmpUser['uid'] = $user['uid'];
      $tmpUser['nickname'] = $user['nickname'];
      $tmpUser['avatar'] = $user['avatar'];
      $tmpUser['desc'] = '玩主推荐';
      $recommendUsers[] = $tmpUser;
      unset($tmpUser);
      unset($user);
    }
    
    $data = array();
    $data['banners'] = $banners;
    $data['games'] = $games;
    $data['recommend_users'] = $recommendUsers;
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 推荐好友列表
   */
  public function actionRecommendUsers () {
    $page = $this->getSafeRequest('page', 1, 'int');
    
    $list = array();
    if ($page == 1) {
      $friends = $this->getUserMineService()->getMineFriendsByFriendUids($this->currentUser['uid'], self::getRecommendUids());
      foreach (self::getRecommendUids() as $uid) {
        $user = $this->getUserService()->getUserByUid($uid);
        if (!$user) continue;
        $user['status'] = $this->getUserFortuneService()->getUserFortuneStatusByUid($user['uid']);
        $userLevel = $this->getUserService()->getUserLevel($user['status']['points']);
        
        $tmpUser = array();
        $tmpUser['uid'] = $user['uid'];
        $tmpUser['nickname'] = $user['nickname'];
        $tmpUser['avatar'] = $user['avatar'];
        $tmpUser['level_num'] = intval($userLevel['id']);
        $tmpUser['friending_roses'] = intval($user['status']['friending_roses']);
        $tmpUser['friend_status'] = in_array($user['uid'], $friends) ? 1 : 0;
        $list[] = $tmpUser;
        unset($tmpUser);
        unset($user);
        unset($userLevel);
      }
    }
    
    $data = array();
    $data['list'] = $list;
    $this->outputJsonData(0, $data);
  }
  
  /**
   * @desc 游戏信息
   */
  public function actionInfo () {
    $gameId = $this->getSafeRequest('gid', 0, 'int');
    
    $pseudoOnlinePlayerself = $this->getPseudoOnlinePlayer();
    $data = array();
    $data['info']['roomid'] = 'tgqcgm10001';
    $data['info']['name'] = '听歌曲猜歌名';
    $data['info']['players'] =  $pseudoOnlinePlayerself['pseudo_count'];
    $data['info']['factor'] =  $pseudoOnlinePlayerself['factor'];
    $data['info']['quit_coins'] = 10;
    $data['info']['play_permission'] = 1;//$this->currentUser['status']['coins'] ? 1 : 0;
    $data['info']['im_config'] = array(
      'YuYin' => 0
    );
    $this->outputJsonData(0, $data);
  }
}
