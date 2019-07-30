<?php

// configuration
require("../includes/config.php"); 


$q = 'SELECT * FROM user WHERE deleted=0;';

$data = [
    'title' => 'Registro de usuarios',
    'users' => query($q)
];

// render portfolio
render("users/list_users", $data);