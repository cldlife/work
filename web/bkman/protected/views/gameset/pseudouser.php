<style type="text/css">
.avatar { width: 50px; }
</style>
<div id="content">
  <div class="itemtitle">
    <h3><?php echo $this->title?></h3>
  </div>

  <div class="itemlist">
    <div class="search submit">
      <table>
        <tbody>
          <tr>
            <td colspan="5">
                 <label>查询：</label>
                 <select id="is-using">
                   <option value="0" <?php if ($isUsing == 0) echo 'selected="selected"'?>>没有使用</option>
                   <option value="1" <?php if ($isUsing == 1) echo 'selected="selected"'?>>正在使用</option>
                 </select>
                 <a class="confirm_btn" id="btn-search-a"><span>&nbsp;查询&nbsp;</span></a>&nbsp;&nbsp;&nbsp;&nbsp;
                 <a class="confirm_btn" href="<?php echo $this->getDeUrl('gameset/pseudouser/addedit', array('id' => $this->permissionId, 'uid' => 0, 'ref' => urlencode($this->getDeUrl('gameset/pseudouser', array('id' => $this->permissionId, 'page' => $page, 'is_using' => $isUsing))))); ?>" ><span>&nbsp;添加用户&nbsp;</span></a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="itemlist"> <?php echo $pager?> </div>

  <div class="itemlist">
  <table>
    <thead>
      <tr>
        <th width="10%">用户ID</th>
        <th width="20%">用户昵称</th>
        <th width="10%">用户头像</th>
        <th width="10%">性别</th>
        <th width="10%">生日</th>
        <th width="10%">年龄</th>
        <th width="30%">操作</th>
      </tr>
    </thead>
    <tbody id="contentTbody">
    <?php
    if ($userList) :
      foreach ($userList as $user) :?>
      <tr>
        <td> <?php echo $user['uid']; ?> </td>

        <td>
          <span><?php echo $user['nickname']; ?></span>
        </td>

        <td>
          <div><img class="avatar" src="<?php echo $user['avatar']; ?>" ></div>
        </td>

        <td>
          <span><?php echo $user['gender_name']; ?></span>
        </td>

        <td>
          <span><?php echo $user['birthday_desc']; ?></span>
        </td>

        <td>
          <span><?php echo $user['age']; ?></span>
        </td>

        <td>
          <a name="btn-view" href="<?php echo $this->getDeUrl('gameset/pseudouser/addedit', array('id' => $this->permissionId, 'uid' => $user['uid'], 'ref' => urlencode($this->getDeUrl('gameset/pseudouser', array('id' => $this->permissionId, 'page' => $page, 'is_using' => $isUsing))))); ?>">编辑</a>
          &nbsp; &nbsp; &nbsp; &nbsp;
          <a name="btn-delete" data-id="<?php echo $user['uid']; ?>" href="javascript:;">删除</a>
        </td>
      </tr>
    <?php
      endforeach;
    endif;?>
    </tbody>
    <tfoot class="tfoot"></tfoot>
  </table>
  </div>

  <div class="itemlist"> <?php echo $pager?> </div>
</div>
<br>
<script type="text/javascript">
$("#btn-search-a").on('click', function () {
  window.location.href="<?php echo $this->getDeUrl('gameset/pseudouser', array('id' => $this->permissionId))?>" + '&is_using=' + $('#is-using').val();
});

//删除回复
var deleting = false;
$('#contentTbody a[name="btn-delete"]').on('click', function() {
  if (deleting) return false;

  var id = parseInt($(this).attr('data-id'));
  if (!id) {
    $.addtip({type: "error", message: "找不到这个用户的ID！", autoclose: 3});
    return false;
  }

  deleting = true;
  var title = '删除用户';
  var content = "<H3>确定删除该用户吗？</H3>";
  $.showWindow({id:"windowBox", title:title, content:content,
    button:[{idname: "sureConfirm", title:"确定",
      callback: function () {
        $('#btn-Confirm span').text('处理中，请稍候...');
        $('#cancel_id').unbind();
        $('#btn-Confirm').unbind();
        $.DeAjax("sureConfirm", {
          type: 'POST',
          dataType: 'json',
          url: '<?php echo $this->getDeUrl('gameset/pseudouser/delete', array('id' => $this->permissionId))?>',
          data: {"uid":id, '_sh_token_':_sh_token_},
          success: function (res) {
            deleting = false;
            $("#admin_tip").remove();
            if (res.code) {
              $.addtip({type: "error", message: res.msg, autoclose: 3});
            } else {
              $.addtip({message:"操作成功", autoclose: 3});
              setTimeout(function () {
                window.location.reload();
              }, 300);
            }
          },
          error: function() {
            deleting = false;
            $("#admin_tip").remove();
            $.addtip({type: "error", message: "操作失败，请检查！", autoclose: 3});
          }
        });
        return true;
      }
    }, { title:"取消", callback: function () {
      return true;
    }}
    ]
  });
});
</script>
