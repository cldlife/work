use dev_wanzhu_thing;

CREATE TABLE IF NOT EXISTS `thing_thread_vote_user1` (
  `tid` bigint(20) unsigned NOT NULL COMMENT '食记id',
  `uid` bigint(20) unsigned NOT NULL COMMENT '点赞用户uid',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='帖子点赞用户关系表';
ALTER TABLE `thing_thread_vote_user1`
  ADD PRIMARY KEY (`tid`,`uid`) USING BTREE,
  ADD KEY `idx_tid_crt` (`tid`,`created_time` DESC) USING BTREE;
