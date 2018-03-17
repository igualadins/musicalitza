<?php

// comprovem que hi hagi tots els camps del formulari
if(isset($_POST['enviar']) and isset($_POST['inputEmail']) and isset($_POST['inputNickname']) and isset($_POST['inputPassword']) and isset($_POST['conditions'])) { 

if( filter_input(INPUT_POST, "inputEmail", FILTER_VALIDATE_EMAIL) ) { // Validem que l'email sigui un email vàlid

		session_start();
		include('connect.php');
		include('class/user.php');
		$user = new User($dbConnection);

		//Registrem a l'usuari amb les dades rebudes
		$formMail = filter_input (INPUT_POST, 'inputEmail', FILTER_SANITIZE_EMAIL); // Netejem l'email
		$formName = filter_input (INPUT_POST, 'inputNickname', FILTER_SANITIZE_STRING); // Netejem el nom d'usuari
		// el password no el netejem perque es farà un hash amb ell i no el volem modificar
		$register = $user->register( $formMail, $formName, $_POST['inputPassword'] ); // Registrem l'usuari
	
		if($register['error'] == 0) { // Usuari registrat correctament
			header("Location: my-account.php?newuser=1");
		} else { // Error al registrar al usuari, tornem i mostrem l'error
			header("Location: register.php?error=1&message=".$register['message']);
		}

	} else { // Error en dades enviades	
		header("Location: register.php?error=1&message=Hi ha errors en les dades enviades");	
	}

} else { // Error en dades enviades
	header("Location: register.php?error=1&message=Tots els camps han de ser emplenats i acceptar les condicions legals");
}

?>