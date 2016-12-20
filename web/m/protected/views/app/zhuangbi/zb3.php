<style type="text/css">
#view { background: #39364d; }
#container { padding: 5% 0; }
.show { margin: 0 5.333%; padding: 4.4776%; background: #4d4a5f; border-radius: 6px; }
.show > .inner { padding-top: 136.0665%; overflow: hidden; }
.show > .inner > img { width: 100%; }
.active { margin-top: 10.4477%; height: 75px; text-align: center; }
.active > a { color: #fff; right: auto; }
.active > a:nth-of-type(1) { left: 40%; }
.active > a:nth-of-type(2) { left: 60%; }
.active > a > img { width: 40px; height: 40px; }
.active > a > p { margin-top: 10px; font-size: 12px; letter-spacing: 1px; -webkit-transform: scale(.83333); transform: scale(.83333); }
</style>
<div id="container">
	
	<div class="show">
		<div class="inner rel">
			<img class="abscenter" src="<?php echo $imgurl?>" />	
		</div>
	</div>
	
	<div class="active rel">
		<a class="abscenter" id="onShareWeixinScreenshot">
			<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/icon/icon_wxhaoyou.png" />
			<p>微信好友</p>
		</a>
		<a class="abscenter" id="onShareWeixinSpaceScreenshot">
			<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/icon/icon_wxpengyouquan.png" />
			<p>朋友圈</p>
		</a>
	</div>
	
</div>
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
    $("#onShareWeixinScreenshot").on(funName, function(e) {
      shareScreenshot(2);
    });
  	$("#onShareWeixinSpaceScreenshot").on(funName, function(e) {
      shareScreenshot(1);
    });
    
    function shareScreenshot (type) {
      var rjson = {};
      rjson.type = type;
      rjson.screenshot = 0;
      rjson.screenshot_url = '';
      rjson.img_url = "<?php echo $imgurl?>";
      rjson.skin_color = "";
      rjson.title = "装逼神器";
      rjson.url = "";
      rjson.desc = "";
      WanZhuJSBridge.onShare(JSON.stringify(rjson));
    }
  });
});
</script>
