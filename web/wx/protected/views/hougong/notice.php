<style type="text/css">
#view { padding: 56px 0 60px; }
.list .icon-msg { margin: 16px 15px 0 8px; }
.tab-bar li.two {background: #333;}
string {margin-left: 3px;margin-right: 3px;}
.major a {display: -webkit-inline-box;text-decoration: none;color: #333;}
</style>
<div class="bar nav-bar">
<header>
	<header>
	<ul>
		<li class="on"><a href="<?php echo $this->getDeUrl("hougong/notice/index") ?>">通知 <?php if($this->isNotice === 0): ?><i class="icon-msg"></i><?php endif;?></a></li>
		<li><a href="<?php echo $this->getDeUrl("hougong/notice/visitor") ?>">访客<?php if($this->isVisitor === 0): ?><i class="icon-msg"></i><?php endif;?></a></a></li>
	</ul>
</header>
</div><!-- /nav-bar -->
 
<div id="container">
	
		<div id="J_droploadList" class="list">
					<ul id="J_msgs">
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
var Token = '<?php echo Yii::app()->request->getCsrfToken();?>';
var wait = new Wait();
new Dropload({
	type : 'post',
	surl : '<?php echo $this->getDeUrl("hougong/notice/noticeajax")?>',
	data :  {'_sh_token_' : Token},
	joint : function (data) {
		var msgItemList = '';
		$.each(data, function (i, item) {
			if(item.status == 1) {
				msgItemList += '<li data-read="' + 0 + '"><div class="info"><div class="major">' + item.content + '</div><div class="minor">' + item.time + '</div></div></li>';
			} else if (item.status == 0) {
				msgItemList += '<li data-read="' + 1 + '"><i class="icon-msg"></i><div class="info"><div class="major">' + item.content + '</div><div class="minor">' + item.time + '</div></div></li>';
			}
		})
		return msgItemList;
	},
	wait : wait,
	nodata : function () {
		wait.over('没有更多消息了～', view);
	},
})

function Massage() {
	this.msgListDom = $('#J_msgs');
	this.bindEvt();
}

Massage.prototype = {
	bindEvt : function () {
		var _this = this;
		this.msgListDom.on('click', 'li', function () {
			_this.read($(this));
		})
	},
	read : function (item) {
		if (item.data('read') == 1) {
			var indicator = item.find('.icon-msg');
			indicator.hide(300, function () {
				indicator.remove();
				item.data('read', 0);
			})
		}
	}
}

new Massage();

	
</script>