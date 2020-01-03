<?php

$dbh = 'mysql:host=localhost; dbname=friendster';
$username = 'root';
$password = '';

try {
	
   $pdo = new PDO($dbh, $username, $password);

} catch (Exception $e) {
	echo "Connection error";
	die();
}

?>