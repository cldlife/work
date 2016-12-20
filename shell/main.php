#!/usr/local/app/php5-cgi/bin/php
<?php
/**
 * shell脚本入口主程序 (支持多进程)
 *
 * @param $argv[1] app应用名称
 * @param $argv[2] 启动进程数 （默认:1）
 * @param $argv[3] 当前进程ID
 */
set_time_limit(0);
define('SHELL_ROOT', dirname(__FILE__));
define('SHELL_PHPBIN', '/usr/local/app/php5-cgi/bin/php');
define('SHELL_LOG_DIR', SHELL_ROOT . '/log');

//run level
define("APP_SYSTEM_RUN_LEVEL", "shell");
define("APP_DEBUG", TRUE);

//include system config
require_once dirname(SHELL_ROOT) . '/config/system.php';

//include Service Factory
require_once APP_SRC_SERVICE_DIR . '/ServiceFactory.php';

//include game class
require_once APP_LIB_GAMES_DIR . '/BaseGame.php';
require_once APP_LIB_GAMES_DIR . '/GameMsg.php';
require_once APP_LIB_GAMES_DIR . '/SpyGame.php';
require_once APP_LIB_GAMES_DIR . '/AppSpyGame.php';
//include Utils
require_once APP_LIB_UTILS_DIR . '/Utils.php';
//include HttpClient
require_once APP_LIB_UTILS_DIR . '/HttpClient.php';
// require_once APP_LIB_UTILS_DIR . '/CityPosition.php';

//include Spider Config
require_once SHELL_ROOT . '/config.php';

//脚本应用名称
$shellName = $argv[1] ? $argv[1] : $_GET['app'];

//启动进程数
$processNum = intval($argv[2]) ? intval($argv[2]) : 1;

//当前进程ID
$currentProcessId = intval($argv[3]) ? intval($argv[3]) : 1;

//启动app脚本
$appFile = $apps[$shellName];
$appFilePath = SHELL_ROOT . '/sh' . $appFile;
if ($appFile && file_exists($appFilePath)) {
  include_once $appFilePath;
  $shellName::run($processNum, $currentProcessId);
} else {
  die("Error!: Don`t found '{$shellName}' App \r\n");
}
