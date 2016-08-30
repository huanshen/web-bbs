<?php

	session_start();

	//读取缓存，获得用户名
	$username="";
	if(isset($_SESSION['valid_user'])){
		$username=$_SESSION['valid_user'];
	}
	
	
	//如果读取不到缓存，则提示用户登录
	if(empty($_SESSION['valid_user'])){
		include("none.php");
		exit;
	}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<link href="css/index.css" rel="stylesheet" />
</head>
<body>
	<div class="bgcolor">
		<div class="center"><h1>讨论区主页</h1></div>

		<p class="right"> 
			<span id="username"><?php echo $username; ?></span> <span><a href="logout.php">退出   </a></span>
		</p>
		
		<div class="center">
			<ul id='board'></ul>
		</div>
	</div>
</body>
<script src="js/jquery-2.0.3.min.js"></script>
<script src="js/index.js"></script>
</html>