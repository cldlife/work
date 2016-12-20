use dev_wanzhu_thing;

ALTER TABLE `thing_thread1` ADD `extend_type` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '帖子扩展类型：1-我画你猜, ...' AFTER `attach_hashid`;
ALTER TABLE `thing_thread1` ADD `extend_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '扩展实体id' AFTER `extend_type`;