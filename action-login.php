<?php


// comprovem que hi hagi tots els camps del formulari
if(isset($_POST['inputEmail']) and isset($_POST['inputPassword']) and filter_input(INPUT_POST, "inputEmail", FILTER_VALIDATE_EMAIL)) { 

	session_start();
	include('connect.php');
	include('class/user.php');
	$user = new User($dbConnection);
	$formMail = filter_input (INPUT_POST, 'inputEmail', FILTER_SANITIZE_EMAIL); // Netejem l'email
	// el password no el netejem perque es farà un hash amb ell i no el volem modificar
	$login = $user->login( $formMail, $_POST['inputPassword'] ); // Fem el login d'usuari amb les dades rebudes 

	if($login['error'] == 0) { // Usuari identificat correctament
		header("Location: my-account.php");
	} else { // Error al identificar al usuari
		header("Location: my-account.php?error=1");
	}

} else { // Error en dades enviades
	header("Location: my-account.php?error=1");
}

?>