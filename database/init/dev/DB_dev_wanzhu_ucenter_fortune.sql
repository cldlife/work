CREATE DATABASE `dev_wanzhu_ucenter_fortune`;
use `dev_wanzhu_ucenter_fortune`;

/*积分流水*/
CREATE TABLE IF NOT EXISTS `uf_point1` (
  `id` int(10) unsigned NOT NULL,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `rule_id` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `point` smallint(5) NOT NULL DEFAULT '0' COMMENT '积分数，加分+或减分-',
  `reason` varchar(100) NOT NULL DEFAULT '' COMMENT '规则以外的，需填写加减积分理由',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户积分记录表' AUTO_INCREMENT=1 ;
ALTER TABLE `uf_point1`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_uid_crt` (`uid`,`created_time` DESC);
ALTER TABLE `uf_point1`
 MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

/*玫瑰流水*/
 CREATE TABLE IF NOT EXISTS `uf_rose1` (
`id` int(10) unsigned NOT NULL,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `rule_id` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `rose` smallint(5) NOT NULL DEFAULT '0' COMMENT '玫瑰数，加+或减-',
  `reason` varchar(100) NOT NULL DEFAULT '' COMMENT '规则以外的，需填写加减玫瑰理由',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户玫瑰记录表' AUTO_INCREMENT=1 ;
ALTER TABLE `uf_rose1`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_uid_crt` (`uid`,`created_time` DESC);
ALTER TABLE `uf_rose1`
 MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
 
/*金币流水*/
CREATE TABLE IF NOT EXISTS `uf_coin1` (
`id` int(10) unsigned NOT NULL,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `rule_id` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `coin` smallint(5) NOT NULL DEFAULT '0' COMMENT '金币数，加金币+或减金币-',
  `reason` varchar(100) NOT NULL DEFAULT '' COMMENT '规则以外的，需填写加减金币理由',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户金币记录表' AUTO_INCREMENT=1 ;
ALTER TABLE `uf_coin1`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_uid_crt` (`uid`,`created_time` DESC);
ALTER TABLE `uf_coin1`
 MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

 /*身价流水*/
CREATE TABLE IF NOT EXISTS `uf_values1` (
  `id` int(10) unsigned NOT NULL,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户uid',
  `rule_id` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `values` smallint(5) NOT NULL DEFAULT '0' COMMENT '身价数，加身价+或身价-',
  `reason` varchar(100) NOT NULL DEFAULT '' COMMENT '规则以外的，需填写加减理由',
  `created_time` int(10) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户金币记录表' AUTO_INCREMENT=1 ;
ALTER TABLE `uf_values1`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_uid_crt` (`uid`,`created_time` DESC);
ALTER TABLE `uf_values1`
 MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

/*用户状态表*/
CREATE TABLE IF NOT EXISTS `uf_status1` (
  `uid` bigint(20) unsigned NOT NULL,
  `points` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分数',
  `coins` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币数',
  `roses` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '玫瑰数',
  `values` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '身价金币数',
  `friending_roses` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '加好友所需玫瑰数，0-无',
  `is_setted_passwd` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否设置密码：0-否，1-是',
  `is_binded_mobile` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '手机绑定状态：0-未绑定，1-已绑定，2-更改绑定中',
  `is_need_edit` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要完善资料，0-否，1-是',
  `is_app_installed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有安装玩主app，0-否，1-是'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户状态表';
ALTER TABLE `uf_status1`
  ADD PRIMARY KEY (`uid`);
