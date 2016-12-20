<?php
/**
 * @desc 核心配置文件（一切精彩都从这里开始）
 */
//    const EMERG   = 0;  // Emergency: system is unusable
//    const ALERT   = 1;  // Alert: action must be taken immediately
//    const CRIT    = 2;  // Critical: critical conditions
//    const ERR     = 3;  // Error: error conditions
//    const WARN    = 4;  // Warning: warning conditions
//    const NOTICE  = 5;  // Notice: normal but significant condition
//    const INFO    = 6;  // Informational: informational messages
//    const DEBUG   = 7;  // Debug: debug messages
//app system run & log level : test,development,production
if (!defined("APP_SYSTEM_RUN_LEVEL")) define("APP_SYSTEM_RUN_LEVEL", "development");
define("APP_SYSTEM_LOG_LEVEL", 3);
if (!defined("APP_DEBUG")) define("APP_DEBUG", TRUE);
define('APP_DEFAULT_CHARACTER', 'UTF-8');

//日期&时间设置
define('DATE_FORMAT', 'Y-m-d H:i:s');
date_default_timezone_set('Asia/Shanghai');

//debug model, display all errors
if (APP_DEBUG) {
  ini_set('display_errors', 'On');
  error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
  defined('YII_DEBUG') or define('YII_DEBUG', APP_DEBUG);
  defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', APP_SYSTEM_LOG_LEVEL);
} else {
  error_reporting(0);
}

//距离计算配置
//地球半径, 平均半径为6371.393km (单位m)
define('EARTH_RADIUS', 6371393);
define('DISTANCE_LIMIT', 1000);
define('DISTANCE_OFFSET', 100);

//app base dir config (config | lib | log | shell | src | storage | web)
define("APP_ROOT_DIR", dirname(dirname(__FILE__)));
define("APP_CONFIG_DIR", APP_ROOT_DIR . DIRECTORY_SEPARATOR . 'config');
define("APP_CONFIG_DATABASE_FILE", APP_CONFIG_DIR . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'database_%level%.php');
define("APP_CONFIG_MEMCACHE_FILE", APP_CONFIG_DIR . DIRECTORY_SEPARATOR . 'memcache' . DIRECTORY_SEPARATOR . 'memcache_%level%.php');
define("APP_CONFIG_GEARMAN_FILE", APP_CONFIG_DIR . DIRECTORY_SEPARATOR . 'gearman' . DIRECTORY_SEPARATOR . 'gearman_%level%.php');
define("APP_CONFIG_THIRDSDK_DIR", APP_CONFIG_DIR . DIRECTORY_SEPARATOR . 'thirdsdk');

define("APP_LIB_DIR", APP_ROOT_DIR . DIRECTORY_SEPARATOR . 'lib');
define("APP_LIB_THIRD_SDK_DIR", APP_LIB_DIR . DIRECTORY_SEPARATOR . 'thirdsdk');
define('APP_LIB_CACHE_DIR', APP_LIB_DIR . DIRECTORY_SEPARATOR . 'cache');
define('APP_LIB_CORE_DIR', APP_LIB_DIR . DIRECTORY_SEPARATOR . 'core');
define('APP_LIB_FRAMEWORK_DIR', APP_LIB_DIR . DIRECTORY_SEPARATOR . 'framework');
define("APP_LIB_UTILS_DIR", APP_LIB_DIR . DIRECTORY_SEPARATOR . 'utils');
define("APP_LIB_GAMES_DIR", APP_LIB_DIR . DIRECTORY_SEPARATOR . 'games');

define("APP_LOG_DIR", APP_ROOT_DIR . DIRECTORY_SEPARATOR . 'log');
define("APP_SHELL_DIR", APP_ROOT_DIR . DIRECTORY_SEPARATOR . 'shell');

define("APP_SRC_DIR", APP_ROOT_DIR . DIRECTORY_SEPARATOR . 'src');
define("APP_SRC_BASE_DIR", APP_SRC_DIR . DIRECTORY_SEPARATOR . 'base');
define("APP_SRC_DAO_DIR", APP_SRC_DIR . DIRECTORY_SEPARATOR . 'dao');
define("APP_SRC_SERVICE_DIR", APP_SRC_DIR . DIRECTORY_SEPARATOR . 'service');

