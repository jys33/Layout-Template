<?php

require("../includes/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// log out current user, if any
	logout();

	// redirect user
	redirect('index.php');
}
redirect('index.php');