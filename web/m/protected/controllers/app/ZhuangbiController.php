<?php
/**
 * @desc 装逼
 * @author dong
 */
class ZhuangbiController extends BaseController {

  //标题
  private static $randTitles = array(
    'zhuangbi' => array(
      'default' => '装逼助手',
    )
  );

  //首页列表
  private static $listQas = array(
    'zhuangbi' => array(
      'bchc' => array('url' => 'zbbchc','imgurl' => '/img/app/zhuangbi/s1.jpg', 'imgurl2' => '/img/app/zhuangbi/t1.jpg', 'title' => '奔驰豪车订单生成器', 'players' => '19235'),
      'bjfc' => array('url' => 'zbbjfc','imgurl' => '/img/app/zhuangbi/s2.jpg', 'imgurl2' => '/img/app/zhuangbi/t2.jpg', 'title' => '北京房产证生成器', 'players' => '14286'),
      'shfc' => array('url' => 'zbshfc','imgurl' => '/img/app/zhuangbi/s3.jpg',  'imgurl2' => '/img/app/zhuangbi/t3.jpg', 'title' => '上海房产证生成器', 'players' => '8973'),
      'myhy' => array('url' => 'zbmyhy','imgurl' => '/img/app/zhuangbi/s4.jpg',  'imgurl2' => '/img/app/zhuangbi/t4.jpg', 'title' => '湖畔大学上课图片生成器', 'players' => '11791'),
      'hyzs' => array('url' => 'zbhyzs','imgurl' => '/img/app/zhuangbi/s5.jpg',  'imgurl2' => '/img/app/zhuangbi/t5.jpg', 'title' => '订婚戒指生成器', 'players' => '7233'),
      'hfbb' => array('url' => 'zbhfbb','imgurl' => '/img/app/zhuangbi/s6.jpg',  'imgurl2' => '/img/app/zhuangbi/t6.jpg', 'title' => '花旗大厦表白生成器',  'players' => '16321'),
      'wyfwb' => array('url' => 'zbwyfwb','imgurl' => '/img/app/zhuangbi/s7.jpg',  'imgurl2' => '/img/app/zhuangbi/t7.jpg', 'title' => '吴亦凡绯闻生成器', 'players' => '24924'),
    )
  );

  //装逼生成器
  private static $commonQas = array(
    'bchc' => array('imgurl' => '/FB-End/files/ui/img/app/zhuangbi/1.jpg', 'default' => array(array('fontsize' => '16, 1, 116, 813', 'color' => '14, 14, 14', 'font' => '/FB-End/files/ui/font/fzjl.ttf')), 'time' => array(array('fontsize' => '12, 1, 100, 835, 1', 'color' => '14, 14, 14', 'font' => '/FB-End/files/ui/font/fzjl.ttf'))),
    'bjfc' => array('imgurl' => '/FB-End/files/ui/img/app/zhuangbi/2.jpg', 'default' => array(array('fontsize' => '12, 0, 293, 200', 'color' => '112, 111, 102', 'font' => '/FB-End/files/ui/font/simsun.ttc')), 'time' => array(array('fontsize' => '12, 0, 290, 345, 2', 'color' => '112, 111, 102', 'font' => '/FB-End/files/ui/font/simsun.ttc'))),
    'shfc' => array('imgurl' => '/FB-End/files/ui/img/app/zhuangbi/3.jpg', 'default' => array(array('fontsize' => '12, 0, 293, 200', 'color' => '112, 111, 102', 'font' => '/FB-End/files/ui/font/simsun.ttc')), 'time' => array(array('fontsize' => '12, 0, 290, 345, 2', 'color' => '112, 111, 102', 'font' => '/FB-End/files/ui/font/simsun.ttc')),),
    'myhy' => array('imgurl' => '/FB-End/files/ui/img/app/zhuangbi/4.jpg', 'default' => array(array('fontsizeone' => '25, -3, 305, 797', 'fontsizetwo' => '25, -3, 286, 797', 'fontsizethree' => '25, -3, 272, 797', 'fontsizefour' => '25, -3, 258, 797','color' => '22, 26, 22',
     'font' => '/FB-End/files/ui/font/hydsj.ttf'))),
    'hyzs' => array('imgurl' => '/FB-End/files/ui/img/app/zhuangbi/5.jpg', 'default' => array(array('fontsize' => '20, 0, 91, 201', 'color' => '26, 12, 17', 'font' => '/FB-End/files/ui/font/fzjl.ttf'))),
    'hfbb' => array('imgurl' => '/FB-End/files/ui/img/app/zhuangbi/6.jpg', 'default' => array(array('fontsize' => '25, 0, 445, 225, 40, 30, 55', 'color' => '253, 75, 146', 'font' => '/FB-End/files/ui/font/msyhbd.ttf')), 'like' => array(array('topys' => '我喜欢你', 'fontsize' => '25, 0, 405, 355, 40, 30, 55', 'color' => '253, 75, 146', 'font' => '/FB-End/files/ui/font/msyhbd.ttf')), 'format' => 'isupright'),
    'wyfwb' => array('imgurl' => '/FB-End/files/ui/img/app/zhuangbi/7.jpg', 'text' => array(array('topys' => '吴亦凡接受采访，曝光与***恋情', 'fontsize' => '20, 0, 58, 791', 'color' => '255, 255, 255', 'font' => '/FB-End/files/ui/font/fzltxh.ttf'))),
  );
  
