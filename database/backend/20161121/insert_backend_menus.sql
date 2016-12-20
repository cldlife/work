use `dev_wanzhu_backend`;

UPDATE bk_menus SET id = id+7 WHERE (id > 3 AND id < 100) ORDER BY id DESC;
UPDATE bk_menus SET id = id+700 WHERE (id > 300 AND id < 600) ORDER BY id DESC;
UPDATE bk_menus SET parent_id = parent_id+7 WHERE parent_id > 3;

ALTER TABLE `bk_menus` CHANGE `uri_alias` `uri_alias` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '菜单URI别名';

INSERT INTO `bk_menus` (`id`, `parent_id`, `name`, `uri_alias`, `order_id`, `is_display`, `created_time`) VALUES
(4, 0, '运营工具', '', 3, 1, 1429779451),
(401, 4, '文件资源管理', 'attachments/list', 1, 1, 1429779451),
(402, 4, '批量上传管理', 'attachments/upload', 2, 1, 1429779451),
(403, 4, '批量上传', 'attachments/upload/file', 2, 0, 1429779451);

