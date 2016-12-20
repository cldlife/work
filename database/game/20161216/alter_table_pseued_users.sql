use `dev_wanzhu_game`;

ALTER TABLE `pseudo_users` 
  ADD `gender` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '性别: 0-保密, 1-男, 2-女' AFTER `is_using`,
  ADD `birthday` INT(10) UNSIGNED NOT NULL DEFAULT '19700101' COMMENT '出生日期，如19700101' AFTER `gender`;

