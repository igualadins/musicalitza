<?php

require 'header.php';
?>

<?php if ($userLoggedIn) { // Si l'usuari ha fet login mostrem el panell d'usuari ?>

    <div id="my-account" class="container top40 bottom80">

        <div class="row">
            <div class="col-sm-4 col-xs-12">
              <?php include 'user-panel.php'; ?>
            </div>
            <div class="col-sm-8 col-xs-12">
              <div class="userPanelSectionBox ">
                <?php $userData = $user->getUserDataById($_SESSION['id']); ?>
                <form autocomplete="off" action="action-update.php" method="POST">
                  <div class="form-group">
                    <label for="inputNickname">Nom d'usuari</label>
                    <input type="text" class="form-control" placeholder="Nom d'usuari" name="inputNickname" required tabindex="1" value="<?php echo $userData['nickname']; ?>" />
                  </div>
                  <div class="form-group">
                    <label for="inputNickname">Biografía o petita descripció del teu perfil</label>
                    <textarea name="inputBio" class="form-control" placeholder="Biografía o petita descripció del teu perfil" required tabindex="2"><?php echo $userData['bio']; ?></textarea>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="conditions" value="conditions" required tabindex="3"> He llegit i accepto les condicions d'ús i pol&iacute;tica de privacitat de la web
                    </label>
                  </div>
                  <input type="hidden" name="userId" value="<?php echo $userData['id'] ?>" />
                  <input type="submit" name="enviar" value="Actualitzar dades" class="btn btn-lg btn-primary btn-block"  tabindex="4" />
                </form>
              </div>
            </div>
        </div>

    </div>

<?php } else { // Si l'usuari no està identificat, mostrem error 

  include 'error.php';

} 

require 'footer.php';
?>  
