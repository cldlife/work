CREATE DATABASE `dev_wanzhu_ucenter_session`;
use `dev_wanzhu_ucenter_session`;


CREATE TABLE IF NOT EXISTS `user_session1` (
  `uid` bigint(20) unsigned NOT NULL,
  `sid` varchar(32) NOT NULL COMMENT '会话id',
  `expires_in` int(10) NOT NULL DEFAULT '0' COMMENT '有效期, 0-永不过期',
  `created_time` int(10) unsigned NOT NULL COMMENT '第1次登录时间',
  `updated_time` int(10) unsigned NOT NULL COMMENT '最后登录时间'
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='用户会话表';
ALTER TABLE `user_session1`
  ADD PRIMARY KEY (`uid`);
