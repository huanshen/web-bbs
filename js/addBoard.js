$(function(){

    var title,pcontent,ip,f_id,username;
    username=$("#username").text();
    //判断内容是否为空
    function check(){
        title=$.trim($("#title").val());
        pcontent=$.trim($("#contain").val());
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

    $("#back").on("click",function(){
        goback();
    });

    


    $("#submit").on("click",function(){
         pcontent = $("#contain").val();
        check();
        ip=$("#username").attr("data-ip");
        
        $.ajax({
        url: "handle.php",
        dataType: "json",
        type: "POST",
        data: { type:"addboard",f_name:title,f_desc:pcontent},
        success:function(data){
            $("#notice").html("添加成功")
            console.log(data.toString());
           
        },
        error:function(data){
            console.log(data);
            $("#notice").html("添加失败，请稍后再试");
        }
    });
    })
});