use `dev_wanzhu_ucenter_account`;
INSERT INTO `user1` (`uid`, `nickname`, `password`, `private_key`, `mobile`, `avatar`, `gender`, `region`, `country_id`, `province_id`, `city_id`, `district_id`, `sign`, `birthday`, `reg_ip`, `reg_from`, `status`, `created_time`, `updated_time`) VALUES
(51000, '小主', 'e8ebb9d9be8f69ad00ba54ed878afb48', '5541c10509eb17b98f5a0d31171e211a', 0, 'http://s.wanzhucdn.com/ui/img/app/xiaozhu300.png', 1, '[]', 0, 0, 0, 0, '', 0, '127.0.0.1', 1, 0, 1476784012, 1476784012);
INSERT INTO `user_mobile_index1` (`mobile`, `uid`, `created_time`, `updated_time`) VALUES
(15000000000, 51000, 1476784012, 1476784012);

use `dev_wanzhu_ucenter_fortune`;
INSERT INTO `uf_status1` (`uid`, `points`, `coins`, `roses`, `values`, `privilege_public_num`, `friending_roses`, `is_setted_passwd`, `is_binded_mobile`, `is_need_edit`, `is_app_installed`) VALUES
(51000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

use `dev_wanzhu_backend`;
INSERT INTO `bk_user` (`uid`, `admin_name`, `is_admin`, `last_login_time`, `created_time`, `updated_time`) VALUES
(51000, '小主', 1, 1456222911, 1442500656, 1456222919);
