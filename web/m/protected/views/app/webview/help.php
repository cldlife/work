<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<title>怎么玩</title>
<link rel="shortcut icon" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/logo/120.png" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/css/m/base.css"/>
<style type="text/css">
#container { padding: 15px; }
.explains { background: #fff; }
.explains dt, .explains dd { padding: 13px 12px; border: 1px solid #e6e6e6; margin-top: -1px; }
.explains dt { padding-right: 35px; text-align: justify; font-size: 15px; position: relative; cursor: pointer; }
.explains dt:first-of-type { margin-top: 0; }
.explains dd { color: #666; display: none; }
.explains dd.current { display: block; }
.explains dt i { position: absolute; right: 12px; top: 50%; margin-top: -7px; width: 15px; height: 15px; -webkit-transform: rotate(45deg); transform: rotate(45deg); -webkit-transition: transform .5s; transition: transform .5s; }
.explains dt.current i { -webkit-transform: rotate(-135deg); transform: rotate(-135deg); }
.explains dt i::before, .explains dt i::after { content: ""; display: block; position: absolute; width: 10px; height: 10px; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; -webkit-transform: skew(5deg, 5deg); transform: skew(5deg, 5deg); }
.explains dt i::after { bottom: 0; right: 0; }
</style>
</head>
<body>
<div id="view">
	<div id="container">
		<div id="J_explains" class="explains">
			<dl>
				<dt class="current">金币有什么用？<i></i></dt>
				<dd class="current">金币是玩主App里流通的游戏货币，可以直接购买充值，也可以通过玩游戏获得。</br>它能购买游戏里的各种道具，增强游戏交互体验。</dd>
				<dt>玫瑰有什么用？<i></i></dt>
				<dd>玫瑰是玩主里面一种强大的撩妹撩汉工具，你在社区里游戏里都可以赠送玫瑰给他人。</br>它不可以用金币兑换，只能靠别人赠送积累；</br>积极参与游戏，社区发帖被赠送的玫瑰也将越多。</dd>
				<dt>积分是什么？<i></i></dt>
				<dd>积分直接与等级挂钩，积分越多等级越高，越有面子！</br>社区发爆照、每天登录、玩游戏、发表评论等等都有积分赠送。</dd>
      			<dt>听歌曲猜歌名规则如何？<i></i></dt>
				<dd>进入游戏后，根据系统播放的歌曲片段进行猜歌名，猜对了可以获得相应金币！</dd>
			</dl>
		</div>
	</div><!-- /container -->
</div><!-- /view -->

<script src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/common/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
var explainList = $('#J_explains');
explainList.on('click', 'dt', function () {
	var currentDt = $(this);
	var currentDd = currentDt.next();
	currentDt.addClass('current').siblings('dt').removeClass("current");
	currentDd.slideDown().siblings('dd').slideUp();
})
</script>
</body>
</html>
