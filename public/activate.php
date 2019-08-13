<?php

require("../includes/config.php");

// Si el usuario no esta logueado
if (!empty($_SESSION['user_id'])) {
    redirect('index.php');
} 

/**
 * Recibimos los datos que provengan de la bandeja de correo del usuario.
 */
if (
	isset($_GET['email'], $_GET['key']) && 
	filter_var($_GET['email'], FILTER_VALIDATE_EMAIL) && 
	(strlen($_GET['key']) == 32 )
) {
	// Sanitize Get data
	$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);

	// Trim all the incoming data:
	$trimmed = array_map('trim', $_GET);
	$email = $trimmed['email'];
	$token = $trimmed['key'];

	$activate_success = query("UPDATE user SET token='activated' WHERE (useremail=? AND token=?) LIMIT 1", $email, $token);

	if ( $activate_success != 0 ) {
		flash('flash_success', 'Su cuenta ha sido activada, ahora puedes iniciar sesión.');
		// re dirigimos al usuario a la página de login
		redirect('login.php');
	} else {
		apologize('La cuenta ya ha sido activada anteriormente.');
	}
    //http://localhost/online-notes-app/public/activate.php?email=peluche@gmail.com&key=e3adb1ccd91be373060b995274604668
}
apologize('Su cuenta no pudo ser activada. Vuelva a comprobar el enlace o póngase en contacto con el administrador del sistema.');