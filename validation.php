<?php
final class Index {
    private static $CLASSES = [
        'Config' => '/config/Config.php',
        'Db' => '/db/Db.php'
    ];

    public function init() {
        // Notificar todos los errores de PHP (ver el registro de cambios)
        // display errors, warnings, and notices
        ini_set("display_errors", true);
        error_reporting(E_ALL);
        mb_internal_encoding('UTF-8');
        spl_autoload_register([$this, 'loadClass']);

        session_start();
    }

    public function loadClass($name) {
        if (!array_key_exists($name, self::$CLASSES)) {
            die('Class "' . $name . '" not found.');
        }
        require_once __DIR__ . self::$CLASSES[$name];
    }
}

$index = new Index();
$index->init();

Config::setDirectory('section');

function validatePasswordStrength($password) {
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

function checkIfOnlyLetters($field) {
    if( !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚÑñÜü ]+$/", $field) ) return false;
    return true;
}

function meetLength($field, $minLength, $maxLength) {
    $strLen = strlen(trim($field));
    if ($strLen >= $minLength && $strLen <= $maxLength) {
        return true;
    } elseif ($strLen < $minLength) {
        return false;
    } else {
        return false;
    }
}

function isEmpty($value) {
    if (empty(trim($value))) return true;
    return false;
}

function checkEmailAddress($email) {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
    return true;
}

function checkUsername($username){
    if (!preg_match('/^[a-zA-Z@]+\d*$/', $username)) return false;
    return true;
}

$data = [
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

$flashes = [];

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 4)
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
            $result = Db::getInstance()->query('SELECT user_id FROM user WHERE user_email=?', $data['email']);

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
            $result = Db::getInstance()->query('SELECT user_id FROM user WHERE user_name=?', $data['usuario']);

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
        $insert_result = Db::getInstance()->query($q, $data['apellido'], $data['nombre'], $data['usuario'], $data['email'], $password, $activationkey, $dateTime, $dateTime);

        // Si true => todo salió bien.
        if ($insert_result) {
            $flashes[] = 'El registro fue insertado correctamente.';
        } else {
            $flashes[] = 'El registro no fue realizado.';
        }
    }

}

extract($data);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
    	<!-- Required meta tags -->
    	<meta charset="utf-8">
        <meta name="description" content="My first web page">
        <meta name="keywords" content="HTML,CSS,PHP,JavaScript">
        <meta name="author" content="?">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    	<!-- Bootstrap CSS -->
    	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	    <title>Form Validation</title>
	    <!-- <link rel="stylesheet" type="text/css" href="css/styles.css"> -->
        <link rel="stylesheet" type="text/css" href="css/material.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-light border-bottom shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="/">App</a>
                <button aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbar" data-toggle="collapse" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar">
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a class="nav-link" href="/quote">Quote</a></li>
                        <li class="nav-item"><a class="nav-link" href="/buy">Buy</a></li>
                        <li class="nav-item"><a class="nav-link" href="/sell">Sell</a></li>
                        <li class="nav-item"><a class="nav-link" href="/history">History</a></li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="/logout">Log Out</a></li>
                    </ul>
                    <?php else: ?>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="/register.php">Register</a></li>
                        <li class="nav-item"><a class="nav-link" href="/login.php">Log In</a></li>
                    </ul>
                    <?php endif ?>
                </div>
            </div>
        </nav>
        <?php if (count($flashes) > 0): ?>
        <header>
            <div class="alert alert-primary text-center" role="alert">
                <?php foreach ($flashes as $flash) echo $flash; ?>
            </div>
        </header>
        <?php endif ?>
        <main role="main">
            
            <div class="container p-5">

                <div class="row align-items-center">
                    <div class="col-md-5 mx-auto">
                        <section style="/*width: 100%;max-width: 520px;padding: 15px;*/">
                            <h2 class="mb-4">Registro de usuario</h2>
                            <form class="myForm" method="POST">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input class="form-control <?= empty($email_err) ? '' : 'is-invalid' ?>" type="email" name="email" value="<?= htmlspecialchars( $email ) ?>" maxlength="32" autocomplete="off"/>
                                    <div class="invalid-feedback"><?= htmlspecialchars($email_err) ?></div>
                                </div>
                                <div class="form-group">
                                    <label for="usuario">Usuario</label>
                                    <input class="form-control <?= empty($usuario_err) ? '' : 'is-invalid' ?>" type="text" name="usuario" value="<?= htmlspecialchars( $usuario ) ?>" maxlength="20" autocomplete="off"/>
                                    <div class="invalid-feedback"><?= htmlspecialchars($usuario_err) ?></div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="firstName">Nombre</label>
                                        <input class="form-control <?= empty($nombre_err) ? '' : 'is-invalid' ?>" type="text" name="nombre" value="<?= htmlspecialchars( $nombre ) ?>" maxlength="32" autocomplete="off" onkeypress="return allow(event, 'car')"/>
                                        <div class="invalid-feedback"><?= htmlspecialchars($nombre_err) ?></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="lastName">Apellido</label>
                                        <input class="form-control <?= empty($apellido_err) ? '' : 'is-invalid' ?>" type="text" name="apellido" value="<?= htmlspecialchars( $apellido ) ?>" maxlength="32" autocomplete="off" onkeypress="return allow(event, 'car')"/>
                                        <div class="invalid-feedback"><?= htmlspecialchars($apellido_err) ?></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="mb-0">Contraseña</label>
                                    <small id="passwordHelpBlock" class="form-text text-muted mb-1">Las contraseñas deben tener por lo menos 8 caracteres y tener una combinación de letras, números y otros caracteres.</small>
                                    <input class="form-control <?= empty($password_err) ? '' : 'is-invalid' ?>" type="password" name="password" maxlength="32" aria-describedby="passwordHelpBlock"/>
                                    <div class="invalid-feedback"><?= htmlspecialchars($password_err) ?></div>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirmar contraseña</label>
                                    <input class="form-control <?= empty($confirm_password_err) ? '' : 'is-invalid' ?>" type="password" name="confirm_password" maxLength="32"/>
                                    <div class="invalid-feedback"><?= htmlspecialchars($confirm_password_err) ?></div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg px-5 my-3">Enviar <span style="font-size: 14px">&#10095;</span></button>
                            </form>
                        </section>
                    </div>
                    <div class="col-md-6 mx-auto">
                        <img src="img/keyboard.jpg" style="width: 100%; border-radius: 6px; opacity: 0.85;">
                    </div>
                </div>

                <section class="p-5">
                    <?php $users = Db::getInstance()->query('SELECT * FROM user;'); ?>
                    <h2 class="mb-4">Usuarios Registrados</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm" style="font-size: .875rem;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Apellido</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Usuario</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['user_id'] ?></td>
                                        <td><?= $user['last_name'] ?></td>
                                        <td><?= $user['first_name'] ?></td>
                                        <td><?= $user['user_email'] ?></td>
                                        <td><?= $user['user_name'] ?></td>
                                        <td>
                                            <a href="" class="" title="Edit">Edit</a>&nbsp;
                                            <a href="" class="" onclick="return confirm('¿Estás absolutamente seguro que quieres eliminar a ' + '<?= htmlspecialchars($user['last_name'] . ' ' . $user['first_name']) ?>?')" title="Delete">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </section>

            </div>

        </main>
        <script type="text/javascript" src="js/validation.js"></script>
    </body>
</html>