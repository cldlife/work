<div id="content">
    <div class="itemtitle">
   	<h3><?php echo $this->title?></h3>
    </div>

    <div class="itemlist">
        <div class="search submit">
          <table>
            <tbody>
               <tr>
                <td>
                 <span>编辑<?php echo $this->title?></span>
                 <a class="confirm_btn" name="btn-submit-a"><span>&nbsp;保存&nbsp;</span></a>
                 </td>
               </tr>
            </tbody>
          </table>
        </div>
    </div>

    <div class="itemlist">
    <?php echo CHtml::beginForm($this->getDeUrl('bkadmin/news/publish/setting', array('id' => $this->permissionId)), 'post', array('id' => 'submitForm', 'name' => 'submitForm'))?>
    <table>
    	<input type="hidden" name="action" value="save">
    	<tbody id="contentTbody">
    	  	<tr>
        	  <th width="10%" style="text-align:center">发布标签</th>
        	  <td>
        	  	<textarea class="txt" style="width:420px;height:200px" id="copyright" name="publish_tags"><?php echo $publish_tags?></textarea>
        	  	<br>
        	    <span class="txt_tip"><i></i>自动发布新闻系统匹配发布标签并自动发布（用“|”分隔，请勿换行）</span>
        	  </td>
    	  	</tr>

    	  	<tr>
        	  <th width="10%" style="text-align:center">通用审核关键词<br>(1级优先)</th>
        	  <td>
        	  	<textarea class="txt" style="width:420px;height:200px" id="copyright" name="audit_keywords"><?php echo $audit_keywords?></textarea>
        	  	<br>
        	    <span class="txt_tip"><i></i>包含审核关键词的新闻、评论等内容，需人工审核（用“|”分隔，请勿换行）</span>
        	  </td>
    	  	</tr>

    	  	<tr>
        	  <th width="10%" style="text-align:center">新闻标题审核关键词<br>(2级优先)</th>
        	  <td>
        	  	<textarea class="txt" style="width:420px;height:200px" id="copyright" name="audit_keywords_subject"><?php echo $audit_keywords_subject?></textarea>
        	  	<br>
        	    <span class="txt_tip"><i></i>包含审核关键词的新闻标题，需人工审核（用“|”分隔，请勿换行）</span>
        	  </td>
    	  	</tr>

    	  	<tr>
        	  <th width="10%" style="text-align:center">新闻内容审核关键词<br>(3级优先)</th>
        	  <td>
        	  	<textarea class="txt" style="width:420px;height:200px" id="copyright" name="audit_keywords_content"><?php echo $audit_keywords_content?></textarea>
        	  	<br>
        	    <span class="txt_tip"><i></i>包含审核关键词的新闻内容，需人工审核（用“|”分隔，请勿换行）</span>
        	  </td>
    	  	</tr>
      </tbody>

      <tfoot class="tfoot"></tfoot>
    </table>
    <?php echo CHtml::endForm();?>
    </div>

    <div class="itemlist">
        <div class="search submit">
          <table>
            <tbody>
               <tr>
                <td>
                 <span>编辑<?php echo $this->title?></span>
                 <a class="confirm_btn" name="btn-submit-a"><span>&nbsp;保存&nbsp;</span></a>
                 </td>
               </tr>
            </tbody>
          </table>
        </div>
    </div>
</div>
<br>
<script type="text/javascript">
<?php if ($code == 1) :?>
$.addtip({message: "保存成功", autoclose: 3});
<?php endif;?>

$("a[name='btn-submit-a']").click(function () {
  var content = "<H3>确定保存发布设置？保存成功将立刻生效。</H3>";
  $.showWindow({id:"windowBox", title:"编辑<?php echo $this->title?>",content:content,
    button:[{idname: "btn-Confirm", title:"确定",
      callback:function(){
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
