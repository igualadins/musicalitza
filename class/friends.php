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
	public $blockedFriends;

	// Al iniciar seteja la connexió amb la base de dades, l'usuari que està gestionant i les estadistiques d'amistat (nombre d'usuaris en cada estat)

	public function __construct(\PDO $dbConnection, $userId)
	{            
		$this->dbConnection = $dbConnection;
		$this->userId = $userId;
                // PENDENT, he comentat linia pq donva error
		//$this->updateFriendsCount();
	}


	/**
	* Llegeix els usuaris que estàn acceptats, que seran aquells que accepted=1 i blocked=0
	*
	* @return array amb totes les dades dels usuaris
	*/
	public function getUserAcceptedFriends()
	{        
            $consulta = "SELECT u.nickname as nom, u.image as imagen, u.id as friendId "
                    . "FROM userfriends us LEFT JOIN users u on (us.friendId=u.id) "
                    . "WHERE us.userId = :userId AND us.accepted = 1 AND us.blocked = 0"
                    . " UNION "
                    . "SELECT u.nickname as nom, u.image as imagen, u.id as friendId "
                    . "FROM userfriends us LEFT JOIN users u on (us.userId=u.id) "
                    . "WHERE us.friendId = :userId AND us.accepted = 1 AND us.blocked = 0";
            $query = $this->dbConnection->prepare($consulta);         
            $query->bindParam(':userId',  $this->userId, PDO::PARAM_INT);                          
            //echo $this->userId;
            //$query->execute(array($this->userId));
            $query->execute();
            //$query->debugDumpParams();
            $dataSet = $query->fetchAll(PDO::FETCH_ASSOC); 
            //var_dump($dataSet);
            return $dataSet;
	}


	/**
	* Llegeix les solicituds rebudes per ser acceptades.
        * Seran aquelles, en que el usuari login es igual al friendId, i accepted=0 i blocked=0
	*
	* @return array amb totes les dades dels usuaris
	*/
	public function getUserNotAcceptedFriends()
	{	
            /*
            $query = $this->dbConnection->prepare("SELECT u.nickname as nom, u.image as imagen, u.id as friendId FROM userfriends us LEFT JOIN users u on (us.friendId=u.id) WHERE us.userId = :userId AND us.accepted = 0 AND us.blocked = 0");         
            $query->bindValue(':userId',  $this->userId, PDO::PARAM_INT);   
            $query->execute();
            $dataSet = $query->fetchAll(PDO::FETCH_ASSOC);
            return $dataSet;             
             */
            
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
        * Seran aquelles, en que el usuari login es igual al userId, i accepted=0 i blocked=0
	*
	* @return array amb totes les dades dels usuaris
	*/
	public function getFriendsNotAcceptedUser()
	{	
            /*
            $query = $this->dbConnection->prepare("SELECT u.nickname as nom, u.image as imagen, u.id as friendId FROM userfriends us LEFT JOIN users u on (us.friendId=u.id) WHERE us.userId = :userId AND us.accepted = 0 AND us.blocked = 0");         
            $query->bindValue(':userId',  $this->userId, PDO::PARAM_INT);   
            $query->execute();
            $dataSet = $query->fetchAll(PDO::FETCH_ASSOC);
            return $dataSet;             
             */
            
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
	* Llegeix els usuaris bloquejats, que seran aquells que blocked=1, per tant poden tenir accepted=1 i 0
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
	* Actualitza els valors numèrics dels amics en cada estat ($acceptedFriends, $pendingFriends, $blockedFriends)
	*
	*/

	public function updateFriendsCount()
	{
		$this->$acceptedFriends=1; 
		$this->$pendingFriends=1;
		$this->$blockedFriends=1;
		return true;
	}


	/**
	* Envia una nova sol.licitud d'amistat a un usuari
	*
	* @param int $friendId identificador d'usuari al que es vol enviar la sol.licitud
	* @return boolean per indicar si ha anat correctament el procès
	*/

	public function sendRequestToFriend($friendId)
	{
		return true;
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
		//$result = 
                        $query->execute(array( $blocked, $this->userId, $friendId, $friendId, $this->userId ));

		if($query->errorCode() == 0) { 
                    //if($result){ // hem fet el update
                        return 1;
                    //}                   
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
                //echo $friendId . " " . $this->userId;
                $consulta = "DELETE FROM userfriends "
                        . "WHERE (userId=? AND friendId=?) OR "
                        . "(userId=? AND friendId=?)";
		$query = $this->dbConnection->prepare($consulta);
		//$result = 
                        $query->execute(array( $this->userId, $friendId, $friendId, $this->userId ));

		if($query->errorCode() == 0) { // Si no hi ha cap problema, retornem l'identificador de la rel.lació                                        
                    //if($result){ // hem esborrat
                        return 1;
                    //} 
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
		return array();
	}


	/**
	* Funció que retorna suggeriments d'amics (que no ho siguin ja) a partir d'un album
	*
	* @param int $albumId identificador d'un album sobre el que cercar afinitats
	* @return array amb totes les dades dels usuaris suggerits
	*/

	public function suggestFriendsByAlbumId($albumId)
	{
		return array();
	}


	/**
	* Funció que retorna suggeriments d'amics (que no ho siguin ja) a partir d'un artista
	*
	* @param int $artistd identificador d'un artista sobre el que cercar afinitats
	* @return array amb totes les dades dels usuaris suggerits
	*/

	public function suggestFriendsByArtistId($artistId)
	{
		return array();
	}


}

