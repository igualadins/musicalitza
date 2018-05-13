<?php
include('connect.php');
// incloem la classe artists
include('class/artists.php');
$artistsObj = new Artists($dbConnection);

// Omplim el JSON de sortida amb dades per defecte
$jsondata = array();
$jsondata['success'] = true;
$jsondata['message'] = 'OK';

$jsondata['mode'] = filter_input(INPUT_POST, 'mode', FILTER_SANITIZE_STRING);;
$jsondata['action'] = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') { // Si la petició no es un POST fem quelcom
    switch ($jsondata['action']) { // Segons el tipus d'acció farem una cosa o una altra
        case 'LLISTA':
            $jsondata['mode'] = filter_input(INPUT_POST, 'mode', FILTER_SANITIZE_STRING); // Agafem el parametre de cerca
            $jsondata['artists'] = $artistsObj->getArtists($jsondata['mode']); // Fem la cerca i l'afegim al JSON de retorn
            break;
    }
} else { // Si la petició no es un POST retornem error
    $jsondata['success'] = false;
    $jsondata['message'] = 'Error en dades rebudes';
}


// Quan arribem aqui, retornem el JSON
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata);
exit();

// Quan arribem aqui, retornem el JSON
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata);
exit();
?>
}