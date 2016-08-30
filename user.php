<?php
class User
{
    public $m_username;
    public $m_name;
    public $m_email;
    public $m_logintimes;
    public $m_lasttime;
    public $m_loginip;
    
    public $m_post_times;
    public $m_reply_times;
    public $m_total_times;
    public $m_enabled;
    
    public function __construct($username='')
    {
        if(empty($username)) return;
        $conn = db_connect();
        
        $sql = "SELECT U.*, P.f_post_times, P.f_reply_times, P.f_enabled,";
        $sql.= " P.f_post_times+P.f_reply_times AS f_total_times ";
        $sql.= " FROM t_user U INNER JOIN t_postinfo P ON U.f_username=P.f_username";        
        $sql.= " WHERE U.f_username='$username'";
        $result = $conn->query($sql);
        
        if($result && $result->num_rows > 0) {
            $row = @$result->fetch_assoc();
                        
            $this->m_username       = $row['f_username'];
            $this->m_name           = $row['f_name'];
            $this->m_email          = $row['f_email'];
            $this->m_logintimes     = $row['f_logintimes'];
            $this->m_lasttime       = $row['f_lasttime'];
            $this->m_loginip        = $row['f_loginip'];
            
            $this->m_post_times     = $row['f_post_times'];
            $this->m_reply_times    = $row['f_reply_times'];
            $this->m_total_times    = $row['f_total_times'];
            $this->m_enabled        = $row['f_enabled'];
        }
    }
    
    public static function getAll(&$users, $bMustPosted=true, $max=0)
    {
        $conn = db_connect();
        
        // 使用INNER JOIN关联只取发过贴的用户，使用LEFT JOINS可取所有的用户
        $join = $bMustPosted ? ' INNER JOIN ' : ' LEFT JOIN '; 
        $sql = "SELECT U.*, P.f_post_times, P.f_reply_times, P.f_enabled,";
        $sql.= " P.f_post_times+P.f_reply_times AS f_total_times ";
        $sql.= " FROM t_user U $join t_postinfo P ON U.f_username=P.f_username";        
        $sql.= " ORDER BY f_total_times DESC";
        $sql.= $max ? " LIMIT $max " : '';
        $result = $conn->query($sql);
        
        for ($count = 0; $row = @$result->fetch_assoc(); $count++)
        {
            $user = new User();
            
            $user->m_username       = $row['f_username'];
            $user->m_name           = $row['f_name'];
            $user->m_email          = $row['f_email'];
            $user->m_logintimes     = $row['f_logintimes'];
            $user->m_lasttime       = $row['f_lasttime'];
            $user->m_loginip        = $row['f_loginip'];
            
            $user->m_post_times     = $row['f_post_times'];
            $user->m_reply_times    = $row['f_reply_times'];
            $user->m_total_times    = $row['f_total_times'];
            $user->m_enabled        = $row['f_enabled'];
            
            $users[$count] = $user;
        }
        
        $conn->close();
        return true;
    }
    
    public static function deleteAll()
    {
        $conn = db_connect();
        
        $sql = "UPDATE t_postinfo SET f_enabled=0";
        $result = $conn->query($sql);
        
        $conn->close();
    }
    
    public static function restoreAll()
    {
        $conn = db_connect();
        
        $sql = "UPDATE t_postinfo SET f_enabled=1";
        $result = $conn->query($sql);
        
        $conn->close();
    }
    
    public static function deleteOrestore($ids)
    {
        if(empty($ids)) return;
        $sets = '';
        foreach($ids as $value) {
            $sets .= "'$value',";
        }
        $sets{strlen($sets)-1} = ' '; //移除末尾的','号
        
        $conn = db_connect();
        
        $sql = "UPDATE t_postinfo SET f_enabled=NOT f_enabled ";
        $sql.= "WHERE f_username IN ($sets)";
        $result = $conn->query($sql);
        
        $conn->close();
    }
}
?>
