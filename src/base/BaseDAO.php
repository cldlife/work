<?php
/**
 * @desc Base DAO
 * @author dong
 */
class BaseDAO extends BaseConstant {

	// 获取 config DB 连接
	protected function getConfigConnection($slave = FALSE) {
		return $this->getConnection ( self::DB_S_CONFIG, $slave );
	}

	// 获取 backend DB 连接
	protected function getBackendConnection($slave = FALSE) {
		return $this->getConnection ( self::DB_S_BACKEND, $slave );
	}

	// 获取 backend DB 连接
	protected function getArticleConnection($slave = FALSE) {
		return $this->getConnection ( self::DB_S_ARTICLE, $slave );
	}

	/**
	 * 获得数据库连接，所有继承自BaseDAO的DAO,连接都只能从此获得
	 *
	 * @param $dbSymbol 数据库别名
	 * @return PDO
	 */
	protected function getConnection($dbSymbol, $slave = FALSE) {
		$dbMS = $slave ? self::DB_S_SLAVE : self::DB_S_MASTER;
		return ConnectionFactory::getInstance ()->getConnection ( $dbSymbol, $dbMS );
	}

	/**
	 * 获得Memcache连接，所有继承自BaseDAO的DAO,连接都只能从此获得
	 *
	 * @return Memcache
	 */
	// public function getMemcache() {
	// return MemcacheFactory::getInstance ()->getMemcache ( self::CACHE_NODE_WEBFRONT );
	// }
	// public function getMemcacheNameSpace($spaceName, $cacheTime) {
	// return MemcacheFactory::getInstance ()->createMemcacheNameSpace ( $spaceName, $cacheTime, self::CACHE_NODE_WEBFRONT );
	// }
	// public function getBkAdminMemcache() {
	// return MemcacheFactory::getInstance ()->getMemcache ( self::CACHE_NODE_BKADMIN );
	// }
	// public function getBkAdminMemcacheNameSpace($spaceName, $cacheTime) {
	// return MemcacheFactory::getInstance ()->createMemcacheNameSpace ( $spaceName, $cacheTime, self::CACHE_NODE_BKADMIN );
	// }
	// public function getWeigameMemcache() {
	// return MemcacheFactory::getInstance ()->getMemcache ( self::CACHE_NODE_WEIGAME );
	// }
	// public function getWeigameMemcacheNameSpace($spaceName, $cacheTime) {
	// return MemcacheFactory::getInstance ()->createMemcacheNameSpace ( $spaceName, $cacheTime, self::CACHE_NODE_WEIGAME );
	// }

	/**
	 * 获得更新语句中的set子句，如：set a=1,b='ket'
	 *
	 * @param Array $fields
	 *        	一个字段的map,每个条目由key和value组成。其中key为字段名，value为字段值
	 * @return String set子句
	 */
	public function getUpdateSect(Array $fields) {
		$updateSect = ' SET ';
		foreach ( array_keys ( $fields ) as $key ) {
			$updateSect .= '`' . $key . '` = :' . $key . ',';
		}
		return substr_replace ( $updateSect, '', - 1 );
	}

	/**
	 * 获得递增/递减更新语句中的set子句，如：set a=a+1,b=b-1
	 *
	 * @param Array $fields
	 *        	一个字段的map,每个条目由key和value组成。其中key为字段名，value为字段值
	 * @param Array $allowedFields
	 *        	允许的字段
	 * @return String set子句
	 */
	public function getInDecreaseUpdateSect(Array $fields, Array $allowedFields) {
		$updateSect = ' SET ';
		foreach ( $fields as $field ) {
			if (in_array ( $field ['key'], $allowedFields ) && $field ['in_de']) {
				if (! $field ['value'])
					$field ['value'] = 1;
				if ($field ['in_de'] == '+')
					$inDe = '+';
				if ($field ['in_de'] == '-')
					$inDe = '-';
				$updateSect .= "`{$field['key']}` = `{$field['key']}` {$inDe} {$field['value']} ,";
			}
		}

		return substr_replace ( $updateSect, '', - 1 );
	}

