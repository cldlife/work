<?php
class SiteController extends BaseController {

  /**
   * @desc Declares class-based actions.
   */
  public function actions () {
  }

  /**
   * @desc Default action is invoked
   */
  public function actionIndex () {
  }

  /**
   * @desc Handle external exceptions
   */
  public function actionError ($retType = 'page') {
    $code = $this->getSafeRequest('code', 404);
    $error = Yii::app()->errorHandler->error;
    $error['code'] = $error['code'] ? $error['code'] : $code;

    $show = array();
    $show['refererUrl'] = urldecode($this->getReferrerUrl());
    $show['message'] = $this->getErrorMessage($error['code']);
    $show['code'] = $error['code'];

    //debug模式,返回json数据
    if (APP_DEBUG) $retType = 'json';
    if ($retType == 'page') {
      $this->title = '您的访问出错了！';
      $this->renderPartial('error', $show);
    } else {
      echo json_encode($show);
    }
  }

  /**
   * @desc Handle Error Message
   * @param int $errorCode
   * @return string
   */
  private function getErrorMessage ($errorCode) {
    $errorMapping = array(
      403 => '很抱歉，您没有权限执行当前的操作',
      404 => '很抱歉，您访问的页面不存在或已删除',
      500 => '您访问的页面存在异常或已失效'
    );

    if (isset($errorMapping[$errorCode])) {
      return $errorMapping[$errorCode];
    } else {
      return '对不起，你所请求的网页链接暂时无法响应';
    }
  }
}
