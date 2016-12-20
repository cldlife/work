use `dev_wanzhu_backend`;
INSERT INTO `bk_menus` (`id`, `parent_id`, `name`, `uri_alias`, `order_id`, `is_display`, `created_time`) VALUES
(5, 0, '谁是卧底管理', '', 5, 1, 1478613721),
(501, 5, '卧底词配置', 'gameset/spyword', 1, 1, 1478613721),
(502, 5, '游戏惩罚配置', 'gameset/punish', 2, 1, 1478613721),
(503, 5, '游戏机器人配置', 'gameset/pseudouser', 3, 1, 1478613721);

