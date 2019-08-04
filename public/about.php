<?php

// configuration
require("../includes/config.php"); 

$page = 'about';

// render about page
render('page/' . $page . '' , ['title' => 'Acerca']);