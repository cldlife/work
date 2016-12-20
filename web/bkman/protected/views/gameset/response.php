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
              <label for="response">添加游戏回复：</label>
              <input class="txt" style="width:360px;" type="text" maxlength="50" placeholder="输入回复..." id='response' name='response' value=""/>
              <a class="confirm_btn" id="add-edit-response" data-rid="<?php echo $gameset['id']; ?>" ><span>&nbsp;添加&nbsp;</span></a>
              <a id="btn-refresh-a" href="javascript:location.reload();"><span>刷新</span></a>&nbsp;&nbsp;&nbsp;&nbsp;
              <a href="<?php echo $ref; ?>" style="color:#0066ff;font-size:12px;font-weight:normal">返回</a>
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
        <th width="10%">编号</th>
      <?php if ($gameset['type'] == 1) : ?>
        <th width="30%">卧底词/平民词</th>
      <?php elseif ($gameset['type'] == 2) : ?>
        <th width="30%">惩罚内容</th>
      <?php endif; ?>
        <th width="30%">回复内容</th>
        <th width="30%">操作</th>
      </tr>
    </thead>
    <tbody id="contentTbody">
    <?php
    if ($responseList) :
      foreach ($responseList as $response) :?>
      <tr>
        <td> <?php echo $response['id']; ?> </td>

        <td>
        <?php if ($gameset['type'] == 1) : ?>
          <span><?php echo "{$gameset['words']['spy']}/{$gameset['words']['normal']}"; ?></span>
        <?php elseif ($gameset['type'] == 2) : ?>
          <span><?php echo $gameset['content']; ?></span>
        <?php endif; ?>
        </td>

        <td>
          <span><?php echo $response['content']?></b></span>
        </td>

        <td>
        <a name="btn-update" data-id="<?php echo $response['id']; ?>" data-content="<?php echo $response['content']; ?>" href="javascript:;">编辑</a>
          &nbsp; &nbsp; &nbsp; &nbsp;
          <a name="btn-delete" data-id="<?php echo $response['id']; ?>" href="javascript:;">删除</a>
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
//添加,修改 回复
var add_str = '&nbsp;添加&nbsp;';
var edit_str = '&nbsp;修改&nbsp;';
var pending = false;
$("#add-edit-response").on('click', function () {
  if (pending) return false;

  var id = parseInt($(this).attr('data-id'));
  var rid = parseInt($(this).attr('data-rid'));
  if (!rid) {
    $('#response').focus();
  <?php if ($gameset['type'] == 1) : ?>
    $.addtip({type: "error", message: "无法获取卧底词编号！", autoclose: 3});
  <?php elseif ($gameset['type'] == 2) : ?>
    $.addtip({type: "error", message: "无法获取惩罚编号！", autoclose: 3});
  <?php endif; ?>
    return false;
  }

  var response = $.trim($('#response').val());
  if (!response) {
    $('#response').focus();
    $.addtip({type: "error", message: "请填写回复内容！", autoclose: 3});
    return false;
  }

  var _this = this;
  pending = true;
  $.DeAjax("add-edit-response", {
    type: 'POST',
    dataType: 'json',
    url: '<?php echo $this->getDeUrl('gameset/addupdate', array('id' => $this->permissionId))?>',
    data: {'gid':id, 'content':response, 'rid':rid, '_sh_token_':_sh_token_},
    success: function (res) {
      pending = false;
      if (res.code) {
        $.addtip({type: "error", message: res.msg, autoclose: 3});
      } else {
        setTimeout(function () {
          $(_this).children().html(add_str);
        }, 500);
        $(_this).attr('data-id', '');
        $('#response').val('');
        $.addtip({message: "操作成功", autoclose: 3});
      }
    },
    error: function() {
      pending = false;
      $.addtip({type: "error", message: "操作失败！", autoclose: 3});
    }
  });
});

//Enter快捷键查询
$("#response").on('keydown', function(e) {
  var keyCode = window.event ? e.keyCode : e.which;
  if (keyCode == 13) {
    $("#add-edit-response").click();
  }
  return true;
});

//点击编辑按钮
$('#contentTbody a[name="btn-update"]').on('click', function() {
  var id = $(this).attr('data-id');
  var response = $(this).attr('data-content');

  $('#add-edit-response').children().html(edit_str);
  $('#add-edit-response').attr('data-id', id);
  $('#response').val(response).focus();
});

//删除回复
var deleting = false;
$('#contentTbody a[name="btn-delete"]').on('click', function() {
  if (deleting) return false;

  var id = parseInt($(this).attr('data-id'));
  if (!id) {
    $.addtip({type: "error", message: "找不到这个惩罚的编号！", autoclose: 3});
    return false;
  }

  deleting = true;
  var title = '删除回复';
  var content = "<H3>确定删除该回复项吗？</H3>";
  $.showWindow({id:"windowBox", title:title, content:content,
    button:[{idname: "sureConfirm", title:"确定",
      callback: function () {
        $('#btn-Confirm span').text('处理中，请稍候...');
        $('#cancel_id').unbind();
        $('#btn-Confirm').unbind();
        $.DeAjax("sureConfirm", {
          type: 'POST',
          dataType: 'json',
          url: '<?php echo $this->getDeUrl('gameset/delete', array('id' => $this->permissionId))?>',
          data: {"gid":id, '_sh_token_':_sh_token_},
          success: function (res) {
            deleting = false;
            $("#admin_tip").remove();
            if (res.code) {
              $.addtip({type: "error", message: res.msg, autoclose: 3});
            } else {
              $.addtip({message:"操作成功", autoclose: 3});
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
