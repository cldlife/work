use dev_wanzhu_ucenter_mine;
CREATE TABLE IF NOT EXISTS `um_disappear_user1` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `disappear_uid` bigint(20) unsigned NOT NULL COMMENT '被屏蔽的用户uid',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='我的用户屏蔽记录表';
ALTER TABLE `um_disappear_user1`
  ADD PRIMARY KEY (`uid`,`disappear_uid`);