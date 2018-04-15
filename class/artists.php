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

	// Al iniciar seteja la connexió amb la base de dades i també referenciem l'objecte de la clase albums que farem servir internament

	public function __construct(\PDO $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}


	/**
	* Llegeix els 10 artistes amb mes favorits
	*
	* @return array amb totes les dades dels artistes
	*/

	public function getTop10FavoritArtists() //FALTA
	{
		return array();
	}


	/**
	* Llegeix els 10 artistes amb millor val.loració mitja
	*
	* @return array amb totes les dades dels artistes
	*/

	public function getTop10RatedArtists() //FALTA
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
		$query = $this->dbConnection->prepare(" SELECT a.id, a.mbid, a.name, a.image, count(ua.id) as likes FROM artist a
																						LEFT JOIN userartists ua ON ua.artistId = a.id
																						WHERE a.name LIKE ?
																						GROUP BY a.id
																						ORDER BY a.name ASC");
		$query->execute(array("%$name%"));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}


	/**
	* Retorna l'info dels albums que te un artista a la bbdd
	*
	* @param artistId Int identificador de l'artista
	* @return array amb totes les dades dels albums
	*/

	public function getArtistAlbums($artistId)
	{
		$query = $this->dbConnection->prepare(" SELECT a.id, a.mbid, a.name, a.image, count(ua.id) as likes FROM album a
																						LEFT JOIN artist ar ON ar.id = a.artistId
																						LEFT JOIN useralbums ua ON ua.albumId = a.id
																						WHERE ar.id LIKE ?
																						GROUP BY a.id
																						ORDER BY a.name ASC");
		$query->execute(array($artistId));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}


	/**
	* Cerca artistes segons el criteri de cerca, a l'API de LastFM
	*
	* @param name String nom de l'artista a cercar
	* @return array amb totes les dades dels artistes rebudes
	*/

	public function searchArtistsByNameInAPI($name) 
	{

		// Construim els paràmetres de url per fer la crida a l'API de LastFM
		// Amb la api_key que hem generat i el mètode artist.search
		$queryParams = http_build_query([
		 'method' => 'artist.search',
		 'artist' => $name,
		 'limit' => 100,
		 'api_key' => 'c2cef55c7ff22d821abe2b6c2529747e',
		 'format' => 'json'
		]);

		// Contruim la url a on farem la crida
		$url = "http://ws.audioscrobbler.com/2.0/?".$queryParams;

		// Iniciem curl per fer la petició
		$ch = curl_init();
		// Deshabilitem SSL perque l'api no ho fa servir
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Per obtenir el retorn de la resposta
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Li indiquem la url on fer la petició
		curl_setopt($ch, CURLOPT_URL, $url);
		// Executem la petició
		$APIresult=curl_exec($ch);
		// Tanquem la connexió
		curl_close($ch);
		// Decodifiquem el JSON rebut
		$APIresult = json_decode($APIresult, true);

		$return = array();

		// Si troba resultats els tractem abans de retornar
		if ($APIresult['results']['opensearch:totalResults'] > 0) {

			foreach ($APIresult['results']['artistmatches']['artist'] as $artist) {
				$tmpArtist = array();
				// nomès artistes que tinguin l'identificador public de lastFM, n'hi ha que no ho tenen perque no tenen molta info o no estàn verificats
				// Només artistes que no estiguin ja a la nostra bbdd
				if (strlen($artist['mbid']) && $this->getArtistIdByMBID($artist['mbid']) == 0) { 					
					// Construim un array per cada artista, nomès amb les dades que necessitem
					$tmpArtist['name'] = $artist['name'];
					$tmpArtist['mbid'] = $artist['mbid'];
					if( count($artist['image']) > 2 ) {
						$tmpArtist['image'] = $artist['image'][3]['#text']; // Si tenim l'imatge "extralarge" millor
					} else if ( count($artist['image']) ) { 
						$tmpArtist['image'] = $artist['image'][0]['#text']; // Si no la tenim, posem la primera que arriba
					} else {
						$tmpArtist['image'] = '';
					}
					if (strlen($tmpArtist['image'])) array_push($return, $tmpArtist); // Afegim l'artista a l'array que retornarem (Nomès si te imatge. Si no, no l'afegim...)
				}
			}

		}

		// Retornem el nostre array d'artistes fet del resultat de l'API de LastFM
		return $return;

	}


	/**
	* Retorna el detall d'informació d'un artista concret a partir del seu id
	*
	* @param artistId Int identificador de l'artista
	* @return array amb totes les dades de l'artista
	*/

	public function getArtistData($artistId)
	{
		$query = $this->dbConnection->prepare(" SELECT a.id, a.name, a.image, a.bio, count(ua.id) as likes FROM artist a
																						LEFT JOIN userartists ua ON ua.artistId = a.id
																						WHERE a.id = ?
																						GROUP BY a.id");
		$query->execute(array($artistId));
		return $query->fetch(PDO::FETCH_ASSOC);
	}


	/**
	* Retorna l'id d'un artista a partir del seu identificador public
	*
  * @param $mbId String Identificador public (de l'API de LastFM) de l'artista
	* @return Int id de l'artista o 0 si no existeix
	*/

	public function getArtistIdByMBID($mbId)
	{	
		$query = $this->dbConnection->prepare("SELECT id FROM artist WHERE mbid = ?");
		$query->execute(array($mbId));
		$return = 0;
		if ($query->rowCount() > 0) {
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$return = $data['id'];
		}		
		return $return;
	}


	/**
	* Retorna l'info d'un artista a partir del seu identificador public desde l'API de LastFM
	*
  * @param $mbId String Identificador public (de l'API de LastFM) de l'artista
	* @return Array amb les dades
	*/

	public function getArtistDataByMBIDFromAPI($mbId)
	{
		
		// Construim els paràmetres de url per fer la crida a l'API de LastFM
		// Amb la api_key que hem generat i el mètode artist.search
		$queryParams = http_build_query([
		 'method' => 'artist.getinfo',
		 'mbid' => $mbId,
		 'api_key' => 'c2cef55c7ff22d821abe2b6c2529747e',
		 'lang' => 'es',
		 'format' => 'json'
		]);

		// Contruim la url a on farem la crida
		$url = "http://ws.audioscrobbler.com/2.0/?".$queryParams;

		// Iniciem curl per fer la petició
		$ch = curl_init();
		// Deshabilitem SSL perque l'api no ho fa servir
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Per obtenir el retorn de la resposta
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Li indiquem la url on fer la petició
		curl_setopt($ch, CURLOPT_URL, $url);
		// Executem la petició
		$APIresult=curl_exec($ch);
		// Tanquem la connexió
		curl_close($ch);
		// Decodifiquem el JSON rebut
		$APIresult = json_decode($APIresult, true);

		return $APIresult;
	}


	/**
	* Importa un nou artista a la bbdd desde l'API de LastFM a partir del seu mbId
	*
  * @param $mbId String Identificador public (de l'API de LastFM) de l'artista
	* @return $artistId Int identificador de l'artista
	*/

	public function setArtist($mbId) 
	{
		// Obtenim l'info de l'artista i el guardem
		$artist = $this->getArtistDataByMBIDFromAPI($mbId);
		$artist = $artist['artist'];

		if( count($artist['image']) > 2 ) {
			$image = $artist['image'][3]['#text']; // Si tenim l'imatge "extralarge" millor
		} else if ( count($artist['image']) ) { 
			$image = $artist['image'][0]['#text']; // Si no la tenim, posem la primera que arriba
		} else {
			$image = '';
		}

		try {
			$saveImg = 'img/artists/'.$mbId.'.png'; // Ruta on guardarem l'imatge de l'artista
			file_put_contents($saveImg, file_get_contents($image)); // Guardem l'imatge
		} catch (Exception $e) { // Si hi ha algun error en el procès parem l'execució
			var_dump($e->getMessage());
			die();
		}

		// Preparem la query per guardar l'artista
		$query = $this->dbConnection->prepare("INSERT INTO artist (id, mbid, name, bio, image) VALUES ('', ?, ?, ?, ?)");
		$query->execute(array( $mbId, $artist['name'], $artist['bio']['content'], $saveImg ));

		if($query->errorCode() == 0) { // Si no hi ha cap problema, guardem l'id del nou artista
			return $this->dbConnection->lastInsertId();
		} else {
			return 0; // Si hi ha algun error en el procès retornem 0
		}

	}


	/**
	* Actualitza un artista desde l'API de LastFM a partir del seu mbId
	*
  * @param $artistId Int Identificador privat de l'artista
  * @param $mbId String Identificador public (de l'API de LastFM) de l'artista
	*/

	public function updateArtist($artistId,$mbId)//FALTA
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
		
		// Preparem la query per guardar l'artista a favorits
		$query = $this->dbConnection->prepare("INSERT INTO userartists (id, userId, artistId, priority) VALUES ('', ?, ?, 1)");
		$query->execute(array( $userId, $artistId ));

		if($query->errorCode() == 0) { // Si no hi ha cap problema, retornem l'identificador de la rel.lació
			return $this->dbConnection->lastInsertId();
		} else {
			return 0; // Si hi ha algun error en el procès retornem 0
		}

	}


	/**
	* Treu l'artista com a favorit d'un usuari
	*
	* @param userId Int identificador de l'usuari
	* @param artistId Int identificador de l'artista
	*/

	public function unsetFavorite($userId,$artistId) 
	{
		// Preparem la query per guardar l'artista a favorits
		$query = $this->dbConnection->prepare("DELETE FROM userartists WHERE userId = ? AND artistId = ?");
		$query->execute(array( $userId, $artistId ));
	}


	/**
	* Retorna els artistes favorits d'un usuari
	*
	* @param userId Int identificador de l'usuari
	* @return array amb tots els artistes
	*/

	public function getFavoriteArtists($userId) 
	{
		$query = $this->dbConnection->prepare(" SELECT a.id, a.name, a.image, (select count(*) from userartists where artistId = a.id) as likes 
																						FROM artist a, userartists ua
																						WHERE ua.userId = ? AND a.id = ua.artistId
																						ORDER BY a.name");
		$query->execute(array($userId));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}


	/**
	* Retorna els comentaris i valoracions d'un artista
	*
	* @param artistId Int identificador de l'artista
	* @return array amb tots els comentaris i valoracions
	*/

	public function getArtistRatings($artistId)
	{
		$query = $this->dbConnection->prepare(" SELECT ar.rating, ar.comment, u.nickname, u.image 
																						FROM userartistratings ar, userartists ua, users u
																						WHERE ua.artistId = ? AND ua.id = ar.userArtistId AND u.id = ua.userId
																						ORDER BY ar.id");
		$query->execute(array($artistId));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}


	/**
	* Retorna la valoració mitja d'un artista
	*
	* @param artistId Int identificador de l'artista
	* @return double Val.loració mitja de l'artista
	*/

	public function getArtistAverageRating($artistId)
	{
		$query = $this->dbConnection->prepare(" SELECT FORMAT ((SUM(ar.rating) / COUNT(ar.id)), 1) as rating
																						FROM userartistratings ar, userartists ua
																						WHERE ua.artistId = ? AND ua.id = ar.userArtistId");
		$query->execute(array($artistId));
		$return = 0;
		if ($query->rowCount() > 0) {
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$return = $data['rating'];
		}		
		return $return;
	}


	/**
	* Crea un comentaris i valoracio a un artista (si l'usuari el té a favorits)
	*
	* @param userArtistId Int identificador de la rel.lació usuari/artista
	* @param rating Int valoració de l'artista (de 0 a 5 estrelles)
	* @param comment String Comentari de l'usuari
	* @return array amb tots els comentaris i valoracions
	*/

	public function setArtistRating($userArtistId,$rating,$comment)
	{
		$query = $this->dbConnection->prepare("INSERT INTO userartistratings (userArtistId, rating, comment) VALUES (?, ?, ?) ");
		$query->execute(array($userArtistId,$rating,$comment));
	}


	/**
	* Retorna els usuaris que han marcat com favorit a l'artista
	*
	* @param artistId Int identificador de l'artista
	* @return array amb tots els artistes
	*/

	public function getArtistUsersRelated($artistId) 
	{
		$query = $this->dbConnection->prepare(" SELECT u.id, u.nickname, u.image 
																						FROM userartists ua, users u
																						WHERE ua.artistId = ? AND u.id = ua.userId
																						ORDER BY u.nickname");
		$query->execute(array($artistId));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}


	/**
	* Retorna l'id de rel.lació d'un usuari amb un artista
	*
	* @param userId Int identificador de l'usuari
	* @param artistId Int identificador de l'artista
	* @return int identificador de la rel.lacio (o 0 si no existeix)
	*/

	public function getUserArtistRelationId($userId,$artistId)
	{
		$query = $this->dbConnection->prepare("SELECT id FROM userartists WHERE userId = ? AND artistId = ?");
		$query->execute(array($userId,$artistId));
		$return = 0;
		if ($query->rowCount() > 0) {
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$return = $data['id'];
		}		
		return $return;
	}



	/**
	* Retorna l'id de rel.lació de valoració (si existeix) d'un usuari amb un artista
	*
	* @param userId Int identificador de l'usuari
	* @param artistId Int identificador de l'artista
	* @return int identificador de la rel.lacio (o 0 si no existeix)
	*/

	public function checkUserArtistValoration($userId,$artistId)
	{
		$query = $this->dbConnection->prepare("SELECT ar.id FROM userartistratings ar, userartists ua WHERE ua.userId = ? AND ua.artistId = ? AND ua.id = ar.userArtistId");
		$query->execute(array($userId,$artistId));
		$return = 0;
		if ($query->rowCount() > 0) {
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$return = $data['id'];
		}		
		return $return;
	}

}

