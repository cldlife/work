<?php
/**
 * @desc APP webview page
 */
class WebviewController extends CController {

  //帮助页
  public function actionHelp () {
    $this->render('help');
  }

  /**
   * @desc JSBridge webview 调试页
   */
  public function actionTestPage () {
    $headers = HttpClient::getAllHeaders();
    $fromapp = $headers['fromapp'];
    $sysversion = $headers['sysversion'];
    $accessToken = $headers['sid'];
    
    $data = array();
    $data['fromapp'] = $fromapp;
    $data['sysversion'] = $sysversion;
    $data['sid'] = $accessToken;
    $this->renderPartial('testpage', $data);
  }
}
?>