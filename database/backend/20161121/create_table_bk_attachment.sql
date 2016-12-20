use `dev_wanzhu_backend`;

CREATE TABLE IF NOT EXISTS `bk_attachment` (
  `aid` bigint(20) unsigned NOT NULL COMMENT '附件id',
  `type` varchar(10) NOT NULL DEFAULT '0' COMMENT '文件类型：jpg,png,jpeg,css',
  `file_uri` varchar(40) NOT NULL DEFAULT '' COMMENT '附件文件路径',
  `file_name` varchar(60) NOT NULL DEFAULT '' COMMENT '附件文件名称',
  `width` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '图片宽',
  `height` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '图片高',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '附件状态：0-正常状态, 1-删除',
  `created_time` int(10) NOT NULL COMMENT '创建时间',
  `updated_time` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`aid`),
  KEY `idx_sta_crt` (`status`,`created_time` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台附件表';
