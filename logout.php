<?php

session_start();
$old_user = isset($_SESSION['valid_user']) ? $_SESSION['valid_user']:"";

unset($_SESSION['valid_user']);
$result_dest = session_destroy();


?>
<html>
<head>
  <meta charset="utf-8">
  <link href="css/post.css?ver=1312" rel="stylesheet" />
</head>
<body>
  <div class="bgcolor">
    <h1 class="pcenter">退出页面</h1>
<?php
if (!empty($old_user)) {
  if ($result_dest)  {
    
    echo '退出.<br />';
    echo '<a href="login.php">登入</a><br />';
  } else {
   
    echo '不能退出<br />';
  }
} else {
  
  echo '你未登入，请先登入.<br />';
  echo '<a href="login.php">登入</a><br />';
}


?>
