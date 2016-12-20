<style type="text/css">
#view { padding-bottom: 20px; }
.top { width: 100%; padding-top: 71.0666%; overflow: hidden; }
.top > img { width: 100%; }
.bt { padding-top: 84.8%; }
.bt > img { width: 100%; }
.form { width: 100%; font-size: 18px; }
.form > div { width: 66.666%; margin: 0 auto; -webkit-box-sizing: border-box; box-sizing: border-box; line-height: 48px; height: 50px; border-radius: 25px; }
.form > div + div { margin-top: 6.6666%; }
.form > div.text { background: #ebeff5; border: 1px solid #ecebf0; padding: 9px 10px; }
.form > div.submit { background: #7965db;border: 1px solid #7965db; color: #fff; box-shadow: 0 5px 0 rgba(119,102,204,.2); overflow: hidden; }
.form > div.text > input { display: block; text-align: center; height: 30px; width: 100%; background: transparent; }
.form > div.text > input::-webkit-input-placeholder { color: #aaa3cc; }
.form > div.submit > input { display: block; width: 100%; height: 100%; color: #fff; background: transparent; }

.load { position: fixed; z-index: 9; margin-top: -15%; width: 150px; height: 40px; border-radius: 6px; background: rgba(0,0,0,.6); color: #fff; line-height: 40px; display: none; }
.icon-load { width: 14px; height: 14px; left: 20px; top: 12px; -webkit-animation: anim 2s infinite cubic-bezier(0,0,1,1); animation: anim 2s infinite cubic-bezier(0,0,1,1); }
@-webkit-keyframes anim {
	0% { -webkit-transform: rotate(0deg); transform: rotate(0deg); }
	100% { -webkit-transform: rotate(360deg); transform: rotate(360deg); }
}
@keyframes anim {
	0% { -webkit-transform: rotate(0deg); transform: rotate(0deg); }
	100% { -webkit-transform: rotate(360deg); transform: rotate(360deg); }
}
.load .info { margin-left: 40px; }
</style>
<div id="J_load" class="load abscenter">
	<img class="icon-load abs" src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/app/qizongzui/icon_load.png" />
	<p class="info">正在诊断中...</p>
</div>

<div id="container">
	<div class="top rel">
		<img class="abscenter" src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/app/qizongzui/top_bg.jpg" />	
	</div>
	<div class="bt rel">
		<img class="abscenter" src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/app/qizongzui/bt_bg.jpg" />
		<div class="form abscenter">
			<div class="text"><input id="J_nickname" type="text" name="nickname" maxlength="4" placeholder="输入你的名字" /></div>
			<div class="submit"><input id="J_sureBtn" type="button" value="开始测试" /></div>
		</div>
	</div>
</div>
<script type="text/javascript">
var _sh_token_ = '<?php echo Yii::app()->request->getCsrfToken();?>';
var load = $('#J_load');
function connectWanZhuJSBridge(callback) {
  if (window.WanZhuJSBridge) {
    callback(window.WanZhuJSBridge);
  } else {
    document.addEventListener('WanZhuJSBridgeReady', function() {
      callback(window.WanZhuJSBridge);
    }, false);
  }
}

function Form() {
	this.nicknameDom = $('#J_nickname');
	this.sureBtnDom = $('#J_sureBtn');
	this.bindEvt();
}

Form.prototype = {
	bindEvt : function () {
		var _this = this, nicknameVal;
		this.sureBtnDom.on('click', function () {
			nicknameVal = _this.getValue(); 
			nicknameVal ? _this.validFn(nicknameVal) : _this.invalidFn();
		})
	},
	getValue : function () {
		return $.trim(this.nicknameDom.val());
	},
	invalidFn : function () {
		return false;
	},
	validFn : function (nickname) {
        load.show();
	  	window.setTimeout(function(){
	  		$.ajax({
	  			type: 'POST',
	            dataType: 'json',
	            url: '<?php echo $this->getDeUrl("app/seven/sin");?>',
	            data: {"nickname": nickname, '_sh_token_':_sh_token_},
	            success: function (res){
	              if (res.code) {
            	    connectWanZhuJSBridge(function(WanZhuJSBridge){
            		  $(function() {
            		  	  var rjson = {};
            		      rjson.title = '七宗罪';
            		      rjson.link = '<?php echo $this->getDeUrl("app/seven/index", array('nickname' => ''));?>' + res.nickname;
            		      rjson.is_inside = 1;
            		      rjson.hide_nav = 0;
            		      rjson.skin_color = "#7965db";
            		      WanZhuJSBridge.createWebPage(JSON.stringify(rjson));
            		      load.hide();
            		  });
            		});
	              }
	            },
	            error: function() {
	            	load.hide();
	            }
			});
		},2000)
	}
}

new Form();
</script>