<?php include './global/header.php';?>

	<div class="container content">
		<div class="row">
			<div id="logo" class="col-8 offset-2">
				<h1>Main Yuk!</h1>
				<!--<p>Sambil belajar dapet duit</p>-->
			</div>
		</div>
	</div>
	<div class="row" id="login-button">
		<a class="col-8 offset-2" href="<?php echo $authUrl; ?>">
			<img src="./img/google.svg" />
			Masuk dengan akun Google
		</a>
	</div>

<?php include './global/footer.php';?>
