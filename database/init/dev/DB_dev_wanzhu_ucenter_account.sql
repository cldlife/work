CREATE DATABASE `dev_wanzhu_ucenter_account`;
use `dev_wanzhu_ucenter_account`;

CREATE TABLE IF NOT EXISTS `user1` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `nickname` varchar(60) CHARACTER SET utf8mb4 NOT NULL COMMENT '昵称',
  `password` varchar(32) NOT NULL COMMENT '用户密码',
  `private_key` varchar(32) NOT NULL COMMENT '用户密钥',
  `mobile` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '手机号',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别: 0-保密, 1-男, 2-女',
  `region` text NOT NULL COMMENT '所在地，json数据',
  `country_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '国家id',
  `province_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '省份ID',
  `city_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '城市ID',
  `district_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '地区id',
  `sign` varchar(40) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '个性签名',
  `birthday` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '出生日期，如19870724',
  `reg_ip` varchar(40) NOT NULL DEFAULT '' COMMENT '注册IP, 支持IPV6',
  `reg_from` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '注册来源，0-手机，1-微信，2-QQ，3-微博',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0-正常, 1-删除, 2-禁用',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` int(10) unsigned NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户基础资料表';
ALTER TABLE `user1`
 ADD PRIMARY KEY (`uid`);

 
CREATE TABLE IF NOT EXISTS `user_index` (
`uid` bigint(20) unsigned NOT NULL COMMENT '用户uid'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户uid步长表' AUTO_INCREMENT=1 ;
ALTER TABLE `user_index`
 ADD PRIMARY KEY (`uid`);
ALTER TABLE `user_index`
 MODIFY `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户uid',AUTO_INCREMENT=1;
 
 
CREATE TABLE IF NOT EXISTS `user_mobile_index1` (
  `mobile` bigint(11) unsigned NOT NULL COMMENT '手机号',
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` int(10) unsigned NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户手机索引表';
ALTER TABLE `user_mobile_index1`
 ADD PRIMARY KEY (`mobile`), ADD KEY `idx_mobile_uid` (`mobile`,`uid`);

 
CREATE TABLE IF NOT EXISTS `user_nickname_index1` (
  `nickname` varchar(30) CHARACTER SET utf8mb4 NOT NULL COMMENT '用户昵称',
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户昵称索引表（根据首字md5 hash分表）';
ALTER TABLE `user_nickname_index1`
 ADD UNIQUE KEY `idx_nickname` (`nickname`), ADD KEY `idx_uid` (`uid`);
 