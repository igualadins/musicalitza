<?php

include 'ChromePhp.php'; //Per debugar php via consola Chrome

/**
 * Albums
 *  
 * Aquesta classe engloba tot allò rel.lacionat amb la gestió dels albums
 * @author Igualadins (Carlos, Pau i Xavier)
 */
/*

 * **************
  Para las clases
 * **************

  Hay 2 maneras de hacer un prepared statement con PDO :

 * * Manera 1: poner los nombres y tipos de cada parametro

  // Asi se prepara la query
  $query = $this->dbConnection->prepare("SELECT * FROM album WHERE mbid = :mbid ORDER BY releaseDate DESC LIMIT :limit");

  // Asi cada uno de los parametros
  $query->bindValue(':mbid',  $mbid, PDO::PARAM_STR);  // param de tipo String
  $query->bindValue(':limit', $limit, PDO::PARAM_INT); // param de tipo integer

  // Asi se ejecuta la query
  $query->execute();


 * * Manera 2: pasar un array de parametros sin especificar tipos ni nombres de variables

  // Asi se prepara la query
  $query = $this->dbConnection->prepare("SELECT * FROM album WHERE mbid = ? ORDER BY releaseDate DESC LIMIT ?");

  // Asi se ejecuta a la vez que se le pasa un array con los parametros en el orden de aparición
  $query->execute(array($mbid,$limit));

 * **********

 * * Si el resultado de la query es un unico row, se guarda en una variable (o se retorna directamente) con un fetch

  $result = $query->fetch(PDO::FETCH_ASSOC);
  return $result;

  o bien $query->fetch(PDO::FETCH_ASSOC);

  Esto devuelve un array asociativo

  Por ejemplo esta query: "SELECT * FROM album WHERE mbid = ?" devolverá siempre un unico row con los datos de un album

  y luego podemos acceder a los datos asi

  $data = $query->fetch(PDO::FETCH_ASSOC); // Aqui volcamos el resultado de la query
  $data['id'] // El id del resultado
  $data['name'] // El name del resultado
  $data['image'] // La imagen del resultado
  ... (y asi con el resto de campos devueltos por la query) ...


 * * Si el resultado de la query se sabe que pueden ser una o varias filas, se guarda en una variable (o se retorna directamente) con un fetchAll

  $result = $query->fetch(PDO::FETCH_ASSOC);
  return $result;

  o bien $query->fetchAll(PDO::FETCH_ASSOC);

  Esto devuelve un array de arrays asociativos

  Por ejemplo esta query: "SELECT * FROM album WHERE artistId = ?" puede devolver 0, 1 o varios albums de un artista

  $dataSet = $query->fetchAll(PDO::FETCH_ASSOC);
  foreach($dataSet as $data){
  $data['id'] // El id del resultado
  $data['name'] // El name del resultado
  $data['image'] // La imagen del resultado
  ... (y asi con el resto de campos devueltos por la query, para cada elemento en la iteración) ...
  }

 * **********

  Si queremos saber si una query de INSERT ha ido bien o no, podemos hacer este if

  if($query->errorCode() == 0) { // Si no ha habido ningun error
  return 1;
  } else {
  return 0; // Si han habido errores
  }

 * **********

  Si queremos retornar la id del registro que acabamos de insertar, podemos usar

  return $this->dbConnection->lastInsertId()

 * **********

  Si quereis ver que retorna una query, o debuggar un poco , podeis mostrar por pantalla las variables con

  var_dump($data);

  os recomiendo poner un die(); después para detener la ejecución




 * ************
  Para el FRONT
 * ************

  Asi se instancia un objeto, por ejemplo si haceis la pantalla de albums podeis instanciar el objeto asi

  include('class/albums.php');
  $albumsObj = new Albums($dbConnection);

  y luego ejecutar un método asi

  $albumsObj->getTop10FavoritAlbums();

  si lo haceis asi, pondreis la respuesta en una variable

  $TOP10FavoritAlbums = $albumsObj->getTop10FavoritAlbums();

  y luego recorrerlo asi

  foreach($TOP10FavoritAlbums as $album){
  $album['id'] // El id del resultado
  $album['name'] // El name del resultado
  $album['image'] // La imagen del resultado
  ... (y asi con el resto de campos devueltos por la query, para cada elemento en la iteración) ...
  }


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

$query = $this->dbConnection->prepare(" SELECT * FROM album WHERE name LIKE ? ORDER BY name ASC");
$query->execute(array("%$name%"));
return $query->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Cerca artistes segons el criteri de cerca, a l'API de LastFM
 *
 * @param name String nom de l'artista a cercar
 * @return array amb totes les dades dels artistes rebudes
 */
