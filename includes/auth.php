<?php

//@bp.route('/register', methods=('GET', 'POST'))
function register() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $error = null;

        if (empty($username)) {
            $error = 'Username is required.';
        } else {
            $rows = query('SELECT user_id FROM user WHERE user_name=?', $username);
            if (count($rows) != 0) {
                $error = 'User ' . $username . ' is already registered.';
            }
        }

        if (empty($password)) {
            $error = 'Password is required.';
        }

        if ($error == null) {
            $password = password_hash($password . 'P4^ncFD!i', PASSWORD_DEFAULT);
            $insert_result = query('INSERT INTO user (user_name, password) VALUES (?, ?)',$username, $password);
            if ($insert_result) {
            	flash('success', 'El registro fue realizado correctamente.');
                return redirect('auth/login.php');
            }

            flash('error', 'El registro no pudo ser creado.');
        }

    }
    return render('auth/register_form');
}

//@bp.route('/login', methods=('GET', 'POST'))
function login(){
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	    $username = $_POST['username'];
	    $password = $_POST['password'];

	    $rows = query('SELECT * FROM user WHERE user_name=?', $username);

	    // Si encontramos al usuario
    	if (count($rows) == 1) {
    		$user = $rows[0];
    		if (password_verify($password . 'P4^ncFD!i', $user['password'] ) == $user['password']) {
    			// remember that user's now logged in by storing user's ID in session
    			$_SESSION["user_id"] = $user["user_id"];
    			// redirect to portfolio
                redirect("index.php");
    		}
    	}

	    flash('error', 'Error de usuario o contraseña', 'danger');
	}
	return render('auth/login_form');
}

//@bp.before_app_request
function load_logged_in_user(){
	$user_id = $_SESSION["user_id"];
	if ($user_id == null) {
		$user = null;
	} else {
		$rows = query('SELECT * FROM user WHERE user_id=?', $user_id);
		$user = $rows[0];
	}
    //return $user;
}

//@bp.route('/logout')
function logout(){
    // unset any session variables
    $_SESSION = array();

    // expire cookie
    if (!empty($_COOKIE[session_name()]))
    {
        setcookie(session_name(), "", time() - 42000);
    }

    // destroy session
    session_destroy();

    return redirect('index.php');
}

//
function login_required($view){

    function wrapped_view(){
        if ($user == null) {
            return redirect('auth/login.php');
        }
        return view();
    }

    return wrapped_view();
}