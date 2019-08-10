<?php

require("../includes/config.php");

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && filter_var($_POST['id'], FILTER_VALIDATE_INT))
{
	getPost($_POST['id']);

	$delete_result = query('DELETE FROM post WHERE id = ?', $_POST['id']);
	if ($delete_result) {
		flash('success', 'Eliminado correctamente.');
		redirect('index.php');
	}
}

render('error/404', ['message' => 'un error ha ocurrido.', 'title' => 'Error']);