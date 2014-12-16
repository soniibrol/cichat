<!DOCTYPE html>
<html>
<head>
	<title>CiChat - Login</title>
	<meta charset="UTF-8">

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/myStyle.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div id="login-box">
				<div id="login-box-title">CiChat Login</div>
				<?php
				if($this->session->flashdata('register-success')){
				?>
					<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">
							<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
						</button>
 	 					<?php echo $this->session->flashdata('register-success'); ?>
					</div>
				<?php	
				}
				?>
				<?php
				if($this->session->flashdata('login-failed')){
				?>
					<div class="alert alert-warning" role="alert">
						<button type="button" class="close" data-dismiss="alert">
							<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
						</button>
 	 					<?php echo $this->session->flashdata('login-failed'); ?>
					</div>
				<?php	
				}
				?>
				<form id="login-form" class="form-horizontal" role="form" method="POST" action="">
					<div class="form-group">
						<label for="username" class="col-sm-4 control-label">Username</label>
						<div class="col-sm-8">
							<input type="text" name="username" id="username" class="form-control" required="required" placeholder="Username">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-4 control-label">Password</label>
						<div class="col-sm-8">
							<input type="password" name="password" id="password" class="form-control" required="required" placeholder="Password">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-8 col-sm-offset-4">
							<button type="submit" id="login" class="btn">Login</button>
							<button type="button" id="register" class="btn btn-primary">Register</button>
						</div>
					</div>
				</form>
				<form id="register-form" class="form-horizontal" role="form" method="POST" action="register">
					<div class="form-group">
						<label for="reg-email" class="col-sm-4 control-label">Email</label>
						<div class="col-sm-8">
							<input type="email" name="reg-email" id="reg-email" class="form-control" required="required" placeholder="Email">
						</div>
					</div>
					<div class="form-group">
						<label for="reg-name" class="col-sm-4 control-label">Full Name</label>
						<div class="col-sm-8">
							<input type="text" name="reg-name" id="reg-name" class="form-control" required="required" placeholder="Full Name">
						</div>
					</div>
					<div class="form-group">
						<label for="reg-username" class="col-sm-4 control-label">Username</label>
						<div class="col-sm-8">
							<input type="text" name="reg-username" id="reg-username" class="form-control" required="required" placeholder="Username">
						</div>
					</div>
					<div class="form-group">
						<label for="reg-password" class="col-sm-4 control-label">Password</label>
						<div class="col-sm-8">
							<input type="password" name="reg-password" id="reg-password" class="form-control" required="required" placeholder="Password">
						</div>
					</div>
					<div class="form-group">
						<label for="reg-password-confirmation" class="col-sm-4 control-label">Confirm Password</label>
						<div class="col-sm-8">
							<input type="password" name="reg-password-confirmation" id="reg-password-confirmation" class="form-control" required="required" placeholder="Confirm Password">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-8">
							<button type="submit" id="reg-submit" class="btn btn-primary">Submit</button>
							<button type="button" id="reg-back" class="btn">Back to Login</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<canvas id="myCanvas" width="578" height="200"></canvas>
		</div>
	</div>

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-2.1.1.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	/************/
	/** CANVAS **/
	/************/
	/*var canvas = document.getElementById('myCanvas');
	var context = canvas.getContext('2d');

	context.beginPath();
	context.moveTo(0, 0);
	context.lineTo(150, 0);
	context.moveTo(0, 10);
	context.lineTo(140, 10);
	context.moveTo(0, 20);
	context.lineTo(130, 20);
	context.moveTo(0, 30);
	context.lineTo(120, 30);
	context.moveTo(0, 40);
	context.lineTo(110, 40);
	context.moveTo(0, 50);
	context.lineTo(100, 50);
	context.moveTo(0, 60);
	context.lineTo(90, 60);
	context.moveTo(0, 70);
	context.lineTo(80, 70);
	context.moveTo(0, 80);
	context.lineTo(70, 80);
	context.moveTo(0, 90);
	context.lineTo(60, 90);
	context.stroke();*/
		

	/************/
	/** CANVAS **/
	/************/

	var base_url = '<?php echo base_url(); ?>';
	$(document).ready(function(){
		$("#reg-submit").prop("disabled",true);
		$("#register-form").hide();
		setInterval(function(){
                    	$("#login-box-title").animate({opacity:0.50},1000).animate({opacity:1.00},1000);
                 	},1000);

		$("#register").click(function(){
			$("#login-form").hide();
			$("#login-box-title").html("CiChat Register");
			$("#register-form").show();
		});

		$("#reg-back").click(function(){
			$("#register-form").hide();
			$("#login-box-title").html("CiChat Login");
			$("#login-form").show();
		});

		$("#reg-username").keyup(function(){
			checking_user($(this).val());
		});

		$("#reg-password-confirmation").keyup(function(){
			if($(this).val()==$("#reg-password").val()){
				$("#reg-submit").prop("disabled",false);
			}else{
				$("#reg-submit").prop("disabled",true);
			}
		});
	});

	function checking_user(uname){
		var datareturn = true;
		$.ajax({
			url: base_url+'user/checking_user/'+uname,
			data: {username: uname},
			dataType: 'json',
			type: 'POST'
		}).done(function(data){
			datareturn = data.username;
			if(datareturn==false){
				alert('Username telah terpakai, harap menggantinya.');
			}
		});
	}
	</script>
</body>
</html>