<?php

require("../includes/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
	// log out current user, if any
	logout();

	flash('success', 'Se ha desconectado correctamente.');

	// redirect user
	redirect('index.php');
}
// redirect user
redirect('index.php');