CREATE DATABASE `dev_wanzhu_ucenter_mine`;
use `dev_wanzhu_ucenter_mine`;


CREATE TABLE IF NOT EXISTS `um_threads1` (
  `uid` bigint(20) unsigned NOT NULL,
  `tid` bigint(20) unsigned NOT NULL COMMENT '食记id',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='我的食记表';
ALTER TABLE `um_threads1`
 ADD PRIMARY KEY (`uid`,`tid`) USING BTREE, 
 ADD KEY `idx_uid_srt` (`uid`,`created_time` DESC) USING BTREE;
 

CREATE TABLE IF NOT EXISTS `um_friends1` (
  `uid` bigint(20) unsigned NOT NULL,
  `friend_uid` bigint(20) unsigned NOT NULL COMMENT '好友的用户uid',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户好友关系表';
ALTER TABLE `um_friends1`
 ADD PRIMARY KEY (`uid`,`friend_uid`) USING BTREE, 
 ADD KEY `idx_uid_crt` (`uid`,`created_time` DESC) USING BTREE;
