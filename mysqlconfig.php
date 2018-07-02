<?php
	define('DB_SERVER', '127.0.0.1');
	define('DB_USERNAME', 'maxwell');
	define('DB_PASSWORD', 'fourier');
	define('DB_DATABASE', 'aula');
	$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

	if(!$db){
		die("Connection failed: " . mysqli_connect_error());
	}
?>