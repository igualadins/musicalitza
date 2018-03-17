<?php
	session_start();
	include('connect.php');
	include('class/user.php');
	$user = new User($dbConnection);
	$user->logout(); // tanquem la sessió de l'usuari
	header("Location: index.php"); // i el portem a la portada de la web
?>