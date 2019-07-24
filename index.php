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
$users = Db::getInstance()->query('SELECT * FROM user;');
// echo  '<pre>';
// print_r($users);
// echo  '</pre>';


$data = [
	[
        'apellido' => 'Doe',
	    'nombre' => 'John',
	    'email' => 'johndoe@yahoo.com',
        'password' => 'm%r_JZLGjLdd~aO'
	],
	[
        'apellido' => 'Paterson',
	    'nombre' => 'Patrick ',
	    'email' => 'ejemplo@yahoo.com',
        'password' => 'twEZR+LPO+6BiRw'
	],
	[
        'apellido' => 'Benites',
	    'nombre' => 'Sandra',
	    'email' => 'sandra.benites@gmail.com',
        'password' => 't${}WQvmO4REdCp'
	]
];

$id = 1;
$flashes = [];

// if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 4){
    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';
    $_POST['password'] = password_hash($_POST['password'] . 'P4^ncFD!i', PASSWORD_DEFAULT);

    array_push($data, $_POST);
    $flashes[] = '¡Usuario registrado correctamente!';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    	<!-- Required meta tags -->
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    	<!-- Bootstrap CSS -->
    	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	    <title>Layout Template</title>
	    <link rel="stylesheet" type="text/css" href="css/styles.css">
    </head>
    <body>
    	<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #fff4ed;">
            <div class="container">
                <a class="navbar-brand" href="#">Layout Template</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbar">
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active"><a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="/logout">Sign out</a></li>
                    </ul>
                    <?php else: ?>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="/register">Sign up</a></li>
                        <li class="nav-item"><a class="nav-link" href="/login">Sign in</a></li>
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

    			<section class="text-center mb-5">
    			    <img id="homer" src="http://thenewcode.com/assets/images/thumbnails/homer-simpson.svg" style="width: 200px">
    			    <h1>Oh no, the world will end in
    			        <span id="countdown"><?= date('s') ?></span> seconds!
    			    </h1>
    			</section>

    			<section>
    				<div class="row align-items-center">
    					<div class="col-md-5 mb-3">
    						<h2 class="mb-4">Registrar</h2>
    						<form class="myForm" id="registration" method="POST">
    							<div class="form-row">
    							    <div class="form-group col-md-12">
    							        <label for="email">Email</label>
    							        <input autocomplete="off" class="form-control" type="email" name="email" id="email" maxlength="50" />
    							        <div class="invalid-feedback"></div>
    							    </div>
    							</div>
    							<div class="form-row">
    							    <div class="form-group col-md-6">
    							        <label for="lastname">Apellido</label>
    							        <input autocomplete="off" class="form-control" type="text" name="apellido" id="lastName" maxlength="50" onkeypress="return allow(event, 'car')"/>
    							        <div class="invalid-feedback"></div>
    							    </div>
    							    <div class="form-group col-md-6">
    							        <label for="firstname">Nombre</label>
    							        <input autocomplete="off" class="form-control" type="text" name="nombre" id="firstName" maxlength="50" onkeypress="return allow(event, 'car')" />
    							        <div class="invalid-feedback"></div>
    							    </div>
    							</div>
    							<div class="form-row">
    							    <div class="form-group col-md-6">
    							        <label for="password">Contraseña</label>
    							        <input autocomplete="off" class="form-control" type="password" name="password" id="password" maxlength="50" />
    							        <div class="invalid-feedback"></div>
    							    </div>
    							    <div class="form-group col-md-6">
    							        <label for="confirmPassword">Confirmar Contraseña</label>
    							        <input autocomplete="off" class="form-control" type="password" name="confirmPassword" id="confirmPassword" maxlength="50" />
    							        <div class="invalid-feedback"></div>
    							    </div>
    							</div>
                                <textarea name="hidden" rows="3" style="display: none;">
                                </textarea>
    							<button type="submit" class="btn btn-success btn-lg px-5">Enviar <span style="font-size: 14px;">&#10095;</span></button>
    						</form>
    					</div>
    					<div class="col-md-7 mb-3">
    						<div class="mb-4">
    							<h2><span id="translate">Registered Users</span> <button class="btn btn-outline-primary float-right translate-btn">Translate</button></h2>
    						</div>
                            <div class="table-responsive">
                            	<table class="table table-striped table-sm">
                            		<thead>
                            			<tr>
                            				<th>#</th>
                            				<th>Last name</th>
                            				<th>First name</th>
                            				<th>Email</th>
                                            <th>Password</th>
                                            <th></th>
                            			</tr>
                            		</thead>
                            		<tbody>
                            			<?php foreach ($data as $user): ?>
                            				<tr>
                            					<td><?= $id++ ?></td>
                            					<td><?= $user['apellido'] ?></td>
                            					<td><?= $user['nombre'] ?></td>
                            					<td><?= $user['email'] ?></td>
                                                <td><?= $user['password'] ?></td>
                                                <td>
                                                    <a href="" class="" title="Edit">Edit</a>&nbsp;
                                                    <a href="" class="" onclick="return confirm('¿Estás absolutamente seguro que quieres eliminar a ' + '<?= htmlspecialchars( $user['apellido'] . ' ' . $user['nombre']) ?>?')" title="Delete">Delete</a>
                                                </td>
                            				</tr>
                            			<?php endforeach ?>
                            		</tbody>
                            	</table>
                            </div>
    					</div>
    				</div>
    			</section>

    			<section class="my-5">
    				<div class="row align-items-center">

    					<div class="col-md-5 mx-auto">
    						<div class="bg-light p-4 rounded">
    							<h2 class="mb-4">Iniciar Sesión</h2>
    							<form class="myForm" onsubmit="return false;">
    								<div class="form-group">
    									<label for="email">Email</label>
    									<input autocomplete="off" maxlength="50" name="email" type="email" class="rounded-0 form-control" value="example@mail.com" autofocus />
    									<div class="invalid-feedback"></div>
    								</div>
    								<div class="form-group">
    									<label for="password">Contraseña</label>
    									<div class="input-group">
    									    <input autocomplete="off" maxlength="50" name="password" type="password" class="rounded-0 form-control" id="Password" value="hiddenpassword">
    									    <div class="input-group-append">
    									        <span class="input-group-text" id="show-password" style="font-size: 0.75rem; line-height: 2.9;background-color: white;/*border:none;border-bottom: 1px solid #9e9e9e;*/">Mostrar</span>
    									    </div>
    									    <div class="invalid-feedback"></div>
    									</div>
    								</div>
									<div class="form-group form-check my-4">
									    <a class="float-right" href="/password_reset.php" style="font-size:14px;margin-top: .2rem;">¿Olvidó su contraseña?</a>
										<input type="checkbox" class="form-check-input" id="remember_me">
										<label class="form-check-label font-weight-normal" for="remember_me">Recordarme</label>
									</div>
    								<button type="submit" class="btn btn-success btn-lg px-5">Ingresar <span style="font-size: 14px;">&#10095;</span></button>
    							</form>
    						</div>
    					</div>
                        <div class="col-md-5 mx-auto" style="font-size: 1.25rem;color: #030f28;">
                            <img src="img/abstract.svg">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
    				</div>
    			</section>
    		</div>

    	</main>

    	<footer class="container">
            <div class="my-5">
    		    <p class="text-center">&copy; 2017-<?= htmlspecialchars( date("Y") ) ?> | Powered by JYS | All rights reserved.</p>
            </div>
    	</footer>

		<a class="cd-top js-cd-top" title="Subir">Top</a>
    	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <script type="text/javascript" src="js/main.js"></script>
        <script type="text/javascript" src="js/validation.js"></script>
		<script type="text/javascript" src="js/back-to-top.js"></script>
    </body>
</html>