<?php

/**
* User 
*  
* Aquesta classe engloba el control de sessió i registre dels usuaris de la web
* @author Igualadins (Carlos, Pau i Xavier)
*/

class User
{
	private $dbConnection;
	private $encryptKey;

	// Al iniciar seteja la connexió amb la base de dades

	public function __construct(\PDO $dbConnection)
	{
			$this->dbConnection = $dbConnection;
			// Hash en el que ens basem per fer les encriptacions
			$this->encryptKey = 'rv76hut-gyjjK9fghlHR47hskd62HG))%4ggk';
			// Si la versió de php on ho fem funcionar es anterior a la 5.5.0 afegim 
			// la classe password per afegir compatibilitat amb les noves funcionalitats d'encriptació
			if (version_compare(phpversion(), '5.5.0', '<')) { require("password.php"); }
	}


	/**
	* Funció per identificar i crear una sessió d'usuari a partir del seu email i password
	*
	* @param string $username Email de l'usuari
	* @param string $password Contrassenya de l'usuari administrador
	* @return array $return ('error' => 0 - Ok, 1 - Error , 'message' => Missatge d'error )
	*/

	public function login($email, $password)
	{
		$return = array();
		$user = $this->getUserData($email);

		// Es verifica si existeix l'email a la taula users
		if($user['userfound'] == true) {

			// Es verifica el password de l'usuari amb les funcions de verificació de php 5.5.0 (o la classe de compatibilitat)
			if (password_verify($password, $user['password'])) {
				
				// Si l'usuari està validat s'intenta crear la sessió
				$_SESSION['id'] = $user['id'];
				$_SESSION['nickname'] = $user['nickname'];
				$_SESSION['hash'] = sha1($user['id'] . $this->encryptKey);
				$return['error'] = 0;
				return $return;

			} else { // Si el password es incorrecte
				$return['error'] = 1;
				$return['message'] = "Password incorrecte";
				return $return;
			}
			
		} else { // Si l'email es incorrecte
			$return['error'] = 1;
			$return['message'] = "Email incorrecte";
			return $return;
		}

	}


	/**
	* Cerca les dades d'un usuari  a partir del seu email
	*
	* @param int $email email de l'usuari 
	* @return array $data Array amb les dades trobades i una dada "userfound" per indicar si l'ha trobat o no
	*/

	public function getUserData($email)
	{
		$data = array();
		$query = $this->dbConnection->prepare("SELECT * FROM users WHERE email = ?");
		$query->execute(array($email));
		if ($query->rowCount() == 0) {
			$data['userfound'] = false;
			return $data;
		} else {
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$data['userfound'] = true;
			return $data;
		}
	}



	/**
	* Cerca les dades d'un usuari  a partir del seu id
	*
	* @param int $id id de l'usuari 
	* @return array $data Array amb les dades trobades i una dada "userfound" per indicar si l'ha trobat o no
	*/

	public function getUserDataById($id)
	{
		$data = array();
		$query = $this->dbConnection->prepare("SELECT * FROM users WHERE id = ?");
		$query->execute(array($id));
		if ($query->rowCount() == 0) {
			$data['userfound'] = false;
			return $data;
		} else {
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$data['userfound'] = true;
			return $data;
		}
	}


	/**
	*
	* Funció per verificar si existeix una sessió d'usuari activa i correcte
	*
	* @return boolean Retorna true o false en funció de la verificació de la sessió
	*/

	public function checkUserSession()
	{
		// Si existeisen els parametres a sessió..
		if (isset($_SESSION['id']) && isset($_SESSION['hash'])) {
			// Si a més a més, els parametres encaixen amb el criteri de creació de sessió
			if ($_SESSION['hash'] == sha1($_SESSION['id'] . $this->encryptKey)) {
	    	return true;
			} else {
	    	return false;
			}
    } else {
    	return false;
    }
	}


	/**
	*
	* Comprueba si el email que se intenta registrar ya está en uso
	* @param string $email
	* @access private
	* @return boolean
	*/

