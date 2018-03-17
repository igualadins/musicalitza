<?php

/**
* Artists
*  
* Aquesta classe engloba tot allò rel.lacionat amb la gestió dels artistes
* @author Igualadins (Carlos, Pau i Xavier)
*/

class Artists
{
	private $dbConnection;
	private $albums;

	// Al iniciar seteja la connexió amb la base de dades i també referenciem l'objecte de la clase albums que farem servir internament

	public function __construct(\PDO $dbConnection, $albums)
	{
		$this->dbConnection = $dbConnection;
		$this->$albums = $albums;
	}


	/**
	* Llegeix els 10 artistes amb mes favorits
	*
	* @return array amb totes les dades dels artistes
	*/

	public function getTop10FavoritArtists()
	{
		return array();
	}


	/**
	* Llegeix els 10 artistes amb millor val.loració mitja
	*
	* @return array amb totes les dades dels artistes
	*/

	public function getTop10RatedArtists()
	{
		return array();
	}


	/**
	* Cerca artistes segons el criteri de cerca, a la base de dades de l'aplicació
	*
	* @param name String nom de l'artista a cercar
	* @return array amb totes les dades dels artistes
	*/

	public function searchArtistsByName($name)
	{
		return array();
	}


	/**
	* Cerca artistes segons el criteri de cerca, a l'API de LastFM
	*
	* @param name String nom de l'artista a cercar
	* @return array amb totes les dades dels artistes rebudes
	*/

	public function searchArtistsByNameInAPI($name)
	{
		return array();
	}


	/**
	* Retorna el detall d'informació d'un artista concret a partir del seu id
	*
	* @param artistId Int identificador de l'artista
	* @return array amb totes les dades de l'artista
	*/

	public function getArtistData($artistId)
	{
		return array();
	}


	/**
	* Retorna l'id d'un artista a partir del seu identificador public
	*
  * @param $mbId String Identificador public (de l'API de LastFM) de l'artista
	* @return Int id de l'artista o 0 si no existeix
	*/

	public function getArtistByMBID($mbId)
	{
		return 1;
	}


	/**
	* Retorna l'info d'un artista a partir del seu identificador public desde l'API de LastFM
	*
  * @param $mbId String Identificador public (de l'API de LastFM) de l'artista
	* @return Int id de l'artista o 0 si no existeix
	*/

	public function getArtistDataByMBIDFromAPI($mbId)
	{
		return 1;
	}


	/**
	* Importa un nou artista a la bbdd i també tots els seus discos desde l'API de LastFM a partir del seu mbId
	*
  * @param $mbId String Identificador public (de l'API de LastFM) de l'artista
	* @return $artistId Int identificador de l'artista
	*/

	public function setArtistAndAlbums($mbId)
	{
		return 1;
	}


	/**
	* Actualitza un artista i també tots els seus discos desde l'API de LastFM a partir del seu mbId
	*
  * @param $artistId Int Identificador privat de l'artista
  * @param $mbId String Identificador public (de l'API de LastFM) de l'artista
	*/

	public function updateArtistAndAlbums($artistId,$mbId)
	{
		return true;
	}


	/**
	* Posa l'artista com a favorit d'un usuari
	*
	* @param userId Int identificador de l'usuari
	* @param artistId Int identificador de l'artista
	* @return $userArtistId Int identificador de la rel.lació
	*/

	public function setFavorite($userId,$artistId)
	{
		return 1;
	}


	/**
	* Treu l'artista com a favorit d'un usuari
	*
	* @param userId Int identificador de l'usuari
	* @param artistId Int identificador de l'artista
	*/

	public function unsetFavorite($userId,$artistId)
	{
		return true;
	}


	/**
	* Retorna els artistes favorits d'un usuari
	*
	* @param userId Int identificador de l'usuari
	* @return array amb tots els artistes
	*/

	public function getFavoriteArtists($userId)
	{
		return array();
	}


	/**
	* Retorna els comentaris i valoracions d'un artista
	*
	* @param artistId Int identificador de l'artista
	* @return array amb tots els comentaris i valoracions
	*/

	public function getArtistRatings($artistId)
	{
		return array();
	}


	/**
	* Retorna la valoració mitja d'un artista
	*
	* @param artistId Int identificador de l'artista
	* @return double Val.loració mitja de l'artista
	*/

	public function getArtistAverageRating($artistId)
	{
		return 3;
	}


	/**
	* Crea un comentaris i valoracio a un artista (si l'usuari el té a favorits)
	*
	* @param userArtistId Int identificador de la rel.lació usuari/artista
	* @param rating Int valoració de l'artista (de 0 a 5 estrelles)
	* @param userArtistId String Comentari de l'usuari
	* @return array amb tots els comentaris i valoracions
	*/

	public function setArtistRating($userId,$artistId,$rating,$comment)
	{
		return true;
	}


}

