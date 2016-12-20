<style type="text/css">
#view { background: #39364d; }
#container { padding-bottom: 4%; }
.list { margin: 0 4%; overflow: hidden; overflow: hidden; }
.list > li { margin-top: 2.8985%; background: #4d4a5f; border-radius: 6px; }
.list > li > a { padding: 5.7971%; position: relative; color: #fff; }
.list .show { float: left; width: 50px; height: 50px; border-radius: 50%; overflow: hidden; }
.list .show > img { width: 50px; height: 50px; border-radius: 50%; }
.list .info { height: 50px; margin: 0 10px 0 70px; overflow: hidden; font-size: 16px; }
.list .info > h4 { margin-top: 13px; }
.list .player-amount { position: absolute; bottom: 0; right: 0; margin: 0 2.8985% 2.8985% 0; font-size: 12px; -webkit-transform: scale(.8333); transform: scale(.8333); }

</style>
<div id="view">
<div id="container">
	<ul class="list">
	<?php foreach ($gamelist as $game) : ?>
		<li>
	    	<a herf="javascript:;" data-url="<?php echo $this->getDeUrl("app/zhuangbi/". $game['url'])?>">
				<div class="show">
					<img src="<?php echo WEB_QW_APP_FILE_UI_URL . $game['imgurl'];?>" />
				</div>
				<div class="info">
					<h4 class="ellipsis"><?php echo $game['title'];?></h4>
				</div>
				<div class="player-amount">
					<?php echo $game['players'];?>玩过
				</div>
			</a>
		</li>
	 <?php endforeach; ?>
	</ul>
</div>
</div>
<script>
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
  	$(".list a").on(funName, function(e) {
  	  var rjson = {};
      rjson.title = $(this).find(".info h4").text();
      rjson.link = $(this).attr("data-url");
      rjson.is_inside = 1;
      rjson.hide_nav = 0;
      rjson.skin_color = "#39364d";
      WanZhuJSBridge.createWebPage(JSON.stringify(rjson));
    });
  });
});
</script>