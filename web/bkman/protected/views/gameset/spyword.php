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
              <label for="spyword">查询卧底词：</label>
              <input class="txt" style="width:160px;" type="text" maxlength="10" placeholder="请填写查询关键词..." id='keyword' name='keyword' value=""/>
              <a class="confirm_btn" id="search-keyword"><span>&nbsp;查找&nbsp;</span></a>
              <a id="btn-refresh-a" href="javascript:location.reload();"><span>刷新</span></a>
              <br>
              <label for="spyword">添加卧底词：</label>
              <input class="txt" style="width:160px;" type="text" maxlength="10" placeholder="输入卧底词..." id='spyword' name='spyword' value=""/>
              <input class="txt" style="width:160px;" type="text" maxlength="10" placeholder="输入平民词..." id='normal-word' name='normal_word' value=""/>
              <a class="confirm_btn" id="add-edit-spyword"><span>&nbsp;添加&nbsp;</span></a>
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
        <th width="30%">卧底词</th>
        <th width="30%">平民词</th>
        <th width="30%">操作</th>
      </tr>
    </thead>
    <tbody id="contentTbody">
    <?php
    if ($wordList) :
      foreach ($wordList as $word) :?>
      <tr>
        <td> <?php echo $word['words_id']; ?> </td>

        <td>
          <span><?php echo $word['spy']?></b></span>
        </td>

        <td>
          <span><?php echo $word['normal']; ?></b></span><br/>
        </td>

        <td>
          <a name="btn-view" href="<?php echo $this->getDeUrl('gameset/response', array('id' => $this->permissionId, 'rid' => $word['words_id'], 'ref' => urlencode($this->getDeUrl('gameset/spyword', array('id' => $this->permissionId, 'page' => $page))))); ?>">查看相应回复</a>
          &nbsp; &nbsp; &nbsp; &nbsp;
    <a name="btn-update" data-id="<?php echo $word['words_id']; ?>" data-spy="<?php echo $word['spy']; ?>" data-normal="<?php echo $word['normal']; ?>" href="javascript:;">编辑</a>
          &nbsp; &nbsp; &nbsp; &nbsp;
          <a name="btn-delete" data-id="<?php echo $word['words_id']; ?>" href="javascript:;">删除</a>
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
//添加,修改 卧底词
var add_str = '&nbsp;添加&nbsp;';
var edit_str = '&nbsp;修改&nbsp;';
var pending = false;

$("#search-keyword").on('click', function () {
  var keyword = $.trim($('#keyword').val());
  if (!keyword) {
    $.addtip({type: "error", message: "请填写查询关键词！", autoclose: 3});
    return;
  }
  window.location.href = '<?php echo $this->getDeUrl('gameset/spyword', array('id' => $this->permissionId)); ?>&keyword=' + encodeURIComponent(keyword);
});

$("#add-edit-spyword").on('click', function () {
  if (pending) return false;

  var id = parseInt($(this).attr('data-id'));
  var spyword = $.trim($('#spyword').val());
  if (!spyword) {
    $('#spyword').focus();
    $.addtip({type: "error", message: "请填写卧底词！", autoclose: 3});
    return false;
  }
  var normal_word = $.trim($('#normal-word').val());
  if (!normal_word) {
    $('#normal-word').focus();
    $.addtip({type: "error", message: "请填写平民词！", autoclose: 3});
    return false;
  }

  pending = true;
  var _this = this;
  var content = '{"spy":"'+ spyword +'", "normal":"'+ normal_word +'"}';
  $.DeAjax("add-edit-spyword", {
    type: 'POST',
    dataType: 'json',
    url: '<?php echo $this->getDeUrl('gameset/addupdate', array('id' => $this->permissionId))?>',
    data: {'gid':id, 'type':1, 'content':content, '_sh_token_':_sh_token_},
    success: function (res) {
      pending = false;
      if (res.code) {
        $.addtip({type: "error", message:res.msg, autoclose: 3});
      } else {
        setTimeout(function () {
          $(_this).children().html(add_str);
        }, 500);
        $(_this).attr('data-id', '');
        $('#spyword').val('');
        $('#normal-word').val('');
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
$("#spyword, #normal-word").on('keydown', function(e) {
  var keyCode = window.event ? e.keyCode : e.which;
  if (keyCode == 13) {
    $("#add-edit-spyword").click();
  }
  return true;
});

//点击编辑按钮
$('#contentTbody a[name="btn-update"]').on('click', function() {
  var id = $(this).attr('data-id');
  var spyword = $(this).attr('data-spy');
  var normal_word = $(this).attr('data-normal');

  $('#add-edit-spyword').children().html(edit_str);
  $('#add-edit-spyword').attr('data-id', id);
  $('#spyword').val(spyword).focus();
  $('#normal-word').val(normal_word);
});

//删除卧底词
var deleting = false;
$('#contentTbody a[name="btn-delete"]').on('click', function() {
  if (deleting) return false;

  var id = parseInt($(this).attr('data-id'));
  if (!id) {
    $.addtip({type: "error", message: "找不到这个卧底词的编号！", autoclose: 3});
    return false;
  }

  deleting = true;
  var title = '删除惩罚';
  var content = "<H3>确定删除该卧底词吗？</H3>";
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
              $.addtip({type: "error", message:res.msg, autoclose: 3});
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
