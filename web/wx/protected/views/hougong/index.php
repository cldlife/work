<style type="text/css">
#view { overflow: hidden; position: relative; }
.bg { position: fixed; z-index: 0; left: 50%; top: 50%; -webkit-transform: translate(-50%, -50%); transform: translate(-50%, -50%); width: 100%; height: 100%; background-repeat: no-repeat; background-position: center; -webkit-background-size: cover; background-size: cover; }
@media only screen and (min-width: 1081px){
.bg { width: 640px !important; }
}
#container { position: absolute; bottom: 20px; width: 100%; z-index: 1; }
.btn-active { margin: 0 10%; }
.btn-active img { width: 100%; }
.agreement { margin-top: 15px; text-align: center; font-size: 12px; }
.agreement span { display: inline-block; width: 17px; height: 17px; border: 1px solid #fff; vertical-align: middle; margin-right: 6px; }
.agreement span.on { position: relative; }
.agreement span.on i { position: absolute; margin-top: -2px; left: 20%; top: 50%; width: 5px; height: 10px; border-right: 2px solid #fff; border-bottom: 2px solid #fff; -webkit-transform: rotate(45deg) translate(-50%, -50%); transform: rotate(45deg) translate(-50%, -50%); }
.agreement a { display: inline-block; color: #fff; }
</style>
<div class="bg" style="background-image: url(<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/bg.jpg);">
<div id="container">
	<div class="btn-active">
		<a id="J_link" href="<?php echo $this->getDeUrl("hougong/mine/u{$this->currentUser['uid']}")?>"  data-href="<?php echo $this->getDeUrl("hougong/mine/u{$this->currentUser['uid']}")?>">
			<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/hougong/btn_entrance.png"/>
		</a>
	</div>
	<div id="J_agreement" class="agreement">
		<span class="on"><i></i></span>
		<a href="http://f.shiyi11.com/static/wx/hougong/agreement.html">我已阅读并同意《用户使用协议》</a>
	</div>
</div><!-- /container -->
</div>
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