<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<title><?php echo $this->title?></title>
<meta name="description" content="个人中心"/>
<meta name="keywords" content="那些风靡95后的娱乐小游戏"/>
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<meta content="telephone=no" name="format-detection" />
<link rel="shortcut icon" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/logo/120.png" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/css/base.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/css/hougong/component.css"/>
<script src="//res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
var IMG_DOMAIN = '<?php echo WEB_QW_APP_FILE_UI_URL?>';
</script>
<script src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/common/jquery-1.8.3.min.js"></script>
<script src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/common/utils.js"></script>
<script src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/hougong/hougong.js"></script>
<script src="<?php echo WEB_QW_APP_FILE_UI_URL?>/js/common/Dropload.js"></script>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?48cd05afe9a7016f764b5db63b9a5948";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
</head>
<body>
<div id="view">
<?php echo $content?>
</div>
</body>
</html>
