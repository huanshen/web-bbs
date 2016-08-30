$(function(){
	$.ajax({
        url: "handle.php",
        dataType: "json",
        type: "POST",
        data: { type:"board" },
        success:function(data){
        	//window.location.href = "index.php";
        	console.log(data);
        	var len=data.length,html="";

        	for(var i=0;i<len;i++){
        		if(data[i].f_enabled>0){
        			html+="<li><a href='board.php?f_id="+data[i].f_id+"'>"+data[i].f_name+"</a></li>";
        			
        			if(window.localStorage){
						localStorage.setItem("bbs_desc"+data[i].f_id, data[i].f_desc);
						localStorage.setItem("bbs_name"+data[i].f_id, data[i].f_name);
					}

        		}
        	}

        	$("#board").html(html);
        },
        error:function(data){
        	console.log(data);
        }

    
    });
});