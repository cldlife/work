<?php
/**
 * @desc Attachment Service
 */
class AttachmentService extends BaseService {

  const ERROR_CODE = 502;

  /** 上传文件类型,本地文件上传,网络文件上传,base64上传 */
  const LOCAL_FILE_TYPE = 0;
  const NET_FILE_TYPE = 1;
  const BASE64_FILE_TYPE = 2;

  const UPLOAD_ALIYUN = 1;
  const UPLOAD_QINIU = 2;

  private function getAttachmentDAO() {
    return DAOFactory::getInstance()->createAttachmentDAO();
  }
  
  /**
   * @desc 七牛云存储
   * @param string $file 相对路径文件
   * @param string $content 二进制文件流
   * @return object $response
   */
  private function uploadQiniuStorage($file, $content, $fops = '') {
    $QiniuStorageService = ServiceFactory::getInstance()->createQiniuStorageService();
    $response = $QiniuStorageService->uploadStream($file, $content, $fops);
    return $response;
  }
  
  /**
   * @desc Aliyun OSS
   * @param string $filePath 路径
   * @param string $fileName 文件名
   * @param string $content 二进制文件流
   * @return object $response
   */
  private function uploadAliyunOSS($filePath, $fileName, $content) {
    //实例AliyunOssService
    $aliyunOssService = ServiceFactory::getInstance()->createAliyunOssService();
  
    //创建目录
    $response = $aliyunOssService->create_object_dir(OSS_BUCKET_NAME, $filePath);
    if ($response->status == 200) {
      $response = $aliyunOssService->upload_file_by_content(OSS_BUCKET_NAME, $filePath . $fileName, array(
        'content' => $content,
        'length' => strlen($content),
      ));
    }
  
    return $response;
  }
  
  private function createAid() {
    return sprintf("%d%d%.0f", mt_rand(10, 20), mt_rand(10, 99), 1000 * microtime(true));
  }

  /**
   * @desc 根据$tid获取附件列表
   */
  public function getAttachmentsBytid ($tid, $attachHashId, $pageSize = 6) {
    $attachments = array();
    if ($tid && $attachHashId && $pageSize) {
      $attachments = $this->getAttachmentDAO()->findAttachmentsBytid($tid, $attachHashId, $pageSize);
    }
  
    return $attachments;
  }
  
  /**
   * @desc 获取图片列表
   */
  public function getThreadImages ($tid, $attachHashId) {
    $images = array();
    $attachments = $this->getAttachmentsBytid($tid, $attachHashId);
    if ($attachments) {
      foreach ($attachments as $attachment) {
        if (!$attachment['file_uri'] || !$attachment['file_name']) continue;
        $tmpItem = array();
        $tmpItem['aid'] = $attachment['aid'];
        $tmpItem['w'] = $attachment['width'];
        $tmpItem['h'] = $attachment['height'];
        
        //大.中.小图
        $tmpItem['b_url'] = WEB_QW_APP_DYNAMIC_FILE_DOMAIN . $attachment['file_uri'] . $attachment['file_name'] . APP_DYNAMIC_FILE_RULE_960;
        $tmpItem['m_url'] = WEB_QW_APP_DYNAMIC_FILE_DOMAIN . $attachment['file_uri'] . $attachment['file_name'] . APP_DYNAMIC_FILE_RULE_750x500;
        $tmpItem['m_url_sw'] = WEB_QW_APP_DYNAMIC_FILE_DOMAIN . $attachment['file_uri'] . $attachment['file_name'] . APP_DYNAMIC_FILE_RULE_750x300;
        $tmpItem['s_url'] = WEB_QW_APP_DYNAMIC_FILE_DOMAIN . $attachment['file_uri'] . $attachment['file_name'] . APP_DYNAMIC_FILE_RULE_300x200;
        
        //uri
        $tmpItem['uri'] = $attachment['file_uri'] . $attachment['file_name'];
        
        $images[] = $tmpItem;
        unset($tmpItem);
      }
  
      unset($attachments);
    }
  
    return $images;
  }
  
  /**
   * @desc 写入附件
   * @param array $fileInfo 文件信息
   */
  public function addAttachment ($attachHashId, Array $fileInfo) {
    if ($attachHashId) {
      return $this->getAttachmentDAO()->insertAttachment($attachHashId, $fileInfo);
    }
    return FALSE; 
  }
  
  /**
   * @desc 根据$aids批量更新附件
   */
  public function updateAttachmentsByAids($aids, $attachHashId, Array $fields) {
    if ($aids && $attachHashId) {
      return $this->getAttachmentDAO()->updateAttachmentsByAids($aids, $attachHashId, $fields);
    }
    return FALSE;
  }
  
