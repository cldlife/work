<?php
/**
 * @desc 七宗罪
 * @author dong
 */
class SevenController extends BaseController {

  //标题
  private static $randTitles = array(
    'qizongzui' => array(
      'default' => '你的欲望爆表了吗？',
    )
  );
  
  //区间最大值
  private static $minContent = 5;
  private static $maxContent = 20;

  //二维码链接
  private static $ewmLink = '/img/qrcode/wanzhu_dl.png';

  public function actionIndex () {
    $nickname = $this->getSafeRequest('nickname');
    $nickname = urldecode($nickname);
    $this->title = self::$randTitles['qizongzui']['default'];
    $this->layout = "main_seven";
    
    $data = array();
    $seven = array();
    if ($nickname) $seven = $this->getWebappService()->getSeven(md5($nickname));
    if ($seven) {
      $content = explode('_', $seven['content']);
      $data['content'] = $content;
      $data['nickname'] = $nickname;
      $this->render('result', $data);
    } else {
      $this->render('start', $data);
    }
  }
  
  /**
   * @desc 数据生成页 (Ajax异步)
   */
  public function actionSin () {
    $nickname = $this->getSafeRequest('nickname', '', 'POST', 'string');
    if ($nickname) {
      $seven = $this->getWebappService()->getSeven(md5($nickname));
      if (!$seven) {
        $content = array();
        for ($i=0; $i < 7; $i++) {
          $num = rand(0, self::$minContent);
          if($i>=4) $num = rand(0, self::$maxContent);
          $content[$i] = $num;
        }
        shuffle($content);
        $this->getWebappService()->addSeven(array(
          'name' => md5($nickname),
          'content' => implode('_', $content)
        ));
      }
      $this->outputJsonData(1, array('nickname' => urlencode($nickname)));
    }
    $this->outputJsonData(0);
  }
 
  /**
   * @desc 分享截图页
   */
  public function actionScreenshot () {
    $nickname = $this->getSafeRequest('nickname', '', 'GET', 'string');
    $nickname = urldecode($nickname);
    $this->title = self::$randTitles['qizongzui']['default'];
    $this->layout = "main_seven";
    if ($nickname) { 
      $seven = $this->getWebappService()->getSeven(md5($nickname));
      if ($seven) {
        $content = explode('_', $seven['content']);
        $data['content'] = $content;
        $data['ewm'] = WEB_QW_APP_FILE_UI_URL . self::$ewmLink;
        $data['nickname'] = $nickname;
        $this->render('screenshot', $data);
      }
    }
  }
}
?>