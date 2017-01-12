<?php
return array (
		'config' => array (
				'master' => array (
						'dbname' => 'dev_wanzhu_config',
						'host' => 'dev_wanzhu_DB1',
						'user' => 'root',
						'password' => ''
				),
				'slave' => array ()
		),
		//后台
		'backend' => array (
				'master' => array (
						'dbname' => 'dev_cldlife_backend',
						'host' => 'dev_wanzhu_DB1',
						'user' => 'root',
						'password' => '',
						'character' => 'utf8mb4'
				),
				'slave' => array ()
		),
		//文章
		'article' => array (
				'master' => array (
						'dbname' => 'dev_cldlife_article',
						'host' => 'dev_wanzhu_DB1',
						'user' => 'root',
						'password' => '',
						'character' => 'utf8mb4'
				),
				'slave' => array ()
		)
);
