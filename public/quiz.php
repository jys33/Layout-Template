<?php

require("../includes/config.php");

$data = [
	'title' => 'Evaluación',
];

// else render form
render("page/quiz", $data);