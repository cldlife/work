<?php
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
  'name' => '玩主API', 
  'basePath' => dirname(__DIR__), 
  'runtimePath' => APP_TMP_RUNTIME_DIR,
    
  //预加载目录/文件
  'import' => array(
    'lib.utils.*', 
    'lib.games.*',
    'src.ServiceFactory',
    'application.controllers.BaseController',
  ), 
  
  //组件设置
  'components' => array(
    'urlManager' => array(
      'urlFormat' => 'path', 
      'showScriptName' => false, 
      'urlSuffix' => '.json', 
      'rules' => array(
    	'wanzhu/v<version:([0-9\.]+)>/wiki.html' => 'site/wiki',
    	'rongcloud/routing/msg.html' => 'rongcloud/routing',
    	'rongcloud/routing/online.html' => 'rongcloud/routing',
      )
    ), 

    'fliter' => array(
      //HTML过滤
      'CHtmlPurifier' => true
    ), 
    
    'errorHandler' => array(
      //use 'site/error' action to display errors
      'errorAction' => 'site/error'
    ), 
  ), 
  
  // application-level parameters that can be accessed
  // using Yii::app()->params['paramName']
  'params' => array(
    'GlobalAttributions' => require (dirname(__FILE__) . '/GlobalAttributions.php'),
    'ApiResponseCodes' => require (dirname(__FILE__) . '/ApiResponseCodes.php'), 
    'SIG_PREFIX_KEY' => 'API_QW_WanZhu_Sig_Key_t201609101734',//申请签名的key
    'upload_token' => 'API_QW_WanZhu_upload_key',//上传token（用作file name）
    'upload_token_android' => 'API_QW_WanZhu_upload_key',
    
    //client静态密匙
    'client_ids' => array(
      1, //iPhone
      2, //Android
    ),
    'client_secrets' => array(
      1 => 'API_QW_WanZhu_client_secret', 
      2 => 'API_QW_WanZhu_client_secret',
    ),
   
    //微信开放平台配置
    'wx_open_config' => array(
      'appid' => 'wx76e66008755e6104',
      'app_secret' => '4b67d2feabe2b81287a80b8ab1e87cd2'
    ),
    //微信开放平台支付配置
    'wx_open_pay_config' => array(
      'WXPAY_APP_ID' => 'wx76e66008755e6104',
      'WXPAY_APP_SECRET' => '4b67d2feabe2b81287a80b8ab1e87cd2',
      'WXPAY_MCH_ID' => '1390719602',
      'WXPAY_API_KEY' => '08d92002fc1f1d44ce670bc1b4238cf7',
      'WXPAY_SSLCERT_PATH' => '',
      'WXPAY_SSLKEY_PATH' => '',
      'WXPAY_NOTIFY_URL' => 'http://121.199.76.16/v1.0.0/paying/wxpaycallback.json',
      'WXPAY_SERVER_IP' => ''
    ),
  )
);
