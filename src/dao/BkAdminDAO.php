<?php
/**
 * @desc 管理后台 DAO
 */
class BkAdminDAO extends BaseDao {

	/**
	 * bk_menus
	 */
	public function findBkAdminMenus() {
		$stmt = $this->getBackendConnection ()->prepare ( "SELECT * FROM bk_menus ORDER BY id ASC" );
		$stmt->execute ();
		$rows = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		return $rows ? $rows : array ();
	}

	/**
	 * find bk_user
	 */
	public function findBkAdminUserByUid($uid) {
		if (! $uid) {
			throw new Exception ( 'uid is null...' );
		}

		$stmt = $this->getBackendConnection ()->prepare ( "SELECT * FROM bk_user WHERE uid = :uid" );
		$stmt->bindValue ( ':uid', $uid, PDO::PARAM_INT );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ? $row : array ();
	}

	/**
	 * find bk_user_permissions
	 */
	public function findBkAdminUserPermissionByUid($uid) {
		if (! $uid) {
			throw new Exception ( 'uid is null...' );
		}
		$stmt = $this->getBackendConnection ()->prepare ( "SELECT * FROM bk_user_permissions WHERE uid = :uid" );
		$stmt->bindValue ( ':uid', $uid, PDO::PARAM_INT );
		$stmt->execute ();
		$rows = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		return $rows ? $rows : array ();
	}

	/**
	 * find bk_user
	 */
	public function findBkAdminUserByUserNameAndPasswd($username, $passwd) {
		$stmt = $this->getBackendConnection ()->prepare ( "SELECT * FROM bk_user WHERE username = :username AND passwd = :passwd" );
		$stmt->bindValue ( ':username', $username, PDO::PARAM_STR );
		$stmt->bindValue ( ':passwd', $passwd, PDO::PARAM_STR );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_ASSOC );
		return $row ? $row : array ();
	}

	/**
	 * update bk_user
	 */
	public function updateBkAdminUser($uid, $fields) {
		if (! $uid || ! $fields) {
			throw new Exception ( 'uid is null...' );
		}
		$updateFields = array ();
		if ($fields ['last_login_time'])
			$updateFields ['last_login_time'] = $fields ['last_login_time'];
		if ($fields ['updated_time'])
			$updateFields ['updated_time'] = $fields ['updated_time'];
		if ($updateFields) {
			$stmt = $this->getBackendConnection ()->prepare ( "UPDATE bk_user {$this->getUpdateSect($updateFields)} WHERE uid = :uid" );
			$stmt->bindValue ( ':uid', $uid, PDO::PARAM_INT );
			$this->bindValues ( $stmt, $updateFields );
			$stmt->execute ();
			$rowCount = $stmt->rowCount ();
			if ($rowCount) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * find bk_user
	 */
	public function findBkAdminUsers($offset = 0, $limit = 20) {
		if (! $limit) {
			throw new Exception ( 'limit is null...' );
		}
		$stmt = $this->getBackendConnection ()->prepare ( "SELECT * FROM bk_user LIMIT :offset, :limit" );
		$stmt->bindValue ( ':offset', $offset, PDO::PARAM_INT );
		$stmt->bindValue ( ':limit', $limit, PDO::PARAM_INT );
		$stmt->execute ();
		$rows = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		if (empty ( $rows ))
			$rows = array ();
		return $rows;
	}

	/**
	 * insert bk_user
	 */
	public function insertBkAdminUser($fields) {
		if (! $fields ['username'] || ! $fields ['admin_name'] || ! $fields ['passwd']) {
			throw new Exception ( 'username,admin_name,passwdis null...' );
		}
		$res = array ();
		$insertFields = array ();
		$insertFields ['username'] = $fields ['username'];
		$insertFields ['passwd'] = $fields ['passwd'];
		$insertFields ['admin_name'] = $fields ['admin_name'];
		$res = $this->insert ( $this->getBackendConnection (), 'bk_user', $insertFields );
		return $res;
	}

	/**
	 * insert bk_user_permissions
	 */
	public function insertBkAdminUserPermission($fields) {
		if (! $fields ['uid'] || ! $fields ['permission_id']) {
			throw new Exception ( 'uid or permission_id is null...' );
		}
		$res = array ();
		$fields ['created_time'] = 'NONE';
		$fields ['updated_time'] = 'NONE';
		$res = $this->insert ( $this->getBackendConnection (), 'bk_user_permissions', $fields );
		return $res;
	}

	/**
	 * delete user_permission
	 *
	 * @param $permission_ids=null, delelte
	 *        	all
	 */
	public function deleteBkAdminUserPermission($uid, $permission_ids) {
		if (! $uid) {
			throw new Exception ( 'uid is null...' );
		}
		$whereSQL = '';
		if ($permission_ids) {
			$whereSQL = " AND permission_id IN ({$permission_ids})";
		}
		$stmt = $this->getBackendConnection ()->prepare ( "DELETE FROM bk_user_permissions WHERE uid = :uid {$whereSQL}" );
		$stmt->bindValue ( ':uid', $uid, PDO::PARAM_INT );
		$stmt->execute ();
		$rowCount = $stmt->rowCount ();
		if ($rowCount) {
			return TRUE;
		}
		return FALSE;
	}
}
