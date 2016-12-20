<div id="content">
    <div class="itemtitle">
   	<h3><?php echo $this->title?><!--<a href="<?php echo $this->getDeUrl('help')?>" style="color:#0066ff;font-size:12px;font-weight:normal">点击查看使用帮助&gt;&gt;</a>--></h3>
    </div>

    <div class="itemlist">
        <div class="search submit">
          <table>
            <tbody>
               <tr>
                <td colspan="5">
                 <span>手机号：</span><input type="text" class="txt" id="mobile" name="mobile" style="width:100px;" value="">
                 <span>后台显示姓名：</span><input type="text" class="txt" id="admin_name" name="admin_name" style="width:100px;" value="">
                 <a class="confirm_btn" id="btn-submit-a"><span>&nbsp;开通&nbsp;</span></a>
                 <span class="txt_tip"><i></i>温馨提示：该手机号是APP账号所用的手机号。</span>
                 </td>
               </tr>
            </tbody>
          </table>
        </div>
    </div>

    <div class="itemlist">
    <table>
      <thead>
      	<tr>
          <th width="15%">管理员帐号</th>
          <th width="15%">开通时间</th>
          <th width="15%">上一次登录时间</th>
          <th width="20%">权限管理</th>
          <th width="35%">操作</th>
				</tr>
      </thead>
      <tbody id="contentTbody">
      	<?php
      	if ($adminUserList) :
      	  foreach ($adminUserList as $adminUser) :
      	    if (!$this->isSystemAdmin && $adminUser['is_admin']) continue;?>

    	  	<tr id="row-<?php echo $adminUser['uid']?>">
        	  <td>
        	  <?php echo $adminUser['admin_name']?>
        	  <br>[昵称 <?php echo $adminUser['user_info']['nickname']?>]
        	  <br>[手机号 <?php echo $adminUser['user_info']['mobile']?>]
        	  </td>
        	  <td><?php echo $adminUser['cdate']?></td>
        	  <td><?php echo $adminUser['last_login_time']?></td>
        	  <td>
        	      <?php if ($this->isSystemAdmin && $adminUser['uid'] != $this->bkAdminUser['uid']):?>
            	  <a href="<?php echo $this->getDeUrl('permission/setuser', array('id' => $this->permissionId, 'uid' => $adminUser['uid']))?>">权限设置</a>
            	  <?php endif;?>

            	  <a href="<?php echo $this->getDeUrl('permission/vestuser', array('id' => $this->permissionId, 'action' => 'search', 'uid' => $adminUser['uid']))?>">马甲账号管理</a>
        	  </td>
        	  <td>
        	     <?php if ($this->isSystemAdmin && $adminUser['uid'] != $this->bkAdminUser['uid']):?>
            	 <a name="btn-del" data-id="<?php echo $adminUser['uid']?>" href="javascript:;">删除</a>
            	 <?php endif;?>
        	  </td>
    	  	</tr>
  	  	<?php
  	  	  endforeach;
  	  	endif;?>
      </tbody>
      <tfoot class="tfoot"></tfoot>
    </table>
    </div>
</div>
<br>
<script type="text/javascript">
var _sh_token_ = '<?php echo Yii::app()->getRequest()->getCsrfToken()?>';

$('#contentTbody a[name="btn-del"]').bind('click', function() {
  var uid = $(this).attr('data-id');
  var content = "<H3>确定删除该管理员账号吗？删除后该账号将不能访问。</H3>";
    $.showWindow({id:"windowBox", title:"删除管理员账号",content:content,
      button:[{idname: "delConfirm", title:"确定",
        callback:function(){
    	  $.DeAjax("delConfirm", {
            type: 'POST',
            dataType: 'json',
            url: '<?php echo $this->getDeUrl('permission/newuser', array('id' => $this->permissionId))?>',
            data: {"action": 'del', "uid": uid, '_sh_token_':_sh_token_},
            success: function(res){
              if (res.code == 1) {
              	$.addtip({message: "删除成功", autoclose: 3});
              	$("#row-" + uid).remove();
              }
            },
            error: function() {
              $.addtip({type: "error", message: "删除失败，请检查！", autoclose: 3});
            }
          });
          return true;
        }
      }, {title:"取消", callback:function(){
    	return true;
    	}}
      ]
    })
})

$("#btn-submit-a").click(function () {
  var mobile = $.trim($("#mobile").val());
  var adminName = $.trim($("#admin_name").val());

  if(!mobile){
    $.addtip({type: "error", message: "请填写手机号", autoclose: 3});
    $("#mobile").focus();
    return false;
  }

  if(!adminName){
    $.addtip({type: "error", message: "请填写后台显示姓名", autoclose: 3});
    $("#admin_name").focus();
    return false;
  }

  if(mobile && adminName) {
	$.DeAjax(this.id, {
      type:'POST',
      dataType:'json',
      url:'<?php echo $this->getDeUrl('permission/newuser', array('id' => $this->permissionId))?>',
      data:{"action": 'new', "mobile": mobile, "admin_name": adminName, '_sh_token_':_sh_token_},
      success:function(res){
  		if (res.code == 1) {
          $.addtip({message: "保存成功", autoclose: 3});
          window.location.href="<?php echo $this->getDeUrl('permission/newuser', array('id' => $this->permissionId))?>";
  		} else if(res.code == -1) {
		  $.addtip({type: "error", message: "管理员 '"+mobile+"' 已开通，可进行权限设置。", autoclose: 3});
  		} else if(res.code == -2) {
  		  $.addtip({type: "error", message: "'"+mobile+"' 该手机号不存在，请重新输入。", autoclose: 3});
  		  $("#mobile").focus();
  		} else if(res.code == -3) {
  		  $.addtip({type: "error", message: "'"+mobile+"' 该手机号格式不正确，请重新输入。", autoclose: 3});
  		  $("#mobile").focus();
  		} else {
		  $.addtip({type: "error", message: "保存失败，请重试", autoclose: 3});
  		}
      },
      error:function(){
      	$.addtip({type: "error", message: "数据异常，请重试！", autoclose: 3});
      }
	});
  } else {
  	$.addtip({type: "error", message: "亲，数据没有填写完整哦", autoclose: 3});
  }
});
</script>