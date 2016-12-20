<style type="text/css">
#view { background: #f5f5f5; padding-bottom: 60px; }
.uesrInfo .dignity { margin-left: -7px; }
.uesrInfo .icon-help { position: absolute; top: 10px; right: 10px; opacity: .8; }
.uesrInfo .icon-help a { height: 100%; }

.mask { z-index: 100; display: none; }
.dialog { z-index: 101; }
.dispatch .info { padding-left: 5%; padding-right: 5%; padding-bottom: 3.3333%; }
.dispatch .desc { display: -webkit-box; display: box; display: -ms-flexbox; display: -webkit-flex; display: flex; }
.dispatch .desc .input { -webkit-box-flex: 1; -webkit-flex: 1; flex: 1; height: 40px; padding: 8px 16px; -webkit-box-sizing: border-box; box-sizing: border-box; background: #f5f5f5; border: 1px solid #e6e6e6; border-radius: 4px; overflow: hidden; }
.dispatch .desc .input input { height: 24px; background: transparent; width: 100%; }
.dispatch .wave { margin-left: 8px; margin-top: -6px; }
.dispatch .wave > img { width: 33px; height: 33px; }
.dispatch .wave > p { font-size: 12px; transform: scale(.8333); }
.dispatch button:nth-of-type(1) { color: #999; }
.help { margin-top: 35%; text-align: center; }
.help a { display: inline-block; line-height: 27px; padding: 0 10px; border: 1px solid #b3b3b3; border-radius: 6px; }
	
.icon-dispatch {display: inline-block;width: 65px;height: 20px;background: url(<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/icon_dispatch.png) no-repeat center;-webkit-background-size: cover;background-size: cover;cursor: pointer;
}
.icon-help {display: block;width: 20px;height: 20px;background: url(<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/icon_help.png) no-repeat center;-webkit-background-size: cover;background-size: cover;
}
.tab-bar li.one {background: #333;}
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

<div id="J_dispatch" class="dispatch dialog abscenter">
	<div class="info">
		<div class="label">给TA派发任务</div>
		<div class="desc">
			<div class="input"><input id="J_task" type="text" placeholder="输入任务" /></div>
			<div id="J_wave" class="wave">
				<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/desc_dice.png" />
				<p>随机选择</p>
			</div>
		</div>
	</div>
	<div class="active btns">
		<button id="J_btn_cancelTask">取消</button><button id="J_btn_sureTask">确定</button>
	</div>
</div>

<div id="container">
	
	<div class="uesrInfo rel">
		<div class="bg img-wrap rel">
			<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/top_bg.jpg"/>
		</div>
		<div class="avatar-suit habscenter">
			<div class="img-wrap">
				<img src="<?php echo $avatar; ?>"/>
			</div>
		</div>
		<div class="avatar-left abs">
			<h1><?php echo $nickname; ?></h1>
		</div>
		<div class="avatar-rihgt abs">
			<p class="dignity">身价<?php echo $values; ?>金</p>
		</div>
		<?php if ($mlink && $mavatar && $mnickname): ?>
		<div class="relUser abs avatarAndInfo">
			<div class="avatar">
				<a href="<?php echo $mlink; ?>">
					<img src="<?php echo $mavatar; ?>"/>
				</a>
			</div>
			<div class="info">
				<h2>我的主人</h2>
				<p><?php echo $mnickname ?></p>
			</div>
		</div>
		<?php endif; ?>
		<i id="J_helpBtn" class="icon-help"><a href="<?php echo $helpLink; ?>"></a></i>
	</div><!-- uesrInfo -->
	
<div id="J_droploadList" class="slaves">
<!--<div class="none"><img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/imgs/icon/desc_sad.png"><p>你还没有奴隶</p></div>-->
	<ul>
	</ul>
</div><!-- slaves -->
	<div class="bar tab-bar">
<footer>
<ul>
<li class="one"><a href="<?php echo $this->getDeUrl("hougong/mine/u{$this->currentUser['uid']}") ?>">后宫</a></li>
<li class="two"><a href="<?php echo $this->getDeUrl("hougong/notice/index") ?>">消息<?php if($this->isVisitor === 0 || $this->isNotice === 0): ?><i class="icon-msg"></i><?php endif; ?></a></li>
<li class="three"><a href="<?php echo $this->getDeUrl("") ?>">个人中心</a></li>
</ul>
</footer>
</div><!-- /tab-bar -->
</div><!-- /container -->
<script type="text/javascript">

/* ================  公用的数据 S =================== */
var Token = '<?php echo Yii::app()->request->getCsrfToken();?>';
var page = 1; 
// 页面
var view = $('#view');

// 遮罩
var mask = $('#J_mask');

// 等待
var wait = new Wait();

// 获取金币的展示
var gainCoins = new GainCoins();

// 对话框
var dialog = new Dialog();

// 派发任务对话框
function Dispatch() {
this._init();
}

Dispatch.prototype = {
_init : function () { // 对话框dom
	this.containerDom = $('#J_dispatch');
	this.taskDom = $('#J_task');
	this.wave = $('#J_wave');
	this.cancelBtn = $('#J_btn_cancelTask');
	this.sureBtn = $('#J_btn_sureTask');
},
bindCancelEvt : function (cancelCallback) {  // 对话框中取消按钮绑定事件 参数为取消之后的回调
	var that = this;
	this.cancelBtn.off('click');
	this.cancelBtn.on('click', function () {
		cancelCallback();
		that.cancel();
	})
	return this;
},
bindSureEvt : function (sureCallback) {  // 对话框中确定按钮绑定事件 参数为确定之后的回调
	var that = this;
	this.sureBtn.off('click');
	this.sureBtn.on('click', function () {
		that.task = that.taskDom.val();
		sureCallback(that.task);
		that.sure();
	})
	return this;
},
bindRandomEvt : function () {
	var that = this;
	this.wave.on('click', function () {
		that.taskDom.val(that.getRandomTask());
	})
	return this;
},
getRandomTask : function () {
	var taskList = this.containerDom.data('tasks') || [];
	var taskListWidth = taskList.length;
	var randomId = Math.floor(Math.random() * taskListWidth);
	var randomTask = taskList[randomId]
	return randomTask;
},
emport : function (data) {
	this.containerDom.data('tasks', data);
	return this;
},
start : function () {  // 显示对话框
	this.containerDom.show();
	return this;
},
end : function () {  // 结束对话框
	this.containerDom.hide();
	return this;
},
cancel : function () {  // 对话框取消方法
	this.containerDom.hide();
	this.taskDom.val('');
	console.log('取消');
	return this;
},
sure : function () {  // 空
	this.containerDom.hide();
	mask.hide();
	wait.loadBottom(dropload.container).setlay(12);
	return this;
}
};

var dispatch = new Dispatch();  // 初始化对话框
dispatch.bindCancelEvt(function () {  // 对话框取消之后绑定回调
mask.hide();
})

// 我的奴隶对象扩展
function MySlaves() {
Slave.call(this, arguments);
}

MySlaves.prototype = new Slave();

MySlaves.prototype.descRender = function (descInfo, dispatchData, gainData) { // 根据奴隶状态显示具体情况
this.bdDom = $('<div class="bd"></div>');
switch (this.status) {
	case 0 : // 休息
		this.rest(descInfo);
		this.loseRender(descInfo);
		this.dispatchRender(dispatchData);
	break;
	case 1 : // 工作
		this.work(descInfo);
	break;
	case 2 : // 完成
		this.finish(descInfo);
		this.bindGainEvt(dispatchData, gainData);
	break;
	default : 
		console.log('数据出错了！');
	break;
}
this.innerContainer.append(this.bdDom);
};

MySlaves.prototype.dispatchRender = function (dispatchData) { // 派发按钮生成方法
var _this = this;
var dispatchBtn = $('<i class="icon-dispatch"></i>');
dispatchBtn.on('click', function () {
	mask.show();
	dispatch
	.start()
	.emport(<?php echo $randTask; ?>)
	.bindRandomEvt()
	.bindSureEvt(function (task) { // 派发任务对话框确定之后的回调
		_this.task = task;  // 对话框中输入的任务数据
		wait.loadCenter(view).setlay(102);
		_this.report({  // 异步请求数据 派发任务
			type : dispatchData.type,
			surl : dispatchData.surl,
			getData : dispatchData.getData,
			successFn : function (data) {
				if (data.code == 0) {
	                dialog
					.render({
						title : '温馨提示',
						desc : 'TA已经不是您的奴隶,您没有这个权限!',
						btn : false
					})
					.bindFineEvent(function () {
						dialog.hide();
						mask.hide();
					}).show()
				} else {
				wait.loadBottom(dropload.container).setlay(12);
				dispatch.end();
				mask.hide();
				_this.work(data);
	
				}
		}
		})
	})
})
this.bdDom.append(dispatchBtn);
};

MySlaves.prototype.loseRender = function (descInfo) {  // 金币被抢完状态 
if (descInfo && descInfo.coins_status == 1) {
var loseStr = '<div class="coin-none"><img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/desc_coin_none.png"><p>金币被抢完</p></div>';
this.bdDom.append(loseStr);
} 
};

MySlaves.prototype.bindGainEvt = function (dispatchData, gainData) {  // 获取金币绑定事件
var _this = this;
this.bdDom.on('click', function () {
	wait.loadCenter(view).setlay(102);
	_this.report({  // 异步请求数据 根据获得金币 之后改休息状态
		type : gainData.type,
		surl : gainData.surl,
		getData : gainData.getData,
		successFn : function (data) {
			wait.loadBottom(dropload.container).setlay(12);
			mask.hide();
			console.log('获得了' + data.remain_coins + '金币');
			gainCoins.render('+' + data.remain_coins).show(function () {
				_this.rest(data);
				_this.loseRender(data);
				_this.dispatchRender(dispatchData);
			});
		}
	})
})
}

// 上拉加载 // 这里是奴隶们出现的异步数据 你可以写 ⬇ 
var dropload = new Dropload({ // 初始化页面的异步
type : 'post',
surl : '<?php echo $this->getDeUrl("hougong/mine/slaveListajax"); ?>',
data : {'uid' : '<?php echo $uid; ?>', '_sh_token_' : Token},
joint : function (data, target) {
	$.each(data, function (i, item) {
		var slave = new MySlaves().init(target);
		slave.userRender(item);
		slave.descRender(item, { // 派发任务的异步
			type : 'post',
			surl : '<?php echo $this->getDeUrl("hougong/mine/task")?>',
			getData : function (item) {
			   return {'info' : item.task, 'ruid' : item.uid, '_sh_token_' : Token};
			}

		}, {  // 获取金币的异步
			type : 'post',
			surl : '<?php echo $this->getDeUrl("hougong/mine/getcoinusers")?>',
			getData : function (item) {
				return {'taskid' : item.id, 'suid' : item.uid, 'uid' :<?php echo $uid; ?>, '_sh_token_' : Token}
			}
		});
	})
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
</script>