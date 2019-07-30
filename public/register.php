<?php

require("../includes/config.php");

$data = [
    'title' => 'Registrar usuario',
    'nombre' => '',
    'apellido' => '',
    'email' => '',
    'usuario' => '',
    'password' => '',
    'confirm_password' => '',
    /*Error*/
    'nombre_err' => '',
    'apellido_err' => '',
    'email_err' => '',
    'usuario_err' => '',
    'password_err' => '',
    'confirm_password_err' => ''
];

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 5)
{
    // Sanitizamos el array POST
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
    // Trim all the incoming data: (quitamos los espacios en blanco de los datos entrantes)
    $trimmed = array_map('trim', $_POST);
    $_POST = preg_replace('/\s\s+/', ' ', $trimmed);

    /**
     * Validamos el email y comprobamos si no existe en la base de datos
     */
    if (isEmpty($_POST['email'])) {
        $data['email_err'] = 'Por favor, dinos tu email.';
    }
    else
    {
        $data['email'] = $_POST['email'];
        if (!checkEmailAddress($data['email'])) {
            $data['email_err'] = "El email no es válido.";
        }
        else
        {
            // consultamos la tabla por el email
            $result = query('SELECT user_id FROM user WHERE user_email=?', $data['email']);

            // Si existe el email
            if( count($result) != 0 ){
                $data['email_err'] = 'Ese email ya está registrado. Prueba con otro.';
            }
        }
    }

    /**
     * Validamos el nombre de usuario y comprobamos si no existe en la base de datos
     */
    if (isEmpty($_POST['usuario'])) {
        $data['usuario_err'] = 'Por favor, crea un usuario.';
    }
    else
    {
        $data['usuario'] = $_POST['usuario'];
        if (!checkUsername($data['usuario'])) {
            $data['usuario_err'] = "Los nombres de usuario no pueden contener espacios y no deben empezar por un número o subrayado.";
        }
        else
        {
            if(!meetLength($data['usuario'], 2, 20)) {
                $data['usuario_err'] = 'El nombre de usuario debe incluir entre 2 y 20 caracteres.';
            }
            // consultamos la tabla por el email
            $result = query('SELECT user_id FROM user WHERE user_name=?', $data['usuario']);

            // Si existe el email
            if( count($result) != 0 ){
                $data['usuario_err'] = 'Ese usuario ya está registrado. Prueba con otro.';
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
        if(!meetLength($data['nombre'], 3, 20)) {
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
        if(!meetLength($data['apellido'], 3, 20)) {
            $data['apellido_err'] = 'El apellido debe incluir entre 3 y 20 letras.';
        }
    }

    /*
     * Validamos la password
     */
    if (isEmpty($_POST['password']))
    {
        $data['password_err'] = 'Crea una contraseña.';
    }
    else
    {
        $data['password'] = $_POST['password'];
        if(!validatePasswordStrength($data['password'])) {
            $data['password_err'] = 'La contraseña debe tener al menos 8 caracteres de longitud y debe incluir al menos una letra mayúscula, un número y un carácter especial.';
            // Elige una contraseña más segura. Prueba con una combinación de letras, números y símbolos.
        }
        else
        {
            if(!isEmpty($_POST["confirm_password"]))
            {
                // Comprobamos si las passwords son iguales
                if($data['password'] !== $_POST['confirm_password']) {
                    $data['confirm_password_err'] = 'Las contraseñas ingresadas no coinciden.';
                }
            }
            else
            {
                $data['confirm_password_err'] = 'Por favor, confirme la contraseña.';
            }
        }
    }

    // Si todo esta OKAY
    if (
        empty( $data['nombre_err'] ) &&
        empty( $data['apellido_err'] ) &&
        empty( $data['email_err'] ) &&
        empty( $data['password_err'] ) &&
        empty( $data['usuario_err'] ) &&
        empty( $data['confirm_password_err'] )
    )
    {
        // Generamos un código de activación
        $activationkey = bin2hex(openssl_random_pseudo_bytes(16));
        // Creamos el hash de la password
        $password = password_hash($data['password'] . 'P4^ncFD!i', PASSWORD_DEFAULT);
        // Creamos la fecha y hora actual
        $dateTime = date('Y-m-d H:i:s');

        $q = 'INSERT INTO user (last_name, first_name, user_name, user_email, password, activation, created_on, last_modified_on) VALUES(?,?,?,?,?,?,?,?);';
        $insert_result = query($q, $data['apellido'], $data['nombre'], $data['usuario'], $data['email'], $password, $activationkey, $dateTime, $dateTime);

        // Si true => todo salió bien.
        if ($insert_result) {
            flash('flash_success', 'El registro fue realizado correctamente.');
            redirect("login.php");
        } else {
            flash('flash_error', 'El registro no fue realizado.', 'danger');
        }
    }
}

// else render form
render("auth/register_form", $data);