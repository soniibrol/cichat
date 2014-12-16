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
		<?php
		if($this->session->flashdata('login-success')){
		?>
			<div class="alert alert-success" role="alert">
				<button type="button" class="close" data-dismiss="alert">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
 					<?php echo $this->session->flashdata('login-success'); ?>
			</div>
		<?php	
		}
		?>
	</div>
	<div class="row">
		<div class="col-md-10">
			<ul class="nav nav-tabs" role="tablist" id="msgboxtitle">
				<li class="active"><a href="#messagebox" id="titlehome" role="tab" data-toggle="tab">Messagebox</a></li>
			</ul>
			<div class="tab-content" id="msgboxbody">
				<div class="tab-pane active" id="messagebox"></div>
			</div>
		</div>
		<div class="col-md-2">
			<h4>Friend List <button type="button" id="btn_add" class="btn btn-xs" title="Add Contact" data-toggle="modal" data-target="#modalAddContact">+</button></h4>
			<div id="friendlist">
				<ul class="friendlist">
					<?php
					foreach($friend as $row){
						echo '<a href="javascript:void(0)" class="friend-item" rel="'.$row['user_id'].'" title="'.$row['username'].'"><li>'.$row['name'].'</li></a>';
					}
					?>
				</ul>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div id="messagewrite">
				<div class="row">
					<div class="col-md-1">
						<b><?php echo $user['name']; ?></b><br/>
						<span style="color:green;">Online</span><br/>
						<a href="javascript:void(0)" id="btn-logout">Logout</a>
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

	<!-- Modal -->
	<div class="modal fade" id="modalAddContact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title" id="myModalLabel">Add Friend</h4>
	      </div>
	      <div class="modal-body">
	      	<div class="form-group">
	      		<input type="text" id="add_username" class="form-control" placeholder="Type username here..">
	      	</div>
	      	<div class="form-group" style="text-align:center">
	      		<button id="add_button" class="btn btn-primary" style="width:40%">Add Contact</button>
	      	</div>
	      	<div id="add_result" style="text-align:center"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" id="add_cancel" class="btn btn-default" data-dismiss="modal">Cancel</button>
	      </div>
	    </div>
	  </div>
	</div>

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-2.1.1.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
	var ssender_id = '<?php echo $user['user_id']; ?>';
	var sreceiver_id = '0';
	var aray = [];
	$(document).ready(function(){
		//store friend list to an array
		<?php
		foreach($friend as $row){
		?>
		aray.push({'id':'<?php echo $row['user_id']; ?>','username':'<?php echo $row['username']; ?>'});
		<?php
		}
		?>

		//add contact section
		$("#add_button").click(function(){
			var uname = $("#add_username").val();
			$.ajax({
				url: base_url+'user/add_contact',
				dataType: 'json',
				data: {requester_id:ssender_id, username:uname},
				type: 'POST',
				success: function(data){
					if(data.status==true){
						$("#add_result").html("Kontak berhasil ditambahkan.");
						setInterval(function(){ location.reload(); }, 2000);
					}else{
						$("#add_result").html("Kontak gagal ditambahkan. Username tidak valid.");
					}
				}
			});
		});

		$("#add_cancel").click(function(){
			$("#add_result").html("");
			$("#add_username").val('');
		});
		

		$(".friend-item").dblclick(function(){
			var user_id = $(this).attr("rel");
			var username = $(this).attr("title");
			if($("#chat_"+username).length == 0){
				$("#msgboxtitle").append('<li class="start-chat" title="'+username+'" rel="'+user_id+'" id="li_'+username+'"><a href="#chat_'+username+'" role="tab" data-toggle="tab"><span id="title_'+username+'">'+username+'</span>   <span class="close-chat" title="'+username+'">x</span></a></li>');
				$("#msgboxbody").append('<div class="tab-pane msgboxbody-item" id="chat_'+username+'"></div>');
				sreceiver_id = user_id;
			}
		});

		$("body").on("click",".close-chat",function(){
			var username = $(this).attr("title");
			$("#li_"+username).remove();
			$("#chat_"+username).remove();
			$("#titlehome").click();
		});

		$("body").on("click",".start-chat",function(){
			var user_id = $(this).attr("rel");
			var username = $(this).attr("title");
			$("#title_"+username).html(" "+username+" ");
			sreceiver_id = user_id;
		});

		$("#btn_send").click(function(){
			var msg = $("#message").val();
			if(msg.length > 0){
				$.ajax({
					url: base_url+"chat/send",
					data: {message:msg, sender_id: ssender_id, receiver_id: sreceiver_id},
					type: "POST"
				}).done(function(){
					$("#message").val("");
					load_last_message("sender", sreceiver_id,"DESC");
				});
			}
		});

		$("#btn-logout").click(function(){
			if(confirm('Apakah Anda akan keluar dari aplikasi?')){
				window.location.href= base_url+"user/logout";
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
				//$("#messagebox").append(returndata);

				if(who == 'receiver'){
					//pertama cek dulu
					var uid = data.result.sender_id;
					var posisi = getIndex(uid);
					var uname = aray[posisi].username;

					if($("#chat_"+uname).length > 0){
						$("#chat_"+uname).append(returndata);
						$("#li_"+uname).html('<a href="#chat_'+uname+'" role="tab" data-toggle="tab"><span id="title_'+uname+'"><b>'+uname+'</b></span> <span class="close-chat" title="'+uname+'">x</span></a>');	
					}else{
						$("#msgboxtitle").append('<li class="start-chat" title="'+uname+'" rel="'+uid+'" id="li_'+uname+'"><a href="#chat_'+uname+'" role="tab" data-toggle="tab"><span id="title_'+uname+'"><b>'+uname+'</b></span> <span class="close-chat" title="'+uname+'">x</span></a></li>');
						$("#msgboxbody").append('<div class="tab-pane msgboxbody-item" id="chat_'+uname+'"></div>');
						$("#chat_"+uname).append(returndata);
					}

					$('<audio id="chatAudio"><source src="notify.ogg" type="audio/ogg"><source src="'+base_url+'assets/audio/alert/alert.mp3" type="audio/mpeg"></audio>').appendTo('body');
					$('#chatAudio')[0].play();
				}else{
					//pertama cek dulu
					var uid = receiver_id;
					var posisi = getIndex(uid);
					var uname = aray[posisi].username;

					$("#chat_"+uname).append(returndata);
				}

				//in order to scroll automatically
				$("#chat_"+uname).animate({scrollTop:$("#chat_"+uname).height()}, "slow");
			}
		});
	}

	function getIndex(keyword){
		var rs = -1;
		for(var i=0;i<aray.length;i++){
			if(aray[i].id==keyword){
				rs = i;
			}
		}

		return rs;
	}
	</script>
</div>
</body>
</html>