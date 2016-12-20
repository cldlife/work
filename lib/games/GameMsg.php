<?php
/**
 * @desc 游戏消息类
 * 获取消息,发送消息,所有的消息都放在这里
 */
class GameMsg {

  private static $msgTemplate = array(
    'default' => "欢迎关注玩主娱乐\n\n==在线玩【谁是卧底】游戏！无需下载，随时邀请好友一起玩\n\n==点击底部菜单“游戏大厅“ / 开始玩->创建房间开始游戏\n\n==获取游戏攻略，回复 0\n\n==任何问题，直接回复，小编在线解答",
    'msg_players_chat' => "%player%说：\n%content%\n",
    //start game
    'start_game_when_gaming' => "游戏正在进行中...",
    'start_game_without_room' => "加入房间后才可以使用这个哦~\n你也可以创建自己的房间邀请好友来玩，请点击菜单上的#开始玩-> 创建房间#",
    'start_game_is_not_host' => "只有房主点击开始游戏才能开始哦，我已经帮你提醒他了。",
    'start_game_players_cannot_wait' => "【%player%】已经迫不及待想开始游戏了，请房主点击#开始玩->开始游戏#",
    'start_game_players_not_enough' => "房间人数不足4人，不能开始游戏，可邀请新的好友入房\n点击下面的链接，分享给好友一起玩吧：\n<a href=\"http://wxapp.shihuo.me/room/invite/%room_id%.html\">邀请链接</a>",
    'start_game_failed' => "抱歉开始游戏失败！请稍候重试...",
    'start_game_successed' => "========游戏开始========\n本场游戏有%count%名玩家，分别是：\n%players%\n\n【本场规则】\n1、惩罚方式随机；\n2、%rule%\n3、超过40秒未描述将扣金币；超过20秒未投票系统随机投；\n========================\n你是【%cur_no%】号，拿到的词语是【%word%】，求含蓄描述。\n第1轮描述开始，请【1号%first%】开始发言。",
    //exit room
    'exit_room_without_room' => "你还没有加入任何房间。\n你可以点击菜单栏 #游戏大厅# 或 #开始玩->创建房间#",
    'exit_room_when_gaming' => "游戏进行中，不能中途退出。",
    'exit_room_successed' => "你已成功退出房间。",
    'exit_room_successed_last' => "你已成功退出房间，你的房间解散。",
    'exit_room_successed_others_not_enough' => "【%quitter%】已退出房间\n房间人数不足4人，可邀请新的好友入房\n点击下面的链接，分享给好友一起玩吧：\n<a href=\"http://wxapp.shihuo.me/room/invite/%room_id%.html\">邀请链接</a>",
    'exit_room_successed_others_enough' => "【%quitter%】已退出房间\n目前房间里有%count%人，分别是\n%players%等待房主宣布开始游戏...\n\n点击下面的链接，分享给好友一起玩吧：\n<a href=\"http://wxapp.shihuo.me/room/invite/%room_id%.html\">邀请链接</a>",
    'exit_room_host_successed' => "你已成功退出房间，你的房主权限将会交给其他玩家。",
    'exit_room_host_successed_last' => "你已成功退出房间，你的房间将会解散。",
    'exit_room_host_successed_others' => "房主【%quitter%】退出房间，房主身份移交给【%new_host%】，由【%new_host%】点击#开始玩->开始游戏# 开始游戏。\n\n点击下面的链接，分享给好友一起玩吧：\n<a href=\"http://wxapp.shihuo.me/room/invite/%room_id%.html\">邀请链接</a>",
     //status
    'status_without_room' => "你当前还没加入任何房间。\n 你可以点击 菜单栏#游戏大厅#  或 #开始玩->创建房间# ",
    'status_players_not_enough' => "谁是卧底微信\n===当前游戏状态===\n\n当前房间共%count%人，分别是：\n%players%房间人数不足4人，可邀请新的好友入房\n点击下面的链接，分享给好友一起玩吧：\n<a href=\"http://wxapp.shihuo.me/room/invite/%room_id%.html\">邀请链接</a>",
    'status_gaming' => "当前房间共%count%人，分别是：\n%players%游戏进行中...",
    'status_new_game' => "当前房间共%count%人，分别是：\n%players%等待房主宣布开始游戏...",
    'status_failed' => "抱歉，获取当前游戏状态失败...",
    //swipe room
    'swipe_room_swiped' => "你已被房主请出房间，如需开始新的游戏，请点击菜单“游戏大厅“或”开始玩->创建房间“",
    'swipe_room_others' => "玩家%swiped%被房主请出房间。\n当前有%count%位玩家，分别是：\n%players%\n点击下面的链接，分享给好友一起玩吧：\n<a href=\"http://wxapp.shihuo.me/room/invite/%room_id%.html\">邀请链接</a>",
    //join room
    'join_room_in_room' => "你已经在另一个房间里了，如需加入新的房间，请先点击#开始玩->退出房间#",
    'join_room_in_same_room' => "你已经在%number%号房间里了哦，如果要换到其它房间，请先点击#开始玩->退出房间#",
    'join_room_wrong_id' => "你要加入的房间不存在或已解散，请换个房间...\n你可以点击菜单栏 #游戏大厅# 或 #开始玩->创建房间#",
    'join_room_filled' => "该房间已经满了哦...",
    'join_room_coins_not_enough' => "抱歉你金币的少于10枚，不能加入房间哦...",
    'join_room_when_gaming' => "该房间正在游戏中哦...\n你可以点击菜单栏 #游戏大厅# 或 #开始玩->创建房间#",
    'join_room_failed' => "抱歉，加入房间失败，请重试...",
    'join_room_successed_self_enough' => "你已加入了房间，请给新人一点鼓励！\n目前房间里有%count%人，分别是：\n%players%等待房主宣布游戏开始\n\n点击下面的链接，分享给好友一起玩吧：\n<a href=\"http://wxapp.shihuo.me/room/invite/%room_id%.html\">邀请链接</a>",
    'join_room_successed_self_not_enough' => "你已加入了房间，请给新人一点鼓励！\n目前房间里有%count%人，分别是：\n%players%房间人数不足4人，请继续邀请好友加入：\n<a href=\"http://wxapp.shihuo.me/room/invite/%room_id%.html\">邀请链接</a>",
    'join_room_successed_enough' => "【%new%】刚刚加入了房间，请给新人一点鼓励！\n目前房间里有%count%人，分别是：\n%players%等待房主宣布游戏开始\n",
    'join_room_successed_not_enough' => "【%new%】已加入了房间，请给新人一点鼓励！\n目前房间里有%count%人，分别是：\n%players%房间人数不足4人，请继续邀请好友加入：\n<a href=\"http://wxapp.shihuo.me/room/invite/%room_id%.html\">邀请链接</a>",
    //speak
    'msg_speak_first' => "【%unum%号 %uname%】描述：\n%speak%\n\n下面请【%unnum%号%unname%】描述",
    'msg_speak_rounds' => "【%unum%号 %uname%】描述：\n%prev-rounds%【第%round%轮说】%speak%\n下面请【%unnum%号%unname%】描述",
    'msg_speak_last_one' => "【%unum%号 %uname%】描述：\n%speak%\n\n第%round%轮描述结束\n========================\n玩家分别描述的是\n%speakers%========================\n现在开始投票，请回复你要投票的玩家编号。",
    //vote
    'msg_vote_self' => "世界这么大，你为何要自杀，请重新投票~",
    'msg_vote_not_exist' => "该用户不存在，请重新投票。",
    'msg_vote_dead' => "该用户已死，请重新投票。",
    'msg_vote' => "%unum%号%uname% 已经完成投票了。",
    'msg_vote_goon' => "%unum%号%uname% 已经完成投票了。\n第%round%轮投票详情如下：\n%votes%\n%voted%得票%syschoose%TA是平民，被冤死。\n========================\n下面开始第%nround%轮描述，请【%unnum%号%unname%】开始描述。",
    'msg_vote_tie' => "%unum%号%uname% 已经完成投票了。\n第%round%轮投票详情如下：\n%votes%\n%voted%得票一样，请重新投票。\n",
    //vote end
    'msg_spy_win' => "%unum%号%uname% 已经完成投票了。\n第%round%轮投票详情如下：\n%votes%\n%voted%得票%syschoose%TA是平民，平民人数已经少于%remain%人，卧底胜利。\n========================\n本轮游戏卧底是【%spy%号%spyname%】\n卧底词是：%spyword%；\n平民词是：%normalword%。",
    'msg_normal_win' => "%unum%号%uname% 已经完成投票了。\n第%round%轮投票详情如下：\n%votes%\n%voted%得票%syschoose%TA是卧底，卧底已被铲除，平民胜利。\n========================\n本轮游戏卧底是【%spy%号%spyname%】\n卧底词是：%spyword%；\n平民词是：%normalword%。",
    //last msg
    'msg_last_normal_win' => "本轮卧底【%spy%号%spyname%】失败：扣除5金币；\n平民%normal%胜利：各奖励5金币。\n\n 请【%spyname%】接受惩罚：\n%punish%\n\n如需进行下一场游戏，请房主点击#开始玩->开始游戏#\n点击下面的链接，分享给好友一起玩吧：\n<a href=\"http://wxapp.shihuo.me/room/invite/%room_id%.html\">邀请链接</a>",
    'msg_last_spy_win' => "本轮卧底【%spy%号%spyname%】胜利：奖励10金币；\n平民%normal%失败：扣除5金币。\n\n 请平民们接受惩罚：\n%punish%\n\n如需进行下一场游戏，请房主点击#开始玩->开始游戏#\n点击下面的链接，分享给好友一起玩吧：\n<a href=\"http://wxapp.shihuo.me/room/invite/%room_id%.html\">邀请链接</a>",
    'msg_last_host_special' => "\n如需将表现不积极的玩家踢出房间，请点击：\n<a href=\"http://wxapp.shihuo.me/room/swipe.html\">踢人</a>",
    //status
    'msg_status_start_speak' => "谁是卧底微信\n===当前游戏状态===\n\n当前玩家的状态：\n%players-status%\n现在刚开始第1轮描述，玩家还没有开始描述",
    'msg_status_speak' => "谁是卧底微信\n===当前游戏状态===\n\n当前玩家的状态：\n%players-status%\n现在正在进行第%round%轮描述，玩家分别描述的是\n%speakers%",
    'msg_status_sum_speak' => "谁是卧底微信\n===当前游戏状态===\n\n当前玩家的状态：\n%players-status%\n现在刚开始第%round%轮描述，上一轮玩家的投票结果:\n%votes%%syschoose%",
    'msg_status_speak_vote' => "谁是卧底微信\n===当前游戏状态===\n\n当前玩家的状态：\n%players-status%\n现在刚开始第%round%轮投票，上一轮玩家分别描述的是\n%speakers%",
    'msg_status_vote' => "谁是卧底微信\n===当前游戏状态===\n\n当前玩家的状态：\n%players-status%\n现在正在进行第%round%轮%count%投票，玩家分别投票的是\n%votes%",
    'msg_status_sum_vote' => "谁是卧底微信\n===当前游戏状态===\n\n当前玩家的状态：\n%players-status%\n现在刚开始第%round%轮第2次投票，玩家分别投票的是\n%votes%由于投票结果同票，需要再次投票",
    //create room
    'create_room_coins_not_enough' => "你的财富少于10金币，不能创建房间，你可以点击此链接去购买金币：\n<a href=\"http://wxapp.shihuo.me/usercenter/wxpay.html\">购买金币</a>",
    'create_room_already_in_room' => "你已经在另外一个房间了哦，若需创建房间，请先点击#开始玩->退出房间#",
    'create_room_successed' => "谁是卧底房间创建成功！\n\n1、房间人数至少4人最多6人；\n2、房间人数满4人之后，房主可点击#开始玩->开始游戏# 开始\n 3、想要踢人，请点击：<a href=\"http://wxapp.shihuo.me/room/swipe.html?id=%room_id%\">踢人</a>\n\n如有疑问，点击查看游戏帮助\n\n点击下面的链接，分享给好友一起玩吧：\n<a href=\"http://wxapp.shihuo.me/room/invite/%room_id%.html\">邀请链接</a>",
    'create_room_failed' => "抱歉创建房间失败！请稍候重试...",
    //automate script
    'msg_not_speak' => "【%unum%号 %uname%】超过40秒，没有进行描述，扣除3个金币。\n\n下面请【%unnum%号%unname%】描述",
    'msg_host_not_speak' => "【%unum%号 %uname%】超过60秒，没有进行描述，扣除3个金币。\n\n下面请【%unnum%号%unname%】描述",
    'msg_not_speak_last_one' => "【%unum%号 %uname%】\n超过40秒，没有进行描述，扣除3个金币。\n\n第%round%轮描述结束\n========================\n玩家分别描述的是\n%speakers%========================\n现在开始投票，请回复你要投票的玩家编号。",
    'msg_speak_notice' => "你还剩15秒的时间，超时会被扣除金币，请抓紧描述哦",
    'dissolve_room' => "你所在的房间超过5分钟没有开始游戏，系统已自动解散。你可以点击＃游戏大厅＃或＃开始玩->创建房间＃。",
    'msg_not_vote' => "【%unum%号 %uname%】超过20秒未投票，由系统随机投票",
  );

