<?php
/**
 * @desc Article DAO
 */
class ArticleDAO extends BaseDao {

  private function getArticleHashTableName () {
    return 'mc_article';
  }

  /**
   * @desc find Articles
   */
  public function findArticles ($status, $offset = 0, $limit = 10) {
//     $cacheKey = $this->getCacheKey(__FUNCTION__ . 'STATUS' . $status . '_OFFSET_' . $offset . '_LIMIT_' . $limit);
//     $scopeKey = $this->getArticlesScopeKey();
//     $rows = $this->getArticleMemcacheNameSpace()->getFromCache($cacheKey, $scopeKey);
//     if (1) {
      $stmt = $this->getArticleConnection()->prepare("SELECT * FROM {$this->getArticleHashTableName()} WHERE status = :status ORDER BY created_time DESC LIMIT :offset, :limit");
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->bindValue(':status', $status, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($rows)) $rows = array();
//       $this->getArticleMemcacheNameSpace()->saveToCache($cacheKey, $scopeKey, $rows, self::ONE_HOUR_CACHE_TIME);
//     }
    return $rows;
  }

  /**
   * @desc find Article id
   */
  public function findArticleById ($articleid) {
    if (!$articleid) {
      throw new Exception('id is null...');
    }
//     $cacheKey = $this->getCacheKey(__FUNCTION__ . '_ARTICLEID_' . $articleid);
//     $row = $this->getMemcache()->get($cacheKey);
//     if (1) {
      $stmt = $this->getArticleConnection()->prepare("SELECT * FROM {$this->getArticleHashTableName()} WHERE id = :id LIMIT 1");
      $stmt->bindValue(':id', $articleid, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) $row = array();
//       $this->getMemcache()->set($cacheKey, $row, 0, self::ONE_HOUR_CACHE_TIME);
//     }

    return $row;
  }

  /**
   * @desc insert Article
   */
  public function insertArticle ($fields) {
    if (!$fields['title'] || !$fields['content'] || !$fields['description'] || !$fields['template_id'] ) {
      throw new Exception('title content description or template_id is null...');
    }

    $insertFields = array();
    $insertFields['title'] = $fields['title'];
    $insertFields['content'] = $fields['content'];
    $insertFields['description'] = $fields['description'];
    $insertFields['template_id'] = $fields['template_id'];
    $insertFields['is_shop'] = $fields['is_shop'];
    $insertFields['is_comment'] = $fields['is_comment'];

    $lastInsertId = $this->insert($this->getArticleConnection(), $this->getArticleHashTableName(), $insertFields, TRUE);
    if ($lastInsertId) {
//       $scopeKey = $this->getArticlesScopeKey();
//       $this->getArticleMemcacheNameSpace()->removeBatchKeys($scopeKey);
      $insertFields['id'] = $lastInsertId;
      return $insertFields;
    }

    return array();
  }

  /**
   * @desc update Article
   */
  public function updateArticle ($articleid, $fields) {
    if (!$articleid) {
      throw new Exception('id is null...');
    }
    $updateFields = array();
    if ($fields['title']) $updateFields['title'] = $fields['title'];
    if ($fields['content']) $updateFields['content'] = $fields['content'];
    if ($fields['description']) $updateFields['description'] = $fields['description'];
    if ($fields['template_id']) $updateFields['template_id'] = $fields['template_id'];
    if (isset($fields['is_shop'])) $updateFields['is_shop'] = $fields['is_shop'] ? $fields['is_shop']:0;
    if (isset($fields['is_comment'])) $updateFields['is_comment'] = $fields['is_comment'] ? $fields['is_comment']:0;
    if (isset($fields['status'])) $updateFields['status'] = $fields['status'];
    if ($updateFields) {
      $updateFields['updated_time'] = time();

      $sql = "UPDATE {$this->getArticleHashTableName()} {$this->getUpdateSect($updateFields)} WHERE id = :id LIMIT 1";
      $stmt = $this->getArticleConnection()->prepare($sql);
      $stmt->bindValue(':id', $articleid, PDO::PARAM_INT);
      $this->bindValues($stmt, $updateFields);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
//       if ($rowCount) {
//         $cacheKey = $this->getCacheKey('findArticleById' . '_ARTICLEID_' . $articleid);
//         $this->getMemcache()->delete($cacheKey);
//         $scopeKey = $this->getArticlesScopeKey();
//         $this->getArticleMemcacheNameSpace()->removeBatchKeys($scopeKey);
//       }
      return TRUE;
    }
    return FALSE;
  }

}
