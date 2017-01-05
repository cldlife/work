user dev_cldife_backend

CREATE TABLE `bk_menus` (
  `id` smallint(5) unsigned NOT NULL,
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单id',
  `name` varchar(20) NOT NULL COMMENT '菜单名称',
  `uri_alias` varchar(40) NOT NULL COMMENT '菜单URI别名',
  `order_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序权重值',
  `is_display` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示，0-否，1-是',
  `created_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_ordid` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台管理菜单配置';
