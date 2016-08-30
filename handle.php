<?php
	require_once('data_valid.php');
	require_once('user_valid.php');
	session_start();

	//获取数据
	$type=isset( $_POST['type'])?$_POST['type']:"";
	switch ($type) {
		case 'login':

			$username = isset( $_POST['username'])?$_POST['username']:"";
			$passwd = isset($_POST['passwd'])?$_POST['passwd']:"";
			$tag = isset($_POST['tag'])?$_POST['tag']:0;
			if ($username && $passwd) {
			  	try  {
			  		//检查是否已经注册
				    login($username, $passwd,$tag);
				    $_SESSION['valid_user'] = $username;
				    echo '{"name":"1123"}';
			}
			  	catch(Exception $e)  {
				    echo '用户名或密码错误';
				    exit;
			  	}
			}
			break;

		case 'board':
			$board=getBoard();
			echo json_encode($board);

			break;

		case 'article':
			$f_id = isset( $_POST['f_id'])?$_POST['f_id']:"";

			$Artical=getArtical($f_id);

			echo json_encode($Artical);

			break;

		case 'content':
			$f_id = isset( $_POST['f_id'])?$_POST['f_id']:"";

			$Acontent=getAcontent($f_id);
			
				echo json_encode($Acontent);
			
			break;

		case 'enable':
			$f_id = isset( $_POST['username'])?$_POST['username']:"";

			$Acontent=getEnable($f_id);
			echo json_encode($Acontent);

			break;

		case 'review':
		case 'post':
			$f_username = isset( $_POST['f_username'])?$_POST['f_username']:"";
			$f_board_id = isset( $_POST['f_board_id'])?$_POST['f_board_id']:"";
			$f_title = isset( $_POST['f_title'])?$_POST['f_title']:"";
			$f_ip=isset( $_POST['ip'])?$_POST['ip']:"";
			$content=isset( $_POST['content'])?$_POST['content']:"";
			$f_parent_id=isset( $_POST['f_parent_id'])?$_POST['f_parent_id']:0;;
			$f_post_time= date('y-m-d h:i:s',time());
			$Acontent=getPost($f_parent_id, $f_title, $f_username, $f_board_id,$f_post_time, $f_ip,$content);
			echo json_encode(array($Acontent,$f_post_time));

			break;

		case 'content1':
			
			$f_parent_id=isset( $_POST['f_id'])?$_POST['f_id']:0;;

			$Acontent=getArticalInfo1($f_parent_id);
			echo json_encode($Acontent);

		break;

		case 'register':
			
			$f_username = isset( $_POST['username'])?$_POST['username']:"";
			$pwd = isset( $_POST['pwd'])?$_POST['pwd']:"";
			$f_email = isset( $_POST['email'])?$_POST['email']:"";
			$f_ip=isset( $_POST['ip'])?$_POST['ip']:"";
			$name=isset( $_POST['name'])?$_POST['name']:"";

			$Acontent=register($f_username,$pwd,$f_ip,$f_email,$name);
			
			echo json_encode(array($Acontent));

		break;

		case 'adminUser':
			
			$Acontent=getUser();
			
			echo json_encode($Acontent);

		break;

		case 'addboard':
			
			$f_name = isset( $_POST['f_name'])?$_POST['f_name']:"";
			$f_desc = isset( $_POST['f_desc'])?$_POST['f_desc']:"";
			$Acontent=addBoard($f_name,$f_desc );
			
			echo json_encode($Acontent);

		break;

		//屏蔽某一个板块
		case 'pingboard':
			
			$ping = isset( $_POST['ping'])?$_POST['ping']:"";
			
			$Acontent=pingBoard($ping );
			
			echo json_encode($Acontent);

		break;

		//取消屏蔽某一个板块
		case 'cancleBoard':
			
			$ping = isset( $_POST['ping'])?$_POST['ping']:"";
			
			$Acontent=cancleBoard($ping );
			
			echo json_encode($Acontent);

		break;

		//取消屏蔽某一个消息
		case 'pingReview':
			
			$ping = isset( $_POST['ping'])?$_POST['ping']:"";
			
			$Acontent=pingArtical($ping );
			
			echo json_encode($Acontent);

		break;

		//取消屏蔽某一个消息
		case 'cancleReview':
			
			$ping = isset( $_POST['ping'])?$_POST['ping']:"";
			
			$Acontent=cancleReview($ping );
			
			echo json_encode($Acontent);

		break;

		//屏蔽某一个文章
		case 'pingArtical':
			
			$ping = isset( $_POST['ping'])?$_POST['ping']:"";
			
			$Acontent=pingArtical($ping );
			
			echo json_encode($Acontent);

		break;

		//取消屏蔽某一个文章
		case 'cancleArtical':
			
			$ping = isset( $_POST['ping'])?$_POST['ping']:"";
			
			$Acontent=cancleArtical($ping );
			
			echo json_encode($Acontent);

		break;

		//屏蔽用户选项
		case 'pingUser':
			
			$ping = isset( $_POST['ping'])?$_POST['ping']:"";
			
			$Acontent=pingUser($ping );
			
			echo json_encode($Acontent);

		break;

		//取消屏蔽用户选项
		case 'cancleUser':
			
			$ping = isset( $_POST['ping'])?$_POST['ping']:"";
			
			$Acontent=cancleUser($ping );
			
			echo json_encode($Acontent);

		break;

		case 'adminPost':
			
			$Acontent=getPostRank();
			
			echo json_encode($Acontent);

		break;
		
		default:
			# code...
			break;
	}
	

?>