	/**
	 * 获得更新语句中的insert子句，如：(a,b,c) values(1,'ab','2007-01-01')
	 *
	 * @param Array $array
	 *        	一个字段的map,每个条目由key和value组成。其中key为字段名，value为字段值
	 * @return String insert子句
	 */
	public function getInsertClause(Array $data) {
		$fields = ' (';
		$values = ' VALUES (';
		foreach ( array_keys ( $data ) as $key ) {
			$fields = $fields . '`' . $key . '`,';
			$values = $values . ':' . $key . ',';
		}
		$fields = substr_replace ( $fields, ')', - 1 );
		$values = substr_replace ( $values, ')', - 1 );
		return $fields . $values;
	}

	/**
	 * insert
	 */
	public function insert($connection, $tableName, $data, $getLastId = FALSE) {
		$curTime = time ();
		if (! $data ['created_time'])
			$data ['created_time'] = $curTime;
		if (! $data ['updated_time'])
			$data ['updated_time'] = $curTime;
		if ($data ['created_time'] == 'NONE')
			unset ( $data ['created_time'] ); // 无字段
		if ($data ['updated_time'] == 'NONE')
			unset ( $data ['updated_time'] ); // 无字段
		$sql = 'INSERT INTO ' . $tableName . $this->getInsertClause ( $data );
		$stmt = $connection->prepare ( $sql );
		$this->bindValues ( $stmt, $data );
		$res = $stmt->execute ();
		if ($res) {
			$lastInsertId = 0;
			if ($getLastId)
				$lastInsertId = $connection->lastInsertId ();
			return $lastInsertId ? $lastInsertId : TRUE;
		}
		return FALSE;
	}

	/**
	 * bind value
	 */
	public function bindValues($stmt, $array) {
		foreach ( $array as $key => $value ) {
			$stmt->bindValue ( ':' . $key, $value, (is_numeric ( $value ) ? PDO::PARAM_INT : PDO::PARAM_STR) );
		}
	}

	/**
	 * DB Hash 分表算法 (bigint)
	 */
	public function getHashTableName($splitKey, $talbleName, $tableNum = self::HASH_TABLE_NUM) {
		if (! $splitKey) {
			die ( "Please input spilt key..." );
		}
		if (! $talbleName) {
			die ( "Please input table name..." );
		}
		return $talbleName . 1;
		// return $talbleName . (bcmod($splitKey, $tableNum) + 1);
	}

	/**
	 * DB Hash 分表算法 (string)
	 */
	private static $alphabets = array (
			'A' => 1,
			'B' => 2,
			'C' => 3,
			'D' => 4,
			'E' => 5,
			'F' => 6,
			'G' => 7,
			'H' => 8,
			'I' => 9,
			'J' => 10,
			'K' => 11,
			'L' => 12,
			'M' => 13,
			'N' => 14,
			'O' => 15,
			'P' => 16,
			'Q' => 17,
			'R' => 18,
			'S' => 19,
			'T' => 20,
			'U' => 21,
			'V' => 22,
			'W' => 23,
			'X' => 24,
			'Y' => 25,
			'Z' => 26
	);
	public function getStringHashTableName($splitKey, $talbleName, $tableNum = self::HASH_TABLE_NUM) {
		$splitKeyWithBigint = 0;
		if ($splitKey) {
			for($i = 0; $i < strlen ( $splitKey ); $i ++) {
				$k = strtoupper ( substr ( $splitKey, $i, 1 ) );
				if (self::$alphabets [$k]) {
					$splitKeyWithBigint += self::$alphabets [$k];
				} else {
					if (is_numeric ( $k ))
						$splitKeyWithBigint += $k;
				}
			}
		}

		return $this->getHashTableName ( $splitKeyWithBigint, $talbleName, $tableNum );
	}
}
