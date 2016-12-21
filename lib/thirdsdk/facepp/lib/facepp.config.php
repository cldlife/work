<?php
/**
 * @desc face++ sdk接口类
 */
class FaceppConfig {

  //api_id & api_secret
  public static $FACEPP_API_KEY = FACEPP_API_KEY;
  public static $FACEPP_API_SECRET = FACEPP_API_SECRET;

  //face++ api接口基础url
  public static $apiBaseUrl = 'http://apicn.faceplusplus.com/v2';

  /**
   * @desc 设置face++ 的api key和api secret
   * @param string face++ api key
   * @param string face++ api secret
   * @return bool
   */
  public static function setFaceppConfig ($apiKey, $apiSecret, $apiUrl = '') {
    if ($apiKey && $apiSecret) {
      self::$FACEPP_API_KEY = $apiKey;
      self::$FACEPP_API_SECRET = $apiSecret;
      if ($apiUrl) self::$apiBaseUrl = $apiUrl;
      return TRUE;
    }
    return FALSE;
  }

  public static $apis = array(
    'detect' => array(
      'api' => '/detection/detect',
      'params' => array(
        'url' => '',
        'img' => '',
        'mode' => NULL,
        'attribute' => NULL,
        'tag' => NULL,
        'async' => FALSE,
      ),
    ),
    'landmark' => array(
      'api' => '/detection/landmark',
      'params' => array(
        'face_id' => '',
        'type' => NULL,
      ),
    ),
    'compare' => array(
      'api' => '/recognition/compare',
      'params' => array(
        'face_id1' => '',
        'face_id2' => '',
        'async' => FALSE,
      ),
    ),
    'create_faceset' => array(
      'api' => '/faceset/create',
      'params' => array(
        'faceset_name' => '',
        'face_id' => NULL,
        'tag' => NULL,
      ),
    ),
    'delete_faceset' => array(
      'api' => '/faceset/delete',
      'params' => array(
        'faceset_name' => '',
        'faceset_id' => '',
      ),
    ),
    'add_face_set' => array(
      'api' => '/faceset/add_face',
      'params' => array(
        'faceset_name' => '',
        'faceset_id' => '',
        'face_id' => '',
      ),
    ),
    'remove_face_set' => array(
      'api' => '/faceset/remove_face',
      'params' => array(
        'faceset_name' => '',
        'faceset_id' => '',
        'face_id' => '',
      ),
    ),
    'set_faceset' => array(
      'api' => '/faceset/set_info',
      'params' => array(
        'faceset_name' => '',
        'faceset_id' => '',
        'name' => NULL,
        'tag' => NULL,
      ),
    ),
    'get_faceset' => array(
      'api' => '/faceset/get_info',
      'params' => array(
        'faceset_name' => '',
        'faceset_id' => '',
      ),
    ),
    'get_image' => array(
      'api' => '/info/get_image',
      'params' => array(
        'img_id' => '',
      ),
    ),
    'get_face' => array(
      'api' => '/info/get_face',
      'params' => array(
        'face_id' => '',
      ),
    ),
    'get_session' => array(
      'api' => '/info/get_session',
      'params' => array(
        'session_id' => '',
      ),
    ),
    'get_app' => array(
      'api' => '/info/get_app',
      'params' => array(),
    ),
    //'' => '/train/verify',
    //'' => '/train/search',
    //'' => '/train/identify',
    //'' => '/recognition/verify',
    //'' => '/recognition/identify',
    //'' => '/recognition/search',
    //'' => '/grouping/grouping',
    //'' => '/person/create',
    //'' => '/person/delete',
    //'' => '/person/add_face',
    //'' => '/person/remove_face',
    //'' => '/person/set_info',
    //'' => '/person/get_info',
    //'' => '/group/create',
    //'' => '/group/delete',
    //'' => '/group/add_person',
    //'' => '/group/remove_person',
    //'' => '/group/set_info',
    //'' => '/group/get_info',
    //'' => '/info/get_person_list',
    //'' => '/info/get_faceset_list',
    //'' => '/info/get_group_list',
  );
}
