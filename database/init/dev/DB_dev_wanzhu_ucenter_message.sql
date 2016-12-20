CREATE DATABASE `dev_wanzhu_ucenter_message`;
use `dev_wanzhu_ucenter_message`;


CREATE TABLE IF NOT EXISTS `message_smscode` (
  `id` int(10) unsigned NOT NULL,
  `mobile` bigint(11) unsigned NOT NULL COMMENT '手机号',
  `type` tinyint(1) unsigned NOT NULL COMMENT '验证码类型：1-注册，2-找回密码，3-绑定手机，4-更新绑定手机',
  `code` int(6) unsigned NOT NULL COMMENT '验证码',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='短信验证码表';
ALTER TABLE `message_smscode`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mobile_type` (`mobile`,`type`) USING BTREE;
ALTER TABLE `message_smscode`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
  
  
CREATE TABLE IF NOT EXISTS `message_rc_user_token1` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `token` varchar(256) NOT NULL COMMENT '融云用户token',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` int(10) unsigned NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='融云用户token关系表';
ALTER TABLE `message_rc_user_token1`
  ADD PRIMARY KEY (`uid`);