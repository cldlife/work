<?php
/**
 * @desc GameDAO
 */
class GameDAO extends BaseDAO {

  //cache key prefix
  const CACHE_PREFIX = 'GAME_CACHE_';

  //cache version
  const CACHE_VERSION = '1.0';

  //cache namespace
  const GAME_SPACE_NAME = 'GAME_SPACE_NAME';

  //game scope keys
  const ROOM_SCOPE_PREFIX = 'SCOPE_ROOM_';
  const ROOM_USER_SCOPE_PREFIX = 'SCOPE_ROOM_USER_';
  const GAME_SPY_STATES_SCOPE_PREFIX = 'SCOPE_GAME_SPY_STATES_';
  const GAME_TGQCGM_TMS_SCOPE_PREFIX = 'SCOPE_GAME_TGQCGM_TMS_';
  const GAMESET_SPY_SCOPE_PREFIX = 'SCOPE_SPY_GAMESET_';
  const PSEUDO_USERS_SCOPE_PREFIX = 'SCOPE_PSEUDO_USERS_';

  private function getCacheKey($id) {
    return self::CACHE_PREFIX . strtoupper($id) . '_' . self::CACHE_VERSION;
  }

  private function getGameMemcacheNameSpace() {
    return $this->getMemcacheNameSpace(self::GAME_SPACE_NAME, self::CACHE_TIME);
  }

  //get game scope keys
  private function getRoomScopeKey () {
    return self::ROOM_SCOPE_PREFIX;
  }
  private function getRoomUserScopeKey ($id) {
    return self::ROOM_USER_SCOPE_PREFIX . strtoupper($id);
  }
  private function getGameSpyStatesScopeKey ($id) {
    return self::GAME_SPY_STATES_SCOPE_PREFIX . strtoupper($id);
  }
  private function getGameTgqcgmTmsScopeKey () {
    return self::GAME_TGQCGM_TMS_SCOPE_PREFIX;
  }
  private function getGamesetSpyScopeKey () {
    return self::GAMESET_SPY_SCOPE_PREFIX;
  }
  private function getPseudoUsersScopeKey () {
    return self::PSEUDO_USERS_SCOPE_PREFIX;
  }

  //get game table names
  private function getRoomTableName () {
    return 'room';
  }
  private function getRoomUserTableName () {
    return 'room_user';
  }
  private function getRoomNumberTableName () {
    return 'room_number';
  }
  private function getGameTableName () {
    return 'game';
  }
  private function getGameSpyStatesTableName () {
    return 'game_spy_states';
  }
  private function getGameTgqcgmTmsTableName () {
    return 'game_tgqcqm_tms';
  }
  private function getGameTgqcgmRightUsersTableName () {
    return 'game_tgqcqm_right_users';
  }
  private function getGameSpySetTableName () {
    return 'game_spy_setting';
  }
  private function getPseudoUsersTableName () {
    return 'pseudo_users';
  }

  /**
   * @desc select from room order by status
   * @param int $gid app game id
   * @return array
   */
  public function findRoomList ($gid, $offset = 0, $limit = 20) {
    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_GID_{$gid}_OFFSET_{$offset}_LIMIT_{$limit}");
    $scopeKey = $this->getRoomScopeKey();
    $rows = $this->getGameMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getRoomTableName()}` WHERE `status` > :status AND `gid` = :gid ORDER BY `status` DESC, `created_time` DESC LIMIT :offset, :limit");

      $stmt->bindValue(':status', ($gid) ? 1 : 0, PDO::PARAM_INT);
      $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $rows = $rows ? $rows : array();
      $this->getGameMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @desc select count(*) from room
   * update cache every 30 minutes
   * @return int
   */
  public function findActiveRoomCount () {
    $cacheKey = $this->getCacheKey(__FUNCTION__);
    $count = $this->getMemcache()->get($cacheKey);
    if ($count === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT COUNT(1) FROM `{$this->getRoomTableName()}` WHERE `status` > 0 LIMIT 1");
      $stmt->execute();
      $count = $stmt->fetchColumn();
      $count = $count ? $count : 0;
      $this->getMemcache()->set($cacheKey, $count, 0, self::FM_CACHE_TIME);
    }
    return $count;
  }

  /**
   * @desc select from room where id
   * @param int $id
   * @return array
   */
  public function findRoomWithId ($id) {
    if (!$id) {
      throw new Exception('room_id is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_ID_{$id}");
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getRoomTableName()}` WHERE `id` = :id LIMIT 1");
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $row = $row ? $row : array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc insert room
   * @param array $fields
   * @return int
   */
  public function insertRoom ($fields) {
    if (!$fields || !$fields['number'] || !$fields['host'] || !isset($fields['players']) || !$fields['status']) {
      throw new Exception('host, number, players or status is null');
    }

    $insertFields = array();
    $insertFields['number'] = $fields['number'];
    $insertFields['host'] = $fields['host'];
    $insertFields['players'] = $fields['players'];
    $insertFields['status'] = $fields['status'];
    if ($fields['gid']) $insertFields['gid'] = $fields['gid'];
    if ($fields['ticket']) $insertFields['ticket'] = $fields['ticket'];
    if ($fields['game_id']) $insertFields['game_id'] = $fields['game_id'];
    $id = $this->insert($this->getGameConnection(), $this->getRoomTableName(), $insertFields, TRUE);
    if ($id) {
      $scopeKey = $this->getRoomScopeKey();
      $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
      $cacheKey = $this->getCacheKey("findRoomWithId_ID_{$id}");
      $this->getMemcache()->delete($cacheKey);
      $cacheKey = $this->getCacheKey("findActiveRoomCount");
      $this->getMemcache()->delete($cacheKey);
      return $id;
    }
    return 0;
  }

