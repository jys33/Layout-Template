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
	//$_POST = preg_replace('/\s\s+/', ' ', $trimmed);
	$_POST = filter_post();

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
	    if (!preg_match('/^[a-zA-Z-ZáéíóúÁÉÍÓÚÑñÜü0-9\s.!¡;,:¿?()]+$/', $data['descripcion'])) {
	    	$data['descripcion_err'] = 'La descripción solo debe incluir letras, algunos signos ortográficos, números y espacios en blanco.';
	    }
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
		    redirect("index.php");
		}
		
		flash('error', 'El registro no fue actualizado.', 'danger');
		redirect("index.php");
	}

	// else render form
	render("post/update", $data);
}

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT))
{
	$post = getPost($_GET['id']);
	$data['titulo'] = $post['title'];
	$data['descripcion'] = $post['body'];
	$data['id'] = $post['id'];

	// else render form
	render("post/update", $data);
}

render('error/404', ['message' => 'un error ha ocurrido.', 'title' => 'Error']);