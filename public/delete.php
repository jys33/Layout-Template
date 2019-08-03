<?php

require("../includes/config.php");

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']))
{
	getPost($_POST['id']);

	$delete_result = query('DELETE FROM post WHERE id = ?', $_POST['id']);
	if ($delete_result) {
		flash('success', 'Eliminado correctamente.');
		return redirect('post.php');
	}
}

render('error/404', ['message' => 'un error ha ocurrido.', 'title' => 'Error']);