  private static $robotChatContent = array(
    '房主开吧',
    '开始游戏吧',
    '快开始吧、房主',
    '请开始游戏吧',
    '我等得花儿都谢了',
  );

  public static function getRobotChatContent () {
    $key = mt_rand(0, count(self::$robotChatContent) - 1);
    return self::$robotChatContent[$key];
  }

  final private static function getGearmanService () {
    return ServiceFactory::getInstance()->createGearmanService();
  }

  /**
   * @desc 根据消息模板获取游戏内容
   * @param string $key
   * @param array $replace
   * @return string
   */
  public static function getMsgContent ($key = 'default', $replace = array()) {
    $msgContent = (self::$msgTemplate[$key]) ? self::$msgTemplate[$key] : self::$msgTemplate['default'];
    if ($replace && $msgContent) {
      $keys = array_keys($replace);
      $vals = array_values($replace);
      $msgContent = str_replace($keys, $vals, $msgContent);
    }
    return $msgContent;
  }

  /**
   * @desc 发送异步消息
   * @param array $users
   * @param string $msg content
   * @return bool
   */
  public static function sendAsyncMsg ($users, $msgContent) {
    if ($users && $msgContent) {
      foreach ($users as $user) {
        if (!$user['openid']) continue;
        $jobMsg[] = array('openid' => $user['openid'], 'msgtype' => 'text', 'content' => $msgContent);
      }
      return self::getGearmanService()->addSendMsgJob($jobMsg);
    }
    return FALSE;
  }

  /**
   * @desc 使用回调函数发送异步消息
   * @param mix $param 由回调函数控制的一个参数
   * @return bool
   */
  public static function sendAsyncMsgByCallback ($callback, $param) {
    if (($callback instanceof Closure) && $param) {
      $jobMsg = array();
      $callback($jobMsg, $param);
      return $jobMsg ? self::getGearmanService()->addSendMsgJob($jobMsg) : FALSE;
    }
    return FALSE;
  }
}

