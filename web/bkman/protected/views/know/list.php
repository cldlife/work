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
    	<a class="confirm_btn" id="btn-add" href="<?php echo $this->getDeUrl('know/addedit', array('id' => $this->permissionId, 'page' => $curPage))?>"><span>&nbsp;+ 添加游戏&nbsp;</span></a>
    	<?php echo $pager?>
    </div>
    
    <div class="itemlist">
    <table>
      <thead>
      	<tr>
          <th width="5%">编号</th>
          <th width="20%">标题</th>
          <th width="10%">分组编号</th>
          <th width="10%">发布时间</th>
          <th width="10%">操作</th>
		    </tr>
      </thead>
      <tbody id="contentTbody">
      	<?php 
      	if ($knowList) :
      	  foreach ($knowList as $know) :?>
    	  	<tr id="row-<?php echo $know['id']?>">
        	  <td><?php echo $know['id'];?></td>
            <td>
              <div style="display: inline-block;font-size: 13px;width: 235px;">
                <?php echo $know['title'];?>
              </div>
            </td>
            <td><?php echo $know['level'];?></td>
        	  <td><?php echo Utils::getDiffTime($know['created_time'])?></td>
        	  <td id="td-operate-<?php echo $know['id']?>">
        	  <a name="btn-edit" data-id="<?php echo $know['id']?>" href="<?php echo $this->getDeUrl('know/addedit', array('id' => $this->permissionId, 'knowgame_id' => $know['id'], 'page' => $curPage))?>">编辑</a>	  
            <a data-src="<?php echo $know['domain']?>/wqiou/know<?php echo $know['id']?>-gt1-gp<?php echo $know['level']?>.html" class="copyurl">复制链接</a>
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
    	<a class="confirm_btn" id="btn-add" href="<?php echo $this->getDeUrl('know/addedit', array('id' => $this->permissionId, 'page' => $curPage))?>"><span>&nbsp;+ 添加游戏&nbsp;</span></a>
    	<?php echo $pager?>
    </div>
</div>
<br>
<script type="text/javascript" src="http://f.shiyi11.com/ui/js/common/clipboard/ZeroClipboard.min.js"></script>
<script type="text/javascript">
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
</script>
