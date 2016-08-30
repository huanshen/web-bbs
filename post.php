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


/*
	if(empty($_SESSION['uid'])) {
	    $_SESSION['fromURL'] = $_SERVER['REQUEST_URI'];
	    header("Location: login.php");
	}
	else {
	    $user = new User($_SESSION['uid']);
	    if(!$user->m_enabled) {
	        echo '对不起，您无权发贴！';
	        exit;
	    }
	}

	// 构造版面列表
	$board = new BoardList();
	// 取当前版面
	$bid = isset($_GET['bid']) ? $_GET['bid'] : 1;
	$cur_board = $board->getBoard($bid);
	// 取贴子ID
	$id = isset($_GET['id']) ? $_GET['id'] : 0;
	// 构造贴子'
	$article = new Article($id);

	//------------------------------------------
	$title      = $_POST['title'];
	$content    = $_POST['content'];
	if(!empty($title)) {
	    $article->m_board_id    = $bid;
	    $article->m_parent_id   = $id;
	    $article->m_title       = $title;
	    $article->m_content     = $content;
	    $article->m_picture     = upload('picture', UPLOAD_IMGS_PATH); 
	    
	    if($article->post()) {
	        header("Location: default.php?bid={$bid}#{$id}");
	    }
	}
	*/
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

		<div id="editor">
					<!-- 加载编辑器的容器 -->
		    <script id="container" name="content" type="text/plain">
		        
		    </script>
		    <!-- 配置文件 -->
		    <script type="text/javascript" src="js/utf8-php/ueditor.config.js"></script>
		    <!-- 编辑器源码文件 -->
		    <script type="text/javascript" src="js/utf8-php/ueditor.all.js"></script>
		    <!-- 实例化编辑器 -->
		    <script type="text/javascript">
		        var ue = UE.getEditor('container');
		    </script>
		</div>

		<p class="pcenter"><input type="button" value="返回" id="back">
				<input type="button" id="submit" value="提交" >
			</p>	
	

	</div>
			
</body>
<script src="js/jquery-2.0.3.min.js"></script>
<script src="js/post.js"></script>
</html>