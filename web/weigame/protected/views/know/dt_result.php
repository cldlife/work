<img src="<?php echo $this->currentUser['avatar']?>" style="left:-3000px;position: absolute;">
<img src="<?php echo $share_logo?>" style="left:-3000px;position: absolute;">
<div class="more-entrance" style="position: fixed; width: 40px;height: 40px; right: 15px; top: 0;margin-top: 60%;">
	<a href="http://www.v1h5.com/cps/30/index.shtml" onclick="_hmt.push(['_trackEvent', 'ad_float_45x45', 'click', 'tg_bird'])">
		<img src="http://s.wanzhucdn.com/ui/img/icon/desc_game.gif">
	</a>
</div>
<div class="delyer">
	<div class="delyer-mask"></div>
	<div class="delyer-cont"><!--sure-->
		<div class="top">
			<p>谨慎操作</p>
		</div>
		<div class="bottom">
			<button class="delbtn"></button>
			<button class="cancel">不删了</button>
		</div>
	</div>
</div>
<div class="container container-10">
	
	<div class="title">
		<img src="<?php echo $knowGame['center_img']?>" />
	</div>
	
	<div class="data" style="margin-top: 12%;">
		<!-- <div class="info">你对某某的了解程度是...</div> -->
		<div class="dataWrap">
			<div id="J_data" class="inner">
				<img style="bottom: -100%;" src="http://f.shiyi11.com/ui/img/wxgame/20151022/heart_content.png" />
				<p>80%</p>
			</div>
		</div>
		<div class="desc">
			<?php echo $this->currentUser['nickname']?>
		</div>
	</div>
	
	<div class="condition nomatch">
			<?php if (Utils::isFromWeixin() && !$question['qa_fromqq']) :?>
			<?php if ($isPay == 0) :?>
				<div class="desc">
					需支付2元红包给<?php echo $nickname ?>
				</div>
			<?php endif; ?>
			<div class="tocheck" onclick="_hmt.push(['_trackEvent', 'lookanswer', 'click', 'tg_lookanswer{$this->fromkey}'])"> 
				<a href="http://<?php echo $wxpayDomain;?>/wxpay/knowqa.html?fromkey=<?php echo $this->fromkey?>&qid=<?php echo $question['id']?>&type=<?php echo $this->type;?>&level=<?php echo $this->level;?>&<?php echo $this->loginTypeSuuidParams?>">偷看正确答案<i class="icon-to"></i></a>
			</div>
			<?php endif; ?>
	</div>
	
	<div class="active actives">
		<a href="<?php echo $randDomain . "/{$randControllerName}/{$randUrlLink}.html?{$this->loginTypeSuuidParams}";?>">
			我也要玩
		</a>
		<a href="<?php echo $qaInfo['msg_link_chiba']?>">
			你有新消息
		</a>
	</div>

	<div class='bn' style="margin-top: 15px;width: 100%;overflow: hidden;padding-top: 16.5%;position: relative;">
    <ul style="position: absolute;width: 100%;top: 0;left: 0; padding:0 10px; -webkit-box-sizing:border-box; box-sizing:border-box; ">
      <li style='list-style: none;'>
        <a href='http://h5.zhudade.cn/checkpay/index20160954.html' style='width: 100%;margin-right: 0;' onclick="_hmt.push(['_trackEvent', 'adv_banner_600x100', 'click', 'tg_eq'])"><img  src='http://f.shiyi11.com/ui/img/wxgame/list/bn600_eq.png' width='100%'></a>
      </li>
  </ul></div>
	<?php if ($answerList) :?>
	<div class="result">
		<div class="result-bd">
			<ul>
				<?php foreach ($answerList as $answer) :?>
					<li <?php if($answer['user_info']['uid'] == $this->currentUser['uid']):?> class="current" <?php endif;?>>
						<div class="avatar">
							<div class="inner">
								<img src="<?php echo $answer['user_info']['avatar'];?>" />
							</div>
						</div>
						<div class="info">
							<h4><?php echo $answer['user_info']['nickname'];?></h4>
							<p><?php echo $answer['answer_desc']?></p>
						</div>
						<div class="record">
							<?php echo $answer['matching_percent'];?>%
						</div>
						<?php if($answer['user_info']['uid'] == $this->currentUser['uid']):?>
						<div class="close" id="<?php echo $answer['id']?>_<?php echo $answer['qid']?>"></div>
						<?php endif;?>
					</li>
				<?php endforeach;?>
			</ul>
		</div>
	</div>
	<?php endif;?>
	
	<div class="hint">
		<a href="http://abcde.wx.shihuoapp.com/templete/kefu.html">客服QQ｜常见问题</a>
	</div>
	
</div><!-- /container -->
<script type="text/javascript">

var num = 80;

