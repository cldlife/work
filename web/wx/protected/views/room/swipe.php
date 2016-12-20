<?php if ($room && $room['host'] == $this->currentUser['uid'] && count($user_list) > 1) : ?>
<style type="text/css">
#view { background: #2e3033; padding-bottom: 65px; }
#container { margin: 0 15px; }
#container>.label { padding: 15px 0; color: #fff; font-size: 12px; }
#container .remind+.label { padding-top: 0; }
#container>.label i { display: inline-block; width: 13px; height: 13px; background: url(<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/icons_room.png) no-repeat top center; -webkit-background-size: 13px 26px; background-size: 13px 26px; opacity: .3; vertical-align: -2px; margin-right: 4px; }
.popup { position: fixed; z-index: 11; background: #fff; border-radius: 6px; width: 72%; overflow: hidden; display: none; }
.popup .main { padding: 20px 30px; text-align: center; }
.popup .info { padding: 25px 0; font-size: 16px; }
.popup .active { border-top: 1px solid #e6e6e6; height: 45px; text-align: center; font-size: 16px; line-height: 45px; }
.popup-sel .active { position: relative; }
.popup .active button { display: block; background: #fff; width: 100%; height: 45px; }
.mask { display: none; }
.players { background: #fff; border-radius: 6px; overflow: hidden; }
.players li { padding: 15px 15px 15px 20px; font-size: 13px; color: #333; display: -webkit-box; display: box; display: -webkit-flex; display: flex; display: -ms-flexbox; }
.players li+li { border-top: 1px solid #d5d6d6; }
.players h4 { -webkit-box-flex: 1; box-flex: 1; -webkit-flex: 1; flex: 1; height: 22px; line-height: 22px; }
.players i { display: block; width: 21px; height: 21px; border-radius: 11px; border: 1px solid #e5e5e5; cursor: pointer; position: relative; }
.players li.on i { border-color: #1f9970; }
.players li.on i::before { content: ""; position: absolute; left: 7px; top: 3px; width: 5px; height: 9px; border-right: 2px solid #1f9970; border-bottom: 2px solid #1f9970; transform: rotate(45deg); -webkit-transform: rotate(45deg); }

.btn-remove { background: #ffe566; height: 50px; line-height: 50px; text-align: center; border-radius: 6px; margin-top: 15px; font-size: 16px; cursor: pointer; }
</style>
<div id="J_popup" class="popup abscenter">
  <div class="main">
    <div class="info">请至少选择一个玩家</div>
  </div>
  <div class="active"> <button id="J_sureBtn">好的</button> </div>
</div>
<div id="J_mask" class="mask"></div>

<div id="container">
  <div class="label">
    <span> <i></i><?php echo $room['number']; ?>号房间 </span>
    <span> 共<?php echo count($user_list); ?>人 </span>
  </div>
  <div class="players">
    <ul id="J_players">
    <?php foreach ($user_list as $user) : ?>
    <?php if ($user['uid'] == $this->currentUser['uid']) continue; ?>
      <li data-uid="<?php echo $user['uid']; ?>">
        <h4 class="nick"><?php echo $user['nickname']; ?></h4>
        <i data-choose="false"></i>
      </li>
    <?php endforeach; ?>
    </ul>
  </div>
  <div id="J_btnRemove" class="btn-remove"> 确定 </div>
</div>

<div id="J_waiting" class="waiting rel">
  <div class="spinner abscenter">
    <div class="spinner-container container1">
      <div class="circle1"></div>
      <div class="circle2"></div>
      <div class="circle3"></div>
      <div class="circle4"></div>
    </div>
    <div class="spinner-container container2">
      <div class="circle1"></div>
      <div class="circle2"></div>
      <div class="circle3"></div>
      <div class="circle4"></div>
    </div>
    <div class="spinner-container container3">
      <div class="circle1"></div>
      <div class="circle2"></div>
      <div class="circle3"></div>
      <div class="circle4"></div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function () {
  var choose = '';
  $('#J_players li').on('touchend', function () {
    $(this).toggleClass('on');
    choose = [];
    $('#J_players li').each(function () {
      if ($(this).hasClass('on')) {
        choose.push(parseInt($(this).attr('data-uid')));
      }
    });
    if (choose.length) {
      choose = '['+ choose.join(',') +']'
    } else {
      choose = '';
    }
    return false;
  });

  $('#J_btnRemove').on('click', function () {
    if (!choose) {
      $('#J_mask, #J_popup').show();
      return false;
    }
    $(this).off();
    $('.waiting').show();
    $.ajax({
      type: 'POST',
      url: '<?php echo $this->getDeUrl('room/swipe'); ?>',
      async: true,
      data: {"users": choose, '_sh_token_': '<?php echo Yii::app()->request->getCsrfToken(); ?>'},
      dataType: 'json',
      success: function (data) {
        $('.waiting').hide();
        $('.info').text(data.msg);
        $('#J_mask, #J_popup').show();
        $('#J_sureBtn').on('click', function () {
          setInterval(WeixinJSBridge.call('closeWindow'), 100);
        });
      },
      error: function () {
        $('.info').text(data.msg);
        $('#J_mask, #J_popup').show();
      }
    });
  });

  $('#J_mask, #J_sureBtn').on('click', function () {
    $('#J_mask, #J_popup').hide();
  });
});
</script>
<?php elseif ($room && $room['host'] == $this->currentUser['uid']) : ?>
<style type="text/css">
#view { background: #2e3033; padding-bottom: 65px; }
#container { margin: 0 15px; }
#container>.label { padding: 15px 0; color: #fff; font-size: 12px; }
#container .remind+.label { padding-top: 0; }
#container>.label i { display: inline-block; width: 13px; height: 13px; background: url(<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/icons_room.png) no-repeat top center; -webkit-background-size: 13px 26px; background-size: 13px 26px; opacity: .3; vertical-align: -2px; margin-right: 4px; }
.popup { position: fixed; z-index: 11; background: #fff; border-radius: 6px; width: 72%; overflow: hidden; }
.popup .main { padding: 20px 30px; text-align: center; }
.popup .info { padding: 25px 0; font-size: 16px; }
.popup .active { border-top: 1px solid #e6e6e6; height: 45px; text-align: center; font-size: 16px; line-height: 45px; }
.popup-sel .active { position: relative; }
.popup .active button { display: block; background: #fff; width: 100%; height: 45px; }
</style>
<div id="J_popup" class="popup abscenter popup-sel">
  <div class="main"> <div class="info">当前只有一个玩家 </div> </div>
  <div class="active"> <button id="J_sureBtn">好的</button> </div>
</div>
<div id="J_mask" class="mask"></div>
<div id="container">
  <div class="label">
    <span> <i></i><?php echo $room['number']; ?>号房间 </span>
    <span> 共1人 </span>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function () {
  $('#J_sureBtn').on('click', function () {
    setInterval(WeixinJSBridge.call('closeWindow'), 100);
  });
});
</script>
<?php endif; ?>
