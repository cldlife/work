<div id="content">
    <div class="itemtitle">
   	<h3><span name='page_title'><?php echo $this->title?></span> <a href="<?php echo $this->getDeUrl('domain/grouplist', array('id' => $this->permissionId, 'page' => $curPage))?>" style="color:#0066ff;font-size:12px;font-weight:normal">返回&gt;&gt;</a></h3>
    </div>     
    <div class="itemlist">
        <div class="search submit">
          <table>
            <tbody>
               <tr>
                <td>
                 <span name='page_title'><?php echo $this->title?></span>
                 <a class="confirm_btn" name="btn-submit-a"><span>&nbsp;保存&nbsp;</span></a>
                 <?php if ($code == 1 || $group) :?>
                 <a class="confirm_btn" href="<?php echo $this->getDeUrl('domain/groupaddedit', array('id' => $this->permissionId));?>"><span>&nbsp;继续添加&nbsp;</span></a>
                 <?php endif;?>
                 </td>
               </tr>
            </tbody>
          </table>
        </div>
    </div>
    
    <div class="itemlist">
    <?php echo CHtml::beginForm($this->getDeUrl('domain/groupaddedit', array('id' => $this->permissionId)), 'post', array('id' => 'submitForm', 'name' => 'submitForm'))?>
    <table>
    	<tbody id="contentTbody">
          <tr>
            <th width="20%" style="text-align:center">分组名称</th>
            <td>
              <input type="text" class="txt" style="width:200px;" id="name" name="name" value="<?php echo $group['name']?>">
              <span class="txt_tip"><i></i>必填项</span>
            </td>
            <td></td>
          </tr>
    	  	
          <tr>
            <th width="20%" style="text-align:center">链接规则</th>
            <td>
              <input type="text" class="txt" style="width:200px;" id="domainLevelUris" name="domainLevelUris" value="<?php echo $group['domain_level_uris']?>">
              <span class="txt_tip"><i></i>必填项，仅后台使用</span>
            </td>
            <td></td>
          </tr>
          
          <tr>
            <th width="20%" style="text-align:center">是否可移动域名</th>
            <td>
              <input name="is_move" type="checkbox" value="<?php echo $group['is_move']?>" <?php if ($group && $group['is_move'] == 1) echo 'checked' ?>/>
              <span class="txt_tip"><i></i>开启后，分组之间可移动域名</span>
            </td>
          </tr>

          <tr>
            <th width="20%" style="text-align:center">是否开启泛域名</th>
            <td>
              <input name="is_twodomain" type="checkbox" value="<?php echo $group['is_twodomain']?>" <?php if ($group && $group['is_twodomain'] == 1) echo 'checked' ?>/>
              <span class="txt_tip"><i></i>开启后，将提取随机3级域名，请确认域名解析是否支持，以免导致不可访问</span>
            </td>
          </tr>
          
          <tr>
            <th width="20%" style="text-align:center">设置域名轮换规则</th>
            <td>
              <input name="is_random" type="checkbox" value="<?php echo $group['is_random']?>" <?php if ($group && $group['is_random'] == 1) echo 'checked' ?>/>
              <span class="txt_tip"><i></i>不设置，默认将随机提取域名</span>
            </td>
          </tr>
          
          <tr class="domainCycleLens" style="display: none;">
            <th width="20%" style="text-align:center">域名数量</th>
            <td>
              <input type="text" class="txt" style="width:200px;" id="domainCycleLens" name="domainCycleLens" value="<?php echo $group['domain_cycle_lens']?>">
              <span class="txt_tip"><i></i>填数子，例如20</span>
            </td>
            <td></td>
          </tr>

          <tr class="domainCycleTimes" style="display: none;">
            <th width="20%" style="text-align:center">轮换时间</th>
            <td>
              <input type="text" class="txt" style="width:200px;" id="domainCycleTimes" name="domainCycleTimes" value="<?php echo $group['domain_cycle_times']?>">
              <span class="txt_tip"><i></i>填秒数，例如180秒</span>
            </td>
            <td></td>
          </tr>
          
          <tr>
            <th width="20%" style="text-align:center">百度统计code</th>
            <td>
              <input type="text" class="txt" style="width:200px;" id="baidu_code" name="baidu_code" value="<?php echo $group['baidu_code']?>">
            </td>
            <td></td>
          </tr>

          <tr>
        	  <th width="20%" style="text-align:center">分组备注</th>
        	  <td>
        	    <textarea rows="3" style="width:300px;" id="remark" name="remark" class="txt"><?php echo $group['remark']?></textarea>
        	  </td>
        	  <td></td>
    	  	</tr>
      </tbody>
      
      <tfoot class="tfoot">
      <input type="hidden" name="action" value="submit">
      <input type="hidden" id='group_id' name='group_id' value="<?php echo $group['level']?>">
      </tfoot>
    </table>
    <?php echo CHtml::endForm();?>
    </div>
    
    <div class="itemlist">
        <div class="search submit">
          <table>
            <tbody>
               <tr>
                <td>
                 <span name='page_title'><?php echo $this->title?></span>
                 <a class="confirm_btn" name="btn-submit-a"><span>&nbsp;保存&nbsp;</span></a>
                 <?php if ($code == 1 || $group) :?>
                 <a class="confirm_btn" href="<?php echo $this->getDeUrl('domain/groupaddedit', array('id' => $this->permissionId));?>"><span>&nbsp;继续添加&nbsp;</span></a>
                 <?php endif;?>
                 </td>
               </tr>
            </tbody>
          </table>
        </div>
    </div>
