$(function(){
    $.ajax({
        url: "handle.php",
        dataType: "json",
        type: "POST",
        data: { type:"adminPost" },
        success:function(data){
            //window.location.href = "index.php";
            console.log(data);
            var len=data.length,html="";
            html+="<div ><p class='span20'>用户名</p><p class='span20'>\
            名字</p><p class='span20'>发表次数</p><p class='span20'>回复次数</p><p class='span20'>总次数</p></div>";
            for(var i=0;i<len;i++){
                if(data[i].f_post_times==null  ){
                    data[i].f_post_times=0;
                }
                if(data[i].f_reply_times==null ){
                    data[i].f_reply_times=0;
                }
                    var sum=parseInt(data[i].f_reply_times)+parseInt(data[i].f_post_times);
                    html+="<div><p class='span20'>\
                    "+data[i].f_uname+"</p><p class='span20'>"+data[i].f_name+"</p><p class='span20'>"+data[i].f_post_times+"</p><p class='span20'>"+data[i].f_reply_times+"</p><p class='span20'>"+sum+"</p></div>";
            }

            $("#board").html(html);
        },
        error:function(data){
            console.log(data);
        }

    
    });
});

$("#add").on("click",function(){
    window.location.href="addBoard.php";
})