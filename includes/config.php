<?php

// display errors, warnings, and notices
ini_set("display_errors", true);
error_reporting(E_ALL);
mb_internal_encoding('UTF-8');
setlocale(LC_TIME, 'es_RA.UTF-8');
date_default_timezone_set('America/Argentina/Buenos_Aires');

// requirements
require("constants.php");
require("functions.php");

// enable sessions
session_start();

// require authentication for most pages
if (!preg_match("{(?:login|logout|register|password_reset|index|about)\.php$}", $_SERVER["PHP_SELF"]))
{
    if (empty($_SESSION["user_id"]))
    {
        redirect("register.php");
    }
}