<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="yes" name="apple-touch-fullscreen" />
<meta name="robots" content="none" />
<meta name="viewport" id="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title><?php echo $this->title?></title>
<meta name="description" itemprop="description" content=" " />
<link rel="stylesheet" type="text/css" href="http://s.wanzhucdn.com/ui/css/know/ndwm.css">
<style type="text/css">
</style>
<?php if ($this->baiduTongjiCode): ?>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?<?php echo $this->baiduTongjiCode;?>";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
<?php endif;?>
<script src="http://f.shiyi11.com/ui/js/common/jquery-1.8.3.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body>
<div class="view">
<div class="bg" style="background-image: url(<?php echo $this->background_image;?>);"></div>
<?php echo $content?>
</div>
<?php echo $this->googleAnalyticsCode; ?>
</body>
</html>