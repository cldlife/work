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
                  <a id="btn-refresh-a" href="javascript:location.reload(true);">
                  <span>刷新</span></a>
                </td>
               </tr>
            </tbody>
          </table>
        </div>
    </div>
    
    <div class="itemlist">
    	<a class="confirm_btn" id="btn-add" href="<?php echo $this->getDeUrl('article/addedit', array('id' => $this->permissionId, 'page' => $curPage))?>"><span>&nbsp;+ 添加文章&nbsp;</span></a>
    	<?php echo $pager?>
    </div>
    
    <div class="itemlist">
    <table>
      <thead>
      	<tr>
          <th width="10%">编号</th>
          <th width="25%">标题</th>
          <th width="10%">发布时间</th>
          <th width="20%">操作</th>
		    </tr>
      </thead>
      <tbody id="contentTbody">
      	<?php 
      	if ($articleList) :
      	  foreach ($articleList as $article) :?>
    	  	<tr id="row-<?php echo $article['id']?>">
        	  <td><?php echo $article['id'];?></td>
            <td>
              <div style="display: inline-block;font-size: 13px;width: 235px;">
                <?php echo $article['title'];?>
              </div>
            </td>
        	  <td><?php echo Utils::getDiffTime($article['created_time'])?></td>
        	  <td id="td-operate-<?php echo $article['id']?>">
        	  <a name="btn-edit" data-id="<?php echo $article['id']?>" href="<?php echo $this->getDeUrl('article/addedit', array('id' => $this->permissionId, 'article_id' => $article['id'], 'page' => $curPage))?>">编辑</a>
        	  <a name="btn-del" data-id="<?php echo $article['id']?>" href="javascript:;">删除</a>
            <a href="http://zt.shihuo.test/topic/view<?php echo $article['id']?>.html" target="_blank">预览链接</a>
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
    	<a class="confirm_btn" id="btn-add" href="<?php echo $this->getDeUrl('article/addedit', array('id' => $this->permissionId, 'page' => $curPage))?>"><span>&nbsp;+ 添加文章&nbsp;</span></a>
    	<?php echo $pager?>
    </div>
</div>
<br>
<script type="text/javascript">
var _sh_token_ = '<?php echo Yii::app()->getRequest()->getCsrfToken()?>';

//查询
// $("#btn-search-a").click(function () {
//   var category = $("#category").find("option:selected").val();
//   var keyword = $("#keyword").val();
//   window.location.href="<?php echo $this->getDeUrl('article/index', array('id' => $this->permissionId))?>" + '&category=' + category;
// });

$("a[name='btn-del']").bind("click", function () {
  var _this = this;
  var title = '删除文章';
  var content = "<H3>确定删除此文章？删除成功后将立刻生效。</H3>";
  $.showWindow({id:"windowBox", title:title,content:content,
    button:[{idname: "btn-Confirm", title:"确定",
      callback:function(){
        $('#btn-Confirm span').text('处理中，请稍后...')
        $('#cancel_id').unbind();
        $('#btn-Confirm').unbind();

        var articleId = $(_this).attr("data-id");
        $.DeAjax(_this.id, {
          type: 'POST',
          dataType: 'json',
          url: '<?php echo $this->getDeUrl('article/del', array('id' => $this->permissionId))?>',
          data: {"article_id": articleId, '_sh_token_':_sh_token_},
          success: function(res){
            //console.log(res);
            if (res.code == 1) {
              $("#row-" + articleId).remove();
              $.addtip({message: "删除成功", autoclose: 3});
            } else {
      	      $.addtip({type: "error", message: "删除失败，请检查！", autoclose: 3});
            }
          },
          error: function() {
            $.addtip({type: "error", message: "加载失败，请检查！", autoclose: 3});
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
