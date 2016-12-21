<?php
require_once APP_CONFIG_THIRDSDK_DIR . '/facepp/config.inc.php';
require_once APP_LIB_THIRD_SDK_DIR . '/facepp/lib/facepp.config.php';
require_once APP_LIB_THIRD_SDK_DIR . '/facepp/lib/facepp.api.php';
require_once APP_LIB_THIRD_SDK_DIR . '/facepp/lib/facepp.setup.php';
require_once APP_LIB_THIRD_SDK_DIR . '/facepp/lib/facepp.result.php';

/**
 * @desc face++ sdk接口类
 */
final class Facepp {

  /**
   * @desc face++ 设置api key和api secret
   * @param string api key
   * @param string api secret
   * @param string api url (optional)
   * @return bool
   */
  public function setFaceppConfig ($apiKey, $apiSecret, $apiUrl = '') {
    return FaceppConfig::setFaceppConfig($apiKey, $apiSecret, $apiUrl);
  }

  /**
   * @desc face++ 人脸检测api
   * @param array 传入参数
   * string img url / post img file
   * @return array face_id,session_id,face...
   */
  public function detectFace ($params) {
    return FaceppApi::send('detect', $params);
  }

  /**
   * @desc face++ 检测人脸(face)的面部轮廓,五官位置
   * @param array 传入参数
   * string  face_id  由detect得到的face_id
   * @return array face_id,session_id,landmark...
   */
  public function landmarkFace ($params) {
    return FaceppApi::send('landmark', $params);
  }

  /**
   * @desc face++ 比较两个人脸的相似度
   * @param array 传入参数
   * string  face_id1  进行比较的两个人脸
   * string  face_id2
   * @return array similarity,component_similarity, session_id
   */
  public function compareFaces ($params) {
    return FaceppApi::send('compare', $params);
  }

  /**
   * @desc face++ 新建一个faceset
   * @param array 传入参数
   * 可选参数
   * string        faceset_name faceset的名称,没有则服务器自动生成
   * string/array  face_id      新建faceset同时添加进去的face_id(多个时为array)
   * string        tag          faceset的标签
   * @return array faceset_name,faceset_id,added_face,tag
   */
  public function createFaceset ($params = array()) {
    return FaceppApi::send('create_faceset', $params);
  }

  /**
   * @desc face++ 删除一个或多个faceset
   * @param array 传入参数
   * faceset_name 或 face_id
   * string 单个时
   * array  多个时
   * @return array deleted,success
   */
  public function deleteFaceset ($params) {
    return FaceppApi::send('delete_faceset', $params);
  }

  /**
   * @desc face++ 向faceset里添加face
   * @param array 传入参数
   * string        faceset_name 或 faceset_id
   * string/array  face_id      添加进faceset的face_id(多个时为array)
   * @return array added,success
   */
  public function addFaceToFaceset ($params) {
    return FaceppApi::send('add_face_set', $params);
  }

  /**
   * @desc face++ 从faceset里删除face
   * @param array 传入参数
   * string        faceset_name 或 faceset_id
   * string/array  face_id      添加进faceset的face_id(多个时为array)
   * @return array removed,success
   */
  public function removeFaceToFaceset ($params) {
    return FaceppApi::send('remove_face_set', $params);
  }

  /**
   * @desc face++ 设置faceset信息
   * @param array 传入参数
   * string        faceset_name 或 faceset_id
   * 可选
   * string  name 新的faceset name
   * string  tag  新的faceset tag
   * @return array faceset_name,faceset_id,tag
   */
  public function setFacesetInfo ($params) {
    return FaceppApi::send('set_faceset', $params);
  }

  /**
   * @desc face++ 获取faceset信息
   * @param array 传入参数
   * string   faceset_name 或 faceset_id
   * @return array faceset_name,faceset_id,tag,face
   */
  public function getFacesetInfo ($params) {
    return FaceppApi::send('get_faceset', $params);
  }

  /**
   * @desc face++ 获取图片检测信息,其中包含的face等信息
   * @param array 传入参数
   * string  img_id 图片的id
   * @return array img_id,url,face
   */
  public function getImage ($params) {
    return FaceppApi::send('get_image', $params);
  }

  /**
   * @desc face++ 获取face信息
   * @param array 传入参数
   * string/array 要查询face的face_id(多个时为array)
   * @return array face_id,attributes,img_id,url,faceset,person,position,tag
   */
  public function getFace ($params) {
    return FaceppApi::send('get_face', $params);
  }

  /**
   * @desc face++ 获取session的状态和结果
   * @param array 传入参数
   * string session_id  要查询session的id
   * @return array session_id,status,result(landmark or face)
   */
  public function getSession ($params) {
    return FaceppApi::send('get_session', $params);
  }

  /**
   * @desc face++ 获取当前face++的app信息
   * @param array 传入参数
   * @return array name,description
   */
  public function getApp ($params = array()) {
    return FaceppApi::send('get_app', $params);
  }
}

