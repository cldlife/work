<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>玩主，就是玩得来</title>
<meta name="description" content="玩主，就是玩得来"/>
<meta name="keywords" content="那些风靡95后的娱乐小游戏"/>
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="yes" name="apple-touch-fullscreen" />
<meta name="robots" content="玩主，就是玩得来。那些风靡95后的娱乐小游戏" />
<meta name="format-detection" content="telephone=no">
<meta name="viewport" id="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="shortcut icon" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/logo/120.png" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/css/m/base.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/css/m/component.css?v1.0"/>
<style type="text/css">
#view { position: relative; background: #7965db; padding-bottom: 20px; }
.bg { position: absolute; z-index: 0; top: 0; width: 100%; }
.bg img { width: 100%; }
#container { position: relative; z-index: 1; margin: 0 8%; }
.caption .slogan { margin-top: 9.5238%; color: #fff; }
.caption .slogan img { width: 60px; height: 60px; float: left; -webkit-box-shadow: 0 0 20px rgba(51,31,153,.5); box-shadow: 0 0 20px rgba(51,31,153,.5); }
.caption .slogan .info { margin-left: 80px; padding-top: 5px; height: 55px; }
.caption .slogan h1 { font-size: 18px; }
.caption .slogan p { margin-top: 4px; font-size: 12px; }
.caption .active { margin-top: 30px; }
.caption .active img { width: 100%; }
.appraise { background: #fff; margin-top: 19.0476%; padding: 6.3492%; border-radius: 6px; }
.appraise .user { width: 100%; top: 0; margin-top: -9.5238%; }
.appraise .user .avatar { width: 19.0476%; margin:0 auto; padding: 2px; background: #fff; -webkit-box-sizing: border-box; box-sizing: border-box; box-shadow: 0 0 5px rgba(0,0,0,.2); }
.appraise .user .nick { margin-top: 10px; text-align: center; color: #999; }
.appraise .detail { margin-top: 18%; }
.feature ul { overflow: hidden; }
.feature li { margin-top: 17.4603%; padding: 12.6984%; padding-bottom: 5.5555%; background: #fff; border-radius: 6px; }
.feature .label { width: 68.0851%; margin: -17.0212% auto 0; -webkit-transform: translateY(-50%); transform: translateY(-50%); height: 50px; line-height: 50px; border-radius: 25px; -webkit-box-shadow: 0 5px 10px rgba(121,101,219,.2); background: #fff; text-align: center; color: #7965db; font-size: 18px; }
.feature .info img { margin-top: -10px; width: 100%; }
.feature .info p { text-align: center; color: #aaa3cc; }
.copyright { margin-top: 15px; color: #fff; text-align: center; font-size: 12px; color: #bfb8e5; }
</style>
</head>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?76494d61fa16deb1db164e7755a58cfa";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
<a href="javascript:void(0);" id="openApp" style="display: none">玩主APP</a>
<body>
<div id="view">
	<div class="bg habscenter">
		<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/m/download_bg1.jpg" />
	</div>
	
	<div id="container" class="clearfix">
		<div class="caption">
			<div class="slogan clearfix">
				<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/logo/120.png" />
				<div class="info">
					<h1>玩主就是玩得来</h1>
					<p>那些风靡95后的娱乐小游戏</p>
				</div>
			</div>
			<div class="active">
				<a href="javascript:;" id="downLoadBtn">
					<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/m/icon_download_active.png" />
				</a>
			</div>
		</div>
		
		<div class="appraise rel">
			<div class="user habscenter">
				<div class="avatar">
					<div class="inner">
						<?php if($avatar):?>
                        <img src="<?php echo $avatar;?>" />
                        <?php else:?>
                        <img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/logo/160.png" />
                        <?php endif;?>
					</div>
				</div>
				<div class="nick"><?php echo $nickname ? $nickname : '小主';?></div>
			</div>
			<div class="detail">
				经我老司机鉴定，玩主确实是一个非常好玩的APP，推荐你也下载玩一玩，里面95后的帅哥美女特别多，可以认识不少朋友哦~
			</div>
		</div>
		<div class="feature">
			<ul>
				<li>
					<div class="label">
						<h3>听歌曲猜歌名</h3>
					</div>
					<div class="info">
						<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/m/desc_download_feature1.jpg" />
						<p>400万人玩过</p>
					</div>
				</li>
			</ul>
		</div>
		
		<div class="copyright">
			版权所有©2016 杭州圈外网络科技有限公司
		</div>
	</div><!-- /container -->
	
</div><!-- /view -->
<script type="text/javascript">
window.onload = function download(){
  var iPhoneHref = 'https://itunes.apple.com/cn/app/wan-zhu-ni-chang-wo-cai-ti/id1144837002?mt=8';
  var AdroidHref = 'http://a.app.qq.com/o/simple.jsp?pkgname=com.QuanWai.WanZhu';
  var wxHref = 'http://a.app.qq.com/o/simple.jsp?pkgname=com.QuanWai.WanZhu';
  var appUrlScheme = 'WanZhu://home';
  var mUrl = '<?php echo WEB_QW_APP_DOMAIN?>';
  var u = navigator.userAgent;

  if(!!u.match(/Mobile/i)) {
    //iPhone
    if(!!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/) || !!u.match(/iPad|iPhone|iPod|iOS/i)) {
      document.getElementById('openApp').href = iPhoneHref;
      document.getElementById('downLoadBtn').href = iPhoneHref;
      
    //Android
    } else {
      document.getElementById('openApp').href = AdroidHref;
      document.getElementById('downLoadBtn').href = AdroidHref;
    }

    //微信
    if (isWx(u)) {
      document.getElementById('openApp').href = wxHref;
      document.getElementById('downLoadBtn').href = wxHref;
    }

  } else {
    //window.location.href = mUrl;
    return;
  }

  document.getElementById('openApp').onclick = function(e){
    //通过iframe的方式试图打开APP，如果能正常打开，会直接切换到APP，并自动阻止a标签的默认行为
    //否则打开a标签的href链接
    var decodeIfrSrc = decodeURIComponent(appUrlScheme);
    var ifr = document.createElement('iframe');
    ifr.src = decodeIfrSrc ;
    ifr.style.display = 'none';
    document.body.appendChild(ifr);
    setTimeout(function(){
      document.body.removeChild(ifr);
    }, 1000);
  };
  
  if (!location.hash) {
    location.hash = '#once';
    setTimeout(function () {
      if(document.all) {
        document.getElementById('openApp').click();

      //其它浏览器
      } else {
        var e = document.createEvent("MouseEvents");
        e.initEvent("click", true, true);
        document.getElementById("openApp").dispatchEvent(e);
      }
    }, 700);
  }

  function isWx (u) {
    return u.match(/MicroMessenger/i) == "MicroMessenger";
  }
}
</script>
</body>
</html>
