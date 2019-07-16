<?php
function validatePasswordStrength ($password) {

    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        return false;
    }else{
        return true;
    }
}
$data = [
    'nombre' => '',
    'apellido' => '',
    'email' => '',
    'password' => '',
    'confirm_password' => '',
    /*Error*/
    'nombre_err' => '',
    'apellido_err' => '',
    'email_err' => '',
    'password_err' => '',
    'confirm_password_err' => ''
];
// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 3)
{
    // Sanitizamos el array POST
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
    // Trim all the incoming data: (quitamos los espacios en blanco)
    $trimmed = array_map('trim', $_POST);
    $_POST = preg_replace('/\s\s+/', ' ', $trimmed);

    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';

    /**
     * Validamos el email y comprobamos si no existe en la base de datos
     */
    if (empty( $_POST['email'] )) {
        $data['email_err'] = 'Por favor, dinos tu dirección de correo electrónico.';
    }
    else {
        // Remove all illegal characters from email
        $data['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        // check if e-mail address is well-formed
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $data['email_err'] = "La dirección de correo electrónico no es válida.";
        }
    }

    /*
     * Validamos el nombre
     */
    if (empty( $_POST['nombre'] )) {
        $data['nombre_err'] = 'Por favor, dinos tu nombre.';
    }
    else {
        $data['nombre'] = $_POST['nombre'];

        if( !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚÑñÜü ]{3,50}$/", $data['nombre']) ){
            $data['nombre_err'] = 'El nombre solo debe incluir entre 3 
            y 50 letras.';
        }
    }

    /*
     * Validamos el apellido
     */
    if (empty( $_POST['apellido'] )) {
        $data['apellido_err'] = 'Por favor, dinos tu apellido.';
    }
    else {
        $data['apellido'] = $_POST['apellido'];

        if( !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚÑñÜü ]{3,50}$/", $data['apellido']) ){
            $data['apellido_err'] = 'El apellido solo debe incluir entre 3 
            y 50 letras.';
        }
    }

    /*
     * Validamos la password ingresada
     */
    if (empty( $_POST['password'] )) {
        $data['password_err'] = 'Crea una contraseña.';
    }
    else {
        if( !validatePasswordStrength( $_POST['password'] ) ) {
            $data['password_err'] = 'La contraseña debe tener al menos 8 caracteres de longitud y debe incluir al menos una letra mayúscula, un número y un carácter especial.';
            // Elige una contraseña más segura. Prueba con una combinación de letras, números y símbolos.
        } else {
            $data['password'] = $_POST['password'];
            if(!empty($_POST["confirmarPassword"])) {
                $data['confirm_password'] = $_POST['confirmarPassword'];
                if($data['password'] !== $data['confirm_password']){
                    $data['confirm_password_err'] = 'Las contraseñas ingresadas no coinciden.';
                }
            } else {
                $data['confirm_password_err'] = 'Por favor confirme la contraseña.';
            }
        }
    }

    // Si todo esta OKAY
    if (
        empty($data['nombre_err'] ) &&
        empty( $data['apellido_err'] ) &&
        empty( $data['email_err'] ) &&
        empty( $data['password_err'] ) &&
        empty( $data['confirm_password_err'] )
    )
    {
        echo '<div class="alert alert-info" role="alert">Todos los datos son válidos.</div>';
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
    }

}
extract($data);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    	<!-- Required meta tags -->
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    	<!-- Bootstrap CSS -->
    	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	    <title>Validation</title>
	    <link rel="stylesheet" type="text/css" href="css/styles.css">
    </head>
    <body>
        <main role="main">
            
            <div class="container p-5">
                
                <section style="width: 100%;max-width: 450px;padding: 15px; margin: auto;">
                    <h2 class="mb-3">Registro de usuario:</h2>
                    <form class="myForm" method="POST">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control form-control-lg <?= empty($email_err) ? '' : 'is-invalid' ?>" type="email" name="email" id="email" value="<?= htmlspecialchars( $email ) ?>" maxlength="50" autofocus="autofocus" autocomplete="off" />
                            <div class="invalid-feedback"><?= htmlspecialchars($email_err) ?></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="firstName">Nombre</label>
                                <input class="form-control form-control-lg <?= empty($nombre_err) ? '' : 'is-invalid' ?>" type="text" name="nombre" id="firstName"  value="<?= htmlspecialchars( $nombre ) ?>" maxlength="50" autocomplete="off" onkeypress="return permite(event, 'car')" />
                                <div class="invalid-feedback"><?= htmlspecialchars($nombre_err) ?></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lastName">Apellido</label>
                                <input class="form-control form-control-lg <?= empty($apellido_err) ? '' : 'is-invalid' ?>" type="text" name="apellido" id="lastName" value="<?= htmlspecialchars( $apellido ) ?>" maxlength="50" autocomplete="off" onkeypress="return permite(event, 'car')" />
                                <div class="invalid-feedback"><?= htmlspecialchars($apellido_err) ?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="mb-0">Contraseña</label>
                            <small id="passwordHelpBlock" class="form-text text-muted mb-1">Las contraseñas deben tener por lo menos 8 caracteres y tener una combinación de letras, números y otros caracteres.</small>
                            <input class="form-control form-control-lg <?= empty($password_err) ? '' : 'is-invalid' ?>" type="password" name="password" id="password" value="" maxlength="50" aria-describedby="passwordHelpBlock"/>
                            <div class="invalid-feedback"><?= htmlspecialchars($password_err) ?></div>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirmar contraseña</label>
                            <input class="form-control form-control-lg <?= empty($confirm_password_err) ? '' : 'is-invalid' ?>" type="password" name="confirmarPassword" id="confirmPassword" value="" maxLength="50">
                            <div class="invalid-feedback"><?= htmlspecialchars($confirm_password_err) ?></div>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg px-5">Save</button>
                    </form>
                </section>
            </div>

        </main>
        <script type="text/javascript" src="js/validation.js"></script>
    </body>
</html>