<?php
/**
 * @desc 设置管理
 */
class SettingController extends BaseController {

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
   * @desc 错误处理回调方法
   */
  public function errorRedirect () {
    $this->redirect($this->getDeUrl('main/error', array('id' => -404)));
  }

  /**
   * @desc 基本设置
   */
}
?>
