<?php
require_once APP_CONFIG_THIRDSDK_DIR . '/Qiniu/config.inc.php';
require_once APP_LIB_THIRD_SDK_DIR . '/Qiniu/Config.php';
require_once APP_LIB_THIRD_SDK_DIR . '/Qiniu/functions.php';
require_once APP_LIB_THIRD_SDK_DIR . '/Qiniu/Http/Client.php';
require_once APP_LIB_THIRD_SDK_DIR . '/Qiniu/Http/Request.php';
require_once APP_LIB_THIRD_SDK_DIR . '/Qiniu/Http/Response.php';
require_once APP_LIB_THIRD_SDK_DIR . '/Qiniu/Http/Error.php';
require_once APP_LIB_THIRD_SDK_DIR . '/Qiniu/Auth.php';
require_once APP_LIB_THIRD_SDK_DIR . '/Qiniu/Storage/FormUploader.php';
require_once APP_LIB_THIRD_SDK_DIR . '/Qiniu/Storage/ResumeUploader.php';

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

/**
 * 七牛存储类
 */
final class QiniuStorage {
  
  const QINIU_ACCESS_KEY = QINIU_ACCESS_KEY;
  
  const QINIU_SECRET_KEY = QINIU_SECRET_KEY;
  
  const QINIU_BUCKET_NAME = QINIU_BUCKET_NAME;
  
  const QINIU_PERSISTENT_PIPELINE = QINIU_PERSISTENT_PIPELINE;
  
  private $auth = NULL;
  
  private $bucketMgr = NULL;
  
  private $uploadMgr = NULL;
  
  //fops配置
  private static $fops = array(
    'FOPS_TO_MP3' => array('ext' => '.mp3', 'encoded' => 'avthumb/mp3/ab/320k/ar/44100/acodec/libmp3lame')
  );
  
  //权限验证
  public function __construct() {
    if (!$this->auth) {
      $auth = new Auth(self::QINIU_ACCESS_KEY, self::QINIU_SECRET_KEY);
      $this->auth = $auth;
    }
  }
  
  //空间资源管理
  public function getBucketMgr() {
    if (!$this->bucketMgr) {
      require_once APP_LIB_THIRD_SDK_DIR . '/Qiniu/Storage/BucketManager.php';
      $bucketMgr = new BucketManager($this->auth);
      $this->bucketMgr = $bucketMgr;
    }
    
    return $this->bucketMgr;
  }
  
  //上传管理
  public function getUploadManager() {
    if (!$this->uploadMgr) {
      require_once APP_LIB_THIRD_SDK_DIR . '/Qiniu/Storage/UploadManager.php';
      $uploadMgr = new UploadManager();
      $this->uploadMgr = $uploadMgr;
    }
    
    return $this->uploadMgr;
  }

  /**
   * @desc 上传二进制流
   * @param string $fileName
   * @param stream $string
   * @param string $fops 
   */
  public function uploadStream ($fileName, $string, $fops = '') {
    if (!$fileName || !$string) {
      throw new Exception('File name or content string is null.');
    }
    
    $ret = array();
    try {
      $this->getUploadManager();
      
      //预转持续化策略
      $policy = null;
      if ($fops && self::$fops[$fops]) {
        $targetFileName = preg_replace('/\..*?$/i', self::$fops[$fops]['ext'], $fileName);//替换文件后缀
        $savekey = Qiniu\base64_urlSafeEncode(self::QINIU_BUCKET_NAME . ':' . $targetFileName);
        $policy = array(
          'persistentOps' => self::$fops[$fops]['encoded'] . '|saveas/' . $savekey,
          'persistentPipeline' => self::QINIU_PERSISTENT_PIPELINE
        );
      }
      
      //生成上传token
      $token = $this->auth->uploadToken(self::QINIU_BUCKET_NAME, null, 3600, $policy);
      list($ret, $err) = $this->uploadMgr->put($token, $fileName, $string);
      if ($err !== null) {
        throw new Exception('Error with ' . json_encode($err));
      } else {
        $ret['status'] = 200;
      }
    } catch (Exception $e) {
      Utils::log(__METHOD__ . ":: error: " . json_encode($e), 'QINIU');
    }
    
    return (object) $ret;
  }
  
  //上传文件
  public function uploadFile ($fileName, $filePath) {
    if (!$fileName || !$filePath) {
      throw new Exception('File name or path is null.');
    }
    
    $ret = array();
    try {
      $this->getUploadManager();
      
      //生成上传token
      $token = $this->auth->uploadToken(self::QINIU_BUCKET_NAME);
      list($ret, $err) = $this->uploadMgr->putFile($token, $fileName, $filePath);
      if ($err !== null) {
        throw new Exception('Error with ' . json_encode($err));
      } else {
        $ret['status'] = 200;
      }
    } catch (Exception $e) {
      Utils::log(__METHOD__ . ":: error: " . json_encode($e), 'QINIU');
    }
    return (object) $ret;
  }
}