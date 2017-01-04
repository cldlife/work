<?php
/**
 * @desc Common DAO
 */
class CommonDAO extends BaseDao {

	public function findUid(){
		$stmt = $this->getBackendConnection()->prepare("SELECT * FROM bk_menus WHERE id = :id");
		$stmt->bindValue(':id', 1, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $rows;
	}
}