define("APP_STORAGE_DIR", APP_ROOT_DIR . DIRECTORY_SEPARATOR . 'storage');
define("APP_STORAGE_CACHE_DIR", APP_STORAGE_DIR . DIRECTORY_SEPARATOR . 'cache');
define("APP_STORAGE_FILE_DIR", APP_STORAGE_DIR . DIRECTORY_SEPARATOR . 'files');
define('APP_STORAGE_FILE_UI_DIR', APP_STORAGE_FILE_DIR . DIRECTORY_SEPARATOR . 'ui');
define('APP_STORAGE_FILE_UPLOADED_DIR', APP_STORAGE_FILE_DIR . DIRECTORY_SEPARATOR . 'up');
define('APP_STORAGE_FILE_AVATAR_DIR', APP_STORAGE_FILE_DIR . DIRECTORY_SEPARATOR . 'avatar');

define("APP_WEB_DIR", APP_ROOT_DIR . DIRECTORY_SEPARATOR . 'web');

//数据库前缀
define('APP_DB_PRIFIX', 'dev_wanzhu_');

//临时文件目录
define('APP_TMP_DIR', APP_LOG_DIR . '/');
define('APP_TMP_RUNTIME_DIR', APP_TMP_DIR . 'runtime');

//允许上传的文件后缀
define('APP_ALLOW_IMAGE_TYPE', 'png|jpg|jpeg');
define('APP_ALLOW_FILE_TYPE', 'amr|mp3|css');

//前后端目录
define('FB_END_PATH', '/FB-End');
define('FB_END_TPL_PATH', FB_END_PATH . '/tpl');

//上传目录(头像,广告等)
define('APP_FILE_UPLOADED_DIR', 'up');
define('APP_FILE_AVATAR_DIR', 'avatar');
define('APP_FILE_ADV_DIR', 'adv');

//动态压缩图片规则rule
define('APP_DYNAMIC_FILE_RULE_960', '_960x.jpg');
define('APP_DYNAMIC_FILE_RULE_750', '/750');
define('APP_DYNAMIC_FILE_RULE_750x500', '_750x500.jpg');
define('APP_DYNAMIC_FILE_RULE_750x300', '_750x300.jpg');
define('APP_DYNAMIC_FILE_RULE_360x360', '_360x360.jpg');
define('APP_DYNAMIC_FILE_RULE_300x200', '_300x200.jpg');

//WEB公共URL配置
define('WEB_QW_APP_DOMAIN_SUFFIX', 'wanzhuwenhua.com');
define('WEB_QW_APP_DOMAIN', 'http://' . WEB_QW_APP_DOMAIN_SUFFIX);
define('WEB_QW_APP_WX_DOMAIN', 'http://wx.' . WEB_QW_APP_DOMAIN_SUFFIX);
define('WEB_QW_APP_API_DOMAIN', 'http://api.' . WEB_QW_APP_DOMAIN_SUFFIX);
define('WEB_QW_APP_M_DOMAIN', 'http://m.' . WEB_QW_APP_DOMAIN_SUFFIX);

//静态资源URL配置
if (!defined("WEB_QW_APP_STATIC_FILE_DOMAIN_SUFFIX")) define('WEB_QW_APP_STATIC_FILE_DOMAIN_SUFFIX', '//img.shihuo.me');
if (!defined("WEB_QW_APP_FILE_UI_URL")) define('WEB_QW_APP_FILE_UI_URL', WEB_QW_APP_STATIC_FILE_DOMAIN_SUFFIX . '/' . 'ui');

//附件上传URL配置
if (!defined("WEB_QW_APP_FILE_DOMAIN_SUFFIX")) define('WEB_QW_APP_FILE_DOMAIN_SUFFIX', 'shiyi11.com');
if (!defined("WEB_QW_APP_FILE_DOMAIN")) define('WEB_QW_APP_FILE_DOMAIN', 'http://tgf.' . WEB_QW_APP_FILE_DOMAIN_SUFFIX);
if (!defined("WEB_QW_APP_DYNAMIC_FILE_DOMAIN")) define('WEB_QW_APP_DYNAMIC_FILE_DOMAIN', 'http://tgf1.' . WEB_QW_APP_FILE_DOMAIN_SUFFIX);

//set lib yii framework file
define('APP_YII_FRAMEWORK_FILE', APP_LIB_FRAMEWORK_DIR . DIRECTORY_SEPARATOR . 'yii/yii.php');
