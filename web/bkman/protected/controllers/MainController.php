<?php
/**
 * @desc iframe 框架首页
 */
class MainController extends BaseController {

  /**
   * @desc index
   */
  public function actionIndex () {
    //get admin user
    $bkAdminUser = $this->bkAdminUser;
    //管理权限验证
    $i = 0;
    $permissionMenuHtml = '';
    $leftMenu = $this->getBkAdminService()->getLeftMenu();
    foreach ($leftMenu as $menu) {
      if (!$this->isSystemAdmin && !in_array($menu['id'], $bkAdminUser['permission_ids'])) continue;
      $i++;
      $permissionMenuHtml .= '<dt><a href="javascript:void(0);">'.$menu['name'].'</a></dt>';
      if ($menu['sub']) {
        foreach ($menu['sub'] as $s_k => $subMenu) {
          if (!$subMenu['is_display']) continue;
          if (!$this->isSystemAdmin && !in_array($subMenu['id'], $bkAdminUser['permission_ids'])) continue;
          $permissionMenuHtml .= '<dd style="display: none;">';
          $permissionMenuHtml .= '<ul><li><a href="'.$this->getDeUrl($subMenu['uri_alias'] ? $subMenu['uri_alias'] : '#', array('id' => $subMenu['id'])).'" target="framecontent">'.$subMenu['name'].'</a></li></ul>';
          $permissionMenuHtml .= '</dd>';
        }
      }
    }
    if (!$permissionMenuHtml) {
      $permissionMenuHtml = '<dt class="unfold"><a href="javascript:void(0);">温馨提示</a></dt><dd style="display: block;"><ul><li><a href="'.$this->getDeUrl('main/error', array('id' => -403)).'" target="framecontent" class="select">管理权限点</a></li></ul></dd>';
    }

    $show['permission_menu_html'] = $permissionMenuHtml;
    $show['bk_admin_name'] = $bkAdminUser['admin_name'];
    $show['default_url'] = $this->getDeUrl('main/welcome', array('id' => 9999));
    $this->title = '管理后台';
    $this->render('index', $show);
  }

  public function actionWelcome () {
    $this->title = '欢迎回来';
    $show['content'] = '欢迎使用玩主管理后台，如遇到问题请联系管理员！<br><br>管理员：Dr.Vegapunk<br>电话：130 1895 2852';
    $this->render('welcome', $show);
  }

  /**
   * @desc 错误提示
   */
  public function actionError () {
    $id = $this->getSafeRequest('id', '');

    if ($id == -2) {
      $show['error'] = '你访问的用户不存在，<a href="javascript:history.go(-1);">请返回</a> 或 联系管理员！';
    } elseif ($id == -404) {
      $show['error'] = '你访问的页面不存在，<a href="javascript:history.go(-1);">请返回</a> 或 联系管理员！';
    } elseif ($id == -403) {
      $show['error'] = '你未开通当前管理权限，<a href="javascript:history.go(-1);">请返回</a> 或 联系管理员！';
    }
    $this->render('error', $show);
  }
}
