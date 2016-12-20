<style type="text/css">
#view { padding-bottom: 15px; }
.user { padding-top: 10.6666%; background: -webkit-linear-gradient(#444790, #432f6c); background: linear-gradient(#444790, #432f6c); color: #fff; }
.user .avatar-suit { width: 21.3333%; margin: 0 auto; }
.user .nick { margin-top: 3.3333%; text-align: center; font-size: 18px; }
.user .asset { margin-top: 3.3333%; background: rgba(36,28,53,.3); }
.user .asset ul { display: -webkit-box; display: box; display: -ms-flexbox; display: -webkit-flex; display: flex; }
.user .asset li { -webkit-box-flex: 1; -webkit-flex: 1; text-align: center; padding: 5px 0; position: relative; }
.user .asset li+li { position: relative; }
.user .asset li+li::before { content: ""; width: 1px; height: 40px; background: rgba(255,255,255,.1); position: absolute; left: 0; top: 50%; margin-top: -20px; }
.user .asset li>p { margin-top: 2px; font-size: 18px; }
.user .icon-add { right: 0; margin-right: 13.3333%; cursor: pointer; }

.list { padding: 6.6666%; }
.list ul { overflow: hidden; }
.list li { float: left; width: 46.875%; position: relative; }
.list li:nth-child(odd) { margin-right: 6.25%; }
.list li:nth-child(n+3) { margin-top: 6.25%; }
.list .show { padding-top: 133.3333%; border-radius: 14px; overflow: hidden; }
.list .active { bottom: 15px; z-index: 2; background: #ffe566; border-radius: 4px; }
.list .active a { white-space: nowrap; line-height: 25px; color: #1a1a1a; padding: 0 20px; }
.remind { text-align: center; }
.remind>button { background: #443474; border-radius: 4px; padding: 0 10px; line-height: 30px; color: #fff; }
.remind>p { margin-top: 5px; color: #B2B2B2; font-size: 12px; }

.remind-cont { position: fixed; z-index: 101; top: -25px; left: 0; width: 100%; display: -webkit-box; display: box; display: -ms-flexbox; display: -webkit-flex; display: flex; background: rgba(0,0,0,.5); line-height: 25px; font-size: 12px; color: #fff; text-align: center; }
.remind-cont .label { -webkit-box-flex: 1; -webkit-flex: 1; }
.remind-cont .icon-close { margin-top: 7px; margin-left: 15px; }
.remind-cont .icon-still { margin: 0 15px; vertical-align: middle; }
.remind-cont .icon-box { top: 10px; margin-left: 10px; }
.remind-cont .icon-direct { margin: 9px 20px 0 0; }

.mask { z-index: 101; display: none; }

.dialog { z-index: 102; }

.coin { position: fixed; z-index: 102; background: #fff; border-radius: 8px; width: 80%; display: none; }
.coin .hd { text-align: center; color: #ff7f00; font-size: 16px; line-height: 48px; border-bottom: 2px solid #ffbb33; }
.coin .bd li { padding: 12px 15px 12px 20px; display: -webkit-box; display: box; display: -ms-flexbox; display: -webkit-flex; display: flex; border-bottom: 1px solid #e6e6e6; }
.coin .bd .icon-coin { margin: 4px 10px 0 0; }
.coin .bd li>p { -webkit-box-flex: 1; -webkit-flex: 1; flex: 1; line-height: 24px; font-size: 15px; }
.coin .bd li>em { display: inline-block; width: 60px; height: 24px; background: url(<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/desc_coinBg.jpg) no-repeat center; -webkit-background-size: cover; background-size: cover; font-style: normal; font-weight: 700; font-size: 13px; color: #ff7f00; text-align: center; line-height: 24px; cursor: pointer; }
.coin .ft { line-height: 50px; text-align: center; color: #b2b2b2; font-size: 12px; }
.icon-coin {display: inline-block;width: 16px;height: 16px;background: url(<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/icon_coin.png) no-repeat center;-webkit-background-size: cover;background-size: cover;
}
</style>
	</head>
	<body>
		<div id="view">
			
			<div id="J_dialog" class="dialog abscenter">
				<div class="info">
					<!--<div class="label">温馨提示</div>
					<div class="desc">金币购买成功，已为你充值488金币</div>-->
				</div>
				<div class="active"><!--btns-->
					<!--<button>取消</button>-->
					<!--<button>确定</button>-->
				</div>
			</div>
			
			<div id="J_mask" class="mask"></div>
			
			<div id="J_coinShop" class="coin abscenter">
				<div class="hd">
					购买金币
				</div>
				<div class="bd">
					<ul id="J_coinList">
						<!--<li>
							<i class="icon-coin"></i>
							<p>18888金币</p>
							<em>￥88.00</em>
						</li>-->
					</ul>
				</div>
				<div class="ft">
					本站采用微信安全支付
				</div>
			</div>
			
			<div id="J_collectTxt" class="remind-cont">
				<i class="icon-close"></i>
				<div class="label">
					点击<i class="icon-still"></i>中的收藏按钮<i class="icon-box"><i></i></i>
				</div>
				<i class="icon-direct"></i>
			</div>
			
			<div id="container"> 
				
				<div class="user">
					<div class="avatar-suit">
						<div class="img-wrap">
			        	<?php if($avatar):?>
							<img src="<?php echo $avatar; ?>"/>
						<?php else:?>
							<img class="abscenter" src="http://f.shiyi11.com/ui/img/m/avatar_none.png" />
						<?php endif;?>
						</div>
					</div>
					<div class="nick">
						<h1><?php echo $nickname; ?></h1>
					</div>
					<div class="asset">
						<ul>
							<li>
								<h3>身价</h3>
								<p><?php echo $values; ?></p>
							</li>
							<li>
								<h3>金币</h3>
								<p id="J_myCoins"><?php echo $coins; ?></p>
								<i id="J_openBtn" class="icon-add vabscenter"></i>
							</li>
						</ul>
					</div>
				</div><!-- /user -->
				
				<div id="J_droploadList" class="list">
					<ul>
						<?php if ($gameList): ?>
						<?php foreach($gameList as $game): ?>
						<li>
							<div class="show img-wrap">
							<a href="<?php echo  $this->getDeUrl($game['href']) ?>">
								<img src="<?php echo WEB_QW_APP_FILE_UI_URL.'/'.$game['img'] ?>" />
                            </a>
							</div>
							<div class="active habscenter">
							<a href="<?php echo  $this->getDeUrl($game['href']) ?>">
									进入游戏
                            </a>
							</div>
						</li>
					<?php endforeach; ?>
		            <?php endif; ?>
					</ul>
					
					<div id="J_waiting" class="waiting rel"><!-- waiting-load: 控制数据load之后呈现 -->
					<div class="spinner abscenter">
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
				</div>
					
				</div>
				
				<div class="remind">
					<button id="J_collectBtn">添加收藏方便玩</button>
					<p>客服QQ: 2180004048</p>
				</div>
				
			</div><!-- /container -->
			
		</div><!-- /view -->
		
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

<script type="text/javascript">
var view = $('#view');
var myCoins = $('#J_myCoins');  // 我的金币dom对象 在购买金币成功之后 里面的值需要更新

/* 
 * 对话框生成
 * render方法 @param {title,desc,btns} btns 是否有两个按钮
 * bindFineEvent方法 单个按钮确定回调
 * bindSureEvent方法 两个按钮时确定回调
 * bindCancelEvent方法 两个按钮时取消回调
 */
var dialog = new Dialog(); // 对话框 在金币购买时候成功与否都需要弹出对话框提示

var mask = $('#J_mask');  // 遮罩 购买金币或者对话框显示时候 都需要显示

var wait = new Wait();
// 收藏
function Pocket() {
	this._init();
}

Pocket.prototype = {
	_init : function () {
		this.txtShowBtn = $('#J_collectBtn');
		this.collectTxt = $('#J_collectTxt');
		this.txtHideBtn = this.collectTxt.find('.icon-close');
		this.bindEvent();
	},
	bindEvent : function () {
		var that = this;
		this.txtShowBtn.on('click', function () {
			that.txtShow();
		})
		this.txtHideBtn.on('click', function () {
			that.txtHide();
		})
	},
	txtShow : function () {
		this.collectTxt.stop().animate({'top' : '0'}, 500);
	},
	txtHide : function () {
		this.collectTxt.stop().animate({'top' : '-25'}, 500);
	}
};

new Pocket();

// 交易市场
function Cointrade(opts) {
	this._init(opts);
}

Cointrade.prototype = {
	_init : function (opts) {
		var coinDatas = opts.coinDatas || [],
				pickCall = opts.pickCall;
		this.coinDatas = coinDatas; // 金币商品列表
		this.openBtn = $('#J_openBtn');
		this.coinShop = $('#J_coinShop');
		this.coinList = $('#J_coinList');
		this.render();
		this.bindEvent(pickCall);
	},
	render : function () { // 摆摊
		var coinListStr = '';
		$.each(this.coinDatas, function (index, item) {
			var coinItemStr = '<li><i class="icon-coin"></i><p>' + item.coinNum + '金币</p><em data-cid="' + item.cid + '">¥ ' + item.value + '.00</em></li>';
			coinListStr += coinItemStr;
		})
		this.coinList.html(coinListStr)
	},
	bindEvent : function (pickCall) {
		var that = this;
		this.coinList.on('click', 'em', function () { // 顾客挑选
			var coinType = $(this).data().cid;
			var coinItem = that.coinDatas[coinType];
			pickCall(coinItem);
		})
	},
	shopOpen : function () { // 开启市场
		this.coinShop.show(350);
	},
	shopCurtain : function () { // 关闭市场
		this.coinShop.hide(350)
	}
};

// 交易市场生成
var cointrade = new Cointrade({ 
	coinDatas : [ // 金币商品列表
	{ cid : 0, coinNum : 188, value : 0.1 },
    { cid : 1, coinNum : 888, value : 18 },
    { cid : 2, coinNum : 5088, value : 88 },
    { cid : 3, coinNum : 23888, value : 388 },
	],
	pickCall : function (coinItem) {  // 挑选之后回调 参数为所选金币类对象
	var fee = coinItem.value;
	wx.config({
	  debug: <?php echo APP_DEBUG ? 'true' : 'false'; ?>, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
	  appId: "<?php echo $weixinJssdkConfig['appid'];?>", // 必填，公众号的唯一标识
	  timestamp: "<?php echo $weixinJssdkConfig['timestamp'];?>", // 必填，生成签名的时间戳
	  nonceStr: "<?php echo $weixinJssdkConfig['noncestr'];?>", // 必填，生成签名的随机串
	  signature: "<?php echo $weixinJssdkConfig['signature'];?>",// 必填，签名，见附录1
	  jsApiList: ['chooseWXPay'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
	});

	wx.ready(function(){
	  cointrade.shopCurtain();
	  wait.loadCenter(view).setlay(102);
	  var data = <?php echo $wxpayParams; ?>;
	  data.fee = fee;
	  $.ajax({
	    type : 'POST',
	    dataType: 'json',
	    url : '<?php echo $this->getDeUrl("usercenter/wxpay");?>',
	    data : data,
	    async : true,
	    success : function (data) {
	      wait.remove().setlay(12);
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
	        	  	wait.loadCenter(view).setlay(102);
	        	  	$('#J_coinShop').hide();
	        	  	dialog
	        	  	.render({
						title : '温馨提示',
						desc : '金币购买成功,已为你充值' + coinItem.coinNum + '金币',
						btns : false
					})
					.bindFineEvent(function () {
						dialog.hide();
						mask.hide();
						var oldCoins = Number(myCoins.html());
						myCoins.html(oldCoins + coinItem.coinNum);
					}).show();
	        	} else {
	        		mask.hide();
	        		dialog
	              	.render({
						title : '温馨提示',
						desc : '金币购买失败,请重试',
						btns : false
					})
					.bindFineEvent(function () {
						dialog.hide();
					}).show();
	        	}
	          },
	          cancel: function(res) {  
               //支付取消  
               mask.hide();
               }  
	        });
	      } else {
	      	mask.hide();
	      	$('#J_coinShop').hide();
	      	dialog
	  	.render({
			title : '温馨提示',
			desc : '提交失败,请重试',
			btns : false
		})
		.bindFineEvent(function () {
			dialog.hide();
		}).show();	    

		  }
	    },
	    error: function() {
	    	mask.hide();
	    	$('#J_coinShop').hide();
	    	dialog
 	  	.render({
				title : '温馨提示',
				desc : '网络异常,请重试',
				btns : false
			})
			.bindFineEvent(function () {
				dialog.hide();
			}).show();

	    },
	      cancel: function(res) {  
           mask.hide(); 
        }  
	  });
	});
		

	}

});

// 添加金币按钮
$('#J_openBtn').on('click', function () {
	cointrade.shopOpen();
	mask.show();
	mask.off('click');
	mask.on('click', function () {
		cointrade.shopCurtain();
		mask.hide();
		mask.off('click');
	})
})

</script>
