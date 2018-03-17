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
		$this->updateFriendsCount();
	}


	/**
	* Llegeix els usuaris que estàn acceptats
	*
	* @return array amb totes les dades dels usuaris
	*/

	public function getUserAcceptedFriends()
	{
		return array();
	}


	/**
	* Llegeix els usuaris que NO estàn acceptats
	*
	* @return array amb totes les dades dels usuaris
	*/

	public function getUserNotAcceptedFriends()
	{
		return array();
	}



	/**
	* Llegeix els usuaris bloquejats
	*
	* @return array amb totes les dades dels usuaris
	*/

	public function getUserBlockedFriends()
	{
		return array();
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
		return true;
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
		return true;
	}


	/**
	* Actualitza l'estat d'un bloqueig d'amistat
	*
	* @param int $friendId identificador d'usuari al que volem eliminar d'amics
	* @return boolean per indicar si ha anat correctament el procès
	*/

	public function deleteFriend($friendId)
	{
		return true;
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

