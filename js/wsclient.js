function createClient(channel)
{
	var options = {debug: true, automaticOpen: false};
	var webSocket = new ReconnectingWebSocket(WS_URL + channel, null, options);

	webSocket.onopen = function(openEvent) {
		$('#disconnected').hide();
	};
	webSocket.onclose = function (closeEvent) {
		console.log("WebSocket CLOSE: " + JSON.stringify(closeEvent, null, 4));
		$('#disconnected').show();
	};
	webSocket.onerror = function (errorEvent) {
		console.log("WebSocket ERROR: " + JSON.stringify(errorEvent, null, 4));
		$('#disconnected').show();
	};
	webSocket.onmessage = function (messageEvent) {
		var wsMsg = messageEvent.data;
		console.log("Received message: " + wsMsg);
		try {
			var json = $.parseJSON(wsMsg);
			switch (json.type) {
				case 'matched' : displayEnemy();
					break;
				case 'question' : displayQuestion(json);
					break;
				case 'answer' :
					if (json.correctAnswer == json.myAnswer) {
						$('.selected').addClass('correct').removeClass('selected');
					} else {
						$('.selected').addClass('wrong').removeClass('selected');
						$('.option' + json.index + '_' + json.correctAnswer).addClass('correct').removeClass('selected');
					}
			}
		} catch(e) {
		}
	};

	function sendMessage(msg) {
		if (webSocket && webSocket.readyState == WebSocket.OPEN) {
			webSocket.send(msg);
		}
	}
	return webSocket;
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
	// sendMessage(JSON.stringify(wsData));
	$('#extra-life').prop('disabled', true);
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

function findOpponent() {
	$.get('./matchmaker.php', function (result) {
		try {
			var json = $.parseJSON(result);
			if (json.success && json.status == 'waiting') {
				$('#play-liga').html('<i class="fa fa-hourglass-o" aria-hidden="true"></i> Mencari lawan ...');
			} else if (json.status == 'matched') {
				matched();
			}
		} catch (e) {
			console.error(e);
		}
	});

	// $('#other-info, #user-info').hide();
	// $('#game-ui').show();
}

function matched() {
	$('#play-liga').addClass('matched').html('<i class="fa fa-check-circle-o" aria-hidden="true"></i> Memulai permainan');
}

var gameId;
var token;
var index;

function displayQuestion(question) {
	gameId = question.gameId;
	token = question.token;
	index = question.index;

	$('#other-info, #user-info').hide();
	var html = '<div class="row"><div class="col question">' + question.question + '</div></div>';
	for (i in question.options) {
		option = question.options[i];
		html += '<div class="row"><a href="javascript:answer(\'' + i +'\');" class="col options option'+ index + '_' + i +'">' + option + '</a></div>';
	}
        $('#game-ui').html(html).show();
}

function answer(answer) {
	lastAnswer = answer;
        $.post('./answer.php', {gameId: gameId, answer: answer, token: token, index: index}, function (result) {
                try {
                        var json = $.parseJSON(result);
                        if (json.success) {
                                $('.option' + index + '_' + json.myAnswer).addClass('selected');
                        }
                } catch (e) {
                        console.error(e);
                }
        });
}
