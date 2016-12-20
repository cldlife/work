<style type="text/css">
#view { background: #f5f5f5; /*padding-bottom: 10px; 不分享的时候的值*/ padding-bottom: 60px; }
.uesrInfo .dignity { margin: 2px -8px -1px 0; }
.dialog { z-index: 100; }
.mask { display: none; }
.remind { position: fixed; top: auto; bottom: 60px; left: 50%; z-index: 9; padding: 4px 10px; background: rgba(0,0,0,.6); box-shadow: 0 0 10px rgba(0,0,0,.35); color: #eee; border-radius: 4px; display: none; white-space: nowrap; }
.entrance { position: fixed; z-index: 19; bottom: 0; width: 100%; height: 50px; text-align: center; background: #1a1a1a; font-size: 15px; }
.entrance a { color: #fff; line-height: 50px; }
.icon-rap {display: inline-block;width: 24px;height: 24px;background: url(<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/icon_rap.png) no-repeat center;-webkit-background-size: cover;background-size: cover;}
.icon_snag {display: block;width: 65px;height: 22px;background: url(<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/icon_snag.png) no-repeat center;-webkit-background-size: cover;background-size: cover;}
.entrance { position: fixed; z-index: 19; bottom: 0; width: 100%; height: 50px; text-align: center; background: #1a1a1a; font-size: 15px; }
.entrance a { color: #fff; line-height: 50px; }
.help { margin-top: 35%; text-align: center; }
.help a { display: inline-block; line-height: 27px; padding: 0 10px; border: 1px solid #b3b3b3; border-radius: 6px; }
.slaves .slave .hd .info p {
    margin-left: -6px;
    font-size: 12px;
    color: #b2b2b2;
    -webkit-transform: scale(0.8333);
    transform: scale(0.8333);
}
</style>

<div id="J_gain-coins" class="gain-coins abscenter">
	<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/desc_gainCoins_bg.png" />
	<em class="abscenter"><!--+50--></em>
</div>

<div id="J_remind" class="remind abscenter">提示文本</div>

<div id="J_mask" class="mask"></div>

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

<div id="container">
	
	<div id="J_roomUser" data-uid="<?php echo  $uid; ?>" class="uesrInfo rel">
		<div class="bg img-wrap rel">
			<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/top_bg.jpg"/>
		</div>
		<div class="avatar-suit habscenter">
			<div class="img-wrap">
				<img src="<?php echo $avatar; ?>"/>
			</div>
		</div>
		<?php if ($muid == $this->currentUser['uid']){?>
		<div class="avatar-left abs">
			<h1><?php echo $nickname; ?></h1>
		</div>
		<div id="J_roomSnagContainer" class="avatar-rihgt abs">
			<p id="J_roomSocia" class="dignity" data-values="<?php echo $values; ?>">身价<?php echo $values; ?>金</p>
		</div>
		<?php }else{ ?>
       		<div class="avatar-left abs">
			<h1><?php echo $nickname; ?></h1>
			<p id="J_roomSocia" class="dignity rvalues" data-values="<?php echo $values; ?>">身价<?php echo $values; ?>金</p>
		</div>
		<div id="J_roomSnagContainer" class="avatar-rihgt abs">
			<i id="J_roomSnag" class="icon_snag"></i>
		</div>
		<?php }?>
		<?php if ($mlink && $mavatar && $mnickname): ?>
		<div id="J_roomMaster" class="relUser abs avatarAndInfo">
			<div class="avatar">
				<a href="<?php echo $mlink; ?>">
					<img src="<?php echo $mavatar; ?>"/>
				</a>
			</div>
			<div class="info">
				<h2>TA的主人</h2>
				<p><?php echo $mnickname; ?></p>
			</div>
		</div>
	   <?php endif; ?>
	</div><!-- uesrInfo -->
	
	<div id="J_droploadList" class="slaves">
		<ul>

		</ul>
	</div><!-- slaves -->

</div><!-- /container -->
<?php if ($share): ?>
<div class="entrance">
	<a href="<?php echo $this->getDeUrl("hougong/mine/u{$this->currentUser['uid']}")?>">
	 进入我的后宫
	</a>
</div>
<?php endif; ?>
<script type="text/javascript">
	/* ================  公用的数据 S =================== */
	var Token = '<?php echo Yii::app()->request->getCsrfToken();?>';
	// 页面
	var view = $('#view');
	
	// 遮罩
	var mask = $('#J_mask');
	
	// 等待
	var wait = new Wait();
	
	// 提示文本
	var remind = $('#J_remind');
	
	// 获取金币的展示
	var gainCoins = new GainCoins();
	
	// 对话框
	var dialog = new Dialog();
    <?php if ($isMaster == 'TRUE'):?>
     dialog
    .render({
		title : '温馨提示',
		desc : '你已经成为TA的奴隶!',
		btn : false
	})
	.bindFineEvent(function () {
		dialog.hide();
		mask.hide();
	})
	.show()
    <?php endif; ?>
	
	// 我的奴隶对象扩展
	function HisSlaves() {
		Slave.call(this, arguments);
	}
	HisSlaves.prototype = new Slave();
	
	HisSlaves.prototype.descRender = function (descInfo, rapData, robData) { // 根据奴隶状态显示具体情况
		this.bdDom = $('<div class="bd"></div>');
		switch (this.status) {
			case 0 :  // 休息
				this.rest(descInfo);
			break;
			case 1 : // 工作
				this.work(descInfo);
			break;
			case 2 : // 完成
				this.finish(descInfo);
				this.bindGainEvt(rapData, robData);
			break;
			default : 
				console.log('数据出错了！');
			break;
		}
		if (this.uid !== <?php echo $this->currentUser['uid']?>) {
			this.rapRender(rapData);
		}
			this.innerContainer.append(this.bdDom);
	};
	
	// 抢走按钮
	HisSlaves.prototype.rapRender = function (rapData) {  // 抢按钮绑定事件
		var _this = this;
		var rapbtn = $('<i class="icon-rap"></i>').appendTo(this.bdDom);
		rapbtn.on('click', function (event) {
			event.preventDefault();
			event.stopPropagation();
			mask.show();
			dialog
			.render({
				desc : '把TA抢为奴隶需要消耗' + _this.values + '金币',
				btns : true
			})
			.bindCancelEvent(function () {  // 取消抢事件绑定
				dialog.hide();
				mask.hide();
			})
			.bindSureEvent(function () {  // 确定抢事件绑定
				wait.loadCenter(view).setlay(102);
				_this.report({  //抢之后异步数据请求
					type : rapData.type,
					surl : rapData.surl,
					getData : rapData.getData,
					successFn : function (data) {
						console.dir(data);
				    	wait.loadBottom(dropdoad.container).setlay(1);
						if(data.code == 0) {  // 金币不足 抢人失败
							dialog
							.render({
								title : '抢人失败',
								desc : '对不起，您的金币不足',
								btn : false
							})
							.bindFineEvent(function () {
								dialog.hide();
								mask.hide();
							})
						} else if (data.code == 1) {  // 抢人成功
							dialog
							.render({
								title : '成功抢走',
								desc : '恭喜你，TA已成为您得奴隶。',
								btn : false
							})
							.bindFineEvent(function () {
								dialog.hide();
								mask.hide();
								_this.remove();
							})
						} else {
						 wait.remove();
						dialog.render({
							title : '抢人失败',
							desc : '系统出现故障,稍后再试!',
							btn : false
						})
						.bindFineEvent(function () {
							dialog.hide();
							mask.hide();
						})
						}
					}
				})
			})
			.show();
		})
		this.bdDom.append(rapbtn);
	};
	
	// 被抢走
	HisSlaves.prototype.remove = function () {
		var _this = this;
		this.container.hide(300, function () {
			_this.container.remove();
			_this = null;
		})
	};
	
	HisSlaves.prototype.bindGainEvt = function (rapData, robData) {  // 获取金币绑定事件
		var _this = this;
		this.bdDom.on('click', function (event) {
			wait.loadCenter(view).setlay(102);
			_this.report({
				type : robData.type,
				surl : robData.surl,
				getData : robData.getData,
				successFn : function (data) {
					wait.loadBottom(view).setlay(1);
					if (data.rob_coins) {
                      remind.html('恭喜你，成功偷了TA的' + data.rob_coins + '金币').fadeIn(300, function () {
						remind.delay(2000).fadeOut(300, function () {
							remind.html('');
						});
					  });
					  gainCoins.render('+' + data.rob_coins).show(function () {
						_this.valDom.html('+' + data.remain_coins);
						console.log('抢了' + data.rob_coins + '金币');
					  })
					} else if (data == 2) {;
						remind.html('您已经抢过TA的钱了,给他留点吧。').fadeIn(300, function () {
							remind.delay(2000).fadeOut(300, function () {
								remind.html('');
							});
						});
					} else {
						gainCoins.over().show(function () {
							_this.rest();

							if (this.uid !== <?php echo $this->currentUser['uid']?>) {
			                  this.rapRender(rapData);
		                    }
						})
					}
				}
			})
		})
	};

	// 上拉加载 // 这里是奴隶们出现的异步数据 你可以写 ⬇ 
	var dropdoad = new Dropload({ // 初始化页面的异步
		type : 'post',
		surl : '<?php echo $this->getDeUrl("hougong/mine/slaveListajax") ?>',
		data : {'uid' : '<?php echo $uid; ?>', '_sh_token_' : Token},
		joint : function (data, target) {
			$.each(data, function(i, item) {
				var t = new Date().getTime();
				var slave = new HisSlaves().init(target);
				slave.userRender(item);
				slave.descRender(item, {  // 抢奴隶的异步
					type : 'post',
					surl : '<?php echo $this->getDeUrl("hougong/mine/relation")?>' + '?t=' + t,
					getData : function (item) {
						return {'uid' : item.uid, '_sh_token_' : Token}
					}
				}, {  // 派金币的异步
					type : 'post',
					surl : '<?php echo $this->getDeUrl("hougong/mine/getcoinusers")?>' + '?t=' + t,
					getData : function (item) {
						return {'taskid' : item.id, 'suid' : item.uid, 'uid' :<?php echo $uid; ?>, '_sh_token_' : Token,}
					}
				})
			});
		},
		wait : wait,
			nodata : function () {
				wait.over('没有更多奴隶了～', view);
			},
			empty : function (that) {
				var noslavesStr = '<div class="none"><img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/desc_sad.png"><p>你还没有奴隶</p></div>';
				that.container.html(noslavesStr);
				var helpStr = '<div class="help"><a href="<?php echo $helpLink; ?>">点击查看如何抓奴隶?</a></div>';
				$('#J_droploadList').append(helpStr);
			}
	});
	
	function Room(opts) {
		this.roomUserDom = $('#J_roomUser');
		this.roomSnagContainer = $('#J_roomSnagContainer');
		this.roomSnagDom = $('#J_roomSnag');
		this.roomMasterDom = $('#J_roomMaster');
		this.roomSociaDom = $('#J_roomSocia');
		this.masterAvatarDom = this.roomMasterDom.find('img');
		this.masterNickDom = this.roomMasterDom.find('.info > p');
		this.uid = this.roomUserDom.data('uid');
		this.values = this.roomSociaDom.data('values');
		console.dir(opts);
		this.asynData = {
			type : opts.asynData.type,
			surl : opts.asynData.surl,
			getData : opts.asynData.getData
		};
		this.bindEvt();
	}
	
	Room.prototype = {
		bindEvt : function () {
			var _this = this;
			this.roomSnagDom.on('click', function () {
				mask.show();
				dialog
				.render({
					desc : '把TA抢为奴隶需要消耗' + _this.values + '金币',
					btns : true
				})
				.bindCancelEvent(function () {  // 取消抢事件绑定
					dialog.hide();
					mask.hide();
				})
				.bindSureEvent(function () { // 确定抢事件绑定
					wait.loadCenter(view).setlay(102);
					_this.fetchData();
				})
				.show();
			})
				},
		render : function (data) {
			this.masterAvatarDom.attr('src', data.avatar);
			this.masterNickDom.html(data.master);
			this.roomSociaDom.html('身价' + data.rvalues + '金').remove().appendTo(this.roomSnagContainer).css({'margin' : '0 0 0 -7px'});
			this.roomSnagDom.remove();
		},
		fetchData : function () {
			var _this = this;
			$.ajax({
				type : _this.asynData.type,
				url : _this.asynData.surl,
				async : true,
				data : _this.asynData.getData(_this.uid),
				success : function (data) {
					console.dir(data);
					wait.loadBottom(dropdoad.container).setlay(1);
					if(data.code == 0) {  // 金币不足 抢人失败
		                wait.remove();
						dialog.render({
							title : '抢人失败',
							desc : '对不起，您的金币不足',
							btn : false
						})
						.bindFineEvent(function () {
							dialog.hide();
							mask.hide();
						})
					} else if (data.code == 1) {  // 抢人成功
						wait.remove();
						dialog
						.render({
							title : '成功抢走',
							desc : '恭喜你，TA已成为您得奴隶。',
							btn : false
						})
						.bindFineEvent(function () {
							dialog.hide();
							mask.hide();
							_this.render(data);
						})
						$('.rvalues').html('身价'+data.rvalues+'金')
					} else {
						wait.remove();
						dialog.render({
							title : '抢人失败',
							desc : '系统出现故障,稍后再试!',
							btn : false
						})
						.bindFineEvent(function () {
							dialog.hide();
							mask.hide();
						})
					}
				}
			});
		}
	}
	var t = new Date().getTime();
	new Room({
	asynData : {
		type : 'post',
		surl : '<?php echo $this->getDeUrl("hougong/mine/relation")?>'+ '?t=' + t,
		getData : function (uid) {
        return {'uid' : uid, '_sh_token_' : Token}
		}
	}
})
	
</script>