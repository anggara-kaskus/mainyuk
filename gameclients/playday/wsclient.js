var webSocket   = null;
var ws_protocol = null;
var ws_hostname = null;
var ws_port     = null;
var ws_endpoint = null;

var pingInterval;
var waitOpponentTimeout;
var timer;

var questions = [];

var answerTime = 0;
var enemyScore = 0;
var myScore = 0;
var answerIndex = 0;
var enemyAnswered = [];
var myAnswered = [];
var timerSecond = 0;

function onConnectClick() {
	var ws_protocol = 'wss';
	var ws_hostname = 'api.playday.live';
	var ws_port     = false;
	var ws_endpoint = '/trivia';
	openWSConnection(ws_protocol, ws_hostname, ws_port, ws_endpoint);
}

function onDisconnectClick() {
	webSocket.close();
}

function openWSConnection(protocol, hostname, port, endpoint) {
	var webSocketURL = null;
	hostname = port ? hostname + ':' + port : hostname;
	webSocketURL = protocol + "://" + hostname + endpoint;
	console.log("openWSConnection::Connecting to: " + webSocketURL);
	try {
		webSocket = new WebSocket(webSocketURL);
		webSocket.onopen = function(openEvent) {
			$('#connect-btn').val('Connected').addClass('connected').prop('disabled', true);
			console.log("Logging in...");
			wsData = {
				ct: 10,
				token: $('#token').val(),
				recon: true
			};
			sendMessage(JSON.stringify(wsData));
			startVibrate([100, 50, 100, 50, 100]);
			$('#header').slideUp();
		};
		webSocket.onclose = function (closeEvent) {
			console.log("WebSocket CLOSE: " + JSON.stringify(closeEvent, null, 4));
			$('#connect-btn').val('Connect').removeClass('connected').prop('disabled', false);
			startVibrate([1000]);
			$('#header').show();
			onConnectClick();
		};
		webSocket.onerror = function (errorEvent) {
			console.log("WebSocket ERROR: " + JSON.stringify(errorEvent, null, 4));
			$('#connect-btn').val('Connect').removeClass('connected').prop('disabled', false);
			startVibrate([1000]);
			$('#header').show();
		};
		webSocket.onmessage = function (messageEvent) {
			var wsMsg = messageEvent.data;
			var jsonData = JSON.parse(wsMsg);

			switch (jsonData.ct) {
				case 10:
					console.log(jsonData.msg);
					if (jsonData.last) {
						displayQuestion(jsonData.last);
					}
					console.debug(jsonData);
				break;

				case 20:
					// console.debug(jsonData.frm + ': ' + jsonData.msg);
				break;

				case 31:
					console.debug(jsonData);
					displayQuestion(jsonData);
				break;

				case 32:
					console.debug(jsonData);
					if (jsonData.ack) {
						$('#question').addClass('answered');
					}
				break;

				case 33:
					console.debug(jsonData);
					displayResult(jsonData);
				break;

				case 34:
					alert('Game over!');
				break;

				case 35:
					$('#extra-life').hide();
				break;

				case 36:
					updateSubscriberCount(jsonData);
				break;

				case 41:
					// {"cnt":2,"ct":41}
					console.log("Event 41 count: " + jsonData.cnt);
				break;

				default:
					console.log("Unhandled code: " + jsonData.ct);
					console.debug(jsonData);
			}
		};
	} catch (exception) {
		console.error(exception);
	}
}

function onSendClick() {
	if (webSocket.readyState != WebSocket.OPEN) {
		console.error("webSocket is not open: " + webSocket.readyState);
		return;
	}
	var msg = document.getElementById("message").value;
	sendMessage(msg);
}

function sendMessage(msg) {
	if (webSocket && webSocket.readyState == WebSocket.OPEN) {
		webSocket.send(msg);
	}
}

