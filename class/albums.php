<?php

/**
* Albums
*  
* Aquesta classe engloba tot allò rel.lacionat amb la gestió dels albums
* @author Igualadins (Carlos, Pau i Xavier)
*/

class Albums
{
	private $dbConnection;

	// Al iniciar seteja la connexió amb la base de dades

	public function __construct(\PDO $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}


	/**
	* Llegeix els 10 albums amb mes favorits
	*
	* @return array amb totes les dades dels albums
	*/
        
	public function getTop10FavoritAlbums()
	{
		return array();
	}
        

	/**
	* Llegeix els 10 albums amb millor val.loració mitja
	*
	* @return array amb totes les dades dels albums
	*/

	public function getTop10RatedAlbums()
	{
		return array();
	}


	/**
	* Cerca albums segons el criteri de cerca, a la base de dades de l'aplicació
	*
	* @param name String nom de l'album a cercar
	* @return array amb totes les dades dels albums
	*/

	public function searchAlbumsByName($name)
	{
		return array();
	}


	/**
	* Retorna el detall d'informació d'un album concret a partir del seu id
	*
	* @param albumId Int identificador de l'album
	* @return array amb totes les dades de l'album
	*/

	public function getAlbumData($albumId)
	{
		return array();
	}


	/**
	* Retorna els albums d'un artista
	*
	* @param artistId Int identificador de l'artista
	* @return array amb tots els albums
	*/

	public function getAlbumsByArtist($artistId)
	{
		return array();
	}


	/**
	* Retorna l'id d'un album a partir del seu identificador public
	*
  * @param $mbId String Identificador public (de l'API de LastFM) de l'album
	* @return Int id de l'artista o 0 si no existeix
	*/

	public function getAlbumByMBID($mbId)
	{
		return 1;
	}

	/**
	* Crea un nou album
	*
  * @param $mbId String Identificador public (de l'API de LastFM) de l'album
  * @param $artistId Int Identificador de l'artista
  * @param $name String Nom de l'album
  * @param $releaseDate Date Data de llançament
  * @param $image String ruta de l'imatge de portada
  * @param $trackInfo String Llistat de cançons
	*/

	public function setAlbum($mbId,$artistId,$name,$releaseDate,$image,$trackInfo)
	{
		return true;
	}


	/**
	* Actualitza un album
	*
  * @param $albumId Int Identificador privat de l'album
  * @param $mbId String Identificador public (de l'API de LastFM) de l'album
  * @param $artistId Int Identificador de l'artista
  * @param $name String Nom de l'album
  * @param $releaseDate Date Data de llançament
  * @param $image String ruta de l'imatge de portada
  * @param $trackInfo String Llistat de cançons
	*/

	public function updateAlbum($albumId,$mbId,$artistId,$name,$releaseDate,$image,$trackInfo)
	{
		return true;
	}


	/**
	* Posa l'album com a favorit d'un usuari
	*
	* @param userId Int identificador de l'usuari
	* @param albumId Int identificador de l'album
	* @return  $userAlbumId Int identificador de la rel.lació
	*/

	public function setFavorite($userId,$albumId)
	{
		return 1;
	}


	/**
	* Treu l'album com a favorit d'un usuari
	*
	* @param userId Int identificador de l'usuari
	* @param albumId Int identificador de l'album
	*/

	public function unsetFavorite($userId,$albumId)
	{
		return true;
	}


	/**
	* Retorna els albums favorits d'un usuari
	*
	* @param userId Int identificador de l'usuari
	* @return array amb tots els albums
	*/

	public function getFavoriteAlbum($userId)
	{
		return array();
	}


	/**
	* Retorna els comentaris i valoracions d'un album
	*
	* @param albumId Int identificador de l'album
	* @return array amb tots els comentaris i valoracions
	*/

	public function getAlbumRatings($albumId)
	{
		return array();
	}


	/**
	* Retorna la valoració mitja d'un album
	*
	* @param albumId Int identificador de l'album
	* @return double Val.loració mitja de l'album
	*/

	public function getAlbumAverageRating($albumId)
	{
		return 3;
	}


	/**
	* Crea un comentaris i valoracio a un album (si l'usuari el té a favorits)
	*
	* @param userAlbumId Int identificador de la rel.lació usuari/album
	* @param rating Int valoració de l'album (de 0 a 5 estrelles)
	* @param userAlbumId String Comentari de l'usuari
	* @return array amb tots els comentaris i valoracions
	*/

	public function setAlbumRating($userId,$albumId,$rating,$comment)
	{
		return true;
	}


}

