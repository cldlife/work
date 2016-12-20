use dev_wanzhu_webapp;

CREATE TABLE IF NOT EXISTS `wp_paints_status` (
  `pp_id` bigint(20) unsigned NOT NULL COMMENT '照片id',
  `paintings` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '画作数',
  `votes` int(10) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0照片,1画作',
  `created_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`pp_id`),
  KEY `idx_type_votes` (`type`, `votes` DESC) USING BTREE,
  KEY `idx_type_crt` (`type`,`created_time` DESC) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='照片状态记录表';

CREATE TABLE IF NOT EXISTS `wp_paints` (
  `pp_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '照片/画作id',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型：0-照片,1-画作',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户uid',
  `file_uri` varchar(255) NOT NULL DEFAULT '' COMMENT '图片uri',
  `relation_tid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '关系帖子tid',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0-正常,1-删除',
  `relation_ppid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联pp_id',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `updated_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`pp_id`),
  KEY `idx_type_crt` (`type`,`created_time` DESC),
  KEY `idx_relation_ppid_crt` (`relation_ppid`,`created_time` DESC)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='照片/画作记录表';