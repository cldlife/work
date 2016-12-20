<img src="<?php echo $this->currentUser['avatar']?>" style="left:-3000px;position: absolute;">
<img src="<?php echo $share_logo?>" style="left:-3000px;position: absolute;">
<style type="text/css">
.question-label .inner span { background: #fff; }
.question-label .inner span a { color: <?php echo  $knowGame['color'] ?>; }
.question-label .inner span.on { background: <?php echo  $knowGame['color'] ?>; }
.question-label .inner span.on a { color: #fff; }
.question-label .line { background: <?php echo  $knowGame['color'] ?>; /*width: 11%;*/ }
.answers ul li.right { background: <?php echo  $knowGame['color'] ?>; color: #fff; }
.answers ul li.wrong { background: red; color: #fff; }
</style>
	<div id="J_waiting" class="waiting"><!--loadImg-->
		<div class="spinner">
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
		<div class="waiting-word"></div>
	</div>
	
	<div class="container questions">
		
		<div id="J_label" class="question-label">
			<div class="lineBg"></div>
			<div id="J_labelLine" class="line"></div>
			<div id="J_labelOrder" class="inner">
				<span><a>1</a></span><!--class="on"-->
				<span><a>2</a></span>
				<span><a>3</a></span>
				<span><a>4</a></span>
				<span><a>5</a></span>
				<span><a>6</a></span>
				<span><a>7</a></span>
				<span><a>8</a></span>
				<span><a>9</a></span>
				<span><a>10</a></span>
			</div>
		</div>
		
		<div id="J_question" class="question-cont">
			<div class="question">
				<div class="show"><img id="J_pic"></div>
				<div id="J_word" class="desc"></div>
			</div>
			<div class="answers">
				<ul id="J_answers">
					<!--<li>蒸肠粉7</li>
					<li>叉烧包</li>
					<li>鲜虾饺</li>
					<li>云吞面</li>
					<li>艇仔粥</li>-->
				</ul>
			</div>
		</div>
		
	</div><!-- /container -->
	<div style="display:none">
	<?php echo CHtml::beginForm($this->getDeUrl("{$randControllerName}/{$randUrlLink}/tm{$question['id']}", array('step' => 2)), 'post', array('id' => 'submitForm', 'name' => 'submitForm'))?>
	<input type="hidden" id="qa_content" name="qa_content">
	<?php if ($test) :?>
	<input type="hidden" id="test" name="test" value="<?php echo $test;?>">
	<?php endif;?>
	<?php echo CHtml::endForm();?>
</div>	
	
</div><!-- /view -->
<script type="text/javascript">

var datas = <?php echo $qaInfo['qas'];?>;

var wait = $('#J_waiting');

function Questions(data) {
	this._init(data);
}

Questions.prototype = {
	_init : function (data) {
		this.bindData(data);
		this.bindDom();
		this.show();
		this.label();
	},
	bindData : function (data) {
		this.timer = null;
		this.data = data;
		this.order = 0;
		this.dataLength = this.data.length;
		this.qas = [];
	},
	bindDom : function () {
		this.labelLineDom = $('#J_labelLine');
		this.labelOrderDom = $('#J_labelOrder span');
		this.questionDom = $('#J_question');
		this.imgDom = $('#J_pic');
		this.titleDom = $('#J_word');
		this.answersDom = $('#J_answers');
	},
	bindEvt : function () {
		var that = this;
		this.questionDom.on('click', function  (event) {
			var $this = $(this);
			var targetEl = event.target;
			if (targetEl.tagName == 'LI') {
				var $li = $(targetEl);
				var $liIndex = $li.index();
				if($liIndex == that.randomQuestion.right) {
					$li.addClass('right');
				} else {
					$li.addClass('wrong');
				}
				that.record(that.randomQuestion.id, $li.data('id'));
				that.order ++;
				if (that.order >= that.dataLength) {
					wait.show();
					var qasStr = that.qas.join('|');
					$("#qa_content").val(qasStr);
      				$(".J_waiting").show();
      				$("#submitForm").submit();
					return;
				}
				that.show();
				that.label();
			}
		})
	},
	record : function (qid, aid) {
		var currentQa = qid + '_' + aid;
		this.qas.push(currentQa);
	},
	show : function () {
		var that = this;
		that.randomQuestion = that.data[that.order];
		this.questionDom.off('click');
		this.questionDom.fadeOut(300, function () {
			that.render(that.randomQuestion);
			$(this).fadeIn(300, function () {
				that.questionDom.off('click');
				that.bindEvt();
			});
		})
	},
	render : function (currentData) {
		var answersData = currentData.answers;
		var answersStr = '';
		this.questionDom.attr('data-id', currentData.id);
		this.imgDom.attr('src', currentData.img);
		this.titleDom.text(currentData.title);
		$.each(answersData, function(i, item) {
			answersStr += '<li data-id="' + (i + 1) + '">' + item + '</li>';
		});
		this.answersDom.html(answersStr);
	},
	label : function () {
		console.log(this.order)
		var lineWidth = 11 * this.order < 100 ? 11 * this.order + '%' : '100%';
		this.labelOrderDom.eq(this.order).addClass('on');
		this.labelLineDom.width(lineWidth);
	},
	_throttle : function (fn) {
		clearTimeout(this.timer);
		this.timer = setTimeout(function () {
			fn && fn();
		}, 200)
	}
}
 new Questions(datas, 10);
</script>