function displayQuestion(jsonData) {
	$('#question-msg').html(jsonData.q);
	$('#question').removeClass('answered correct wrong');
	$('#extra-life').html(jsonData.ext);
	var buttons = '';
	var options = [];
	for (i in jsonData.o) {
		option = jsonData.o[i];
		buttons += '<button onclick="sendAnswer(\'' + jsonData.id + '\', \'' + option.id + '\', this)">'+ option.val +'</button>';
		options.push(option.val);
	}
	$('#button-wrapper').html(buttons);
	startVibrate([200, 50, 200]);
	search(jsonData.q, options);
}

function displayResult(jsonData) {
	$('#question-msg').html(jsonData.q);
	$('#extra-life').html(jsonData.ext);
	$('#question').removeClass('answered');

	var buttons = '';
	for (i in jsonData.o) {
		option = jsonData.o[i];
		btnClass = option.id == jsonData.ans ? 'correct' : '';
		buttons += '<button class="' + btnClass + '">'+ option.val +' ('+ option.cnt +')</button>';
	}
	$('#button-wrapper').html(buttons);

	if (!jsonData.in) {
		$('#question').addClass('wrong');
		if (jsonData.ext) {
			$('#extra-life').show();
		}
	} else {
		$('#question').addClass('correct');
	}
}

function sendAnswer(questionid, answer, obj) {
	wsData = {
		ans: answer,
		ct: 32,
		id: questionid
	};
	console.log(wsData);
	sendMessage(JSON.stringify(wsData));
	$('#button-wrapper button').prop('disabled', true);
	$(obj).addClass('selected');
}

function updateSubscriberCount(jsonData) {
	$('#subscriber-count').html(jsonData.cnt);
}

function useExtraLife() {
	wsData = {
		code: 1,
		ct: 35
	};
	console.log(wsData);
	sendMessage(JSON.stringify(wsData));
	$('#use-extra-life').prop('disabled', true);
}

function toggleFullScreen() {
	var doc = window.document;
	var docEl = doc.documentElement;

	var requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
	var cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;

	if(!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
		requestFullScreen.call(docEl);
	}
	else {
		cancelFullScreen.call(doc);
	}
}

function search(question, options) {
	question = removeStopWords(question);
	keyword = question;
	if (options.length > 0) {
		keyword += ' ("' + options.join('" OR "') + '")';
	}

	$.ajax({
		url: "https://www.googleapis.com/customsearch/v1",
		jsonp: "callback",
		dataType: "jsonp",
		data: {
			key: 'AIzaSyDpXZ9gW7sAg6D2gBVmKDOSW4gHPolXdXM',
			q: keyword,
			cx: '007157765884662800022:ztqozwlnhcg'
		},
		success: function(response) {
			console.log(response);
			var result = '<div id="search-terms">' + response.queries.request[0].searchTerms + '</div>';
			for (i in response.items) {
				item = response.items[i];
				result += '<div class="hints"><a>'+ item.htmlTitle +'</a>'+ item.htmlSnippet +'</div>';
			}
			marginTop = Math.ceil($('#game-ui').height() + 10) + 'px';
			$('#search-result').css({'margin-top': marginTop}).html(result);
		}
	});
}

function removeStopWords(question)
{
	var stopWords = [
		'ini',
		'merupakan',
		'adalah',
		'yang',
		'bukan',
		'ke',
		'di',
		'kecuali',
		'termasuk',
		'berikut',
		'oleh',
		'tidak'
	];
	for (var i = stopWords.length - 1; i >= 0; i--) {
		pattern = new RegExp('\\b' + stopWords[i] + '\\b', 'i');
		question = question.replace(pattern, '');
	}
	question = question.replace(/([^a-z0-9=+*:\/-])/gi, ' ').replace(/\s+/g, ' ');
	return $.trim(question);
}

function startVibrate(duration) {
	navigator.vibrate(duration);
}

var noSleep = new NoSleep();

function init() {
	noSleep.enable();
	toggleFullScreen();
	screen.orientation.lock('portrait');
	document.removeEventListener('touchstart', init, false);
}

document.addEventListener('touchstart', init, false);
