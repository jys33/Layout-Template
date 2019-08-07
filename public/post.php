<?php

// configuration
require("../includes/config.php"); 

$q = 'SELECT p.id, p.title, p.body, p.created_on, p.author_id, u.user_name
        FROM post p JOIN user u ON p.author_id = u.user_id
        ORDER BY p.created_on DESC;';

$data = [
    'title' => 'Publicaciones',
    'posts' => query($q)
];

// render datalist
render("post/m-index", $data);