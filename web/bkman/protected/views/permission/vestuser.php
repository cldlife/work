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
                 <label>查询：</label>
                 <select name="uid" id="uid">
                   <?php if ($this->isSystemAdmin) :?>
                   <option value="0" <?php if ($uid == 0) echo 'selected="selected"'?>>全部</option>
                   <?php endif;?>
                   <option value="<?php echo $this->bkAdminUser['uid']?>" <?php if ($uid == $this->bkAdminUser['uid']) echo 'selected="selected"'?>>我创建的</option>

                   <?php if ($this->isSystemAdmin) :?>
                     <?php if ($adminUserList):
                      foreach ($adminUserList as $admin):
                        if ($admin['uid'] == $this->bkAdminUser['uid']) continue;?>
                     <option value="<?php echo $admin['uid']?>" <?php if ($uid == $admin['uid']) echo 'selected="selected"'?>><?php echo $admin['admin_name']?></option>
                     <?php
                      endforeach;
                     endif;?>
                   <?php endif;?>
                 </select>
                 <a class="confirm_btn" id="btn-search-a"><span>&nbsp;查询&nbsp;</span></a>
                 </td>
               </tr>

               <tr>
                <td colspan="5">
                 <span>用户昵称：</span><input type="text" class="txt" id="nickname" name="nickname" style="width:100px;" value="">
                 <span>密码：</span><input type="text" class="txt" id="password" name="password" style="width:150px;" value="" placeholder="默认密码 <?php echo $defaultPassword;?>">
                 <input type="hidden" id="ouid" name="ouid">
                 <a class="confirm_btn" id="btn-submit-a"><span>&nbsp;创建马甲&nbsp;</span></a>
                 <a class="confirm_btn" id="btn-cancel-a" style="display: none"><span>&nbsp;取消&nbsp;</span></a>
                 <span class="txt_tip"><i></i>温馨提示：创建的马甲将仅所属当前管理员；创建成功后，可再次编辑昵称但不支持删除。</span>
                 </td>
               </tr>
            </tbody>
          </table>
        </div>
    </div>

    <div class="itemlist">
    	<?php echo $pager?>
    </div>

    <div class="itemlist">
    <table>
      <thead>
      	<tr>
          <th width="10%">用户ID</th>
          <th width="10%">用户昵称</th>
          <th width="30%">财富</th>
          <th width="10%">最近活动时间</th>
          <th width="10%">创建时间</th>
          <th width="20%">操作</th>
		</tr>
      </thead>
      <tbody id="contentTbody">
      	<?php
      	if ($vestUserList) :
      	  foreach ($vestUserList as $user) :?>
    	  	<tr id="row-<?php echo $user['uid']?>">
        	  <td><?php echo $user['uid']?></td>
        	  <td><img src="<?php echo $user['avatar']?>" width="40" style="border-radius: 40px;"><br><?php echo $user['nickname']?> <img src="<?php echo WEB_QW_APP_FILE_UI_URL;?>/img/app/level/<?php echo $user['level']['id'];?>@2x.png" width='12'/></td>
        	  <td>金币 <?php echo intval($user['status']['coins']);?> 玫瑰 <?php echo intval($user['status']['roses']);?> 喊话次数 <?php echo intval($user['status']['privilege_public_num']);?> 积分 <?php echo intval($user['status']['points']);?></td>
        	  <td><?php echo $user['update']?></td>
        	  <td><?php echo $user['cdate']?></td>
        	  <td>
        	  <a name="btn-edit" data-uid="<?php echo $user['uid']?>" data-nickname="<?php echo $user['nickname']?>" data-password="<?php echo $user['password']?>" href="javascript:;">编辑</a>
        	  <a name="btn-sethanhua" href="javascript:;">设置喊话权限</a>
        	  <div id="privilege_public_num" name="privilege_public_num" style="display: none; width: 140px"><input type="text" class="txt" style="width:50px;" value="" placeholder='输入次数' onkeyup="this.value=this.value.replace(/[^\-|^\d]/g,'')" maxlength=5> <a class="confirm_btn" data-uid="<?php echo $user['uid']?>"><span>&nbsp;保存&nbsp;</span></a></div>
        	  </td>
    	  	</tr>
  	  	<?php
  	  	  endforeach;
  	  	endif;?>
      </tbody>
      <tfoot class="tfoot"></tfoot>
    </table>
    </div>

    <div class="itemlist">
    	<?php echo $pager?>
    </div>
