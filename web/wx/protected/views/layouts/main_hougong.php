<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<title><?php echo $this->title?></title>
<meta name="description" content="后宫"/>
<meta name="keywords" content="那些风靡95后的娱乐小游戏"/>
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<link rel="shortcut icon" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/logo/120.png" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/css/base.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/css/hougong/component.css?v1.0_2016.11.15"/>
<script src="//res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">var IMG_DOMAIN = '<?php echo WEB_QW_APP_FILE_UI_URL?>';</script>
<script src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/common/jquery-1.8.3.min.js"></script>
<script src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/common/utils.js?v=0.0.1.142335-20161203"></script>
<script src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/hougong/hougong.js?v=0.0.1.142335-20161203"></script>
<script src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/common/Dropload.js?v=0.0.1.142335-20161214"></script>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?48cd05afe9a7016f764b5db63b9a5948";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
</head>
<body>
<div id="view">
<?php echo $content?>
</div>
</body>
<script type="text/javascript">
/* ================  jsjdk分享 S =================== */
	wx.config({
	  debug: <?php echo APP_DEBUG ? 'true' : 'false'; ?>, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
	  appId: "<?php echo $this->weixinJssdkConfig['appid'];?>", // 必填，公众号的唯一标识
	  timestamp: "<?php echo $this->weixinJssdkConfig['timestamp'];?>", // 必填，生成签名的时间戳
	  nonceStr: "<?php echo $this->weixinJssdkConfig['noncestr'];?>", // 必填，生成签名的随机串
	  signature: "<?php echo $this->weixinJssdkConfig['signature'];?>",// 必填，签名，见附录1
	  jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
	});
	wx.ready(function(){
	    wx.onMenuShareTimeline({
		   title: '<?php echo $this->shareTitle; ?>', // 分享标题
		   link: "<?php echo $this->getDeUrl($this->shareLink).'?share=1';?>", // 分享链接
		   imgUrl: '<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/sq_hougong.png', // 分享图标
		   success: function () {},
		   cancel: function () {}
	    });
	    wx.onMenuShareAppMessage({
		   title: '<?php echo $this->shareTitle ?>', // 分享标题
		   link: "<?php echo $this->getDeUrl($this->shareLink).'?share=1'?>", // 分享链接
		   imgUrl: '<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/sq_hougong.png', // 分享图标
		   success: function () {},
		   cancel: function () {}
	    });
	});
</script>
</html>
