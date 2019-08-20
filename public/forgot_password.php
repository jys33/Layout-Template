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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])){
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
            $rows = query('SELECT user_id FROM user WHERE user_email=?', $data['email']);

            // Si no existe el email
            if( count($rows) == 0 ){
                $data['email_err'] = 'Lo sentimos, no encontramos ese email.';
            }
        }
    }

    /**
     * Si todo esta ok
     */
    if (empty($data['email_err'])) {
        $user = $rows[0];
        $user_id = $user['user_id'];

        //Create a unique activation code 32 caracteres
        //Ejemplo KEY: cc58481ee70ce002 7209abf27af17199
        $key = bin2hex(openssl_random_pseudo_bytes(16));
        $time = time();
        $dateTime = date("Y-m-d H:i:s");
        $status = 'pending';
        
        $q = 'INSERT INTO forgot_password (user_id, reset_key, time, status, created_on, last_modified_on) VALUES (?,?,?,?,?,?)';

        $insert_success = query($q, $user_id, $key, $time, $status, $dateTime, $dateTime);

        if ($insert_success) {
            $to = $data['email'];
            $subject = 'Restablecimiento de contraseña';
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From:PHPApp <noreply@eduoffyoucode.com>' . "\r\n";
            $message = "Para restablecer su contraseña haga click en el siguiente enlace:\n\n";
            $message .= '<p><a href="'. BASE_URL . "/reset_password.php?user_id=" . $user_id . "&key=" . $key.'">Restablecer contraseña</a></p><p>El enlace expirará en 24 horas.</p>';
            if( mail($to, $subject , $message, $headers) ) {
                $data['message'] = 'Se ha enviado un correo electrónico de restablecimiento de contraseña a la dirección de correo electrónico registrada en su cuenta, pero puede tardar varios minutos en aparecer en su bandeja de entrada. Espere al menos 10 minutos antes de intentar otro reinicio.';
                $data['success'] = 'Correo electrónico de restablecimiento de contraseña enviado.';
                // else render form
                render("auth/forgot_password", $data);
            }
        }
        render('error/404', ['message' => 'Un error ha ocurrido.', 'title' => 'Error']);
    }
}

// else render form
render("auth/forgot_password", $data);