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
              <a id="btn-refresh-a" href="javascript:location.reload();"><span>刷新</span></a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="itemlist">
    <a class="confirm_btn" id="btn-add" href="<?php echo $this->getDeUrl('attachments/upload', array('id' => $this->permissionId, 'page' => $curPage))?>"><span>&nbsp;+ 上传文件&nbsp;</span></a>
  </div>


  <div class="itemlist">
    <?php echo $pager?>
  </div>

  <div class="itemlist">
  <table>
    <thead>
      <tr>
        <th width="10%">文件编号</th>
        <th width="30%">文件内容</th>
        <th width="15%">文件名</th>
        <th width="5%">类型</th>
        <th width="10%">尺寸</th>
        <th width="10%">上传时间</th>
        <th width="20%">操作(复制链接)</th>
    </tr>
    </thead>
    <tbody id="contentTbody">
      <?php
      if ($list) :
        foreach ($list as $item) :?>
        <tr id="row-<?php echo $song['tm_id']?>">
          <td> <?php echo $item['aid']; ?> </td>

          <td>
          <style>
            .smallImg {}
            .smallImg:hover { width: 100px !important; }
            .smallImg:hover .bigImg { display: block !important; }
          </style>
            <div style="position: relative;">
              <a class="smallImg" style="width: 50px; height: 50px; display: inline-block;">
                <div style="position: relative; display: block; width: 50px; height: 50px; overflow: hidden;">
                  <img src="<?php echo WEB_QW_APP_FILE_DOMAIN.$item['file_uri'].$item['file_name']; ?>" style="position: absolute; width: 100%; top: 50%; left: 50%; -webkit-transform: translate(-50%, -50%); transform: translate(-50%, -50%);" data-uid="6">
                </div>
                <img class="bigImg" src="<?php echo WEB_QW_APP_FILE_DOMAIN.$item['file_uri'].$item['file_name']; ?>" style="position: absolute; left: 55px; top: 0; display: none;">
              </a>
            </div>
          </td>
          
          <td><span><?php echo $item['local_name']; ?></span><br/></td>
          <td><span><?php echo $item['type']; ?></span><br/></td>

          <td><span><?php echo $item['width']; ?>x<?php echo $item['height']; ?></span></td>

          <td><?php echo Utils::getDiffTime($item['created_time'])?></td>

          <td>
             <a data-src="<?php echo WEB_QW_APP_FILE_DOMAIN.$item['file_uri'].$item['file_name']; ?>" class="copyurl">原图</a>
             <a data-src="<?php echo WEB_QW_APP_FILE_DOMAIN.$item['file_uri'].$item['file_name'] . '/no'; ?>" class="copyurl">质量90%</a>
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
