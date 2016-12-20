<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf8">
<meta id="viewport" name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>正在加载</title>
<style>	
html,body{
	height:100%;
	padding:0px;
	margin:0px;
}
body{
	background-color: #F4F4F4;
}
.panel {
	padding: 18px 22px 10px;	
}
.mesg-block{
	margin-bottom:20px;
}
.mesg-block p{
	font-size: 16px;
	line-height: 1.3em;
	color: #000;
	text-shadow: 0px 1px 0px #FFF;
	text-align:center;
}
</style>
</head>
<body>
<div class="panel">
  <div class="mesg-block">
    <p>正在加载...</p>
  </div>	
</div>
<script>
var htmlDecode = function(str){
  return str.replace(/&#39;/g, '\'')
        .replace(/<br\s*(\/)?\s*>/g, '\n')
        .replace(/&nbsp;/g, ' ')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>')
        .replace(/&quot;/g, '"')
        .replace(/&amp;/g, '&');
};
var url = htmlDecode("<?php echo $targetUrl;?>");
location.href = url;
</script>
</body>
</html>
