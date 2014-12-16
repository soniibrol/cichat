<!DOCTYPE html>
<html>
<head>
	<title>Cichat</title>
	<meta charset="UTF-8">

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/myStyle.css">
</head>
<body>
<div class="container">
	<div class="row">
		<h1>Cichat With Codeigniter</h1>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div id="messagebox">

			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div id="messagewrite">
				<div class="row">
					<div class="col-md-1">
						<b>Remo Deri</b><br/>
						<span style="color:green;">Online</span>
					</div>
					<div class="col-md-9">
						<textarea name="message" id="message" class="form-control"></textarea>
					</div>
					<div class="col-md-2">
						<button type="button" id="btn_send" class="btn" style="width:100%;">Send</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-2.1.1.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
	var ssender_id = '2';
	var sreceiver_id = '1';
	$(document).ready(function(){
		$("#btn_send").click(function(){
			var msg = $("#message").val();
			if(msg.length > 0){
				$.ajax({
					url: base_url+"chat/send",
					data: {message:msg, sender_id: ssender_id, receiver_id: sreceiver_id},
					type: "POST"
				}).done(function(){
					$("#message").val("");
					load_last_message("sender",sreceiver_id,"DESC");
				});
			}
		});

		setInterval(function(){
                     load_last_message("receiver", ssender_id, "ASC");},1000);
	});

	function load_last_message(who, receiver_id, order){
		$.ajax({
			dataType: "json",
			url: base_url+"chat/load_last_message/"+who+"/"+receiver_id+"/"+order,
			success: function(data){
				var returndata = '';
				returndata += '<b>' + data.result.sender + '</b> : ' + data.result.message + ' <span style="color:#dddddd"><small><i>on ' + data.result.time + '</i></small></span><br/>';
				$("#messagebox").append(returndata);
				if(who == 'receiver'){
					$('<audio id="chatAudio"><source src="notify.ogg" type="audio/ogg"><source src="'+base_url+'assets/audio/alert/alert.mp3" type="audio/mpeg"></audio>').appendTo('body');
					$('#chatAudio')[0].play();
				}
			}
		});
	}
	</script>
</div>
</body>
</html>