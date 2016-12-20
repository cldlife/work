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
              <label for="punish">添加游戏惩罚：</label>
              <input class="txt" style="width:360px;" type="text" maxlength="50" placeholder="输入惩罚..." id='punish' name='punish' value=""/>
              <a class="confirm_btn" id="add-edit-punish"><span>&nbsp;添加&nbsp;</span></a>
              <a id="btn-refresh-a" href="javascript:location.reload();"><span>刷新</span></a>
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
        <th width="20%">编号</th>
        <th width="40%">惩罚内容</th>
        <th width="40%">操作</th>
      </tr>
    </thead>
    <tbody id="contentTbody">
    <?php
    if ($punishList) :
      foreach ($punishList as $punish) :?>
      <tr>
        <td> <?php echo $punish['id']; ?> </td>

        <td>
          <span><?php echo $punish['content']?></b></span>
        </td>

        <td>
          <a name="btn-view" href="<?php echo $this->getDeUrl('gameset/response', array('id' => $this->permissionId, 'rid' => $punish['id'], 'ref' => urlencode($this->getDeUrl('gameset/punish', array('id' => $this->permissionId, 'page' => $page))))); ?>">查看相应回复</a>
          &nbsp; &nbsp; &nbsp; &nbsp;
    <a name="btn-update" data-id="<?php echo $punish['id']; ?>" data-content="<?php echo $punish['content']; ?>" href="javascript:;">编辑</a>
          &nbsp; &nbsp; &nbsp; &nbsp;
          <a name="btn-delete" data-id="<?php echo $punish['id']; ?>" href="javascript:;">删除</a>
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
//添加,修改 惩罚
var add_str = '&nbsp;添加&nbsp;';
var edit_str = '&nbsp;修改&nbsp;';
var pending = false;
$("#add-edit-punish").on('click', function () {
  if (pending) return false;

  var id = parseInt($(this).attr('data-id'));
  var punish = $.trim($('#punish').val());
  if (!punish) {
    $('#punish').focus();
    $.addtip({type: "error", message: "请填写惩罚内容！", autoclose: 3});
    return false;
  }

  var _this = this;
  pending = true;
  $.DeAjax("add-edit-punish", {
    type: 'POST',
    dataType: 'json',
    url: '<?php echo $this->getDeUrl('gameset/addupdate', array('id' => $this->permissionId)); ?>',
    data: {'gid':id, 'content':punish, 'type':2, '_sh_token_':_sh_token_},
    success: function (res) {
      pending = false;
      if (res.code) {
        $.addtip({type: "error", message: res.msg, autoclose: 3});
      } else {
        setTimeout(function () {
          $(_this).children().html(add_str);
        }, 500);
        $('#punish').val('');
        $(_this).attr('data-id', '');
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
$("#punish").on('keydown', function(e) {
  var keyCode = window.event ? e.keyCode : e.which;
  if (keyCode == 13) {
    $("#add-edit-punish").click();
  }
  return true;
});

//点击编辑按钮
$('#contentTbody a[name="btn-update"]').on('click', function() {
  var id = $(this).attr('data-id');
  var punish = $(this).attr('data-content');

  $('#add-edit-punish').children().html(edit_str);
  $('#add-edit-punish').attr('data-id', id);
  $('#punish').val(punish).focus();
});

//删除惩罚
var deleting = false;
$('#contentTbody a[name="btn-delete"]').on('click', function() {
  if (deleting) {
    $.addtip({type: "error", message: "正在删除，请稍候！", autoclose: 3});
    return false;
  }

  var _this = this;
  var id = parseInt($(_this).attr('data-id'));
  if (!id) {
    $.addtip({type: "error", message: "找不到这个惩罚的编号！", autoclose: 3});
    return false;
  }

  deleting = true;
  var title = '删除惩罚';
  var content = "<H3>确定删除该惩罚项吗？</H3>";
  $.showWindow({id:"windowBox", title:title, content:content,
    button:[{idname: "sureConfirm", title:"确定",
      callback: function () {
        $('#btn-Confirm span').text('处理中，请稍后...');
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
