<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<script src="jquery-3.3.1.min.js"></script>
	<script src="nosleep.js"></script>
	<script src="wsclient.js"></script>
	<style>
		body {
			font-family: Helvetica, Arial, sans-serif;
			font-size: 12px;
			background: #eee;
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
			padding: 10px;
			margin-bottom: 30px;
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

		button#use-extra-life {
			background-color: #bf3f3f;
			color: #fff;
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
	</style>
</head>
<body oncontextmenu="return false;" onselect="return false;" unselectable="on" onselectstart="return false;" onmousedown="return false;">
	<div id="main">
		<div id="header">
			<input type="text" onclick="this.select()" id="token" value="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJ1aWQiOiI1YmEzNTY1MzAwNTRjOTJjYjZhNWE5ZTciLCJzZXMiOiIxOTU0NTQ3Njg0NWJhOWM1NWQxNjU0MDcuMzM3MDIyNTIifQ.VoGTOTCDPexA5yswDsbpvRhYx9vxTvg1DTGGvk_IydzrWTf01MVwQjNGkSFQYeOKREhFEGEv-Zk8xdMtf5ptnO_gzr41P8fbIOhSrbEdw9zBwYDUqFb_d_7yX4K-irbdI8_bQkduQQ8daiHdiSxF9wBIWcnGgsMWSSDds-zu5qQ">
			<input id="connect-btn" type="button" onclick="onConnectClick()" value="Connect">
		</div>
		<br />
		<br />
		<div id="info">👥 <span id="subscriber-count">0</span> ❤️ <span id="extra-life">0</span></div>
		<div id="question" class="correct">
			<h3 id="question-msg">Question Lorem ipsum dolor sit amet</h3>
			<div id="button-wrapper">
				<button class="correct" onclick="	startVibrate([200, 50, 200]);">Vibrate Test</button>
				<button class="selected" onclick="toggleFullScreen()">Fullscreen</button>
				<button onclick="startVibrate(1000)">Vibrate</button>
				<button onclick="init()">Initialize</button>
				<button id="use-extra-life" onclick="useExtraLife()">Use extra life</button>
			</div>
		</div>
		<div id="search-result"></div>
	</div>
</body>
</html>
