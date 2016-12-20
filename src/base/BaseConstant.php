<?php
/**
 * @desc Base Constant
 * @author VegaPunk
 */
abstract class BaseConstant {

  //mem缓存时间
  const THIRTY_CACHE_TIME = 30;
  const FM_CACHE_TIME = 300;
  const CACHE_TIME = 1800;
  const ONE_HOUR_CACHE_TIME = 3600;
  const ONE_DAY_CACHE_TIME = 86400;
  
  //无数据时的缓存时间
  const NONE_CACHE_TIME = 5;
  
  //file缓存时间
  const FILE_CACHE_TIME = 1800;
  
  //cache节点
  const CACHE_NODE_WEBFRONT = 'webfront';
  const CACHE_NODE_BKADMIN = 'bkman';
  const CACHE_NODE_WEIGAME = 'weigame';

  //分表数
  const HASH_TABLE_NUM = 1;
  const LARGE_HASH_TABLE_NUM = 1;
  const LARGER_HASH_TABLE_NUM = 1;
  const LARGEST_HASH_TABLE_NUM = 1;
  
  //默认DB别名定义
  const DB_S_PRIFIX = APP_DB_PRIFIX;
  const DB_S_MASTER = 'master';
  const DB_S_SLAVE = 'slave';
  
  const DB_S_CONFIG = 'config';
  const DB_S_BACKEND = 'backend';
  const DB_S_THING = 'thing';
  const DB_S_GAME = 'game';
  const DB_S_GAME_HOUGONG = 'game_hougong';
  const DB_S_WEBAPP  = 'webapp';
  const DB_S_TASK = 'task';
  const DB_S_WEIGAME = 'weigame';
  
  const DB_S_UCENTER_ACCOUNT = 'ucenter_account';
  const DB_S_UCENTER_WEIXIN = 'ucenter_weixin';
  const DB_S_UCENTER_FORTUNE = 'ucenter_fortune';
  const DB_S_UCENTER_MESSAGE = 'ucenter_message';
  const DB_S_UCENTER_MINE = 'ucenter_mine';
  const DB_S_UCENTER_SESSION = 'ucenter_session';
  
  //gearman server node
  const GEARMAN_SERVER_NODE = 'default';
}
