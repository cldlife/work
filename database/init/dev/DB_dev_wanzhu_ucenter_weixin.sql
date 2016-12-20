CREATE DATABASE `dev_wanzhu_ucenter_weixin`;
use `dev_wanzhu_ucenter_weixin`;
 
CREATE TABLE IF NOT EXISTS `user_weixin_openids1` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `appid` varchar(20) NOT NULL COMMENT '微信appid',
  `openid` varchar(32) NOT NULL COMMENT '微信openid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信openid用户关系表';
ALTER TABLE `user_weixin_openids1`
  ADD PRIMARY KEY (`uid`,`appid`);
  
  
CREATE TABLE IF NOT EXISTS `user_weixin_openid_index1` (
  `openid` varchar(32) NOT NULL COMMENT '微信openid',
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信用户索引表';
ALTER TABLE `user_weixin_openid_index1`
 ADD PRIMARY KEY (`openid`);

 
CREATE TABLE IF NOT EXISTS `user_weixin_unionid_index1` (
  `unionid` varchar(32) NOT NULL COMMENT '微信unionid',
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信unionid用户索引表';
ALTER TABLE `user_weixin_unionid_index1`
  ADD PRIMARY KEY (`unionid`);


CREATE TABLE IF NOT EXISTS `user_weixin_info1` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `openid` varchar(32) NOT NULL COMMENT '微信openid',
  `nickname` varchar(60) CHARACTER SET utf8mb4 NOT NULL COMMENT '用户对应的weixin的nickname',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `access_token` varchar(200) NOT NULL DEFAULT '' COMMENT '该weixin用户对应的授权token',
  `expires_in` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '有效时间',
  `refresh_token` varchar(200) NOT NULL DEFAULT '' COMMENT '该weixin用户对应的刷新授权token',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户绑定状态：0-未绑定，1 已绑定，2-解除绑定',
  `sync_share` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '同步分享设置：0-未开启同步，1-开启同步',
  `wx_from` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '注册来源：1-app，2-公众号，3-web',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` int(10) unsigned NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信用户信息表';
ALTER TABLE `user_weixin_info1`
  ADD PRIMARY KEY (`uid`) USING BTREE;
