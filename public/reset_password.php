<?php

http://localhost/phpapp/public/reset_password.php?user_id=1&key=feb6672443545e3ee1266f56d4eebeff
require("../includes/config.php");

if (array_key_exists('user_id', $_SESSION)) {
    redirect('index.php');
}

$data = [
	'title' => 'Restablecer contraseña',
	'id' => '',
	'key' => '',
	// Error
	'password_err' => '',
	'confirm_password_err' => ''
];

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && 
	isset($_POST['id'], $_POST['key'], $_POST['password'], $_POST['confirm_password']) && 
	filter_var($_POST['id'], FILTER_VALIDATE_INT) && (strlen($_POST['key']) == 32 )) {
	
    // Sanitize post data
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $time = time() - 86400;
    $data['id'] = $_POST['id'];
    $data['key'] = $_POST['key'];

    //Run Query: Check combination of user_id & key exists and less than 24h old
    $q = "SELECT user_id FROM forgot_password WHERE reset_key=? AND user_id=? AND time > ? AND status='pending'";
    $rows = query($q, $data['key'], $data['id'], $time);
    /**
     * Si encontramos al usuario en la base de datos
     */
    if (count($rows) == 1) {
    	/*
    	 * Validamos la password
    	 */
    	if (isEmpty($_POST['password'])) {
    	    $data['password_err'] = 'Crea una contraseña.';
    	} else {
    	    $data['password'] = $_POST['password'];
    	    if(!validatePasswordStrength($data['password'])) {
    	        $data['password_err'] = 'La contraseña debe tener al menos 8 caracteres de longitud y debe incluir al menos una letra mayúscula, un número y un carácter especial.';
    	        // Elige una contraseña más segura. Prueba con una combinación de letras, números y símbolos.
    	    } else {
    	        if(!isEmpty($_POST["confirm_password"])) {
    	            // Comprobamos si las passwords son iguales
    	            if($data['password'] !== $_POST['confirm_password']) {
    	                $data['confirm_password_err'] = 'Las contraseñas ingresadas no coinciden.';
    	            }
    	        } else {
    	            $data['confirm_password_err'] = 'Por favor, confirme la contraseña.';
    	        }
    	    }
    	}

    	// Si todo esta OKAY
    	if (empty( $data['password_err'] ) && empty( $data['confirm_password_err'] )) {
    		$dateTime = date("Y-m-d H:i:s");
    		$password = password_hash($data['password'] . 'P4^ncFD!i', PASSWORD_DEFAULT);
    		$q = 'UPDATE user SET password=?, last_modified_on=? WHERE user_id=? LIMIT 1';

    		$update_success = query($q, $password, $dateTime, $data['id']);

    		if ($update_success != 0) {
    			$q = "UPDATE forgot_password SET status='used', last_modified_on=? WHERE reset_key=? AND user_id=? LIMIT 1";
    			$update_success = query($q, $dateTime, $data['key'], $data['id']);
    			/**
    			 * Si se actualizó el status de la tabla forgot_password
    			 */
    			if ($update_success != 0) {
    				// seteamos el mensage flash para la vista
    				flash('success', 'Su contraseña se ha actualizado correctamente.');
    				// re dirigimos al usuario a la página de login
    				redirect('login.php');
    			}
    		}
            //echo 'Verificar esté punto';
    	}

    	render("auth/m-reset_password", $data);
    }
}

if (isset($_GET['user_id'], $_GET['key']) && 
	filter_var($_GET['user_id'], FILTER_VALIDATE_INT) && 
	(strlen($_GET['key']) == 32 ))
{
	// Sanitize GET data
	$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
	$time = time() - 86400;
	$data['id'] = $_GET['user_id'];
	$data['key'] = $_GET['key'];

	//Run Query: Check combination of user_id & key exists and less than 24h old
	$q = "SELECT user_id FROM forgot_password WHERE reset_key=? AND user_id=? AND time > ? AND status='pending'";
	$rows = query($q, $data['key'], $data['id'], $time);

	/**
	 * Si encontramos al usuario, mostramos el formulario de cambio de password
	 */
	if (count($rows) == 1) {
		render("auth/m-reset_password", $data);
	}
}
$message = 'Algo salió mal. Vuelva a comprobar el enlace o póngase en contacto con el administrador del sistema.';
render('error/404', ['message' => $message, 'title' => 'Error']);