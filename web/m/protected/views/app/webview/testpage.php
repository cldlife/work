<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8" />
<link rel="dns-prefetch" href="<?php echo WEB_QW_APP_FILE_DOMAIN?>">
<link rel="dns-prefetch" href="<?php echo WEB_QW_APP_DYNAMIC_FILE_DOMAIN?>">
<meta name="robots" content="none" />
<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="format-detection" content="telephone=no">
<title></title>
<style>
html,body{background:#f8f8f8; -webkit-touch-callout: none;}
body,input,button,textarea,select{font-family:Helvetica;-webkit-text-size-adjust:none;font:normal 14px/1.5;outline:none;color:#3A3A3A;}
a{text-decoration:none;color:#576b95}
h1,h2,h3{margin:0;padding:0;font-size:100%}
ul, li{list-style-type: none;margin:0;padding:0;}
img{border:0 none}
</style>
<script src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/common/jquery-1.8.3.min.js"></script>
<!-- <script src="http://s.wanzhucdn.com/ui/js/app/WanZhuJSBridge.js"></script> -->
</head>
<body>
测试页<br><br>

-- Debug header头信息 --<br>
fromapp : <?php echo $fromapp?><br>
sysversion : <?php echo $sysversion?><br>
sid : <?php echo $sid?><br>

<div id="debug-log"></div>
<br>-- Debug 交互--<br>
<input type="button" id="createWebPage" value="创建外网webview页"> <input type="button" id="createWebPageInside" value="创建内网webview页"> <input type="button" id="refreshWebPage" value="刷新页面"> <input type="button" id="closeWebPage" value="关闭页面"> <input type="button" id="onBackHome" value="回首页"> 
<br><br>

<input type="button" id="onShareWeixinSpace" value="朋友圈分享"> <input type="button" id="onShareWeixinSpaceScreenshot" value="朋友圈截屏分享"> <br>
<input type="button" id="onShareWeixin" value="微信好友分享"> <input type="button" id="onShareWeixinScreenshot" value="微信好友截屏分享">
<br><br>
<input type="button" id="onShareWeibo" value="微博分享"> <input type="button" id="onShareQQ" value="QQ好友分享"> <input type="button" id="onShareQQSpace" value="QQ空间分享">
<br><br>
<input type="button" id="onHomePage" value="打开用户主页">
<br><br>
<input type="button" id="onImageshot" value="iOS截图"> <input type="button" id="getImgInfoString" value="获取imgInfoString"> <input type="button" id="setImgInfoString" value="设置imgInfoString">

<script type="text/javascript">
function connectWanZhuJSBridge(callback) {
  if (window.WanZhuJSBridge) {
    callback(window.WanZhuJSBridge);
  } else {
    document.addEventListener('WanZhuJSBridgeReady', function() {
      callback(window.WanZhuJSBridge);
    }, false);
  }
}

connectWanZhuJSBridge(function(WanZhuJSBridge){
  $(function() {
  	var funName = "click";
  	$("#createWebPage").on(funName, function(e) {
  	  var rjson = {};
      rjson.title = "百度首页";
      rjson.link = "http://www.baidu.com";
      rjson.is_inside = 0;
      rjson.display_nav = 0;
      WanZhuJSBridge.createWebPage(JSON.stringify(rjson));
      console.log(WanZhuJSBridge.getWebPageInfo());
    });
  	
  	$("#createWebPageInside").on(funName, function(e) {
  	  var rjson = {};
      rjson.title = "Webview测试页";
      rjson.link = "http://www.shihuo.me/app/webview/testpage.html";
      rjson.is_inside = 1;
      rjson.display_nav = 1;
      WanZhuJSBridge.createWebPage(JSON.stringify(rjson));
      console.log(WanZhuJSBridge.getWebPageInfo());
    });
  	
  	$("#refreshWebPage").on(funName, function(e) {
  	  WanZhuJSBridge.refreshWebPage();
  	  console.log(WanZhuJSBridge.getRefreshFlg());
    });

  	$("#closeWebPage").on(funName, function(e) {
  	  WanZhuJSBridge.refreshWebPage(false);
  	  console.log(WanZhuJSBridge.getRefreshFlg());
    });
  	
    $("#onBackHome").on(funName, function(e) {
  	  WanZhuJSBridge.onBackHome();
    });
    $("#onHomePage").on(funName, function(e) {
  	  var rjson = {};
      rjson.uid = "1";
  	  WanZhuJSBridge.onHomePage(JSON.stringify(rjson));
      console.log(WanZhuJSBridge.getHomePageInfo());
    });
    
    $("#onShare").on(funName, function(e) {
  	  share(0);
    });
    $("#onShareWeixin").on(funName, function(e) {
  	  share(2);
    });
    $("#onShareWeixinScreenshot").on(funName, function(e) {
  	  shareScreenshot(2);
    });
    $("#onShareWeixinSpace").on(funName, function(e) {
  	  share(1);
    });
    $("#onShareWeixinSpaceScreenshot").on(funName, function(e) {
  	  shareScreenshot(1);
    });
    $("#onShareWeibo").on(funName, function(e) {
  	  share(3);
    });
    $("#onShareQQ").on(funName, function(e) {
  	  share(4);
    });
    $("#onShareQQSpace").on(funName, function(e) {
  	  share(5);
    });
    $("#onImageshot").on(funName, function(e) {
  	  WanZhuJSBridge.onImageshot();
      console.log('onImageshot');
  	  var timer = setInterval(function () {
  	  	if (WanZhuJSBridge.getImgInfoString() != undefined) {
  	  	  alert(WanZhuJSBridge.getImgInfoString());
          clearInterval(timer);
  	  	}
    	console.log(typeof WanZhuJSBridge.getImgInfoString());
  	  }, 500)
    });
    $("#getImgInfoString").on(funName, function(e) {
  	  alert(WanZhuJSBridge.getImgInfoString());
    });
    $("#setImgInfoString").on(funName, function(e) {
  	  WanZhuJSBridge.setImgInfoString('{"code":1,"fileInfo":{"aid":"1231482068079302","file_type":"image\/png","file_uri":"\/up\/161218\/","file_name":"0934_1231482068079302_949180fb328d80416fa9f406c622fd47","width":51,"height":50}}');
      console.log('setImgInfoString');
    });

    function share (type) {
   	  var rjson = {};
 	  rjson.type = type;
  	  rjson.screenshot = 0;
   	  rjson.screenshot_url = "";
 	  rjson.img_url = "http://tf.shiyi11.com/ui/img/test/IMG_00951.JPG";
   	  rjson.title = "装逼神器";
 	  rjson.url = "";
 	  rjson.desc = "描述";
  	  WanZhuJSBridge.onShare(JSON.stringify(rjson));
 	  console.log(WanZhuJSBridge.getShareInfo());
    }
    
    function shareScreenshot (type) {
   	  var rjson = {};
      rjson.type = type;
  	  rjson.screenshot = 1;
   	  rjson.screenshot_url = "http://www.baidu.com";
  	  rjson.img_url = "";
   	  rjson.title = "七宗罪";
  	  rjson.url = "";
  	  rjson.desc = "描述";
  	  WanZhuJSBridge.onShare(JSON.stringify(rjson));
 	  console.log(WanZhuJSBridge.getShareInfo());
    }
  });
});
</script>
</body>
</html>