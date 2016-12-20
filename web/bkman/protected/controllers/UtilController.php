<?php
/**
 * @desc Ajax 异步请求处理 Utils
 * @return JSON数据
 */
class UtilController extends BaseController {

  public function run ($action) {
    $method = $this->getURIDoAction($this, 1);
    $this->$method();
  }

  /**
   * @desc 获取开通城市列表
   */
  private function doCityInfo() {
    $siteCities = $this->getCommonService()->getAllSiteCities();
    $return['code'] = 1;
    $return['data'] = $siteCities;
    $this->outputJsonData($return);
  }

  /**
   * @desc 获取省份列表
   */
  private function doProvinces() {
    $provinces = $this->getCommonService()->getRegionsById();
    $return['code'] = 1;
    $return['data'] = $provinces;
    $this->outputJsonData($return);
  }

  /**
   * @desc 根据province_id获取cities
   */
  private function doRegions() {
    $regions = array();
    $id = $this->getSafeRequest('id', 0, 'GET', 'int');
    if ($id) {
      $regions = $this->getCommonService()->getRegionsById($id);
    }
    $return['code'] = 1;
    $return['data'] = $regions;
    $this->outputJsonData($return);
  }

  /**
   * @desc upload file
   * @return json data
   */
  private function doUpload () {
    $res = $this->getAttachmentService()->uploadAttach('Filedata');
    if ($res['code'] == 1 && $res['fileInfo']) {
      $return['status'] = 0;
      $return['imgUrl'] = WEB_QW_APP_DYNAMIC_FILE_DOMAIN . $res['fileInfo']['file_uri'] . $res['fileInfo']['file_name'] . APP_DYNAMIC_FILE_RULE_750;
    } else {
      $return['status'] = -1;
    }

    $this->outputJsonData($return);
  }
}
?>
