<?php

require("../includes/config.php");

if (array_key_exists('user_id', $_SESSION)) {
    redirect('index.php');
}

$data = [
	'title' => 'Iniciar sesión',
	'email' => '',
	'password' => '',
	// Error
	'email_err' => '',
	'password_err' => ''
];

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'], $_POST['password'])){
    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';
    // Sanitizamos el array POST
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    /**
     * Comprobamos que el email no este vacío
     */
    if (!isEmpty($_POST['email'])) {
        $data['email'] = $_POST["email"];
        // $data['email'] = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    } else {
        $data['email_err'] = 'Un email es necesario para iniciar sesión.';
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
    if (empty($data['email_err']) && empty($data['password_err'])) {
        // 
    	$rows = query("SELECT * FROM user WHERE user_email=? AND activation='activated'", $data["email"]);
    	// Si encontramos al usuario
    	if (count($rows) == 1) {
    		$user = $rows[0];
    		if (password_verify($data['password'] . 'P4^ncFD!i', $user['password'] ) == $user['password']) {
    			// remember that user's now logged in by storing user's ID in session
    			$_SESSION["user_id"] = $user["user_id"];
    			$_SESSION["user_name"] = $user["user_name"];
    			// redirect to portfolio
                redirect("post.php");
    		}
    	}

    	flash('error', 'El usuario o la contraseña ingresada es incorrecta.', 'danger');
        //flash('error', 'El usuario o la contraseña ingresada es incorrecta o aún no ha activado su cuenta.', 'danger');
    }
}

// else render form
render("auth/login_form", $data);