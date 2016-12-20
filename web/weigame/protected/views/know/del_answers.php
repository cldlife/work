<img src="<?php echo $this->currentUser['avatar']?>" style="left:-3000px;position: absolute;">
<img src="<?php echo $share_logo?>" style="left:-3000px;position: absolute;">
<div class="container">
	<div id="J_waiting" class="waiting"><!--loadImg-->
		<div class="spinner">
		  <div class="spinner-container container1">
		    <div class="circle1"></div>
		    <div class="circle2"></div>
		    <div class="circle3"></div>
		    <div class="circle4"></div>
		  </div>
		  <div class="spinner-container container2">
		    <div class="circle1"></div>
		    <div class="circle2"></div>
		    <div class="circle3"></div>
		    <div class="circle4"></div>
		  </div>
		  <div class="spinner-container container3">
		    <div class="circle1"></div>
		    <div class="circle2"></div>
		    <div class="circle3"></div>
		    <div class="circle4"></div>
		  </div>
		</div>
		<div class="waiting-word"></div>
	</div>
	<div class="pay" style="width: 88%;">
		
		<div class="amount">
			<label>金额</label>
			<p>50元</p>
		</div>
		
		<div class="paybtn wxpay">
			微信支付删答案
		</div>

		<div id="prompt" style="position:fixed;background:rgba(0,0,0,.6);padding:14px;color:#fff;left:50%;top:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);border-radius:6px;display:none;">
  		支付成功
		</div>
	</div>
</div><!-- /container -->
<script type="text/javascript">
$(document).ready(function(){
  $('.wxpay').on("touchend", function () {
  	var $prompt = $('#prompt');
  	wx.config({
  	  debug: <?php echo APP_DEBUG ? 'true' : 'false'?>, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
  	  appId: "<?php echo $weixinJssdkConfig['appid'];?>", // 必填，公众号的唯一标识
  	  timestamp: "<?php echo $weixinJssdkConfig['timestamp'];?>", // 必填，生成签名的时间戳
  	  nonceStr: "<?php echo $weixinJssdkConfig['noncestr'];?>", // 必填，生成签名的随机串
  	  signature: "<?php echo $weixinJssdkConfig['signature'];?>",// 必填，签名，见附录1
  	  jsApiList: ['chooseWXPay'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
  	});

  	wx.ready(function(){
      $("#J_waiting").show();
      $.ajax({
        type : 'POST',
        dataType: 'json',
        url : '<?php echo $this->getDeUrl("know/wxpay");?>',
        data : <?php echo $wxpayParams; ?>,
        async : true,
        success : function (data) {
          $("#J_waiting").hide();
          if (data.code == 1) {
            wx.chooseWXPay({
              timestamp: '' + data.wxpayPre.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
              nonceStr: data.wxpayPre.nonceStr, // 支付签名随机串，不长于 32 位
              package: data.wxpayPre.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
              signType: data.wxpayPre.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
              paySign: data.wxpayPre.paySign, // 支付签名
              success: function (res) {
            	var str = JSON.stringify(res);
            	if (res.errMsg == "chooseWXPay:ok") {
            	  $("#J_waiting").show();
            	  $prompt.html('加载中...').show().fadeOut(5000);
            	  window.location.href = '<?php echo $randDomain."/{$randControllerName}/{$randUrlLink}/tm{$qid}.html?{$this->loginTypeSuuidParams}"?>';
            	} else {
                  $prompt.html('支付失败，请重试！').show().fadeOut(5000);
                  setTimeout(function(){location.reload();}, 5000);
            	}
              }
            });
          } else {
            $prompt.html('提交失败，请重试！').show().fadeOut(5000);
            setTimeout(function(){$("#J_waiting").show();location.reload();}, 5000);
          }
        },
        error: function() {
          $("#J_waiting").hide();
      	  $prompt.html('网络异常，请重试！').show().fadeOut(5000);
        }
      });
    });
  });
});
</script>