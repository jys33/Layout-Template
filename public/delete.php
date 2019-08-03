<?php

require("../includes/config.php");

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 0)
{
	if (array_key_exists('id', $_POST)) {
		getPost($_POST['id']);

		$delete_result = query('DELETE FROM post WHERE id = ?', $_POST['id']);
		if ($delete_result) {
			flash('success', 'El registro fue eliminado correctamente.');
			return redirect('post.php');
		}
	}
}

render('error/404', ['message' => 'un error ha ocurrido.', 'title' => 'Error']);