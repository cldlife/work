<?php
/**
 * @desc face++ api接口参数设置
 */
class FaceppSetup extends FaceppConfig {

  /**
   * @desc face++ api入口
   * @param string api map key
   * @param array api request param
   * @return array when success, bool when fail
   * 所有接口必须的参数
   * api_key     App的Face++ API Key
   * api_secret  APP的Face++ API Secret
   */
  public static function setup ($api, $paramsIn) {
    $method = 'setup_' . $api;
    $apiParams = self::$apis[$api]['params'] ? self::$apis[$api]['params'] : array();
    if ($apiParams && method_exists(__CLASS__, $method)) {
      $paramsOut = array();
      $paramsOut['api_key'] = self::$FACEPP_API_KEY;
      $paramsOut['api_secret'] = self::$FACEPP_API_SECRET;
      return self::$method($apiParams, $paramsIn, $paramsOut);
    }
    return FALSE;
  }

  /**
   * @desc face++ detect api
   * @param array api config params
   * @param array param input
   * @param array param output
   * @return array
   * url或img[POST] 待检测图片的URL 或者 通过POST方法上传的二进制数据,原始图片大小需要小于1M
   *可选
   * mode       检测模式可以是normal(默认)或者 oneface.在oneface模式中,检测器仅找出图片中最大的一张脸
   * attribute  可以是none或者由逗号分隔的属性列表.默认为gender,age,race,smiling. 目前支持的属性包括:gender,age,race,smiling,glass,pose
   * tag        可以为图片中检测出的每一张Face指定一个不包含^@,&=*'"等非法字符且不超过255字节的字符串作为tag, tag信息可以通过 /info/get_face 查询
   * async      如果置为true，该API将会以异步方式被调用;也就是立即返回一个session id,稍后可通过/info/get_session查询结果.默认值为false。
   */
  private static function setup_detect ($apiParams, $paramsIn, $paramsOut) {
    if ($paramsIn && ($paramsIn['url'] || ($paramsIn['img'] && file_exists($paramsIn['img'])))) {
      foreach ($apiParams as $key => $val) {
        if (is_array($paramsIn[$key])) {
          $paramsOut[$key] = implode(',', $paramsIn[$key]);
        } else if ($paramsIn[$key]) {
          $paramsOut[$key] = $paramsIn[$key];
        }
      }
      return $paramsOut;
    }
    return array();
  }

  /**
   * @desc face++ landmark api
   * @param array api config params
   * @param array param input
   * @param array param output
   * @return array
   * face_id  待检测人脸的face_id
   *可选
   * type     表示返回的关键点个数,目前支持83p或25p,默认为83p
   */
  private static function setup_landmark ($apiParams, $paramsIn, $paramsOut) {
    if ($paramsIn && $paramsIn['face_id']) {
      foreach ($apiParams as $key => $val) {
        if ($paramsIn[$key])  $paramsOut[$key] = $paramsIn[$key];
      }
      return $paramsOut;
    }
    return array();
  }

  /**
   * @desc face++ compare api
   * @param string api map key
   * @param array param input
   * @param array param output
   * @return array
   *  face_id1  第一个Face的face_id
   *  face_id2  第二个Face的face_id
   *可选
   *  async     如果置为true,该API将会以异步方式被调用;也就是立即返回一个session id,稍后可通过/info/get_session查询结果.默认值为false
   */
  private static function setup_compare ($apiParams, $paramsIn, $paramsOut) {
    if ($paramsIn && $paramsIn['face_id1'] && $paramsIn['face_id2']) {
      $paramsOut['face_id1'] = $paramsIn['face_id1'];
      $paramsOut['face_id2'] = $paramsIn['face_id2'];
      return $paramsOut;
    }
    return array();
  }

  /**
   * @desc face++ create faceset api
   * @param string api map key
   * @param array param input
   * @param array param output
   * @return array
   * 一个Faceset最多允许包含10000个Face,开发版最多允许创建5个Faceset。
   *可选
   *  faceset_name  Faceset的Name信息,必须在App中全局唯一.Name不能包含^@,&=*'"等非法字符,且长度不得超过255.Name也可以不指定,此时系统将产生一个随机的name
   *  face_id       一组用逗号分隔的face_id, 表示将这些Face加入到该Faceset中
   *  tag           Faceset相关的tag,不需要全局唯一,不能包含^@,&=*'"等非法字符,长度不能超过255
   */
  private static function setup_create_faceset ($apiParams, $paramsIn, $paramsOut) {
    if ($paramsIn) {
      foreach ($apiParams as $key => $val) {
        if (is_array($paramsIn[$key])) {
          $paramsOut[$key] = implode(',', $paramsIn[$key]);
        } else if ($paramsIn[$key]) {
          $paramsOut[$key] = $paramsIn[$key];
        }
      }
    }
    return $paramsOut;
  }

  /**
   * @desc face++ delete faceset api
   * @param string api map key
   * @param array param input
   * @param array param output
   * @return array
   * faceset_name/faceset_id 用逗号隔开的待删除的faceset id列表或者name列表
   */
  private static function setup_delete_faceset ($apiParams, $paramsIn, $paramsOut) {
    if ($paramsIn && ($paramsIn['faceset_name'] || $paramsIn['faceset_id'])) {
      foreach ($apiParams as $key => $val) {
        if (is_array($paramsIn[$key])) {
          $paramsOut[$key] = implode(',', $paramsIn[$key]);
        } else if ($paramsIn[$key]) {
          $paramsOut[$key] = $paramsIn[$key];
        }
      }
      return $paramsOut;
    }
    return array();
  }

