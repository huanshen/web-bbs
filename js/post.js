$(function(){

    var title,pcontent,ip,f_id,username;
    username=$("#username").text();
    //判断内容是否为空
    function check(){
        title=$.trim($("#title").val());
        pcontent=$.trim(ue.getContent());
        if(title=="" || pcontent==""){
            alert("请确认表单已输入");
            return false;
        }
        //console.log(pcontent+" "+title)
    }

    //返回上一页
    function goback(){
        window.history.back(-1);
    }

    //获取url后面的参数函数
    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]); return null;
    }

    f_id=getQueryString("f_id");

    $("#back").on("click",function(){
        goback();
    });

    //获取数据，确定其是否有权发帖
    $.ajax({
        url: "handle.php",
        dataType: "json",
        type: "POST",
        data: { type:"enable",username:username },
        success:function(data){
            //window.location.href = "index.php";
            //console.log(data[0].f_enabled);
            if(data[0].f_enabled==0){
                $("#notice").html("对不起，你无权发帖!");
                $("#title").attr("disabled","true");
                $("#pcontent").attr("disabled","true");
            }
        },
        error:function(data){
            console.log(data);
        }
    });


    $("#submit").on("click",function(){
        title=$.trim($("#title").val());
         pcontent = ue.getContent();
        console.log(pcontent);
        check();
        ip=$("#username").attr("data-ip");
        if(title!=""){
            $.ajax({
                url: "handle.php",
                dataType: "json",
                type: "POST",
                data: { type:"post",f_username:username,f_board_id:f_id, f_title:title,ip:ip,content:pcontent},
                success:function(data){
                    window.location.href = "postSucess.php";
                    console.log(data.toString());
                   
                },
                error:function(data){
                    console.log(data);
                    $("#notice").html("对不起，发帖失败，请稍后再试");
                }
            });
        }
    })
});