<?php

// Aquest fitxer engloba les accions que farem amb AJAX
// rebrà en un POST la variable "action" que li dirà que ha de fer
// I finalment retorna un JSON amb el resultat de l'acció


include('connect.php');
// incloem la classe artists
include('class/friends.php');
$jsondata['userId'] = filter_input (INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT);
$friendsObj = new Friends($dbConnection,$jsondata['userId']);

// Omplim el JSON de sortida amb dades per defecte
$jsondata = array();
$jsondata['success'] = true;
$jsondata['message'] = 'OK';

// Agafem el parametre que ens dirà que hem de fer
$jsondata['action'] = filter_input (INPUT_POST, 'action', FILTER_SANITIZE_STRING);

if( strtolower( $_SERVER[ 'REQUEST_METHOD' ] ) == 'post') { // Si la petició no es un POST fem quelcom
        
        try { // Control d'errors 

		switch ($jsondata['action']) { // Segons el tipus d'acció farem una cosa o una altra
				
	    case 'BLOQUEJARACCEPTAT':
                    $jsondata['friendId'] = filter_input (INPUT_POST, 'friendId', FILTER_SANITIZE_NUMBER_INT);
                    $jsondata['blocked'] = filter_input (INPUT_POST, 'blocked', FILTER_SANITIZE_NUMBER_INT);
                    $jsondata['accepted'] = $friendsObj->updateBlockFriend($jsondata['friendId'],$jsondata['blocked']);                              
                    break;

            case 'ACCEPTARBLOQUEJAT':
                    $jsondata['friendId'] = filter_input (INPUT_POST, 'friendId', FILTER_SANITIZE_NUMBER_INT);
                    $jsondata['blocked'] = filter_input (INPUT_POST, 'blocked', FILTER_SANITIZE_NUMBER_INT);
                    $jsondata['accepted'] = $friendsObj->updateBlockFriend($jsondata['friendId'],$jsondata['blocked']);                           
                    break;

            case 'ACCEPTARPENDENT':
                    $jsondata['friendId'] = filter_input (INPUT_POST, 'friendId', FILTER_SANITIZE_NUMBER_INT);
                    $jsondata['accepted'] = filter_input (INPUT_POST, 'accepted', FILTER_SANITIZE_NUMBER_INT);
                    $jsondata['accepted'] = $friendsObj->updateAcceptFriend($jsondata['friendId'],$jsondata['accepted']);                           
                    break;
                
            case 'ESBORRAR':
                    $jsondata['friendId'] = filter_input (INPUT_POST, 'friendId', FILTER_SANITIZE_NUMBER_INT);                            
                    $jsondata['accepted'] = $friendsObj->deleteFriend($jsondata['friendId']);                           
                    break;        

            case 'SOLICITARAMISTAT':
                    $jsondata['friendId'] = filter_input (INPUT_POST, 'friendId', FILTER_SANITIZE_NUMBER_INT);                            
                    $friendsObj->sendRequestToFriend($jsondata['friendId']);
                    $jsondata['accepted'] = 1;
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