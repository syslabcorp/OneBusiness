$(function(){
	$("input[type='text']").keydown(function(event){
		if(event.keyCode == 13) {
			event.preventDefault();
			return false;
		}
	});

	$("#form-username input[name='email']").change(function(){
		var username = $(this).val();
		//check_btn(username);
	});
});

function check_btn(username){
	if(username == ""){
		$(".submit-button").html('');
		return false;
	}
	var action = 'check_btn';
	var _token = $("input[name='_token']").val();
	$.ajax({
		url: ajax_url+'/ajax_action',
		data: {username, _token, action},
		type: "POST",
		dataType: "json",
		success: function(res){
			if(res.id){
				$(".submit-button").html(res.btn);
			}else{
				$(".submit-button").html('<button type="submit" class="btn btn-primary">Submit</button><div class="pull-right forgot-password"><a href="'+ajax_url+'/forgot_pass">Forgot Your Password</a></div>');
			}
		}
	});
}
function user_register(user_id, user_name) {
	$('body').ajaxMask();
	regStats = 0;
	regCt = -1;
	try
	{
		timer_register.stop();
	}
	catch(err)	
	{
		console.log('Registration timer has been init');
	}
	
	var limit = 4;
	var ct = 1;
	var timeout = 5000;
	
	timer_register = $.timer(timeout, function() {
		console.log("'"+user_name+"' registration checking...");
		user_checkregister(user_id,$(".user-finger").attr("finger-count"));
		if (ct>=limit || regStats==1) 
		{
			timer_register.stop();
			console.log("'"+user_name+"' registration checking end");
			if (ct>=limit && regStats==0)
			{
				$("#myModal").modal("show");
				//alert("'"+user_name+"' registration fail!");
				$('body').ajaxMask({ stop: true });
			}						
			if (regStats==1)
			{
				$(".user-finger").attr("finger-count", regCt);
				//alert("'"+user_name+"' registration success!");
				$('body').ajaxMask({ stop: true });
				$(".removeonsuccess").remove();
				window.location.replace(ajax_url+'/get_logout');
				//check_btn(user_name);
				//load('user.php?action=index');
			}
		}
		ct++;
	});
}

function user_checkregister(user_id, current) {
	var action = 'checkreg';
	var _token = $("input[name='_token']").val();
	$.ajax({
		//url :	ajax_url+"/ajax_action",
		url :	ajax_url+"/home_ajax_action",
		data: {_token, action, user_id, current},
		type :	"POST",
		success :	function(data) {
			try
			{
				var res = jQuery.parseJSON(data);	
				if (res.result)
				{
					regStats = 1;
					$.each(res, function(key, value){
						if (key=='current')
						{														
							regCt = value;
						}
					});
				}
			}
			catch(err)
			{
				alert(err.message);
			}
		}
	});
}

function check_for_btn(username, type){
	if(type == "bio_auth"){
		var action = 'btnontype';
		var _token = $("input[name='_token']").val();
		$.ajax({
			url: ajax_url+'/ajax_action',
			data: {username, _token, action},
			type: "POST",
			dataType: "json",
			success: function(res){
				$(".submit-button").html(res.btn);
			}
		});
	}else{
		$(".submit-button").html('<button type="submit" class="btn btn-primary otp-submit">Submit</button>');
	}
}

function user_register_type(user_id, user_name) {
	$('body').ajaxMask();
	regStats = 0;
	regCt = -1;
	try
	{
		timer_register.stop();
	}
	catch(err)	
	{
		console.log('Registration timer has been init');
	}
	
	var limit = 4;
	var ct = 1;
	var timeout = 5000;
	
	timer_register = $.timer(timeout, function() {
		console.log("'"+user_name+"' registration checking...");
		user_checkregister(user_id,$(".user-finger").attr("finger-count"));
		if (ct>=limit || regStats==1) 
		{
			timer_register.stop();
			console.log("'"+user_name+"' registration checking end");
			if (ct>=limit && regStats==0)
			{
				$("#myModal").modal("show");
				//alert("'"+user_name+"' registration fail!");
				$('body').ajaxMask({ stop: true });
			}						
			if (regStats==1)
			{
				$(".user-finger").attr("finger-count", regCt);
				alert("'"+user_name+"' registration success!");
				$('body').ajaxMask({ stop: true });
				check_for_btn(user_name);
				//load('user.php?action=index');
			}
		}
		ct++;
	});
}

function reset_finger(user_id){
	var _token = $("meta[name='csrf-token']").attr("content");
	var action = "remove_finger";
	$.ajax({
		url: ajax_url+'/home_ajax_action',
		data: {user_id, _token, action},
		type: "POST",
		dataType: "json",
		success: function(res){
			$(".row-"+user_id+" td.switch-reset-register").html(res.btn);
		}
	});
}

function user_register_admin(user_id, user_name) {
	$('body').ajaxMask();
	regStats = 0;
	regCt = -1;
	try
	{
		timer_register.stop();
	}
	catch(err)	
	{
		console.log('Registration timer has been init');
	}
	
	var limit = 4;
	var ct = 1;
	var timeout = 5000;
	timer_register = $.timer(timeout, function() {
		console.log("'"+user_name+"' registration checking...");
		user_checkregister(user_id,$(".row-"+user_id+" td a.user-finger").attr("finger-count"));
		if (ct>=limit || regStats==1) 
		{
			timer_register.stop();
			console.log("'"+user_name+"' registration checking end");
			if (ct>=limit && regStats==0)
			{
				$("#myModal").modal("show");
				$('body').ajaxMask({ stop: true });
			}						
			if (regStats==1)
			{
				$('body').ajaxMask({ stop: true });
				$("#successReg").modal("show");
				$(".switch-reset-register").html('<a href="javascript:;" class="btn btn-xs btn-danger" onclick="reset_finger('+user_id+')">Reset</a>');
			}
		}
		ct++;
	});
}