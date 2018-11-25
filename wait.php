<?php include './global/header.php';?>
	<script type="text/javascript">
		$(document).ready(function() {
			channel = '/ws/public/<?php echo $currentUser['userChannel'];?>';
			ws = createClient(channel);
			ws.open();
		});
	</script>
	<div class="row" id="user-info">
		<div class="col-8 offset-2">
			<i class="fa fa-user-circle-o" aria-hidden="true"></i>
		</div>
		<div class="col-8 offset-2"><?php echo explode('@', $currentUser['email'])[0];?></div>
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
		<div class="row"><span id="timer">10</span></div>
		<div id="question-wrapper">
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

		<div class="row" id="scores">
			<div class="col-6" id="myScore">Skor Anda: 0</div>
			<div class="col-6" id="enemyScore">Skor Lawan: 0</div>
		</div>
	</div>

	<div id="match-found">
		<div class="row">
			<div class="col-4 offset-1">
				<div>
					<i class="fa fa-user-circle-o" aria-hidden="true"></i>
				</div>
				<div id="match-found-username">ebureg</div>
			</div>

			<div class="col-2">
				<h3 style="margin-top:1em;">vs</h3>
			</div>

			<div class="col-4">
				<div>
					<i class="fa fa-user-circle-o" aria-hidden="true"></i>
				</div>
				<div id="match-found-opponent">ebureg</div>
			</div>
		</div>
		<div class="row" id="get-ready-wrapper">
			<span id="get-ready">Bersiap...</span>
		</div>
		<div class="row" id="done-wrapper">
			<button id="done" class="col col-10 col-offset-1" onclick="done()">Tutup</button>
		</div>
	</div>
<?php include './global/footer.php';?>
