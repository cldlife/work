<?php
class SiteController extends BaseController {
  
  public $version = '';

  /**
   * Declares class-based actions.
   */
  public function actions() {}

  /**
   * This is the default 'index' action that is invoked
   * when an action is not explicitly requested by users.
   */
  public function actionIndex() {
    header('HTTP/1.1 302 Moved Temporarily');
    header('Location: ' . WEB_QW_APP_DOMAIN);
    exit();
  }

  /**
   * This is the action to handle external exceptions.
   */
  public function actionError() {
    $error = Yii::app()->errorHandler->error;
    $data['code'] = $error['code'] ? $error['code'] : 404;
    $this->outputJsonData($data['code']);
  }
  
  /**
   * @desc API wiki
   */
  public function actionWiki() {
    $version = $this->getSafeRequest('version');
    $this->layout = 'layout_wiki';
    $this->version = 'v' . $version;
    $viewTemplate = str_replace('.', '', $version);
    if ($viewTemplate) $this->render("/wiki/v{$viewTemplate}");
  }
  public function getApiUrl($path) {
    $apiDomain = WEB_QW_APP_API_DOMAIN . '/';
    return $apiDomain . $this->version . $path . ".json?version={$this->version}&client_id=1&client_secret=API_QW_WanZhu_client_secret&t=1438737842&network=china_mobile_3G";
  }
}