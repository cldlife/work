CREATE DATABASE `dev_wanzhu_backend`;
use `dev_wanzhu_backend`;


CREATE TABLE IF NOT EXISTS `bk_menus` (
  `id` smallint(5) unsigned NOT NULL,
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单id',
  `name` varchar(20) NOT NULL COMMENT '菜单名称',
  `uri_alias` varchar(20) NOT NULL COMMENT '菜单URI别名',
  `order_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序权重值',
  `is_display` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示，0-否，1-是',
  `created_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_ordid` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台管理菜单配置表';

INSERT INTO `bk_menus` (`id`, `parent_id`, `name`, `uri_alias`, `order_id`, `is_display`, `created_time`) VALUES
(1, 0, '系统设置', '', 1, 1, 1429779451),
(2, 0, '后台权限管理', '', 2, 1, 1429779451),
(3, 0, '用户管理', '', 3, 1, 1429779451),
(101, 1, '基本设置', 'setting/base', 1, 1, 1429779451),
(102, 1, '关键词设置', 'setting/keyword', 2, 1, 1429779451),
(201, 2, '权限点管理', 'permission/set', 1, 1, 1429779451),
(202, 2, '管理员账号设置', 'permission/newuser', 2, 1, 1429779451),
(203, 2, '马甲账号管理', 'permission/vestuser', 3, 1, 1429779451),
(301, 3, '查找用户', 'user/search', 1, 1, 1429779451);

INSERT INTO `bk_menus` (`id`, `parent_id`, `name`, `uri_alias`, `order_id`, `is_display`, `created_time`) VALUES
(4, 0, '听歌曲猜歌名', '', 3, 1, 1429779451),
(401, 4, '歌曲审核管理', 'songs/audit', 1, 1, 1429779451),
(402, 4, '批量上传歌曲', 'songs/upload', 2, 1, 1429779451);

CREATE TABLE IF NOT EXISTS `bk_user` (
  `uid` bigint(20) unsigned NOT NULL,
  `admin_name` varchar(20) NOT NULL COMMENT '管理员姓名',
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是管理员：0-否, 1-是',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员设置表';

INSERT INTO `bk_user` (`uid`, `admin_name`, `is_admin`, `last_login_time`, `created_time`, `updated_time`) VALUES
(1, 'Dr.Vegapunk', 1, 1456222911, 1442500656, 1456222919);

CREATE TABLE IF NOT EXISTS `bk_user_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `permission_id` smallint(5) unsigned NOT NULL COMMENT '权限点id(管理菜单id)',
  PRIMARY KEY (`id`),
  KEY `idx_UidPermid` (`uid`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理权限设置表';

CREATE TABLE IF NOT EXISTS `bk_user_vests` (
  `id` int(10) unsigned NOT NULL,
  `uid` bigint(20) unsigned NOT NULL,
  `online_uid` bigint(20) unsigned NOT NULL COMMENT '关联的线上用户uid',
  `is_robot_vest` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '设置为点赞机器人马甲',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` int(20) unsigned NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员马甲账号表';
ALTER TABLE `bk_user_vests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_uid_ouid` (`uid`,`online_uid`),
  ADD KEY `idx_ouid` (`online_uid`);
ALTER TABLE `bk_user_vests`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
  

CREATE TABLE IF NOT EXISTS `bk_feedback` (
  `id` int(10) unsigned NOT NULL,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户id',
  `content` varchar(200) CHARACTER SET utf8mb4 NOT NULL COMMENT '反馈内容',
  `contact_info` varchar(40) NOT NULL COMMENT '联系方式',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '处理状态：0-未处理，1-已处理',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` INT(10) UNSIGNED NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户反馈表' AUTO_INCREMENT=1 ;
ALTER TABLE `bk_feedback`
 ADD PRIMARY KEY (`id`);
ALTER TABLE `bk_feedback`
 MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

 
CREATE TABLE IF NOT EXISTS `bk_report` (
  `id` int(10) unsigned NOT NULL,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户id',
  `report_id` tinyint(1) unsigned NOT NULL COMMENT '类型：1-爆照 2-用户主页',
  `relation_id` bigint(20) unsigned NOT NULL COMMENT '关联id，帖子tid或用户uid',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '处理状态：0-未处理，1-已处理',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` INT(10) UNSIGNED NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户举报表' AUTO_INCREMENT=1 ;
ALTER TABLE `bk_report`
 ADD PRIMARY KEY (`id`);
ALTER TABLE `bk_report`
 MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

