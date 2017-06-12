<?php

$db = new mysqli('localhost',$config['username'],$config['password'],$config['database']);
if($db === false) {
	// Handle error - notify administrator, log to a file, show an error screen, etc.
	die('Error : ('. $db->connect_errno .') '. $db->connect_error);
}

/*
$db = new PDO("mysql:host=localhost;dbname=$config['database']", $config['username'], $config['password']);  
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
*/