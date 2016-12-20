<?php
class GameController extends BaseController {
  
  //游戏列表配置
  private function getGamelist() {
    return array(
      5 => array('gid' => 5, 'name' => '我拍你画', 'players' => $this->getPseudoOnlinePlayer()['pseudo_count'], 'img' => WEB_QW_APP_DYNAMIC_FILE_DOMAIN . '/ui/img/app/banner/mini_index_painting_710x230_1.png', 'link' => WEB_QW_APP_WX_DOMAIN . '/pictures/list/hothomepage.html', 'skin_color' => '#ff7733'),
      4 => array('gid' => 4, 'name' => '谁是卧底', 'players' => $this->getPseudoOnlinePlayer()['pseudo_count'] * 2, 'img' => WEB_QW_APP_DYNAMIC_FILE_DOMAIN . '/ui/img/app/banner/mini_index_spy_710x230_1.png', 'link' => 'WanZhu://woisspy', 'skin_color' => ''),
      1 => array('gid' => 1, 'name' => '听歌曲猜歌名', 'players' => $this->getPseudoOnlinePlayer()['pseudo_count'], 'img' => WEB_QW_APP_DYNAMIC_FILE_DOMAIN . '/ui/img/app/banner/mini_index_tgcgm_710x230_1.png', 'link' => 'WanZhu://gongxiangequ', 'skin_color' => ''),
      2 => array('gid' => 2, 'name' => '七宗罪', 'players' => $this->getPseudoOnlinePlayer()['pseudo_count'], 'img' => WEB_QW_APP_DYNAMIC_FILE_DOMAIN . '/ui/img/app/banner/mini_index_sevensin_710x230_1.png', 'link' => WEB_QW_APP_M_DOMAIN . '/app/seven/index.html', 'skin_color' => '#7965db'),
      3 => array('gid' => 3, 'name' => '炫耀神器', 'players' => $this->getPseudoOnlinePlayer()['pseudo_count'], 'img' => WEB_QW_APP_DYNAMIC_FILE_DOMAIN . '/ui/img/app/banner/mini_index_zb_710x230_1.png', 'link' => WEB_QW_APP_M_DOMAIN . '/app/zhuangbi/zb.html', 'skin_color' => '#39364d'),
    );
  }
  
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
    
    //游戏banner列表
    $games = array();
    foreach (self::getGamelist() as $game) {
      $games[] = $game;
    }
    
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
    $data['header'] = array(
      'bg_url' => WEB_QW_APP_DYNAMIC_FILE_DOMAIN . '/ui/img/app/header/bg.png',
      'avatar_decorated_url' => WEB_QW_APP_DYNAMIC_FILE_DOMAIN . '/ui/img/app/header/head_cap.png',
    );
    $data['banners'] = $banners;
    $data['games'] = $games;
    $data['recommend_users'] = $recommendUsers;
    $this->outputJsonData(0, $data);
  }
}
?>