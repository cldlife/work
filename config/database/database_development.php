<?php
return array(
  'config' => array(
    'master' => array('dbname' => 'dev_wanzhu_config', 'host' => 'dev_wanzhu_DB1', 'user' => 'root', 'password' => ''),
    'slave' => array(),
  ),
  'backend' => array(
    'master' => array('dbname' => 'dev_cldlife_backend', 'host' => 'dev_wanzhu_DB1', 'user' => 'root', 'password' => '', 'character' => 'utf8mb4'),
    'slave' => array(),
  ),
);
