<style type="text/css">
#view { background: #2e3033; padding-bottom: 30px; }
#container { margin: 0 38px; }
.label { padding: 30px 0; color: #fff; text-align: center; font-size: 16px; }
.ewm { padding: 20px; font-size: 18px; background: #fff; border-radius: 6px; }
.ewm img { width: 100%; }
.ewm p { padding: 0 15px; margin-top: 15px; text-align: center; }
</style>
<div id="container">
  <div class="label"><?php echo $nickname;?>邀请你进入他的房间一起玩谁是卧底</div>
  <div class="ewm">
    <img src="<?php echo $qr_url;?>" />
    <p>长按识别二维码立即加入</p>
  </div>
</div><!-- /container -->
<script type="text/javascript">
/* ================  jsjdk分享 S =================== */
  wx.config({
    debug: <?php echo APP_DEBUG ? 'true' : 'false'; ?>, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: "<?php echo $weixinJssdkConfig['appid'];?>", // 必填，公众号的唯一标识
    timestamp: "<?php echo $weixinJssdkConfig['timestamp'];?>", // 必填，生成签名的时间戳
    nonceStr: "<?php echo $weixinJssdkConfig['noncestr'];?>", // 必填，生成签名的随机串
    signature: "<?php echo $weixinJssdkConfig['signature'];?>",// 必填，签名，见附录1
    jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
  });
  wx.ready(function(){
      wx.onMenuShareTimeline({
       title: '<?php echo $shareTitle ?>', // 分享标题
       link: "<?php echo $sharelink?>", // 分享链接
       imgUrl: '<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/sq_shuishiwodi.jpg', // 分享图标
       success: function () {},
       cancel: function () {}
      });
      wx.onMenuShareAppMessage({
       title: '<?php echo $shareTitle ?>', // 分享标题
       link: "<?php echo $sharelink?>", // 分享链接
       imgUrl: '<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/sq_shuishiwodi.jpg', // 分享图标
       success: function () {},
       cancel: function () {}
      });
  });
</script>
