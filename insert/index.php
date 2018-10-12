<style type="text/css">
	label {
		width: 200px;
		display: inline-block;
	}
	input[type=text] {
		width: 300px;
	}
</style>
<form method="post">
	<div class="r"><label>	Question</label> <input type="text" name="question" value=""></div>
	<div class="r"><label>	Option A</label> <input type="text" name="option[A]" value=""></div>
	<div class="r"><label>	Option B</label> <input type="text" name="option[B]" value=""></div>
	<div class="r"><label>	Option C</label> <input type="text" name="option[C]" value=""></div>
	<div class="r"><label>	Option D</label> <input type="text" name="option[D]" value=""></div>
	<div class="r"><label>	Correct Answer</label> <input type="text" name="answer" value=""></div>
	<div class="r"><label>	&nbsp;</label> <input type="submit" value="Submit"></div>
</form>
<?php

if (!empty($_POST)) {
	require_once dirname(__DIR__) . '/db/db.php';
	$options = array_filter($_POST['option']);
	$insertData = [
		'question' => $_POST['question'],
		'options' => json_encode($options),
		'answer' => $_POST['answer'],
	];

	if (insert('trivia', $insertData)) {
		echo "Inserted";
	} else {
		echo "Failed";
	}
}
