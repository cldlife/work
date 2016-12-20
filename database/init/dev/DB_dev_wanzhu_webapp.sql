CREATE DATABASE `dev_wanzhu_webapp`;
use dev_wanzhu_webapp;

CREATE TABLE IF NOT EXISTS `wp_seven_deadly_sin` (
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字MD5加密',
  `content` varchar(20) NOT NULL DEFAULT '' COMMENT '七宗罪7个数值，用_链接',
  KEY `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='七宗罪信息表';