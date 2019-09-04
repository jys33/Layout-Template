<?php

require("../includes/config.php");

if (!array_key_exists('user_id', $_SESSION)) {
    redirect('/');
}

$data = [
	'title' => 'Evaluación',
];

// else render form
render("page/quiz", $data);