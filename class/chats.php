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
	* @param userId id de l'usuari amb qui s'ha parlat
	* @return array amb tots els missatges del chat
	*/

	public function getChatHistory($userId)
	{
		return array();
	}


	/**
	* Crea un nou missatge a un chat
	*
	* @param userId id de l'usuari amb qui s'ha parlat
	* @return boolean per indicar si ha anat be el procès
	*/

	public function sendChatMessage($userId,$message)
	{
		return true;
	}


	/**
	* Marca un missatge com llegit
	*
	* @param $id id del missatge de chat a actualitzar
	* @return boolean per indicar si ha anat be el procès
	*/

	public function markAsRead($id)
	{
		return true;
	}


}

