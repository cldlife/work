<?php
/**
 * @desc 文件管理
 */
class AttachmentsController extends BaseController {

  /**
   * @desc actions 主入口
   */
  public function run ($actionID = NULL) {
    parent::filters();
    $this->defaultURIDoAction = 'base';
    $method = $this->getURIDoAction($this);
    $this->$method();
  }

  /**
   * @desc 文件列表
   */
  private function doList () {
    $page = $this->getSafeRequest('page', 1, 'GET', 'int');
    $pageSize = 20;
    $status = 0;
    $list = array();
    $list = $this->getBkAdminService()->getBkAttachments($status, $page, $pageSize);

    //分页处理
    $pageCount = self::DEFAULT_PAGER_PAGESIZE * $pageSize;
    $count = count($list);
    if ($count < $pageSize) $pageCount = ($page - 1) * $pageSize + $count;

    $data = array();
    $data['curPage'] = $page;
    $data['list'] = $list;
    $data['pager'] = $this->getPager($pageCount, $page, $pageSize);
    $this->render('list', $data);
  }

  /**
   * @desc 文件上传
   */
  private function doUpload () {
    $this->render('upload');
  }

  private function doUploadFile () {
    $name = $file = '';
    foreach ($_POST as $key => $value) {
      if ($key == '_sh_token_' || !$value) continue;
      $name = trim($key);
      $file = trim($value);
    }
    if (!$name || !$file) $this->outputJsonData(array('code' => 1, 'msg' => '获取文件数据失败!'));
    $attachmentName = $this->formateFileName($name);
    if (!$attachmentName) $this->outputJsonData(array('code' => 2, 'msg' => '文件名称格式错误!', 'name' => $name));
    $tmpFile = "/tmp/{$attachmentName['filename']}";
    if (!file_put_contents($tmpFile, base64_decode($file))) $this->outputJsonData(array('code' => 3, 'msg' => '生成文件文件失败!'));
    $res = $this->getAttachmentService()->uploadImage($tmpFile,0);

    unlink($tmpFile);
    if (!$res || $res['code'] != 1) $this->outputJsonData(array('code' => 4, 'msg' => '上传到服务器失败!'));

    if ($this->getBkAdminService()->addBkAttachment(array(
      'aid' => $res['fileInfo']['aid'], 
      'type' => $attachmentName['ext'],
      'local_name' => $attachmentName['local_name'],
      'file_uri' => $res['fileInfo']['file_uri'],
      'file_name' =>  $res['fileInfo']['file_name'],
      'width' => $res['fileInfo']['width'],
      'height' => $res['fileInfo']['height'],
    ))) {
      $this->outputJsonData(array('code' => 0, 'msg' => '上传文件成功!'));
    } else {
      $this->outputJsonData(array('code' => 5, 'msg' => '添加文件失败!'));
    }
  }

  /**
   * @desc 获取文件名和格式
   */
  private function formateFileName ($name) {
    if ($name) { 
      $extNum = strripos($name, '_');
      $fileName = substr($name,0,$extNum);
      $ext = substr($name,$extNum);
      $ext = str_replace('_', '.', $ext);
      $name = $fileName.$ext;
      return array(
        'local_name' => $fileName,
        'filename' => $name,
        'ext' => trim(strtolower($ext),'.')
      );
    }
    return array();
  }

  /**
   * @desc 错误处理回调方法
   */
  public function errorRedirect () {
    $this->redirect($this->getDeUrl('main/error', array('id' => -404)));
  }
}
