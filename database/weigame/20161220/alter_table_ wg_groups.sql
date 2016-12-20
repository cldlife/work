use `dev_wanzhu_weigame`;

ALTER TABLE `wg_groups` ADD `is_move` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否可移动域名，0.不开启，1.开启' AFTER `is_twodomain`;
