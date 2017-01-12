<?php
/**
 * Current APP Route Index
*/
define("CUR_APP_ROOT", dirname(__FILE__));
define("PARENT_ROOT", dirname(dirname(CUR_APP_ROOT)));

//include system config
require_once PARENT_ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'system.php';

//include Framework of Yii file
require_once (APP_YII_FRAMEWORK_FILE);

//setting dependent packages
Yii::setPathOfAlias('lib', APP_LIB_DIR);
Yii::setPathOfAlias('src', APP_SRC_SERVICE_DIR);

//running
$config = CUR_APP_ROOT . DIRECTORY_SEPARATOR . 'protected'. DIRECTORY_SEPARATOR . 'config/main.php';
Yii::createWebApplication($config)->run();
