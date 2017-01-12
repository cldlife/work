<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title><?php echo $this->title?></title>
  <link rel="stylesheet" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/css/bkman/style.css?v2.0.0_2016.06.12"/>
  <script type="text/javascript" src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/common/jquery-1.8.3.min.js"></script>
  <script type="text/javascript" src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/bkman/component.showWindow.js"></script>
  <script type='text/javascript' src='<?php echo WEB_QW_APP_FILE_UI_URL?>/js/bkman/index.js'></script>
  <script type="text/javascript" src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/bkman/component.DeAjax.js"></script>
  <script type="text/javascript" src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/bkman/common.js"></script>
</head>
<body>
<script>var _sh_token_ = '<?php echo Yii::app()->getRequest()->getCsrfToken()?>';</script>

<?php echo $content?>

<script type="text/javascript">
$('td[name="td-userinfo"]').hover(function () {
  $(this).find(".userinfo-block").show();
}, function () {
  if ($(this).find('.messagelist-block').is(":hidden")) {
    $(this).find(".userinfo-block").hide();
  }
});

//esc隐藏
$(document).bind("keydown", function(e){
  var keyCode = window.event ? e.keyCode : e.which;
  if (keyCode == 27) {
    $(".userinfo-block").hide();
  }
});

$('a[name="btn-a-diableuser"]').live('click', function () {
  var dataId = $(this).attr("data-id");
  var dataStatus = parseInt($(this).attr("data-status"));
  var uid = $(this).attr("data-uid");
  var _this = this;

  if (dataStatus == 2) {
    var title = "取消屏蔽用户";
    var content = "<H3>确定取消屏蔽吗？保存成功将立刻生效。</H3>";
  } else {
    var title = "屏蔽用户";
    var content = "<H3>确定屏蔽用户吗？屏蔽后，用户将不能登录使用APP，<br>且该用户发布的食记和评论也将被屏蔽。</H3>";
  }

  $.showWindow({id:"windowBox", title:title,content:content,
    button:[{idname: "btn-Confirm", title:"确定",
      callback:function(){
  	    $.DeAjax("sureConfirm", {
          type: 'POST',
          dataType: 'json',
          url: '<?php echo $this->getDeUrl('user/disabled', array('id' => $this->permissionId))?>',
          data: {"uid": uid, '_sh_token_':_sh_token_},
          success: function(res){
      	  	if (res.code == 1) {
              $.addtip({message: "保存成功", autoclose: 3});
              $(_this).text(res.status == 2 ? '屏蔽用户' : '取消屏蔽用户');
        	} else if (res.code == -1) {
      		  $.addtip({type: "error", message: "你没有权限执行此操作", autoclose: 3});
        	}
          },
          error: function() {
      	    $.addtip({type: "error", message: "数据异常，请重试！", autoclose: 3});
          }
        });
        return true;
      }
    }, {title:"取消", callback:function(){
  	  return true;
  	}}
    ]
  })
});

$('a[name="btn-a-message"]').live('click', function () {
  var uid = $(this).attr("data-uid");
  var _this = this;

  var messagelistBlock = $(_this).siblings("div.messagelist-block");
  messagelistBlock.show();
  messagelistBlock.jqDrag(".title");
  messagelistBlock.find(".loading").show();
  messagelistBlock.find(".close").bind('click', function() {
	$(".messagelist-block").hide();
  });

  //Enter发送
  messagelistBlock.find("#post_content").bind('keydown', function(e) {
    var keyCode = window.event ? e.keyCode : e.which;
    if (keyCode == 13) {
  	  messagelistBlock.find("#btn-message-send").click();
    }
  })

});
</script>
</body>
</html>
