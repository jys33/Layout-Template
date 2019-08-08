<?php

require("../includes/config.php");

// if (!array_key_exists('user_id', $_SESSION)) {
//     redirect('index.php');
// }

$data = [
	'title' => 'Perfil de usuario',
	'id' => '',
	'nombre' => '',
    'apellido' => '',
    'usuario' => '',
    'password_actual' => '',
    'password_1' => '',
    'password_2' => '',
    /*Error*/
    'nombre_err' => '',
    'apellido_err' => '',
    'usuario_err' => '',
    'password_actual_err' => '',
    'password_1_err' => '',
    'password_2_err' => ''
];

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'], $_POST['apellido'], $_POST['usuario']) && filter_var($_POST['id'], FILTER_VALIDATE_INT) ) {

	// echo '<pre>';
	// print_r($_POST);
	// echo '</pre>';
	echo 'En construcción: <a href="profile.php">Volver</a>';
	exit;

}

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
	$rows = query("SELECT * FROM user WHERE user_id=?", $_SESSION['user_id']);
	if (count($rows) != 1) {
		header("HTTP/1.0 404 Not Found");
		render('error/404', ['message' => 'user id ' . $_SESSION['user_id'] . ' no existe.', 'title' => 'Error']);
	}
	$user = $rows[0];
	$data['nombre'] = $user['first_name'];
	$data['apellido'] = $user['last_name'];
	$data['usuario'] = $user['user_name'];
	$data['email'] = $user['user_email'];
	$data['id'] = $user['user_id'];

	// else render form
	render("auth/profile_form", $data);
}

render('error/404', ['message' => 'un error ha ocurrido.', 'title' => 'Error']);