function submitfn(){

	var type=0,
        username = $("#username").val(),
		passwd=$("#passwd").val();

	if(username=="" || passwd==""){
		alert("请确认表单已输入")
	}

    if($("#checkbox").prop("checked")==true){
        type=1;
    }

	$.ajax({
        url: "handle.php",
        dataType: "json",
        type: "POST",
        data: { type:"login",username: username, passwd: passwd,tag:type },
        success:function(data){
        	//window.location.href = "index.php";
            if($("#checkbox").prop("checked")==true){
                window.location.href = "adminBoard.php";
            }else{
                window.location.href = "index.php";
            }
        },
        error:function(data){
        	alert("用户名或密码错误");
        	console.log(data);
        }
    });

}

$("#register").on("click",function(){
    window.location.href="register.php"
})

	