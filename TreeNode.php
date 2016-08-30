<?php
class TreeNode
{ 
    // 成员变量
    public $m_board_id;
    public $m_id;
    //public $m_parent_id;
    public $m_has_child;
    public $m_title;
    public $m_username;     // 用户名
    public $m_user_name;    // 用户姓名
    public $m_post_time;
    public $m_ip;
    //public $m_enabled;
    
    public $m_childlist;    // 孩子节点
    public $m_depth;        // 节点深度
    
    // 构造函数
    // 不仅设置成员变量，更重要的是递归设置其孩子节点
    public function __construct($board_id, $id, $has_child, $title, 
                                $username, $user_name, 
                                $post_time, $ip, $depth, $expands, $expanded=true, $force_expanded=false)
    { 
        $this->m_board_id   = $board_id;
        $this->m_id         = $id;
        $this->m_has_child  = $has_child;
        $this->m_title      = $title;
        $this->m_username   = $username;
        $this->m_user_name  = $user_name;
        $this->m_post_time  = $post_time;
        $this->m_ip         = $ip;
        
        $this->m_childlist  = array();
        $this->m_depth      = $depth;
                
        // 如果有孩子节点，将孩子节点找出来放入成员变量$m_childlist数组中    
        $conn = db_connect();
        
        $sql = "SELECT A.*, U.f_name FROM t_article A, t_user U ";
        $sql .= "WHERE A.f_username=U.f_username AND A.f_parent_id=$id ";
        $sql .= " AND A.f_board_id=$board_id AND A.f_enabled ";
        $sql .= " ORDER BY A.f_post_time";
        $result = $conn->query($sql);
        
        if(!($has_child && ($expanded || $force_expanded))) return;
                
        for ($count = 0; $row = @$result->fetch_assoc(); $count++)
        {
            $expanded = ($force_expanded || $expands[ $row['f_id'] ] == true);
          
            $this->m_childlist[$count] 
                = new TreeNode($board_id, $row['f_id'], $row['f_has_child'],
                        $row['f_title'], $row['f_username'], 
                        $row['f_name'], $row['f_post_time'], 
                        $row['f_ip'], $depth + 1, $expands, $expanded, $force_expanded);
        }
    } // end function __construct


    // 显示函数, 树节点必须负责显示自己
    // $row 为行号，用于交错显示不同的背景色
    function display($row, $force_expanded=false)
    {
        // 如果是空的根节点则不显示
        if($this->m_depth > -1)  
        {
            // 交错行的背景色
            $color = ($row % 2) ? '#cccccc' : '#ffffff';
            echo "<tr><td bgcolor='$color'>";
            
            // 根据贴子的深度进行缩进
            echo "<img src='images/spacer.gif' height='22'
                width='" . 22 * $this->m_depth . "' alt='' valign='bottom' />";
            
            // 根据是否展开显示+或-号图片，或者没有回贴时显示空白图片
            if ( !$force_expanded && $this->m_has_child && sizeof($this->m_childlist))
            // 在讨论区主页, 具有回贴且回贴是展开的
            {
                // 展开的，显示-号图片以便折叠
                echo "<a href='default.php?collapse={$this->m_id}#{$this->m_id}'>";
                echo "<img src='images/minus.gif' valign='bottom' 
                    height='22' width='22' alt='折叠' border='0' /></a>";
            }
            else if(!$force_expanded && $this->m_has_child)
            {
                // 折叠的，显示+号图片以便展开
                echo "<a href='default.php?expand={$this->m_id}#{$this->m_id}'>";
                echo "<img src='images/plus.gif' valign='bottom'
                    height='22' width='22' alt='展开' border='0'></a>";
            }
            else
            {
                // 显示空白图片
                echo "<img src='images/spacer.gif' height='22' width='22'
                           alt='' valign='bottom' />";
            }

            // 显示贴子标题
            echo " <a name='{$this->m_id}'>
                <a href='view.php?bid={$this->m_board_id}&id={$this->m_id}'>
                {$this->m_title} - {$this->m_user_name} - " 
                . reformat_date($this->m_post_time) . '</a>';
            echo "</td></tr>\n";
            
            // 增加行号
            $row++;
        }
        
        // 显示孩子节点
        $num_children = sizeof($this->m_childlist);
        for($i = 0; $i < $num_children; $i++)
        {
            $row = $this->m_childlist[$i]->display($row, $force_expanded);
        }
        
        return $row;
    }
}; // end class TreeNode

function expand_all(&$expands)
{
    $conn = db_connect();
        
    $sql = "SELECT f_id FROM t_article WHERE f_has_child=true AND f_enabled";
    $result = $conn->query($sql);
    if(!$result) return;
    
    $num = $result->num_rows;
    for ($i = 0; $i < $num; $i++) {
        $row = $result->fetch_row();
        $expands[$row[0]] = true;
    }
}

class Article
{
    // 成员变量
    public $m_board_id;
    public $m_board_name;
    public $m_id;
    public $m_parent_id;
    public $m_has_child;
    public $m_title;
    public $m_username;     // 用户名
    public $m_user_name;    // 用户姓名
    public $m_post_time;
    public $m_ip;
    public $m_content;
    public $m_picture;
    public $m_enabled;
    
