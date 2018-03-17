<?php

// comprovem que hi hagi tots els camps del formulari
if(isset($_POST['enviar']) and isset($_POST['inputNickname']) and isset($_POST['inputBio']) and isset($_POST['conditions']) and isset($_POST['userId'])) { 

	session_start();
	include('connect.php');
	include('class/user.php');
	$user = new User($dbConnection);

	//Registrem a l'usuari amb les dades rebudes
	$formName = filter_input (INPUT_POST, 'inputNickname', FILTER_SANITIZE_STRING); // Netejem el nom d'usuari
	$formBio = filter_input (INPUT_POST, 'inputBio', FILTER_SANITIZE_STRING); // Netejem la bio
	$userId = filter_input (INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT); // Netejem el userId
	$update = $user->update( $formName, $formBio, $userId); // Actualitzem l'usuari

	if($update['error'] == 0) { // Usuari actualitzat correctament
		header("Location: my-account.php");
	} else { // Error al actualitzar al usuari, tornem i mostrem l'error
		header("Location: my-data.php?error=1&message=".$update['message']);
	}

} else { // Error en dades enviades
	header("Location: my-data.php?error=1&message=Tots els camps han de ser emplenats i acceptar les condicions legals");
}

?>