public function searchAlbumsByNameInAPI($name) {

// Construim els paràmetres de url per fer la crida a l'API de LastFM
// Amb la api_key que hem generat i el mètode artist.search
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
if (strlen($album['mbid'])) { // nomès albums que tinguin l'identificador public de lastFM, n'hi ha que no ho tenen perque no tenen molta info o no estàn verificats
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
array_push($return, $tmpAlbum); // Afegim l'artista a l'array que retornarem (Nomès si te imatge. Si no, no l'afegim...)
}
}
}

// Retornem el nostre array d'artistes fet del resultat de l'API de LastFM
return $return;
}

/**
 * Retorna el detall d'informació d'un album concret a partir del seu id
 *
 * @param albumId Int identificador de l'album
 * @return array amb totes les dades de l'album
 */
public function getAlbumData($albumId) {
return array();
}

/**
 * Retorna els albums d'un artista
 *
 * @param artistId Int identificador de l'artista
 * @return array amb tots els albums
 */
public function getAlbumsByArtist($artistId) {
return array();
}

/**
 * Retorna l'id d'un album a partir del seu identificador public
 *
 * @param $mbId String Identificador public (de l'API de LastFM) de l'album
 * @return Int id de l'artista o 0 si no existeix
 */
public function getAlbumByMBID($mbId) {
return 1;
}

/**
 * Retorna l'info d'un artista a partir del seu identificador public desde l'API de LastFM
 *
 * @param $mbId String Identificador public (de l'API de LastFM) de l'artista
 * @return Array amb les dades
 */
public function getAlbumDataByMBIDFromAPI($mbId) {

// Construim els paràmetres de url per fer la crida a l'API de LastFM
// Amb la api_key que hem generat i el mètode artist.search
$queryParams = http_build_query([
'method' => 'album.getinfo',
 'mbid' => $mbId,
 'api_key' => 'c2cef55c7ff22d821abe2b6c2529747e',
 'lang' => 'es',
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
 * @param $mbId String Identificador public (de l'API de LastFM) de l'artista
 * @return $artistId Int identificador de l'artista
 */
public function setAlbum($mbId) {

// Obtenim l'info de l'artista i el guardem
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
$saveImg = 'img/albums/' . $mbId . '.png'; // Ruta on guardarem l'imatge de l'artista
file_put_contents($saveImg, file_get_contents($image)); // Guardem l'imatge
} catch (Exception $e) { // Si hi ha algun error en el procès parem l'execució
var_dump($e->getMessage());
die();
}

// Preparem la query per guardar l'artista
$query = $this->dbConnection->prepare("INSERT INTO album (id, mbid, artistId, name, releaseDate, image, trackInfo) VALUES ('', ?, ?, ?, ?, ?, ?)");

ChromePhp::log($query);

$query->execute( array($mbId, 1, $album['name'], 'releaseDate', $saveImg, json_encode($album['tracks']) ) ); //TODO

if ($query->errorCode() == 0) { // Si no hi ha cap problema, guardem l'id del nou artista
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

        // Preparem la query per guardar l'artista a favorits
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
return true;
}

/**
 * Retorna els albums favorits d'un usuari
 *
 * @param userId Int identificador de l'usuari
 * @return array amb tots els albums
 */
public function getFavoriteAlbums($userId) {
$query = $this->dbConnection->prepare("
            SELECT a.id, a.name, a.image
                FROM album a, useralbums ua 
                WHERE ua.userId = ? AND a.id = ua.albumId
                ORDER BY ua.priority");
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
        return array();
    }

    /**
     * Retorna la valoració mitja d'un album
     *
     * @param albumId Int identificador de l'album
     * @return double Val.loració mitja de l'album
     */
    public function getAlbumAverageRating($albumId) {
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
    public function setAlbumRating($userId, $albumId, $rating, $comment) {
        return true;
    }

}
