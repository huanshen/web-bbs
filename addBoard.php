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
	$ip=$_SERVER['REMOTE_ADDR'];

?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<link href="css/post.css?ver=1312" rel="stylesheet" />
</head>
<body>
	<div class="bgcolor">
		<h1 class="pcenter">帖子发布页面</h1>
		
		<p class="right"> 
			<span id="username" data-ip="<?php echo $ip; ?>"><?php echo $username; ?></span>     <span><a href="logout.php">退出   </a></span>
		</p>
		<p id="notice"></p>
			<p class="pcenter"><label>题目：<input id="title"  name="username" type="text" placeholder=""></label></p>

		<div class="pcenter"><p>描述：</p>
				<textarea rows="6" cols="60" id="contain"></textarea>	
		    
		</div>

		<p class="pcenter"><input type="button" value="返回" id="back">
				<input type="button" id="submit" value="提交" >
			</p>	
	

	</div>
			
</body>
<script src="js/jquery-2.0.3.min.js"></script>
<script src="js/addBoard.js"></script>
</html>