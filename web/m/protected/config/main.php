<?php
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
  'name' => 'M',
  'basePath' => dirname(__DIR__), 
  'runtimePath' => APP_TMP_RUNTIME_DIR,
  
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
         't/<tid:([0-9]+)>' => '/thing/detail',
         's/<song_id:([0-9]+)>'  => '/thing/song',
         '/app/zhuangbi/zb<category:([a-z]+)>' => '/app/zhuangbi/zbcreat',
      ) 
    ),
    
    'errorHandler' => array(
      // use 'site/error' action to display errors
      'errorAction' => 'site/error' 
    ),
  ),
  
  // application-level parameters that can be accessed
  // using Yii::app()->params['paramName']
  'params' => array() 
);
