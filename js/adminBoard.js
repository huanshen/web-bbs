$(function(){
    boardSql();
});

function boardSql(){
    $.ajax({
        url: "handle.php",
        dataType: "json",
        type: "POST",
        data: { type:"board" },
        success:function(data){
            console.log(data);
            var len=data.length,html="";
            if(len>0){
                html+="<div ><p class='span10'>屏蔽</p><p class='span15'>名称</p><p class='span30'>\
                        简介</p><p class='span30'>创建时间</p><p class='span15'>是否屏蔽</p></div>";
            }
            
            for(var i=0;i<len;i++){
                    html+="<div><p class='span10'><input type='checkbox' data-id='"+data[i].f_id+"' data-f_enabled='"+data[i].f_enabled+"' class='ping'></p><p class='span15'><a href='adminArtical.php?f_id="+data[i].f_id+"'>\
                    "+data[i].f_name+"</a></p><p class='span30' >"+data[i].f_desc+"</p><p class='span30'>"+data[i].f_created_time+"</p><p class='span15'>"+data[i].f_enabled+"</p></div>";
                    
            }

            $("#board").html(html);
        },
        error:function(data){
            console.log(data);
        }
    });
}

$("#add").on("click",function(e){
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
})

var ping,num;
$("#ping").on("click",function(){
    ping=[];num=0;
    $("input[type='checkbox']").each(function(){
        if($(this).prop("checked")==true){
            
            if($(this).attr("data-f_enabled")=="0"){
                alert("您选择的用户里有已被屏蔽的，请重新选择");
                return false;
            }

            if($(this).attr("data-f_enabled")>"0"){
                ping[num]=$(this).attr("data-id");
                num++;
            }
        }
    });

    //只有在屏蔽数大于0的时候发起请求，节省资源
    if(ping.length>0){
        $.ajax({
            url: "handle.php",
            dataType: "json",
            type: "POST",
            data: { type:"pingboard",ping:ping },
            success:function(data){
                boardSql();
            },
            error:function(data){
                console.log(data);
            }
        });
    }
});


//点击取消屏蔽按钮
var cping,cnum=0;
$("#cancle").on("click",function(){
    cping=[];cnum=0;
    $("input[type='checkbox']").each(function(){
        //只对选中的做出判断
        if($(this).prop("checked")==true){

            if($(this).attr("data-f_enabled")>"0"){
                alert("您选择的用户里有未屏蔽的，请重新选择");
                return false;
            }

            if($(this).attr("data-f_enabled")=="0"){
                cping[cnum]=$(this).attr("data-id");
                cnum++;
            }
        }
    });

    //只有在屏蔽数大于0的时候发起请求，节省资源
    if(cping.length>0){
        console.log(cping)
        $.ajax({
            url: "handle.php",
            dataType: "json",
            type: "POST",
            data: { type:"cancleBoard",ping:cping },
            success:function(data){
                boardSql();
            },
            error:function(data){
                console.log(data);
            }

        
        });
    }
})