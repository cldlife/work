<style type="text/css">
#view { background: #2e3033; padding-bottom: 65px; }
#container { margin: 0 15px; }
.remind { color: #fff; padding: 10px; text-align: center; line-height: 24px; font-size: 16px; }
#container>.label { padding: 15px 0; color: #fff; font-size: 12px; }
#container .remind+.label { padding-top: 0; }
#container { margin: 0 15px; }
#container>.label i { display: inline-block; width: 13px; height: 13px; background: url(<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/shuishiwodi/icons_room.png) no-repeat top center; -webkit-background-size: 13px 26px; background-size: 13px 26px; opacity: .3; vertical-align: -2px; margin-right: 4px; }
#container>.label span:nth-of-type(2) { color: #fff373; }
#container>.label span:nth-of-type(2) i { background-position-y: bottom; }
.list li { background: #fff; border-radius: 6px; padding: 15px; }
.list li a { position: relative; }
.list li+li { margin-top: 15px; }
.list .avatar { width: 45px; height: 45px; border-radius: 50%; }
.list .desc { color: #b2b2b2; font-size: 12px; }
.list .info { margin-left: 55px; margin-right: 34%; height: 45px; padding: 3px; -webkit-box-sizing: border-box; box-sizing: border-box; }
.list .info .title { font-size: 15px; }
.list .status { max-width: 35%; right: 0; text-align: right; }
.list .status .title { font-size: 12px; }
.list li.playing .status .title { color: #ff4433; }
.list li.waiting-yes .status .title { color: #30bf78; }
.list li.waiting-no .status .title { color: #ff9933; }
.list .status .desc { margin-top: 3px; }
.list i { display: inline-block; width: 12px; height: 12px; background: url(<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/shuishiwodi/icons_avatar.png) no-repeat top center; -webkit-background-size: 12px 36px; background-size: 12px 36px; vertical-align: -2px; margin-right: 2px; }
.list li.playing .status i { background-position-y: top; }
.list li.waiting-yes .status i { background-position-y: -24px; }
.list li.waiting-no .status i { background-position-y: -12px; }
.popup { position: fixed; z-index: 11; background: #fff; border-radius: 6px; width: 72%; overflow: hidden; }
.popup .main { padding: 20px 30px; text-align: center; }
.popup .label { color: #b2b2b2; font-size: 16px; margin-bottom: 15px; }
.popup .active { border-top: 1px solid #e6e6e6; height: 45px; text-align: center; font-size: 16px; line-height: 45px; }
.popup .active button:first-of-type { display: none; }
.popup-sel .active { position: relative; }
.popup-sel .active::before { content: ""; display: block; position: absolute; width: 1px; height: 45px; background: #e6e6e6; left: 50%; }
.popup .active button { display: block; background: #fff; width: 100%; height: 45px; }
.popup-sel .active button { width: 50%; float: left; }
.popup-sel .active button:first-of-type { display: block; color: #b2b2b2; }
.waiting-load { position: relative; height: 50px; background: none; }
.waiting-word { color: #fff; text-align: center; line-height: 50px; display: none; }
.ewm { width: 80%; position: fixed; z-index: 101; left: 50%; top: 50%; -webkit-transform: translate(-50%, -50%); transform: translate(-50%, -50%); background: #fff; padding: 6%; -webkit-box-sizing: border-box; box-sizing: border-box; border-radius: 8px; display: none; }
.ewm img { width: 100%; }
.ewm p { text-align: center; margin-top: 15px; font-size: 16px; }
.btn-create { position: fixed; z-index: 101; width: 100%; left: 0; bottom: 0; height: 50px; background: #1a1a1a; text-align: center; }
.btn-create a { color: #fff; line-height: 50px; color: #fff373; font-size: 16px; }
.btn-create i { width: 12px;  height: 2px; display: inline-block; background: #fff373; border-radius: 2px; vertical-align: 5px; margin-right: 6px; position: relative; }
.btn-create i::before { content: ""; position:  absolute; left: 50%; top: 50%; width: 2px; height: 12px; background: #fff373; border-radius: 2px; margin: -6px 0 0 -1px; }
</style>
<div id="J_popup" class="popup abscenter popup-sel" style="display: none;">
  <div class="main">
    <div class="label">提示</div>
    <div class="info"> 您当前在1717号房间，确定要离 开此房间，加入到1818号房间吗？ </div>
  </div>
  <div class="active">
    <button>取消</button>
    <button>确定</button>
  </div>
</div>

<div id="J_mask" class="mask" style="display: none;"></div>

<div id="J_ewm" class="ewm">
  <img src=""/>
  <p>长按识别二维码立即加入</p>
</div>
<div id="container">
  <?php if(!$share):?>
  <div class="remind"><?php echo $this->currentUser['nickname']?>邀请你一起玩谁是卧底</div>
  <?php endif;?>
  <div class="label">
    <span>
      <i></i>在线房间 <?php echo intval($roomCount);?> 个
    </span>
    <?php if($number):?>
    <span>
      <i></i>你当前在 [<?php echo $number;?>] 号房间
    </span>
    <?php endif;?>
  </div>

  <div id="J_list" class="list">
    <ul> </ul>
  </div>
</div>
<div class="btn-create">
  <a href="http://tf.shiyi11.com/static/wanzhuyule/createroom.html"><i></i>创建房间</a>
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
  wx.config({
    debug: <?php echo APP_DEBUG ? 'true' : 'false'; ?>,
    appId: "<?php echo $weixinJssdkConfig['appid'];?>",
    timestamp: "<?php echo $weixinJssdkConfig['timestamp'];?>",
    nonceStr: "<?php echo $weixinJssdkConfig['noncestr'];?>",
    signature: "<?php echo $weixinJssdkConfig['signature'];?>",
    jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage']
  });
  wx.ready(function(){
    wx.onMenuShareTimeline({
      title: '<?php echo $shareTitle ?>',
      link: "<?php echo $sharelink?>",
      imgUrl: '<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/shuishiwodi/sq_shuishiwodi.jpg',
      success: function () {},
      cancel: function () {}
    });
    wx.onMenuShareAppMessage({
      title: '<?php echo $shareTitle ?>',
      link: "<?php echo $sharelink?>",
      imgUrl: '<?php echo WEB_QW_APP_FILE_UI_URL?>/img/wx/shuishiwodi/sq_shuishiwodi.jpg',
      success: function () {},
      cancel: function () {}
    });
  });
  var Token = '<?php echo Yii::app()->request->getCsrfToken();?>';
  var share = '<?php echo $share;?>';
  var win = $(window);
  var docEl = $(document);

  docEl.bindMoveEvent = function () {
    docEl.on('touchmove', function (e) {
        e.preventDefault();
      })
  };
  docEl.offMoveEvent = function () {
    docEl.off('touchmove');
  }

  var waiting = $('#J_waiting');

  function throttle(func) {
    var timer =null;
    return function () {
      clearTimeout(timer);
      timer = setTimeout(func, 200);
    }
  }

  function Popup(opts) {
    this.init(opts);
  }

  Popup.prototype = {
    init : function () {
      this.bindDom();
      this.bindEvent();
    },
    bindDom : function () {
      var activeBtns;
      this.popMain = $('#J_popup');
      this.mask = $('#J_mask');
      this.info = this.popMain.find('.info');
      activeBtns = this.popMain.find('button');
      this.cancalBtn = activeBtns.eq(0);
      this.sureBtn = activeBtns.eq(1);
      this.tempSureFn = null;
    },
    bindEvent : function () {
      var that = this;
      this.cancalBtn.on('click', function () {
        that.popHide();
      })
      this.mask.on('click', function () {
        that.popHide();
      })
    },
    popShow : function (str) {
      this.popMain.show();
      this.mask.show();
    },
    popHide : function () {
      this.popMain.hide();
      this.mask.hide();
      docEl.offMoveEvent();
      ewm.hide();
    },
    showInfo : function (str) {
      this.info.html(str);
    },
    sureFn : function (sureFunc) {
      this.tempSureFn = sureFunc;
    },
    cancelShow : function (str) {
      var that = this;
      this.popShow(str);
      this.showInfo(str);
      this.popMain.removeClass('popup-sel');
      this.sureBtn.off('click');
      this.sureBtn.on('click', function () {
        that.popHide();
      })
    },
    sureShow : function (str) {
      var that = this;
      this.popShow(str);
      this.showInfo(str);
      this.popMain.addClass('popup-sel');
      this.sureBtn.off('click');
      this.sureBtn.on('click', function () {
        that.tempSureFn();
      })
    },
    setData : function (info) {
      this.popMain.attr('data-info', info);
    },
    getData : function () {
      return this.popMain.attr('data-info');
    }
  };

  var popup = new Popup();
  function Ewm() {
    this.init();
  }

  Ewm.prototype = {
    init : function () {
      this.bindDom();
    },
    setImg : function (src) {
      this.img = src;
      this.bindImg();
      return this;
    },
    bindDom : function () {
      this.imgWrapDom = $('#J_ewm');
      this.imgDom = this.imgWrapDom.find('img');
      return this;
    },
    bindImg : function () {
      this.imgDom.attr('src', this.img);
    },
    show : function () {
      this.imgWrapDom.show();
      return this;
    },
    hide : function () {
      this.imgWrapDom.hide();
      return this;
    }
  };

  var ewm = new Ewm();
  function Roomlist(opts) {
    this.init(opts);
  };

  Roomlist.prototype = {
    init : function (opts) {
      this.bindDom();
      this.ajaxData = {
        type : opts.type,
        surl :  opts.surl
      };
      this.successFn = opts.successFn;
      this.sureFn = opts.successFn;
      this.bindEvent();
    },
    bindDom : function () {
      this.roomCont = $('#J_list');
    },
    bindEvent : function () {
      var that = this;
      this.roomCont.on('click', 'li', function () {
        var info = $(this).data('info') || '0_0';
        var status = $(this).data('status');
        that.popConfirm(info, status);
        popup.sureFn(function () {
          var info = popup.getData('data-info');
          that.popConfirm(info, status);
        })
      })
    },
    popConfirm : function (info, status) {
      var that = this;
      popup.popMain.hide();
      waiting.removeClass('waiting-load').show();
      setTimeout(function () {
        that.fetchData(info, status);
      }, 200)
    },
    fetchData : function (info, status) {
      var that = this;
      if (info == "<?php echo $pseudoRoom; ?>") {
        waiting.show();
        setTimeout(function () {
          waiting.hide();
          var tip = status == 2 ? '手慢了,该房间已满,无法加入.你可以自己创建房间邀请好友一起来玩' : '手慢了,游戏已经开始,无法加入.你可以自己创建房间邀请好友一起来玩';
          popup.cancelShow(tip);
          docEl.bindMoveEvent();
        }, 500);
        return true;
      }
      $.ajax({
        type : that.ajaxData.type,
        url : that.ajaxData.surl,
        async : true,
        data : {"info" : info, '_sh_token_' : Token, 'share' : share},
        success : function (data) {
          waiting.addClass('waiting-load');
          if (dropLoad.checkSide()) {
            waiting.hide();
          }
          var strInfo = '';
          switch (data.code) {
            case -6:
              strInfo = '你已经在游戏中,不可加入任何房间';
              break;
            case -5:
              strInfo = '系统出错加入失败';
              break;
            case -4:
              strInfo = '手慢了,该房间已满,无法加入.你可以自己创建房间邀请好友一起来玩';
              break;
            case -3:
              strInfo = '手慢了,游戏已经开始,无法加入.你可以自己创建房间邀请好友一起来玩';
              break;
            case -2:
              strInfo = '你已经在此房间内了,请换个房间';
              break;
            // case -1:
            //   strInfo = '你的财富低于10金币,不能加入房间!';
            //   break;
            case 1:
              that.successFn(info);
              return;
              break;
            case 2:
              strInfo = '你当前在' + data.beforeRoomNum + '房间，要确定要加入' + data.afterRoomNum + '房间么';
              popup.setData(data.RoomInfo);
              popup.sureShow(strInfo);
              docEl.bindMoveEvent();
              return;
              break;
            case 3:
              popup.mask.show();
              ewm.setImg(data.ticket).show();
              return;
            default:
              strInfo = '服务出错';
              break;
          }
          popup.cancelShow(strInfo);
          docEl.bindMoveEvent();
        },
        error : function (data) {
          console.log('失败了。。。');
        }
      });
    }
  };

  var roomlist = new Roomlist({
    type : 'post',
    surl : '<?php echo $this->getDeUrl("room/roomjoinajax");?>',
    successFn : function (info) {
      setInterval(WeixinJSBridge.call('closeWindow'), 100);
    }
  });

  function Dropload(opts) {
    this.timer = null;
    this.init(opts);
  }

  Dropload.prototype = {
    init : function (opts) {
      var that = this;
      this.ajaxData = {
        type : opts.type,
        surl :  opts.surl,
        page : opts.page || 0,
      };
      this.cont = $('#container');
      this.itemContainer = roomlist.roomCont.find('ul');
      this.bindWinEvent();
      this.load(that);
    },
    bindWinEvent : function () {
      var that = this;
      var load = throttle(function () {
        that.load(that);
      });
       win.on('scroll', function () {
         load();
      })
    },
    load : function (that) {
      if(that.checkSide()) {
        waiting.addClass('waiting-load').show();
        that.fetchData();
      }
    },
    checkSide : function () {
      var winScrollHeight = win.scrollTop(),
          winHeight = win.height(),
          contHeight = this.cont.outerHeight();
      return (winScrollHeight + winHeight >= contHeight);
    },
    fetchData : function () {
      var that = this;
      $.ajax({
        type : this.ajaxData.type,
        url : this.ajaxData.surl,
        async : true,
        data : {'page' : this.ajaxData.page ++, '_sh_token_' : Token},
        success : function (data) {
          if (data.length == 0) {
            waiting.removeClass('waiting-load').hide();
            var remind = $('<div style="text-align: center; line-height: 50px; color: #fff;">没有更多数据了</div>');
            that.cont.after(remind);
            win.off('scroll');
            return;
          }
          that.bindDom(data);
          that.load(that);
        },
        error : function (data) {
          alert('服务器出错。。。');
        }
      });
    },
    bindDom : function (data) {
      var str = '';
      $.each(data, function (index, item) {
        var className = '',
            title = '',
            desc = '';
        if (item.gameState == 1) {  //  1: 游戏中 2: 等待中(num : 0 : 人数已满, num : !0 : 人数可加入实际num人数)
          className = 'playing';
          title = '游戏中';
          desc = '不可加入';
        } else if (item.gameState == 2) {
          if(item.gameNum == 0) {
            className = 'waiting-no';
            title = '等待中';
            desc = '人数已满';
          } else if (item.gameNum > 0) {
            className = 'waiting-yes';
            title = '等待中';
            desc = '还可以加入' + item.gameNum + '人';
          } else {
            str += '<li><a href="javascript:;"><div class="info" style="line-height:45px;">gameNum error</div></a></li>';
          }
        } else {
          str += '<li><a href="javascript:;"><div class="info" style="line-height:45px;">gameState error</div></a></li>';
        }
        str += '<li class="' + className + '" data-info="'+ item.data +'" data-status="'+ item.gameState +'"><a href="javascript:;"><img class="avatar vabscenter" src="' + item.avatar + '"><div class="info"><div class="title ellipsis">' + item.number + '号房</div><div class="desc ellipsis">' + item.nickname + '</div></div><div class="status vabscenter"><div class="title"><i></i>' + title + '</div><div class="desc ellipsis">' + desc + '</div></div></a></li>';
      })
      this.itemContainer.append($(str));
    }
  };

  var dropLoad = new Dropload({
    type : 'post',
    surl :  '<?php echo $this->getDeUrl("room/roomlistajax");?>',
    page : 1,
  });
</script>

