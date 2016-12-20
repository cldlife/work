<img src="<?php echo $this->currentUser['avatar']?>" style="left:-3000px;position: absolute;">
<img src="<?php echo $share_logo?>" style="left:-3000px;position: absolute;">
<div class="more-entrance" style="position: fixed; width: 40px;height: 40px; right: 15px; top: 0;margin-top: 60%;">
	<a href="http://www.v1h5.com/cps/30/index.shtml" onclick="_hmt.push(['_trackEvent', 'ad_float_45x45', 'click', 'tg_bird'])">
		<img src="http://s.wanzhucdn.com/ui/img/icon/desc_game.gif">
	</a>
</div>
<div class="container">
	
	<div class="title">
		<img src="<?php echo $knowGame['center_img']?>" />
	</div>
	
	<div class="user" style="margin-top: 12%;">
		<div class="avatar">
			<div class="inner">
				<img src="<?php echo $this->currentUser['avatar']?>" />
			</div>
		</div>
		<div class="nick" style="color: #fff;">
			<?php echo $this->currentUser['nickname']?>
		</div>
	</div>
	
	<div class="active actives">
		<a  href='<?php echo $randDomain . "/{$randControllerName}/{$randUrlLink}.html?step=1&{$this->loginTypeSuuidParams}";?>'>重选一次</a>
		<a href="<?php echo $qaInfo['msg_link_chiba']?>">
			你有新消息
		</a>
	</div>
	
	<div class='bn' style="margin-top: 15px;width: 100%;overflow: hidden;padding-top: 16.5%;position: relative;">
    <ul style="position: absolute;width: 100%;top: 0;left: 0; padding:0 10px; -webkit-box-sizing:border-box; box-sizing:border-box; ">
      <li style='list-style: none;'>
        <a href='http://h5.zhudade.cn/checkpay/index20160954.html' style='width: 100%;margin-right: 0;' onclick="_hmt.push(['_trackEvent', 'adv_banner_600x100', 'click', 'tg_eq'])"><img  src='http://f.shiyi11.com/ui/img/wxgame/list/bn600_eq.png' width='100%'></a>
      </li>
  	</ul></div>

	<div class="result">
		<div class="result-hd">
			<a href='<?php echo $this->getDeUrl("{$randControllerName}/{$randUrlLink}/tm{$question["id"]}/qa");?>'>查看正确答案</a>
		</div>
		<div class="result-bd">
			<ul>
				<?php if ($answerList) :?>
    			<?php foreach ($answerList as $answer) :?>
					<li>
						<div class="avatar">
							<a class="inner" href='<?php echo $this->getDeUrl("{$randControllerName}/{$randUrlLink}/tm{$question["id"]}/qa");?>?uid=<?php echo $answer["user_info"]["uid"]?>'>
								<img src="<?php echo $answer['user_info']['avatar'];?>" />
							</a>
						</div>
						<div class="info">
							<h4><?php echo $answer['user_info']['nickname'];?></h4>
							<p><?php echo $answer['answer_desc']?></p>
						</div>
						<div class="record">
							<?php echo $answer['matching_percent'];?>%
						</div>
					</li>
				<?php endforeach;?>
				<?php else:?>
					<li style="text-align: center;">还没有人参与哦！</li>
				<?php endif;?>
			</ul>
		</div>
	</div>
	
	<div class="hint">
		<a href="http://abcde.wx.shihuoapp.com/templete/kefu.html">客服QQ｜常见问题</a>
	</div>
	
</div><!-- /container -->
<?php if ($isFromWeixin && $sinaShareDomain && $knowGame['jssdk_mpids']) : ?>
<script type="text/javascript">
wx.config({
  debug: <?php echo APP_DEBUG ? 'true' : 'false'; ?>, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
  appId: "<?php echo $weixinJssdkConfig['appid'];?>", // 必填，公众号的唯一标识
  timestamp: "<?php echo $weixinJssdkConfig['timestamp'];?>", // 必填，生成签名的时间戳
  nonceStr: "<?php echo $weixinJssdkConfig['noncestr'];?>", // 必填，生成签名的随机串
  signature: "<?php echo $weixinJssdkConfig['signature'];?>",// 必填，签名，见附录1
  jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'hideMenuItems'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});

var shareTitle = '<?php echo $this->title;?>';
var shareLink = '<?php echo "http://{$sinaShareDomain}/" . Utils::enDecrypt($shareLink) ;?>';
var shareImgUrl = '<?php echo $this->currentUser['avatar'] ? $this->currentUser['avatar'] : $qaInfo['share_logo_xswm'];?>';
wx.ready(function(){  
 wx.hideMenuItems({
    menuList: ['menuItem:share:weiboApp', 'menuItem:favorite', 'menuItem:share:facebook', 'menuItem:editTag', 'menuItem:delete', 'menuItem:copyUrl', 'menuItem:originPage', 'menuItem:readMode', 'menuItem:openWithQQBrowser', 'menuItem:openWithSafari', 'menuItem:share:email', 'menuItem:share:brand']
  });// 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
 
  /*分享到朋友圈*/
  wx.onMenuShareTimeline({ 
    title: shareTitle, // 分享标题
    desc: '', // 分享描述
    link: shareLink, // 分享链接
    imgUrl: shareImgUrl, // 分享图标
    success: function () { 
    },
    cancel: function () { 
    }
  });
  
  /*分享到微信好友*/
  wx.onMenuShareAppMessage({
    title: shareTitle, // 分享标题
    desc: '', // 分享描述
    link: shareLink, // 分享链接
    imgUrl: shareImgUrl, // 分享图标
    success: function () { 
    },
    cancel: function () { 
    } 
  });

});
</script>
<?php endif; ?>