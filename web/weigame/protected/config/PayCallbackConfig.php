<?php
/**
 * @desc 小游戏支付回调url
 * 为单个游戏配置, 在数组中添加URL
 * 域名级别分组：'/topic/qa'
 */
return array(
  //支付页uris
  '/wxpay/knowqa',
  '/wxpay/knowdelanswer',
    
  //支付接口和回调uris
  '/know/wxpay',
  '/know/wxpayviewanswer',
  '/know/wxpaydelanswer',
);
