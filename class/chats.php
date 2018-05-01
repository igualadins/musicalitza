<?php

/**
* Chats
*  
* Aquesta classe engloba tot allò rel.lacionat amb les converses entre usuaris
* @author Igualadins (Carlos, Pau i Xavier)
*/

class Chats
{
	private $dbConnection;
	private $userId;

	// Al iniciar seteja la connexió amb la base de dades i l'usuari que està gestionant

	public function __construct(\PDO $dbConnection, $userId)
	{
		$this->dbConnection = $dbConnection;
		$this->userId = $userId;
	}


	/**
	* Llegeix els chats que te un usuari
	*
	* @return array amb totes les dades dels chats
	*/

	public function getUserChats()
	{
		return array();
	}


	/**
	* Llegeix els missatgets d'un chat
	*
	* @param userToId id de l'usuari amb qui s'ha parlat
	* @return array amb tots els missatges del chat
	*/

	public function getChatHistory($userToId)
	{
		$query = $this->dbConnection->prepare(" SELECT * FROM userchats
																						WHERE userId = :userId AND userToId = :userToId
																						UNION ALL
																						SELECT * FROM userchats
																						WHERE userId = :userToId AND userToId = :userId
																						ORDER BY dateSent ASC");
		$query->bindParam(':userId', $this->userId, PDO::PARAM_INT);
		$query->bindParam(':userToId', $userToId, PDO::PARAM_INT);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}


	/**
	* Llegeix els missatgets d'un chat a partir l'identificador de missatge
	*
	* @param userToId id de l'usuari amb qui s'ha parlat
	* @param messageId identificador de l'ultim missatge
	* @return array amb tots els missatges del chat
	*/

	public function getChatHistoryFromMessageId($userToId,$messageId)
	{
		$query = $this->dbConnection->prepare(" SELECT * FROM userchats
                                                        WHERE userId = :userId AND userToId = :userToId AND id > :messageId
                                                        UNION ALL
                                                        SELECT * FROM userchats
                                                        WHERE userId = :userToId AND userToId = :userId AND id > :messageId
                                                        ORDER BY dateSent ASC");
		$query->bindParam(':userId', $this->userId, PDO::PARAM_INT);
		$query->bindParam(':userToId', $userToId, PDO::PARAM_INT);
		$query->bindParam(':messageId', $messageId, PDO::PARAM_INT);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}


	/**
	* Crea un nou missatge a un chat
	*
	* @param userToId id de l'usuari amb qui s'ha parlat
	* @param message String missatge enviat
	* @return boolean per indicar si ha anat be el procès
	*/

	public function sendChatMessage($userToId,$message)
	{
		$query = $this->dbConnection->prepare("INSERT INTO userchats (userId, userToId, message, messageRead, deleted) VALUES (?, ?, ?, 0, 0)");
		$query->execute(array($this->userId, $userToId, $message));
		return $this->dbConnection->lastInsertId();
	}


	/**
	* Marca un missatge com llegit
	*
	* @param $id id del missatge de chat a actualitzar
	* @return boolean per indicar si ha anat be el procès
	*/

	public function markAsRead($id)
	{
            $query = $this->dbConnection->prepare("UPDATE userchats SET messageRead=1 WHERE id = ?");
            $query->execute(array($id));            		
	}
        

}