  /**
   * @desc 装逼首页
  */ 
  public function actionZb() {
    $this->layout = "main_zhuangbi";
    $this->title = self::$randTitles['zhuangbi']['default'];
    $gamelist = array();
      foreach (self::$listQas['zhuangbi'] as $zb) {
      $game = array();
      $game['url'] = $zb['url'];
      $game['imgurl'] = $zb['imgurl'];
      $game['imgurl2'] = $zb['imgurl2'];
      $game['title'] = $zb['title'];
      /*根据时间蹉模拟出的访客量*/
      $time = time();
      $time = $time / 240;
      $fixTime = '2016-11-21 21:49';
      $fixTime = strtotime($fixTime)/240;
      $time = round($time) - round($fixTime);
      $players = $zb['players'] + $time;
      $game['players'] = $players;
      $gamelist[] = $game;
    }
    $data = array();
    $data['gamelist'] = $gamelist;
    $this->render("zb1", $data);
    
  }

  /**
   * @desc 装逼制作图片
   */
  public function actionZbCreat() {
    $this->layout = "main_zhuangbi";
    $this->title = self::$randTitles['zhuangbi']['default'];
    $category = $this->getSafeRequest('category', '', 'GET', 'string');
  
    $data = array();
    $key = self::$listQas['zhuangbi'][$category];
    if ($key) {
      
      $step = $this->getSafeRequest('step', 0, 'GET', 'int');
      $username = $this->getSafeRequest('username', '', 'GET', 'string');  
      $username = urldecode($username);
      if ($step) {
        if (!$username) $this->redirect($this->getDeUrl("app/zhuangbi/zb{$category}"));
        
        if ($step == 1) {
          $data['imgurl'] = $this->getDeUrl("app/zhuangbi/zb{$category}", array('step' => 2, 'username' => urlencode($username)));
          $this->render("zb3", $data);
        } elseif ($step == 2) {
          $image = self::$commonQas[$category]['imgurl'];
          $image = imagecreatefromjpeg($image);
          imagealphablending($image, true);
          if (self::$commonQas[$category]['default']) {
            foreach (self::$commonQas[$category]['default'] as $item) {
              if ($item['fontsizeone']) {
                if (mb_strlen($username) == 3) {
                  list($size, $angle, $x, $y, $abclength, $numlength, $chinalength) = explode(',', $item['fontsizeone']);
                } elseif (mb_strlen($username) == 6) {
                  list($size, $angle, $x, $y, $abclength, $numlength, $chinalength) = explode(',', $item['fontsizetwo']);
                } elseif (mb_strlen($username) == 9) {
                  list($size, $angle, $x, $y, $abclength, $numlength, $chinalength) = explode(',', $item['fontsizethree']);
                } elseif (mb_strlen($username) == 12) {
                  list($size, $angle, $x, $y, $abclength, $numlength, $chinalength) = explode(',', $item['fontsizefour']);
                }
              } else {
                list($size, $angle, $x, $y, $abclength, $numlength, $chinalength) = explode(',', $item['fontsize']);
              }
              if ($abclength && $numlength && $chinalength) {
                $length = $this->countlength($username, $abclength, $numlength, $chinalength);
                $x = $x - $length;
              }
              
              list($red, $green, $blue) = explode(',', $item['color']);
              $red = imagecolorallocate($image, $red, $green, $blue);
              //排版为竖
              if (self::$commonQas[$category]['format'] == 'isupright') {
                $newText = '';
                for ($i = 0; $i < $length; ++ $i ) {
                  $newText .= mb_substr($username, $i, 1, APP_DEFAULT_CHARACTER) . "\n\r";
                }
                imagettftext($image, $size, $angle, $x + $length, $y, $red, $item['font'], $newText);
              } else {
                imagettftext($image, $size, $angle, $x, $y, $red, $item['font'], $username);
              }
            }
          }
   
          if (self::$commonQas[$category]['time']) {
            foreach (self::$commonQas[$category]['time'] as $item) {
              list($size1, $angle1, $x1, $y1, $type) = explode(',',  $item['fontsize']);
              list($red1, $green1, $blue1) = explode(',', $item['color']);
              $red = imagecolorallocate($image, $red1, $green1, $blue1);
              if ($type == 1) {
                $time = date('Y.m.d', time());
              } else {
                $time = date('Y-m-d', time());
              }
              imagettftext($image, $size1, $angle1, $x1, $y1, $red, $item['font'], $time);
            }
          }
          if (self::$commonQas[$category]['like']) {
            foreach (self::$commonQas[$category]['like'] as $item) {
              list($size2, $angle2, $x2, $y2, $abclength, $numlength, $chinalength) = explode(',', $item['fontsize']);
              if ($abclength && $numlength && $chinalength) {
                $length = $this->countlength($username, $abclength, $numlength, $chinalength);
                $x = $x - $length;
              }
              list($red, $green, $blue) = explode(',', $item['color']);
              $red = imagecolorallocate($image, $red, $green, $blue);
              $newText = '';
              for( $i = 0; $i < $length; ++ $i )
              $newText .= mb_substr($item['topys'], $i, 1, APP_DEFAULT_CHARACTER) . "\n\r";
              // Add some shadow to the text
              imagettftext($image, $size2, $angle2, $x2, $y2, $red, $item['font'], $newText);
            }
          }

          if (self::$commonQas[$category]['text']) {
            foreach (self::$commonQas[$category]['text'] as $item) {
              list($size, $angle, $x, $y, $abclength, $numlength, $chinalength) = explode(',', $item['fontsize']);
              if ($abclength && $numlength && $chinalength) {
                $length = $this->countlength($username, $abclength, $numlength, $chinalength);
                $x = $x - $length;
              }
              $item['topys'] = str_replace('***', $username, $item['topys']);
              list($red, $green, $blue) = explode(',', $item['color']);
              $red = imagecolorallocate($image, $red, $green, $blue);
              imagettftext($image, $size, $angle, $x, $y, $red, $item['font'], $item['topys']);
            }
          }
          
          ob_clean();
          header('Content-type: image/jpeg');
          imagejpeg($image);
          imagedestroy($image);
        }
      } else {
        $this->title = self::$randTitles[$category]['default'];
        $data['catagory'] = $category;
        $data['imgurl'] = self::$listQas['zhuangbi'][$category]['imgurl2'];
        $this->render("zb2", $data);
      }
    } else {
      Yii::app()->runController('site/error');
    }
  }    

  //计算长度
  private function countlength($username, $abclength, $numlength, $chinalength){
    $re = array();
    $abc = 0;
    $num = 0;
    $china = 0;
    for ($i=0; $i < mb_strlen($username, APP_DEFAULT_CHARACTER); $i++){
      $re[] = mb_substr($username, $i, 1, APP_DEFAULT_CHARACTER); //将单个字符存到数组当中
      if (is_numeric($re[$i])){
        $num++;
      } elseif (preg_match("/^[a-zA-Z\s]+$/",$re[$i])){
        $abc++;
      } else {
        $china++;
      }
    }
    
    return $abc * $abclength + $num * $numlength + $china * $chinalength;
  } 
}
