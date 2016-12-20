<style type="text/css">
#view { padding: 5% 0; overflow: hidden; background: #7965db; }
.label { text-align: center; color: #fff; font-size: 20px; }
.main { padding-bottom: 10px; background: #fff; margin: 5% 6.6667% 0; border-radius: 6px; overflow: hidden; } 
.main > .title { text-align: center; font-size: 18px; line-height: 30px; padding: 8% 0; }
.main > .title span { color: #7766cc; font-weight: 500; margin-right: 5px;}
.main > .canvas { width: 160%; margin-left: -30%; position: relative; }
.main > .canvas canvas { /*margin-left: -15%; */}
.main > .ewm { width: 45px; height: 45px; margin-top: 10px; }
.main > .ewm > img { width: 45px; height: 45px; }
.main > .source { bottom: 10px; right: 10px; font-size: 12px; -webkit-transform: scale(.8333); transform: scale(.8333); color: #b2b2b2; }

.load { position: fixed; z-index: 9; margin-top: -15%; width: 150px; height: 40px; border-radius: 6px; background: rgba(0,0,0,.6); color: #fff; line-height: 40px; }
.icon-load { width: 14px; height: 14px; left: 20px; top: 12px; -webkit-animation: anim 2s infinite cubic-bezier(0,0,1,1); animation: anim 2s infinite cubic-bezier(0,0,1,1); }
@-webkit-keyframes anim {
	0% { -webkit-transform: rotate(0deg); transform: rotate(0deg); }
	100% { -webkit-transform: rotate(360deg); transform: rotate(360deg); }
}
@keyframes anim {
	0% { -webkit-transform: rotate(0deg); transform: rotate(0deg); }
	100% { -webkit-transform: rotate(360deg); transform: rotate(360deg); }
}
.load .info { margin-left: 40px; }
</style>

<div id="container">
	
	<div class="label">诊断结果</div>
	
	<div class="main rel">
		<div class="title">
			<span><?php echo $nickname?></span>的七宗罪
		</div>
		<div class="canvas">
			<canvas id="canvas" style="display:none"></canvas>
		</div>
		<div class="ewm mcenter">
			<img src="<?php echo $ewm;?>" alt="" />
		</div>
		<div class="source abs">
			Form 玩主APP
		</div>
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
            duration: 0, // 规定完成动画所花费的时间，以毫秒计 如1500
            onComplete: function(animation) {  // 动画完成之后的callback
                var canvas = document.getElementById('canvas');
            	var image = new Image();
        		image.src = canvas.toDataURL("image/png");
        		image.style.width = canvas.style.width;
        		$('.canvas').html(image)
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
