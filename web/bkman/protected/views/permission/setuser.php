<div id="content">
	<div class="itemtitle">
		<h3><?php echo $this->title?>
			<!--&nbsp;&nbsp;<a href="<?php echo $this->getDeUrl('help')?>" style="color:#0066ff;font-size:12px;font-weight:normal">点击查看使用帮助&gt;&gt;</a>-->
		</h3>
	</div>

	<div class="itemlist">
		<div class="search submit">
			<table>
				<tbody>
					<tr>
						<td><span>设置权限：<?php echo $adminUserInfo['admin_name']?></span> <a
							class="confirm_btn" name="btn-submit-a"><span>&nbsp;保存&nbsp;</span></a>
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
					<th width="100%"><input type="checkbox" id="selectAllChk"> 权限点</th>
				</tr>
			</thead>
			<tbody id="contentTbody">
      	<?php
							if ($adminPermissionList) :
								foreach ( $adminPermissionList as $permission ) :
									?>
    	  	<tr id="row-parent-<?php echo $permission['id']?>">
					<td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"
						value="<?php echo $permission['id']?>" name="permission_ids[]"
						<?php if (in_array($permission['id'], $adminUserInfo['permission_ids'])) echo 'checked="true"';?>
						data-id='<?php echo $permission['id']?>'
						onclick="selectAllChild(this)"> <b><?php echo $permission['name']?></b></td>
				</tr>
    	  	<?php if ($permission['sub']): ?>
  	  	  <tr id="row-<?php echo $permission['id']?>">
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        	  	  <?php foreach ($permission['sub'] as $subPermission):?>
        	  	  &nbsp;&nbsp;<input type="checkbox"
						value="<?php echo $subPermission['id']?>" name="permission_ids[]"
						<?php if (in_array($subPermission['id'], $adminUserInfo['permission_ids'])) echo 'checked="true"';?>
						onclick="selectParent(this, <?php echo $permission['id']?>);">
        	  	  <?php if ($subPermission['is_display']) : ?>
        	  	  <a target="_blank" title="点击查看"
						href="<?php echo $this->getDeUrl('' . $subPermission['uri_alias'])?>"><?php echo $subPermission['name']?></a>
        	  	  <?php else :?>
        	  	  <?php echo $subPermission['name']?>
        	  	  <?php endif;?>
    	  	  	  <?php endforeach;?>
    	  	  </td>
				</tr>
    	  	<?php endif;?>
  	  	<?php
								endforeach
								;


  	  	endif;
							?>
      </tbody>
			<tfoot class="tfoot"></tfoot>
		</table>
	</div>

	<div class="itemlist">
		<div class="search submit">
			<table>
				<tbody>
					<tr>
						<td><span>设置权限：<?php echo $adminUserInfo['admin_name']?></span> <a
							class="confirm_btn" name="btn-submit-a"><span>&nbsp;保存&nbsp;</span></a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<br>
<script type="text/javascript">
$("#selectAllChk").bind("click", function() {
  if (this.checked) {
    $("input[name='permission_ids[]']").each(function() {
  	  this.checked = true;
    });
  } else {
  	$("input[name='permission_ids[]']").each(function() {
	  	this.checked = false;
    });
  }
});

function selectAllChild (_this) {
  var id = 'row-' + $(_this).attr('data-id');
  if (_this.checked) {
    $("#"+id).find("input[name='permission_ids[]']").each(function() {
  	  this.checked = true;
    });
  } else {
    $("#"+id).find("input[name='permission_ids[]']").each(function() {
	  	this.checked = false;
    });
  }
}

function selectParent (_this, id) {
  var parent = $(_this).parent();
  var parentChecked = false;
  parent.find("input[name='permission_ids[]']").each(function() {
	  if (this.checked) {
		  parentChecked = true;
	  }
  });
  $('#row-parent-' + id).find("input[name='permission_ids[]']").attr('checked', parentChecked);
}


var _sh_token_ = '<?php echo Yii::app()->getRequest()->getCsrfToken()?>';

$("a[name='btn-submit-a']").click(function () {
	var permission_ids = [];
	$("input[name='permission_ids[]']").each(function() {
		if (this.checked) {
			permission_ids.push($(this).val());
		}
  });

  var content = "<H3>确定保存该管理员权限设置？保存成功将立刻生效。</H3>";
  $.showWindow({id:"windowBox", title:"管理员权限设置",content:content,
    button:[{idname: "btn-Confirm", title:"确定",
      callback:function(){
    	  $.DeAjax("btn-Confirm", {
          type: 'POST',
          dataType: 'json',
          url: '<?php echo $this->getDeUrl('permission/setuser', array('id' => $this->permissionId))?>',
          data: {"action": 'save', "uid": '<?php echo $adminUserInfo['uid']?>' , "permission_ids": permission_ids, '_sh_token_':_sh_token_},
          success: function(res){
      	  	if (res.code == 1) {
              $.addtip({message: "保存成功", autoclose: 3});
              window.location.href="<?php echo $this->getDeUrl('permission/setuser', array('uid' => $adminUserInfo['uid'], 'id' => $this->permissionId))?>";
        	} else {
      		  $.addtip({type: "error", message: "保存失败，请重试", autoclose: 3});
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
</script>
