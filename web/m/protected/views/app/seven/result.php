<style type="text/css">
#view { padding-bottom: 20px; overflow: hidden; }
.title { margin-top: 45px; text-align: center; font-size: 18px; position: relative; line-height: 30px; }
.title::before, .title::after { content: ""; display: inline-block; width: 0px; height: 0px; border: 6px solid transparent; }
.title::before { border-right-color: #e6e6e6; margin-right: 9px; }
.title::after { border-left-color: #e6e6e6; margin-left: 6px; }
.title span { color: #7766cc; font-weight: 500; margin-right: 5px;}
.canvas { margin-top: 13.3333%; width: 160%; margin-left: -30%; position: relative; }
.canvas canvas { /*margin-left: -15%; */}
.active { margin-top: 10.4477%; height: 75px; text-align: center; }
.active > a { color: #fff; right: auto; }
.active > a:nth-of-type(1) { left: 40%; }
.active > a:nth-of-type(2) { left: 60%; }
.active > a > img { width: 40px; height: 40px; }
.active > a > p { margin-top: 10px; font-size: 12px; letter-spacing: 1px; -webkit-transform: scale(.83333); transform: scale(.83333); }
.active > a > p {color: black;margin-top: 10px;font-size: 12px;letter-spacing: 1px;-webkit-transform: scale(.83333); transform: scale(.83333);
</style>
<div id="container">

<div class="title">
	<span><?php echo $nickname?></span>の七つの大罪チャート
</div>

<div class="canvas">
	<canvas id="canvas"></canvas>
</div>

<div class="active rel">
	<a class="abscenter" href="javascript:;" id ="onShareWeixinScreenshot">
		<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/icon/icon_wxhaoyou.png" />
		<p>微信好友</p>
	</a>
	<a class="abscenter" href="javascript:;" id="onShareWeixinSpaceScreenshot">
		<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/icon/icon_wxpengyouquan.png" />
		<p>朋友圈</p>
	</a>
</div>

</div>

<script src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/common/Chart.min.js"></script>
<script type="text/javascript">
	
	Chart.defaults.global.defaultFontSize = 14;  // 数字的大小
  var config = {
      type: 'radar',  // 图标的类型 如：'bar'/'pie'
      data: { // 数据
				labels: ['暴食', '色欲', '強欲', '憤怒', '怠惰', '傲慢', '嫉妬'], // 要呈现的具体数据种类 图标会自动根据种类的数量改变图表
				datasets: [ // 数据在图表中的呈现个数 整个以对象表示
					{
						label: "指数", // 当前数据集在图表中呈现的小标识 
		        borderWidth: 1,	// 图标中数据范围描边的大小 如：0
		        borderColor: '#7766cc',	// 图标中数据范围描边的颜色 如：'rgba(186,38,255,1)'
		        backgroundColor: 'rgba(119,102,204,0.6)',	// 如：图标中数据范围填充的颜色 如：'rgba(186,38,255,0.2)'
		        pointBackgroundColor: '#7766cc',	// 如：图标中数据范围点填充的颜色 如：'rgba(186,38,255,1)'
		        pointBorderColor: '#fff',	// 如：图标中数据范围点描边的颜色 如：'rgba(186,38,255,1)'
		        pointHoverBackgroundColor: '#7766cc', // 如：图标中数据范围点hover之后填充的颜色 如：'#fff'
		        pointHoverBorderColor: '#fff', // 如：图标中数据范围点hover之后描边的颜色 如：'rgba(179,181,198,1)'
		        pointRadius : 5, // 如：图标中数据范围点半径大小 如：'5'
		        pointHoverRadius : 8, // 如：图标中数据范围点hover之后半径大小 如：'5'
		        data: ['<?php echo $content[0]?>', '<?php echo $content[1]?>', '<?php echo $content[2]?>', '<?php echo $content[3]?>', '<?php echo $content[4]?>', '<?php echo $content[5]?>', '<?php echo $content[6]?>'], // 在图标中反映数据每个种类具体数值大小 如：[3, 3, 5, 3, 3, 2, 0] 数组类型
        		}
				]
      },
      options: {
        animation: { // 动画
            duration: 1500, // 规定完成动画所花费的时间，以毫秒计 如1500
            onComplete: function(animation) {  // 动画完成之后的callback
                window.setTimeout(function() {
                }, 500);
            }
        },
        title: {	 // 大标题
            display: false,
            text: 'Custom Chart Title',
            fontColor: 'rgb(255, 99, 132)'
        },
        legend: { // 小标题
        		display: false,
        		position: 'top',
        		fullWidth : 'false',
        		labels : {
        			fontColor: 'red'
        		}
        },
        scale: {
          reverse: false,
          pointLabels: { // x轴数据配置
            fontColor: '#222', 
            fontSize: 14, 
        		},
					ticks: { // 数据呈现的伸缩性配置
						min: 0,
						max: 5,
						beginAtZero: true,
						maxTicksLimit: 5,
						fontColor: '#999',
						lineWidth: 2,
	        },
	        gridLines : { // 网格线条配置
	          color: 'rgba(0,0,0,0.1)'
	        },
			},
		},
	};
	
	var canvas = document.getElementById('canvas');
	new Chart(canvas, config);
	
	
	
	function Active() {
		this.active = $('#J_active');
		this.activeBtn = $('#J_activeBtn');
		this.bindEvt();
		this.run();
	}
	
	Active.prototype = {
		bindEvt : function () {
			var _this = this;
			this.activeBtn.on('click', function () {
				_this.hide();
			})
		},
		hide : function () {
			this.active.fadeOut(350);
		},
		run : function () {
			var _this = this;
			setTimeout(function () {
				_this.hide();
			}, 5000)
		}
	}
	
	new Active();
</script>
<script type="text/javascript">
function connectWanZhuJSBridge(callback) {
  if (window.WanZhuJSBridge) {
    callback(window.WanZhuJSBridge);
  } else {
    document.addEventListener('WanZhuJSBridgeReady', function() {
      callback(window.WanZhuJSBridge);
    }, false);
  }
}

connectWanZhuJSBridge(function(WanZhuJSBridge){
  $(function() {
    var funName = "click";
    $("#onShareWeixinScreenshot").on(funName, function(e) {
      shareScreenshot(2);
    });
    $("#onShareWeixinSpaceScreenshot").on(funName, function(e) {
      shareScreenshot(1);
    });
    
    function shareScreenshot (type) {
      var rjson = {};
      rjson.type = type;
      rjson.screenshot = 1;
      rjson.screenshot_url = "<?php echo $this->getDeUrl('app/seven/screenshot', array('nickname' => urlencode($nickname)))?>";
      rjson.img_url = "";
      rjson.skin_color = "#7965db";
      rjson.title = "七宗罪";
      rjson.url = "";
      rjson.desc = "";
      WanZhuJSBridge.onShare(JSON.stringify(rjson));
    }
  });
});
</script>
