<?php

$dbh = 'mysql:host=	
99.000webhost.io:3306; dbname=id12124452_friendster';
$username = 'id12124452_root';
$password = 'kpthegreat';

try {
	
   $pdo = new PDO($dbh, $username, $password);

} catch (Exception $e) {
	echo $e->getMessage();
	die();
}

?>