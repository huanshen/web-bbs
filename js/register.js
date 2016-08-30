     //检测表单数据
     var username,pwd,name,email;
    function check(){
        username=$("#username").val(),
        pwd=$("#pwd").val(),
        rpwd=$("#repeat_pwd").val(),
        name=$("#name").val(),
        email=$("#email").val();

        var reg=/^[a-zA-Z_][\w]*$/;
        if(!reg.test(username)){
            alert("用户名只能是下划线和字母开头，且由数字和字母下划线组成");
            return false;
        }
        if(username=="" || pwd=="" ||name==""||email==""){
            alert("请确认表单填写完整");
            return false;
        }
        if(pwd!=rpwd){
            alert("两次输入密码不一致");
            return false;
        }
        if(pwd.length<6 ||pwd.length>16){
            alert("密码位数在6-16之间");
            return false;
        }
        if(!(/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/.test(email))){
            alert("邮箱格式错误");
            return false;
        }
    }

    $("#reset").on("click",function(){
         $("#username").val("");
         $("#pwd").val("");
         $("#name").val("");
         $("#repeat_pwd").val("");
         $("#email").val("");
    })
    //提交数据
    $("#submit").on("click",function(){
        var ip=$("#username").attr("data-ip");
        console.log(ip);
        check();
        console.log(username+" "+ip+" "+pwd+" "+email+" "+name)
        $.ajax({
            url: "handle.php",
            dataType: "json",
            type: "POST",
            data: { type:"register",username:username,ip:ip,pwd:pwd,email:email,name:name},
            success:function(data){
                if(data==2){
                    alert('该用户名已被使用，请换一个');
                }
                if(data==1){
                    alert('注册有误，请稍后再试');
                }
                if(data==0){
                    alert("注册成功，转到登入页面");
                    window.location.href = "login.php";
                }
                if(data==3){
                    alert('系统繁忙，请稍后再试.');
                }
            },
            error:function(data){
                alert('系统繁忙，请稍后再试.');
                
            }
        });
    })