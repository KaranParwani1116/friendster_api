<?php


$dbh = 'mysql:host=localhost:3306;dbname=old_frnt';
$username = 'root';
$password = 'karan';

try {
	
   $pdo = new PDO($dbh, $username, $password);

} catch (Exception $e) {
	echo $e->getMessage();
	die();
}

?>