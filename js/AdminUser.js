$(function(){
    getsql();
});

function getsql(){
    $.ajax({
        url: "handle.php",
        dataType: "json",
        type: "POST",
        data: { type:"adminUser" },
        success:function(data){
            //window.location.href = "index.php";
            console.log(data);
            var len=data.length,html="";
            if(len>0){
                html+="<div ><p class='span10'>屏蔽</p><p class='span20'>用户名</p><p class='span20'>\
                名字</p><p class='span20'>发表次数</p><p class='span15'>回复次数</p><p class='span15'>是否屏蔽</p></div>";
            }
            for(var i=0;i<len;i++){
                if(data[i].f_post_times==null  ){
                    data[i].f_post_times=0;
                }
                if(data[i].f_reply_times==null ){
                    data[i].f_reply_times=0;
                }
                if(data[i].f_enabled==null ){
                    data[i].f_enabled="-";
                }   
                if(data[i].f_tag==0){
                    html+="<div><p class='span10'><input type='checkbox' data-f_enabled='"+data[i].f_enabled+"' data-username='"+data[i].f_username+"' class='ping'></p><p class='span20'>\
                        "+data[i].f_username+"</p><p class='span20'>"+data[i].f_name+"</p><p class='span20'>"+data[i].f_post_times+"</p>\
                        <p class='span15'>"+data[i].f_reply_times+"</p><p class='span15'>"+data[i].f_enabled+"</p></div>";
                }
            }

            $("#board").html(html);
        },
        error:function(data){
            console.log(data);
        }
    });
}

$("#add").on("click",function(){
    window.location.href="addBoard.php";
})
var t=0;
$("#all").on("click",function(){
    t++;
    if(t%2){
        $("input[type='checkbox']").each(function(){
        $(this).prop("checked",true);
        $('#all').val("取消全选");
        })
    }else{
        $("input[type='checkbox']").each(function(){
        $(this).prop("checked",false);
        $('#all').val("全选");
        })
    }
});

//点击屏蔽按钮
var cping,cnum=0;
$("#ping").on("click",function(){
    cping=[];cnum=0;
    $("input[type='checkbox']").each(function(){
        //只对选中的做出判断
        if($(this).prop("checked")==true){
            if($(this).attr("data-f_enabled")=="-"){
                alert("您选择的用户里有尚未发帖的，无法屏蔽");
                return false;
            }

            if($(this).attr("data-f_enabled")=="0"){
                alert("您选择的用户里有已被屏蔽的，请重新选择");
                return false;
            }

            if($(this).attr("data-f_enabled")>"0"){
                cping[cnum]=$(this).attr("data-username");
                cnum++;
            }
        }
    })

    //只有在屏蔽数大于0的时候发起请求，节省资源
    if(cping.length>0){
        $.ajax({
            url: "handle.php",
            dataType: "json",
            type: "POST",
            data: { type:"pingUser",ping:cping },
            success:function(data){
                getsql();
            },
            error:function(data){
                console.log(data);
            }

        
        });
    }
})

//点击取消屏蔽按钮
var ping,num;
$("#cancle").on("click",function(){
    num=0;ping=[];
    $("input[type='checkbox']").each(function(){
        //只对选中的做出判断
        if($(this).prop("checked")==true){
            if($(this).attr("data-f_enabled")=="-"){
                alert("您选择的用户里有尚未发帖的，无法取消屏蔽");
                return false;
            }

            if($(this).attr("data-f_enabled")>"0"){
                alert("您选择的用户里有未屏蔽的，请重新选择");
                return false;
            }

            if($(this).attr("data-f_enabled")=="0"){
                ping[num]=$(this).attr("data-username");
                num++;
            }
        }
    })

    //只有在屏蔽数大于0的时候发起请求，节省资源
    if(ping.length>0){
        $.ajax({
            url: "handle.php",
            dataType: "json",
            type: "POST",
            data: { type:"cancleUser",ping:ping },
            success:function(data){
                getsql();
            },
            error:function(data){
                console.log(data);
            }

        
        });
    }
})