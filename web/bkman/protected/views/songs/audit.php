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
              <label for="status">状态：</label>
              <select name="status" id="status">
                <option value="-1" <?php if ($status == -1) echo 'selected'?>>全部</option>
                <option value="0" <?php if ($status == 0) echo 'selected'?>>未通过</option>
                <option value="1" <?php if ($status == 1) echo 'selected'?>>已通过</option>
              </select>
              <input class="txt" style="width:160px;" type="text" maxlength="11" placeholder="输入歌曲名称..." id='keyword' name='keyword' value="<?php echo $keyword; ?>"/>
              <a class="confirm_btn" id="btn-search-a"><span>&nbsp;查询&nbsp;</span></a>
              <a id="btn-refresh-a" href="javascript:location.reload();"><span>刷新</span></a>
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
        <th width="8%">歌曲编号</th>
        <th width="20%">用户信息</th>
        <th width="30%">歌曲信息</th>
        <th width="15%">发布时间</th>
        <th width="20%">操作</th>
  	</tr>
    </thead>
    <tbody id="contentTbody">
    	<?php
    	if ($songList) :
    	  foreach ($songList as $song) :?>
  	  	<tr id="row-<?php echo $song['tm_id']?>">
      	  <td> <?php echo $song['tm_id']; ?> </td>

      	  <td>
            <div>
              <img src="<?php echo $song['user_info']['avatar']; ?>" width="30" style="border-radius: 30px;" data-uid="6">
            </div>
            <span>用户昵称：<b><?php echo $song['user_info']['nickname']?></b> <?php echo $song['uid'];?></span><br/>
            <span>用户手机：<b><?php echo $song['user_info']['mobile']?></b></span>
      	  </td>

          <td>
            <span>歌曲名称：<b><?php echo $song['song_name']; ?></b></span><br/>
            <span>歌手名称：<b><?php echo $song['singer']; ?></b></span><br/>
            <span>歌曲时长：<b><?php echo $song['duration']; ?>秒</b></span>
            <?php if ($song['ori_name']) : ?><br/><span>文件名：<?php echo $song['ori_name']; ?></span><?php endif;?>
          </td>

      	  <td><?php echo Utils::getDiffTime($song['created_time'])?></td>

      	  <td>
            <span style="color: #999;" id="status-span-<?php echo $song['tm_id']; ?>"><?php echo $song['status'] == 0 ? '未通过' : '已通过'?>，于 <?php echo Utils::getDiffTime($song['updated_time'])?> 更新</span>
            <br>
            <a name="btn-recieve-a" data-id="<?php echo $song['tm_id']; ?>" data-st="<?php echo $song['status']; ?>" href="javascript:;"><?php echo $song['status'] == 0 ? '设为已通过' : '设为未通过'?></a>
            <a href="<?php echo $song['online_link'];?>" target="_blank">试听</a>
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
//查询
$("#btn-search-a").click(function () {
  var status = $("#status").val();
  var keyword = $.trim($("#keyword").val());
  var href = "<?php echo $this->getDeUrl('songs/audit', array('id' => $this->permissionId))?>" + '&action=search&status=' + status;
  if (keyword) href += '&keyword=' + keyword;
  window.location.href = href;
});
//Enter快捷键查询
$("#keyword").on('keydown', function(e) {
  var keyCode = window.event ? e.keyCode : e.which;
  if (keyCode == 13) {
    $("#btn-search-a").click();
  }
  return true;
});

//设置反馈意见是否已读
$('#contentTbody a[name="btn-recieve-a"]').on('click', function() {
  var _this = this;
  var tmId = parseInt($(_this).attr('data-id'));
  var songStatus = parseInt($(_this).attr('data-st'));

  var title = $(_this).text();
  var content = "<H3>确定"+ title +"该歌曲吗？"+ title +"将立即生效。</H3>";
  $.showWindow({id:"windowBox", title:title, content:content,
    button:[{idname: "sureConfirm", title:"确定",
      callback: function () {
    	  $('#btn-Confirm span').text('处理中，请稍后...')
          $('#cancel_id').unbind();
          $('#btn-Confirm').unbind();
    	  $.DeAjax("sureConfirm", {
          type: 'POST',
          dataType: 'json',
          url: '<?php echo $this->getDeUrl('songs/audit/onoff', array('id' => $this->permissionId))?>',
          data: {"tm_id":tmId, "status":songStatus, '_sh_token_':_sh_token_},
          success: function (res) {
      	    $("#admin_tip").remove();
            if (res.code == 0) {
      	      $.addtip({message: title + "成功", autoclose: 3});
        	  $(_this).text(res.status == 1 ? '设为已通过' : '设为未通过');
        	  $('#status-span-' + tmId).text(res.desc);
            } else {
              $.addtip({type: "error", message: "操作失败！", autoclose: 3});
            }
          },
          error: function() {
      	    $("#admin_tip").remove();
            $.addtip({type: "error", message: "更新失败，请检查！", autoclose: 3});
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