  /**
   * @desc 根据$aid更新附件（单个）
   */
  public function updateAttachmentByAid($aid, $attachHashId, Array $fields) {
    if ($aid && $attachHashId) {
      return $this->getAttachmentDAO()->updateAttachmentByAid($aid, $attachHashId, $fields);
    }
    return FALSE;
  }
  
  /**
   * @desc 根据$tid批量删除附件 (更新为删除状态)
   */
  public function deleteAttachmentsByTid($tid, $attachHashId) {
    if ($tid && $attachHashId) {
      return $this->getAttachmentDAO()->updateAttachmentsByTid($tid, $attachHashId, array(
      	'status' => -1
      ));
    }
    return FALSE;
  }

  private function upload($filename,$content,$thirdStorageId){
    $aid = Utils::longIdGenerator();

    $fileUri = APP_FILE_UPLOADED_DIR . date('/ymd/');
    $fileName = sprintf("%s_%s_%s", date('hi'), $aid, md5($filename));
    $response = null;
    if ($thirdStorageId == 1) {
      $response = $this->uploadAliyunOSS($fileUri, $fileName, $content);
    } elseif ($thirdStorageId == 2) {
      $response = $this->uploadQiniuStorage($fileUri . $fileName, $content);
    }
    if ($response->status != 200) {
      $res['code'] = -4;
      return $res;
    }else{
      return TRUE;
    }
  }

  /**
   * @desc 根据文件类型获取文件内容
   * @param $file
   * @param $type 0文件名,1网络文件,2文件流
   * @return null|string
   * @throws Exception
   */
  private function getFileContentByType($file,$type){
    $content = NULL;
    if ($type == self::LOCAL_FILE_TYPE) {
      ob_start();
      readfile($file);
      $content['binary'] = ob_get_contents();
      $content['base64'] = "data:image/jpeg;base64," . base64_encode($content['binary']);
      ob_end_clean();
    } elseif ($type == self::NET_FILE_TYPE) {
      if (stripos($file, 'http://') !== FALSE || stripos($file, 'https://') !== FALSE) {
        ob_start();
        $this->hackThirdImage($file);
        $content['binary'] = ob_get_contents();
        $content['base64'] = "data:image/jpeg;base64," . base64_encode($content['binary']);
        ob_end_clean();
      } else {
        throw new Exception("[".__LINE__."]系统错误,请联系管理员", self::ERROR_CODE);
      }
    } elseif ($type == self::BASE64_FILE_TYPE) {
      if (preg_match("/data:(.*?);base64,/", $file)) {
        $content['base64'] = $file;
        $content['binary'] = base64_decode(preg_replace("/data:(.*?);base64,/", "", $file));
      } else {
        $content['base64'] = "data:image/jpeg;base64," . $file;
        $content['binary'] = base64_decode($file);
      }
    } else {
      throw new Exception("[".__LINE__."]系统错误,请联系管理员", self::ERROR_CODE);
    }
    return $content;
  }

  /**
   * @desc 根据文件类型获取文件名
   * @param $file
   * @param $type
   * @return mixed|null|string
   */
  public function getFilenameByType($file, $type){
    $filename = NULL;
    switch ($type) {
      case self::LOCAL_FILE_TYPE:
      case self::NET_FILE_TYPE:
        $filename = Utils::getFileName($file);
        break;
      case self::BASE64_FILE_TYPE:
        $filename = md5($file);
        break;
      default:
        $filename = Utils::getFileName($file);
        break;
    }
    return $filename;
  }

  /**
   * @desc 上传头像,指定path
   * @param $file
   * @param $type
   * @return array
   */
  public function uploadAvatar($file, $type){
    $uploadPath = APP_FILE_AVATAR_DIR . date('/ymd/');
    return $this->uploadImage($file, $type, self::UPLOAD_QINIU, $uploadPath);
  }

