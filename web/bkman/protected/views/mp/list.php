<style>
td a {cursor: pointer; }
.cur-domain{padding: 5px;border: 1px dashed #ccc;margin-top: 10px;}
.group-remark{padding: 5px 5px 0 5px;}
.group-rule-uri{padding: 5px 5px 0 5px;}
#block-remark-group-div {display: none}
</style>
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
                <label for="domain">添加公众号</label>
                <span class="txt_tip"><i></i>温馨提示：请核对正确填写公众号信息，以免导致JSSDK不可用</span>
                <br>
                <label for="status">名称:</label>
                <input type="text" class="txt" id="mp_name" name="mp_name" style="width:100px;" value="">
                <label for="status">AppId:</label>
                <input type="text" class="txt" id="app_id" name="app_id" style="width:100px;" value="">
                <label for="status">AppSecret:</label>
                <input type="text" class="txt" id="app_secret" name="app_secret" style="width:100px;" value="">
                <label for="status">是否开启支付功能:</label>
                <input name="type" id="type" type="checkbox" value="0">
                <!-- <a class="confirm_btn" id="btn-submit-check"><span>&nbsp;检测配置&nbsp;</span></a> -->
                <a class="confirm_btn" id="btn-submit-a"><span>&nbsp;添加&nbsp;</span></a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <?php if ($pager) :?>
    <div class="itemlist" style="overflow: hidden;">
      <?php echo $pager?>
    </div>
    <?php endif;?>

    <div class="itemlist">
    <table>
      <thead>
        <tr>
          <th width="5%">编号</th>
          <th width="10%">名称</th>
          <th width="10%">AppId</th>
          <th width="20%">AppSecret</th>
          <th width="15%">JSSDK安全域名</th>
          <th width="15%">授权域名</th>
          <th width="15%">支付域名</th>
          <th width="15%">创建时间</th>
        </tr>
      </thead>
      <tbody id="contentTbody">
      <?php if ($mpList) : ?>
        <?php foreach($mpList as $mp) : ?>
        <tr>
          <td><?php echo $mp['mp_id']; ?></td>
          <td><?php echo $mp['mp_name']; ?></td>
          <td><?php echo $mp['app_id']; ?></td>
          <td><?php echo $mp['app_secret']; ?></td>
          <td>
          <div style="padding:1px"><input type="text" id="mp_<?php echo $mp['mp_id']; ?>_jsdoman_1" class="txt" style="width:200px;" value="<?php echo $mp['jsdomains'][0]['domain_address']; ?>"></div>
          <div style="padding:1px"><input type="text" id="mp_<?php echo $mp['mp_id']; ?>_jsdoman_2" class="txt" style="width:200px;" value="<?php echo $mp['jsdomains'][1]['domain_address']; ?>"></div>
          <div style="padding:1px"><input type="text" id="mp_<?php echo $mp['mp_id']; ?>_jsdoman_3" class="txt" style="width:200px;" value="<?php echo $mp['jsdomains'][2]['domain_address']; ?>"></br><a href="javascript:;" name="jssdk-btn-save-all-a" data-type="0" data-mpid="<?php echo $mp['mp_id']; ?>">全部保存</a></div>
          </td>
          <td>
          <div style="padding:1px"><input type="text" id="mp_<?php echo $mp['mp_id']; ?>_sqdoman" class="txt" style="width:200px;" value="<?php echo $mp['sqdomains'][0]['domain_address']; ?>"><a href="javascript:;" name="sq-btn-save-all-a" data-type="1" data-mpid="<?php echo $mp['mp_id']; ?>">全部保存</a></div>
          </td>
          <td>
          <?php if ($mp['type'] == 1) : ?>
          <div style="padding:1px"><input type="text" id="mp_<?php echo $mp['mp_id']; ?>_paydoman_1" class="txt" style="width:200px;" value="<?php echo $mp['paydomains'][0]['domain_address']; ?>"></div>
          <div style="padding:1px"><input type="text" id="mp_<?php echo $mp['mp_id']; ?>_paydoman_2" class="txt" style="width:200px;" value="<?php echo $mp['paydomains'][1]['domain_address']; ?>"></div>
          <div style="padding:1px"><input type="text" id="mp_<?php echo $mp['mp_id']; ?>_paydoman_3" class="txt" style="width:200px;" value="<?php echo $mp['paydomains'][2]['domain_address']; ?>"> <a href="javascript:;" name="pay-btn-save-all-a" data-type="2" data-mpid="<?php echo $mp['mp_id']; ?>">全部保存</a></div>
          <?php else :?>
          <div style="padding:1px;width:200px;">未启用</div>
          <?php endif ;?>  
          </td>
          <td><?php echo $mp['cdate']; ?></td>

        </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="8">没有数据了哦~亲</td>
        </tr>
      <?php endif; ?>
      </tbody>
      <tfoot class="tfoot"></tfoot>
    </table>
    </div>

    <div class="itemlist" style="overflow: hidden;">
      <?php echo $pager?>
    </div>
</div>
<br>
<script type="text/javascript">
var _sh_token_ = '<?php echo Yii::app()->getRequest()->getCsrfToken()?>';

//打勾
$("#type").bind('click', function () {
     if ($(this).attr("checked")) {
       $("input[name='type']").val(1);
     } else {
       $("input[name='type']").val(0);
     }
})

//添加公众号
$("#btn-submit-a").click(function () {
  var mpName = $.trim($("#mp_name").val());
  var appId = $.trim($("#app_id").val());
  var appSecret = $.trim($("#app_secret").val());
  var type = $.trim($("#type").val());
  if (!mpName) {
    $("#mp_name").focus();
    $.addtip({type: "error", message: "请填写公众号名称！", autoclose: 3});
    return false;
  }
  if (!appId) {
    $("#app_id").focus();
    $.addtip({type: "error", message: "请填写公众号开发者AppId！", autoclose: 3});
    return false;
  }
  if (!appSecret) {
    $("#app_secret").focus();
    $.addtip({type: "error", message: "请填写公众号开发者AppSecret！", autoclose: 3});
    return false;
  }

  $.DeAjax(this, {
    type:'POST',
    dataType:'json',
    url:'<?php echo $this->getDeUrl('mp/addedit', array('id' => $this->permissionId))?>',
    data: {'mp_name': mpName, "app_id": appId, 'app_secret': appSecret, 'type': type, '_sh_token_': _sh_token_},
    success:function (res) {
      if (res.code) {
        $.addtip({message: "保存成功", autoclose: 3});
        setTimeout(function () {
          window.location.href="<?php echo $this->getDeUrl('mp/index', array('id' => $this->permissionId))?>";
        }, 1000);
      } else {
        $.addtip({type: "error", message: "保存失败，请重试", autoclose: 3});
      }
    },
    error:function(){
      $.addtip({type: "error", message: "数据异常，请重试！", autoclose: 3});
    }
  });
});

//保存JSSDK安全域名
$("#contentTbody a[name='jssdk-btn-save-all-a']").click(function () {
  var _this = this;
  var title = '全部保存';
  var content = "<H3>确定全部保存？保存成功后将同步到域名管理“微信jssdk域名”分组。</H3>";
  $.showWindow({id:"windowBox", title:title,content:content,
    button:[{idname: "btn-Confirm", title:"确定",
      callback:function(){
        $('#btn-Confirm span').text('处理中，请稍后...')
        $('#cancel_id').unbind();
        $('#btn-Confirm').unbind();

        var mpId = parseInt($(_this).attr('data-mpid'));
        var type = parseInt($(_this).attr('data-type'));
        var mpJsDomain1 = $("#mp_"+ mpId +"_jsdoman_1").val();
        var mpJsDomain2 = $("#mp_"+ mpId +"_jsdoman_2").val();
        var mpJsDomain3 = $("#mp_"+ mpId +"_jsdoman_3").val();
        $.DeAjax(_this.id, {
          type: 'POST',
          dataType: 'json',
          url: '<?php echo $this->getDeUrl('mp/savejsdomains', array('id' => $this->permissionId))?>',
          data: {"mp_id": mpId, "type" : type, 'jsdoman1': mpJsDomain1, 'jsdoman2': mpJsDomain2, 'jsdoman3': mpJsDomain3, '_sh_token_':_sh_token_},
          success: function(res){
            console.log(res);
            if (res.code) {
              $.addtip({message: "保存成功", autoclose: 3});
              setTimeout(function () {
                window.location.href="<?php echo $this->getDeUrl('mp/index', array('id' => $this->permissionId))?>";
              }, 1000);
            } else {
              $.addtip({type: "error", message: "保存失败，请重试", autoclose: 3});
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

//保存授权域名
$("#contentTbody a[name='sq-btn-save-all-a']").click(function () {
  var _this = this;
  var title = '全部保存';
  var content = "<H3>确定全部保存？</H3>";
  $.showWindow({id:"windowBox", title:title,content:content,
    button:[{idname: "btn-Confirm", title:"确定",
      callback:function(){
        $('#btn-Confirm span').text('处理中，请稍后...')
        $('#cancel_id').unbind();
        $('#btn-Confirm').unbind();

        var mpId = parseInt($(_this).attr('data-mpid'));
        var type = parseInt($(_this).attr('data-type'));
        var mpSqDomain = $("#mp_"+ mpId +"_sqdoman").val();
        $.DeAjax(_this.id, {
          type: 'POST',
          dataType: 'json',
          url: '<?php echo $this->getDeUrl('mp/savejsdomains', array('id' => $this->permissionId))?>',
          data: {"mp_id": mpId, "type" : type, 'mpsqdomain': mpSqDomain, '_sh_token_':_sh_token_},
          success: function(res){
             console.log(res);
            if (res.code) {
              $.addtip({message: "保存成功", autoclose: 3});
              setTimeout(function () {
                window.location.href="<?php echo $this->getDeUrl('mp/index', array('id' => $this->permissionId))?>";
              }, 1000);
            } else {
              $.addtip({type: "error", message: "保存失败，请重试", autoclose: 3});
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

//保存安全支付目录
$("#contentTbody a[name='pay-btn-save-all-a']").click(function () {
  var _this = this;
  var title = '全部保存';
  var content = "<H3>确定全部保存？</H3>";
  $.showWindow({id:"windowBox", title:title,content:content,
    button:[{idname: "btn-Confirm", title:"确定",
      callback:function(){
        $('#btn-Confirm span').text('处理中，请稍后...')
        $('#cancel_id').unbind();
        $('#btn-Confirm').unbind();

        var mpId = parseInt($(_this).attr('data-mpid'));
        var type = parseInt($(_this).attr('data-type'));
        var mpPayDomain1 = $("#mp_"+ mpId +"_paydoman_1").val();
        var mpPayDomain2 = $("#mp_"+ mpId +"_paydoman_2").val();
        var mpPayDomain3 = $("#mp_"+ mpId +"_paydoman_3").val();
        $.DeAjax(_this.id, {
          type: 'POST',
          dataType: 'json',
          url: '<?php echo $this->getDeUrl('mp/savejsdomains', array('id' => $this->permissionId))?>',
          data: {"mp_id": mpId, "type" : type, 'paydomain1': mpPayDomain1, 'paydomain2': mpPayDomain2, 'paydomain3': mpPayDomain3, '_sh_token_':_sh_token_},
          success: function(res){
             console.log(res);
            if (res.code) {
              $.addtip({message: "保存成功", autoclose: 3});
              setTimeout(function () {
                window.location.href="<?php echo $this->getDeUrl('mp/index', array('id' => $this->permissionId))?>";
              }, 1000);
            } else {
              $.addtip({type: "error", message: "保存失败，请重试", autoclose: 3});
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