var dataAnim = (function () {
	
	var data = document.getElementById('J_data'),
			imgData = data.children[0],
			txtData = data.children[1],
			initNum = 0,
			timer = null;
			
	var textAnim = function (num) {
		if(initNum > num) {
			clearTimeout(timer);
			return;
		}
//				console.log(initNum);
		txtData.innerHTML = initNum + '%';
		initNum ++;
		timer = setTimeout(function () {
			textAnim(num);
		}, 20);
	};
	
	var imgAnim = function (num) {
		setTimeout(function () {
			imgData.style.bottom = (num - 100) + '%';
		}, 0)
	};
	
	var dataAnimFn = function (num) {
		this.init(num);
	};
	
	dataAnimFn.prototype.init = function (num) {
		textAnim(num);
		imgAnim(num);
	};
	
	return dataAnimFn;
	
}());

new dataAnim(<?php echo $matchingPercent?>);


function DelItem() {
	var _this = this;
	this.item = $('.result .current');
	this.closeBtn = this.item.find('.close');
	this.delyer = $('.delyer');
	this.inner = $('.delyer-cont');
	this.delbtn = this.delyer.find('.delbtn');
	this.cancelbtn = this.delyer.find('.cancel');
	this.closeBtn.on('touchend', function () {
		_this.show();
	})
	this.cancelbtn.on('touchend', function (e) {
		e.preventDefault();
		_this.hide();
	})
	this.delbtn.on('touchend', function (e) {
		e.preventDefault();
		_this.addClass();
		$(this).on('touchend', function (e) {
			e.preventDefault();
			//console.log('删除了');
			_this.remove();
			_this.hide();
			$(this).off('touchend');
			var id_qid=_this.closeBtn.attr('id').split('_');
				location.href = 'http://<?php echo $wxpayDomain;?>/wxpay/knowdelanswer.html?qid='+id_qid[1]+'&fromkey=<?php echo $this->fromkey;?>&type=<?php echo $this->type;?>&level=<?php echo $this->level;?>&<?php echo $this->loginTypeSuuidParams?>';
		})
	})
}

DelItem.prototype = {
	show : function () {
		this.delyer.show();
	},
	hide : function () {
		this.delyer.hide();
	},
	addClass : function () {
		this.inner.addClass('sure');
	},
	remove : function () {
		this.item.remove();
	}
};

new DelItem();

</script>
<?php if ($isFromWeixin && $sinaShareDomain && $knowGame['jssdk_mpids']) : ?>
<script type="text/javascript">
wx.config({
  debug: <?php echo APP_DEBUG ? 'true' : 'false'; ?>, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
  appId: "<?php echo $weixinJssdkConfig['appid'];?>", // 必填，公众号的唯一标识
  timestamp: "<?php echo $weixinJssdkConfig['timestamp'];?>", // 必填，生成签名的时间戳
  nonceStr: "<?php echo $weixinJssdkConfig['noncestr'];?>", // 必填，生成签名的随机串
  signature: "<?php echo $weixinJssdkConfig['signature'];?>",// 必填，签名，见附录1
  jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareQZone'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});

var shareTitle = '<?php echo $this->title;?>';
var shareLink = '<?php echo "http://{$sinaShareDomain}/" . Utils::enDecrypt($shareLink) ;?>';
var shareImgUrl = '<?php echo $this->currentUser['avatar'] ? $this->currentUser['avatar'] : $qaInfo['share_logo_wwcxy'];?>';
wx.ready(function(){  

  /*分享到朋友圈*/
  wx.onMenuShareTimeline({ 
    title: shareTitle, // 分享标题
    desc: '', // 分享描述
    link: shareLink, // 分享链接
    imgUrl: shareImgUrl, // 分享图标
    success: function () { 
    },
    cancel: function () { 
    }
  });
  
  /*分享到微信好友*/
  wx.onMenuShareAppMessage({
    title: shareTitle, // 分享标题
    desc: '', // 分享描述
    link: shareLink, // 分享链接
    imgUrl: shareImgUrl, // 分享图标
    success: function () { 
    },
    cancel: function () { 
    } 
  });
  
  /*分享到QQ好友*/
  wx.onMenuShareQQ({
    title: shareTitle, // 分享标题
    desc: '', // 分享描述
    link: shareLink, // 分享链接
    imgUrl: shareImgUrl, // 分享图标
    success: function () { 
    },
    cancel: function () { 
    }
  });
  
  /*分享到QQ空间*/
  wx.onMenuShareQZone({
    title: shareTitle, // 分享标题
    desc: '', // 分享描述
    link: shareLink, // 分享链接
    imgUrl: shareImgUrl, // 分享图标
    success: function () { 
    },
    cancel: function () { 
    }
  });
});
</script>
<?php endif; ?>
