<?php

$dbh = 'mysql:host=http://99.000webhost.io:3306/phpmyadmin;dbname=d12124452_friendster';
$username = 'id12124452_root';
$password = 'karan';

try {
	
   $pdo = new PDO($dbh, $username, $password);

} catch (Exception $e) {
	echo $e->getMessage();
	die();
}

?>