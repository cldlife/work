<img src="<?php echo $this->currentUser['avatar']?>" style="left:-3000px;position: absolute;">
<img src="<?php echo $share_logo?>" style="left:-3000px;position: absolute;">
<div class="container">
	
	<div class="container-mask"></div>
	
	<div id="carton" class="carton">
		<img class="carton_a" src="http://s.wanzhucdn.com/ui/img/icon/desc_arrow1.png"/>
		<img class="carton_b" src="http://s.wanzhucdn.com/ui/img/icon/desc_arrow2.png"/>
	</div>
	
	<div class="title">
		<img src="<?php echo $knowGame['center_img']?>" />
	</div>
	
	<div class="xhare" style="width: 80%; margin-top: 12%;">
		<img src="<?php echo $knowGame['share_center']?>"/>
		<!--<div class="txt">
			题目设置完毕<br />
			邀请好友答题
		</div>-->
	</div>
	
	<div class="btn-xhare">
		<a href="javascript:;">
			<img src="<?php echo $knowGame['share_button']?>" />
		</a>
	</div>
	
</div><!-- /container -->
<script type="text/javascript">
var flag = 0;
setInterval(function(){
	flag ++;
	if(flag > 1) {
		flag =0;
	}
	if(flag%2 == 0) {
		$(".carton_a").show().siblings().hide();
	}else if(flag%2 == 1) {
		$(".carton_b").show().siblings().hide();
	}
},80)

function Popup() {
	var self = this;
	this.shareBtn = $('.btn-xhare');
	this.mask = $('.container-mask');
	this.anim = $('.carton');
	this.shareBtn.on('touchend', function () {
		self.show();
		
	})
	this.mask.on('touchend', function () {
		self.hide();
	})
}
Popup.prototype = {
	show : function () {
		this.mask.show();
		this.anim.show();
	},
	hide : function () {
		this.mask.hide();
		this.anim.hide();
	}
}
	
new Popup();
</script>
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
