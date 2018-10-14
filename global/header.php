<?php
$pageTitle = empty($pageTitle) ? '' : 'Sambil belajar dapet duit';
?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="mobile-web-app-capable" content="yes" />
	<title>Main Yuk! - <?php echo $pageTitle;?></title>
	<link href="https://fonts.googleapis.com/css?family=Carter+One" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href="./css/style.css?_=<?php echo time();?>" rel="stylesheet">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<script type="text/javascript">
		var WS_URL = '<?php echo WS_URL;?>';
	</script>
	<script src="./js/jquery-3.3.1.min.js"></script>
	<!-- <script src="./js/nosleep.js"></script> -->
	<script src="./js/rws.js"></script>
	<script src="./js/wsclient.js?_=<?php echo time();?>"></script>
</head>
<body oncontextmenu="return false;" onselect="return false;" unselectable="on" onselectstart="return false;" onmousedown="return false;">
	<nav class="navbar navbar-dark fixed-top">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<button class="navbar-toggler blink" id="disconnected" type="button" onclick="alert('Jaringan terputus!')">
			<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
		</button>
		<button class="navbar-toggler fullscreen" type="button" onclick="init();return false;">
			<i class="fa fa-arrows-alt" aria-hidden="true"></i>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="/how-to-play">Cara Bermain</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="/faq">Pertanyaan</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="/about">Tentang Main Yuk!</a>
				</li>
				<?php if(!empty($access_token)):?>
				<li class="nav-item active">
					<a class="nav-link" href="?logout">Keluar</a>
				</li>
				<?php endif;?>
			</ul>
		</div>
	</nav>

	<div id="main">
