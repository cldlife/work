<style>
.money {font-size: 13px;color:#ff4433;}
.withdraw{padding: 5px 10px;}
.withdraw span{color:#999}
.withdraw textarea{width: 240px;height: 80px;}
</style>
<div id="content">
    <div class="itemtitle">
   	<h3><?php echo $this->title?> <?php if ($ref):?><a href="<?php echo $this->getDeUrl($ref, array('id' => $this->permissionId, 'action' => 'search', 'page' => $curPage, 'id' => $qaId, 'status' => $status))?>" style="color:#0066ff;font-size:12px;font-weight:normal">返回&gt;&gt;</a><?php endif;?></h3>
    </div>     
    
    <div class="itemlist">
        <div class="search submit">
          <table>
            <tbody>
              <tr>
                <td colspan="5">
                 <input type="text" class="txt" style="width:240px;" id="keyword" name="keyword" value="<?php if ($keyword) echo $keyword;?>" placeholder="输入题目ID或微信商户单号...">
                 <a class="confirm_btn" id="btn-search-a"><span>&nbsp;查询&nbsp;</span></a> <a id="btn-refresh-a" href="javascript:location.reload(true);">
                 <span>刷新</span></a>
                 </td>
               </tr>
            </tbody>
          </table>
        </div>
    </div>
    
    <?php if ($questionInfo):?>
    <div class="itemlist">
      <div>
        <img src="<?php echo $questionInfo['user_info']['avatar'];?>" style="vertical-align: bottom;width:40px;border-radius: 40px;">
        <div style="display: inline-block;font-size: 13px;width: 50%;">
        <?php echo $questionInfo['user_info']['nickname'];?><br><font style="color:#999;font-size: 12px;">用户ID:<?php echo $questionInfo['user_info']['uid'];?> 微信授权ID: <?php echo $questionInfo['wxpay_info']['openid'];?></font>
        </div>
      </div>
      <p><b>游戏名称</b>：<?php echo $questionInfo['name'];?></p>
      <p><b>出题时间</b>：<?php echo $questionInfo['cdate'];?></p>
      <p><b>链接</b>：<?php echo $questionInfo['link'];?></p>
    </div>
    <?php endif;?>
    
    <?php if ($pager) : ?>
    <div class="itemlist">
    	<?php echo $pager?>
    	<br>
    </div>
    <?php endif;?>
    
    <div class="itemlist">
    <table>
      <thead>
      	<tr>
          <th width="10%">答案编号</th>
          <th width="30%">用户信息</th>
          <th width="10%">是否有偷看答案</th>
          <th width="10%">重新答题次数</th>
          <th width="10%">回答时间</th>
          <th width="20%">操作</th>
		</tr>
      </thead>
      <tbody id="contentTbody">
      	<?php 
      	if ($qaList) :
      	  foreach ($qaList as $qa) :?>
    	  	<tr id="row-<?php echo $qa['id']?>">
        	  <td><?php echo $qa['id'];?></td>
        	  
        	  <td>
          	    <div>
                  <img src="<?php echo $qa['user_info']['avatar'];?>" style="vertical-align: bottom;width:40px;border-radius: 40px;">
                  <div style="display: inline-block;font-size: 13px;font-size: 12px;">
                  <?php echo $qa['user_info']['nickname'];?><br><font color="#999">用户ID:<?php echo $qa['user_info']['uid'];?></font>
                  </div>
                </div>
        	  </td>
        	  
        	  <td>
        	  <?php echo $qa['is_pay'] ? '是（已支付）' : '否'; ?>
        	  </td>
        	  <td>
            	<?php echo $qa['reanswer_num']; ?>
              </td>
        	  <td><?php echo date(DATE_FORMAT, $qa['created_time']);?></td>
        	  <td>
        	  <div id="status-div-<?php echo $qa['id']?>">
            	  <p style="color: #999"><?php echo $qa['status_desc'];?></p>
            	  <?php if ($qa['status'] == 0) : ?>
            	  <a name="btn-qa-a" data-id="<?php echo $qa['id']?>" href="javascript:;">删除</a>
            	  <?php else:?>
            	  <a name="btn-qa-a" data-id="<?php echo $qa['id']?>" href="javascript:;">恢复</a>
            	  <?php endif;?>
        	  </div>
        	  </td>
    	  	</tr>
  	  	<?php 
  	  	  endforeach;
  	  	else :?>
  	  	<tr><td colspan='6'>未查询到相关数据</td></tr>
  	  	<?php endif;?>
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
  var keyword = $("#keyword").val();
  window.location.href="<?php echo $this->getDeUrl('know/questionanswer', array('id' => $this->permissionId))?>" + '&action=search&keyword='+keyword;
});
//Enter快捷键查询
$("#keyword").bind('keydown', function(e) {
  var keyCode = window.event ? e.keyCode : e.which;
  if (keyCode == 13) {  
    $("#btn-search-a").click();
  }
});

//发放奖励
$('#contentTbody a[name="btn-qa-a"]').bind('click', function() {
  var _this = this;
  var aid = $(_this).attr('data-id');

  var title = $(_this).text() + '答案';
  var content = '<H3>确定'+title+'吗？操作成功后将立刻生效。</H3>';
  $.showWindow({id:"windowBox", title: title,content:content,
    button:[{idname: "sureConfirm", title:"确定",
      callback:function(){
    	  $('#btn-Confirm span').text('处理中，请稍后...');
          $('#cancel_id').unbind();
          $('#btn-Confirm').unbind();
    	  $.DeAjax("sureConfirm", {
          type: 'POST',
          dataType: 'json',
          url: '<?php echo $this->getDeUrl('know/questionanswer/up', array('id' => $this->permissionId))?>',
          data: {"qid": '<?php echo $questionInfo['id'];?>', "aid": aid, '_sh_token_':_sh_token_},
          success: function(res){
      	    $("#admin_tip").remove();
            if (res.code == 1) {
        	  $('#status-div-' + aid).text(res.data.statusDesc);
        	  $.addtip({message: "操作成功", autoclose: 3});
            }
          },
          error: function() {
      	    $("#admin_tip").remove();
            $.addtip({type: "error", message: "更新失败，请检查！", autoclose: 3});
          }
        });
        return true;
      }
    }, {title:"取消", callback:function(){
  	  return true;
  	}}
    ]
  });
});
</script>
