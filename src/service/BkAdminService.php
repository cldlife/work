<?php
/**
 * @desc 管理后台Service
 */
class BkAdminService extends BaseService {
	private function getBkAdminDAO() {
		return DAOFactory::getInstance ()->createBkAdminDAO ();
	}

	// 后台管理用户uid
	private static $kbmanUids = array (
			1
	);
	public static function getBkmanUids() {
		return self::$kbmanUids;
	}

	/**
	 * 获取所有的权限点菜单
	 */
	private function getAllPermissionMenus() {
		$menus = $this->getBkAdminDAO ()->findBkAdminMenus ();
		return $menus;
	}

	/**
	 * 左侧菜单(权限点)
	 */
	public function getLeftMenu() {
		$leftMenus = array ();
		$menus = $this->getAllPermissionMenus ();
		if ($menus) {
			foreach ( $menus as $menu ) {
				if ($menu ['parent_id']) {
					$leftMenus [$menu ['parent_id']] ['sub'] [] = $menu;
				} else {
					$leftMenus [$menu ['id']] = $menu;
				}
			}
		}
		return $leftMenus;
	}

	/**
	 * 获取权限点
	 */
	public function getPermission($id = 0) {
		$permission = array ();
		$menus = $this->getAllPermissionMenus ();
		foreach ( $menus as $menu ) {
			if ($menu ['id'] == $id) {
				$permission = $menu;
				break;
			}
		}
		return $permission;
	}

	/**
	 *
	 * @return 根据$uid获取管理后台用户&权限点
	 * @param bool $detail
	 *        	是否获取更详细的信息(包括app账号信息 & 管理员权限点集合)
	 */
	public function getBkAdminUserByUid($uid, $detail = TRUE) {
		if (! $uid) {
			throw new Exception ( 'uid is null...' );
		}
		$BkAdminUser = $this->getBkAdminDAO ()->findBkAdminUserByUid ( $uid );
		if ($detail && $BkAdminUser && $BkAdminUser ['uid']) {
			// $BkAdminUser['user_info'] = $this->getUserByUid($BkAdminUser['uid']);
			$BkAdminUser ['permission_ids'] = array ();
			$permissionIds = $this->getBkAdminDAO ()->findBkAdminUserPermissionByUid ( $BkAdminUser ['uid'] );
			foreach ( $permissionIds as $permissionId ) {
				$BkAdminUser ['permission_ids'] [] = $permissionId ['permission_id'];
			}
		}
		return $BkAdminUser;
	}

	/**
	 *
	 * @return 根据username,passwd获取管理后台用户&权限点
	 */
	public function getBkAdminUserByUserNameAndPasswd($username, $passwd) {
		$BkAdminUser = $this->getBkAdminDAO ()->findBkAdminUserByUserNameAndPasswd ( $username, $passwd );
		if ($BkAdminUser && $BkAdminUser ['uid']) {
			$BkAdminUser ['permission_ids'] = array ();
			$permissionIds = $this->getBkAdminDAO ()->findBkAdminUserPermissionByUid ( $BkAdminUser ['uid'] );
			foreach ( $permissionIds as $permissionId ) {
				$BkAdminUser ['permission_ids'] [] = $permissionId ['permission_id'];
			}
		}
		return $BkAdminUser;
	}

	/**
	 * update admin user
	 */
	public function updateBkAdminUser($uid, $fields) {
		if (! $uid || ! $fields) {
			throw new Exception ( 'uid is null...' );
		}
		return $this->getBkAdminDAO ()->updateBkAdminUser ( $uid, $fields );
	}

	/**
	 * 获取管理后台用户列表
	 */
	public function getBkAdminUsers($page = 1, $pageSize = 20) {
		$users = array ();
		if ($page && $pageSize) {
			$offset = ($page - 1) * $pageSize;
			$bkAdminUsers = $this->getBkAdminDAO ()->findBkAdminUsers ( $offset, $pageSize );
			if ($bkAdminUsers) {
				foreach ( $bkAdminUsers as $bkAdminUser ) {
					$bkAdminUser ['cdate'] = Utils::getDiffTime ( $bkAdminUser ['created_time'] );
					$bkAdminUser ['last_login_time'] = $bkAdminUser ['last_login_time'] ? Utils::getDiffTime ( $bkAdminUser ['last_login_time'] ) : '无记录';
					$users [] = $bkAdminUser;
				}
			}
		}

		return $users;
	}

	/**
	 * new admin user
	 */
	public function newBkAdminUser($fields) {
		if (! $fields) {
			throw new Exception ( 'fields is null...' );
		}
		return $this->getBkAdminDAO ()->insertBkAdminUser ( $fields );
	}
}
