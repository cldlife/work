<style>
td a {cursor: pointer; }
.cur-domain{padding: 5px;border: 1px dashed #ccc;margin-top: 10px;}
.group-remark{padding: 5px 5px 0 5px;}
.group-rule-uri{padding: 5px 5px 0 5px;}
#block-remark-group-div {display: none}
</style>
<div id="content">
   <div class="itemtitle">
     <h3><?php echo $this->title?></h3>
   </div>

   <div class="itemlist">
      <a href="<?php echo $this->getDeUrl("domain/domain", array('id' => $this->permissionId, 'status' => $curStatus, 'level' => -1))?>" <?php if ($curLevel == -1) echo 'style="color:#999"';?>>全部分组</a>
      <a href="<?php echo $this->getDeUrl("domain/groupaddedit", array('id' => $this->permissionId))?>" style="color:#0066ff;font-size:12px;font-weight:normal">+添加分组</a>
      <?php foreach ($domainLevel as $key => $level) : ?>
       | <a href="<?php echo $this->getDeUrl("domain/domain", array('id' => $this->permissionId, 'status' => $curStatus, 'level' => $key))?>" <?php if ($key == $curLevel) echo 'style="color:#999"';?>><?php echo "{$key}::{$level['name']}"; ?></a>
      <?php endforeach; ?>
      <?php if ($curOnlineDomains[$curLevel]) :?>
      <div class="cur-domain">
        <div>当前正使用的域名：<?php echo $curOnlineDomains;?></div>
        <div>域名轮换规则：轮换<?php echo $domainCycleLens?>个，<?php echo $domainCycleTimes?>秒/个</div>
      </div>
      <?php endif;?>
      
      <?php if ($curLevel != -1):?>
      <?php if ($domainLevelUris) :?>
      <div class="group-rule-uri">
        <label><strong>规则链接:</strong></label> <?php echo $domainLevelUris;?>
      </div>
      <?php endif;?>
      
      <?php if($curLevel == '2'):?>
      <div class="group-rule-uri">
        <label><strong>集合页站点分类编号:</strong></label> 
        <?php foreach ($gamelistCategories as $catetoryId => $catetoryName) :?>
        <?php echo "{$catetoryId}: {$catetoryName}，"?>
        <?php endforeach;?>
      </div>
      <?php endif;?>
      
      <div class="group-remark">
        <label><strong>分组备注:</strong></label>
        <?php echo $remark;?>
        <a class="confirm_btn" href="<?php echo $this->getDeUrl("domain/groupaddedit", array('id' => $this->permissionId, 'group_id' => $curLevel))?>"><span>&nbsp;编辑分组&nbsp;</span></a>
        </div></br>
    </div> 
    <?php endif;?>
   </div>
    
    <div class="itemlist">
      <div class="search submit">
        <table>
          <tbody>
            <tr>
              <td colspan="5">
                <label for="domain_status">查询：</label>
                <select name="domain_status" id="domain_status">
                  <?php foreach ($domainStatus as $key => $status) : ?>
                  <option value="<?php echo $key; ?>" <?php if ($key == $curStatus) echo 'selected="selected"'; ?>><?php echo $status; ?></option>
                  <?php endforeach; ?>
                </select>
  
                <?php if($curLevel == '2'):?>
                <select name="category" id="category">
                  <?php foreach ($gamelistCategories as $id => $name) : ?>
                  <option value="<?php echo $id; ?>" <?php if ($id == $category) echo 'selected="selected"'; ?>><?php echo $name; ?></option>
                  <?php endforeach; ?>
                </select>
                <?php endif; ?>
                <a class="confirm_btn" id="btn-search-a"><span>&nbsp;查询&nbsp;</span></a> <a id="btn-refresh-a" href="javascript:location.reload(true);">
                <span>刷新</span></a>
              </td>
            </tr>

            <tr>
              <td colspan="5">
                <label for="domain">添加域名</label>
                <span class="txt_tip"><i></i>温馨提示：域名地址格式(hhh.ishihuo.cn)</span>
                <?php if (!$randSecondDomainLevels) :?>
                <font color="red"> 固定二级域名，非泛解析</font>
                <?php endif;?>
                <br>
                <label for="status">域名地址:</label>
                <input type="text" class="txt" id="domain" name="domain" style="width:100px;" value="">
                <label for="status">域名状态:</label>
                <select name="status" id="status">
                  <?php foreach ($domainStatus as $key => $status) : ?>
                  <option value="<?php echo $key; ?>" <?php if ($key == 1) echo 'selected="selected"'; ?>><?php echo $status; ?></option>
                  <?php endforeach; ?>
                </select>
     
                <label for="level" <?php if(!$is_move):?>style="display: none;"<?php endif; ?>>分组:</label>
                <select name="level" id="level" <?php if(!$is_move):?>style="display: none;"><?php endif; ?>
                  <?php foreach ($domainLevel as $key => $level) : ?>
                  <option value="<?php echo $key; ?>" <?php if ($key == $curLevel) echo 'selected="selected"'; ?>><?php echo $level['name']; ?></option>
                  <?php endforeach; ?>
                </select>
              
                <?php if($curLevel == '2'):?>
                <label for="add_category">游戏站点分类:</label>
                <select name="add_category" id="add_category">
                  <?php foreach ($gamelistCategories as $id => $name) : ?>
                  <option value="<?php echo $id; ?>" <?php if ($id == $category) echo 'selected="selected"'; ?>><?php echo $name; ?></option>
                  <?php endforeach; ?>
                </select>
                <?php endif; ?>
                <label for="status">到期日期:</label>
                <input type="text" class="txt" id="expiring_date" name="expiring_date" style="width:100px;" value="">
                <label for="status">备注(后台可见):</label>
                <input type="text" class="txt" id="remarks" name="remarks" style="width:300px;" value="">
                <a class="confirm_btn" id="btn-submit-a"><span>&nbsp;添加域名&nbsp;</span></a>
                <a class="confirm_btn" id="btn-cancel-a" style="display:none;"><span>&nbsp;取消&nbsp;</span></a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <?php if ($pager) :?>
    <div class="itemlist" style="overflow: hidden;">
      <?php echo $pager?>
    </div>
    <?php endif;?>

    <div class="itemlist">
    <table>
      <thead>
        <?php if ($curLevel != -1):?>
        <tr>
          <td colspan="2">
            <label for="status">全选:</label>
            <input id='checkall' name="checkall" type="checkbox" value="" />
            <a class="createurlall" >生成全部链接</a>
            <a data-src="" class="copyurlall" style="display: none;">复制全部链接</a>
          </td>
        </tr>
        <?php endif;?>
        <tr>
          <th width="5%">选择</th>
          <th width="5%">编号</th>
          <th width="10%">分组</th>
          <th width="20%">域名地址</th>
          <th width="10%">过期时间</th>
          <th width="15%">备注(仅后台可见)</th>
          <th width="10%">创建时间</th>
          <th width="30%">操作</th>
        </tr>
      </thead>
      <tbody id="contentTbody">
      <?php if ($domainList) : ?>
        <?php foreach($domainList as $domain) : ?>
        <tr>
          <td><input name="chk_list" type="checkbox" value="" /></td>
          <td><?php echo $domain['id']; ?></td>
          <td><?php echo $domain['domain_level']['name']; ?></td>
          <td><?php echo $domain['address']; ?></td>
          <td><?php echo $domain['expiring_date']; ?></td>
          <td><?php echo $domain['remarks']; ?></td>
          <td><?php echo $domain['cdate']; ?></td>
          <td>
            
      	    <font color='#999'><?php echo $domain['domain_status']; ?>，于 <?php echo $domain['update'];?> 更新</font>
            <br>
            <a name="btn-edit-domain" data-id="<?php echo $domain['id']; ?>" data-address="<?php echo $domain['address'];?>" data-status="<?php echo $domain['status'];?>" data-level="<?php echo $domain['level'];?>" data-expiringdate="<?php echo $domain['expiring_date'];?>"  data-remarks="<?php echo $domain['remarks'];?>">编辑</a>
            <?php if ($curStatus == 0) : ?>
            <a name="btn-offline-domain" data-id="<?php echo $domain['id']; ?>" data-address="<?php echo $domain['address'];?>" data-status="<?php echo $domain['status'];?>" data-expiringdate="<?php echo $domain['expiring_date'];?>" data-level="<?php echo $domain['level'];?>" data-remarks="<?php echo $domain['remarks'];?>" data-category="<?php echo $domain['category'];?>">下线</a>
            <?php endif; ?>
            <?php if ($curStatus == 2 || $curStatus == 1) : ?>
            <a name="btn-online-domain" data-id="<?php echo $domain['id']; ?>" data-address="<?php echo $domain['address'];?>" data-status="<?php echo $domain['status'];?>"  data-expiringdate="<?php echo $domain['expiring_date'];?>" data-level="<?php echo $domain['level'];?>" data-remarks="<?php echo $domain['remarks'];?>" data-category="<?php echo $domain['category'];?>">上线</a>
            <?php endif; ?>
            
            <?php if ($curLevel != -1): ?>
              <?php if (!$randSecondDomainLevels) :?>
              <a data-src="http://<?php echo $domain['address']; ?><?php echo $domainLevelUris;?>" class="copyurl">复制链接</a>
              <?php else:?>
              <a data-src="http://<?php echo substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 5) . '.' .$domain['address']; ?><?php echo $domainLevelUris;?>" class="copyurl">复制链接</a>
              <?php endif;?>
            <?php endif;?>
          </td>

        </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="8">没有数据了哦~亲</td>
        </tr>
      <?php endif; ?>
      </tbody>
      <tfoot class="tfoot"></tfoot>
    </table>
    </div>

    <div class="itemlist" style="overflow: hidden;">
      <?php echo $pager?>
    </div>
</div>
<br>
<script type="text/javascript" src="http://f.shiyi11.com/ui/js/common/clipboard/ZeroClipboard.min.js"></script>
<script type="text/javascript">
var _sh_token_ = '<?php echo Yii::app()->getRequest()->getCsrfToken()?>';
var domain_id = 0;

$("#btn-search-a").click(function () {
  var domain_status = parseInt($("#domain_status").val());
  var category = parseInt($("#category").val());
  var domain_level = parseInt('<?php echo $curLevel;?>');
  window.location.href="<?php echo $this->getDeUrl('domain/domain', array('id' => $this->permissionId, 'action' => 'search'))?>" + '&status=' + domain_status + '&level=' + domain_level +'&category=' + category;
});

$("#contentTbody a[name='btn-edit-domain']").click(function () {
  domain_id = $(this).attr('data-id');
  $("#domain").focus();
  $("#domain").val($(this).attr('data-address'));
  $("#status").val($(this).attr('data-status'));
  $("#level").val($(this).attr('data-level'));
  $("#expiring_date").val($(this).attr('data-expiringdate'));
  $("#remarks").val($(this).attr('data-remarks'));
  $("#btn-submit-a").html('<span>&nbsp;编辑域名&nbsp;</span>');
  $("#btn-cancel-a").show();
});

$("#btn-cancel-a").click(function () {
  domain_id = 0;
  $("#domain").val('');
  $("#status").val(1);
  $("#level").val(0);
  $("#remarks").val('');
  $("#btn-submit-a").html('<span>&nbsp;添加域名&nbsp;</span>');
  $(this).hide();
});

//添加域名
$("#btn-submit-a").click(function () {
  var address = $.trim($("#domain").val());
  var domain_status = parseInt($.trim($("#status").val()));
  var level = parseInt($.trim($("#level").val()));
  var category = parseInt($("#add_category").val());
  var remarks = $.trim($("#remarks").val());
  var expiring_date = $.trim($("#expiring_date").val());
  var action = domain_id ? 'update' : 'add';

  if(!address){
    $("#address").focus();
    $.addtip({type: "error", message: "请填写域名内容！", autoclose: 3});
    return false;
  } else if (action == 'update' && !domain_id) {
    $.addtip({type: "error", message: "亲，数据没有填写完整哦", autoclose: 3});
    return false;
  } else if (action == 'add' && domain_id) {
    $.addtip({type: "error", message: "数据异常，请重试！", autoclose: 3});
    return false;
  }

  if (address) {
    deAjax(this.id, {"action": action, 'domain_id': domain_id, "address": address, "status": domain_status, "level": level, "category" : category, 'expiring_date':expiring_date,'remarks': remarks, '_sh_token_':_sh_token_})
  } else {
    $.addtip({type: "error", message: "亲，数据没有填写完整哦", autoclose: 3});
  }
});

//上线域名
$("#contentTbody a[name='btn-online-domain']").click(function () {
  if (confirm('你确定要上线该域名吗？')) {
    var action = 'update'
    var domain_id = parseInt($(this).attr('data-id'));
    var address = $(this).attr('data-address');
    var domain_status = 0;
    var level = $(this).attr('data-level');
    var expiring_date = $(this).attr('data-expiringdate');
    var category = $(this).attr('data-category');
    var remark = $(this).attr('data-remarks');
    if (domain_id && address) {
      deAjax(this.id, {"action": action, 'domain_id': domain_id, "address": address, "status": domain_status, "level": level, 'expiring_date':expiring_date, 'remarks':remark, 'category':category, '_sh_token_':_sh_token_})
    } else {
      $.addtip({type: "error", message: "数据异常，请重试！", autoclose: 3});
      return false;
    }
  }
});

//下线域名
$("#contentTbody a[name='btn-offline-domain']").click(function () {
  if (confirm('你确定要下线该域名吗？')) {
    var action = 'update'
    var domain_id = parseInt($(this).attr('data-id'));
    var address = $(this).attr('data-address');
    var domain_status = 2;
    var level = $(this).attr('data-level');
    var expiring_date = $(this).attr('data-expiringdate');
    var category = $(this).attr('data-category');
    var remark = $(this).attr('data-remarks');
    if (domain_id && address) {
      deAjax(this.id, {"action": action, 'domain_id': domain_id, "address": address, "status": domain_status, "level": level, 'expiring_date':expiring_date, 'remarks':remark, 'category':category, '_sh_token_':_sh_token_})
    } else {
      $.addtip({type: "error", message: "数据异常，请重试！", autoclose: 3});
      return false;
    }
  }
});

//保存游戏名称备注
$('#btn-edit-a').click(function () {
  if ($('#block-remark-group-div').css('display') == 'none') {
    $('#block-remark-group-div').show();
    $(this).text('取消');
  } else {
    $('#block-remark-group-div').hide();
    $(this).text('编辑'); 
  }
});
$("#btn-game_remarks-a").click(function () {
  if (confirm('你确定要保存吗？')) {
    var action = 'updategroup';
    var remark_group = $("#remark_group").val();
    var level = parseInt('<?php echo $curLevel;?>');
    if (action && remark_group) {
      deAjax(this.id, {"action": action, "level": level, 'remark_group': remark_group, '_sh_token_':_sh_token_})
    } else {
      $.addtip({type: "error", message: "数据异常，请重试！", autoclose: 3});
      return false;
    }
  }
});

function deAjax(id, fields) {
  $.DeAjax(id, {
    type:'POST',
    dataType:'json',
    url:'<?php echo $this->getDeUrl('domain/domain', array('id' => $this->permissionId))?>',
    data:fields,
    success:function(res){
      if (res.code == 0) {
        $.addtip({message: "保存成功", autoclose: 3});
        setTimeout(function () {
          window.location.href="<?php echo $this->getDeUrl('domain/domain', array('id' => $this->permissionId, 'status' => $curStatus, 'level' => $curLevel, 'category' => $category))?>";
        }, 1000);
      } else if (res.code == 1) {
        $.addtip({type: "error", message: "亲，数据没有填写完整哦", autoclose: 3});
      } else if (res.code == 3) {
        $.addtip({type: "error", message: "保存失败，该域名已经添加且属于不可移动的分组内", autoclose: 3});
      } else {
        $.addtip({type: "error", message: "保存失败，请重试", autoclose: 3});
      }
    },
    error:function(){
      $.addtip({type: "error", message: "数据异常，请重试！", autoclose: 3});
    }
  });
} 

var client = new ZeroClipboard($('.copyurl'));
client.on( 'ready', function(event) {
  client.on( 'copy', function(event) {
    var src = $(event.target).attr('data-src');
    event.clipboardData.setData('text/plain', src);
  });
  client.on( 'aftercopy', function(event) {
    // console.log('Copied text to clipboard: ' + event.data['text/plain']);
    $.addtip({message: "复制成功，按右键或ctrl＋v粘贴", autoclose: 3});
  });
});
client.on( 'error', function(event) {
  ZeroClipboard.destroy();
});

$('.createurlall').on('click', function () {
    var arr = [];
    var items = $('#contentTbody tr');
    var copyurlall = $('.copyurlall');
    items.each(function (index, item) {
      var checkbox = $(item).find('input[type="checkbox"]');
      if(checkbox.attr('checked')) {
        var copyurl = $(item).find('.copyurl');
        arr.push(copyurl.attr('data-src'));
      }
    })
    if (arr.length == 0) {
       $.addtip({type: "error", message: "请选择要复制的链接打勾！", autoclose: 3});
       return false;
    }
    var strArr = arr.join('\n');
    copyurlall.attr('data-src', strArr);
    $(this).hide();
    copyurlall.show();
})

var clientall = new ZeroClipboard($('.copyurlall'));
clientall.on( 'ready', function(event) {
  clientall.on( 'copy', function(event) {
    var src = $(event.target).attr('data-src');
    event.clipboardData.setData('text/plain', src);
  });
  clientall.on( 'aftercopy', function(event) {
    // console.log('Copied text to clipboard: ' + event.data['text/plain']);
    $.addtip({message: "复制成功，按右键或ctrl＋v粘贴", autoclose: 3});
  });
});
clientall.on( 'error', function(event) {
  ZeroClipboard.destroy();
});

$("#checkall").click(function(){
  if ($(this).attr("checked")=='checked') {
    $("input[name='chk_list']").attr("checked",$(this).attr("checked"));
  }else{
    $("input[name='chk_list']").removeAttr("checked","checked");
  } 
});
</script>