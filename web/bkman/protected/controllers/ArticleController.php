<?php
/**
 * @desc 文章管理
 */
class ArticleController extends BaseController {

  //文章类别
  private static $category = array(1=>'专题', 2=>'活动');

  //模版
  private static $template = array(1=>'模版1', 2=>'模版2');

  /**
   * @desc actions 主入口
   */
  public function run () {
    parent::filters();
    $this->defaultURIDoAction = '';
    $method = $this->getURIDoAction($this);
    $this->$method();
  }

  /**
   * @desc 错误处理回调方法
   */
  public function errorRedirect () {
    $this->redirect($this->getDeUrl('main/error', array('id' => -404)));
  }

  /**
   * @desc  文章查询
   */
  private function doIndex() {

    $page = $this->getSafeRequest('page', 1, 'GET', 'int');
    $category = $this->getSafeRequest('category', 0, 'GET', 'int');
    $keyword = $this->getSafeRequest('keyword', '', 'GET', 'string');
    if (!$page) $page = 1;
    $pageSize = 10;
    $status = 0;
    if ($category) {
      $articleList = $this->getArticleService()->getArticlesCategory($category, $status, $page, $pageSize);
    } else {
      $articleList = $this->getArticleService()->getArticles($status, $page, $pageSize);
    }
    //分页处理
    $listPageCount = self::DEFAULT_PAGER_PAGESIZE * $pageSize;
    $listCount = count($articleList);
    if ($listCount < $pageSize) {
      $listPageCount = ($page - 1) * $pageSize + $listCount;
    }

    $show = array();
    $show['curPage'] = $page;
    $show['category'] = self::$category;
    $show['categoryid'] = $category;
    $show['articleList'] = $articleList;
    $show['pager'] = $this->getPager($listPageCount, $page, $pageSize);
    $this->title = '文章列表';
    $this->render('list', $show);
  }

  /**
   * @desc 添加/编辑文章
   */
  private function doAddEdit() {
    $action = $this->getSafeRequest('action');
    $code = $this->getSafeRequest('code', 0, 'GET', 'int');
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');
    $articleid = $this->getSafeRequest('article_id', 0, 'GET', 'int');

    $title = $this->getSafeRequest('title', '', 'POST', 'string');
    $content = $this->getSafeRequest('editorContent', '', 'POST', 'string');
    $description = $this->getSafeRequest('description', '', 'POST', 'string');
    $template_id = $this->getSafeRequest('template_id', 0, 'POST', 'int');
    $category_id = $this->getSafeRequest('category_id', 0, 'POST', 'int');
    $is_shop = $this->getSafeRequest('is_shop', 0, 'POST', 'int');
    $is_comment = $this->getSafeRequest('is_comment', 0, 'POST', 'int');

    //获取文章信息
    $article = $this->getArticleService()->getArticleById($articleid);

    //添加or编辑
    if ($action == 'submit') {

      if ($article['id']) {
        //编辑文章
        $updateFields = array();
        if ($title != $article['title']) $updateFields['title'] = $title;
        if ($content != $article['content']) $updateFields['content'] = $content;
        if ($description != $article['description']) $updateFields['description'] = $description;
        if ($template_id != $article['template_id']) $updateFields['template_id'] = $template_id;
        if ($is_shop != $article['is_shop']) $updateFields['is_shop'] = $is_shop;
        if ($is_comment != $article['is_comment']) $updateFields['is_comment'] = $is_comment;
        $this->getArticleService()->updateArticle($article['id'], $updateFields);
        $this->redirect($this->getDeUrl('article/addedit', array('id' => $this->permissionId, 'article_id' => $article['id'], 'code' => 1)));
      } else {
        //添加文章
        if ($title && $content) {

          $article = $this->getArticleService()->addArticle(array(
            'title' => $title,
            'description'  => $description,
            'content' => $content,
            'template_id' => $template_id,
            'category_id' => $category_id,
            'is_shop' => $is_shop,
            'is_comment' => $is_comment,
          ));
          if ($article['id']) {
            $this->redirect($this->getDeUrl('article/addedit', array('id' => $this->permissionId, 'article_id' => $article['id'], 'code' => 1)));
          }
        } else {
          $code = -1;
        }
      }
    }

    $show = array();
    $show['code'] = $code;
    $show['curPage'] = $page;
    $show['category'] = self::$category;
    $show['template'] = self::$template;
    $show['article'] = $article;
    $this->title = $article['id'] ? '编辑文章' : '添加文章';
    $this->render('addedit', $show);
  }

  /**
   * @desc 删除文章信息 (ajax异步)
   */
  private function doDel () {
    $articleid = $this->getSafeRequest('article_id', 0, 'POST', 'int');
    $ret = array();
    $updateFields = array();
    $ret['code'] = 0;
    $updateFields['status'] = 1;
    //获取文章信息
    $article = $this->getArticleService()->getArticleById($articleid);
    if ($article) {
      if ($this->getArticleService()->updateArticle($articleid, $updateFields)) $ret['code'] = 1;
    }
    $this->outputJsonData($ret);
  }

}
?>
