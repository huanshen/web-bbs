<?php

require_once('db_fns.php');

function register($username,  $password,$ip,$email,$name) {

  $conn = db_connect();
  $conn->autocommit(FALSE);
  $result = $conn->query("select * from t_user where f_username='".$username."'");
  if (!$result) {
    return 1;
  }

  if ($result->num_rows>0) {
    return 2;
  }

  $rs1 = $conn->query("insert into t_user (f_username, f_password,f_loginip, f_email,f_name) values
                         ('".$username."', md5('".$password."'), '".$ip."', '".$email."', '".$name."')");
  $rs2 = $conn->query("insert into t_postinfo (f_uname) values
                         ('".$username."')");
  if (!$rs1) {
    return 3;
  }
  if($rs1  && $rs2){
      $conn->commit();
      $ret=0;
  }else{
      $conn->rollback();
      $ret= 3;
  }
  $conn->close();
  return $ret;
}

function login($username, $password,$type) {

  $conn = db_connect();

  $result = $conn->query("select * from t_user
                         where f_username='".$username."'
                         and f_password = md5('".$password."') ");
  if (!$result) {
     throw new Exception('Could not log you in.');
  }

  if ($result->num_rows>0) {
    $rs=$result->fetch_object();
    if($rs->f_tag==$type){
     return true;
   }else{
      throw new Exception('请确认你的登入类型.');
   }
  } else {
     throw new Exception('Could not log you in.');
  }
}

function check_valid_user() {
// see if somebody is logged in and notify them if not
  if (isset($_SESSION['valid_user']))  {
      echo "Logged in as ".$_SESSION['valid_user'].".<br />";
  } else {
     // they are not logged in
     do_html_heading('Problem:');
     echo 'You are not logged in.<br />';
     do_html_url('login.php', 'Login');
     do_html_footer();
     exit;
  }
}

function change_password($username, $old_password, $new_password) {
// change password for username/old_password to new_password
// return true or false

  // if the old password is right
  // change their password to new_password and return true
  // else throw an exception
  login($username, $old_password);
  $conn = db_connect();
  $result = $conn->query("update user
                          set passwd = sha1('".$new_password."')
                          where username = '".$username."'");
  if (!$result) {
    throw new Exception('Password could not be changed.');
  } else {
    return true;  // changed successfully
  }
}

function get_random_word($min_length, $max_length) {
// grab a random word from dictionary between the two lengths
// and return it

   // generate a random word
  $word = '';
  // remember to change this path to suit your system
  $dictionary = '/usr/dict/words';  // the ispell dictionary
  $fp = @fopen($dictionary, 'r');
  if(!$fp) {
    return false;
  }
  $size = filesize($dictionary);

  // go to a random location in dictionary
  $rand_location = rand(0, $size);
  fseek($fp, $rand_location);

  // get the next whole word of the right length in the file
  while ((strlen($word) < $min_length) || (strlen($word)>$max_length) || (strstr($word, "'"))) {
     if (feof($fp)) {
        fseek($fp, 0);        // if at end, go to start
     }
     $word = fgets($fp, 80);  // skip first word as it could be partial
     $word = fgets($fp, 80);  // the potential password
  }
  $word = trim($word); // trim the trailing \n from fgets
  return $word;
}

function reset_password($username) {
// set password for username to a random value
// return the new password or false on failure
  // get a random dictionary word b/w 6 and 13 chars in length
  $new_password = get_random_word(6, 13);

  if($new_password == false) {
    throw new Exception('Could not generate new password.');
  }

  // add a number  between 0 and 999 to it
  // to make it a slightly better password
  $rand_number = rand(0, 999);
  $new_password .= $rand_number;

  // set user's password to this in database or return false
  $conn = db_connect();
  $result = $conn->query("update user
                          set passwd = sha1('".$new_password."')
                          where username = '".$username."'");
  if (!$result) {
    throw new Exception('Could not change password.');  // not changed
  } else {
    return $new_password;  // changed successfully
  }
}

function notify_password($username, $password) {
// notify the user that their password has been changed

    $conn = db_connect();
    $result = $conn->query("select email from user
                            where username='".$username."'");
    if (!$result) {
      throw new Exception('Could not find email address.');
    } else if ($result->num_rows == 0) {
      throw new Exception('Could not find email address.');
      // username not in db
    } else {
      $row = $result->fetch_object();
      $email = $row->email;
      $from = "From: support@phpbookmark \r\n";
      $mesg = "Your PHPBookmark password has been changed to ".$password."\r\n"
              ."Please change it next time you log in.\r\n";

      if (mail($email, 'PHPBookmark login information', $mesg, $from)) {
        return true;
      } else {
        throw new Exception('Could not send email.');
      }
    }
}

function getBoard(){
    $m_boards = array();
          
    $conn = db_connect();

    $sql = "SELECT * FROM t_board";
    $result = $conn->query($sql);
    //获取行数
    $num_results=$result->num_rows;

    for ($i=0;$i<$num_results;$i++){
        //获取每一行的记录
        $m_boards[$i]=$result->fetch_assoc();
    }

    return $m_boards;
}

function getUser(){
    $m_boards = array();
          
    $conn = db_connect();

    $sql = "SELECT * FROM t_user left outer join t_postinfo on t_user.f_username=t_postinfo.f_uname ";
    $result = $conn->query($sql);
    //获取行数
    $num_results=$result->num_rows;

    for ($i=0;$i<$num_results;$i++){
        //获取每一行的记录
        $m_boards[$i]=$result->fetch_object();
    }

    return $m_boards;
}

//获取用户发帖排行
function getPostRank(){
    $m_boards = array();
          
    $conn = db_connect();

    $sql = "SELECT * FROM t_user right outer join t_postinfo on t_user.f_username=t_postinfo.f_uname order by f_post_times desc, f_reply_times desc limit 0, 20";
    $result = $conn->query($sql);
    //获取行数
    $num_results=$result->num_rows;

    for ($i=0;$i<$num_results;$i++){
        //获取每一行的记录
        $m_boards[$i]=$result->fetch_object();
    }

    return $m_boards;
}

//获取每一个版面的文章信息
function getArtical($f_id){
    $Artical = array();

    $conn=db_connect();

    $sql="SELECT * from t_article where f_board_id= '".$f_id."' ";

    $result=$conn->query($sql);
    //获取行数
    $num_results=$result->num_rows;

    for ($i=0;$i<$num_results;$i++){
        //获取每一行的记录
        $Artical[$i]=$result->fetch_assoc();
    }

    return $Artical;
}

//获取每一篇文章的内容
function getAcontent($f_id){
    $artical = array();
    $review2=array();
    $conn=db_connect();

    $sql="SELECT * from t_article_content join t_article on t_article.f_id=t_article_content.f_id where t_article_content.f_id= '".$f_id."' ";

    $result=$conn->query($sql);
    
    //获取对象
    $articalObj=$result->fetch_object();

    if($articalObj->f_has_child){
        
        $review1=getArticalInfo($articalObj->f_id);
        
        $num=count($review1);
        
        for($i=0;$i<$num;$i++){
            //获取评论的子评论
            if($review1[$i]->f_has_child){
              $review2[$i]=getArticalInfo($review1[$i]->f_id);
            }else{
              $review2[$i]=array();
            }
        }
    }
    $artical[0]=$articalObj;
    $artical[1]=isset($review1)?$review1:array();
    $artical[2]=$review2;

    return $artical;
}

//获取每一篇文章的内容
function getEnable($f_username){
    $Artical = array();

    $conn=db_connect();

    $sql="SELECT * from t_postinfo where f_uname= '".$f_username."' ";

    $result=$conn->query($sql);
    //获取行数
    $num_results=$result->num_rows;

    for ($i=0;$i<$num_results;$i++){
        //获取每一行的记录
        $Artical[$i]=$result->fetch_assoc();
    }

    return $Artical;
}

//获取每一篇文章的内容
function getPost($f_parent_id, $f_title, $f_username, $f_board_id,$f_post_time, $f_ip,$content){
    $Artical = array();

    $conn=db_connect();
    $conn->autocommit(FALSE);

    $sql="INSERT into t_article (f_parent_id, f_title, f_username, f_board_id,f_post_time, f_ip) values";
    $sql.="('".$f_parent_id."','".$f_title."','".$f_username."','".$f_board_id."','".$f_post_time."','".$f_ip."')";
    $rs1=$conn->query($sql);
    $ttt=mysqli_insert_id($conn);
    $sq4="SELECT * from t_postinfo where f_uname='".$f_username."'";
    $rs4=$conn->query($sq4);
    
    if($rs4 && $rs4->num_rows){
        $t=$rs4->fetch_object();
        $post_reply=$f_parent_id ? $t->f_reply_times : $t->f_post_times;
    }


    $sq2="INSERT into t_article_content (f_id, f_content ) values (LAST_INSERT_ID(),'".$content."')";
    
    $rs2=$conn->query($sq2);
    
    if($f_parent_id){
        $sq3="UPDATE t_article set f_has_child=1 where f_id='".$f_parent_id."'";
        $rs3=$conn->query($sq3);

        $sq4="UPDATE t_postinfo set f_reply_times=$post_reply+1 where f_uname='".$f_username."'";
        $rs4=$conn->query($sq4);
    }else{
        $sq4="UPDATE t_postinfo set f_post_times=$post_reply+1 where f_uname='".$f_username."'";
        $rs4=$conn->query($sq4);
    }

    if( ($rs1 && $rs2 && $f_parent_id && $rs3 && $rs4) || ($rs1 && $rs2 && !$f_parent_id && $rs4)){
      $conn->commit();
       $ret=$ttt;
    }else{
      $conn->rollback();
       $ret=true;
    }

    $conn->close();
    return $ret;
}


/*
function last_insert_id(){
    
    $conn=db_connect();

    $sql="SELECT * from t_article order by f_id desc limit 0,1";
    
    $rs1=$conn->query($sql);
    
    $num_rows=$rs1->fetch_object();
    
    return $num_rows->f_id;
}

*/

//获取每一篇文章的详细信息，不包括内容
function getArticalInfo($f_id){
    $Artical = array();

    $conn=db_connect();

    $sql="SELECT * from t_article_content join t_article on t_article.f_id=t_article_content.f_id where t_article.f_parent_id= '".$f_id."' ";

    $result=$conn->query($sql);
    
    $num_results=$result->num_rows;

    for ($i=0;$i<$num_results;$i++){
        //获取每一行的记录
        $Artical[$i]=$result->fetch_object();
    }

    return $Artical;
}

//获取每一篇文章的详细信息，不包括内容
function getArticalInfo1($f_id){
    $Artical = array();

    $conn=db_connect();

    $sql="SELECT * from t_article_content join t_article on t_article.f_id=t_article_content.f_id where t_article.f_parent_id= '".$f_id."' ";

    $result=$conn->query($sql);
    //获取行数
    $num_results=$result->num_rows;

    for ($i=0;$i<$num_results;$i++){
        //获取每一行的记录
        $Artical[$i]=$result->fetch_object();
    }

    return $Artical;
}

//增加一个版面
function addBoard($f_name,$f_desc ){

    $f_post_time= date('y-m-d h:i:s',time());
    
    $conn=db_connect();

    $sql="INSERT into t_board (f_name,f_desc,f_created_time) values ( '".$f_name."', '".$f_desc."', '".$f_post_time."')";

    $result=$conn->query($sql);
    //获取行数
    if(!$result){
      return flase;
    }

    return true;
}

//屏蔽一个版面
function pingBoard($ping ){
    $sql=array();$result=array();
    $conn=db_connect();
    $conn->autocommit(FALSE);
    for($i=0;$i<count($ping);$i++){
        $sql[$i]="UPDATE t_board set f_enabled=0 where f_id = '".$ping[$i]."'";
        $result[$i]=$conn->query($sql[$i]);
    }
    for($i=0;$i<count($ping);$i++){
        if(!$result[$i]>0){
          $conn->rollback();
          break;
          return true;
        }
    }

    $conn->commit();
    return true;
}

//取消屏蔽一个版面
function cancleBoard($ping ){
    $sql=array();$result=array();
    $conn=db_connect();
    $conn->autocommit(FALSE);
    for($i=0;$i<count($ping);$i++){
        $sql[$i]="UPDATE t_board set f_enabled=1 where f_id = '".$ping[$i]."'";
        $result[$i]=$conn->query($sql[$i]);
    }
    for($i=0;$i<count($ping);$i++){
        if(!$result[$i]>0){
          $conn->rollback();
          break;
          return true;
        }
    }

    $conn->commit();
    return true;
}

//屏蔽一个版面
function pingArtical($ping ){
    $sql=array();$result=array();
    $conn=db_connect();
    $conn->autocommit(FALSE);
    for($i=0;$i<count($ping);$i++){
        $sql[$i]="UPDATE t_article set f_enabled=0 where f_id = '".$ping[$i]."'";
        $result[$i]=$conn->query($sql[$i]);
        if(!$result[$i]){
          $conn->rollback();
          break;
          return "1";
        }
    }
    

    $conn->commit();
    $conn->close();
    return "123";
}

//取消屏蔽一个版面
function cancleReview($ping ){
    $conn=db_connect();
        $sql="UPDATE t_article set f_enabled=1 where f_id = '".$ping[0]."'";
        $result=$conn->query($sql);
      
        $sq2="SELECT * from t_article_content where f_id = '".$ping[0]."'";
        $result1=$conn->query($sq2);
    
        if($result && $result1 ){
         
          return $result1->fetch_object();
        }

        return flase;
    }

    


//取消屏蔽一个版面
function cancleArtical($ping ){
    $sql=array();$result=array();
    $conn=db_connect();
    $conn->autocommit(FALSE);
    for($i=0;$i<count($ping);$i++){
        $sql[$i]="UPDATE t_article set f_enabled=1 where f_id = '".$ping[$i]."'";
        $result[$i]=$conn->query($sql[$i]);
    }
    for($i=0;$i<count($ping);$i++){
        if(!$result[$i]>0){
          $conn->rollback();
          break;
          return true;
        }
    }

    $conn->commit();
    return true;
}

//屏蔽一个用户
function pingUser($ping ){
    $sql=array();$result=array();
    $conn=db_connect();
   
    $conn->autocommit(FALSE);
    
    for($i=0;$i<count($ping);$i++){
        $sql[$i]="UPDATE t_postinfo set f_enabled=0 where f_uname = '".$ping[$i]."'";
        $result[$i]=$conn->query($sql[$i]);
    }

    for($i=0;$i<count($ping);$i++){
        if(!$result[$i]>0){
          $conn->rollback();
          break;
          return true;
        }
    }

    $conn->commit();
    return true;
}

//取消用户屏蔽
function cancleUser($ping){
    $sql=array();$result=array();
    $conn=db_connect();
    
    $conn->autocommit(FALSE);
    
    for($i=0;$i<count($ping);$i++){
        $sql[$i]="UPDATE t_postinfo set f_enabled=1 where f_uname = '".$ping[$i]."'";
        $result[$i]=$conn->query($sql[$i]);
    }
    
    for($i=0;$i<count($ping);$i++){
        if(!$result[$i]>0){
          $conn->rollback();
          break;
          return true;
        }
    }

    $conn->commit();
    return true;
}




?>
