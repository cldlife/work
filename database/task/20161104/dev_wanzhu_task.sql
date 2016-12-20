CREATE DATABASE `dev_wanzhu_task`;
use `dev_wanzhu_task`;

CREATE TABLE IF NOT EXISTS `task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增id',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '任务类型:send_msg, join_room ...',
  `run_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '任务运行时间',
  `workload` text NOT NULL COMMENT '任务数据',
  `status` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '任务状态:0,待处理;1,已处理;2,处理失败',
  `created_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_st_runt` (`status` DESC, `run_time` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='wanzhu定时任务表';

