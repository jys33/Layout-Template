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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'], $_POST['apellido'], $_POST['usuario'], $_POST['id'], $_POST['save_details']) && filter_var($_POST['id'], FILTER_VALIDATE_INT) ) {
	
	// $_POST = preg_replace('/\s\s+/', ' ', $trimmed);
	$_POST = filter_post();
	
	getUser($_POST['id']);
	$data['id'] = $_POST['id'];
	/**
	 * Validamos el nombre de usuario y comprobamos si no existe en la base de datos
	 */
	if (isEmpty($_POST['usuario'])) {
	    $data['usuario_err'] = 'Por favor, ingresa un usuario.';
	}
	else
	{
	    $data['usuario'] = $_POST['usuario'];
	    if (!checkUsername($data['usuario'])) {
	        $data['usuario_err'] = "Los nombres de usuario no pueden contener espacios y no deben empezar por un número o subrayado.";
	    }
	    elseif(!meetLength($data['usuario'], 2, 20)) {
	            $data['usuario_err'] = 'El nombre de usuario debe incluir entre 2 y 20 caracteres.';
	    }
	    else {
	        // consultamos la tabla por el email
	        $rows = query('SELECT user_id FROM user WHERE user_name=? AND user_id !=?', $data['usuario'], $data['id']);

	        // Si existe el email
	        if( count($rows) != 0 ){
	            $data['usuario_err'] = 'El usuario "' . $data['usuario'] . '" ya está registrado. Prueba con otro.';
	        }
	    }
	}

	/*
	 * Validamos el nombre
	 */
	if (isEmpty($_POST['nombre']))
	{
	    $data['nombre_err'] = 'Por favor, dinos tu nombre.';
	}
	else
	{
	    $data['nombre'] = $_POST['nombre'];
	    if(!checkIfOnlyLetters($data['nombre'])) {
	        $data['nombre_err'] = 'El nombre solo debe incluir letras y espacios en blanco.';
	    }
	    elseif(!meetLength($data['nombre'], 3, 20)) {
	        $data['nombre_err'] = 'El nombre debe incluir entre 3 y 20 letras.';
	    }
	}

	/*
	 * Validamos el apellido
	 */
	if (isEmpty($_POST['apellido']))
	{
	    $data['apellido_err'] = 'Por favor, dinos tu apellido.';
	}
	else
	{
	    $data['apellido'] = $_POST['apellido'];
	    if(!checkIfOnlyLetters($data['apellido'])) {
	        $data['apellido_err'] = 'El apellido solo debe incluir letras y espacios en blanco.';
	    }
	    elseif(!meetLength($data['apellido'], 3, 20)) {
	        $data['apellido_err'] = 'El apellido debe incluir entre 3 y 20 letras.';
	    }
	}

	// Si todo esta OKAY
	if (
	    empty( $data['nombre_err'] ) &&
	    empty( $data['apellido_err'] ) &&
	    empty( $data['usuario_err'] )
	)
	{
		$dateTime = date('Y-m-d H:i:s');

		$q = 'UPDATE user set last_name=?, first_name=?, user_name=?, last_modified_on=? WHERE user_id=? LIMIT 1';
		$update_result = query($q, $data['apellido'], $data['nombre'], $data['usuario'], $dateTime, $data['id']);

		// Si true => todo salió bien.
		if ($update_result) {
		    flash('success', 'Los detalles de la cuenta se modificaron correctamente.');
		    redirect("profile.php");
		}
		
		flash('error', 'El registro no fue actualizado.', 'danger');
	}

    // else render form
    render("auth/profile_form", $data);
}

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password_actual'], $_POST['password_1'], $_POST['password_2'], $_POST['id'], $_POST['save_password']) && filter_var($_POST['id'], FILTER_VALIDATE_INT) ) {

	// Sanitizamos el array POST
	$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	
	// Trim all the incoming data: (quitamos los espacios en blanco de los datos entrantes)
	$trimmed = array_map('trim', $_POST);
	$_POST = preg_replace('/\s\s+/', ' ', $trimmed);

	$user = getUser($_POST['id']);
	$data['id'] = $_POST['id'];
	$data['usuario'] = $_POST['usuario'];
	$data['nombre'] = $_POST['nombre'];
	$data['apellido'] = $_POST['apellido'];

	if (isEmpty($_POST['password_actual']) || isEmpty($_POST['password_1']) || isEmpty($_POST['password_2'])) {
		flash('error', 'Por favor, completa todos los campos de contraseña.', 'danger');
		// else render form
		render("auth/profile_form", $data);
	} else {
		/*
		 * Validamos la password actual
		 */
		$data['password_actual'] = $_POST['password_actual'];
		if (!password_verify($data['password_actual'] . 'P4^ncFD!i', $user['password'] ) == $user['password']){
			$data['password_actual_err'] = 'Tu contraseña actual es incorrecta.';
		} else {
			$data['password_1'] = $_POST['password_1'];
			if(!validatePasswordStrength($data['password_1'])) {
			    $data['password_1_err'] = 'La contraseña debe tener al menos 8 caracteres de longitud y debe incluir al menos una letra mayúscula, un número y un carácter especial.';
			    // Elige una contraseña más segura. Prueba con una combinación de letras, números y símbolos.
			}
			else
			{
			    if(!isEmpty($_POST["password_2"]))
			    {
			        // Comprobamos si las passwords son iguales
			        if($data['password_1'] !== $_POST['password_2']) {
			            $data['password_2_err'] = 'Las contraseñas ingresadas no coinciden.';
			        }
			    }
			    else
			    {
			        $data['password_2_err'] = 'Por favor, confirme la nueva contraseña.';
			    }
			}
		}
	}

	// Si todo esta Ok
	if (empty( $data['password_actual_err'] ) &&
        empty( $data['password_1_err'] ) &&
        empty( $data['password_2_err'] ) ) {

		$dateTime = date('Y-m-d H:i:s');
	    // Creamos el nuevo hash de la password
        $password = password_hash($data['password_1'] . 'P4^ncFD!i', PASSWORD_DEFAULT);
		$q = 'UPDATE user set password=?, last_modified_on=? WHERE user_id=? LIMIT 1';
		$update_result = query($q, $password, $dateTime, $data['id']);

		// Si true => todo salió bien.
		if ($update_result) {
		    flash('success', 'Contraseña actualizada correctamente.');
		    redirect("profile.php");
		}
		
		flash('error', 'El registro no fue actualizado.', 'danger');
	}

	// else render form
	render("auth/profile_form", $data);

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