<style>
#row-images-div {display: block; float: left; width: 100%;}
.row-upload {padding: 2px; float: left}
.row-upload img {padding: 2px;height: 150px;}
a:hover { cursor: pointer; }
</style>
<div id="content">
  <div class="itemtitle">
    <h3>
      <span id='page_title'><?php echo $this->title?></span>
      <a href="<?php echo $ref; ?>" style="color:#0066ff;font-size:12px;font-weight:normal">返回</a>
    </h3>
  </div>

  <div class="itemlist">
    <div class="search submit">
      <table>
        <tbody>
          <tr>
            <td>
              <span name='ops_title'><?php echo $this->title?></span>
              <a class="confirm_btn" name="btn-submit-a"><span>&nbsp;保存&nbsp;</span></a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="itemlist">
  <?php echo CHtml::beginForm($this->getDeUrl('gameset/pseudouser/addedit', array('id' => $this->permissionId, 'uid' => $uid, 'ref' => urlencode($ref))), 'post', array('id' => 'submitForm', 'name' => 'submitForm', 'enctype' => 'multipart/form-data'))?>
  <table>
    <tbody id="contentTbody">
      <tr>
        <th width="10%" style="text-align:center"><label for="nickname">用户昵称</label></th>
        <td>
          <input type="text" class="txt" style="width:270px;" id="nickname" name="nickname" value="<?php echo $userInfo['nickname']?>">
          <span class="txt_tip"><i></i>必填项</span>
        </td>
      </tr>

      <tr>
        <th width="10%" style="text-align:center"><label for="is-using">是否使用</label></th>
        <td>
          <select name="is_using" id="is-using">
            <option value="0" <?php if ($userInfo['is_using'] == 0) echo "selected"; ?>>不使用</option>
            <option value="1" <?php if ($userInfo['is_using'] == 1) echo "selected"; ?>>已使用</option>
          </select>
          <span class="txt_tip"><i></i>必选项</span>
        </td>
      </tr>

      <tr>
        <th width="10%" style="text-align:center"><label for="gender">性别</label></th>
        <td>
          <select name="gender" id="gender">
            <option value="0" <?php if ($userInfo['gender'] == 0) echo "selected"; ?>>保密</option>
            <option value="1" <?php if ($userInfo['gender'] == 1) echo "selected"; ?>>男</option>
            <option value="2" <?php if ($userInfo['gender'] == 2) echo "selected"; ?>>女</option>
          </select>
          <span class="txt_tip"><i></i>必选项</span>
        </td>
      </tr>

      <tr>
        <th width="10%" style="text-align:center"><label for="birthday">出生日期</label></th>
        <td>
          <input type="text" class="txt" style="width:180px;" id="birthday" name="birthday" value="<?php echo $userInfo['birthday']?>">
          <span class="txt_tip"><i></i>(<b style="color:red;">年龄:<?php echo $userInfo['age']; ?></b>)格式:年月日,如19700101 必填项</span>
        </td>
      </tr>

      <tr>
        <th width="10%" style="text-align:center"><label for="avatar-img">信息流图片</label></th>
        <td>
          <div id="row-images-div">
          <?php if ($userInfo['avatar']) : ?>
            <div class="row-upload" id="row-upload-div">
              <label for="avatar-img">
                <img src="<?php echo $userInfo['avatar']; ?>" />
              </label>
              <br/>
              <label for="avatar-img">上传头像</label> <input type="file" id="avatar-img" name="avatar_img" />
            </div>
          <?php else :?>
            <div class="row-upload" id="row-upload-div">
              <label for="avatar-img">
                <img src="http://f.shiyi11.com/ui/img/dot1.gif" />
              </label>
              <br/>
              <label for="avatar-img">上传图片</label> <input type="file" id="avatar-img" name="avatar_img" />
            </div>
          <?php endif;?>
          </div>
          <br/>
        </td>
      </tr>
    </tbody>

    <tfoot class="tfoot">
      <input type="hidden" name="avatar" value="<?php echo $userInfo['avatar']; ?>">
      <input type="hidden" id="is-upload" name="is_upload" value="0">
      <input type="hidden" name="action" value="post">
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
              <span name='ops_title'><?php echo $this->title?></span>
              <a class="confirm_btn" name="btn-submit-a"><span>&nbsp;保存&nbsp;</span></a>
            <?php if ($code == 1) :?>
              <a class="confirm_btn" href="<?php echo $this->getDeUrl('gameset/pseudouser/addedit', array('id' => $this->permissionId, 'ref' => urlencode($ref)));?>"><span>&nbsp;继续添加&nbsp;</span></a>
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
<?php if ($code == 1) :?>
$.addtip({message: "保存成功", autoclose: 3});
<?php elseif ($code == 2) :?>
$.addtip({type:"error", message: "操作失败", autoclose: 3});
<?php endif;?>

var myURL = window.URL || window.webkitURL;
$('#avatar-img').on('change', function () {
  var file = (File && FileList && this.files.length) ? this.files[0] : null;
  if (File && FileList) {
    $('#is-upload').val(this.files.length ? '1' : '0');
  } else {
    $('#is-upload').val('1');
  }
  if (myURL && file) {
    $('#row-upload-div img').attr('src', myURL.createObjectURL(file));
  }
});

//提交保存
$("a[name='btn-submit-a']").click(function () {
  var nickname = $.trim($('#nickname').val());
  if (!nickname) {
    $('#nickname').focus();
    $.addtip({type: "error", message: "请填写用户昵称！", autoclose: 3});
    return false;
  }
  var gender = parseInt($.trim($('#gender').val()));
  if (!gender) {
    $('#gender').focus();
    $.addtip({type: "error", message: "请选择用户性别！", autoclose: 3});
    return false;
  }
  var birthday = $.trim($('#birthday').val());
  if (!birthday) {
    $('#birthday').focus();
    $.addtip({type: "error", message: "请填写用户性别！", autoclose: 3});
    return false;
  }

  var title = $('#page_title').text();
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