  /**
   * @desc update room where id
   * @param int $id
   * @param array $fields
   * @return bool
   */
  public function updateRoomWithRoomId ($id, $fields) {
    if (!$id || !$fields) {
      throw new Exception('id or update fields is null');
    }

    $updateFields = array();
    if (isset($fields['gid'])) $updateFields['gid'] = $fields['gid'];
    if ($fields['number']) $updateFields['number'] = $fields['number'];
    if ($fields['game_id']) $updateFields['game_id'] = $fields['game_id'];
    if ($fields['host']) $updateFields['host'] = $fields['host'];
    if ($fields['players']) $updateFields['players'] = $fields['players'];
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($fields['ticket']) $updateFields['ticket'] = $fields['ticket'];
    if ($updateFields) {
      $updateFields['updated_time'] = time();
      $stmt = $this->getGameConnection()->prepare("UPDATE `{$this->getRoomTableName()}` {$this->getUpdateSect($updateFields)} WHERE `id` = :id LIMIT 1");
      $this->bindValues($stmt, $updateFields);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount()) {
        $scopeKey = $this->getRoomScopeKey();
        $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
        $cacheKey = $this->getCacheKey("findRoomWithId_ID_{$id}");
        $this->getMemcache()->delete($cacheKey);

        if ($fields['status'] == 0) {
          $cacheKey = $this->getCacheKey("findActiveRoomCount");
          $this->getMemcache()->delete($cacheKey);
        }
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @desc delete room where id
   * @param int $id
   * @return bool
   */
  public function deleteRoomWithRoomId ($id) {
    if (!$id) {
      throw new Exception('id is null');
    }

    $stmt = $this->getGameConnection()->prepare("DELETE FROM `{$this->getRoomTableName()}` WHERE `id` = :id LIMIT 1");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()) {
      $scopeKey = $this->getRoomScopeKey();
      $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
      $cacheKey = $this->getCacheKey("findRoomWithId_ID_{$id}");
      $this->getMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc update room set players where id
   * @param int $id
   * @param array $fields
   * @return bool
   */
  public function indeRoomPlayersWithRoomId ($id, $fields) {
    if (!$id || !$fields) {
      throw new Exception('id or fields is null');
    }

    $allowedFields = array('players');
    $indeUpdateSql = $this->getInDecreaseUpdateSect($fields, $allowedFields);
    if ($indeUpdateSql) {
      $stmt = $this->getGameConnection()->prepare("UPDATE `{$this->getRoomTableName()}` {$indeUpdateSql}, `updated_time` = :updated_time  WHERE `id` = :id LIMIT 1");
      $stmt->bindValue(':updated_time', time(), PDO::PARAM_INT);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount()) {
        $scopeKey = $this->getRoomScopeKey();
        $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
        $cacheKey = $this->getCacheKey("findRoomWithId_ID_{$id}");
        $this->getMemcache()->delete($cacheKey);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @desc select from room_user where room_id
   * @param int $id
   * @return array
   */
  public function findUserListWithRoomId ($roomId, $offset = 0, $limit = 30) {
    if (!$roomId) {
      throw new Exception('id is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_ROOMID_{$roomId}_OFFSET_{$offset}_LIMIT_{$limit}");
    $scopeKey = $this->getRoomUserScopeKey($roomId);
    $rows = $this->getGameMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getRoomUserTableName()}` WHERE `room_id` = :room_id ORDER BY `id` ASC LIMIT :offset, :limit ");
      $stmt->bindValue(':room_id', $roomId, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $rows = $rows ? $rows : array();
      $this->getGameMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @desc select from room_user where room_id
   * @param int $uid
   * @return int
   */
  public function findRoomIdWithUid ($uid) {
    if (!$uid) {
      throw new Exception('uid is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_UID_{$uid}");
    $roomId = $this->getMemcache()->get($cacheKey);
    if ($roomId === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT `room_id` FROM `{$this->getRoomUserTableName()}` WHERE `uid` = :uid ORDER BY `id` DESC LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $roomId = $stmt->fetchColumn();
      $roomId = $roomId ? $roomId : 0;
      $this->getMemcache()->set($cacheKey, $roomId, 0, self::CACHE_TIME);
    }
    return $roomId;
  }

  /**
   * @desc select room_user
   * @param int $room_id
   * @param int $uid
   * @return array
   */
  public function findRoomUserWithRoomidAndUid ($roomId, $uid) {
    if (!$roomId || !$uid) {
      throw new Exception('room id or uid is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_RID_{$roomId}_UID_{$uid}");
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getRoomUserTableName()}` WHERE `room_id` = :room_id AND `uid` = :uid LIMIT 1");
      $stmt->bindValue(':room_id', $roomId, PDO::PARAM_INT);
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $row = $row ? $row : array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc insert room_user
   * @param int $roomId
   * @param int $uid
   * @param bool $isRobot
   * @return bool
   */
  public function insertRoomUser ($roomId, $uid, $isRobot = FALSE) {
    if (!$roomId || !$uid) {
      throw new Exception('room_id or uid is null');
    }

    $insertFields = array();
    $insertFields['room_id'] = $roomId;
    $insertFields['uid'] = $uid;
    $insertFields['status'] = 1;
    if ($isRobot) $insertFields['is_robot'] = 1;
    $insertFields['created_time'] = 'NONE';
    $insertFields['updated_time'] = 'NONE';

    if ($this->insert($this->getGameConnection(), $this->getRoomUserTableName(), $insertFields)) {
      $scopeKey = $this->getRoomUserScopeKey($roomId);
      $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
      $cacheKey = $this->getCacheKey("findRoomIdWithUid_UID_{$uid}");
      $this->getMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc update room_user where room_id
   * @param int $roomId
   * @param int $uid
   * @param array $fields
   * @return bool
   */
  public function updateRoomUserWithRidAndUid ($roomId, $uid, $fields) {
    if (!$roomId || !$uid || !$fields) {
      throw new Exception('room_id, uid or fields is null');
    }

    $updateFields = array();
    if (isset($fields['is_robot'])) $updateFields['is_robot'] = $fields['is_robot'];
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($updateFields) {
      $stmt = $this->getGameConnection()->prepare("UPDATE `{$this->getRoomUserTableName()}` {$this->getUpdateSect($updateFields)} WHERE `room_id` = :room_id AND `uid` = :uid LIMIT 1");
      $this->bindValues($stmt, $updateFields);
      $stmt->bindValue(':room_id', $roomId, PDO::PARAM_INT);
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount()) {
        $cacheKey = $this->getCacheKey("findRoomUserWithRoomidAndUid_RID_{$roomId}_UID_{$uid}");
        $this->getMemcache()->delete($cacheKey);
        $scopeKey = $this->getRoomUserScopeKey($roomId);
        $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @desc delete room_user where room_id and uid
   * @param int $roomId
   * @param int $uid
   * @return bool
   */
  public function deleteRoomUser ($roomId, $uid) {
    if (!$roomId || !$uid) {
      throw new Exception('room_id or uid is null');
    }

    $stmt = $this->getGameConnection()->prepare("DELETE FROM `{$this->getRoomUserTableName()}` WHERE `room_id` = :room_id AND `uid` = :uid LIMIT 1");
    $stmt->bindValue(':room_id', $roomId, PDO::PARAM_INT);
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()) {
      $cacheKey = $this->getCacheKey("findRoomUserWithRoomidAndUid_RID_{$roomId}_UID_{$uid}");
      $this->getMemcache()->delete($cacheKey);
      $scopeKey = $this->getRoomUserScopeKey($roomId);
      $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
      $cacheKey = $this->getCacheKey("findRoomIdWithUid_UID_{$uid}");
      $this->getMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc select room_number where room_id = 0
   * @return int
   */
  public function findIdleRoomNumber () {
    $cacheKey = $this->getCacheKey(__FUNCTION__);
    $number = $this->getMemcache()->get($cacheKey);
    if ($number === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT `number` FROM `{$this->getRoomNumberTableName()}` WHERE `room_id` = 0 ORDER BY `number` LIMIT 1");
      $stmt->execute();
      $number = $stmt->fetchColumn();
      $number = $number ? $number : 0;
      $this->getMemcache()->set($cacheKey, $number, 0, self::CACHE_TIME);
    }
    return $number;
  }

  /**
   * @desc insert room_number
   * @param int $roomId
   * @return bool
   */
  public function insertRoomNumber ($roomId = 0) {
    $insertFields = array();
    $insertFields['room_id'] = $roomId ? $roomId : 0;
    $insertFields['created_time'] = 'NONE';
    $insertFields['updated_time'] = 'NONE';
    $number = $this->insert($this->getGameConnection(), $this->getRoomNumberTableName(), $insertFields, TRUE);
    if ($number) {
      $cacheKey = $this->getCacheKey('findIdleRoomNumber');
      $this->getMemcache()->delete($cacheKey);
      return $number;
    }
    return 0;
  }

  /**
   * @desc update room_number where number
   * @param int $number
   * @param int $roomId
   * @return bool
   */
  public function updateRoomIdWithNumber ($number, $roomId = 0) {
    if (!$number) {
      throw new Exception('number is null');
    }
    $updateFields = array();
    $updateFields['room_id'] = $roomId ? $roomId : 0;
    $stmt = $this->getGameConnection()->prepare("UPDATE `{$this->getRoomNumberTableName()}` {$this->getUpdateSect($updateFields)} WHERE `number` = :number LIMIT 1");
    $this->bindValues($stmt, $updateFields);
    $stmt->bindValue(':number', $number, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()) {
      $cacheKey = $this->getCacheKey('findIdleRoomNumber');
      $this->getMemcache()->delete($cacheKey);
      return $number;
    }
    return FALSE;
  }

  /**
   * @desc select from game where id
   * @param int $id
   * @return array
   */
  public function findGameWithId ($id) {
    if (!$id) {
      throw new Exception('id is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_ID_{$id}");
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getGameTableName()}` WHERE `id` = :id LIMIT 1");
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $row = $row ? $row : array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc insert game
   * @param array $fields
   * @return int
   */
  public function insertGame ($fields) {
    if (!$fields || !$fields['room_id'] || !$fields['status'] || !$fields['info'] || !$fields['type']) {
      throw new Exception('room_id, status, info or type is null');
    }

    $insertFields = array();
    $insertFields['room_id'] = $fields['room_id'];
    $insertFields['status'] = $fields['status'];
    $insertFields['info'] = $fields['info'];
    $insertFields['type'] = $fields['type'];
    $id = $this->insert($this->getGameConnection(), $this->getGameTableName(), $insertFields, TRUE);
    if ($id) {
      $cacheKey = $this->getCacheKey("findGameWithId_ID_{$id}");
      $this->getMemcache()->delete($cacheKey);
      return $id;
    }
    return 0;
  }

  /**
   * @desc update game where id
   * @param int $id
   * @param array $fields
   * @return false
   */
  public function updateGameWithId ($id, $fields) {
    if (!$id || !$fields) {
      throw new Exception('id or fields is null');
    }

    $updateFields = array();
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($fields['info']) $updateFields['info'] = $fields['info'];
    if ($fields['type']) $updateFields['type'] = $fields['type'];
    if ($updateFields) {
      $updateFields['updated_time'] = time();
      $stmt = $this->getGameConnection()->prepare("UPDATE `{$this->getGameTableName()}` {$this->getUpdateSect($updateFields)} WHERE `id` = :id LIMIT 1");
      $this->bindValues($stmt, $updateFields);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount()) {
        $cacheKey = $this->getCacheKey("findGameWithId_ID_{$id}");
        $this->getMemcache()->delete($cacheKey);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @desc select from game_states where game_id and round and action
   * @param int $gameId
   * @param int $round
   * @param string $action
   * @return array
   */
  public function findGameStatesWithGameIdAndRoundAndAction ($gameId, $round, $action, $offset = 0, $limit = 20) {
    if (!$gameId || !$round || !$action) {
      throw new Exception('game_id, round or action is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_GAMEID_{$gameId}_ROUND_{$round}_ACTION_{$action}_OFFSET_{$offset}_LIMIT_{$limit}");
    $scopeKey = $this->getGameSpyStatesScopeKey((string)$gameId);
    $rows = $this->getGameMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getGameSpyStatesTableName()}` WHERE `game_id` = :game_id AND `round` = :round AND `action` = :action ORDER BY `created_time` ASC LIMIT :offset, :limit");
      $stmt->bindValue(':game_id', $gameId, PDO::PARAM_INT);
      $stmt->bindValue(':round', $round, PDO::PARAM_INT);
      $stmt->bindValue(':action', $action, PDO::PARAM_STR);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $rows = $rows ? $rows : $rows;
      $this->getGameMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @desc select from game_states where game_id and uid and action
   * @param int $gameId
   * @param int $uid
   * @param string $action
   * @return array
   */
  public function findGameStatesWithGameIdAndUidAndAction ($gameId, $uid, $action, $offset = 0, $limit = 20) {
    if (!$gameId || !$uid || !$action) {
      throw new Exception('game_id, uid or action is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_GAMEID_{$gameId}_UID_{$uid}_ACTION_{$action}_OFFSET_{$offset}_LIMIT_{$limit}");
    $scopeKey = $this->getGameSpyStatesScopeKey((string)$gameId);
    $rows = $this->getGameMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getGameSpyStatesTableName()}` WHERE `game_id` = :game_id AND `uid` = :uid AND `action` = :action ORDER BY `created_time` ASC LIMIT :offset, :limit");
      $stmt->bindValue(':game_id', $gameId, PDO::PARAM_INT);
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->bindValue(':action', $action, PDO::PARAM_STR);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $rows = $rows ? $rows : $rows;
      $this->getGameMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @desc select from game_states where game_id limit 1
   * @param int $gameId
   * @return array
   */
  public function findGameLatestStateWithGameId ($gameId) {
    if (!$gameId) {
      throw new Exception('game_id is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_GAMEID_{$gameId}");
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getGameSpyStatesTableName()}` WHERE `game_id` = :game_id ORDER BY `id` DESC LIMIT 1");
      $stmt->bindValue(':game_id', $gameId, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $row = $row ? $row : array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc insert game_states
   * @param array $fields
   * @return bool
   */
  public function insertGameState ($fields) {
    if (!$fields || !$fields['game_id'] || !$fields['uid'] || !$fields['round'] || !$fields['action'] || !$fields['state'] || !$fields['content']) {
      throw new Exception('game_id, uid, round, action, state or content is null');
    }

    $insertFields = array();
    $insertFields['game_id'] = $fields['game_id'];
    $insertFields['uid'] = $fields['uid'];
    $insertFields['round'] = $fields['round'];
    $insertFields['action'] = $fields['action'];
    $insertFields['state'] = $fields['state'];
    $insertFields['content'] = $fields['content'];
    $insertFields['updated_time'] = 'NONE';
    if ($this->insert($this->getGameConnection(), $this->getGameSpyStatesTableName(), $insertFields)) {
      $cacheKey = $this->getCacheKey("findGameLatestStateWithGameId_GAMEID_{$fields['game_id']}");
      $this->getMemcache()->delete($cacheKey);
      $scopeKey = $this->getGameSpyStatesScopeKey((string) $fields['game_id']);
      $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @return game_tgqcqm_tms
   */
  public function findGameTgqcgmTms ($status = -1, $offset = 0, $limit = 20) {
    if (!$limit) {
      throw new Exception('limit is null');
    }

    $sqlWhere = $status >= 0 ? ' WHERE status = :status' : '';
    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_STATUS_{$status}_OFFSET_{$offset}_LIMIT_{$limit}");
    $scopeKey = $this->getGameTgqcgmTmsScopeKey();
    $rows = $this->getGameMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getGameTgqcgmTmsTableName()}` {$sqlWhere} ORDER BY `created_time` DESC LIMIT :offset, :limit");
      if ($status >= 0) $stmt->bindValue(':status', $status, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $rows = $rows ? $rows : array();
      $this->getGameMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @return game_tgqcqm_tms (like song_name)
   */
  public function findGameTgqcgmTmLikeSongName ($songName, $status = -1, $offset = 0, $limit = 20) {
    if (!$songName || !$limit) {
      throw new Exception('song_name or limit is null');
    }

    $sqlWhere = $status >= 0 ? ' status = :status AND ' : '';
    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_SONGNAME_{$songName}_STATUS_{$status}_OFFSET_{$offset}_LIMIT_{$limit}");
    $scopeKey = $this->getGameTgqcgmTmsScopeKey();
    $rows = $this->getGameMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getGameTgqcgmTmsTableName()}` WHERE {$sqlWhere} song_name LIKE :song_name ORDER BY `created_time` DESC LIMIT :offset, :limit");
      if ($status >= 0) $stmt->bindValue(':status', $status, PDO::PARAM_INT);
      $stmt->bindValue(':song_name', '%'.$songName.'%', PDO::PARAM_STR);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $rows = $rows ? $rows : array();
      $this->getGameMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @return count game_tgqcqm_tms (where status = 1)
   */
  public function findGameTgqcgmOnlineTmsCount () {
    $cacheKey = $this->getCacheKey(__FUNCTION__);
    $rowCount = $this->getMemcache()->get($cacheKey);
    if ($rowCount === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT COUNT(1) FROM {$this->getGameTgqcgmTmsTableName()} WHERE status = 1 LIMIT 1");
      $stmt->execute();
      $rowCount = $stmt->fetchColumn();
      if (empty($rowCount)) $rowCount = 0;
      $this->getMemcache()->set($cacheKey, $rowCount, 0, self::CACHE_TIME);
    }
    return $rowCount ? $rowCount : 0;
  }

  /**
   * @return game_tgqcqm_tms (with tm_id)
   */
  public function findGameTgqcgmTmWithId ($id) {
    if (!$id) {
      throw new Exception('id is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_ID_{$id}");
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getGameTgqcgmTmsTableName()}` WHERE `tm_id` = :tm_id LIMIT 1");
      $stmt->bindValue(':tm_id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $row = $row ? $row : array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc insert game_tgqcqm_tms
   */
  public function insertGameTgqcgmTm ($fields) {
    if (!$fields || !$fields['uid'] || !$fields['song_name'] || !$fields['uri'] || !$fields['duration']) {
      throw new Exception('uid, song_name, singer, uri or duration is null');
    }

    $resFields = array();
    $insertFields = array();
    $insertFields['uid'] = $fields['uid'];
    $insertFields['song_name'] = $fields['song_name'];
    if ($fields['singer']) $insertFields['singer'] = $fields['singer'];
    $insertFields['uri'] = $fields['uri'];
    if ($fields['ori_name']) $insertFields['ori_name'] = $fields['ori_name'];
    $insertFields['duration'] = $fields['duration'];
    if (isset($fields['status'])) $insertFields['status'] = $fields['status'];
    $tmId = $this->insert($this->getGameConnection(), $this->getGameTgqcgmTmsTableName(), $insertFields, TRUE);
    if ($tmId) {
      $insertFields['tm_id'] = $tmId;
      $resFields = $insertFields;
      $cacheKey = $this->getCacheKey("findGameTgqcgmTmWithId_ID_{$insertFields['tm_id']}");
      $this->getMemcache()->delete($cacheKey);
      $scopeKey = $this->getGameTgqcgmTmsScopeKey();
      $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
    }
    return $resFields;
  }

  /**
   * @desc update game_tgqcqm_tms where tm_id
   * @param int $tmID
   * @param array $fields
   * @return bool
   */
  public function updateGameTgqcgmTmWithId ($tmId, $fields) {
    if (!$tmId || !$fields) {
      throw new Exception('tm_id or fields is null');
    }

    $updateFields = array();
    if ($fields['uid']) $updateFields['uid'] = $fields['uid'];
    if ($fields['song_name']) $updateFields['song_name'] = $fields['song_name'];
    if (isset($fields['singer'])) $updateFields['singer'] = $fields['singer'];
    if ($fields['uri']) $updateFields['uri'] = $fields['uri'];
    if ($fields['duration']) $updateFields['duration'] = $fields['duration'];
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($updateFields) {
      $updateFields['updated_time'] = time();
      $stmt = $this->getGameConnection()->prepare("UPDATE `{$this->getGameTgqcgmTmsTableName()}` {$this->getUpdateSect($updateFields)} WHERE `tm_id` = :tm_id LIMIT 1");
      $this->bindValues($stmt, $updateFields);
      $stmt->bindValue(':tm_id', $tmId, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount()) {
        $cacheKey = $this->getCacheKey("findGameTgqcgmTmWithId_ID_{$tmId}");
        $this->getMemcache()->delete($cacheKey);
        $scopeKey = $this->getGameTgqcgmTmsScopeKey();
        $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @desc insert game_tgqcqm_right_users
   */
  public function insertGameTgqcgmRightUser ($fields) {
    if (!$fields || !$fields['tm_id'] || !$fields['uid'] || !$fields['rank']) {
      throw new Exception('tm_id, uid or rank is null');
    }

    $insertFields = array();
    $insertFields['tm_id'] = $fields['tm_id'];
    $insertFields['uid'] = $fields['uid'];
    $insertFields['rank'] = $fields['rank'];
    $insertFields['updated_time'] = 'NONE';
    return $this->insert($this->getGameConnection(), $this->getGameTgqcgmRightUsersTableName(), $insertFields);
  }

  /**
   * @desc select from game_setting where type and rid
   * @param int $type
   * @param int $rid
   * @return array
   */
  public function findGamesetListWithTypeAndRid ($type, $rid, $offset, $limit) {
    if (!$type && !$rid) {
      throw new Exception('type and rid are null at same time');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_TYPE_{$type}_RID_{$rid}_OFFSET_{$offset}_LIMIT_{$limit}");
    $scopeKey = $this->getGamesetSpyScopeKey();
    $rows = $this->getGameMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getGameSpySetTableName()}` WHERE `type` = :type AND `rid` = :rid ORDER BY `id` DESC LIMIT :offset, :limit");
      $stmt->bindValue(':type', $type, PDO::PARAM_INT);
      $stmt->bindValue(':rid', $rid, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $rows = $rows ? $rows : array();
      $this->getGameMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::ONE_DAY_CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @desc select from game_setting where id
   * @param int $id
   * @return array
   */
  public function findGamesetWithId ($id) {
    if (!$id) {
      throw new Exception('id is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_ID_{$id}");
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getGameSpySetTableName()}` WHERE `id` = :id LIMIT 1");
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $row = $row ? $row : array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc select count from game_setting where type
   * @param int $type
   * @param int $pageSize
   * @return int
   */
  public function findGamesetCountWithType ($type, $pageSize) {
    if (!$type || !$pageSize) {
      throw new Exception('type or page count is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_TYPE_{$type}");
    $total = $this->getMemcache()->get($cacheKey);
    if ($total === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT COUNT(*) FROM `{$this->getGameSpySetTableName()}` WHERE `type` = :type");
      $stmt->bindValue(':type', $type, PDO::PARAM_INT);
      $stmt->execute();
      $total = $stmt->fetchColumn();
      $total = $total ? $total : 0;
    }

    //TODO insert will delete this cache, while delete won't
    $this->getMemcache()->set($cacheKey, $total, 0, self::ONE_DAY_CACHE_TIME);
    return ceil($total / $pageSize);
  }
  
  /**
   * @desc select from game_setting where type like content
   * @param int $type
   * @param string $content
   * @return array
   */
  public function findGamesetWithTypeLikeContent ($type, $content, $offset = 0, $limit = 20) {
    if (!$type || !$content || !$limit) {
      throw new Exception('type, content or limit is null');
    }
  
    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_TYPE_{$type}_CONTENT_{$content}_OFFSET_{$offset}_LIMIT_{$limit}");
    $scopeKey = $this->getGamesetSpyScopeKey();
    $rows = $this->getGameMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getGameSpySetTableName()}` WHERE `type` = :type AND `content` LIKE :content LIMIT :offset, :limit");
      $stmt->bindValue(':type', $type, PDO::PARAM_INT);
      $stmt->bindValue(':content', '%'.$content.'%', PDO::PARAM_STR);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $rows = $rows ? $rows : array();
      $this->getGameMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey, $rows, self::ONE_DAY_CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @desc insert into game_setting
   * @param array $fields
   * @return bool
   */
  public function insertGameset ($fields) {
    if (!$fields || (!$fields['type'] && !$fields['rid']) || !$fields['content']) {
      throw new Exception('type and rid are null at same time or content is null');
    }

    $insertFields = array(
      'content' => $fields['content'],
      'created_time' => 'NONE',
      'updated_time' => 'NONE',
    );
    if ($fields['type']) $insertFields['type'] = $fields['type'];
    if ($fields['rid']) $insertFields['rid'] = $fields['rid'];
    $id = $this->insert($this->getGameConnection(), $this->getGameSpySetTableName(), $insertFields, TRUE);
    if ($id) {
      if ($fields['type']) {
        $cacheKey = $this->getCacheKey("findGamesetCountWithType_TYPE_{$fields['type']}");
        $this->getMemcache()->delete($cacheKey);
      }
      $cacheKey = $this->getCacheKey("findGamesetWithId_ID_{$id}");
      $this->getMemcache()->delete($cacheKey);
      $scopeKey = $this->getGamesetSpyScopeKey();
      $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @desc update game_setting where id
   * @param int $id
   * @param array $fields
   * @return bool
   */
  public function updateGamesetWithId ($id, $fields) {
    if (!$id || !$fields) {
      throw new Exception('id or fields is null');
    }

    $updateFields = array();
    if (isset($fields['type'])) $updateFields['type'] = $fields['type'];
    if (isset($fields['rid'])) $updateFields['rid'] = $fields['rid'];
    if ($fields['content']) $updateFields['content'] = $fields['content'];
    if ($updateFields) {
      $stmt = $this->getGameConnection()->prepare("UPDATE `{$this->getGameSpySetTableName()}` {$this->getUpdateSect($updateFields)} WHERE `id` = :id LIMIT 1");
      $this->bindValues($stmt, $updateFields);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      if ($stmt->execute() && $stmt->rowCount()) {
        $cacheKey = $this->getCacheKey("findGamesetWithId_ID_{$id}");
        $this->getMemcache()->delete($cacheKey);
        $scopeKey = $this->getGamesetSpyScopeKey();
        $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @desc delete from game_setting where id
   * @param int $id
   * @return bool
   */
  public function deleteGamesetWithId ($id) {
    if (!$id) {
      throw new Exception('id is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_ID_{$id}");
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getGameConnection()->prepare("DELETE FROM `{$this->getGameSpySetTableName()}` WHERE `id` = :id LIMIT 1");
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      if ($stmt->execute() && $stmt->rowCount()) {
        $cacheKey = $this->getCacheKey("findGamesetWithId_ID_{$id}");
        $this->getMemcache()->delete($cacheKey);
        $scopeKey = $this->getGamesetSpyScopeKey();
        $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @desc select from pseudo_users
   * @return array
   */
  public function findPseudoUserListWithUsing ($isUsing, $offset = 0, $limit = 20) {
    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_USING_{$isUsing}_OFFSET_{$offset}_LIMIT_{$limit}");
    $scopeKey = $this->getPseudoUsersScopeKey();
    $rows = $this->getGameMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
    if ($rows === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getPseudoUsersTableName()}` WHERE `is_using` = :is_using LIMIT :offset, :limit");
      $stmt->bindValue(':is_using', $isUsing, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $rows = $rows ? $rows : array();
      $this->getGameMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey, $rows, self::ONE_DAY_CACHE_TIME);
    }
    return $rows;
  }

  /**
   * @desc select from pseudo_users where uid
   * @param int $uid
   * @return array
   */
  public function findPseudoUserWithUid ($uid) {
    if (!$uid) {
      throw new Exception('uid is null');
    }

    $cacheKey = $this->getCacheKey(__FUNCTION__ . "_UID_{$uid}");
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getPseudoUsersTableName()}` WHERE `uid` = :uid LIMIT 1");
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $row = $row ? $row : array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::ONE_DAY_CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc select pseudo_users where is_using limit 1
   * @return array
   */
  public function findLatestPseudoUser () {
    $cacheKey = $this->getCacheKey(__FUNCTION__);
    $row = $this->getMemcache()->get($cacheKey);
    if ($row === FALSE) {
      $stmt = $this->getGameConnection()->prepare("SELECT * FROM `{$this->getPseudoUsersTableName()}` WHERE `is_using` = 0 LIMIT 1");
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $row = $row ? $row : array();
      $this->getMemcache()->set($cacheKey, $row, 0, self::CACHE_TIME);
    }
    return $row;
  }

  /**
   * @desc insert into pseudo_users
   * @param array $fields
   * @return int
   */
  public function insertPseudoUser ($fields) {
    if (!$fields['nickname'] || !$fields['birthday'] || !$fields['gender']) {
      throw new Exception('nickname, birthday or gender is null');
    }

    $insertFields = array(
      'nickname' => $fields['nickname'],
      'birthday' => $fields['birthday'],
      'created_time' => 'NONE',
      'updated_time' => 'NONE',
    );
    if ($fields['avatar']) $insertFields['avatar'] = $fields['avatar'];
    if ($fields['uid']) $insertFields['uid'] = $fields['uid'];
    if (isset($fields['is_using'])) $insertFields['is_using'] = $fields['is_using'];
    if (isset($fields['gender']))  $insertFields['gender'] = $fields['gender'];
    $uid = $this->insert($this->getGameConnection(), $this->getPseudoUsersTableName(), $insertFields, TRUE);
    if ($uid) {
      $scopeKey = $this->getPseudoUsersScopeKey();
      $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
      $cacheKey = $this->getCacheKey("findPseudoUserWithUid_UID_{$uid}");
      $this->getMemcache()->delete($cacheKey);
      return $uid;
    }
    return 0;
  }

  /**
   * @desc 更新假用户信息
   * @param int $uid
   * @param array $fields
   * @return bool
   */
  public function updatePseudoUserWithUid ($uid, $fields) {
    if (!$uid || !$fields) {
      throw new Exception('uid or fields is null');
    }

    $updateFields = array();
    if (isset($fields['is_using'])) $updateFields['is_using'] = $fields['is_using'];
    if ($fields['nickname']) $updateFields['nickname'] = $fields['nickname'];
    if ($fields['avatar']) $updateFields['avatar'] = $fields['avatar'];
    if ($fields['birthday']) $updateFields['birthday'] = $fields['birthday'];
    if (isset($fields['gender'])) $updateFields['gender'] = $fields['gender'];

    if ($updateFields) {
      $stmt = $this->getGameConnection()->prepare("UPDATE `{$this->getPseudoUsersTableName()}` {$this->getUpdateSect($updateFields)} WHERE `uid` = :uid LIMIT 1");
      $this->bindValues($stmt, $updateFields);
      $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount()) {
        $scopeKey = $this->getPseudoUsersScopeKey();
        $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
        $cacheKey = $this->getCacheKey("findPseudoUserWithUid_UID_{$uid}");
        $this->getMemcache()->delete($cacheKey);
        $cacheKey = $this->getCacheKey("findLatestPseudoUser");
        $this->getMemcache()->delete($cacheKey);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @desc delete pseudo_users where uid
   * @param int $uid
   * @return bool
   */
  public function deletePseudoUserWithUid ($uid) {
    if (!$uid) {
      throw new Exception('uid is null');
    }

    $stmt = $this->getGameConnection()->prepare("DELETE FROM `{$this->getPseudoUsersTableName()}` WHERE `uid` = :uid LIMIT 1");
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()) {
      $scopeKey = $this->getPseudoUsersScopeKey();
      $this->getGameMemcacheNameSpace()->removeBatchKeys($scopeKey);
      $cacheKey = $this->getCacheKey("findPseudoUserWithUid_UID_{$uid}");
      $this->getMemcache()->delete($cacheKey);
      $cacheKey = $this->getCacheKey("findLatestPseudoUser");
      $this->getMemcache()->delete($cacheKey);
      return TRUE;
    }
    return FALSE;
  }
}

