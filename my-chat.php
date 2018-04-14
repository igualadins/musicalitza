<?php

require 'header.php';
?>

<?php 
  if ($userLoggedIn) { // Si l'usuari ha fet login mostrem el panell d'usuari 

    $friendId = filter_input(INPUT_GET, 'friendId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador de l'amic amb qui parlem

    // incloem la classe chats
    include('class/chats.php');
    $chatsObj = new Chats($dbConnection,$_SESSION['id']);

?>

    <div id="my-chat" class="container top40 bottom80">

        <div class="row">
            <div class="col-sm-4 col-xs-12">
              <?php include 'user-panel.php'; ?>
            </div>
            <div class="col-sm-8 col-xs-12">
              <div class="userPanelSectionBox">

                <div class="panel panel-default">
                  <div class="panel-heading">
                    <span class="glyphicon glyphicon-comment"></span> Xat
                  </div>
                  <div class="panel-body" id="chatMessages">

                    <ul class="chat" id="chat"> <!-- chat start -->

                      <?php 
                        // Llegim la conversa
                        $missatges = $chatsObj->getChatHistory($friendId); 
                        // Llegim les dades de l'usuari
                        $userData = $user->getUserDataById($_SESSION['id']);
                        $userImage = $userData['image'];
                        if (!strlen($userImage)) { $userImage = 'img/users/_user.jpg'; }
                        // Llegim les dades de l'amic al que parlem
                        $friendData = $user->getUserDataById($friendId);
                        $friendImage = $friendData['image'];
                        if (!strlen($friendImage)) { $friendImage = 'img/users/_user.jpg'; }

                        foreach ($missatges as $missatge) {
                          $side = $missatge['userId'] == $_SESSION['id'] ? 'left' : 'right'; // Si el missatge es de l'usuari loginat va a un costat, si no va a l'altre
                          echo '<li class="'.$side.' clearfix" id="'.$missatge['id'].'">';
                          echo '<span class="chat-img pull-'.$side.'">';
                          $image = $missatge['userId'] == $_SESSION['id'] ? $userImage : $friendImage;
                          echo '<img src="'.$image.'" alt="" class="img-circle" />';
                          echo '</span>';
                          echo '<div class="chat-body clearfix">';
                          echo '<div class="header">';
                          if ($side == 'left') { // Si el missatge es de l'usuari loginat va a un costat, si no va a l'altre
                            echo '<strong>'.$userData['nickname'].'</strong> ';
                            echo '<small class="pull-right text-muted"><span class="glyphicon glyphicon-time"></span> '.$missatge['dateSent'].'</small>';
                          } else {
                            echo '<small class="text-muted"><span class="glyphicon glyphicon-time"></span> '.$missatge['dateSent'].'</small>';
                            echo '<strong class="pull-right">'.$friendData['nickname'].'</strong> ';
                          }
                          echo '</div>';
                          echo '<p>'.$missatge['message'].'</p>';
                          echo '</div>';
                          echo '</li>';
                        }
                      ?>

                    </ul> <!-- chat end -->

                  </div>
                  <div class="panel-footer">
                    <form action="action-chat.php" method="POST" id="my-chat-form" autocomplete="off">
                      <div class="input-group">
                        <input id="chat-message" name="chat-message" type="text" class="form-control" placeholder="Escriu el teu missatge..." />
                        <input type="hidden" name="friendId" id="friendId" value="<?php echo $friendId; ?>" />
                        <input type="hidden" name="userId" id="userId" value="<?php echo $_SESSION['id']; ?>" />
                        <span class="input-group-btn">
                          <input type="submit" class="btn btn-danger" id="btn-chat" value="Enviar" />
                        </span>
                      </div>
                    </form>
                  </div>
                </div>

              </div>
            </div>
        </div>

    </div>

<?php } else { // Si l'usuari no està identificat, mostrem error 

  include 'error.php';

} 

require 'footer.php';
?>  
