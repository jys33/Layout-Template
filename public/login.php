<?php

require("../includes/config.php");

$pass = [
	[
        'password' => 'm%r_JZLGjLdd~aO'
	],
	[
        'password' => 'twEZR+LPO+6BiRw'
	],
	[
        'password' => 't${}WQvmO4REdCp'
	]
];

$data = [
	'title' => 'Iniciar sesión',
	'login' => '',
	'password' => '',
	// Error
	'login_err' => '',
	'password_err' => ''
];

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 1){
    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';
    // Sanitizamos el array POST
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    /**
     * Comprobamos que el email no este vacío
     */
    if (!isEmpty($_POST['login'])) {
    	$data['login'] = $_POST["login"];
    	// $data['login'] = filter_var($_POST["login"], FILTER_SANITIZE_EMAIL);
    } else {
    	$data['login_err'] = 'Un correo electrónico es necesario para iniciar sesión.';
    }

    /**
     * Comprobamos que la password no este vacía
     */
    if (!isEmpty($_POST['password'])) {
    	$data['password'] = $_POST["password"];
    } else {
    	$data['password_err'] = 'Se requiere una contraseña para iniciar sesión.';
    }

    /**
     * Si todo esta ok
     */
    if (empty($data['login_err']) && empty($data['password_err'])) {
        // 
    	$rows = query("SELECT * FROM user WHERE user_email=? AND activation='activated'", $data["login"]);
    	// Si encontramos al usuario
    	if (count($rows) == 1) {
    		$user = $rows[0];
    		if (password_verify($data['password'] . 'P4^ncFD!i', $user['password'] ) == $user['password']) {
    			// remember that user's now logged in by storing user's ID in session
    			$_SESSION["user_id"] = $user["user_id"];
    			$_SESSION["user_name"] = $user["user_name"];
    			// redirect to portfolio
    			redirect("index.php");
    		}
    	}

    	flash('flash_error', 'El usuario o la contraseña ingresada es incorrecta o aún no ha activado su cuenta.', 'danger');
    }
}

// else render form
render("auth/login_form", $data);