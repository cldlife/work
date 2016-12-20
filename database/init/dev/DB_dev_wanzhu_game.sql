CREATE DATABASE `dev_wanzhu_game`;
use `dev_wanzhu_game`;

CREATE TABLE IF NOT EXISTS `room` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增id',
  `number` smallint(4) unsigned NOT NULL DEFAULT 0 COMMENT '四位房间号',
  `game_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '正在玩的游戏主键id',
  `host` bigint(20) unsigned NOT NULL COMMENT '房主uid',
  `players` smallint(5) unsigned NOT NULL COMMENT '当前房间里玩家人数',
  `ticket` varchar(255) NOT NULL DEFAULT '' COMMENT '带房间号参数的微信二维码链接,默认有效期30天',
  `status` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '房间状态:0,已解散;1,游戏中;2,未开始;倒序排列',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_st_crt` (`status` DESC, `created_time` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='wanzhu房间表';

CREATE TABLE IF NOT EXISTS `room_user` (
  `room_id` int(10) unsigned NOT NULL COMMENT '房间id',
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户id',
  PRIMARY KEY (`room_id`, `uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='wanzhu房间/玩家索引表';
ALTER TABLE `room_user` DROP PRIMARY KEY;
ALTER TABLE `room_user` ADD `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键自增id' FIRST, ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `idx_uid_rid` (`uid`, `room_id`);

CREATE TABLE IF NOT EXISTS `room_number` (
  `number` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '四位房间号:1000-9999',
  `room_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '房间主键自增的id',
  PRIMARY KEY (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8 COMMENT='wanzhu房间/号码索引表';

CREATE TABLE IF NOT EXISTS `game` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增id',
  `room_id` int(10) unsigned NOT NULL COMMENT '房间的主键id',
  `type` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '游戏类型:1,我是卧底;2听歌猜名等...',
  `status` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '游戏状态:0,已结束;1,进行中;',
  `info` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT 'json编码数据,如卧底身份等',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_st_crt` (`status`, `created_time` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='wanzhu游戏表';

CREATE TABLE IF NOT EXISTS `game_states` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增id',
  `game_id` int(10) unsigned NOT NULL COMMENT '游戏的主键id',
  `uid` bigint(20) unsigned NOT NULL COMMENT '发出此状态的用户id',
  `round` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '游戏第几轮,如:第1轮发言,投票,发言;第2轮发言,投票,发言...',
  `action` varchar(20) NOT NULL COMMENT '该步骤类别:speak, vote, sum...',
  `state` varchar(255) NOT NULL COMMENT 'json编码,游戏当前状态:当前的玩家们,发言人/投票人,下一步操作等',
  `content` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '该步骤具体内容:发言内容,投票对象等',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_gid_crt_act` (`game_id`, `created_time` DESC, `action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='wanzhu游戏状态表';


CREATE TABLE IF NOT EXISTS `game_tgqcqm_tms` (
  `tm_id` int(10) unsigned NOT NULL COMMENT '题目id',
  `uid` bigint(20) unsigned NOT NULL COMMENT '贡献题目的用户uid',
  `song_name` varchar(40) NOT NULL COMMENT '歌曲名称',
  `singer` varchar(40) NOT NULL DEFAULT '' COMMENT '歌手名',
  `uri` varchar(255) NOT NULL COMMENT '下载uri',
  `duration` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '歌曲时长(秒)',
  `status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态，0-审核中，1-已通过审核',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` int(10) unsigned NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='听歌曲猜歌名题目库';
ALTER TABLE `game_tgqcqm_tms`
  ADD PRIMARY KEY (`tm_id`);
ALTER TABLE `game_tgqcqm_tms`
  MODIFY `tm_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '题目id';
ALTER TABLE `game_tgqcqm_tms` ADD INDEX `idx_stus_songname` (`status`, `song_name`);


CREATE TABLE IF NOT EXISTS `game_tgqcqm_right_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tm_id` int(10) unsigned NOT NULL COMMENT '题目id',
  `uid` bigint(20) unsigned NOT NULL COMMENT '答题用户uid',
  `rank` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '当前轮排名',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='听歌曲猜歌名答对用户记录';
