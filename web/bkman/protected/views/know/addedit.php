<div id="content">
    <div class="itemtitle">
   	<h3><span name='page_title'><?php echo $this->title?></span> <a href="<?php echo $this->getDeUrl('know/index', array('id' => $this->permissionId, 'page' => $curPage))?>" style="color:#0066ff;font-size:12px;font-weight:normal">返回&gt;&gt;</a></h3>
    </div>     
    <div class="itemlist">
        <div class="search submit">
          <table>
            <tbody>
               <tr>
                <td>
                 <span name='page_title'><?php echo $this->title?></span>
                 <a class="confirm_btn" name="btn-submit-a"><span>&nbsp;保存&nbsp;</span></a>
                 <?php if ($code == 1 || $knowgame) :?>
                 <a class="confirm_btn" href="<?php echo $this->getDeUrl('know/addedit', array('id' => $this->permissionId));?>"><span>&nbsp;继续添加&nbsp;</span></a>
                 <?php endif;?>
                 </td>
               </tr>
            </tbody>
          </table>
        </div>
    </div>
    
    <div class="itemlist">
    <?php echo CHtml::beginForm($this->getDeUrl('know/addedit', array('id' => $this->permissionId)), 'post', array('id' => 'submitForm', 'name' => 'submitForm', 'enctype' => 'multipart/form-data'))?>
    <table>
    	<tbody id="contentTbody">
          <tr>
            <th width="20%" style="text-align:center">分组</th>
            <td>
                <select name="level" id="level">
                  <option>请选择分组</option>
                  <?php foreach ($groups as $item) : ?>
                  <?php if ($item['level'] <= 6) continue;?>
                  <option value="<?php echo $item['level']; ?>" <?php if ($item['level'] == $knowgame['level']) echo 'selected="selected"';?>><?php echo $item['name']; ?></option>
                  <?php endforeach; ?>
                </select>
                <span class="txt_tip"><i></i>必填项</span>
                 <a class="confirm_btn" href="<?php echo $this->getDeUrl('domain/grouplist', array('id' => $this->permissionId));?>"><span>&nbsp;分组管理&nbsp;</span></a>
            </td>
            <td></td>
          </tr>
    	  	
          <tr>
        	  <th width="20%" style="text-align:center">游戏标题</th>
        	  <td>
        	  	<input type="text" class="txt" style="width:200px;" id="title" name="title" value="<?php echo $knowgame['title']?>">
        	  	<span class="txt_tip"><i></i>必填项</span>
        	  </td>
        	  <td></td>
    	  	</tr>

          <tr>
            <th width="20%" style="text-align:center">背景图URL</th>
            <td>
              <input type="text" class="txt" style="width:200px;" id="background_img" name="background_img" value="<?php echo $knowgame['background_img']?>">
              <span class="txt_tip"><i></i>必填项,尺寸750*1206</span>
            </td>
            <td></td>
          </tr>

          <tr>
            <th width="20%" style="text-align:center">标题图URL</th>
            <td>
                <input type="text" class="txt" style="width:200px;" id="center_img" name="center_img" value="<?php echo $knowgame['center_img']?>"</input>
                <span class="txt_tip"><i></i>必填项,尺寸750*450</span>
            </td>
            <td></td>
          </tr>

          <tr>
            <th width="20%" style="text-align:center">开始出题按钮图URL</th>
            <td>
                <input type="text" class="txt" style="width:200px;" id="ct_button" name="ct_button" value="<?php echo $knowgame['ct_button']?>"</input>
                <span class="txt_tip"><i></i>必填项,尺寸600*100</span>
            </td>
            <td></td>
          </tr>
          
          <tr>
            <th width="20%" style="text-align:center">开始答题按钮图URL</th>
            <td>
                <input type="text" class="txt" style="width:200px;" id="dt_button" name="dt_button" value="<?php echo $knowgame['dt_button']?>"</input>
                <span class="txt_tip"><i></i>必填项,尺寸600*100</span>
            </td>
            <td></td>
          </tr>
          
          <tr>
            <th width="20%" style="text-align:center">分享页中部图片URL</th>
            <td>
                <input type="text" class="txt" style="width:200px;" id="share_center" name="share_center" value="<?php echo $knowgame['share_center']?>"</input>
                <span class="txt_tip"><i></i>必填项,尺寸600*430</span>
            </td>
            <td></td>
          </tr>

           <tr>
            <th width="20%" style="text-align:center">分享页按钮图片URL</th>
            <td>
                <input type="text" class="txt" style="width:200px;" id="share_button" name="share_button" value="<?php echo $knowgame['share_button']?>"</input>
                <span class="txt_tip"><i></i>必填项,尺寸640*100</span>
            </td>
            <td></td>
          </tr>
          
          <tr>
            <th width="20%" style="text-align:center">300*300分享图片URL</th>
            <td>
                <input type="text" class="txt" style="width:200px;" id="share_logo" name="share_logo" value="<?php echo $knowgame['share_logo']?>"</input>
                <span class="txt_tip"><i></i>必填项</span>
            </td>
            <td></td>
          </tr>
          
          <tr>
            <th width="20%" style="text-align:center">按钮颜色</th>
            <td>
                <input type="text" class="txt" style="width:200px;" id="color" name="color" value="<?php echo $knowgame['color']?>"</input>
                <span class="txt_tip"><i></i>必填项,请勿填白色</span>
            </td>
            <td></td>
          </tr>

          <tr>
            <th width="20%" style="text-align:center">题目</th>
            <td style="width:420px;">
              <textarea type="text" class="txt" style="width:420px;height:200px" id="question" name="question"><?php echo $question?></textarea><br>
            </td>
            <td>
              <p style="color:red;">注意:题目之间隔一行</p><br>
              <p>图片地址URL 570*270</p>
              <p>1. 曾经有人评价过你花心吗？</p>
              <p>有过</p>
              <p>没有</p>
              </br>
              <p>图片地址URL 570*270</p>
              <p>2. 在你印象中，TA并不是个自信的人？</p>
              <p>是的</p>
              <p>不是</p>
              </br>
              <p>图片地址URL 570*270</p>
              <p>3. TA谈过恋爱的次数并不多吗？</p>
              <p>是的</p>
              <p>不是</p>
              </br>
              </td>
          </tr>

          <tr>
            <th width="20%" style="text-align:center">答案匹配文案</th>
            <td style="width:420px;">
              <textarea type="text" class="txt" style="width:420px;height:210px" id="answer" name="answer"><?php echo $answer?></textarea><br>
            </td>
            <td>
              <p style="color:red;">注意:从小到大11个</p><br>
              <p>结果1</p>
              <p>结果2</p>
              <p>结果3</p>
              <p>结果4</p>
              <p>结果5</p>
              <p>结果6</p>
              <p>结果7</p>
              <p>结果8</p>
              <p>结果9</p>
              <p>结果10</p>
              <p>结果11</p>
              </td>
          </tr>

          <tr>
            <th width="20%" style="text-align:center">分享标题</th>
            <td style="width:420px;">
              <textarea type="text" class="txt" style="width:420px;height:200px" id="share_title" name="share_title"><?php echo $share_title?></textarea><br>
            </td>
            <td>
              <p style="color:red;">注意:昵称用**，可以有多个</p><br>
              <p>标题1</p>
              <p>标题2</p>
              <p>标题3</p>
              </td>
          </tr>
          
          <tr>
            <th width="20%" style="text-align:center"> 带套JSSDK公众号</th>
            <td>
              <?php foreach ($jssdk_mpids as $item) : ?>
                <input name="jssdk_mpids[]" type="checkbox" value="<?php echo $item['mp_id']?>" <?php if($jscheckwechat) {if (in_array($item['mp_id'], $jscheckwechat)) echo 'checked'; }?>/> <?php echo $item['mp_name']?>  
              <?php endforeach; ?>
            </td>
          </tr>

          <tr>
            <th width="20%" style="text-align:center">授权支付公众号</th>
            <td>
            <select name="pay_mpid" id="pay_mpid">
              <option value="0" <?php if ($item['mp_id'] == $knowgame['pay_mpid']) echo 'selected="selected"';?>>无</option>
              <?php foreach ($pay_mpid as $item) : ?>
              <option value="<?php echo $item['mp_id']; ?>" <?php if ($item['mp_id'] == $knowgame['pay_mpid']) echo 'selected="selected"';?>><?php echo $item['mp_name']; ?></option>
              <?php endforeach; ?>
            </select>
            <span class="txt_tip"><i></i>必填项</span>
            </td>
          </tr>

          <tr>
            <th width="20%" style="text-align:center">是否允许qq授权,在qq上玩</th>
            <td>
              <input name="is_qq" type="checkbox" value="<?php echo $knowgame['is_qq']?>" <?php if ($knowgame && $knowgame['is_qq'] == 1) echo 'checked' ?>/>
            </td>
          </tr>
      </tbody>
      
      <tfoot class="tfoot">
      <input type="hidden" name="action" value="submit">
      <input type="hidden" id='knowgame_id' name='knowgame_id' value="<?php echo $knowgame['id']?>">
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
                 <?php if ($code == 1 || $knowgame) :?>
                 <a class="confirm_btn" href="<?php echo $this->getDeUrl('know/addedit', array('id' => $this->permissionId));?>"><span>&nbsp;继续添加&nbsp;</span></a>
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

