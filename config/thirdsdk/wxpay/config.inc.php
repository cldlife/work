<?php
/**
 * @desc 微信支付配置文件
 */

//微信支付APP ID(mp.weixin.com)
define('WXPAY_APP_ID', '');

//微信支付APP SECRET
define('WXPAY_APP_SECRET', '');

//微信支付商户ID
define('WXPAY_MCH_ID', '');

//微信支付API密钥
define('WXPAY_API_KEY', '');

//微信支付证书CERT地址
define('WXPAY_SSLCERT_PATH', APP_CONFIG_THIRDSDK_DIR . '/wxpay/apiclient_cert.pem');

//微信支付证书KEY地址
define('WXPAY_SSLKEY_PATH', APP_CONFIG_THIRDSDK_DIR . '/wxpay/apiclient_key.pem');

//支付结果回调地址, 可在请求时指定
define('WXPAY_NOTIFY_URL', '');

//调用接口的服务端ip
define('WXPAY_SERVER_IP', '');

