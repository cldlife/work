<?php
/**
 * Cache of File
 * @author VegaPunk
 */
class FileCacheHandler {

  protected $cacheDir = "";

  protected $errorMsg = "";

  public function __construct ($cacheDir = "") {
    $this->cacheDir = $cacheDir;
  }

  public function getErrorMsg () {
    return $this->errorMsg;
  }

  /**
   * 获得目录
   * @param string $cacheId
   * @return string
   */
  protected function __getCacheFilePath ($cacheId) {
    if (!$cacheId) {
      $this->errorMsg = "请检查参数[cacheId]";
      return false;
    }
    $dir = $this->cacheDir . "/";
    $seed = md5($cacheId);
    $firstDir = substr($seed, 0, 2);
    $secondDir = substr($seed, 2, 2);
    $thirdDir = substr($seed, 4, 2);
    $dir .= $firstDir . "/";
    $dir .= $secondDir . "/";
    $dir .= $thirdDir . "/";
    if (file_exists($dir) == false) {
      @mkdir($dir, 0777, true);
    }
    $cachePath = $dir . "cache_" . $cacheId . ".php";
    return $cachePath;
  }

  /**
   * 缓存到文件
   * @param string $cacheId
   * @param mixed $data
   */
  protected function __cacheToFile ($cacheId, &$data, $cacheTime = 0) {
    if (is_array($data) || is_object($data)) {
      $_str = serialize($data);
    } else {
      $arr["__string_data"] = $data;
      $_str = serialize($arr);
    }
    if ($_str == "") {
      $this->errorMsg = "数据不能为空";
      return false;
    }
    $cachePath = $this->__getCacheFilePath($cacheId);
    $f = @fopen($cachePath, "w");
    if (!$f) {
      $this->errorMsg = "文件打开错误[$cachePath]";
      return false;
    }
    $time = $cacheTime ? date(DATE_FORMAT, time() + $cacheTime) : 0;
    $_str = "<?php exit();?>" . $time . "#^#^#" . $_str;
    $len = strlen($_str);
    flock($f, LOCK_EX);
    for ($i = 0; $i < $len; $i++) {
      @fwrite($f, $_str[$i]);
    }
    flock($f, LOCK_UN); // 释放锁定
    @fclose($f);
    //@chmod($cachePath,0777);
    return true;
  }

  /**
   * 缓存数据
   * @param string $cacheId
   * @param mixed $data
   */
  function set ($cacheId, $data, $cacheTime = 0) {
    if (!$cacheId) {
      $this->errorMsg = "请检查参数[cacheId]";
      return false;
    }
    return $this->__cacheToFile($cacheId, $data, $cacheTime);
  }

  /**
   * 文件中读取数据
   * @param string $cacheId
   */
  function &__fetchFromFile ($cacheId) {
    $cacheFile = $this->__getCacheFilePath($cacheId);
    if (file_exists($cacheFile) == false) {
      $this->errorMsg = "文件不存在[$cacheFile]!";
      return false;
    }
    $f = @fopen($cacheFile, "r");
    if (!$f) {
      $this->errorMsg = "打开文件错误[$cacheFile]!";
      return false;
    }
    $_str = "";
    while (!feof($f)) {
      $_str .= @fgetc($f);
    }
    @fclose($f);
    if (!empty($_str)) {
      $pos = strlen("<?php exit();?>");
      $_str = substr($_str, $pos);
    }
    if (!$_str) {
      $this->errorMsg = "无数据[$cacheFile]!";
      return false;
    }
    list($_expireTime, $_datastr) = explode("#^#^#", $_str);
    if ($_expireTime) {//0,永不过期
      if (strtotime($_expireTime) < time()) {
        $this->errorMsg = "已过期[$cacheFile]!";
        return false;
      }
    }
    if (!$_datastr) {
      $this->errorMsg = "无数据[$cacheFile]!";
      return false;
    }
    $arr = unserialize($_datastr);
    if ($arr["__string_data"]) { //字符串数据
      return $arr["__string_data"];
    } else {
      return $arr; //数组
    }
  }

  /**
   * 获取数据
   * @param string $cacheId
   */
  function &get ($cacheId) {
    if (!$cacheId) {
      $this->errorMsg = "请检查参数[cacheId]";
      return false;
    }
    return $this->__fetchFromFile($cacheId);
  }

  /**
   * 获取CACHEID
   * @param string $tbl 数据表名
   * @param string $code 代码
   */
  function getCacheId () {
    $cacheId = date("YmdHis");
    return $cacheId;
  }

  /**
   * 清除缓存文件
   * @param string $cacheId
   */
  function __clearFile ($cacheId) {
    $cacheFile = $this->__getCacheFilePath($cacheId);
    if (file_exists($cacheFile) == false) {
      $this->errorMsg = "文件不存在[$cacheFile]!";
      return false;
    }
    @unlink($cacheFile);
    return true;
  }

  /**
   * 清除缓存
   * @param string $cacheId
   */
  function clearCache ($cacheId) {
    return $this->__clearFile($cacheId);
  }
}