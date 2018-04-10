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
              <div class="userPanelSectionBox text-center">
                <?php $userData = $user->getUserDataById($_SESSION['id']); ?>
                <div class="profilePicture">
                  <?php if (strlen($userData['image'])) { ?>
                    <img src="<?php echo $userData['image']; ?>" class="img-circle" />
                  <?php } else { ?>
                    <img src="img/users/_user.jpg" class="img-circle" />
                  <?php } ?>                  
                </div>
                <div class="profileName"><?php echo $userData['nickname']; ?></div>
                <form autocomplete="off" action="action-picture-update.php" method="POST" enctype="multipart/form-data">
                  <div class="input-group">
                      <label class="input-group-btn">
                          <span class="btn btn-info">
                              <span class="glyphicon glyphicon-picture"></span> Cercar una imatge al dispositiu <input type="file" name="inputPicture" style="display: none;">
                          </span>
                      </label>
                      <input type="text" class="form-control" readonly>
                  </div>
                  <div class="clearfix"></div>
                  <input type="hidden" name="userId" value="<?php echo $userData['id'] ?>" />
                  <input type="submit" name="enviar" value="Actualitzar imatge" class="btn btn-lg btn-primary"  tabindex="2" />
                </form>
                <p><br/>* Tamany recomanat 250x250px. L'imatge serà redimensionada a aquest tamany si es mes gran.</p>
            </div>
          </div>
        </div>

    </div>

<?php } else { // Si l'usuari no està identificat, mostrem error 

  include 'error.php';

} 

require 'footer.php';
?>  