$("input[name='is_qq']").bind('click', function () {
     if ($(this).attr("checked")) {
       $("input[name='is_qq']").val(1);
     } else {
       $("input[name='is_qq']").val(0);
     }
})
$("a[name='btn-submit-a']").click(function () {
  
  var title = $.trim($('#title').val());
  if (!title) {
    $('#title').focus()
    $.addtip({type: "error", message: "请输入游戏标题！", autoclose: 3});
    return false;
  }

  var background_img = $.trim($('#background_img').val());
  if (!background_img) {
    $('#background_img').focus()
    $.addtip({type: "error", message: "请输入背景图！", autoclose: 3});
    return false;
  }
  
  var center_img = $.trim($('#center_img').val());
  if (!center_img) {
    $('#center_img').focus()
    $.addtip({type: "error", message: "请输入中部图片地址！", autoclose: 3});
    return false;
  }

  var ct_button = $.trim($('#ct_button').val());
  if (!ct_button) {
    $('#ct_button').focus()
    $.addtip({type: "error", message: "请输入出题按钮！", autoclose: 3});
    return false;
  }

  var dt_button = $.trim($('#dt_button').val());
  if (!dt_button) {
    $('#dt_button').focus()
    $.addtip({type: "error", message: "请输入答题按钮", autoclose: 3});
    return false;
  }

  var share_center = $.trim($('#share_center').val());
  if (!share_center) {
    $('#share_center').focus()
    $.addtip({type: "error", message: "请输入分享页中部", autoclose: 3});
    return false;
  }
  
  var share_button = $.trim($('#share_button').val());
  if (!share_button) {
    $('#share_button').focus()
    $.addtip({type: "error", message: "请输入分享页按钮", autoclose: 3});
    return false;
  }
  
  var share_logo = $.trim($('#share_logo').val());
  if (!share_logo) {
    $('#share_logo').focus()
    $.addtip({type: "error", message: "请输入分享页中部", autoclose: 3});
    return false;
  }

  var question = $.trim($('#question').val());
  if (!question) {
    $('#question').focus()
    $.addtip({type: "error", message: "请输入游戏题目！", autoclose: 3});
    return false;
  }

  var answer = $.trim($('#answer').val());
  if (!answer) {
    $('#answer').focus()
    $.addtip({type: "error", message: "请输入游戏答案！", autoclose: 3});
    return false;
  }

  var share_title = $.trim($('#share_title').val());
  if (!share_title) {
    $('#share_title').focus()
    $.addtip({type: "error", message: "请输入分享标题！", autoclose: 3});
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
