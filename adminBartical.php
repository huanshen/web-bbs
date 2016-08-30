<?php 
	session_start();

	//读取缓存，获得用户名
	$username="";
	if(isset($_SESSION['valid_user'])){
		$username=$_SESSION['valid_user'];
	}
	$f_board_id="";
	if(isset($_SESSION['f_board_id'])){
		$f_board_id=$_SESSION['f_board_id'];
	}
	
	//如果读取不到缓存，则提示用户登录
	if(empty($_SESSION['valid_user'])){
		include("none.php");
		exit;
	}

	$ip=$_SERVER['REMOTE_ADDR'];
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<link href="css/article.css?ver=1312" rel="stylesheet" />
</head>
<body>
	<div class="bgcolor">
		
		<!--显示登陆信息-->
		<p class="right"> 
			<span id="username" data-ip="<?php echo $ip; ?>"><?php echo $username; ?></span> <span><a href="logout.php">退出   </a></span>
		</p>
		<!--显示文章内容-->
		<div>
			<h1 class="center" id="title"></h1>

			<p class="center pfont"><span id="poster"></span><span id="post-time"></span></p>

			<p></p>
			<div id="content">
			</div>
		</div>
		<hr>
		<div >
			<div id="reviewlist">
				<div id="messagebox"><textarea class="xheditor"  id="message"></textarea>
				<p>&nbsp</p><p id="hui" class="fright hover pfont inBlock">回复</p></div>
			</div>
			
		</div>
		
		<input type="button" value="返回" id="back">
	</div>

</body>
<script src="js/jquery-2.0.3.min.js"></script>
<script src="js/adminBarticle.js"></script>
<script type="text/javascript" src="js/xheditor-1.2.2.min.js"></script>
<script type="text/javascript" src="js/zh-cn.js"></script>
</html>