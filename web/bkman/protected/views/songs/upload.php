<style type="text/css">
.file:nth-child(1) { margin-top: 10px; }
.file:nth-child(odd) { background-color: #ddd; }
.file { width: 100%; border-radius: 3px; font-size: 15px; padding: 3px 0px 0px 9px; }
.file-info { display: inline-block; width: 30%; }
.file-name {  display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.file-size {  display: block; color: #a94442; }
.progress-container { display: inline-block; width: 50%; margin: 6px 0px 0px 0px; height: 27px; vertical-align: top; }
.progress { width: 0%; margin-top: 10px; height: 30%; background-color: #5cb85c; border-radius: 9px; }
.upload-info { display: inline-block; height: 27px; width: 18%; margin-left: 9px; vertical-align: top; padding-top: 9px; }
.upload-error { display: none; width: 100%; color: red; }
</style>
<div id="content">
  <div class="itemtitle">
    <h3><?php echo $this->title?></h3>
  </div>
  <div class="itemlist">
    <span>上传马甲用户</span>
    <select id="list-vestuser-select">
      <option value="">请选择</option>
      <?php if ($vestUserList) :?>
      <?php foreach ($vestUserList as $vestUser):?>
      <option value="<?php echo $vestUser['uid']?>"><?php echo $vestUser['nickname']?></option>
      <?php endforeach;?>
      <?php endif;?>
    </select>
    <a class="confirm_btn" id="btn-choose-a"><span>&nbsp;选择歌曲&nbsp;</span></a>
    <a class="confirm_btn" id="btn-confirm-a" style="display:none;"><span>&nbsp;开始上传&nbsp;</span></a>
    <input type="file" id="songs" name="songs" multiple style="visibility: hidden;"/>
    <br/> <br/>
    <span class="txt_tip"><i></i>上传马甲用户默认小主</span>
    <br/>
    <span class="txt_tip"><i></i>每次文件不得超过20个,单个文件不要超出2MB</span>
    <div id="file-list"> </div>
  </div>
</div>
<script type="text/javascript">
if (window.File && window.FileReader && window.FileList && window.Blob && window.btoa) {

  $('#btn-choose-a').on('click', function () {
    $('#songs').click();
  });
  var songList = [];
  $('#songs').on('change', function () {
    var _this = this;
    var maxSize = 2097152;
    var maxLen = 20;
    songList = [];
    if (_this.files.length) {
      for (var i=0; i<_this.files.length; i++) {
        var file = _this.files[i];
        if (file.size > maxSize) {
          $.addtip({type: "error", message: file.name + ": 文件太大，超出2MB了！", autoclose: 3});
          continue;
        } else if (songList.length >= maxLen) {
          break;
        }
        songList.push(file);
      }
      if (songList.length) {
        var html = '';
        for (var i=0; i<songList.length; i++) {
          html += '<div class="file"><div class="file-info"><span class="file-name" title="'+ songList[i].name +'">'+ songList[i].name +'</span>';
          html += '<span class="file-size">'+ getFileSize(songList[i].size) +'</span></div><div class="progress-container"><div class="progress"></div></div>';
          html += '<div class="upload-info"><span class="upload-error">该文件上传失败!</span></div> </div>';
        }
        $('#file-list').html(html);
        $('#btn-confirm-a').off().show().on('click', uploadFiles);
      }
    } else {
      $('#btn-confirm-a').off().hide();
      $('#file-list').html('<span style="color:red; font-size:20px;">请至少选择一个文件</span>');
    }
  });

  function uploadFiles () {
    if (!songList.length) return false;
    for (var i=0; i<songList.length; i++) {
      (function (i) {
        var reader = new FileReader();
        reader.onload = function (e) {
          var fileStr = btoa(e.target.result);
          var xhr = new XMLHttpRequest();
          xhr.open("POST", "<?php echo $this->getDeUrl('songs/upload/file', array('id' => $this->permissionId)); ?>");
          xhr.overrideMimeType('text/plain; charset=utf-8');
          xhr.upload.addEventListener("progress", function(e) {
            if (e.lengthComputable) {
              var progress = $($('.progress')[i]);
              var percentage = Math.round((e.loaded * 100) / e.total);
              progress.animate({width: percentage.toString() + '%'});
            }
          }, false);
          xhr.onreadystatechange = function () {
            var hint = $($('.upload-error')[i]);
            if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
              var res = JSON.parse(xhr.responseText);
              if (res.code === 0) {
          	    songList.splice(i, 1);
                hint.css('color', '#3071a9');
                hint.text('文件上传成功!').show();
                setTimeout(function () {
              	  $($('.file')[i]).hide(500);
                }, 1000)
                return true;
              } else {
                hint.text(res.msg).show();
                setTimeout(function () {
                  hint.text('请点击重新上传');
                  $($('.progress')[i]).animate({width: '0%'});
                  $('#btn-confirm-a span').html('&nbsp;重新上传&nbsp;');
                }, 1500);
              }
            }
          }
          var data = new FormData();
          data.append('vest_uid', $('#list-vestuser-select').val());
          data.append('_sh_token_', _sh_token_);
          data.append(songList[i].name, fileStr);
          xhr.send(data);
          reader = null;
        }
        reader.readAsBinaryString(songList[i]);
      })(i);
    }
  }

  function getFileSize (size) {
    var humanSize = (size/1024).toFixed(2).toString();
    return (humanSize.length > 6) ? (size/1048576).toFixed(2).toString() +'MB' : humanSize +'KB';
  }
} else {
  $('#file-list').html('<span style="color:red; font-size:20px;">抱歉，你的浏览器不支持批量上传，建议使用最新的Chrome浏览器</span>');
}
</script>
