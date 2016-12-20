use `dev_wanzhu_game`;

ALTER TABLE `room` ADD `gid` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '游戏类型(与game_id不同): 1-听歌曲猜歌名,4-谁是卧底,5-你画我猜...' AFTER `id`;
ALTER TABLE `room` DROP INDEX `idx_st_crt`, ADD INDEX `idx_st_gid_crt` (`status`, `gid`, `created_time`) USING BTREE;

ALTER TABLE `room_user` ADD `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '用户状态: 0-离线,1-未准备,2-已准备' AFTER `is_robot`;

RENAME TABLE `game_setting` TO `game_spy_setting`;

RENAME TABLE `game_states` TO `game_spy_states`;

