$(function(){

	//获取url后面的参数函数
	function getQueryString(name) {
	    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	    var r = window.location.search.substr(1).match(reg);
	    if (r != null) return unescape(r[2]); return null;
	}

	//返回上一页
	function goback(){
		window.history.back(-1);
	}

    //事件绑定,
    //返回按钮
	$("#back").on("click",function(){
		goback();
	});

    //获取url参数值
    var f_id=getQueryString('f_id');

    //发表文章按钮
    $("#post").on("click",function(){
        window.location.href="post.php?f_id="+f_id;
    });

	//判断是否支持本地存储
	if(window.localStorage){
		var bbs_desc = localStorage.getItem("bbs_desc"+f_id);
		var bbs_name = localStorage.getItem("bbs_name"+f_id);
		console.log(bbs_desc);
	}else{

	}

	$("#desc").html(bbs_desc);
	$("#title").html(bbs_name+"版面");

    //发起ajax请求数据
	articleSql();
    function articleSql(){
        $.ajax({
            url: "handle.php",
            dataType: "json",
            type: "POST",
            data: { type:"article",f_id:f_id },
            success:function(data){
                
                console.log(data);
                var len=data.length,html="";
                
                //判断有没有数据，有的话加上标题
                if(len>0){
                    html+="<div ><p class='span20'>屏蔽</p><p  class='span20'>文章标题</p><p class='span20'>作者</p><p class='span30'>时间</p><p class='span10'>屏蔽</p></div>";
                }
                
                for(var i=0;i<len;i++){
                    if(  data[i].f_parent_id==0){
                        var title=encodeURI(data[i].f_title),
                            post_time=encodeURI(data[i].f_post_time),
                            username=encodeURI(data[i].f_username);
                        url=encodeURI("adminBartical.php?f_id="+data[i].f_id+"&f_post_time="+post_time+"&f_username="+username+"&f_title="+title);
                        html+="<div ><p class='span20'><input type='checkbox'  data-id='"+data[i].f_id+"' data-f_enabled='"+data[i].f_enabled+"' class='ping'></p><p class='span20'><a href='"+url+"'>"+data[i].f_title+"</a></p><p class='span20'>"+data[i].f_username+"</p><p class='span30'>"+data[i].f_post_time+"</p><p class='span10'>"+data[i].f_enabled+"</p></div>";
                    }
                }
                //将数据返回到页面
                $("#board").html(html);
            },
            error:function(data){
                alert("发生错误，稍后再试");
                console.log(data);
            }
        });
    }

/**************************************************/
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

/**************************************************************/
var ping,num;
$("#ping").on("click",function(){
    ping=[];num=0;
    console.log("t");
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
    console.log(ping);
    //只有在屏蔽数大于0的时候发起请求，节省资源
    if(ping.length>0){
        $.ajax({
            url: "handle.php",
            dataType: "json",
            type: "POST",
            data: { type:"pingArtical",ping:ping },
            success:function(data){
                articleSql();
                console.log(data);
            },
            error:function(data){
                console.log(data);
            }
        });
    }
});

/**************************************************************/
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
            data: { type:"cancleArtical",ping:cping },
            success:function(data){
                articleSql();
            },
            error:function(data){
                console.log(data);
            }
        });
    }
})

})
