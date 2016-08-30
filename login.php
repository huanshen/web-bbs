<?php
	require_once ('data_valid.php');
	require_once ('user_valid.php');
	session_start();

	//获取数据
	$username = isset( $_POST['username'])?$_POST['username']:"";
	$passwd = isset($_POST['passwd'])?$_POST['passwd']:"";

	echo $username;
	echo $passwd;
	//$result =login($username,$passwd);
	//echo $result[0];

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<link href="css/login.css" rel="stylesheet" />
</head>
<body>
	<div class="bgcolor">
		<div class="center"><h1>论坛登陆</h1></div>
		<form method="post" action="login.php">
			<p class="center"><label>姓名：<input id="username" name="username" type="text" placeholder=""></label></p>

			<p class="center"><label>密码：<input id="passwd" name="passwd" type="password" placeholder="" value=""></label></p>
			
			<p class="center">
				<label><input type="checkbox" id="checkbox">管理员</label><input type="button" id="submit" value="登入" onclick="submitfn()">
				<input type="button" value="注册" id="register">
			</p>	
		</form>
		<p class="center"><a href="#">忘了密码</a></p>
	</div>
</body>
<script src="js/jquery-2.0.3.min.js"></script>
<script src="js/login.js"></script>
</html>