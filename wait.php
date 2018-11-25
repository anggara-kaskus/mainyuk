<?php include './global/header.php';?>
	<script type="text/javascript">
		$(document).ready(function() {
			channel = '/ws/public/<?php echo $currentUser['userChannel'];?>';
			ws = createClient(channel);
			ws.open();
		});
	</script>
	<div class="row" id="user-info">
		<div class="col-8 offset-2" id="user-avatar">
			<i class="fa fa-user-circle-o" aria-hidden="true"></i>
		</div>
		<div class="col-8 offset-2" id="user-avatar"><?php echo explode('@', $currentUser['email'])[0];?></div>
	</div>


	<div id="other-info">
		<div class="row">
			<div class="col info">
				<i class="fa fa-money money" aria-hidden="true"></i> Rp 0
			</div>
		</div>
		<div class="row">
			<div class="col info">
				<i class="fa fa-star star" aria-hidden="true"></i> 0
			</div>
			<div class="col info">
				<i class="fa fa-heart heart" aria-hidden="true"></i> 1
			</div>
		</div>
		<div class="row">
			<a class="col" href="javascript:findOpponent()" id="play-liga">Main Liga</a>
		</div>
	</div>

	<div id="game-ui">
		<div class="row">
			<div class="col question">
				Question Lorem ipsum dolor sit amet?
			</div>
		</div>
		<div class="row">
			<a href="#" class="col options">Option 1</a>
		</div>
		<div class="row">
			<a href="#" class="col options">Option 2</a>
		</div>
		<div class="row">
			<a href="#" class="col options">Option 3</a>
		</div>
		<div class="row">
			<a href="#" class="col options">Option 4</a>
		</div>
	</div>

<?php include './global/footer.php';?>