</div>
<br>

<script type="text/javascript">
var _sh_token_ = '<?php echo Yii::app()->getRequest()->getCsrfToken()?>';
var questionnum = 3;
var chosenum = 3;
<?php if ($code == 1) :?>
$.addtip({message: "保存成功", autoclose: 3});
<?php endif;?>

<?php if ($group['is_random'] == 1) :?>
  if ($("input[name='is_random']").is(':checked')){
      $(".domainCycleTimes").show();
      $(".domainCycleLens").show();
  }
<?php endif;?>
$("input[name='is_twodomain']").bind('click', function () {
     if ($(this).attr("checked")) {
        $("input[name='is_twodomain']").val(1);
     } else {
        $("input[name='is_twodomain']").val(0);
     }
})

$("input[name='is_move']").bind('click', function () {
     if ($(this).attr("checked")) {
        $("input[name='is_move']").val(1);
     } else {
        $("input[name='is_move']").val(0);
     }
})

$("input[name='is_random']").bind('click', function () {
     if ($(this).attr("checked")) {
        $("input[name='is_random']").val(1);
        $(".domainCycleTimes").show();
        $(".domainCycleLens").show();
     } else {
        $("input[name='is_random']").val(0);
        $(".domainCycleTimes").hide();
        $(".domainCycleLens").hide();
     }
})



$("a[name='btn-submit-a']").click(function () {
  
  var name = $.trim($('#name').val());
  if (!name) {
    $('#name').focus()
    $.addtip({type: "error", message: "请输入分组名称！", autoclose: 3});
    return false;
  }

  var title = $($('span[name="page_title"]')[0]).text();
  var content = "<H3>确定保存？保存成功将立刻生效。</H3>";
  $.showWindow({id:"windowBox", title:title,content:content,
    button:[{idname: "btn-Confirm", title:"确定",
      callback:function(){
        $('#btn-Confirm span').text('处理中，请稍后...')
        $('#cancel_id').unbind();
        $('#btn-Confirm').unbind();
        $('#submitForm').submit();
        return false;
      }
    }, {title:"取消", callback:function(){
  	  return true;
  	}}
    ]
  })
});
</script>
