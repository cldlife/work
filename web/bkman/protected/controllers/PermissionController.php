<?php
/**
 * @desc 后台管理权限验证
 * @return is_admin 管理级别(1-最高权限, 0-按权限点)
 */
class PermissionController extends BaseController {

	/**
	 * actions 主入口
	 */
	public function run($actionID = NULL) {
		parent::filters ();
		$this->defaultURIDoAction = 'login';
		$method = $this->getURIDoAction ( $this );
		$this->$method ();
	}

	/**
	 * 错误处理回调方法
	 */
	public function errorRedirect() {
		$this->redirect ( $this->getDeUrl ( 'main/error', array (
				'id' => - 404
		) ) );
	}

	/**
	 * 登录
	 */
	private function doLogin() {
		$username = $this->getSafeRequest ( 'username', '', 'POST' );
		$passwd = $this->getSafeRequest ( 'passwd', '', 'POST' );

		if ($username && $passwd) {
			// 参数验证
			if (! $username || ! $passwd)
				$this->outputJsonData ( array (
						'code' => 1001
				) );
				// 验证是否有密码安全key
			if (! preg_match ( "/.+" . self::BKMAN_PASSWORD_KEY . "$/", $passwd ))
				$this->outputJsonData ( array (
						'code' => 1002
				) );

				// 验证用户权限
			$user = $this->getBkAdminService ()->getBkAdminUserByUserNameAndPasswd ( $username, md5 ( $passwd ) );
			$passwd = str_replace ( self::BKMAN_PASSWORD_KEY, '', $passwd );
			if (! $user ['uid'] || $user ['passwd'] != md5 ( $passwd )) {
				$this->outputJsonData ( array (
						'code' => 1002
				) );
			}

			// 验证是否是管理员
			$bkAdminUser = $this->getBkAdminService ()->getBkAdminUserByUid ( $user ['uid'] );

			// 登录成功, 验证管理权限
			if ($bkAdminUser ['uid']) {
				// 设置后台会话session（过期时间，2小时）
				$options = array (
						'expire' => 7200
				);
				$this->setCookie ( '_bk_admin_uid', $bkAdminUser ['uid'], $options );
				$this->setCookie ( '_bk_admin_accesskey',  $user ['passwd'] . self::BKMAN_PASSWORD_KEY , $options );

				// 更新最近登录时间
				$this->getBkAdminService ()->updateBkAdminUser ( $user ['uid'], array (
						'last_login_time' => $bkAdminUser ['updated_time'],
						'updated_time' => time ()
				) );

				$this->outputJsonData ( array (
						'code' => 1
				) );
			} else {
				$this->outputJsonData ( array (
						'code' => 1003
				) );
			}
		}

		$this->title = '登录 - 管理后台';
		$this->render ( 'login', $show );
	}

	/**
	 * 退出登录
	 */
	private function doLogout() {
		$this->deleteCookie ( '_bk_admin_uid' );
		$this->deleteCookie ( '_bk_admin_accesskey' );
		$this->redirect ( $this->getDeUrl ( 'permission/login' ) );
	}

	/**
	 * 添加管理员
	 */
	private function doNewuser() {
		$action = $this->getSafeRequest ( 'action' );
		// 添加
		if ($action == 'new') {
			$username = $this->getSafeRequest ( 'username', 0, 'POST', 'string' );
			$passwd = $this->getSafeRequest ( 'passwd', 0, 'POST', 'string' );
			$adminName = $this->getSafeRequest ( 'admin_name', '', 'POST', 'string' );

			// 参数验证
			if (! $username || ! $passwd || ! $adminName)
				$this->outputJsonData ( array (
						'code' => - 2
				) );
				// 验证用户权限
			$user = $this->getBkAdminService ()->getBkAdminUserByUserNameAndPasswd ( $username, $passwd );
			if ($user ['uid']) {
				$this->outputJsonData ( array (
							'code' => - 1
					) );
			} else {
				if ($this->getBkAdminService ()->newBkAdminUser ( array (
						'username' => $username,
						'passwd' => md5($passwd),
						'admin_name' => $adminName
				) )) {
					$this->outputJsonData ( array (
							'code' => 1
					) );
				}
				$this->outputJsonData ( array (
						'code' => 0
				) );
			}

			$this->outputJsonData ( array (
					'code' => - 2
			) );

			// 删除
		} elseif ($action == 'del') {
			$uid = $this->getSafeRequest ( 'uid', 0, 'POST', 'int' );

			// 验证管理员是否存在
			if ($this->getBkAdminService ()->getBkAdminUserByUid ( $uid )) {
				// 删除管理账号
				if ($this->getBkAdminService ()->deleteBkAdminUser ( $uid )) {
					$this->outputJsonData ( array (
							'code' => 1
					) );
				}
			} else {
				$this->outputJsonData ( array (
						'code' => 0
				) );
			}
		}

		// 管理员列表
		$adminUserList = $this->getBkAdminService ()->getBkAdminUsers ();

		$this->title = '管理员账号设置';
		$show = array ();
		$show ['adminUserList'] = $adminUserList;
		$this->render ( 'newuser', $show );
	}

	/**
	 * 管理员权限设置
	 */
	private function doSetuser() {
		$action = $this->getSafeRequest ( 'action' );
		$uid = $this->getSafeRequest ( 'uid', 0, 'GET', 'int' );
		// 获取管理员信息
		$adminUserInfo = $this->getBkAdminService ()->getBkAdminUserByUid ( $uid );
		// 保存设置
		if ($action == 'save') {
			$permissionIds = $this->getSafeRequest ( 'permission_ids', '', 'POST', 'array' );
			if (! $permissionIds)
				$permissionIds = array ();

				// 计算差值
			$delPermissionIds = array_diff ( $adminUserInfo ['permission_ids'], $permissionIds );

			if ($delPermissionIds) {
				$this->getBkAdminService ()->deleteBkAdminUserPermission ( $uid, $delPermissionIds );
			}

			$addPermissionIds = array_diff ( $permissionIds, $adminUserInfo ['permission_ids'] );
			if ($addPermissionIds) {
				foreach ( $addPermissionIds as $permissionId ) {
					$this->getBkAdminService()->saveBkAdminUserPermission ( array (
							'uid' => $uid,
							'permission_id' => $permissionId
					) );
				}
			}

			$this->outputJsonData ( array (
					'code' => 1
			) );
		}

		// 获取权限点
		$adminPermissionList = $this->getBkAdminService ()->getLeftMenu ();

		$this->title = '管理员权限设置';
		$show = array ();
		$show ['adminUserInfo'] = $adminUserInfo;
		$show ['adminPermissionList'] = $adminPermissionList;
		$show ['adminUserInfo'] = $adminUserInfo;
		$this->render ( 'setuser', $show );
	}

}
?>
