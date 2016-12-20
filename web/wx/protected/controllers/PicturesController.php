<?php
/**
 * @desc 我拍你画
 * @desc Chu 
 */
Yii::import('application.actions.pictures.*');
class PicturesController extends BaseController {
  
  public $defaultAction = 'index';
  
  public $layout = 'main_pictures';
  
  public $defaultURIDoAction = '';
 
  //过滤器（初始化）
  public function filters() {

    //BaseController filters
    parent::filters();
    //查询
    //$relationMaster = $this->getHougongService()->getHgRelationMasterByUidAndLevel($this->currentUser['uid']);
    //增
   /* $this->getHougongService()->addHgRelation (array(
      'uid' => $uid,
      'relation_uid' => $this->currentUser['uid'],
      'level' => self::SLAVE_LEVEL_ID
    )*/
  }

  /**
   * @desc Actions Map
   */
  public function actions () {
    return array(
      'list' => 'ListAction',
      'draw' => 'DrawAction',
      'homepage' => 'HomePageAction',
    );
  }
}