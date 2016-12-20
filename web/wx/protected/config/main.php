<?php
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
  'name' => 'WX',
  'basePath' => dirname(__DIR__),
  'runtimePath' => APP_TMP_RUNTIME_DIR,

  // 预加载目录/文件
  'import' => array(
    'lib.utils.*',
    'lib.games.*',
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
        'room/invite/<roomid:([0-9]+)>' => '/room/invite',
        'hougong/index/u<uid:([0-9]+)>' => '/hougong/index',
        'hougong/mine/u<uid:([0-9]+)>' => '/hougong/mine/index'

      )
    ),

    'request' => array(
      // SCRF 安全过滤
      'enableCsrfValidation' => true,
      'noCsrfValidationRoutes' => array(
        'wx/wanzhu',
        'hougong/weixin/api',
        'usercenter/wxpayrecharge',
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
    'spyWords' => require (dirname(__FILE__) . '/SpyWordsConfig.php'),
    'pseudoUsers' => require (dirname(__FILE__) . '/pseudoUsers.php'),
    'openWeixinWebUrl' => 'https://open.weixin.qq.com/connect/qrconnect?appid=wx318680eae930969f&redirect_uri={redirect_uri}&response_type=code&scope=snsapi_userinfo',
    'openWeixinWapUrl' => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx318680eae930969f&redirect_uri={redirect_uri}&response_type=code&scope=snsapi_userinfo',
    'weixinConfig' => array( //  TODO shell/sh
      'wanZhuyule' => array(
        'WEIXIN_APP_ID' => 'wx318680eae930969f',
        'WEIXIN_APP_SECRET' => '5961e808c2339acc0aecd53802798f3c',
        'WEIXIN_AES_KEY' => 'CIwJbp3HMD9Vx7tfRJ9YByKqNFl3chiiMkepAuvb9Jh',
        'WEIXIN_ACCOUNT_NAME' => 'gh_85b2af0e4fb4',
        'WEIXIN_SERVER_TOKEN' => 'HELLOQUANWAI',
      ),
      'houGong' => array(
        'WEIXIN_APP_ID' => 'wx318680eae930969f',
        'WEIXIN_APP_SECRET' => '5961e808c2339acc0aecd53802798f3c',
        'WEIXIN_AES_KEY' => 'CIwJbp3HMD9Vx7tfRJ9YByKqNFl3chiiMkepAuvb9Jh',
        'WEIXIN_ACCOUNT_NAME' => 'gh_85b2af0e4fb4',
        'WEIXIN_SERVER_TOKEN' => 'HELLOQUANWAI',
      ),
    ),
    'wxPayConfig' => array(
      'WXPAY_APP_ID' => 'wx318680eae930969f',
      'WXPAY_APP_SECRET' => '5961e808c2339acc0aecd53802798f3c',
      'WXPAY_MCH_ID' => '1254323501',
      'WXPAY_API_KEY' => '374d355c3a008a02e3cb5b40f2b9628c',
      'WXPAY_SSLCERT_PATH' => '', //APP_CONFIG_THIRDSDK_DIR . '/wxpay/apiclient_cert.pem',
      'WXPAY_SSLKEY_PATH' => '', //APP_CONFIG_THIRDSDK_DIR . '/wxpay/apiclient_key.pem',
      'WXPAY_NOTIFY_URL' => 'http://wx.wanzhuwenhua.com/usercenter/wxpayrecharge.html',
      'WXPAY_SERVER_IP' => '',
    ),
  )
);
