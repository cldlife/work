<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>玩主喊话</title>
<meta name="description" content="玩主，就是玩得来"/>
<meta name="keywords" content="那些风靡95后的娱乐小游戏"/>
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<link rel="shortcut icon" href="http://s.wanzhucdn.com/ui/img/logo/120.png" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/css/m/base.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo WEB_QW_APP_FILE_UI_URL?>/css/m/component.css?v1.0"/>
<style type="text/css">
#view { padding-bottom: 65px; }
.article .show .img-wrap { padding-top: 40%; overflow: hidden;}
</style>
</head>
<body>
<div id="view">
	
	<div id="container">
		
		<div class="article">
			<div class="user">
				<div class="avatar">
					<div class="inner">
						<img src="<?php echo $user['avatar']?>" />
					</div>
				</div>
				<div class="info">
					<div class="nick">
						<h1 class="ellipsis"><?php echo $user['nickname']?></h1>
						<img src="<?php echo WEB_QW_APP_FILE_UI_URL;?>/img/app/level/<?php echo $grade;?>@2x.png"/>
					</div>
					<div class="time"><?php echo date('Y.m.d H:i',$thread['created_time']);?></div>
				</div>
			</div>
			
			<div class="show">
				<div class="img-wrap">
					<img src="<?php echo $user['avatar']?>" />
				</div>
				<div class="show-desc">
					<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/m/icon_hanhua_showdeco.png" />
					<p class="mellipsis"><?php echo $thread['content']?></p>
					<div class="sponsor">
						<p class="ellipsis">By <?php echo $user['nickname']?></p>
						<p></p>
					</div>
				</div>
			</div>
			
			<div class="comment">
				<ul>
					<?php if($replied):?>
						<?php foreach($replied as $item):?>
						<li class="box">
							<div class="label">
								<h4><?php echo $item['user_nickname']?></h4> <?php if($item['replied_nickname']):?>回复 <p><?php echo $item['replied_nickname']?></p><?php endif;?>
							</div>
							<div class="desc box1">
								<?php echo $item['content']?>
							</div>
						</li>
						<?php endforeach;?>
					<?php endif;?>
				</ul>
			</div>
		</div><!-- /article -->
		
		<div class="correlation">
			<div class="label">
				<h3>热门喊话</h3>
			</div>
			<div class="details">
				<ul>
					<?php if($hanhualist):?>
						<?php foreach($hanhualist as $item):?>
						<li>
							<a href="<?php echo $item['href']?>">
								<div class="show-desc">
									<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/m/icon_hanhua_showdeco.png" />
									<p class="mellipsis"><?php echo $item['content']?></p>
									<div class="sponsor">
										<p class="ellipsis">By <?php echo $item['nickname']?></p>
									</div>
								</div>
							</a>
						</li>
						<?php endforeach;?>
					<?php endif;?>
				</ul>
			</div>
		</div>
		
	</div><!-- /container -->
	
	<div class="aidver rel">
		<div class="info">
			<h2 class="ellipsis">玩主，就是玩得来</h2>
			<p class="ellipsis">那些风靡95后的娱乐小游戏</p>
		</div>
		<div class="show vabscenter">
			<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/logo/120.png" />
		</div>
		<div class="acitve vabscenter">
			<a href="<?php echo WEB_QW_APP_DOMAIN?>/d?fr=hanhua">
				<img src="<?php echo WEB_QW_APP_FILE_UI_URL?>/img/m/icon_download.png" />
			</a>
		</div>
	</div>
	
</div><!-- /view -->

</body>
</html>
