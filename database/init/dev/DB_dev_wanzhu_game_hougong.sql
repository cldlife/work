CREATE DATABASE `dev_wanzhu_game_hougong`;
use `dev_wanzhu_game_hougong`;
 
CREATE TABLE IF NOT EXISTS `hg_relation1` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `relation_uid` bigint(20) unsigned NOT NULL COMMENT '关系uid',
  `level` tinyint(1) unsigned NOT NULL COMMENT '关系级别:1-主人;2-奴隶',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后宫主人/奴隶关系表';
ALTER TABLE `hg_relation1`
 ADD PRIMARY KEY (`uid`,`relation_uid`), 
 ADD KEY `idx_uid_level_crt` (`uid`,`level`,`created_time` DESC);

CREATE TABLE IF NOT EXISTS `hg_task1` (
`id` bigint(10) unsigned NOT NULL COMMENT '任务id',
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `task` varchar(60) NOT NULL DEFAULT '' COMMENT '派发任务内容 （json数据）',
  `total_coins` int(10) NOT NULL DEFAULT '0' COMMENT '总金币数',
  `remain_coins` int(10) NOT NULL DEFAULT '0' COMMENT '抢后剩余金币数',
  `coins_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '金币状态：0-未抢完,1-被抢完',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '任务状态：0-休息中,1-任务中,2-任务完成',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` int(10) unsigned NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后宫任务表' AUTO_INCREMENT=1 ;
ALTER TABLE `hg_task1`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_uid_crt` (`uid`,`created_time` DESC);
ALTER TABLE `hg_task1`
MODIFY `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '任务id';

CREATE TABLE IF NOT EXISTS `hg_task_getcoin_users1` (
  `task_id` int(10) unsigned NOT NULL COMMENT '任务id',
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后宫任务抢金币用户记录表(task_id分表)';
ALTER TABLE `hg_task_getcoin_users1`
 ADD PRIMARY KEY (`task_id`,`uid`);

CREATE TABLE IF NOT EXISTS `hg_visitor1` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `visitor_uid` bigint(20) unsigned NOT NULL COMMENT '访客uid',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0-未查看,1-已查看',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` int(10) unsigned NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后宫访客记录表';
ALTER TABLE `hg_visitor1`
 ADD PRIMARY KEY (`uid`,`visitor_uid`), 
 ADD KEY `idx_uid_upt` (`uid`,`updated_time` DESC);

CREATE TABLE IF NOT EXISTS `hg_notice1` (
`id` int(10) unsigned NOT NULL,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `content` text NOT NULL COMMENT '通知内容 (json数据)',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0-未读,1-已读',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后宫消息通知表' AUTO_INCREMENT=1 ;
ALTER TABLE `hg_notice1`
 ADD PRIMARY KEY (`id`), 
 ADD KEY `idx_uid_crt` (`uid`,`created_time` DESC);
ALTER TABLE `hg_notice1`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;



