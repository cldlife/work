<img src="<?php echo $this->currentUser['avatar']?>" style="left:-3000px;position: absolute;">
<img src="<?php echo $share_logo?>" style="left:-3000px;position: absolute;">
<div class="container">
	
	<div class="title" >
		<img src="<?php echo $center_img?>" />
	</div>
	
	<div class="user">
		<div class="avatar">
			<div class="inner">
				<img src="<?php echo $this->currentUser['avatar']?>" />
			</div>
		</div>
		<div class="nick" style="color: #fff;">
			<?php echo $this->currentUser['nickname']?>
		</div>
	</div>
	
	<div class="active" style="margin-top: 24%; width: 80%;">
		<a id="J_link" href="<?php echo "{$randDomain}/{$randControllerName}/{$randUrlLink}.html?step=1&{$this->loginTypeSuuidParams}";?>"  data-href="<?php echo "{$randDomain}/{$randControllerName}/{$randUrlLink}.html?step=1&{$this->loginTypeSuuidParams}";?>">
			<img src="<?php echo $ct_button?>"/>
		</a>
	</div>
	
	<div id="J_agreement" class="agreement">
		<span class="on"><i></i></span>
		<a href="http://abcde.wx.shihuoapp.com/templete/agreement.html">我已阅读并同意《用户使用协议》</a>
	</div>
	
</div><!-- /container -->


<script type="text/javascript">

var Agreement = (function () {

var btnLink = document.getElementById('J_link'),
		agree = document.getElementById('J_agreement').getElementsByTagName('span')[0],
		flag = true,
		alink = btnLink.getAttribute('data-href'),
		timer = null;

var swtchState = function () {
	if(flag) {
		btnLink.setAttribute('href', alink);
		agree.className = 'on';
		return;
	} 
	agree.className = '';
	btnLink.setAttribute('href', 'javascript:;');
};

var bindEvent = function () {
	agree.addEventListener('click', function () {
		clearTimeout(timer);
		timer = setTimeout(function () {
			flag = !flag;
			swtchState();
		}, 200)
	}, false);
};

var AgreementFn = function () {
	this.init();
};

AgreementFn.prototype.init = function () {
	bindEvent();
};

return AgreementFn;

}());

new Agreement();

</script>
