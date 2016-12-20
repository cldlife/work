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
    	<a class="confirm_btn" id="btn-add" href="<?php echo $this->getDeUrl('domain/groupaddedit', array('id' => $this->permissionId, 'page' => $curPage))?>"><span>&nbsp;+ 添加分组&nbsp;</span></a>
    	<?php echo $pager?>
    </div>
    
    <div class="itemlist">
    <table>
      <thead>
      	<tr>
          <th width="5%">编号</th>
          <th width="10%">分组名称</th>
          <th width="50%">分组备注</th>
          <th width="10%">创建时间</th>
          <th width="25%">操作</th>
		    </tr>
      </thead>
      <tbody id="contentTbody">
      	<?php 
      	if ($groupList) :
      	  foreach ($groupList as $group) :?>
    	  	<tr id="row-<?php echo $group['level']?>">
        	  <td><?php echo $group['level'];?></td>
              <td><?php echo $group['name'];?></td>
              <td><?php echo $group['remark'];?></td>
        	  <td><?php echo Utils::getDiffTime($group['created_time'])?></td>
        	  <td id="td-operate-<?php echo $group['level']?>">
        	  <a name="btn-edit" data-id="<?php echo $group['level']?>" href="<?php echo $this->getDeUrl('domain/groupaddedit', array('id' => $this->permissionId, 'group_id' => $group['level'], 'page' => $curPage))?>">编辑</a>	  
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
    	<a class="confirm_btn" id="btn-add" href="<?php echo $this->getDeUrl('domain/groupaddedit', array('id' => $this->permissionId, 'page' => $curPage))?>"><span>&nbsp;+ 添加分组&nbsp;</span></a>
    	<?php echo $pager?>
    </div>
</div>
<br>