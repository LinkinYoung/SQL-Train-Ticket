$(document).ready(function() {
    $('#signup').click(userSignup);
});

function userSignup() {
    $.ajax({
			type: "POST",
			url: "API/signup.php",
			dataType:"json",
			data:{
                "userName": $('#userName').val(),
                "pwd": $('#pwd').val(),
                "name": $('#name').val(),
                "id": $('#id').val()
            },
			success: function(data){
				if(data.result == "success"){
					Materialize.toast("注册成功！3秒后跳转", 3000);
					setTimeout(function() {
						location.href='index.html';
					}, 3000)
				}
				else Materialize.toast("注册失败 请选择另一个用户名", 3000);
			},
			error: function (XMLHttpRequest, textStatus, errorThrown){    
			}
		});
}