<?php
class SiteController extends BaseController {

  /**
   * Declares class-based actions.
   */
  public function actions () {}

  /**
   * This is the default 'index' action that is invoked
   * when an action is not explicitly requested by users.
   */
  public function actionIndex () {
    //默认PC版下载官网
    $redirect = WEB_QW_APP_DOMAIN;
    if (Utils::isFromMobile()) {
      $redirect = WEB_QW_APP_DOMAIN . '/d';
    }

    header('HTTP/1.1 302 Moved Temporarily');
    header('Location: ' . $redirect);
    exit();
  }

  /**
   * This is the action to handle external exceptions.
   */
  public function actionError ($retType = 'page') {
    //debug模式,返回json数据
    if (APP_DEBUG) $retType = 'json';

    $code = $this->getSafeRequest('code', 404);
    $error = Yii::app()->errorHandler->error;
    $error['code'] = $error['code'] ? $error['code'] : $code;

    $show['refererUrl'] = urldecode($this->getReferrerUrl());
    $show['message'] = $this->getErrorMessage($error['code']);
    $show['code'] = $error['code'];

    if ($retType == 'page') {
      $this->title = '您的访问出错了！';
      $this->renderPartial('error', $show);
    } else {
      echo json_encode($show);
    }
  }

  private function getErrorMessage ($errorCode) {
    $errorMapping = array(
      403 => '很抱歉，您没有权限执行当前的操作',
      404 => '很抱歉，您访问的页面不存在或已删除',
      500 => '您访问的页面存在异常或已失效，请选择下面操作：'
    );

    if (isset($errorMapping[$errorCode])) {
      return $errorMapping[$errorCode];
    } else {
      return '对不起，你所请求的操作暂时无法响应，请选择下面操作：';
    }
  }
}
