<?php
/**
 * @desc Base Service
 * @author VegaPunk
 */
class BaseService extends BaseConstant {
  
  protected $filecache = null;
  
  //官方客服账号uid
  const DEFAULT_OFFICIAL_UID = 3;
  
  /**
   * @desc 实例文件缓存类
   * @param $cacheDir 缓存目录
   */
  public function getFileCache ($cacheDir = APP_STORAGE_CACHE_DIR) {
    $key = md5($cacheDir);
    if (!$this->filecache[$key]) {
      $this->filecache[$key] = new FileCacheHandler($cacheDir);;
    }
    return $this->filecache[$key];
  }

  public function getMd5CacheKey ($cacheKey) {
    if ($cacheKey) {
      return md5($cacheKey);
    }
    return FALSE;
  }
  
  /**
   * 获得Memcache连接，所有继承自BaseService的Service,连接都只能从此获得
   * @return Memcache
   */
  public function getMemcache() {
    return MemcacheFactory::getInstance()->getMemcache(self::CACHE_NODE_WEBFRONT);
  }
  
  /**
   * @desc CommonService
   */
  public function getCommonService() {
    return ServiceFactory::getInstance()->createCommonService();
  }
  
  /**
   * @desc 附件中心 Service
   */
  public function getAttachmentService() {
    return ServiceFactory::getInstance()->createAttachmentService();
  }
  
  /**
   * @desc 云通讯 Service
   */
  public function getYunTongxunService() {
    return ServiceFactory::getInstance()->createYunTongxunService();
  }
  
  /**
   * @desc 管理后台 BkAdminService
   */
  public function getBkAdminService() {
    return ServiceFactory::getInstance()->createBkAdminService();
  }
  
  /**
   * @desc 用户 UserMineService
   */
  public function getUserMineService() {
    return ServiceFactory::getInstance()->createUserMineService();
  }

  /**
   * @desc 获取一个GearmanClient,通过它去提交任务
   */
  public function getGearmanService () {
    return ServiceFactory::getInstance()->createGearmanService();
  }
  
  /**
   * @desc 融云 RongCloudService
   */
  public function getRongCloudService() {
    return ServiceFactory::getInstance()->createRongCloudService();
  }
}
