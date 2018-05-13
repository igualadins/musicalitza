<?php

/**
* Friends
*  
* Aquesta classe engloba tot allò rel.lacionat amb la gestió d'amics, sol.licituds d'amistat, ...
* @author Igualadins (Carlos, Pau i Xavier)
*/

class Friends
{
	private $dbConnection;
	private $userId;
	public $acceptedFriends;
	public $pendingFriends;
	public $pendingRequests;
	public $blockedFriends;

	// Al iniciar seteja la connexió amb la base de dades, l'usuari que està gestionant i les estadistiques d'amistat (nombre d'usuaris en cada estat)

	public function __construct(\PDO $dbConnection, $userId)
	{            
		$this->dbConnection = $dbConnection;
		$this->userId = $userId;
		// Actualitzem les estadistiques d'amics al construir l'objecte
		$this->updateFriendsCount();
	}


	/**
	* Llegeix els usuaris que estàn acceptats, que seran aquells que accepted=1 i blocked=0
	*
	* @return array amb totes les dades dels usuaris
	*/
	public function getUserAcceptedFriends()
	{        
    $consulta = "SELECT u.nickname as nom, u.image as imagen, u.id as friendId, 1 as canblock "
            . "FROM userfriends us LEFT JOIN users u on (us.friendId=u.id) "
            . "WHERE us.userId = :userId AND us.accepted = 1 AND us.blocked = 0"
            . " UNION "
            . "SELECT u.nickname as nom, u.image as imagen, u.id as friendId, 0 as canblock "
            . "FROM userfriends us LEFT JOIN users u on (us.userId=u.id) "
            . "WHERE us.friendId = :userId AND us.accepted = 1 AND us.blocked = 0";
    $query = $this->dbConnection->prepare($consulta);         
    $query->bindParam(':userId',  $this->userId, PDO::PARAM_INT);                          
    $query->execute();
    $dataSet = $query->fetchAll(PDO::FETCH_ASSOC); 
    return $dataSet;
	}


	/**
	* Llegeix les solicituds rebudes per ser acceptades.
	* (Seran aquelles, en que el usuari login es igual al friendId, i accepted=0 i blocked=0)
	*
	* @return array amb totes les dades dels usuaris
	*/
	public function getUserNotAcceptedFriends()
	{	
    $consulta = "SELECT u.nickname as nom, u.image as imagen, u.id as friendId "
            . "FROM userfriends us LEFT JOIN users u on (us.userId=u.id) "
            . "WHERE us.friendId = :userId AND us.accepted = 0 AND us.blocked = 0";
    $query = $this->dbConnection->prepare($consulta);         
    $query->bindParam(':userId',  $this->userId, PDO::PARAM_INT);
    $query->execute();
    $dataSet = $query->fetchAll(PDO::FETCH_ASSOC); 
    return $dataSet;
	}
        
  /**
	* Llegeix les solicituds enviades per ser acceptades.
  * (Seran aquelles, en que el usuari login es igual al userId, i accepted=0 i blocked=0)
	*
	* @return array amb totes les dades dels usuaris
	*/
	public function getFriendsNotAcceptedUser()
	{	
    $consulta = "SELECT u.nickname as nom, u.image as imagen, u.id as friendId "
            . "FROM userfriends us LEFT JOIN users u on (us.friendId=u.id) "
            . "WHERE us.userId = :userId AND us.accepted = 0 AND us.blocked = 0";
    $query = $this->dbConnection->prepare($consulta);         
    $query->bindParam(':userId',  $this->userId, PDO::PARAM_INT);
    $query->execute();
    $dataSet = $query->fetchAll(PDO::FETCH_ASSOC); 
    return $dataSet;
	}

	/**
	* Llegeix els usuaris bloquejats
	* (seran aquells que blocked=1, per tant poden tenir accepted=1 i 0)
	*
	* @return array amb totes les dades dels usuaris
	*/
	public function getUserBlockedFriends()
	{		
    $query = $this->dbConnection->prepare("SELECT u.nickname as nom, u.image as imagen, u.id as friendId FROM userfriends us LEFT JOIN users u on (us.friendId=u.id) WHERE us.userId = :userId AND us.blocked = 1");         
    $query->bindValue(':userId',  $this->userId, PDO::PARAM_INT);   
    $query->execute();
    $dataSet = $query->fetchAll(PDO::FETCH_ASSOC);
    return $dataSet;
	}