    public function __construct($id=0)
    {
        if($id <= 0)
            return;
        $conn = db_connect();
        
        $sql = "SELECT A.*, C.f_content, C.f_picture, U.f_name ";
        $sql .= " FROM t_article A, t_article_content C, t_user U ";
        $sql .= " WHERE U.f_username=A.f_username ";
        $sql .= " AND A.f_id=C.f_id AND A.f_id=$id AND A.f_enabled";
        $result = $conn->query($sql);
        if(!$result) return;    
        
        $row = $result->fetch_assoc();
        $this->m_board_id   = $row['f_board_id'];
        $this->m_board_name = '';
        $this->m_id         = $row['f_id'];
        $this->m_parent_id  = $row['f_parent_id'];;
        $this->m_has_child  = $row['f_has_child'];
        $this->m_title      = $row['f_title'];
        $this->m_username   = $row['f_username'];
        $this->m_user_name  = $row['f_name'];
        $this->m_post_time  = $row['f_post_time'];
        $this->m_ip         = $row['f_ip'];
        $this->m_enabled    = $row['f_enabled'];
        
        $this->m_content    = $row['f_content'];
        $this->m_picture    = $row['f_picture'];
    }
    
    public function post()
    {
        if(empty($this->m_board_id)
                || empty($this->m_title) || empty($this->m_content))
            return false;
        if(empty($this->m_parent_id))
            $this->m_parent_id = 0;
        $this->m_ip         = $_SERVER['REMOTE_ADDR']; // 获取客户端的IP
        $this->m_has_child  = 0;
        $this->m_username   = $_SESSION['uid'];
            
        $conn = db_connect();
        $conn->autocommit(FALSE);   // 设置为非自动提交，使用事务处理
        
        // 插入标题部分
        $sql = "INSERT INTO t_article ";
        $sql .= "(f_parent_id, f_has_child, f_title, f_username, f_board_id, f_post_time, f_ip) VALUES";
        $sql .= "({$this->m_parent_id}, {$this->m_has_child}, '{$this->m_title}', ";
        $sql .= "'{$this->m_username}', {$this->m_board_id}, now(), '{$this->m_ip}')";
        $rs1 = $conn->query($sql);
        
        // 插入内容部分
        $sql = "INSERT INTO t_article_content (f_id, f_content, f_picture) VALUES";
        $sql .= "(last_insert_id(), '{$this->m_content}', '{$this->m_picture}')";         
        $rs2 = $conn->query($sql);
        
        // 更改父贴的f_has_child字段
        $sql = "UPDATE t_article SET f_has_child=1 WHERE f_id={$this->m_parent_id}";
        $rs3 = $conn->query($sql);
        
        // 更改发贴信息表
        $sql = "SELECT * FROM t_postinfo WHERE f_username='{$this->m_username}'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) { // 该用户已经发过贴
            $post_reply = $this->m_parent_id ? 'f_reply_times' : 'f_post_times';
            $sql = "UPDATE t_postinfo SET $post_reply=$post_reply+1";
            $sql.= " WHERE f_username='{$this->m_username}'";
        }
        else {
            $sql = "INSERT INTO t_postinfo(f_username, f_post_times, f_reply_times)";
            $post_reply = $this->m_parent_id ? '0, 1' : '1, 0';
            $sql.= "VALUES('{$this->m_username}', $post_reply)";
        }
        $rs4 = $conn->query($sql);
        
        if($rs1 && $rs2 && $rs3 && $rs4) {
            $conn->commit();    // 全部成功，提交
            $ret = true;
        }
        else {
            $conn->rollback();  // 至少有一个SQL执行失败，回滚
            $ret = true;
        }
        
        $conn->close();
        return $ret;
    }
    
    public static function getAll(&$articles, $keyword='')
    {
        $conn = db_connect();
        
        $sql = "SELECT A.*, B.f_name FROM t_article A, t_board B WHERE A.f_board_id=B.f_id";
        if(!empty($keyword))
            $sql .= " AND A.f_title LIKE '%$keyword%'";
        $result = $conn->query($sql);        
        if(!$result) return false;
        
        for ($count = 0; $row = @$result->fetch_assoc(); $count++)
        {
            $article = new Article();
            $article->m_board_id   = $row['f_board_id'];
            $article->m_board_name = $row['f_name'];
            $article->m_id         = $row['f_id'];
            $article->m_parent_id  = $row['f_parent_id'];;
            $article->m_has_child  = $row['f_has_child'];
            $article->m_title      = $row['f_title'];
            $article->m_username   = $row['f_username'];
            $article->m_user_name  = $row['f_name'];
            $article->m_post_time  = $row['f_post_time'];
            $article->m_ip         = $row['f_ip'];
            $article->m_enabled    = $row['f_enabled'];
            
            $articles[$count] = $article;
        }
        
        $conn->close();
        return true;
    }
    
    public static function deleteAll($ids)
    {
        $ins = makeIns4SQL($ids);
        if(empty($ins)) return;
        
        $conn = db_connect();
        
        $sql = "UPDATE t_article SET f_enabled=0";
        $result = $conn->query($sql);
        
        $conn->close();
    }
    
    public static function restoreAll($ids)
    {
        $ins = makeIns4SQL($ids);
        if(empty($ins)) return;
        
        $conn = db_connect();
        
        $sql = "UPDATE t_article SET f_enabled=1";
        $result = $conn->query($sql);
        
        $conn->close();
    }
    
    public static function deleteOrestore($ids)
    {
        $ins = makeIns4SQL($ids);
        if(empty($ins)) return;
        
        $conn = db_connect();
        
        $sql = "UPDATE t_article SET f_enabled=NOT f_enabled ";
        $sql.= "WHERE f_id IN ($ins)";
        $result = $conn->query($sql);
        
        $conn->close();
    }
};
?>
