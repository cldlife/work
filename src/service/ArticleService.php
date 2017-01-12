<?php
/**
 * @desc ArticleService
 */
class ArticleService extends BaseService {
  
  private function getArticleDAO() {
    return DAOFactory::getInstance()->createArticleDAO();
  }

  /**
   * @desc 获取列表
   * @param int page pagesize
   */
  public function getArticles ($status, $page = 1, $pageSize = 10) {
    if ($page && $pageSize) {
      $offset = ($page - 1) * $pageSize;
      $articles = $this->getArticleDAO()->findArticles($status, $offset, $pageSize);
    }
    return $articles;
  }

  /**
   * @desc 根据ID获取文章数据信息
   * @param int $articleid
   */
  public function getArticleById ($articleid) {
    if ($articleid) {
      $article = $this->getArticleDAO()->findArticleById($articleid);
    }
    return $article;
  }

  /**
   * @desc 增加文章信息
   * @param array $fields
   */
  public function addArticle ($fields) {
    if (!$fields['title'] || !$fields['content'] || !$fields['description'] || !$fields['template_id']) {
      throw new Exception('title content description or template_id is null...');
    }
    return $this->getArticleDAO()->insertArticle($fields);
  }

  /**
   * @desc 更新文章信息
   */
  public function updateArticle ($articleid, $fields) {
    if ($articleid) {
      $article = $this->getArticleDAO()->updateArticle($articleid, $fields);
    }
    return $article;
  }

}
