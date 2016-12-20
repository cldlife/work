<style type="text/css">
#view { padding: 51px 0 60px; }
#container .label { padding: 0 5.3333%; height: 40px; line-height: 40px; background: #f5f5f5; font-size: 12px; }
#container .label li { display: inline-block; }
.list .avatar { margin: 5px 10px 0 0; width: 30px; height: 30px; }
.list .major { display: -webkit-box; display: box; display: -ms-flexbox; display: -webkit-flex; display: flex; }
.list .major p { -webkit-box-flex: 1; box-flex: 1; -webkit-flex: 1; flex: 1; padding-top: 3px; margin-left: 5px; color: #b2b2b2; font-size: 12px; }
.list .icon-rap { margin-top: 7px; }
.icon-rap { display: inline-block;width: 24px;height: 24px;background: url(<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/icon_rap.png) no-repeat center;-webkit-background-size: cover;
background-size: cover;
}
.tab-bar li.two {background: #333;}
</style>
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
<div class="bar nav-bar">
	<header>
		<ul>
	     <li><a href="<?php echo $this->getDeUrl("hougong/notice/index") ?>">通知<?php if($this->isNotice === 0): ?><i class="icon-msg"></i><?php endif;?></a></li>
		<li class="on"><a href="<?php echo $this->getDeUrl("hougong/notice/visitor") ?>">访客<?php if($this->isVisitor === 0): ?><i class="icon-msg"></i><?php endif;?></a></a></li>
		</ul>
	</header>
</div><!-- /nav-bar -->

<div id="container">

<div class="label">
	<ul>
		<li>总访问人次：<?php echo $countVisitor?>人</li>
		<li>今日访问：<?php echo $todayCountVisitor ?></li>
	</ul>
</div>

<div id="J_droploadList" class="list">
	<ul id="J_visitors">
	</ul>
</div>
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
var view = $('#view');

var wait = new Wait(); // 等待组件

var dialog = new Dialog();  // 对话框组件

var dropload = new Dropload({ // 上拉加载
	type : 'post',
	surl : '<?php echo $this->getDeUrl("hougong/notice/visitorajax")?>',
	data :  {'_sh_token_' : Token},
	joint : function (data) {
		var visitorList = '';
		$.each(data, function(i, item) {
			if (item.isrel == 0) {
				visitorList += '<li data-viuid="' + item.viuid + '" data-socia="' + item.values + '" class="avatarAndInfo"><div class="avatar"><a href="'+ item.link +'"><img src="' + item.avatar + '"></a></div><div class="info"><div class="major"><h4>' + item.nickname + '</h4><p>' + item.time + '</p></div><div class="minor">' + item.mnickname + '</div></div></li>'
			} else if (item.isrel == 1) {
				visitorList += '<li data-viuid="' + item.viuid + '" data-values="' + item.values + '" class="avatarAndInfo"><div class="avatar"><a href="'+ item.link +'"><img src="' + item.avatar + '"></a></div><div class="info"><div class="major"><h4>' + item.nickname + '</h4><p>' + item.time + '</p></div><div class="minor">' + item.mnickname  + '</div></div><i class="icon-rap"></i></li>';
			}
		});
		return visitorList;
	},
	wait : wait,
	nodata : function () {
	wait.over('没有更多访客了～', view);
	},
})

function Visitors(opts) {  // 访客
	this.visitorsContainerDom = $('#J_visitors');
	this.bindEvt();
	this.rapData = {
		type : opts.type,
		surl : opts.surl,
		getData : opts.getData
	}
}

Visitors.prototype = {
	bindEvt : function () {
		var _this = this;
		this.visitorsContainerDom.on('click', '.icon-rap', function () {
			var rapBtnDom = $(this);
			var visitorDom = rapBtnDom.parents('#J_visitors > li');
			var visitorValues = visitorDom.data('values');
			var visitorViuid = visitorDom.data('viuid');
			var visitorRelDom = visitorDom.find('.minor');
			wait.remove();
			dialog.render({
				desc : '把TA抢为奴隶需要消耗' + visitorValues + '金币',
				btns : true
			})
			.show()
			.bindCancelEvent(function () {
				dialog.hide();
			})
			.bindSureEvent(function () {
				wait.loadCenter(view);
				_this.fetchData(visitorRelDom, rapBtnDom, _this.rapData.getData(visitorViuid));
				dialog.hide();
			})
		})
	},
	fetchData : function (visitorRelDom, rapBtnDom, data) {
		var _this = this;
		console.dir(data);
		$.ajax({
			type : _this.rapData.type,
			url : _this.rapData.surl,
			async : true,
			dataType: "json",
			data : {'uid' : data.uid, '_sh_token_' : data._sh_token_},
			success : function (data) {
				console.dir(data);
				wait.remove();
				wait.loadBottom(dropload.container);
				if (data.code == 0) {
					dialog.render({
						title : '抢走失败',
						desc : '对不起，您的金币不足。',
						btns : false
					})
					.show()
					.bindFineEvent(function () {
						console.log('抢人失败');
						dialog.hide();
					});
				} else if (data.code == 1) {
					dialog.render({
						title : '成功抢走',
						desc : '恭喜你，TA已经成为你的奴隶。',
						btns : false
					})
                    .show()
					.bindFineEvent(function () {
						dialog.hide();
						rapBtnDom.remove();
						visitorRelDom.html('TA是你的奴隶');
					});
				} else {
					dialog.render({
						title : '抢走失败',
						desc : '系统出现故障，稍后重试！',
						btns : false
					})
					.show()
					.bindFineEvent(function () {
						console.log('抢人失败');
						dialog.hide();
					});
				}
			}
		});
	}
};
var t = new Date().getTime();
new Visitors({
	type : 'post',
	surl : '<?php echo $this->getDeUrl("hougong/mine/relation") ?>'+ '?t=' + t,
	getData : function (uid) {
		return {'uid' : uid, '_sh_token_' : Token}
	}
});
	
</script>


