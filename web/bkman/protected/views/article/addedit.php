<div id="content">
    <div class="itemtitle">
   	<h3><span name='page_title'><?php echo $this->title?></span> <a href="<?php echo $this->getDeUrl('article/index', array('id' => $this->permissionId, 'page' => $curPage))?>" style="color:#0066ff;font-size:12px;font-weight:normal">返回&gt;&gt;</a></h3>
    </div>
    <div class="itemlist">
        <div class="search submit">
          <table>
            <tbody>
               <tr>
                <td>
                 <span name='page_title'><?php echo $this->title?></span>
                 <a class="confirm_btn" name="btn-submit-a"><span>&nbsp;保存&nbsp;</span></a>
                 <?php if ($code == 1 || $article['id']) :?>
                 <a class="confirm_btn" href="<?php echo $this->getDeUrl('article/addedit', array('id' => $this->permissionId, 'category' => $category));?>"><span>&nbsp;继续添加&nbsp;</span></a>
                 <?php endif;?>
                 </td>
               </tr>
            </tbody>
          </table>
        </div>
    </div>

    <div class="itemlist">
    <?php echo CHtml::beginForm($this->getDeUrl('article/addedit', array('id' => $this->permissionId)), 'post', array('id' => 'submitForm', 'name' => 'submitForm', 'enctype' => 'multipart/form-data'))?>
    <table>
    	<tbody id="contentTbody">


          <tr>
            <th width="10%" style="text-align:center">模版</th>
            <td>
                 <select id="template_id" name="template_id">
                    <option value="0">请选择</option>
                    <?php foreach ($template as $id => $name) :?>
                  <option value="<?php echo $id?>" <?php if ($id == $article['template_id']) echo " selected";?>><?php echo $name?></option>
                  <?php endforeach;?>
                 </select>
            </td>
          </tr>

    	  	<tr>
        	  <th width="10%" style="text-align:center">标题</th>
        	  <td>
        	  	<input type="text" class="txt" style="width:160px;" id="title" name="title" value="<?php echo $article['title']?>">
        	  	<span class="txt_tip"><i></i>必填项</span>
        	  </td>
    	  	</tr>

          <tr>
            <th width="10%" style="text-align:center">摘要描述</th>
            <td>
                <textarea type="text" class="txt" style="width:420px;height:100px" id="description" name="description"><?php echo $article['description']?></textarea>
            </td>
          </tr>

    	  	<tr>
        	  <th width="10%" style="text-align:center">内容</th>
        	  <td>
				 <textarea type="text" class="txt" style="width:420px;height:100px" id="text_content" name="content"><?php echo $article['description']?></textarea>
        	  	<br>
        	    <span class="txt_tip"><i></i>必填项</span>
        	  </td>
    	  	</tr>

    	  	<tr>
            <th width="10%" style="text-align:center">是否有购买链接</th>
            <td>
              <input name="is_shop" type="radio" value="1" <?php if ($article && $article['is_shop'] == 1) echo 'checked' ?>/> 否
              <input name="is_shop" type="radio" value="0" <?php if ($article && $article['is_shop'] == 0) echo 'checked' ?>/> 是
            </td>
          </tr>

          <tr>
            <th width="10%" style="text-align:center">是否有评论</th>
            <td>
              <input name="is_comment" type="radio" value="1" <?php if ($article && $article['is_comment'] == 1) echo 'checked' ?>/> 否
              <input name="is_comment" type="radio" value="0" <?php if ($article && $article['is_comment'] == 0) echo 'checked' ?>/> 是
            </td>
          </tr>
      </tbody>

      <tfoot class="tfoot">
      <input type="hidden" name="action" value="submit">
      <input type="hidden" id='article_id' name='article_id' value="<?php echo $article['id']?>">
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
                 <?php if ($code == 1 || $article) :?>
                 <a class="confirm_btn" href="<?php echo $this->getDeUrl('article/addedit', array('id' => $this->permissionId, 'category' => $category));?>"><span>&nbsp;继续添加&nbsp;</span></a>
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

<?php if ($code == 1) :?>
$.addtip({message: "保存成功", autoclose: 3});
<?php endif;?>

$("a[name='btn-submit-a']").click(function () {

  var template_id = parseInt($('#template_id').find("option:selected").val());
  if (!template_id) {
    $('#template_id').focus()
    $.addtip({type: "error", message: "请选择模版！", autoclose: 3});
    return false;
  }

  var title = $.trim($('#title').val());
  if (!title) {
    $('#title').focus()
    $.addtip({type: "error", message: "请输入文章标题！", autoclose: 3});
    return false;
  }

  var description = $.trim($('#description').val());
  if (!description) {
    $('#description').focus()
    $.addtip({type: "error", message: "请输入文章描述！", autoclose: 3});
    return false;
  }

  var content = $.trim($('#text_content').val());
  if (!content) {
    $('#text_content').focus()
    $.addtip({type: "error", message: "请输入文章内容！", autoclose: 3});
    return false;
  }

  var title = $($('span[name="page_title"]')[0]).text();
  var content = "<H3>确定保存编辑？保存成功将立刻生效。</H3>";
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
