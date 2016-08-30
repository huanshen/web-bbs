<?php

// 定义上传上来的图像文件存放的目录，注意是相对目录

define("UPLOAD_IMGS_PATH", "upload_imgs/");



// 上传文件的函数

function upload($name, $destDir)

{

    if(empty($_FILES[$name])) return '';

    

    $uploadname = basename($_FILES[$name]['name']);

    $uploaddir = realpath($destDir) . '/';

    $uploadfile = $uploaddir . $uploadname;

    if(file_exists($uploadfile)) {

        $uploadname .= rand();

        $uploadfile = $uploaddir . $uploadname;

    }

    

    if (move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile)) {

        return $uploadname;

    }

    

    return '';

}



function db_connect()

{

   $db = @new mysqli("127.0.0.1", "developer", "123456", "bbs");

   if (!$db)

      return false;

   return $db;

}



function reformat_date($datetime)

{

    // 格式化日期时间

    list($year, $month, $day, $hour, $min, $sec) = split( '[: -]', $datetime );

    return "{$year}年{$month}月{$day}日 $hour:$min:$sec";

}



function makeIns4SQL($arr, $type='int')

{

    if(empty($arr)) return '';

    

    $ins = '';

    foreach($arr as $value) {

        if($type == 'int')

            $ins .= "$value,";

        else

            $ins .= "'$value',";

    }    

    $ins{strlen($ins)-1} = ' '; //移除末尾的','号    

    return $ins;

}

        

// 检查客户端提交的数据中是否含有非法字符

function checkIllegalWord ()

{

    // 定义不允许提交的SQL命令及关键字

    $words = array();

    $words[]    = "add";

    $words[]    = "count";

    $words[]    = "create";

    $words[]    = "delete";

    $words[]    = "drop"; 

    $words[]    = "from";

    $words[]    = "grant";

    $words[]    = "insert"; 

    $words[]    = "select";

    $words[]    = "truncate";

    $words[]    = "update";

    $words[]    = "use";

    

    // 判断提交的数据中是否存在以上关键字, $_REQUEST中含有所有提交数据

    foreach($_REQUEST as $strGot) {

        $strGot = strtolower($strGot); // 转为小写

        if (in_array($strGot, $words)) {

            echo "您输入的内容含有非法字符！";

            exit; // 退出运行

        }

    }// foreach

}



checkIllegalWord(); // 检查非法字符

?>