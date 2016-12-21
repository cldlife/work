<?php
/**
 * @desc face++ api请求结果处理类
 */
class FaceppResult extends FaceppConfig {

  /**
   * @desc face++ 处理结果入口方法
   * @param string api map key
   * @param array api request results
   * @return array when success, bool when fail
   */
  public static function translate ($api, $results) {
    $method = 'translate_' . $api;
    if (method_exists(__CLASS__, $method) && $results) {
      if ($results['http_code'] != '200') {
        if (APP_DEBUG) throw new Exception('face++ api request failed:' . $results['response']['error']);
      } else {
        return self::$method($results['response']);
      }
    } else if (APP_DEBUG) {
      throw new Exception('sending request failed, please check it.');
    }
    return FALSE;
  }

  /**
   * @desc face++ detect api
   * @param array results
   * @return array
   * session_id string 相应请求的session标识符,可用于结果查询
   * img_id     string 相应请求的img标识符,可用于再次查询
   * face array :
   *    array face_id, attributes, position
   *    ......
   */
  private static function translate_detect ($results) {
    $resultsOut = array();
    if ($results['face'] && $results['face'][0]['face_id']) {
      $resultsOut['session_id'] = $results['session_id'];
      $resultsOut['face_id'] = $results['face'][0]['face_id'];
      $resultsOut['face'] = $results['face'];
    }
    return $resultsOut;
  }

  /**
   * @desc face++ landmark api
   * @param array results
   * @return array
   * session_id string 相应请求的session标识符,可用于结果查询
   * result array :
   *   array face_id, landmark
   *   .....
   */
  private static function translate_landmark ($results) {
    $resultsOut = array();
    if ($results['result'] && $results['result'][0]['landmark'] && $results['result'][0]['face_id']) {
      $resultsOut['session_id'] = $results['session_id'];
      $resultsOut['face_id'] = $results['result'][0]['face_id'];
      $resultsOut['landmark'] = $results['result'][0]['landmark'];
    }
    return $resultsOut;
  }

  /**
   * @desc face++ compare api
   * @param array results
   * @return array
   * session_id string 相应请求的session标识符,可用于结果查询
   * similarity float 整体相似度
   * component_similarity array 五官部分相似度:
   * 眼 eye,嘴 mouth,鼻子 nose,眉毛 eyebrow
   */
  private static function translate_compare ($results) {
    if (isset($results['similarity'])) {
      return $results;
    }
    return array();
  }

  /**
   * @desc face++ create faceset api
   * @param array results
   * @return array
   * added_face    int     成功加入的face数量
   * tag           string  Faceset相关的tag
   * faceset_name  string  相应Faceset的name
   * faceset_id    string  相应Faceset的id
   */
  private static function translate_create_faceset ($results) {
    if ($results['faceset_name'] && $results['faceset_id']) {
      return $results;
    }
    return array();
  }

  /**
   * @desc face++ delete faceset api
   * @param array results
   * @return array
   * deleted  int  成功删除的faceset数量
   * success  bool  表示操作是否成功
   */
  private static function translate_delete_faceset ($results) {
    if (isset($results['success'])) {
      return $results;
    }
    return array();
  }

  /**
   * @desc face++ add face to faceset api
   * @param array results
   * @return array
   * added    int   成功加入的face数量
   * success  bool  表示操作是否成功
   */
  private static function translate_add_face_set ($results) {
    if (isset($results['success'])) {
      return $results;
    }
    return array();
  }

  /**
   * @desc face++ compare api
   * @param array results
   * @return array
   * removed  int  成功删除的face数量
   * success  bool  表示操作是否成功
   */
  private static function translate_remove_face_set ($results) {
    if (isset($results['success'])) {
      return $results;
    }
    return array();
  }

  /**
   * @desc face++ compare api
   * @param array results
   * @return array
   * tag           string  Faceset相关的tag
   * faceset_name  string  相应Faceset的name
   * faceset_id    string  相应Faceset的id
   */
  private static function translate_set_faceset ($results) {
    if ($results['faceset_name'] && $results['faceset_id']) {
      return $results;
    }
    return array();
  }

  /**
   * @desc face++ compare api
   * @param array results
   * @return array
   * face          array   属于该faceset的face信息
   * tag           string  Faceset相关的tag
   * faceset_name  string  相应Faceset的name
   * faceset_id    string  相应Faceset的id
   */
  private static function translate_get_faceset ($results) {
    if ($results['faceset_name'] && $results['faceset_id']) {
      return $results;
    }
    return array();
  }

  /**
   * @desc face++ compare api
   * @param array results
   * @return array
   * img_id  string  Face++系统中的图片标识符,用于标识用户请求中的图片
   * url     string  请求中图片的url XXX 如果请求时用的是img会怎样?
   * face array :
   *    array face_id, position, tag
   *    .....
   */
  private static function translate_get_image ($results) {
    $resultsOut = array();
    if ($results['face'] && $results['face'][0]['face_id']) {
      $resultsOut['img_id'] = $results['img_id'];
      $resultsOut['face_id'] = $results['face'][0]['face_id'];
      $resultsOut['face'] = $results['face'];
      if ($results['url']) $resultsOut['url'] = $results['url'];
    }
    return $resultsOut;
  }

  /**
   * @desc face++ compare api
   * @param array results
   * @return array
   * face_info
   */
  private static function translate_get_face ($results) {
    if ($results['face_info']) {
      return $results['face_info'];
    }
    return array();
  }

  /**
   * @desc face++ compare api
   * @param array results
   * @return array
   * session_id  由/detection或/recognition中的API调用产生的session_id
   */
  private static function translate_get_session ($results) {
    if (!empty($results['results'])) {
      return $results;
    }
    return array();
  }

  /**
   * @desc face++ compare api
   * @param array results
   * @return array
   * name         string  App的名称信息
   * description  string  App的描述信息
   */
  private static function translate_get_app ($results) {
    if ($results['name']) {
      return $results;
    }
    return array();
  }
}
