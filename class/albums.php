<?php

/**
 * Albums
 *  
 * Aquesta classe engloba tot allò rel.lacionat amb la gestió dels albums
 * @author Igualadins (Carlos, Pau i Xavier)
 */
 

class Albums {

    private $dbConnection;

    // Al iniciar seteja la connexió amb la base de dades

    public function __construct(\PDO $dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    /**
     * Llegeix els 10 albums amb mes favorits
     *
     * @return array amb totes les dades dels albums
     */
    public function getTop10FavoritAlbums() {
        return array();
    }

    /**
     * Llegeix els 10 albums amb millor val.loració mitja
     *
     * @return array amb totes les dades dels albums
     */
    public function getTop10RatedAlbums() {
        return array();
    }

    /**
     * Cerca albums segons el criteri de cerca, a la base de dades de l'aplicació
     *
     * @param name String nom de l'album a cercar
     * @return array amb totes les dades dels albums
     */
    public function searchAlbumsByName($name) {

        $query = $this->dbConnection->prepare(" SELECT a.id, a.mbid, a.name, a.image, count(ua.id) as likes FROM album a
                                                LEFT JOIN useralbums ua ON ua.albumId = a.id
                                                WHERE a.name LIKE ?
                                                GROUP BY a.id
                                                ORDER BY a.name ASC");
        $query->execute(array("%$name%"));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cerca albums segons el criteri de cerca, a l'API de LastFM
     *
     * @param name String nom de l'album a cercar
     * @return array amb totes les dades dels albums rebudes
     */
    public function searchAlbumsByNameInAPI($name) {

// Construim els paràmetres de url per fer la crida a l'API de LastFM
// Amb la api_key que hem generat i el mètode album.search
        $queryParams = http_build_query([
            'method' => 'album.search',
            'album' => $name,
            'api_key' => 'c2cef55c7ff22d821abe2b6c2529747e',
            'format' => 'json'
        ]);

        // Contruim la url a on farem la crida
        $url = "http://ws.audioscrobbler.com/2.0/?" . $queryParams;

        // Iniciem curl per fer la petició
        $ch = curl_init();
        // Deshabilitem SSL perque l'api no ho fa servir
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Per obtenir el retorn de la resposta
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Li indiquem la url on fer la petició
        curl_setopt($ch, CURLOPT_URL, $url);
        // Executem la petició
        $APIresult = curl_exec($ch);
        // Tanquem la connexió
        curl_close($ch);
        // Decodifiquem el JSON rebut
        $APIresult = json_decode($APIresult, true);

        $return = array();

        // Si troba resultats els tractem abans de retornar
        if ($APIresult['results']['opensearch:totalResults'] > 0) {
            //https://www.last.fm/api/show/album.search
            foreach ($APIresult['results']['albummatches']['album'] as $album) {// Agafem el nom de l'album
                $tmpAlbum = array();
                // nomès albums que tinguin l'identificador public de lastFM, n'hi ha que no ho tenen perque no tenen molta info o no estàn verificats
                // Només albums que no estiguin ja a la nostra bbdd
                if (strlen($album['mbid']) && $this->getAlbumByMBID($album['mbid']) == 0) {    

                    // Construim un array per cada album, nomès amb les dades que necessitem
                    $tmpAlbum['name'] = $album['name'];
                    $tmpAlbum['mbid'] = $album['mbid'];
                    if (count($album['image']) > 2) {
                        $tmpAlbum['image'] = $album['image'][3]['#text']; // Si tenim l'imatge "extralarge" millor
                    } else if (count($album['image'])) {
                        $tmpAlbum['image'] = $album['image'][0]['#text']; // Si no la tenim, posem la primera que arriba
                    } else {
                        $tmpAlbum['image'] = '';
                    }
                    if (strlen($tmpAlbum['image']))
                        array_push($return, $tmpAlbum); // Afegim l'album a l'array que retornarem (Nomès si te imatge. Si no, no l'afegim...)
                }
            }
        }

        // Retornem el nostre array d'albums fet del resultat de l'API de LastFM
        return $return;
    }

    /**
     * Retorna el detall d'informació d'un album concret a partir del seu id
     *
     * @param albumId Int identificador de l'album
     * @return array amb totes les dades de l'album
     */
    public function getAlbumData($albumId) {
        $query = $this->dbConnection->prepare("SELECT a.id, a.artistId, a.name, a.releaseDate, a.image, a.trackInfo, count(ua.id) as likes FROM album a LEFT JOIN useralbums ua ON ua.albumId = a.id WHERE a.id = ? GROUP BY a.id");
        $query->execute(array($albumId));
        return $query->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Retorna l'id d'un album a partir del seu identificador public
     *
     * @param $mbId String Identificador public (de l'API de LastFM) de l'album
     * @return Int id de l'album o 0 si no existeix
     */
    public function getAlbumByMBID($mbId) {
        $query = $this->dbConnection->prepare("SELECT id FROM album WHERE mbid = ?");
        $query->execute(array($mbId));
        $return = 0;
        if ($query->rowCount() > 0) {
            $data = $query->fetch(PDO::FETCH_ASSOC);
            $return = $data['id'];
        }       
        return $return;
    }

    /**
     * Retorna l'info d'un album a partir del seu identificador public desde l'API de LastFM
     *
     * @param $mbId String Identificador public (de l'API de LastFM) de l'album
     * @return Array amb les dades
     */
    public function getAlbumDataByMBIDFromAPI($mbId) {

// Construim els paràmetres de url per fer la crida a l'API de LastFM
// Amb la api_key que hem generat i el mètode album.search
        $queryParams = http_build_query([
            'method' => 'album.getinfo',
            'mbid' => $mbId,
            'api_key' => 'c2cef55c7ff22d821abe2b6c2529747e',
            'format' => 'json'
        ]);

        // Contruim la url a on farem la crida
        $url = "http://ws.audioscrobbler.com/2.0/?" . $queryParams;

        // Iniciem curl per fer la petició
        $ch = curl_init();
        // Deshabilitem SSL perque l'api no ho fa servir
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Per obtenir el retorn de la resposta
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Li indiquem la url on fer la petició
        curl_setopt($ch, CURLOPT_URL, $url);
        // Executem la petició
        $APIresult = curl_exec($ch);
        // Tanquem la connexió
        curl_close($ch);
        // Decodifiquem el JSON rebut
        $APIresult = json_decode($APIresult, true);

        return $APIresult;
    }

    /**
     * Importa un nou album a la bbdd desde l'API de LastFM a partir del seu mbId
     * Vindria a disparar-se quan es clica "Afegeix a favorits"
     *
     * @param $mbId String Identificador public (de l'API de LastFM) de l'album
     * @return $albumId Int identificador de l'album
     */
    public function setAlbum($mbId) {

        // Obtenim l'info de l'album i el guardem
        $album = $this->getAlbumDataByMBIDFromAPI($mbId);
        $album = $album['album'];

        //Mirem les imatges que tenim
        if (count($album['image']) > 2) {
            $image = $album['image'][3]['#text']; // Si tenim l'imatge "extralarge" millor
        } else if (count($album['image'])) {
            $image = $album['image'][0]['#text']; // Si no la tenim, posem la primera que arriba
        } else {
            $image = '';
        }

        //Procés de guardar la imatge
        try {
            $saveImg = 'img/albums/' . $mbId . '.png'; // Ruta on guardarem l'imatge de l'album
            file_put_contents($saveImg, file_get_contents($image)); // Guardem l'imatge
        } catch (Exception $e) { // Si hi ha algun error en el procès parem l'execució
            var_dump($e->getMessage());
            die();
        }

        // mirem si l'artista ja hi es a la bbdd o no
        $query = $this->dbConnection->prepare("SELECT id FROM artist WHERE name = ?");
        $query->execute(array($album['artist']));
        if ($query->rowCount() > 0) {
            $data = $query->fetch(PDO::FETCH_ASSOC);
            $artistId = $data['id'];
        } else { // Si no hi es, fem una cerca i assignem el primer
            include('artists.php');
            $artistsObj = new Artists($this->dbConnection);
            $artists = $artistsObj->searchArtistsByNameInAPI($album['artist']);
            $artistId = $artistsObj->setArtist($artists[0]['mbid']);
        }

        // Preparem la query per guardar l'album
        $query = $this->dbConnection->prepare("INSERT INTO album (id, mbid, artistId, name, releaseDate, image, trackInfo) VALUES ('', ?, ?, ?, ?, ?, ?)");

        $query->execute(array($mbId, $artistId, $album['name'], $album['wiki']['published'], $saveImg, json_encode($album['tracks'])));

        if ($query->errorCode() == 0) { // Si no hi ha cap problema, guardem l'id del nou album
            return $this->dbConnection->lastInsertId();
        } else {
            return 0; // Si hi ha algun error en el procès retornem 0
        }
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
    public function updateAlbum($albumId, $mbId, $artistId, $name, $releaseDate, $image, $trackInfo) {
        return true;
    }

    /**
     * Posa l'album com a favorit d'un usuari
     *
     * @param userId Int identificador de l'usuari
     * @param albumId Int identificador de l'album
     * @return  $userAlbumId Int identificador de la rel.lació
     */
    public function setFavorite($userId, $albumId) {

        // Preparem la query per guardar l'album a favorits
        $query = $this->dbConnection->prepare("INSERT INTO useralbums (id, userId, albumId, priority) VALUES ('', ?, ?, 1)");
        $query->execute(array($userId, $albumId));

        if ($query->errorCode() == 0) { // Si no hi ha cap problema, retornem l'identificador de la rel.lació
            return $this->dbConnection->lastInsertId();
        } else {
            return 0; // Si hi ha algun error en el procès retornem 0
        }
    }

    /**
     * Treu l'album com a favorit d'un usuari
     *
     * @param userId Int identificador de l'usuari
     * @param albumId Int identificador de l'album
     */
    public function unsetFavorite($userId, $albumId) {
        $query = $this->dbConnection->prepare("DELETE FROM useralbums WHERE userId = ? AND albumId = ?");
        $query->execute(array($userId, $albumId));
    }

    /**
     * Retorna els albums favorits d'un usuari
     *
     * @param userId Int identificador de l'usuari
     * @return array amb tots els albums
     */
    public function getFavoriteAlbums($userId) {
        $query = $this->dbConnection->prepare(" SELECT a.id, a.name, a.image, (select count(*) from useralbums where albumId = a.id) as likes 
                                                FROM album a, useralbums ua
                                                WHERE ua.userId = ? AND a.id = ua.albumId
                                                ORDER BY a.name");
        $query->execute(array($userId));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna els comentaris i valoracions d'un album
     *
     * @param albumId Int identificador de l'album
     * @return array amb tots els comentaris i valoracions
     */
    public function getAlbumRatings($albumId) {
        $query = $this->dbConnection->prepare(" SELECT ar.rating, ar.comment, u.nickname, u.image 
                                                FROM useralbumratings ar, useralbums ua, users u
                                                WHERE ua.albumId = ? AND ua.id = ar.userAlbumId AND u.id = ua.userId
                                                ORDER BY ar.id");
        $query->execute(array($albumId));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna la valoració mitja d'un album
     *
     * @param albumId Int identificador de l'album
     * @return double Val.loració mitja de l'album
     */
    public function getAlbumAverageRating($albumId) {
        $query = $this->dbConnection->prepare(" SELECT FORMAT ((SUM(ar.rating) / COUNT(ar.id)), 1) as rating
                                                FROM useralbumratings ar, useralbums ua
                                                WHERE ua.albumId = ? AND ua.id = ar.userAlbumId");
        $query->execute(array($albumId));
        $return = 0;
        if ($query->rowCount() > 0) {
            $data = $query->fetch(PDO::FETCH_ASSOC);
            $return = $data['rating'];
        }       
        return $return;
    }

    /**
     * Crea un comentaris i valoracio a un album (si l'usuari el té a favorits)
     *
     * @param userAlbumId Int identificador de la rel.lació usuari/album
     * @param rating Int valoració de l'album (de 0 a 5 estrelles)
     * @param comment String Comentari de l'usuari
     * @return array amb tots els comentaris i valoracions
     */
    public function setAlbumRating($userAlbumId, $rating, $comment) {
        $query = $this->dbConnection->prepare("INSERT INTO useralbumratings (userAlbumId, rating, comment) VALUES (?, ?, ?) ");
        $query->execute(array($userAlbumId,$rating,$comment));
    }

    /**
    * Retorna els usuaris que han marcat com favorit a l'album
    *
    * @param albumId Int identificador de l'album
    * @return array amb tots els albums
    */
    public function getAlbumUsersRelated($albumId) {
        $query = $this->dbConnection->prepare(" SELECT u.id, u.nickname, u.image 
                                                FROM useralbums ua, users u
                                                WHERE ua.albumId = ? AND u.id = ua.userId
                                                ORDER BY u.nickname");
        $query->execute(array($albumId));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
    * Retorna l'id de rel.lació d'un usuari amb un album
    *
    * @param userId Int identificador de l'usuari
    * @param albumId Int identificador de l'album
    * @return int identificador de la rel.lacio (o 0 si no existeix)
    */
    public function getUserAlbumRelationId($userId,$albumId) {
        $query = $this->dbConnection->prepare("SELECT id FROM useralbums WHERE userId = ? AND albumId = ?");
        $query->execute(array($userId,$albumId));
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
    * @param albumId Int identificador de l'artista
    * @return int identificador de la rel.lacio (o 0 si no existeix)
    */
    public function checkUserAlbumValoration($userId,$albumId) {
        $query = $this->dbConnection->prepare("SELECT ar.id FROM useralbumratings ar, useralbums ua WHERE ua.userId = ? AND ua.albumId = ? AND ua.id = ar.userAlbumId");
        $query->execute(array($userId,$albumId));
        $return = 0;
        if ($query->rowCount() > 0) {
            $data = $query->fetch(PDO::FETCH_ASSOC);
            $return = $data['id'];
        }       
        return $return;
    }

    /**
    * Converteix un temps en segons en un en format minuts:segons
    *
    * @param temps Int el temps en segons
    * @return String el temps en el nou format
    */
    public function convertTime($seconds) {
        $minuts = floor(($seconds) / 60);
        $segons = $seconds - ($minuts * 60);
        return $minuts . ":" . $segons;
    }

}
