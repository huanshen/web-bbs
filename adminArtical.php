<?php 
	session_start();

	//读取缓存，获得用户名
	$username="";
	if(isset($_SESSION['valid_user'])){
		$username=$_SESSION['valid_user'];
	}
	
	if(isset($_GET['f_id'])){
		$_SESSION['f_board_id']=$_GET['f_id'];
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
	<link href="css/adminboard.css?ver=1312" rel="stylesheet" />
</head>
<body>
	<div class="bgcolor">
		<h1 class="center" id="title"></h1>

		<p class="right"> 
			<span id="username"><?php echo $username; ?></span> <span><a href="logout.php">退出   </a></span>
		</p>

		<p><b>描述：</b><span id="desc"></span></p>
		<div id="board">
		</div>

		<p class="center">
			
			<input type="button" value="返回" id="back">
			<input type="button" id="all" value="全选" >
			<input type="button" id="ping" value="屏蔽" >
			<input type="button" id="cancle" value="取消屏蔽" >
		</p>
		
	</div>
</body>
<script src="js/jquery-2.0.3.min.js"></script>
<script src="js/adminArtical.js"></script>
</html>