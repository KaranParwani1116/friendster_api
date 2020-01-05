<?php


$dbh = 'mysql:host=remotemysql.com:3306/phpmyadmin;dbname=WB5NBKqNZ5';
$username = 'WB5NBKqNZ5';
$password = 'EjE2q24wE0';

try {
	
   $pdo = new PDO($dbh, $username, $password);

} catch (Exception $e) {
	echo $e->getMessage();
	die();
}

?>