  /**
   * @desc 上传文件
   * @param $file
   * @param int $type 0文件名,1网络文件,2文件流
   * @param int $thirdStorageId
   * @param null $uploadPath
   * @return array
   * @throws Exception
   */
  public function uploadImage($file, $type = 0, $thirdStorageId = self::UPLOAD_QINIU, $uploadPath = null){
    $res = array();
    $res['code'] = 0;
    if (!$file) {
      $res['code'] = -1;
      return $res;
    }
    
    $filename = $this->getFilenameByType($file, $type);
    if (!$filename) {
      $res['code'] = -1;
      return $res;
    }
    
    $content = $this->getFileContentByType($file, $type);
    
    //验证并获取文件尺寸和类型 (TODO 图片和文件的判断)
    $image = @getimagesize($content['base64']);
    if (!$image || !$image[0] || !$image[1] || !$image['mime']) {
      $res['code'] = -3;
      return $res;
    }

    //通过内容上传文件
    $length = strlen($content['binary']);
    if ($length > 4) {
      //生成附件aid
      $aid = Utils::longIdGenerator();
      $fileUri = APP_FILE_UPLOADED_DIR . date('/ymd/');
      if($uploadPath){
        $fileUri = $uploadPath;
      }
      $uploadFileName = sprintf("%s_%s_%s", date('hi'), $aid, md5($filename));
      $response = null;
      if ($thirdStorageId == self::UPLOAD_ALIYUN) {
        $response = $this->uploadAliyunOSS($fileUri, $uploadFileName, $content['binary']);
      } elseif ($thirdStorageId == self::UPLOAD_QINIU) {
        $response = $this->uploadQiniuStorage($fileUri . $uploadFileName, $content['binary']);
      }
      if ($response->status != 200) {
        $res['code'] = -4;
        return $res;
      }
      $res['fileInfo']['aid'] = $aid;
      $res['fileInfo']['file_type'] = $image['mime'];
      $res['fileInfo']['file_uri'] = '/' . $fileUri;
      $res['fileInfo']['file_name'] = $uploadFileName;
      $res['fileInfo']['width'] = $image[0];
      $res['fileInfo']['height'] = $image[1];
      $res['code'] = 1;
    }
    return $res;
  }

  /**
   * @desc 上传图片 (第3方存储)(通过内容上传)
   * @param string $fileKey 图片http地址（或本地文件域file名称, 或文件路径）
   * @param int $uid 头像需要传入用户$uid
   * @param int $thirdStorageId 第3方存储: 1-Aliyun OSS, 2-七牛云存储
   */
  public function uploadImageBak($fileKey, $uid = 0, $thirdStorageId = 2) {
    $res = array();
    $res['code'] = 0;
    $fromLocal = FALSE;
  
    if (!$fileKey) {
      $res['code'] = -1;
      return $res;
    }
  
    //网络文件上传
    if (stripos($fileKey, 'http://') !== FALSE || stripos($fileKey, 'https://') !== FALSE) {
      $tmpFileName = $fileKey;
      $origFileName = Utils::getFileName($tmpFileName);
  
      //本地上传(文件域或文件路径上传)
    } else {
      $fromLocal = TRUE;
      $tmpFileName = $_FILES[$fileKey]['tmp_name'];
      if (is_uploaded_file($tmpFileName)) {
        $origFileName = $_FILES[$fileKey]['name'];
      } else {
        $tmpFileName = $fileKey;
        $origFileName = Utils::getFileName($tmpFileName);
      }
    }
  
    if (!$origFileName) {
      $res['code'] = -1;
      return $res;
    }
  
    //验证是否是允许的文件后缀
    $fileExt = Utils::getFileExt($origFileName);
    if (!$fileExt || !preg_match("/(".APP_ALLOW_IMAGE_TYPE.")/i", $fileExt)) {
      $res['code'] = -2;
      return $res;
    }
  
    ob_start();
    if ($fromLocal) {
      //读取文件的数据流
      readfile($tmpFileName);
    } else {
      //网络图片 TODO
      $this->hackThirdImage($tmpFileName);
    }
    $content = ob_get_contents();
    ob_end_clean();
  
    //验证并获取文件尺寸和类型 (TODO 图片和文件的判断)
    $image = @getimagesize('data://image/jpeg;base64,'. base64_encode($content));
    if (!$image || !$image[0] || !$image[1] || !$image['mime']) {
      $res['code'] = -3;
      return $res;
    }
  
    //通过内容上传文件
    $length = strlen($content);
    if ($length > 4) {
      //生成附件aid
      $aid = Utils::longIdGenerator();
  
      //头像path
      if ($uid) {
        $fileName = md5($uid . $aid . $origFileName);
        $fileUri = APP_FILE_AVATAR_DIR . '/' . Utils::getAvatarPath($uid) . '/';
      } else {
        $fileUri = APP_FILE_UPLOADED_DIR . date('/ymd/');
        $fileName = sprintf("%s_%s_%s", date('hi'), $aid, md5($origFileName));
      }
  
      if ($thirdStorageId == 1) {
        $response = $this->uploadAliyunOSS($fileUri, $fileName, $content);
      } elseif ($thirdStorageId == 2) {
        $response = $this->uploadQiniuStorage($fileUri . $fileName, $content);
      }
      if ($response->status != 200) {
        $res['code'] = -4;
        return $res;
      }
  
      $res['fileInfo']['aid'] = $aid;
      $res['fileInfo']['file_type'] = $image['mime'];
      $res['fileInfo']['file_uri'] = '/' . $fileUri;
      $res['fileInfo']['file_name'] = $fileName;
      $res['fileInfo']['width'] = $image[0];
      $res['fileInfo']['height'] = $image[1];
      $res['code'] = 1;
    }
  
    return $res;
  }
  
