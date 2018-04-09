<?php

// Aquest fitxer engloba les accions que farem amb AJAX
// rebrà en un POST la variable "action" que li dirà que ha de fer
// I finalment retorna un JSON amb el resultat de l'acció


include('connect.php');
// incloem la classe artists
include('class/artists.php');
$artistsObj = new Artists($dbConnection);

// Omplim el JSON de sortida amb dades per defecte
$jsondata = array();
$jsondata['success'] = true;
$jsondata['message'] = 'OK';

// Agafem el parametre que ens dirà que hem de fer
$jsondata['action'] = filter_input (INPUT_POST, 'action', FILTER_SANITIZE_STRING);

if( strtolower( $_SERVER[ 'REQUEST_METHOD' ] ) == 'post') { // Si la petició no es un POST fem quelcom

	try { // Control d'errors 

		switch ($jsondata['action']) { // Segons el tipus d'acció farem una cosa o una altra

				// Si estem fent una cerca
		    case 'CERCAR':
		    		$jsondata['search'] = filter_input (INPUT_POST, 'search', FILTER_SANITIZE_STRING); // Agafem el parametre de cerca
		        $jsondata['artists'] = $artistsObj->searchArtistsByName($jsondata['search']); // Fem la cerca i l'afegim al JSON de retorn
		        break;
				// Si estem fent una cerca a l'API de LastFM
		    case 'CERCARAPI':
		    		$jsondata['search'] = filter_input (INPUT_POST, 'search', FILTER_SANITIZE_STRING); // Agafem el parametre de cerca
		        $jsondata['artists'] = $artistsObj->searchArtistsByNameInAPI($jsondata['search']); // Fem la cerca i l'afegim al JSON de retorn
		        break;
				// Si estem afegint un artista desde l'API de LastFM
		    case 'AFEGIRARTIST':
		    		$jsondata['mbId'] = filter_input (INPUT_POST, 'mbId', FILTER_SANITIZE_STRING); // Agafem el parametre identificador
		        $jsondata['artistId'] = $artistsObj->setArtist($jsondata['mbId']); // Afegim l'artista
		        break;
				// Si estem afegint un artista a favortis
		    case 'AFEGIRFAVORITS':
		    		$jsondata['artistId'] = filter_input (INPUT_POST, 'artistId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador
		    		$jsondata['userId'] = filter_input (INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador
		        $jsondata['userArtistId'] = $artistsObj->setFavorite($jsondata['userId'],$jsondata['artistId']); // Afegim l'artista a favorits
		        break;
				// Si estem afegint un artista a favortis
		    case 'TREUREFAVORITS':
		    		$jsondata['artistId'] = filter_input (INPUT_POST, 'artistId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador
		    		$jsondata['userId'] = filter_input (INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador
		        $artistsObj->unsetFavorite($jsondata['userId'],$jsondata['artistId']); // treiem l'artista de favorits
		        break;
				// Si estem actulalitzant la llista de  favortis
		    case 'LLISTAFAVORITS':
		    		$jsondata['userId'] = filter_input (INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador
		        $jsondata['favoriteArtists'] = $artistsObj->getFavoriteArtists($jsondata['userId']); // retornem la llista de favorits
		        break;
		}

	} catch (Exception $e) { // Si hi ha algun error en el procès, retornem l'error al JSON

		$jsondata['success'] = false;
		$jsondata['message'] = $e->getMessage();
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($jsondata);
		exit();

	}

} else { // Si la petició no es un POST retornem error

	$jsondata['success'] = false;
	$jsondata['message'] = 'Error en dades rebudes';

}


// Quan arribem aqui, retornem el JSON
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata);
exit();

?>