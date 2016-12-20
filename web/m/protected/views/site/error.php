<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8" />
<meta http-equiv="refresh" content="5;url=<?php echo $this->getDeUrl()?>">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<style>
.site-error {color:#666; padding:20px;}
.site-error em {font: bold 80px/80px Arial;display:block;}
.site-error h3 {font-size:22px;vertical-align:top;height:20px;line-height:20px;margin:0;padding:10px 0;}
.site-error ul {padding-left: 20px;}
.site-error li {text-align: left;font-size: 14px; line-height:18px;list-style:circle;}
.site-error li a{color:#063ac8; }
.site-error li a:hover{color:#0079C1; text-decoration: underline;}
.site-error .infotip {vertical-align:top;margin-top:20px;border-bottom:1px solid #d6dbe5;padding-bottom:45px;}
.site-error .infotip .tiper {display:inline-block;	vertical-align:top;font-size:14px;}
.site-error .icon {display:inline-block;width:60px;_float:left;height:75px;margin-right:15px;vertical-align:top;}
.site-error .buttons {padding-top:38px;}
.site-error .blue-button {cursor: pointer;overflow: hidden;border: 1px solid #4d980b;background: #3CAA0E;background: -moz-linear-gradient(top, #4ac317, #3caa0e);background:-webkit-gradient(linear, 0 0, 0 100%, from(#4ac317), to(#3caa0e));filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#4ac317', endColorstr='#3caa0e');-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#4ac317', endColorstr='#3caa0e')";COLOR: #FFF;box-shadow: 0 1px 3px #ececec;-webkit-box-shadow: 0 1px 3px #ececec;-moz-box-shadow: 0 1px 3px #ececec;padding:0 20px}
</style>
<title><?php echo $this->title?></title>
</head>
<body>
  <div class="center">
    <div class="site-error">
      <?php if ($code == 404) :?>
      <div class="infotip">
        <em>:( <?php echo $code;?></em>
        <h3 style="padding: 40px 0;font-size:20px;"><?php echo $message;?></h3>
        <ul>
          <li>请检查您输入的网址是否正确。</li>
          <li>直接访问 <a href="<?php echo $this->getDeUrl()?>">网站首页</a></li>
          <li style="padding-top:20px">5秒后将自动跳转到网站首页</li>
        </ul>
      </div>
      
      <?php else :?>
      
      <div class="infotip">
        <span class="icon"></span>
        <span class="tiper">
          <h3>温馨提示</h3>
          <div style="padding-bottom:10px"><?php echo $message;?></div>
          <ul>
            <li>请返回上一页重新操作</li>
            <li>直接访问 <a href="<?php echo $this->getDeUrl()?>">网站首页</a></li>
            <!-- <li><a href="<?php echo $this->getDeUrl('help/feedback')?>">报告问题</a></li> -->
          </ul>
        </span>
      </div>
      <div class="buttons">
        <a class="blue-button close" style="display:inline-block;font-size:14px;line-height:34px; text-decoration:none;color:#fff" href="<?php echo $refererUrl?>">我已明白，返回上一页</a>
      </div>
      <?php endif;?>
    </div>
  </div>
</body>
</html>