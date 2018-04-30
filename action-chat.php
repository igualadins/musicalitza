<?php

// Aquest fitxer engloba les accions que farem amb AJAX
// rebrà en un POST la variable "action" que li dirà que ha de fer
// I finalment retorna un JSON amb el resultat de l'acció

include('connect.php');

// Omplim el JSON de sortida amb dades per defecte
$jsondata = array();
$jsondata['success'] = true;
$jsondata['message'] = 'OK';

// Agafem el parametre que ens dirà que hem de fer
$jsondata['action'] = filter_input (INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$jsondata['userId'] = filter_input (INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador de l'usuari
$jsondata['friendId'] = filter_input (INPUT_POST, 'friendId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador de l'amic al que parlem

// incloem la classe chats
include('class/chats.php');
$chatsObj = new Chats($dbConnection,$jsondata['userId']);

if( strtolower( $_SERVER[ 'REQUEST_METHOD' ] ) == 'post') { // Si la petició no es un POST fem quelcom

	try { // Control d'errors 

		switch ($jsondata['action']) { // Segons el tipus d'acció farem una cosa o una altra

                    // Si estem enviant un missatge
		    case 'ENVIARMISSATGE':
			$jsondata['message'] = filter_input (INPUT_POST, 'chat-message', FILTER_SANITIZE_STRING); // Agafem el parametre identificador de l'amic al que parlem
		        $jsondata['chatMessageId'] = $chatsObj->sendChatMessage($jsondata['friendId'],$jsondata['message']); // Enviem el missatge
		        break;

                    // Si estem actualitzant el xat
		    case 'ACTUALITZARCHAT':
			$jsondata['lastChatId'] = filter_input (INPUT_POST, 'lastChatId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador de l'ultim missatge
		        $missatges = $chatsObj->getChatHistoryFromMessageId($jsondata['friendId'],$jsondata['lastChatId']); // Fem la lectura
                        $jsondata['missatges'] = '';

			// incloem la classe usuari
			include('class/user.php');
			$user = new User($dbConnection);

                        // Llegim les dades de l'usuari
                        $userData = $user->getUserDataById($jsondata['userId']);
                        $userImage = $userData['image'];
                        if (!strlen($userImage)) { $userImage = 'img/users/_user.jpg'; }
                        // Llegim les dades de l'amic al que parlem
                        $friendData = $user->getUserDataById($jsondata['friendId']);
                        $friendImage = $friendData['image'];
                        if (!strlen($friendImage)) { $friendImage = 'img/users/_user.jpg'; }

                        // Muntem els missatges que falten en html, i ho ficarem al json de sortida
                        // Desprès desde jQuery els inserim al final del div de la conversa
                        foreach ($missatges as $missatge) {
                            $tmpMissatge = '';
                            $side = $missatge['userId'] == $jsondata['userId'] ? 'left' : 'right'; // Si el missatge es de l'usuari loginat va a un costat, si no va a l'altre
                            $tmpMissatge .= '<li class="'.$side.' clearfix" id="'.$missatge['id'].'">';
                            $tmpMissatge .= '<span class="chat-img pull-'.$side.'">';
                            $image = $missatge['userId'] == $jsondata['userId'] ? $userImage : $friendImage;
                            $tmpMissatge .= '<img src="'.$image.'" alt="" class="img-circle" />';
                            $tmpMissatge .= '</span>';
                            $tmpMissatge .= '<div class="chat-body clearfix">';
                            $tmpMissatge .= '<div class="header">';
                            if ($side == 'left') { // Si el missatge es de l'usuari loginat va a un costat, si no va a l'altre
                                $tmpMissatge .= '<strong>'.$userData['nickname'].'</strong> ';
                                // nomes indiquem que s'ha llegit el missatge en el cas d'enviament
                                $eye = $missatge['messageRead'] == '1' ? 'fas fa-eye' : 'fas fa-eye-slash';
                                $tmpMissatge .= '<small class="pull-right text-muted"><span class="'.$eye.'"></span> <span class="glyphicon glyphicon-time"></span> '.$missatge['dateSent'].'</small>';
                            } else {                                
                                $tmpMissatge .= '<small class="text-muted"><span class="glyphicon glyphicon-time"></span> '.$missatge['dateSent'].'</small>';
                                $tmpMissatge .= '<strong class="pull-right">'.$friendData['nickname'].'</strong> ';
                                // aqui cridem la funcio actualitzar check, com hem llegit el seu missatge
                                $chatsObj->markAsRead($missatge['id']); // Fem la lectura
                            }
                            $tmpMissatge .= '</div>';
                            $tmpMissatge .= '<p>'.$missatge['message'].'</p>';
                            $tmpMissatge .= '</div>';
                            $tmpMissatge .= '</li>';

                            $jsondata['missatges'] .= $tmpMissatge; // Afegim l'html a la resposta
                        }

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