<?php
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
  'name' => 'WEB',
  'basePath' => dirname(__DIR__), 
  'runtimePath' => APP_TMP_RUNTIME_DIR,
  'defaultController' => 'd',

  // 预加载目录/文件
  'import' => array(
    'lib.utils.*',
    'src.ServiceFactory',
    'application.controllers.BaseController' 
  ),
  
  // 组件设置
  'components' => array(
    'urlManager' => array(
      'urlFormat' => 'path',
      'showScriptName' => false,
      'urlSuffix' => '.html',
      'rules' => array(
        'd/<uid:([0-9]+)>'  => '/d/index',
      ) 
    ),
    
    'request' => array(
      //SCRF安全验证（白名单不作csrf验证，注：必须全小写）
      'enableCsrfValidation' => true,
      'noCsrfValidationRoutes' => array(
      ),
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