	/**
	* Retorna un array amb totes les id d'usuaris que tenen alguna rel.lació amb l'usuari 
	* (amics, a l'espera o bloquejats)
	*
	* @return array amb totes les id dels usuaris
	*/
	public function getUserFriendsRelationIdList()
	{		
	  $query = $this->dbConnection->prepare("SELECT u.id FROM userfriends us LEFT JOIN users u on (us.friendId=u.id) WHERE us.userId = :userId UNION SELECT u.id FROM userfriends us LEFT JOIN users u on (us.friendId = :userId)");
	  $query->bindValue(':userId',  $this->userId, PDO::PARAM_INT);   
	  $query->execute();
	  $dataSet = $query->fetchAll(PDO::FETCH_ASSOC);
	  return $dataSet;
	}


	/**
	* Actualitza els valors numèrics dels amics en cada estat 
	* ($acceptedFriends, $pendingFriends, $blockedFriends)
	* No te retorn de dades, perque actualitza les propietats de l'objecte
	*
	*/

	public function updateFriendsCount()
	{
		// Recompte d'amics
		$query = $this->dbConnection->prepare(" SELECT SUM(TMPTABLE.amics) AS acceptedFriends FROM
																						( SELECT count(u.id) AS amics
																							FROM userfriends us LEFT JOIN users u on (us.friendId=u.id)
																							WHERE us.userId = :userId AND us.accepted = 1 AND us.blocked = 0
																							UNION ALL
																							SELECT count(u.id)
																							FROM userfriends us LEFT JOIN users u on (us.userId=u.id)
																							WHERE us.friendId = :userId AND us.accepted = 1 AND us.blocked = 0) AS TMPTABLE");
    $query->bindParam(':userId',  $this->userId, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC); 
		$this->acceptedFriends = $result['acceptedFriends'];

		// Recompte de sol.licituds rebudes pendents
    $query = $this->dbConnection->prepare(" SELECT count(u.id) as pendingFriends
																						FROM userfriends us LEFT JOIN users u on (us.userId=u.id) 
																						WHERE us.friendId = :userId AND us.accepted = 0 AND us.blocked = 0");
    $query->bindParam(':userId',  $this->userId, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC); 
    $this->pendingFriends = $result['pendingFriends'];

		// Recompte de sol.licituds enviades pendents
    $query = $this->dbConnection->prepare(" SELECT count(u.id) as pendingRequests
																						FROM userfriends us LEFT JOIN users u on (us.friendId=u.id) 
																						WHERE us.userId = :userId AND us.accepted = 0 AND us.blocked = 0");
    $query->bindParam(':userId',  $this->userId, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC); 
    $this->pendingRequests = $result['pendingRequests'];

		// Recompte d'amics bloquejats
    $query = $this->dbConnection->prepare("SELECT count(u.id) AS blockedFriends FROM userfriends us LEFT JOIN users u on (us.friendId=u.id) WHERE us.userId = :userId AND us.blocked = 1");         
    $query->bindValue(':userId',  $this->userId, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
		$this->blockedFriends = $result['blockedFriends'];
	}


	/**
	* Retorna 1 si hi ha registres d'amistat entre 2 usuaris, o 0 si no n'hi ha
	*
	* @param int $friendId identificador d'usuari amb el que es vol comprovar amistat
	* @return boolean per indicar si hi ha rel.lació
	*/

	public function checkFriendShip($friendId)
	{
		$query = $this->dbConnection->prepare("SELECT us.id FROM userfriends us WHERE (us.userId = :userId AND us.friendId = :friendId) OR (us.userId = :friendId AND us.friendId = :userId)");
	  $query->bindValue(':userId',  $this->userId, PDO::PARAM_INT);   
	  $query->bindValue(':friendId', $friendId, PDO::PARAM_INT);   
	  $query->execute();
	  if ($query->rowCount() > 0) {
	  	return 1;
	  } else {
			return 0;
	  }
	}

	/**
	* Envia una nova sol.licitud d'amistat a un usuari
	*
	* @param int $friendId identificador d'usuari al que es vol enviar la sol.licitud
	* @return boolean per indicar si ha anat correctament el procès
	*/

	public function sendRequestToFriend($friendId)
	{
		if(!$this->checkFriendShip($friendId)) { // Si no hi ha ja una rel.lació previa, fem el procès
		  $query = $this->dbConnection->prepare("INSERT INTO userfriends (id, userId, friendId, accepted, blocked) VALUES ('', :userId, :friendId, 0, 0)");
		  $query->bindValue(':userId',  $this->userId, PDO::PARAM_INT);   
		  $query->bindValue(':friendId', $friendId, PDO::PARAM_INT);   
		  $query->execute();
			if($query->errorCode() == 0) { // Si no hi ha cap problema, retornem l'identificador de la rel.lació                                        
				return 1;
			} else {                    
	      	return -1; 
			}
		}
	}


	/**
	* Actualitza l'estat de l'acceptació d'amistat
	*
	* @param int $friendId identificador d'usuari al que es vol enviar la sol.licitud
	* @param boolean $accepted indica l'acció a realitzar (false no acceptat, true acceptat)
	* @return boolean per indicar si ha anat correctament el procès
	*/
	public function updateAcceptFriend($friendId,$accepted)
	{
		$query = $this->dbConnection->prepare("UPDATE userfriends SET accepted=? WHERE userId=? AND friendId=?");
		$query->execute(array( $accepted, $friendId, $this->userId ));
		if($query->errorCode() == 0) { // Si no hi ha cap problema, retornem l'identificador de la rel.lació                                        
			return 1;
		} else {                    
			return -1; 
		}
	}


	/**
	* Actualitza l'estat d'un bloqueig d'amistat
	*
	* @param int $friendId identificador d'usuari al que es vol enviar la sol.licitud
	* @param boolean $blocked indica l'acció a realitzar (false desbloquejar, true bloquejar)
	* @return boolean per indicar si ha anat correctament el procès
	*/
	public function updateBlockFriend($friendId,$blocked)
	{		                
    $consulta = "UPDATE userfriends SET blocked=? "
            . "WHERE (userId=? AND friendId=?) OR "
            . "(userId=? AND friendId=?)";
		$query = $this->dbConnection->prepare($consulta);
		$query->execute(array( $blocked, $this->userId, $friendId, $friendId, $this->userId ));
		if($query->errorCode() == 0) { 
			return 1;
		} 
		return -1;
	}


	/**
	* Actualitza l'estat d'un bloqueig d'amistat
	*
	* @param int $friendId identificador d'usuari al que volem eliminar d'amics
	* @return boolean per indicar si ha anat correctament el procès
	*/
	public function deleteFriend($friendId)
	{
    $consulta = "DELETE FROM userfriends "
            . "WHERE (userId=? AND friendId=?) OR "
            . "(userId=? AND friendId=?)";
		$query = $this->dbConnection->prepare($consulta);
		$query->execute(array( $this->userId, $friendId, $friendId, $this->userId ));
		if($query->errorCode() == 0) { // Si no hi ha cap problema, retornem l'identificador de la rel.lació                                        
			return 1;
		} 
		return -1;
	}


	/**
	* Funció que retorna suggeriments d'amics (que no ho siguin ja) a partir dels teus gustos (artistes+albums)
	*
	* @return array amb totes les dades dels usuaris suggerits
	*/
	public function suggestFriends()
	{
    $consulta = "SELECT usuaris.userId as suggestUser, " 
                    . "ROUND(COUNT(usuaris.userId)*100/((SELECT COUNT(albumId) FROM useralbums WHERE userId= :userId) + (SELECT COUNT(artistId) FROM userartists WHERE userId= :userId)),2) as afinitat, "                              
                    . "u.id as id, u.nickname as nom, u.image as imagen FROM " 
                    . "(SELECT userId FROM useralbums WHERE albumId IN (SELECT albumId FROM useralbums WHERE userId= :userId) AND userId<> :userId "
                    . "UNION ALL "
                    . "SELECT userId FROM userartists WHERE artistId IN (SELECT artistId FROM userartists WHERE userId= :userId) AND userId<> :userId) "
                    . " usuaris "
                    . "LEFT JOIN users u on (usuaris.userId=u.id) "
                    . "LEFT JOIN userfriends uf1 on (usuaris.userId=uf1.userId) "
                    . "LEFT JOIN userfriends uf2 on (usuaris.userId=uf2.friendId) "
                    . "WHERE uf1.userId is null and uf2.friendId is null "
                    . "GROUP BY usuaris.userId "
                    . "ORDER BY afinitat DESC "
                    . "LIMIT 10";
    $query = $this->dbConnection->prepare($consulta);         
    $query->bindParam(':userId',  $this->userId, PDO::PARAM_INT);
    $query->execute();
    $dataSet = $query->fetchAll(PDO::FETCH_ASSOC); 
    return $dataSet;            
	}

}