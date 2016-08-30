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
        		html+="<p class='center'><span class='span-margin'>文章标题</span><span class='span-margin1'>作者</span><span class='span-margin'>时间</span></p>";
        	}
        	
        	for(var i=0;i<len;i++){
        		if(data[i].f_enabled>0 && data[i].f_parent_id==0){
        			var title=encodeURI(data[i].f_title),
        				post_time=encodeURI(data[i].f_post_time),
        				username=encodeURI(data[i].f_username);
        			console.log(title);
        			url=encodeURI("article.php?f_id="+data[i].f_id+"&f_post_time="+post_time+"&f_username="+username+"&f_title="+title);
        			html+="<p class='center'><span class='span-margin'><a href='"+url+"'>"+data[i].f_title+"</a></span><span class='span-margin1'>"+data[i].f_username+"</span><span class='span-margin'>"+data[i].f_post_time+"</span></p>";
        		}
        	}
        	//将数据返回到页面
        	$("#boardlist").html(html);
        },
        error:function(data){
        	console.log(data);
        }
    });
})