  /**
   * @desc 上传附件文件 (第3方存储)(通过内容上传)
   * @param string $fileKey 文件http地址（或本地文件域file名称, 或文件路径）
   * @param int $thirdStorageId 第3方存储: 1-Aliyun OSS, 2-七牛云存储
   */
  public function uploadAttach($fileKey, $thirdStorageId = 2, $fops = '') {
    $res = array();
    $res['code'] = 0;
  
    if (!$fileKey) {
      $res['code'] = -1;
      return $res;
    }
    
    //网络文件上传
    if (stripos($fileKey, 'http://') !== FALSE || stripos($fileKey, 'https://') !== FALSE) {
      $tmpFileName = $fileKey;
      $origFileName = Utils::getFileName($tmpFileName);
      
    //本地上传(文件域或文件路径上传)
    } else {
      $tmpFileName = $_FILES[$fileKey]['tmp_name'];
      if (is_uploaded_file($tmpFileName)) {
        $origFileName = $_FILES[$fileKey]['name'];
      } else {
        $tmpFileName = $fileKey;
        $origFileName = Utils::getFileName($tmpFileName);
      }
    }
    
    if (!$origFileName) {
      $res['code'] = -1;
      return $res;
    }
  
    //验证是否是允许的文件后缀
    $fileExt = Utils::getFileExt($origFileName);
    if (!$fileExt || !preg_match("/(".APP_ALLOW_FILE_TYPE.")/i", $fileExt)) {
      $res['code'] = -2;
      return $res;
    }

    //读取文件的数据流
    ob_start();
    readfile($tmpFileName);
    $content = ob_get_contents();
    ob_end_clean();
    
    //通过内容上传文件
    $length = strlen($content);
    if ($length > 4) {
      //生成附件aid
      $aid = Utils::longIdGenerator();
      $fileUri = APP_FILE_UPLOADED_DIR . "/{$fileExt}/" . date('ymd') . "/";
      $fileName = sprintf("%s_%s_%s", date('Hi'), $aid, md5($origFileName)) . ".{$fileExt}";
      
      if ($thirdStorageId == 1) {
        $response = $this->uploadAliyunOSS($fileUri, $fileName, $content);
      } elseif ($thirdStorageId == 2) {
        $response = $this->uploadQiniuStorage($fileUri . $fileName, $content, $fops);
      }
      if ($response->status != 200) {
        $res['code'] = -4;
        return $res;
      }
  
      $res['fileInfo']['aid'] = $aid;
      $res['fileInfo']['file_type'] = $fileExt;
      $res['fileInfo']['file_uri'] = '/' . $fileUri;
      $res['fileInfo']['file_name'] = $fileName;
      $res['fileInfo']['ori_name'] = $origFileName;
      $res['code'] = 1;
    }
  
    return $res;
  }

  /**
   * @desc 读取文件的数据流(并破解图片防盗链)
   * @param string $targetUrl 目标图片链接
   * @param string $refererUrl 指定的referer url
   */
  private static $refererWhiteList = array("wx.qlogo.cn" => "wx.qq.com");
  public function hackThirdImage ($targetUrl, $refererUrl = '') {
    $parseUrl = parse_url($targetUrl);
    if (!$refererUrl) $refererUrl = $targetUrl;
    
    //referer白名单(在白名单中的图片用白名单referer)
    foreach (self::$refererWhiteList as $key => $referer) {
      if (stripos($parseUrl['host'], $key) !== FALSE) {
        $refererUrl = $referer;
        break;
      }
    }
    
    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'header' => "Host: {$parseUrl['host']}\r\n".
        "Referer: {$refererUrl}\r\n".
        "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36\r\n".
        "Accept: image/jpeg,image/png,image/*;q=0.8,*/*;q=0.5"
      )
    );
    $context = stream_context_create($opts);
    $content = readfile($targetUrl);
    return $content;
  }
}
