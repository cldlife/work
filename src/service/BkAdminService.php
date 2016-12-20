<?php
/**
 * @desc 管理后台Service
 */
class BkAdminService extends BaseService {
  
  //缓存key前缀
  const FILE_CACHE_PREFIX = 'FILE_BK_ADMIN_CACHE_';
  
  //缓存版本号
  const FILE_CACHE_VERSION = '1.0';

  private function getCacheKey ($id) {
    return $this->getMd5CacheKey(self::FILE_CACHE_PREFIX . strtoupper($id) . '_' . self::FILE_CACHE_VERSION);
  }

  private function getBkAdminDAO () {
    return DAOFactory::getInstance()->createBkAdminDAO();
  }
  
  //后台管理用户uid
  private static $kbmanUids = array(1);
  public static function getBkmanUids () {
    return self::$kbmanUids;
  }

  /**
   * @desc 获取所有的权限点菜单
   */
  private function getAllPermissionMenus () {
    $cacheId = 'bk_admin_menus';
    $menus = $this->getFileCache()->get($cacheId);
    if (!$menus) {
      $menus = $this->getBkAdminDAO()->findBkAdminMenus();
      $this->getFileCache()->set($cacheId, $menus);
    }
    return $menus;
  }
  
  /**
   * @desc 左侧菜单(权限点)
   */
  public function getLeftMenu () {
    $leftMenus = array();
    $menus = $this->getAllPermissionMenus();
    if ($menus) {
      foreach ($menus as $menu) {
        if ($menu['parent_id']) {
          $leftMenus[$menu['parent_id']]['sub'][] = $menu;
        } else {
          $leftMenus[$menu['id']] = $menu;
        }
      }
    }
    return $leftMenus;
  }
  
  /**
   * @desc 获取权限点
   */
  public function getPermission ($id = 0) {
    $permission = array();
    $menus = $this->getAllPermissionMenus();
    foreach ($menus as $menu) {
      if ($menu['id'] == $id) {
        $permission = $menu; 
        break;
      }
    }
    return $permission;
  }
  
  /**
   * @desc 发表反馈
   */
  public function addFeedback($fields) {
    if (!$fields['uid'] || !$fields['content'] || !$fields['contact_info']) {
      throw new Exception('uid, content or contact_info is null...');
    }
    return $this->getBkAdminDAO()->insertFeedback($fields);
  }
  
  /**
   * @desc 获取反馈列表
   */
  public function getFeedbacks($status = -1, $page = 1, $pageSize = 10) {
    $list = array();
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $list = $this->getBkAdminDAO()->findFeedbacks($status, $offset, $pageSize);
    }
    return $list;
  }
  
  /**
   * @desc 举报
   */
  public function addReport($fields) {
    if (!$fields['uid'] || !$fields['report_id'] || !$fields['relation_id']) {
      throw new Exception('uid, report_id or relation_id is null...');
    }
    return $this->getBkAdminDAO()->insertReport($fields);
  }
  
  /**
   * @desc 获取我发的帖子列表
   */
  public function getReports($status = -1, $page = 1, $pageSize = 10) {
    $list = array();
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $list = $this->getBkAdminDAO()->findReports($status, $offset, $pageSize);
    }
    return $list;
  }


  /**
   * @desc 根据获取后台图片文件附件列表
   */
  public function getBkAttachments ($status = 0, $page = 1, $pageSize = 20) {
    $attachments = array();
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $attachments = $this->getBkAdminDAO()->findBkAttachments($status, $offset, $pageSize);
    }
    return $attachments;
  }

  /**
   * @desc 写入后台图片文件附件列表
   * @param array $fileInfo 文件信息
   */
  public function addBkAttachment (Array $fileInfo) {
    if ($fileInfo) {
      return $this->getBkAdminDAO()->insertBkAttachment($fileInfo);
    }
    return FALSE; 
  }
}
