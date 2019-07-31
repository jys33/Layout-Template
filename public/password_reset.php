<?php

require("../includes/config.php");

if (array_key_exists('user_id', $_SESSION)) {
    redirect('index.php');
}

$data = [
	'title' => '¿Olvidó su contraseña?',
	'email' => '',
	// Error
	'email_err' => ''
];

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 0){
    // Sanitizamos el array POST
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    /**
     * Chequeamos el email
     */
    if (isEmpty($_POST['email'])) {
        $data['email_err'] = 'El email es necesario para restablecer la contraseña.';
    }
    else
    {
        $data['email'] = $_POST['email'];
        if (!checkEmailAddress($data['email'])) {
            $data['email_err'] = "El email ingresado no es válido.";
        }
        else
        {
            // consultamos la tabla por el email
            $result = query('SELECT user_id FROM user WHERE user_email=?', $data['email']);

            // Si existe el email
            if( count($result) == 0 ){
                $data['email_err'] = 'No encontramos ese email, lo sentimos.';
            }
        }
    }

    /**
     * Si todo esta ok
     */
    if (empty($data['email_err'])) {
        // 
    	// $rows = query("SELECT * FROM user WHERE user_email=? AND activation='activated'", $data["email"]);
    	// // Si encontramos al usuario
    	// if (count($rows) == 1) {
    	// 	$user = $rows[0];
    	// 	if (password_verify($data['password'] . 'P4^ncFD!i', $user['password'] ) == $user['password']) {
    	// 		// remember that user's now logged in by storing user's ID in session
    	// 		$_SESSION["user_id"] = $user["user_id"];
    	// 		$_SESSION["user_name"] = $user["user_name"];
    	// 		// redirect to portfolio
    	// 		redirect("index.php");
    	// 	}
    	// }

    	// flash('flash_error', 'El usuario o la contraseña ingresada es incorrecta o aún no ha activado su cuenta.', 'danger');
    }
}

// else render form
render("auth/password_reset", $data);