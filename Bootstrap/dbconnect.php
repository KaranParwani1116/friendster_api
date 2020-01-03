<?php

$dbh = 'mysql:host=localhost; dbname=friendster';
$username = 'root';
$password = 'kpthegreat1116';

try {
	
   $pdo = new PDO($dbh, $username, $password);

} catch (Exception $e) {
	echo "Connection error";
	die();
}

?>