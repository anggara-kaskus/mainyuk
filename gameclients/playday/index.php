<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Security-Policy"	content="connect-src * 'unsafe-inline';">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<script src="jquery-3.3.1.min.js"></script>
	<script src="nosleep.js"></script>
	<script src="wsclient.js"></script>
	<style>
		body {
			font-family: Helvetica, Arial, sans-serif;
			font-size: 12px;
			background: #f3f3f3;
			  -webkit-touch-callout: none; /* iOS Safari */
				-webkit-user-select: none; /* Safari */
				 -khtml-user-select: none; /* Konqueror HTML */
				   -moz-user-select: none; /* Firefox */
					-ms-user-select: none; /* Internet Explorer/Edge */
						user-select: none; /* Non-prefixed version, currently
											  supported by Chrome and Opera */		}

		#main {
			min-width: 300px;
			max-width: 600px;
			margin: 0 auto;
		}

		#header input {
			padding: 3px;
			line-height: 16px;
			width: 45%;
		}

		.answered {
			background: #9CF;
		}

		div.correct {
			background: #c2e8c2;
		}

		.wrong {
			background: #ffdada;
		}

		#connect-btn {
			background: #9CF;
			border: none;
		}

		#connect-btn.connected {
			background: #CCC;
			color: #666;
		}

		#question {
			padding: 5px 10px;
		}

		#button-wrapper button {
			display: block;
			padding: 10px;
			width: 100%;
			margin-bottom: 10px;
			background: #fff;
			border: 1px solid #ccc;
		}

		#button-wrapper button.correct {
			background: #383;
			font-weight: bold;
			color: #fff;
			border-color: #060;
		}

		#button-wrapper button.selected {
			background: #66C;
			font-weight: bold;
			color: #fff;
			border-color: #060;
		}

		#extra-life {
			display: none;
		}

		button#use-extra-life {
			border: 1px solid #bf3f3f;
			line-height: 1.2em;
			border-radius: 3px;
		}

		button#use-extra-life:disabled {
			border-color: #999;
			background-color: #CCC;
			color: #999;
			font-weight: bold;
		}

		input[type=button], button {
			cursor: pointer;
		}

		.hints {
			color: #666;
			background: #f7f7f7!important;
			text-align: justify;
			border: 1px solid #ccc;
			margin-bottom: 10px;
			padding: 8px;
			border-radius: 5px;
			letter-spacing: 0.2px;
			line-height: 1.5em;
    	}

		.hints strong {
			color: #333;
		}

		.hints a {
			display: block;
			color: #444;
			text-decoration: none;
			margin-bottom: 5px;
			border-bottom: 1px solid #ccc;
			padding-bottom: 5px;
		}

		#info {
			font-size: 14px;
			line-height: 18px;
			text-align: right;
		}

		#game-ui {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			background: #eee;
			box-shadow: 0 0 10px 0px #0005;
			padding: 0 5px 5px;
		}

		h3 {
			margin: 0.5em 0;
		}

		#search-terms {
			font-style: italic;
			font-size: small;
			padding: 3px;
			color: #666;
		}

		.row-btn:after {
			display: block;
			clear: both;
		}
	</style>
</head>
<body oncontextmenu="return false;" onselect="return false;" unselectable="on" onselectstart="return false;" onmousedown="return false;">
	<div id="main">
		<div id="game-ui">
			<div id="header">
				<input type="text" onclick="this.select()" id="token" value="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJ1aWQiOiI1YmEzNTY1MzAwNTRjOTJjYjZhNWE5ZTciLCJzZXMiOiIxOTU0NTQ3Njg0NWJhOWM1NWQxNjU0MDcuMzM3MDIyNTIifQ.VoGTOTCDPexA5yswDsbpvRhYx9vxTvg1DTGGvk_IydzrWTf01MVwQjNGkSFQYeOKREhFEGEv-Zk8xdMtf5ptnO_gzr41P8fbIOhSrbEdw9zBwYDUqFb_d_7yX4K-irbdI8_bQkduQQ8daiHdiSxF9wBIWcnGgsMWSSDds-zu5qQ">
				<input id="connect-btn" type="button" onclick="onConnectClick()" value="Connect">
				<br />
				<br />
			</div>
			<div id="info">
				👥 <span id="subscriber-count">0</span><span id="extra-life">0</span>
				<button id="use-extra-life" onclick="useExtraLife()">❤️</button>
			</div>
			<div id="question" class="correct">
				<h3 id="question-msg">Question Lorem ipsum dolor sit amet</h3>
				<div id="button-wrapper">
					<div class="row-btn">
						<button class="correct" onclick="startVibrate([200, 50, 200]);">Vibrate Test</button>
						<button class="toggles">&nbsp;</button>
					</div>
					<button class="selected" onclick="toggleFullScreen()">Fullscreen</button>
					<button onclick="startVibrate(1000)">Vibrate</button>
					<button onclick="init()">Initialize</button>
				</div>
			</div>
		</div>
		<div id="search-result"></div>
	</div>
</body>
</html>
