<?php

session_start();
require_once('config.php');

date_default_timezone_set('America/New_York');

spl_autoload_register(function($class) {
	require_once 'classes/' . $class . '.php';
});

?>