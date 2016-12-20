<?php
//This is the main Web application configuration. Any writable
//CWebApplication properties can be configured here.
return array(
  'name' => 'WEIGAME',
  'basePath' => dirname(__DIR__),
  'runtimePath' => APP_TMP_RUNTIME_DIR,
  'defaultController' => 'gamelist',
  
  //预加载目录/文件
  'import' => array(
    'lib.utils.*',
    'src.ServiceFactory',
    'application.controllers.BaseController',
  ),
  
  //组件设置
  'components' => array(
    'urlManager' => array(
      'urlFormat' => 'path',
      'showScriptName' => false,
      'urlSuffix' => '.html',
      'rules' => array(
        'index' => '/gamelist/index',
        'glrd' => '/redirect/index',
        '<control_name:([a-z0-9]+)>/know<fromkey:([0-9]+)>-gt<type:([0-9]+)>-gp<level:([0-9]+)>' => '/know/qaquestion',
        '<control_name:([a-z0-9]+)>/know<fromkey:([0-9]+)>-gt<type:([0-9]+)>-gp<level:([0-9]+)>/tm<qid:([0-9]+)>' => '/know/qaanswer',
        '<control_name:([a-z0-9]+)>/know<fromkey:([0-9]+)>-gt<type:([0-9]+)>-gp<level:([0-9]+)>/tm<qid:([0-9]+)>/qa' => '/know/qatrueanswer',
        //支付安全目录 http://***/wxpay/
        'wxpay/knowqa' => '/know/qatrueanswer',
        'wxpay/knowdelanswer' => '/know/delanswer',
      ) 
    ),
    
    'request' => array(
      //SCRF安全验证（白名单不作csrf验证，注：必须全小写）
      'enableCsrfValidation' => true,
      'noCsrfValidationRoutes' => array(
        'know/wxpayviewanswer',
        'know/wxpaydelanswer',
      ),
    ),
    
    'errorHandler' => array(
      //use 'site/error' action to display errors
      'errorAction' => 'site/error' 
    ) 
  ),
  
  //application-level parameters that can be accessed
  //using Yii::app()->params['paramName']
  'params' => array(
    'qaInfo' => require (dirname(__FILE__) . '/QaInfoConfig.php'),
    'paycallbackconfig' => require (dirname(__FILE__) . '/PayCallbackConfig.php'),
    'wxPayNotifyUrlDomain' => 'http://sandbox.notify.weileiba.cn',
    'wxPayConfig' => array(
      //后宫服务号(授权+支付)
      'wx318680eae930969f' => array(
        'WXPAY_APP_ID' => 'wx318680eae930969f',
        'WXPAY_APP_SECRET' => '5961e808c2339acc0aecd53802798f3c',
        'WXPAY_MCH_ID' => '1254323501',
        'WXPAY_API_KEY' => '374d355c3a008a02e3cb5b40f2b9628c',
        'WXPAY_SSLCERT_PATH' => APP_CONFIG_THIRDSDK_DIR . '/wxpay/apiclient_cert.pem',
        'WXPAY_SSLKEY_PATH' => APP_CONFIG_THIRDSDK_DIR . '/wxpay/apiclient_key.pem',
        'WXPAY_NOTIFY_URL' => '',
        'WXPAY_SERVER_IP' => '121.199.76.16',
      ),
    )
  )
);
