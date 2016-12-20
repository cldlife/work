<?php
/**
 * @desc 脚本列表(别名=>路径)
 */
$apps = array(
  'Test' => '/Test/Test.php',
  'Gearman' => '/Gearman/GearmanWorkers.php',
  'WodiGame' => '/Wodi/WodiGame.php',
  'Gamebot' => '/Wodi/Gamebot.php',
);

/**
 * @desc BaseShell
 */
class BaseShell {
  
  //启动进程数
  public $processNum = 1;
  
  //当前进程ID
  public $currentProcessId = 1;
  
  //每个进程处理的数据量
  public $perProcessDataCount = 0;
  
  //数据总数
  public $dataCount = 0;
  
  public $debug = FALSE;
  
  //分表数
  const HASH_TABLE_NUM = 16;
  const LARGE_HASH_TABLE_NUM = 64;
  const LARGER_HASH_TABLE_NUM = 128;
  const LARGEST_HASH_TABLE_NUM = 256;
  
  const TARGET_DB_TABLE_NUM = 0;
  
  //打印输出
  public function println ($str = '', $nextline = TRUE) {
    if ($this->debug) echo $str . ($nextline ? "\n" : '');
  }
  
  /**
   * @desc 获得数据库连接，连接都只能从此获得
   * @param $dbSymbol 数据库别名
   */
  public function getConnection($dbSymbol) {
    return ConnectionFactory::getInstance()->getConnection($dbSymbol);
  }
  
  //insert子句
  public function getInsertClause(Array $data) {
    $fields = ' (';
    $values = ' VALUES (';
    foreach ( array_keys($data) as $key ) {
      $fields = $fields . '`' . $key . '`,';
      $values = $values . ':' . $key . ',';
    }
    $fields = substr_replace($fields, ')', -1);
    $values = substr_replace($values, ')', -1);
    return $fields . $values;
  }
  
  //获取数据字段
  public function getDataFields (Array $data) {
    $fields = ' (';
    foreach ( array_keys($data) as $key ) {
      $fields = $fields . '`' . $key . '`,';
    }
    $fields = substr_replace($fields, ')', -1);
    return $fields;
  }
  
  //获取数据字段值
  public function getDataValues (Array $data) {
    $values = ' (';
    foreach ( array_values($data) as $value ) {
      $values = $values . '"' .addslashes($value) .'",';
    }
    $values = substr_replace($values, ')', -1);
    return $values;
  }
  
  /**
   * @desc bind value
   */
  public function bindValues($stmt, $array) {
    foreach ( $array as $key => $value ) {
      $stmt->bindValue(':' . $key, $value, (is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR));
    }
  }
  
  /**
   * @desc DB Hash 分表算法 (bigint)
   */
  public function getHashTableName($splitKey, $talbleName, $tableNum = self::HASH_TABLE_NUM) {
    if (!$splitKey) {
      die("Please input spilt key...");
    }
    if (!$talbleName) {
      die("Please input table name...");
    }
  
    return $talbleName . (bcmod($splitKey, $tableNum) + 1);
  }
  
  /**
   * @desc DB Hash 分表算法 (string)
   */
  private static $alphabets  = array(
    'A' => 1, 'B' => 2, 'C' => 3, 'D' => 4,
    'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10, 'K' => 11, 'L' => 12,
    'M' => 13, 'N' => 14, 'O' => 15, 'P' => 16, 'Q' => 17, 'R' => 18, 'S' => 19, 'T' => 20,
    'U' => 21, 'V' => 22, 'W' => 23, 'X' => 24, 'Y' => 25, 'Z' => 26);
  public function getStringHashTableName ($splitKey, $talbleName, $tableNum = self::HASH_TABLE_NUM) {
    $splitKeyWithBigint = 0;
    if ($splitKey) {
      for ($i = 0; $i < strlen($splitKey); $i++) {
        $k = strtoupper(substr($splitKey, $i, 1));
        if (self::$alphabets[$k]) {
          $splitKeyWithBigint += self::$alphabets[$k];
        } else {
          if (is_numeric($k)) $splitKeyWithBigint += $k;
        }
      }
    }
  
    return $this->getHashTableName($splitKeyWithBigint, $talbleName, $tableNum);
  }
  
  /**
   * @desc 获取字符串首字并md5生成新的字符串
   */
  public function getFirstStringFromString ($string) {
    $firstString = '';
    preg_match("/[0-9a-z\x{4e00}-\x{9fa5}]+/iu", $string, $match);
    if ($match[0]) $firstString = mb_substr($match[0], 0, 1, APP_DEFAULT_CHARACTER);
    return md5($firstString);
  }
  
  protected function getUserFortuneService() {
    return ServiceFactory::getInstance()->createUserFortuneService();
  }
  protected function getGameService () {
    return ServiceFactory::getInstance()->createGameService();
  }
  protected function getGearmanService () {
    return ServiceFactory::getInstance()->createGearmanService();
  }
  protected function getCommonService () {
    return ServiceFactory::getInstance()->createCommonService();
  }
  protected function getWeixinService() {
    return ServiceFactory::getInstance()->createWeixinService();
  }
  protected function getUserService() {
    return ServiceFactory::getInstance()->createUserService();
  }
}
