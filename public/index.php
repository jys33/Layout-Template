<?php

// configuration
require("../includes/config.php"); 

//echo $_SERVER['REQUEST_URI'];
$page = 'home';

// render portfolio
render('page/' . $page . '' , ['title' => 'Home']);