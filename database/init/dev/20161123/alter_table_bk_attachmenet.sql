use dev_wanzhu_backend;

ALTER TABLE `bk_attachment` ADD `local_name` varchar(60) NOT NULL COMMENT '附件文件本地名称' AFTER `type`;