<?php

// comprovem que hi hagi tots els camps del formulari
if(isset($_POST['enviar']) and isset($_POST['userId'])) { 

	session_start();
	include('connect.php');
	include('class/user.php');
	$user = new User($dbConnection);
	$userId = filter_input (INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT); // Netejem el userId


	try {

		if( strtolower( $_SERVER[ 'REQUEST_METHOD' ] ) == 'post' && !empty( $_FILES ) ) {

			$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF); // tipus de fitxer que permetem pujar

			foreach($_FILES as $file){

				if(is_uploaded_file($file['tmp_name'])) {

					// Comprobamos el fichero subido
		  		$sourcePath = $file['tmp_name'];
					$detectedType = exif_imagetype($sourcePath);
					$error = !in_array($detectedType, $allowedTypes);

					$fileName = preg_replace("/[^A-Z0-9._-]/i", "_", $file["name"]); // Limpiamos el nombre del fichero

					// Mientras el fichero exista, le va añadiendo numeros al nombre
			  	$targetPath = "img/users/".$fileName;
					while (file_exists ($targetPath)) {
						$name = pathinfo($targetPath,PATHINFO_DIRNAME) . '/' . pathinfo($targetPath,PATHINFO_FILENAME); // nombre del fichero con ruta
						$ext = pathinfo($targetPath,PATHINFO_EXTENSION); // extension
						$randName = rand(1, 9); // Numero aleatorio
						$targetPath = $name . $randName . '.' . $ext; // Nuevo nombre
					}

					if(!$error) { // Si el formato es valido
						if(move_uploaded_file($sourcePath,$targetPath)) { // Si el fichero se puede mover correctamente
							$update = $user->updatePicture( $targetPath, $userId); // Actualitzem l'usuari
						} 
				  }

				}
			}

		}

	} catch (Exception $e) { // Si hi ha algun error processant

	}


}

// Passi el que passi, anirem a la secció de foto de perfil
header("Location: my-picture.php");

?>