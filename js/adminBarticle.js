$(function(){
	var f_board_id;
/******************************************/
//评论模板

	var reTemp='<div class="review">\
						<p class="pfont" >{f_username}</p>\
						<p class="reContent" id="{f_id2}">{f_content}</p>\
						<textarea class="none1 none" rows="2"></textarea> \
						<p class="pfont inBlock">发表于：<span>{f_post_time}</span></p><p class="fright hover1 pfont inBlock" \
						data-parentId="{f_id1}"data-username="{f_username1}">回复</p><p class="fright hover1 pfont inBlock" data-id="{f_id3}">{pingbi}</p>\
					<div class="sonreview {f_id}"></div>\
					</div>',
		//评论的子评论的模板
		reTemp2='<div><p class="fontcolor inline">{f_username} </p><p class="inline">{f_title}：</p><p class="inline" id="{f_id3}">{f_content}</p>\
		<br><textarea class="none1 none" rows="2"></textarea> \
		<p class="pfont inBlock">{f_post_time}</p><p class="fright hover2 pfont inBlock" data-parentId="{f_parent_id}"\
		data-username="{f_username1}">回复</p><p class="fright hover1 pfont inBlock" data-id="{f_id2}">{pingbi}</p></div>';


	String.prototype.replacer = function (obj) {
        var fullStr = this.toString();
        for (var key in obj) {
        	if(key=="f_username")
            fullStr = fullStr.replace('{' + key + '1}', obj[key]);
        	if(key=="f_id"){
	            fullStr = fullStr.replace('{' + key + '1}', obj[key]);
	        	fullStr = fullStr.replace('{' + key + '2}', obj[key]);
	        	fullStr = fullStr.replace('{' + key + '}', obj[key]);
	        	fullStr = fullStr.replace('{' + key + '3}', obj[key]);
	        }
	        if(key=="f_content" && (obj["f_enabled"]==0)){
	        	obj[key]="该评论已被屏蔽";
	        	fullStr = fullStr.replace('{pingbi}', "取消屏蔽");
	        }
	        if(obj["f_enabled"]>0){
	        	fullStr = fullStr.replace('{pingbi}', "屏蔽");
	        }
	        fullStr = fullStr.replace('{' + key + '}', obj[key]);
        }
        return fullStr;
    };			
/******************************************/
	//获取url后面的参数函数
	function getQueryString(name) {
	    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	    var r = window.location.search.substr(1).match(reg);
	    if (r != null) return unescape(r[2]); return null;
	}

	//获取url参数值
	var f_id=getQueryString('f_id'),
		f_title=decodeURI(getQueryString('f_title')),
		f_post_time=decodeURI(getQueryString('f_post_time')),
		f_username=decodeURI(getQueryString('f_username'));

	//显示标题时间作者
	$("#title").html(f_title);
	$("#poster").html("作者："+f_username);
	$("#post-time").html("发表时间："+f_post_time);
    
/******************************************/
    //发起ajax请求文章数据
    abaSQL();
    function abaSQL(){
		$.ajax({
	        url: "handle.php",
	        dataType: "json",
	        type: "POST",
	        data: { type:"content",f_id:f_id },
	        success:function(data){
	        	//window.location.href = "index.php";
	        	console.log(data);

	        	//显示文章内容
	        	$("#content").html(data[0].f_content);
	        	f_board_id=data[0].f_board_id;
	        	f_parent_id=data[0].f_id;
	        	var div="";
	        	for(var i=0;i<data[1].length;i++){
	        		div+=reTemp.replacer(data[1][i]);
	        	}
	        	//显示文章内容的评论
	        	$("#reviewlist").prepend(div);
	        	
	        	//显示文章内容的评论的评论
	        	$(".sonreview").each(function(i){
	        		var div2="";
	        		var len=data[2][i].length
	        		for(var j=0;j<len;j++){
	        			div2+=reTemp2.replacer(data[2][i][j]);
	        		}
	        		$(this).html(div2);
	        	});
	        },
	        error:function(data){
	        	console.log(data);
	        }
	    });
	}


/******************************************/
	

	//获取回复人的姓名
	var username=$("#username").text();

/******************************************/
	//返回上一页
	function goback(){
		window.history.back(-1);
	}
	$("#back").on("click",function(){
		goback();
	});

/******************************************/
	//点击回复按钮
	$("#reviewlist").on("click",function(e){
		//显示输入框
		var that=e.target,title="",ip,content,child;
		
		$(that).prev().prev().show();
		
		var c=$.trim($(that).prev().prev().val());
		if($(that).html()=="回复" && c!=""){
			
			var classname=$(that).attr('class');
				f_parent_id=$(that).attr("data-parentId");
				content=$(that).prev().prev().val();
			if(classname.indexOf("hover")){
				title="";
				child=1;
			}
			if(classname.indexOf("hover1")){
				title="";
				child=1;
			}
			if(classname.indexOf("hover2")){
				var t=$(that).attr("data-username");
				title="回复 "+ t;
				child=1;
			}

			ip=$("#username").attr("data-ip");
			console.log(f_parent_id+" "+title+" "+ip+" "+f_board_id+" "+content);
			
			//将评论提交到数据库
			$.ajax({
		        url: "handle.php",
		        dataType: "json",
		        type: "POST",
		        data: { type:"review",f_username:username,f_board_id:f_board_id, f_parent_id:f_parent_id, f_title:title,ip:ip,content:content,child:child },
		        success:function(data){
		        	//成功的话插入评论
		        	console.log(data);


		        	var div='<div><p class="fontcolor inline">'+username+' </p><p class="inline">'+title+'：</p><p class="inline" id="'+data[0]+'">'+content+'</p>\
		<br><textarea class="none1 none" rows="2"></textarea> \
		<p class="pfont inBlock">'+data[1]+'</p><p class="fright hover2 pfont inBlock" data-parentId="'+f_parent_id+'"\
		data-username="'+username+'">回复</p><p class="fright hover1 pfont inBlock" data-id="'+data[0]+'">屏蔽</p></div>';
		        	$("."+f_parent_id).append(div);
		        	$(that).prev().prev().hide();
		        	$(that).prev().prev().val("");
		        	console.log(div);
		        },
		        error:function(data){
		        	console.log(data);
		        	alert("回复失败，请稍后再试");
		        }
		    });
		}
});
	
/*************************************************************************/
	$("#hui").on("click",function(e){
		//显示输入框
		var that=e.target,title="",ip,content,child;
		
		var c=$.trim($("#message").val());
		
		var classname=$(that).attr('class');
			content=$("#message").val();

			ip=$("#username").attr("data-ip");
			//console.log(f_parent_id+" "+title+" "+ip+" "+f_board_id+" "+content);

			//将评论提交到数据库
			$.ajax({
		        url: "handle.php",
		        dataType: "json",
		        type: "POST",
		        data: { type:"review",f_username:username,f_board_id:f_board_id, f_parent_id:f_parent_id, f_title:title,ip:ip,content:content,child:child },
		        success:function(data){
		        	
			        $.ajax({
				        url: "handle.php",
				        dataType: "json",
				        type: "POST",
				        data: { type:"content1",f_id:f_parent_id },
				        success:function(data){
	        	
			        	//成功的话插入评论
			        	console.log(data);
			        	len=data.length-1;
			        	var div='<div class="review">\
							<p class="pfont" >'+data[len].f_username+'</p>\
							<p class="reContent" id="'+data[len].f_id+'">'+data[len].f_content+'</p>\
							<textarea class="none1 none" rows="2"></textarea> \
							<p class="pfont inBlock">发表于：<span>'+data[len].f_post_time+'</span></p><p class="fright hover1 pfont inBlock" \
							data-parentId="'+data[len].f_id+'"data-username="'+data[len].f_username+'">回复</p><p class="fright hover1 pfont inBlock" data-id="'+data[len].f_id+'">屏蔽</p>\
						<div class="sonreview '+data[len].f_id+'"></div>\
						</div>';
			        	$("#messagebox").before(div);
			        	$("#message").val("");

				        },
				        error:function(data){
				        	console.log(data);
				        }
				    });
		        },
		        error:function(data){
		        	console.log(data);
		        	alert("回复失败，请稍后再试");
		        }
		    });
		
});

/******************************************/
	//点击屏蔽按钮
	var preview;
	$("#reviewlist").on("click",function(e){
		//显示输入框
		preview=[];
		var that=e.target,title="",ip,content,child;
		
		if($(that).html()=="屏蔽" ){
			
			preview[0]=$(that).attr("data-id");
			
			//将评论提交到数据库
			$.ajax({
		        url: "handle.php",
		        dataType: "json",
		        type: "POST",
		        data: { type:"pingReview",ping:preview},
		        success:function(data){
		        	//成功的话插入评论
		        	console.log(preview[0]);
		        
		        	$("#reviewlist").find("p").each(function(){
		        		if($(this).attr("id")==preview[0]){
		        			console.log($(this).html())
		        			$(this).html("该评论已被屏蔽");

		        		}
		        	})
		        	$(that).html("取消屏蔽");
		        },
		        error:function(data){
		        	console.log(data);
		        	alert("回复失败，请稍后再试");
		        }
		    });
		}
	/***************************************************/
		if($(that).html()=="取消屏蔽" ){
			
			var classname=$(that).attr('class');
				preview[0]=$(that).attr("data-id");
			
				preview[1]=username;
			//将评论提交到数据库
			$.ajax({
		        url: "handle.php",
		        dataType: "json",
		        type: "POST",
		        data: { type:"cancleReview",ping:preview},
		        success:function(data){
		        	//成功的话插入评论
		        	console.log(data)
		        	$("#reviewlist").find("p").each(function(){
		        		if($(this).attr("id")==preview[0]){
		        			$(this).html(data.f_content);
		        		}
		        	})
		        	$(that).html("屏蔽");
		        },
		        error:function(data){
		        	console.log(data);
		        	alert("屏蔽失败，请稍后再试");
		        }
		    });
		}
});

})
