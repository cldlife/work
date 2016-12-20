<?php
/**
 * @desc 后宫首页
 */
class IndexAction extends CAction {

  //标题
  private static $title = '全民后宫';

  /**
   * @desc init 
   */
  public function run () {
    $uid = $this->getController()->getSafeRequest('uid', 0, 'GET', 'int'); 
    $this->getController()->title = str_replace('***', '我', self::$title);  
    $this->getController()->render('index', $show);
  }

}