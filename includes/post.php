<?php

//@bp.route('/')
function index(){
	$posts = query(
		'SELECT p.id, p.title, p.body, p.created_on, p.author_id, u.user_name
		FROM post p JOIN user u ON p.author_id = u.user_id
		ORDER BY p.created_on DESC;');

	return render('post/index.html',['posts' => $posts] );
}

//@bp.route('/create', methods=('GET', 'POST'))
//@login_required
function create(){
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	    $title = $_POST['title'];
	    $body = $_POST['body'];

	    $error = null;

	    if (empty($title)) {
	        $error = 'Title is required.';
	    }

	    if (empty($body)) {
	        $error = 'body is required.';
	    }

	    if ($error == null) {
	        $insert_result = query('INSERT INTO post (title, body, author_id) VALUES (?, ?, ?)', $title, $body, $_SESSION['user_id']);
	        if ($insert_result) {
	        	flash('success', 'El registro fue creado correctamente.');
	            return redirect('post/index.php');
	        }

	        flash('error', 'El registro no pudo ser creado.');
	    }
	}
	return render('post/create');
}

function get_post($id, $check_autor=true){
	$q = 'SELECT p.id, p.title, p.body, p.created, p.author_id, u.user_name
	      FROM post p JOIN user u ON p.author_id = u.user_id WHERE p.id = ?';
	$posts = query($q, $id);
	if (count($posts) != 1) {
		header("HTTP/1.0 404 Not Found");
		render('error/404', ['message' => 'post id ' . $id . ' doesn\'t exist.']);
	}
	$post = $posts[0];
	if ($check_autor && $post['author_id'] != $_SESSION['user_id']) {
		header("HTTP/1.0 403 Forbidden");
		render('error/404', ['message' => 'no puedes realizar está acción.']);
	}

	return $post;
}

//@bp.route('/<int:id>/update', methods=('GET', 'POST'))
//@login_required
function update($id){
	$post = get_post($id);

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$title = $_POST['title'];
		$body = $_POST['body'];

		$error = null;
		if (empty($title)) {
		    $error = 'Title is required.';
		}

		if (empty($body)) {
		    $error = 'body is required.';
		}

		if ($error == null) {
			$q = 'UPDATE post SET title = ?, body = ? WHERE id = ?';
			$update_result = query($q, $title, $body, $id);
			if ($update_result) {
				flash('success', 'El registro fue actualizado correctamente.');
	            return redirect('post/index.php');
			}
		}
		echo 'Mostramos los errores generados.';
	}
	return render('post/update', ['post' => $post]);
}

//@bp.route('/<int:id>/delete', methods=('POST',))
//@login_required
function delete($id){
	get_post($id);

	$delete_result = query('DELETE FROM post WHERE id = ?', $id);
	if ($delete_result) {
		flash('success', 'El registro fue eliminado correctamente.');
		return redirect('post/index.php');
	}
}