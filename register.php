<?php
    $ip=$_SERVER['REMOTE_ADDR'];
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>用户注册</title>
    <link href="css/register.css" rel="stylesheet" />
</head>

<body>

    <div class="center"><h1>用户注册</h1></div>
    <p><a href="login.php">回到登入页面</a></p>
    <table width="330" border="0" align="center" cellpadding="5" bgcolor="#eeeeee">

        <tr>
            <td width="40%" >用户名：</td>
            <td><input name="username" data-ip="<?php echo $ip;?>" type="text" id="username"> </td>
        </tr>

        <tr>
            <td>密码：</td>
            <td><input name="pwd" type="password" id="pwd"></td>
        </tr>

        <tr>
            <td>重复密码：</td>
            <td><input name="repeat_pwd" type="password" id="repeat_pwd"></td>
        </tr>

        <tr>
            <td>姓名：</td>
            <td><input name="name" type="text" id="name"></td>
        </tr>

        <tr>
            <td>Email:</td>
            <td><input name="email" type="text" id="email"></td>
        </tr>

        <tr>
            <td colspan="2" align="center">
            <input type="button" name="submit" id="submit"  value="提交">
            <input type="reset" id="reset" name="reset" value="重置"></td>
        </tr>

    </table>

</body>
    <script src="js/jquery-2.0.3.min.js"></script>
    <script src="js/register.js"></script>
</html>

