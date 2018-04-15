<?php

// Aquest fitxer engloba les accions que farem amb AJAX
// rebrà en un POST la variable "action" que li dirà que ha de fer
// I finalment retorna un JSON amb el resultat de l'acció


include('connect.php');
// incloem la classe albums
include('class/albums.php');
$albumsObj = new Albums($dbConnection);

// Omplim el JSON de sortida amb dades per defecte
$jsondata = array();
$jsondata['success'] = true;
$jsondata['message'] = 'OK';

// Agafem el parametre que ens dirà que hem de fer
$jsondata['action'] = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') { // Si la petició no es un POST fem quelcom
    try { // Control d'errors 
        switch ($jsondata['action']) { // Segons el tipus d'acció farem una cosa o una altra
            // Si estem fent una cerca
            case 'CERCAR':
                $jsondata['search'] = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING); // Agafem el parametre de cerca
                $jsondata['albums'] = $albumsObj->searchAlbumsByName($jsondata['search']); // Fem la cerca i l'afegim al JSON de retorn
                break;
            // Si estem fent una cerca a l'API de LastFM
            case 'CERCARAPI':
                $jsondata['search'] = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING); // Agafem el parametre de cerca
                $jsondata['albums'] = $albumsObj->searchAlbumsByNameInAPI($jsondata['search']); // Fem la cerca i l'afegim al JSON de retorn
                break;
            // Si estem afegint un album desde l'API de LastFM
            case 'AFEGIRALBUM':
                $jsondata['mbId'] = filter_input(INPUT_POST, 'mbId', FILTER_SANITIZE_STRING); // Agafem el parametre identificador
                $jsondata['albumId'] = $albumsObj->setAlbum($jsondata['mbId']); // Afegim l'album
                break;
            // Si estem afegint un album a favortis
            case 'AFEGIRFAVORITS':
                $jsondata['albumId'] = filter_input(INPUT_POST, 'albumId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador
                $jsondata['userId'] = filter_input(INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador
                $jsondata['userAlbumId'] = $albumsObj->setFavorite($jsondata['userId'], $jsondata['albumId']); // Afegim l'album a favorits
                break;
            // Si estem afegint un album a favortis
            case 'TREUREFAVORITS':
                $jsondata['albumId'] = filter_input(INPUT_POST, 'albumId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador
                $jsondata['userId'] = filter_input(INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador
                $albumsObj->unsetFavorite($jsondata['userId'], $jsondata['albumId']); // treiem l'album de favorits
                break;
            // Si estem actulalitzant la llista de  favortis
            case 'LLISTAFAVORITS':
                $jsondata['userId'] = filter_input(INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador
                $jsondata['favoriteAlbums'] = $albumsObj->getFavoriteAlbums($jsondata['userId']); // retornem la llista de favorits
                break;
		    // Si estem valorant un album
		    case 'VALORAR':
			    $jsondata['valoracio'] = filter_input (INPUT_POST, 'valoracio', FILTER_SANITIZE_NUMBER_INT); // Agafem la valoracio numerica
			    $jsondata['comentari'] = filter_input (INPUT_POST, 'comentari', FILTER_SANITIZE_STRING); // Agafem el comentari 
			    $jsondata['relationId'] = filter_input (INPUT_POST, 'relationId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador de la relació
	            $albumsObj->setAlbumRating($jsondata['relationId'],$jsondata['valoracio'],$jsondata['comentari']); // guardem la valoracio
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