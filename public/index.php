<?php

// configuration
require("../includes/config.php"); 

//echo $_SERVER['REQUEST_URI'];
$page = 'm-home';

// render request page
render('page/' . $page . '' , ['title' => 'Inicio']);