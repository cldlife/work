<?php
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
  'name' => 'WANZHU_BKMAN',
  'basePath' => dirname(__DIR__),
  'runtimePath' => APP_TMP_RUNTIME_DIR,
  //'defaultController' => 'permission',

  // 预加载目录/文件
  'import' => array(
    'lib.utils.*',
    'lib.utils.editor.*',
    'src.ServiceFactory',
    'application.controllers.BaseController'
  ),

  // 组件设置
  'components' => array(
    'urlManager' => array(
      'urlFormat' => 'path',
      'showScriptName' => false,
      'urlSuffix' => '.html',
      'rules' => array()
    ),

    'request' => array(
      // SCRF 安全过滤
      'enableCsrfValidation' => true,
    ),

    'errorHandler' => array(
      // use 'site/error' action to display errors
      'errorAction' => 'site/error'
    ),
  ),

  // application-level parameters that can be accessed
  // using Yii::app()->params['paramName']
  'params' => array(
  )
);
