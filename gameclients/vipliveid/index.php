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
			<input type="text" onclick="this.select()" id="token" value="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1dWlkIjoiNGQ3ZGYyN2QtNjQwNi00Mzc3LTg1NzEtMWFhZTYyMmE0ZWZkIiwibmFtZSI6bnVsbCwiZW1haWwiOiJlYnVyZWdAZ21haWwuY29tIiwicGhvbmUiOm51bGwsInBob3RvX3VybCI6bnVsbCwiY3JlYXRlZF9hdCI6IjIwMTgtMDktMjAgMDI6MzQ6MzkiLCJ1cGRhdGVkX2F0IjoiMjAxOC0wOS0yMCAwMjozNDozOSIsImRlbGV0ZWRfYXQiOm51bGwsInJlZmVyZW50X3V1aWQiOm51bGwsImNvbGxlY3RlZF9leHRyYV9saXZlcyI6MCwidXNlZF9leHRyYV9saXZlcyI6MCwiZ2FtZXNfcGxheWVkIjowLCJ3aW5fY291bnQiOjAsImhpZ2hfc2NvcmUiOjAsImlkZW50aXRpZXMiOlt7InV1aWQiOiI0NmU2ZTMwYS1hNTYzLTRhMjctYmUyNS04ZmJmNDZmYWI2ZGUiLCJ1c2VyX3Byb2ZpbGVfdXVpZCI6IjRkN2RmMjdkLTY0MDYtNDM3Ny04NTcxLTFhYWU2MjJhNGVmZCIsInR5cGUiOiJzb2NpYWwiLCJwcm92aWRlciI6ImZhY2Vib29rLmNvbSIsInZlcmlmaWVkIjp0cnVlLCJpZGVudGlmaWVyIjoiMTk4OTE2ODg2MTE0MjQ5MyIsImNoYWxsZW5nZSI6bnVsbCwiaXNfYWN0aXZhdGVkIjp0cnVlLCJjcmVhdGVkX2F0IjoiMjAxOC0wOS0yMCAwMjozNDozOSIsInVwZGF0ZWRfYXQiOm51bGx9XSwiaWF0IjoxNTM3NDEwODgxLCJleHAiOjE1Njg5NDY4ODEsImlzcyI6ImFwcHRpdml0eWxhYi5jb20ifQ.WdMCMTc_dHSh0Vna0hzWAMR5qXYR51tUCYdTvVJA9oY">
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