</div>
<br>
<script type="text/javascript">
var _sh_token_ = '<?php echo Yii::app()->getRequest()->getCsrfToken()?>';

$("#btn-search-a").click(function () {
  var uid = parseInt($("#uid").find("option:selected").val());
  window.location.href="<?php echo $this->getDeUrl('permission/vestuser', array('id' => $this->permissionId, 'action' => 'search'))?>" + '&uid=' + uid;
});

$("#contentTbody a[name='btn-edit']").click(function () {
  $("#nickname").focus();
  $("#ouid").val($(this).attr('data-uid'));
  $("#nickname").val($(this).attr('data-nickname'));
  $("#password").val('');
  $("#password").attr('placeholder', '如需修改，请输入新密码');
  $("#btn-submit-a").html('<span>&nbsp;编辑马甲&nbsp;</span>');
  $("#btn-cancel-a").show();
})

$("#btn-cancel-a").click(function () {
  $("#ouid").val('');
  $("#nickname").val('');
  $("#btn-submit-a").html('<span>&nbsp;创建马甲&nbsp;</span>');
  $(this).hide();
})

//创建马甲
$("#btn-submit-a").click(function () {
  var nickname = $.trim($("#nickname").val());
  var password = $.trim($("#password").val());
  var ouid = $.trim($("#ouid").val());

  if(!nickname){
    $("#nickname").focus();
    $.addtip({type: "error", message: "请填写用户昵称！", autoclose: 3});
    return false;
  }

  if (nickname) {
	$.DeAjax(this.id, {
      type:'POST',
      dataType:'json',
      url:'<?php echo $this->getDeUrl('permission/vestuser', array('id' => $this->permissionId))?>',
      data:{"action": 'save', 'ouid': ouid, "nickname":nickname, "password":password , '_sh_token_':_sh_token_},
      success:function(res){
  		if (res.code == 1) {
          $.addtip({message: "保存成功", autoclose: 3});
          setTimeout(function () {
        	window.location.href="<?php echo $this->getDeUrl('permission/vestuser', array('id' => $this->permissionId, 'action' => 'search', 'uid' => $uid))?>";
          }, 1000);
  		} else if (res.code == 2) {
		  $.addtip({type: "error", message: "请更新用户昵称或密码", autoclose: 3});
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

//设置喊话权限
$('a[name="btn-sethanhua"]').click(function (e) {
  $('div[name="privilege_public_num"]').hide();
  $(this).next().show(500);

  $(document).one("click", function(){
	$('div[name="privilege_public_num"]').hide();
  });

  e.stopPropagation();
})
$('div[name="privilege_public_num"] a').on("click", function(e){
  var uid = $(this).attr('data-uid');
  var privilegePublicNum = parseInt($(this).parent().find('input').val());
  if (!isNaN(privilegePublicNum) && uid) {
	  $.DeAjax(this.id, {
      type:'POST',
      dataType:'json',
      url:'<?php echo $this->getDeUrl('user/updatestatus', array('id' => $this->permissionId))?>',
      data:{'uid': uid, "status_fields": '{"privilege_public_num":'+privilegePublicNum+'}', '_sh_token_':_sh_token_},
      success:function(res){
  		if (res.code == 1) {
          $.addtip({message: "保存成功", autoclose: 3});
          setTimeout(function () {
        	window.location.href="<?php echo $this->getDeUrl('permission/vestuser', array('id' => $this->permissionId, 'action' => 'search', 'uid' => $uid))?>";
          }, 1000);
  		} else {
		  $.addtip({type: "error", message: "数据异常，请重试！", autoclose: 3});
  		}
      },
      error:function(){
      	$.addtip({type: "error", message: "数据异常，请重试！", autoclose: 3});
      }
	});
  } else {
    $(this).parent().find('input').val('');
  }
  
  e.stopPropagation();
});
$('div[name="privilege_public_num"] input').on("click", function(e){
  e.stopPropagation();
});
</script>
