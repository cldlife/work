<?php
/**
 * @desc WebappService
 */
class WebappService extends BaseService {

  private function  getWebappDAO () {
    return DAOFactory::getInstance()->createWebappDAO();
  }

  /**
   * @desc 查询七宗罪信息
   */
  public function getSeven ($nickname) {
    if ($nickname) {
      $seven = $this->getWebappDAO()->findSeven($nickname);
    }
    return $seven;
  }

  /**
   * @desc 插入七宗罪信息
   */
  public function addSeven ($fields) {
    if ($fields) {
      $seven = $this->getWebappDAO()->insertSeven($fields);
    }
    return $seven;
  }
  
  /**
   * @desc 我拍你画 查询照片/画作列表
   * @desc Chu 
   */
  public function getPicturesPaintsByType ($type, $page = 1, $pageSize = 30, $rppid) {
    $paintsList = array();
    if (isset($type) && $page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $paintsList = $this->getWebappDAO()->findPaintsByType($type, $offset, $pageSize, $rppid);
    } 
    return $paintsList;
  }
  
  /**
   * @desc 我拍你画 查询单个照片/画作
   * @desc Chu
   */
  public function getPicturesPaintsByppid ($ppid) {
    return ($ppid) ? $this->getWebappDAO()->findPicturesPaintsByppid($ppid) : array();
  }

  /**
   * 上传自拍
   *
   * @param $picture
   * @param $uid
   * @return array|bool
   * @throws Exception
   */
  public function addPicture($picture,$uid){
    if ($picture) {
      $pic = $this->getWebappDAO()->insertPicture($picture,$uid);
      if(!$pic)
        return FALSE;
      if(!$this->getWebappDAO()->insertPictureStatus($pic['pp_id'],$pic['type'],$pic['created_time']))
        return FALSE;
      return $pic;
    }
    return false;
  }

  /**
   * 上传画作
   * 除添加实体和状态外,还要添加被画照片的画作数
   *
   * @param $picture
   * @param $uid
   * @param $rid
   * @return array|bool
   * @throws Exception
   */
  public function addDraw($picture,$uid,$rid){
    if ($picture) {
      $pic = $this->getWebappDAO()->insertDraw($picture,$uid,$rid);

      if(!$pic)
        return FALSE;
      if(!$this->getWebappDAO()->insertPictureStatus($pic['pp_id'],$pic['type'],$pic['created_time'])){
        return FALSE;
      }
      if(!$this->getWebappDAO()->inDecreasePaintsByPid($rid,$pic['type'],array(array('key' => 'paintings', 'value' => 1, 'in_de' => '+'))))
        return FALSE;
      return $pic;
    }
    return FALSE;
  }

  /**
   * 更新图片信息
   *
   * @param $pid
   * @param array $fields
   * @return array|bool
   * @throws Exception
   */
  public function updatePictureByPid($pid, Array $fields) {
    if ($pid) {
      return $this->getWebappDAO()->updatePictureByPid($pid, $fields);
    }
    return FALSE;
  }

  /**
   * 根据图片ID自增/减字段
   *
   * @param $rid
   * @param array $fields
   * @param $type
   * @return bool
   * @throws Exception
   */
  public function inDecreasePaintsByPid($rid,$type,Array $fields){
    return $this->getWebappDAO()->inDecreasePaintsByPid($rid,$type,$fields);
  }

  /**
   * 获取图片信息
   *
   * @param $rid
   * @return mixed
   */
  public function getPicturesPaintsByRppid($rid){
    return $this->getWebappDAO()->findPicturesPaintsByppid($rid);
  }

  /**
   * @desc 我拍你画 画作次数
   * @desc Chu
   */
  public function getPicturesPaintsStatusByPPid ($ppid) {
    return ($ppid) ? $this->getWebappDAO()->findPicturesPaintsStatusByPPid($ppid) : array();
  }

  /**
   * @desc 我拍你画 画作状态列表
   * @desc Chu
   */
  public function getPicturesPaintsStatusByType ($type, $status = '', $page = 1, $pageSize = 30) {
    $paintsStatusList = array();
    if (isset($type) && $page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $paintsStatusList = $this->getWebappDAO()->findPicturesPaintingStatusByType($type, $status, $offset, $pageSize);
    }
    return $paintsStatusList;
  }

}