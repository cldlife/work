<?php
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array (
		'name' => 'WX',
		'basePath' => dirname ( __DIR__ ),
		'runtimePath' => APP_TMP_RUNTIME_DIR,
		'defaultController' => 'index',

		// 预加载目录/文件
		'import' => array (
				'lib.utils.*',
				'src.ServiceFactory',
				'application.controllers.BaseController'
		),

		// 组件设置
		'components' => array (
				'urlManager' => array (
						'urlFormat' => 'path',
						'showScriptName' => false,
						'urlSuffix' => '.html',
						'rules' => array (
								'<control_name:([a-z0-9]+)>/know<fromkey:([0-9]+)>-gt<type:([0-9]+)>-gp<level:([0-9]+)>' => '/know/qaquestion'
						)
				),

				'request' => array (
						// SCRF安全验证（白名单不作csrf验证，注：必须全小写）
						'enableCsrfValidation' => true,
						'noCsrfValidationRoutes' => array (
								'know/wxpayviewanswer',
								'know/wxpaydelanswer'
						)
				),

				'errorHandler' => array (
						// use 'site/error' action to display errors
						'errorAction' => 'site/error'
				)
		),

		// application-level parameters that can be accessed
		// using Yii::app()->params['paramName']
		'params' => array ()
);
// 'qaInfo' => require (dirname(__FILE__) . '/QaInfoConfig.php'),
// 'paycallbackconfig' => require (dirname(__FILE__) . '/PayCallbackConfig.php'),


