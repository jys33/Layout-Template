<?php

require("../includes/config.php");

if (!array_key_exists('user_id', $_SESSION)) {
    redirect('index.php');
}

$data = [
    'title' => 'Editar post',
    'titulo' => '',
    'descripcion' => '',
    /*Error*/
    'titulo_err' => '',
    'descripcion_err' => ''
];

http://localhost/phpapp/public/update.php?id=2

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'], $_POST['titulo'], $_POST['descripcion']) && filter_var($_POST['id'], FILTER_VALIDATE_INT) )
{
	// Sanitizamos el array POST
	$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	
	// Trim all the incoming data: (quitamos los espacios en blanco de los datos entrantes)
	$trimmed = array_map('trim', $_POST);
	$_POST = preg_replace('/\s\s+/', ' ', $trimmed);

	$data['id'] = $_POST['id'];

	/*
	 * Validamos el nombre
	 */
	if (isEmpty($_POST['titulo']))
	{
	    $data['titulo_err'] = 'Por favor, ingrese un título.';
	}
	else
	{
	    $data['titulo'] = $_POST['titulo'];
	    if(!checkIfOnlyLetters($data['titulo'])) {
	        $data['titulo_err'] = 'El título solo debe incluir letras y espacios en blanco.';
	    }
	    elseif(!meetLength($data['titulo'], 3, 50)) {
	        $data['titulo_err'] = 'El título debe incluir entre 3 y 50 caracteres.';
	    }
	}

	/*
	 * Validamos la descripción
	 */
	if (isEmpty($_POST['descripcion']))
	{
	    $data['descripcion_err'] = 'Por favor, ingrese un descripción.';
	}
	else
	{
	    $data['descripcion'] = $_POST['descripcion'];
	}

	// Si todo esta OKAY
	if (empty( $data['titulo_err'] ) && empty( $data['descripcion_err'] )) {
		// Creamos la fecha y hora actual
		$dateTime = date('Y-m-d H:i:s');
		$q = 'UPDATE post set title=?, body=?, author_id=?, last_modified_on=? WHERE id=?';
		$update_result = query($q, $data['titulo'], $data['descripcion'], $_SESSION['user_id'], $dateTime, $data['id']);
		// Si true => todo salió bien.
		if ($update_result) {
		    flash('success', 'Guardado correctamente.');
		    redirect("post.php");
		}
		
		flash('error', 'El registro no fue actualizado.', 'danger');
		redirect("post.php");
	}

	// else render form
	render("post/update", $data);
}

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']))
{
	$post = getPost($_GET['id']);
	$data['titulo'] = $post['title'];
	$data['descripcion'] = $post['body'];
	$data['id'] = $post['id'];

	// else render form
	render("post/update", $data);
}

render('error/404', ['message' => 'un error ha ocurrido.', 'title' => 'Error']);