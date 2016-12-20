<?php
/**
 * @desc 全局变量属性配置  
 */
return array(
  //金币购买列表
  'coins_list' => array(
    array('id' => 1, 'coin' => 188, 'point' => 0, 'fee' => '6.0', 'desc' => '', 'rule_id' => 23, 'privilege_public_num' => 0),
    array('id' => 2, 'coin' => 888, 'point' => 0, 'fee' => '18.0', 'desc' => '', 'rule_id' => 24, 'privilege_public_num' => 0),
    array('id' => 3, 'coin' => 5088, 'point' => 2000, 'fee' => '88.0', 'desc' => '+ 2000积分', 'rule_id' => 25, 'privilege_public_num' => 0),
    array('id' => 4, 'coin' => 23888, 'point' => 5000, 'fee' => '388.0', 'desc' => '+ 5000积分 + 一次喊话权', 'rule_id' => 26, 'privilege_public_num' => 1),
  ),
    
  //好友门槛设置列表
  'friending_roses' => array(
    array('id' => 1, 'roses' => 0,),
    array('id' => 2, 'roses' => 5),
    array('id' => 3, 'roses' => 10),
    array('id' => 4, 'roses' => 20),
    array('id' => 5, 'roses' => 50),
    array('id' => 6, 'roses' => 100),
  )
);
