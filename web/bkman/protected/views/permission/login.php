<div class="layout bg_login">
	<div id="login">
		<h1 style="width: 200px;text-align: left;font-size:24px"><img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/logo/160.png" width="40" style="border-radius: 6px;vertical-align:top"> 管理后台</h1>
		<ul>
			<li><label>用户名：</label><input type="text" id="username" name="username" class="txt" /></li>
			<li><label>密　码：</label><input type="password" id="passwd" name="passwd" class="txt" /></li>
			<li><a id="loginButton" name="loginButton" href="javascript:;" class="confirm_btn"><span>登录</span></a>　<span id="showMessage" name="showMessage" class="c_orange"></span></li>
			<li style="color:#666">请妥善保管密码。<br>如遇到问题，请及时联系管理员。</li>
		</ul>
		<div style="color:#666"><a href="" target="_blank">author by chenlidong</a></div>
	</div>
</div>

<script type="text/javascript">
$(function(){
  var EventAction = {
  	message:{
    	"login_error": "请检查用户名或密码",//1001
    	"login_miss_param": "用户名或密码错误",//1002
    	"login_no_access": "该账号无权限登录管理后台"//1003
  	},

  	validateLogin:function(){
  		var username = $("#username").val();
  		var passwd = $("#passwd").val();
  		if (!$.trim(username) || !$.trim(passwd)) {
  			EventAction.showMessage(EventAction.message.login_miss_param);
  			return false;
  		}
  		return true;

  	},

  	login:function(_this){
  		if(EventAction.validateLogin()){
  			var username = $("#username").val().replace(/\s+/g,"");
  			var passwd = $("#passwd").val();
  			var _sh_token_ = '<?php echo Yii::app()->getRequest()->getCsrfToken()?>';
  			$.DeAjax(_this.id,{
  				type:'POST',
  				dataType:'json',
  				url:'<?php echo $this->getDeUrl('permission/login')?>',
  				data:{'username':username, 'passwd':passwd, '_sh_token_':_sh_token_},
  				success: function(res){
  	  				console.log(res);
  					if (res.code == 1){
  						if($("#framemenu", window.parent.document)[0]){
  							window.parent.location.href = window.parent.location.href;
  						}else{
  							window.location.href="<?php echo $this->getDeUrl('main/index')?>";
  						}
  					} else {
  						if(res.code == 1001){
  							EventAction.showMessage(EventAction.message.login_miss_param);
  						} else if(res.code == 1002) {
  							EventAction.showMessage(EventAction.message.login_error);
  						} else if(res.code == 1003){
  							EventAction.showMessage(EventAction.message.login_no_access);
  						}
  					}
  				},
  				error: function() {
                }
  			})
  		}
  	},

  	showMessage:function(msg){
  		$("#showMessage").html(msg);
  	}
  }

  $("#loginButton").click(function(){
  	EventAction.login(this);
  });

  $(document).keydown(function(event) {
		var e = e || event;
		var keycode = e.which || e.keyCode;
		if (keycode == 13){
			$("#loginButton").click();
		}
	});
});
</script>
