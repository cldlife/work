CREATE DATABASE `dev_wanzhu_thing`;
use `dev_wanzhu_thing`;


CREATE TABLE IF NOT EXISTS `thing_attachment1` (
  `aid` bigint(20) unsigned NOT NULL COMMENT '附件id',
  `attach_hashid` bigint(20) unsigned NOT NULL COMMENT '附件hashid, 精确到毫秒数',
  `tid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '帖子id',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '文件类型：0-图片（默认），1-音频，2-视频',
  `file_uri` varchar(40) NOT NULL DEFAULT '' COMMENT '附件文件路径',
  `file_name` varchar(60) NOT NULL DEFAULT '' COMMENT '附件文件名称',
  `width` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '图片宽',
  `height` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '图片高',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '附件状态：0-正常状态, 1-删除, 2-审核中',
  `order_id` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '排序id，从小到大靠前',
  `created_time` int(10) NOT NULL COMMENT '创建时间',
  `updated_time` int(10) NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='附件表（根据attch_hashid分表）';
ALTER TABLE `thing_attachment1`
  ADD PRIMARY KEY (`aid`),
  ADD KEY `idx_tid_stus_oid` (`status`,`order_id` DESC) USING BTREE;

CREATE TABLE IF NOT EXISTS `thing_thread1` (
  `tid` bigint(20) unsigned NOT NULL COMMENT '食记id',
  `category` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '帖子类型：1-喊话，2-爆照',
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `content` text CHARACTER SET utf8mb4 NOT NULL COMMENT '食记内容',
  `attach_hashid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '附件hashid, 精确到毫秒数',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '食记状态：0-正常，1-删除，2-待审核',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` int(10) unsigned NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='帖子内容表';
ALTER TABLE `thing_thread1`
  ADD PRIMARY KEY (`tid`);
  
CREATE TABLE IF NOT EXISTS `thing_thread_status1` (
  `tid` bigint(20) unsigned NOT NULL COMMENT '食记id',
  `replies` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `votes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='状态表';
ALTER TABLE `thing_thread_status1`
  ADD PRIMARY KEY (`tid`);


CREATE TABLE IF NOT EXISTS `thing_thread_post1` (
  `pid` bigint(20) unsigned NOT NULL COMMENT '回复id',
  `tid` bigint(20) unsigned NOT NULL COMMENT '食记id',
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `content` text CHARACTER SET utf8mb4 NOT NULL COMMENT '食记内容',
  `replied_uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '被回复的用户uid',
  `is_invisible` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅自己可见：0-否，1-是',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0-正常，1-删除',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` int(10) unsigned NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='帖子评论表';
ALTER TABLE `thing_thread_post1`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `idx_tid_stus` (`tid`,`status`,`created_time` DESC);


CREATE TABLE IF NOT EXISTS `thing_thread_list` (
  `tid` bigint(20) unsigned NOT NULL COMMENT '食记id',
  `category` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '帖子类型，1-喊话，2-爆照',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='帖子索引表';
ALTER TABLE `thing_thread_list`
  ADD PRIMARY KEY (`tid`),
  ADD KEY `idx_category_crt` (`category`,`created_time` DESC) USING BTREE;