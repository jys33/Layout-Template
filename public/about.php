<?php

// configuration
require("../includes/config.php"); 

$page = 'about';

// render portfolio
render('page/' . $page . '' , ['title' => 'Acerca']);