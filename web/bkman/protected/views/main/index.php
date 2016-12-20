<table width="100%" height="100%" cellspacing="0" cellpadding="0">
  <tbody>
    <tr id="frameheader">
      <!-- head -->
      <td colspan="2" height="72">
        <div id="head">
          <h1>
          	<a href="<?php echo $this->getDeUrl('main/index')?>" class="bg_logo" style="padding-top:14px;font-size:30px;  width: 200px;">
          	<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/logo/160.png" width="40" style="border-radius: 6px;vertical-align:top"> 管理后台</a>
          </h1>
          <div class="userinfo">
            <p><a href="<?php echo $default_url?>" target="framecontent">你好</a>，<?php echo $bk_admin_name;?> <a href="<?php echo $this->getDeUrl('permission/logout')?>">[退出]</a></p>
          </div>
        </div>
      </td>
    </tr>

    <tr>
      <!-- menu -->
      <td id="framemenu" width="180" valign="top" style="height: 703px;">
        <div id="menu">
          <dl><?php echo $permission_menu_html;?></dl>

          <div style="text-align: center;position: absolute;bottom: 10px;width:180px"><a href="<?php echo WEB_QW_APP_DOMAIN?>" target="_blank">玩主 - 就是玩得来</a><br><span style="color: #666;font-size:10px">Copyright 2016 © wanzhuyouxi.com</span></div>
      	</div>
      </td>

      <!-- #content -->
      <td id="framecontent" width="100%" valign="top" style="height: 713px;">
      	<iframe src="" id="framecontentpage" name="framecontent" width="100%" height="100%" frameborder="0" scrolling="yes" style="overflow:visible"></iframe>
	  </td>
	</tr>
  </tbody>
</table>

<script type="text/javascript">
$(function(){
  $("#menu dt").bind("click", function(){
    var ddNode = $(this).nextUntil("dt");
    if(ddNode.css("display") == "none"){
      $(this).addClass("unfold");
      $(this).siblings("dd").hide();
      $(this).siblings("dt").removeClass("unfold");
      ddNode.css("display", "block");
    } else {
      $(this).removeClass("unfold");
      ddNode.css("display", "none");
    }
  });

  $("#menu").find("a").live('click', function(){
    $("#menu li a").removeClass("select");
    $(this).addClass("select");
  });

  $("#framecontent iframe").attr('src', '<?php echo $default_url?>');

  //autoframe
  var autoframe = function(){
  	$("#framemenu").height($(window).height()-82);
  	$("#framecontent").height($(window).height()-78);
  };

  autoframe();
  $(window).bind("resize", autoframe);
})

function readAllContent (contentId, sparent) {
  if (typeof sparent == undefined) sparent = false;
  var showHtml = sparent == true ? $('#framecontentpage').contents().find('#iframepage').contents().find(contentId).html() : $('#framecontentpage').contents().find(contentId).html();
  var content = '<div class="article-content" style="height: 520px;overflow-y:auto;line-height:26px;font-size:14px;top:0px">' + showHtml + '</div>';
  $.showWindow({id:"windowBox", title:"查看全文", width: "780", content:content,
    button:[{title:"关闭",
      callback:function(){
        return true;
      }
    }, {title:"取消", callback:function(){
        return true;
  	}}
    ]
  })

  $(document).focus();
  $(document).bind("keydown", function(e){
  	var keyCode = window.event ? e.keyCode : e.which;
  	if (keyCode == 27) {
  	  $(".showWindow_close").click();
  	}
  });
}

function switchfullScreen(close) {
  if ($("#frameheader").is(":hidden") || close) {
  	$("#frameheader").show();
  	$("#framemenu").show();
  } else {
  	$("#frameheader").hide();
  	$("#framemenu").hide();
  }

  $("#framecontent").height($(window).height());
}

</script>
