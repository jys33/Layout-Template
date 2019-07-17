<?php
// Notificar todos los errores de PHP (ver el registro de cambios)
// display errors, warnings, and notices
ini_set("display_errors", true);
error_reporting(E_ALL);

$flashes = ['¡Usuario registrado correctamente!'];
$data = [
	[
		'id' => 1,
	    'lastname' => 'Doe',
	    'firstname' => 'John',
	    'email' => 'johndoe@yahoo.com'
	],
	[
		'id' => 2,
	    'lastname' => 'Paterson',
	    'firstname' => 'Patrick ',
	    'email' => 'ejemplo@yahoo.com'
	],
	[
		'id' => 3,
	    'lastname' => 'Benites',
	    'firstname' => 'Sandra Ximena',
	    'email' => 'sabina.benites@gmail.com'
	],
];
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
    	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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

    	<?php if ($flashes): ?>
    	<header>
    	    <div class="alert alert-primary text-center" role="alert">
    	        <?php foreach ($flashes as $flash) echo $flash; ?>
    	    </div>
    	</header>
    	<?php endif ?>

    	<main role="main">

    		<div class="container p-5">

    			<section class="text-center">
    			    <img id="homer" src="http://thenewcode.com/assets/images/thumbnails/homer-simpson.svg" style="width: 200px">
    			    <h1>Oh no, the world will end in
    			        <span id="countdown"><?= date('s') ?></span> seconds!
    			    </h1>
    			</section>
    			<section>
    				<div class="row align-items-center">
    					<div class="col-md-6 mb-3">
    						<h2 class="">Sign Up</h2>
    						<form id="registration">
    							<div class="form-row">
    							    <div class="form-group col-md-6">
    							        <label for="lastname">Last Name</label>
    							        <input class="form-control form-control-lg" type="text" name="lastname" id="lastname" onfocusout="" />
    							        <div class="invalid-feedback"></div>
    							    </div>
    							    <div class="form-group col-md-6">
    							        <label for="firstname">First Name</label>
    							        <input class="form-control form-control-lg" type="text" name="firstname" id="firstname" />
    							        <div class="invalid-feedback"></div>
    							    </div>
    							</div>
    							<div class="form-row">
    							    <div class="form-group col-md-12">
    							        <label for="email">Email</label>
    							        <input class="form-control form-control-lg" type="email" name="email" id="email" />
    							        <div class="invalid-feedback"></div>
    							    </div>
    							</div>
    							<div class="form-row">
    							    <div class="form-group col-md-6">
    							        <label for="password">Password</label>
    							        <input class="form-control form-control-lg" type="password" name="password" id="password" />
    							        <div class="invalid-feedback"></div>
    							    </div>
    							    <div class="form-group col-md-6">
    							        <label for="confirmPassword">Confirm Password</label>
    							        <input class="form-control form-control-lg" type="password" name="confirmPassword" id="confirmPassword" />
    							        <div class="invalid-feedback"></div>
    							    </div>
    							</div>
    							<button type="submit" class="btn btn-success btn-lg">Save</button>
    						</form>
    					</div>
    					<div class="col-md-6 mb-3">
    						<div class="mb-3">
    							<h2><span id="translate">Registered Users</span> <button class="btn btn-primary float-right translate-btn">Translate</button></h2>
    						</div>
                            <div class="table-responsive">
                            	<table class="table table-striped table-sm">
                            		<thead>
                            			<tr>
                            				<th>#</th>
                            				<th>Last name</th>
                            				<th>First name</th>
                            				<th>Age</th>
                            			</tr>
                            		</thead>
                            		<tbody>
                            			<?php foreach ($data as $value): ?>
                            				<tr>
                            					<td><?= $value['id'] ?></td>
                            					<td><?= $value['lastname'] ?></td>
                            					<td><?= $value['firstname'] ?></td>
                            					<td><?= $value['email'] ?></td>
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

    					<div class="col-md-5 mb-3 mx-auto">
    						<div class="bg-light p-4 rounded">
    							<h2 class="mb-3">Sign In</h2>
    							<form>
    								<div class="form-group">
    									<label for="email">Email</label>
    									<input class="rounded-0 form-control form-control-lg" type="email" name="email"  />
    									<div class="invalid-feedback"></div>
    								</div>
    								<div class="form-group">
    									<label for="password">Contraseña</label>
    									<a class="float-right" href="/password_reset.php">¿Olvidó su contraseña?</a>
    									<div class="input-group">
    									    <input autocomplete="off" maxlength="50" name="password" type="password" class="rounded-0 form-control form-control-lg" id="Password" value="hiddenpassword">
    									    <div class="input-group-append">
    									        <span class="input-group-text" id="show-password">Mostrar</span>
    									    </div>
    									    <div class="invalid-feedback"></div>
    									</div>
    								</div>
    								<button type="submit" class="btn btn-success btn-lg">Save</button>
    							</form>
    						</div>
    					</div>
                        <div class="col-md-6 my-3 mx-auto" style="font-size: 1.25rem;">
                            <img src="img/abstract.svg" style="width: 100%;opacity: 0.85;">
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

    	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <script type="text/javascript" src="js/main.js"></script>
    </body>
</html>