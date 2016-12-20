<?php
require_once APP_SRC_BASE_DIR . '/BaseConstant.php';
require_once APP_SRC_BASE_DIR . '/BaseService.php';
require_once APP_LIB_CACHE_DIR . '/FileCacheHandler.php';
require_once APP_SRC_DAO_DIR . '/DAOFactory.php';
require_once APP_SRC_BASE_DIR . '/GearmanFactory.php';
final class ServiceFactory {

  protected static $instance = null;

  protected static $services = array();

  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new ServiceFactory();
    }
    return self::$instance;
  }
  
  /**
   * @return CommonService
   */
  public function createCommonService() {
    if (!self::$services["CommonService"]) {
      require_once APP_SRC_SERVICE_DIR . '/CommonService.php';
      self::$services["CommonService"] = new CommonService();
    }
    return self::$services["CommonService"];
  }
  
  /**
   * @return AttachmentService
   */
  public function createAttachmentService() {
    if (!self::$services["AttachmentService"]) {
      require_once APP_SRC_SERVICE_DIR . '/AttachmentService.php';
      self::$services["AttachmentService"] = new AttachmentService();
    }
    return self::$services["AttachmentService"];
  }
  
  /**
   * @return 第3方Aliyun OSS SDK Service
   */
  public function createAliyunOssService() {
    if (!self::$services["AliyunOssService"]) {
      require_once APP_LIB_THIRD_SDK_DIR . '/aliyunoss/sdk.class.php';
      $AliyunOssService = new ALIOSS();
  
      //设置是否打开curl调试模式
      $AliyunOssService->set_debug_mode(FALSE);
  
      self::$services["AliyunOssService"] = $AliyunOssService;
    }
    return self::$services["AliyunOssService"];
  }
  
  /**
   * @return 第3方七牛云存储 SDK Service
   */
  public function createQiniuStorageService() {
    if (!self::$services["QiniuStorageService"]) {
      require_once APP_LIB_THIRD_SDK_DIR . '/Qiniu/sdk.class.php';
      $QiniuStorageService = new QiniuStorage();
  
      self::$services["QiniuStorageService"] = $QiniuStorageService;
    }
    return self::$services["QiniuStorageService"];
  }
  
  /**
   * @return 第3方云通讯 SDK Service
   */
  public function createYunTongxunService() {
    if (!self::$services["YunTongxun"]) {
      require_once APP_LIB_THIRD_SDK_DIR . '/yuntongxun/sdk.class.php';
      $YunTongxun = new YunTongxun();
  
      self::$services["YunTongxun"] = $YunTongxun;
    }
    return self::$services["YunTongxun"];
  }
  
  /**
   * @return BkAdminService
   */
  public function createBkAdminService() {
    if (!self::$services["BkAdminService"]) {
      require_once APP_SRC_SERVICE_DIR . '/BkAdminService.php';
      self::$services["BkAdminService"] = new BkAdminService();
    }
    return self::$services["BkAdminService"];
  }

  /**
   * @return UserService
   */
  public function createUserService() {
    if (!self::$services["UserService"]) {
      require_once APP_SRC_SERVICE_DIR . '/UserService.php';
      self::$services["UserService"] = new UserService();
    }
    return self::$services["UserService"];
  }
  
  /**
   * @return UserFortuneService
   */
  public function createUserFortuneService() {
    if (!self::$services["UserFortuneService"]) {
      require_once APP_SRC_SERVICE_DIR . '/UserFortuneService.php';
      self::$services["UserFortuneService"] = new UserFortuneService();
    }
    return self::$services["UserFortuneService"];
  }
  
  /**
   * @return UserMineService
   */
  public function createUserMineService() {
    if (!self::$services["UserMineService"]) {
      require_once APP_SRC_SERVICE_DIR . '/UserMineService.php';
      self::$services["UserMineService"] = new UserMineService();
    }
    return self::$services["UserMineService"];
  }
  
  /**
   * @return MessageService
   */
  public function createMessageService() {
    if (!self::$services["MessageService"]) {
      require_once APP_SRC_SERVICE_DIR . '/MessageService.php';
      self::$services["MessageService"] = new MessageService();
    }
    return self::$services["MessageService"];
  }
  
  /**
   * @return ThingService
   */
  public function createThingService () {
    if (!self::$services['ThingService']) {
      require_once APP_SRC_SERVICE_DIR . '/ThingService.php';
      self::$services['ThingService'] = new ThingService();
    }
    return self::$services['ThingService'];
  }
  
  /**
   * @return 第3方微博 SDK Service
   */
  public function createWeiboService() {
    if (!self::$services["WeiboService"]) {
      require_once APP_LIB_THIRD_SDK_DIR . '/weibo/sdk.class.php';
      $WeiboService = new Weibo();
  
      self::$services["WeiboService"] = $WeiboService;
    }
    return self::$services["WeiboService"];
  }

  /**
   * @return 第3方QQ SDK Service
   */
  public function createQQService() {
    if (!self::$services["QQService"]) {
      require_once APP_LIB_THIRD_SDK_DIR . '/qq/sdk.class.php';
      $QQService = new QQ();
  
      self::$services["QQService"] = $QQService;
    }
    return self::$services["QQService"];
  }

  /**
   * @return 第3方 SDK Service (connectQQ)
   */
  public function createConnectQQService() {
    if (!self::$services["ConnectQQService"]) {
      require_once APP_LIB_THIRD_SDK_DIR . '/qq/sdk.class.php';
      $QQService = new QQ(TRUE);
  
      self::$services["ConnectQQService"] = $QQService;
    }
    return self::$services["ConnectQQService"];
  }

  /**
   * @return 第3方 Wxpay Service
   */
  public function createWxpayService() {
    if (!self::$services["WxpayService"]) {
      require_once APP_LIB_THIRD_SDK_DIR . '/wxpay/sdk.class.php';
      $WxpayService = new Wxpay();

      self::$services["WxpayService"] = $WxpayService;
    }
    return self::$services["WxpayService"];
  }

  /**
   * @return 第3方 Qumi Service
   */
  public function createQumiService() {
    if (!self::$services["QumiService"]) {
      require_once APP_LIB_THIRD_SDK_DIR . '/qumi/sdk.class.php';
      $QumiService = new Qumi();

      self::$services["QumiService"] = $QumiService;
    }
    return self::$services["QumiService"];
  }
  
  /**
   * @return 第3方 Umengpush Service
   */
  public function createUmengpushService() {
    if (!self::$services["UmengpushService"]) {
      require_once APP_LIB_THIRD_SDK_DIR . '/umengpush/sdk.class.php';
      $UmengpushService = new Umengpush();
  
      self::$services["UmengpushService"] = $UmengpushService;
    }
    return self::$services["UmengpushService"];
  }

  /**
   * @return 第3方 Facepp Service
   */
  public function createFaceppService() {
    if (!self::$services["FaceppService"]) {
      require_once APP_LIB_THIRD_SDK_DIR . '/facepp/sdk.class.php';
      $FaceppService = new Facepp();
      self::$services["FaceppService"] = $FaceppService;
    }
    return self::$services["FaceppService"];
  }

  /**
   * @return 第3方 Weixin Service
   */
  public function createWeixinService() {
    if (!self::$services["WeixinService"]) {
      require_once APP_LIB_THIRD_SDK_DIR . '/weixin/sdk.class.php';
      $WeixinService = new Weixin();
      self::$services["WeixinService"] = $WeixinService;
    }
    return self::$services["WeixinService"];
  }
  
  /**
   * @return GearmanService
   */
  public function createGearmanService () {
    if (!self::$services['GearmanService']) {
      require_once APP_SRC_SERVICE_DIR . '/GearmanService.php';
      self::$services['GearmanService'] = new GearmanService();
    }
    return self::$services['GearmanService'];
  }

  /**
   * @return GameService
   */
  public function createGameService () {
    if (!self::$services['GameService']) {
      require_once APP_SRC_SERVICE_DIR . '/GameService.php';
      self::$services['GameService'] = new GameService();
    }
    return self::$services['GameService'];
  }
  
   /**
   * @return HougongService
   */
  public function createHougongService () {
    if (!self::$services['HougongService']) {
      require_once APP_SRC_SERVICE_DIR . '/HougongService.php';
      self::$services['HougongService'] = new HougongService();
    }
    return self::$services['HougongService'];
  }

  /**
   * @return TaskService
   */
  public function createWeigameService () {
    if (!self::$services['WeigameService']) {
      require_once APP_SRC_SERVICE_DIR . '/WeigameService.php';
      self::$services['WeigameService'] = new WeigameService();
    }
    return self::$services['WeigameService'];
  }
  
  /**
   * @return WebappService
   */
  public function createWebappService () {
    if (!self::$services['WebappService']) {
      require_once APP_SRC_SERVICE_DIR . '/WebappService.php';
      self::$services['WebappService'] = new WebappService();
    }
    return self::$services['WebappService'];
  }

  /**
   * @return 第3方融云 SDK Service
   */
  public function createRongCloudService() {
    if (!self::$services["RongCloudService"]) {
      require_once APP_LIB_THIRD_SDK_DIR . '/rongcloud/sdk.class.php';
      $RongCloudService = new RongCloudSdk();
      self::$services["RongCloudService"] = $RongCloudService;
    }
    return self::$services["RongCloudService"];
  }

  /**
   * @return TaskService
   */
  public function createTaskService () {
    if (!self::$services['TaskService']) {
      require_once APP_SRC_SERVICE_DIR . '/TaskService.php';
      self::$services['TaskService'] = new TaskService();
    }
    return self::$services['TaskService'];
  }
}
