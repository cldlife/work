use `dev_wanzhu_backend`;

INSERT INTO `bk_menus` (`id`, `parent_id`, `name`, `uri_alias`, `order_id`, `is_display`, `created_time`)
VALUES
  (5,0,'你懂我吗系列管理','',5,1,1429779451),
  (501,5,'公众号管理','mp/index',1,1,1429779451),
  (503,5,'分组管理','domain/groupList',1,1,1429779451),
  (504,5,'域名管理','domain/domain',1,1,1429779451),
  (505,5,'游戏管理','know/index',2,1,0),
  (506,5,'支付管理','know/questionanswer',2,1,0);