  /**
   * @desc face++ add face to faceset api
   * @param string api map key
   * @param array param input
   * @param array param output
   * @return array
   *一个Faceset最多允许包含10000个Face
   * faceset_name 或 faceset_id  相应Faceset的name或者id
   * face_id  一组用逗号分隔的face_id,表示将这些Face加入到相应Faceset中
   */
  private static function setup_add_face_set ($apiParams, $paramsIn, $paramsOut) {
    if ($paramsIn && ($paramsIn['faceset_name'] || $paramsIn['faceset_id']) && $paramsIn['face_id']) {
      foreach ($apiParams as $key => $val) {
        if (is_array($paramsIn[$key])) {
          $paramsOut[$key] = implode(',', $paramsIn[$key]);
        } else if ($paramsIn[$key]) {
          $paramsOut[$key] = $paramsIn[$key];
        }
      }
      return $paramsOut;
    }
    return array();
  }

  /**
   * @desc face++ compare api
   * @param string api map key
   * @param array param input
   * @param array param output
   * @return array
   * faceset_name 或 faceset_id  相应faceset的name或者id
   * face_id  一组用逗号分隔的face_id列表,表示将这些face从该faceset中删除.开发者也可以指定face_id=all,表示删除该faceset所有相关Face
   */
  private static function setup_remove_face_set ($apiParams, $paramsIn, $paramsOut) {
    if ($paramsIn && ($paramsIn['faceset_name'] || $paramsIn['faceset_id']) && $paramsIn['face_id']) {
      foreach ($apiParams as $key => $val) {
        if (is_array($paramsIn[$key])) {
          $paramsOut[$key] = implode(',', $paramsIn[$key]);
        } else if ($paramsIn[$key]) {
          $paramsOut[$key] = $paramsIn[$key];
        }
      }
      return $paramsOut;
    }
    return array();
  }

  /**
   * @desc face++ compare api
   * @param string api map key
   * @param array param input
   * @param array param output
   * @return array
   *  faceset_id 或 faceset_name 相应faceset的id或name
   *可选
   *  name 新的name
   *  tag  新的tag
   */
  private static function setup_set_faceset ($apiParams, $paramsIn, $paramsOut) {
    if ($paramsIn && ($paramsIn['faceset_name'] || $paramsIn['faceset_id']) && ($paramsIn['name'] || $paramsIn['tag'])) {
      foreach ($apiParams as $key => $val) {
        if ($paramsIn[$key]) {
          $paramsOut[$key] = $paramsIn[$key];
        }
      }
      return $paramsOut;
    }
    return array();
  }

  /**
   * @desc face++ compare api
   * @param string api map key
   * @param array param input
   * @param array param output
   * @return array
   * faceset_id 或 faceset_name 相应faceset的id或name
   */
  private static function setup_get_faceset ($apiParams, $paramsIn, $paramsOut) {
    if ($paramsIn && ($paramsIn['faceset_name'] || $paramsIn['faceset_id'])) {
      foreach ($apiParams as $key => $val) {
        if ($paramsIn[$key]) {
          $paramsOut[$key] = $paramsIn[$key];
        }
      }
      return $paramsOut;
    }
    return array();
  }

  /**
   * @desc face++ compare api
   * @param string api map key
   * @param array param input
   * @param array param output
   * @return array
   * img_id 目标图片的img_id
   */
  private static function setup_get_image ($apiParams, $paramsIn, $paramsOut) {
    if ($paramsIn && $paramsIn['img_id']) {
      $paramsOut['img_id'] = $paramsIn['img_id'];
      return $paramsOut;
    }
    return array();
  }

  /**
   * @desc face++ compare api
   * @param string api map key
   * @param array param input
   * @param array param output
   * @return array
   * face_id  一组用逗号分割的face_id
   */
  private static function setup_get_face ($apiParams, $paramsIn, $paramsOut) {
    if ($paramsIn && $paramsIn['face_id']) {
      $paramsOut['face_id'] = is_array($paramsIn['face_id']) ? implode(',', $paramsIn['face_id']) : $paramsIn['face_id'];
      return $paramsOut;
    }
    return array();
  }

  /**
   * @desc face++ compare api
   * @param string api map key
   * @param array param input
   * @param array param output
   * @return array
   * 获取session相关状态和结果
   * 可能的status：INQUEUE(队列中), SUCC(成功) 和FAILED(失败)
   * 当status是SUCC时，返回结果中还包含session对应的结果
   * 所有session都将在计算完成72小时之后过期，并被自动清除。
   * status返回值为SUCC仅表示成功取得运行结果，实际任务成功与否请根据result内容判断
   *
   * session_id  由/detection或/recognition中的API调用产生的session_id
   */
  private static function setup_get_session ($apiParams, $paramsIn, $paramsOut) {
    if ($paramsIn && $paramsIn['session_id']) {
      $paramsOut['session_id'] = $paramsIn['session_id'];
      return $paramsOut;
    }
    return array();
  }

  /**
   * @desc face++ compare api
   * @param string api map key
   * @param array param input
   * @param array param output
   * @return array
   */
  private static function setup_get_app ($apiParams, $paramsIn, $paramsOut) {
    return $paramsOut;
  }
}
