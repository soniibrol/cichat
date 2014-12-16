<!DOCTYPE html>
<html>
<head>
	<title>CiChat - Login</title>
	<meta charset="UTF-8">

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/test.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div id="box"></div>
		</div>
	</div>

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-2.1.1.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
	$(document).ready(function(){
		$("#box").click(function(){
			$("#box").animate({width:"20px", height:"20px"},500);
		});
	});

	var jarak = 50;
	$(document).keydown(function(e){
		switch(e.which){
			case 37:
				$("#box").css("left", $("#box").offset().left - jarak);
				$("#box").css("width", "+=10px");
				$("#box").css("-webkit-animation","bayangan 2s");
				break;
			case 38:
				$("#box").css("top", $("#box").offset().top - jarak);
				$("#box").css("height", "+=10px");
				$("#box").css("-webkit-animation","bayangan 2s");
				break;
			case 39:
				$("#box").css("left", $("#box").offset().left + jarak);
				$("#box").css("width", "+=10px");
				$("#box").css("-webkit-animation","bayangan 2s");
				break;
			case 40:
				$("#box").css("top", $("#box").offset().top + jarak);
				$("#box").css("height", "+=10px");
				$("#box").css("-webkit-animation","bayangan 2s");
				break;
		}
	});
	</script>
</body>
</html>