<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>玩主听歌曲猜歌名</title>
<meta name="description" content="玩主，就是玩得来"/>
<meta name="keywords" content="那些风靡95后的娱乐小游戏"/>
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<link rel="shortcut icon" href="http://s.wanzhucdn.com/ui/img/logo/120.png" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/css/m/base.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/css/m/component.css?v1.0"/>
<style type="text/css">
#view { background: #39364d; z-index: 0; }
.aidver { bottom: auto; top: 0; z-index: 9; }
#container { padding: 50px 0 20px; position: relative; z-index: 1; }
.bg { margin-top: 40%; position: absolute; z-index: 1; width: 100%; z-index: -1; }
.bg img { width: 100%; }
.label { margin-top: 20%; text-align: center; color: #fff; font-size: 16px; }
.user { width: 62%; margin: 18% auto 0; overflow: hidden; color: #fff; }
.user .avatar { width: 60px; height: 60px; float: left; padding: 2px; background: #fff; }
.user .info { margin-left: 75px; padding-top: 5px; }
.user .info h1 { float: left; margin-right: 2px; font-size: 15px; }
.user .info img { display: inline-block; width: 12px; height: 12px; vertical-align: -4px; }
.user .info .bd { margin-top: 8px; height: 30px; background: #ffff7f; color: #39364d; border-radius: 6px; line-height: 30px; padding: 0 10px; position: relative; }
.user .info .bd::before { content: ""; position: absolute; width: 0; height: 0; border: 4px solid transparent; border-right-color: #ffff7f; right: 100%; top: 50%; margin-top: -6px; }
.user .info .bd span { margin-top: 7px; margin-left: -8px; width: 16px; height: 16px; float: left; position: relative; }
.user .info .bd i, .user .info .bd i::before { position: absolute; width: 0; height: 0; border-style: solid; border-color: transparent; border-radius: 50%; left: 50%; top: 50%; -webkit-transform: translate(-50%, -50%); transform: translate(-50%, -50%); }
.user .info .bd i:nth-child(1) { border-width: 10px; border-right-color: #39364d; }
.user .info .bd i:nth-child(1)::before { content: ""; border-width: 8px; border-color: #ffff7f; }
.user .info .bd i:nth-child(2) { border-width: 7px; border-right-color: #39364d; }
.user .info .bd i:nth-child(2)::before { content: ""; border-width: 5px; border-color: #ffff7f; }
.user .info .bd i:nth-child(3) { border-width: 4px; border-right-color: #39364d; }
.user .info .bd i:nth-child(3)::before { content: ""; border-width: 2px; border-color: #ffff7f; }
.user .info .bd.on i:nth-child(1) { -webkit-animation: anim1 0.5s ease 0s infinite; animation: anim1 0.5s ease 0s infinite; }
@-webkit-keyframes anim1 {
	from { border-right-color: #39364d; }
	to { border-right-color: #ffff7f; }
}
@keyframes anim1 {
	from { border-right-color: #39364d; }
	to { border-right-color: #ffff7f; }
}
.user .info .bd.on i:nth-child(2) { -webkit-animation: anim2 0.5s ease 0.5s infinite; animation: anim2 0.5s ease 0.5s infinite; }
@-webkit-keyframes anim2 {
	from { border-right-color: #39364d; }
	to { border-right-color: #ffff7f; }
}
@-keyframes anim2 {
	from { border-right-color: #39364d; }
	to { border-right-color: #ffff7f; }
}
.user .info .bd em { float: right; font-style: normal; }
.user .info .bd em sub { vertical-align: 0px; margin-left: 1px; }
.user .info .bd b { position: absolute; top: 0; left: 25px; font-size: 12px; color: #f43; font-weight: 300; } 
.btn-record { width: 85%; margin: 34% auto 0; }
.btn-record img { width: 100%; }
audio { display: none; }
</style>
</head>
<body>

<div id="view">
	
	<div class="aidver rel">
		<div class="info">
			<h2 class="ellipsis">玩主，就是玩得来</h2>
			<p class="ellipsis">那些风靡95后的娱乐小游戏</p>
		</div>
		<div class="show vabscenter">
			<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/logo/120.png" />
		</div>
		<div class="acitve vabscenter">
			<a href="<?php echo WEB_QW_APP_DOMAIN?>/d?fr=song">
				<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/m/icon_download.png" />
			</a>
		</div>
	</div>
	
	<div id="container">
		
		<audio id="J_audio">
		  <source src="<?php echo $song_url;?>" >
		</audio>
		
		<div class="bg">
			<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/m/desc_audioBg.png" />
		</div>
		
		<div class="label">
			猜猜这是什么歌？
		</div>
		
		<div class="user">
			<div class="avatar">
				<div class="inner">
					<?php if($avatar):?>
		            <img src="<?php echo $avatar;?>" />
		            <?php else:?>
		            <img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/logo/120.png" />
		            <?php endif;?>
				</div>
			</div>
			<div class="info">
			    <?php if ($nickname) :?>
				<div class="hd">
					<h1><?php echo $nickname;?></h1>
					<img src="<?php echo WEB_QW_APP_FILE_UI_URL;?>/img/app/level/<?php echo $grade;?>@2x.png"/>
				</div>
				<?php endif;?>
				<div class="bd on" id="J_audio-btn">
					<span>
						<i></i><i></i><i></i>
					</span>
					<em><sub>s</sub></em>
					<b>点击播放</b>
				</div>
			</div>
		</div>
		
		<div class="btn-record">
			<a href="<?php echo WEB_QW_APP_DOMAIN?>/d?fr=song">
				<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/m/icon_record_btnBg.png" />
			</a>
		</div>
	</div>
</div>
<script type="text/javascript">
function Music(time) {
	this.audio = document.getElementById("J_audio");
	this.audioBtn = document.getElementById("J_audio-btn");
	this.timeDom = this.audioBtn.getElementsByTagName('em')[0];
	this.remind = this.audioBtn.getElementsByTagName('b')[0];
	this.originalTime = time || 0;
	this._init();
}

Music.prototype = {
	_init : function () {
		this.time = this.originalTime || 0;
		this.timer = null;
		this.isPlay = false;
		this.render();
		this.bindEvt();
		this.remind.style.display = 'block';
		this.audioBtn.classList.remove('on');
	},
	bindEvt : function () {
		var _this = this;
		this.audioBtn.onclick = function () {
			(_this.isPlay = !_this.isPlay) ? _this.play() : _this.pause();
		}
	},
	render : function () {
		this.timeDom.innerHTML = this.time + '<sub>s</sub>';
	},
	play : function () {
		this.audio.play();
		this.run();
		this.remind.style.display = 'none';
		this.audioBtn.classList.add('on');
	},
	pause : function () {
		this.audio.pause();
		clearTimeout(this.timer);
		this.audioBtn.classList.remove('on');
	},
	run : function () {
		var _this = this;
		_this.time --;
		_this.render();
		this.timer = setTimeout(function () {
			if(_this.time <= 0) {
				_this.pause();
				clearTimeout(_this.timer);
				_this._init();
				return;
			}
			_this.run();
		}, 1000)
	}
};

var music = new Music(<?php echo $duration;?>);
</script>
</body>
</html>