	private function checkUserEmail($email)
	{
			$query = $this->dbConnection->prepare("SELECT id FROM users WHERE email = ?");
			$query->execute(array($email));
			if ($query->rowCount() == 0) {
					return false;
			} else {
					return true;
			}
	}


	/**
	*
	* Crea nou usuari i el desa a la base de dades si les dades rebudes son correctes
	* @param string $email
	* @param string $nickname
	* @param string $password
	* @return array $return
	*/

	public function register($email, $nickname, $password)
	{
		$return = array();
		//Si les dades rebudes no son correctes segons el tipus de parametres retornem error
		if (strlen($email) == 0 or strlen($email) < 3 or strlen($email) > 150) {
				$return['error'] = 1;
				$return['message'] = "Email no vàlid";
				return $return;
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$return['error'] = 1;
				$return['message'] = "Email no vàlid";
				return $return;
		} elseif (strlen($nickname) == 0 or strlen($nickname) < 3 or strlen($nickname) > 150) {
				$return['error'] = 1;
				$return['message'] = "Nickname no vàlid";
				return $return;
		} elseif (strlen($password) < 3 or strlen($password) > 60) {
				$return['error'] = 1;
				$return['message'] = "Password no vàlid";
				return $return;
		} else { //Si les dades son vàlides
			//comprovem l'email, que no estigui repetit
			if (!$this->checkUserEmail($email)) {
				//Netejem les dades rebudes per si de cas
				$username = htmlentities($nickname);
				$email = htmlentities($email);
				//Preparem l'encriptació del password
				$salt = substr(strtr(base64_encode(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)), '+', '.'), 0, 22);
				$bcrypt_cost = 10;
				$password = password_hash($password, PASSWORD_BCRYPT, ['salt' => $salt, 'cost' => $bcrypt_cost]);
				//Grabem les dades
				$query = $this->dbConnection->prepare("INSERT INTO users (email, password, nickname) VALUES (?, ?, ?)");
				$query->execute(array($email, $password, $nickname));
				$return['error'] = 0;
				$return['id'] = $this->dbConnection->lastInsertId();
				$return['email'] = $email;
				// S'inicia la sessió de l'usuari des d'aquest moment, donat que ja està registrat
				$_SESSION['nickname'] = $nickname;
				$_SESSION['id'] = $return['id'];
				$_SESSION['hash'] = sha1($return['id'] . $this->encryptKey);
				return $return;
			} else {
				//Si l'email ja estava registrat retornem un error
				$return['error'] = 1;
				$return['message'] = "Email ja registrat";
				return $return;
			}
		}
	}


	/**
	*
	* Actualitza les dades basiques d'un usuari
	*
	* @param string $nickname
	* @param string $bio
	* @param Int $userId
	* @return array $return
	*/

	public function update($nickname, $bio, $userId)
	{
		$return = array();
		//Si les dades rebudes no son correctes segons el tipus de parametres retornem error
		if (strlen($nickname) == 0 or strlen($nickname) < 3 or strlen($nickname) > 150) {
				$return['error'] = 1;
				$return['message'] = "Nickname no vàlid";
				return $return;
		} else {
				//Guardem les dades, i actualitzem la sessió
				$query = $this->dbConnection->prepare("UPDATE users SET nickname = ?, bio = ? WHERE id = ?");
				$query->execute(array($nickname, $bio, $userId));
				$return['error'] = 0;
				$_SESSION['nickname'] = $nickname;
				return $return;
		}
	}


	/**
	*
	* Actualitza la foto de perfil d'un usuari
	*
	* @param string $targetPath Ruta de la nova imatge
	* @param Int $userId
	*/

	public function updatePicture($targetPath, $userId)
	{
		//Guardem les dades, i actualitzem la sessió
		$query = $this->dbConnection->prepare("UPDATE users SET image = ? WHERE id = ?");
		$query->execute(array($targetPath, $userId));
	}


	/**
	* Tanca la sessió de l'usuari i reseteja les dades a zero
	*
	*/

	public function logout()
	{
	  $_SESSION = array();
    session_destroy();
	}


}

?>
