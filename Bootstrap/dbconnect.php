<?php

$dbh = 'mysql:host=localhost; dbname=id12124452_friendster';
$username = 'id12124452_root';
$password = 'kpthegreat';

try {
	
   $pdo = new PDO($dbh, $username, $password);

} catch (Exception $e) {
	echo "Connection error";
	die();
}

?>