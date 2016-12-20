<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="none" />
<meta name="viewport" id="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title><?php echo $this->title?></title>
<link rel="stylesheet" type="text/css" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/css/base.css">
<script src="<?php echo WEB_QW_APP_FILE_UI_URL; ?>/js/common/jquery-1.8.3.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
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
