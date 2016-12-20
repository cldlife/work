<style type="text/css">
#view { background: #39364d; }
#container { overflow: hidden; padding: 13.3333% 0; }
.detail { margin: 0 5.3333%; padding: 5.9701%; background: #fff; border-radius: 6px; }
.detail > .show { padding-top: 84.7457%; overflow: hidden; }
.detail > .show > img { width: 100%; }
.detail > .active { margin-top: 11.8644%; overflow: hidden; }
.detail > .active > div { margin-top: 5.0847%; text-align: center;  }
.detail > .active > .nickname { background: #ecebf0; padding: 15px 25px; height: 20px; border-radius: 25px; }
.detail > .active > .nickname > input { width: 100%; line-height: 20px; height: 20px; background: transparent; font-size: 16px; text-align: center; }
.detail > .active > .nickname > input::-webkit-input-placeholder { color: #bcb8cc; }
.detail > .active > .sure { border-radius: 25px; height: 50px; background: #7965db; }
.detail > .active > .sure > a { color: #fff; line-height: 50px; font-size: 16px; }
</style>
<div id="container">
	<?php echo CHtml::beginForm($this->getDeUrl("app/zhuangbi/zb{$catagory}", array('step' => 1)), 'post', array('id' => 'submitForm', 'name' => 'submitForm'))?>

	<div class="detail">
		<div class="show rel">
			<img class="abscenter" src="<?php echo WEB_QW_APP_FILE_UI_URL . $imgurl;?>" />
		</div>
		<div class="active">
			<div class="nickname">
				<input type="text" id="J_nickname" name='username' maxlength="4" placeholder="输入您的姓名" />
			</div>
			<div class="sure">
				<a id="J_sureBtn">确定</a>
			</div>
		</div>
<?php echo CHtml::endForm();?>	
</div>
</div>
<script type="text/javascript">

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
		$("#submitForm").submit();

	}
}

new Form();
</script>
