<?php

require 'header.php';
?>

<? if ($userLoggedIn) { // Si l'usuari ha fet login mostrem el panell d'usuari ?>

    <div id="my-account" class="container top40 bottom80">

        <div class="row">
            <div class="col-sm-4 col-xs-12">
              <? include 'user-panel.php'; ?>
            </div>
            <div class="col-sm-8 col-xs-12">
              <div class="userPanelSectionBox text-center">
                <? $userData = $user->getUserDataById($_SESSION['id']); ?>
                <div class="profilePicture">
                  <? if (strlen($userData['image'])) { ?>
                    <img src="<? echo $userData['image']; ?>" class="img-circle" />
                  <? } else { ?>
                    <img src="img/users/_user.jpg" class="img-circle" />
                  <? } ?>                  
                </div>
                <div class="profileName"><? echo $userData['nickname']; ?></div>

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
                  <input type="hidden" name="userId" value="<? echo $userData['id'] ?>" />
                  <input type="submit" name="enviar" value="Actualitzar imatge" class="btn btn-lg btn-primary"  tabindex="2" />
                </form>
                <p><br/>* Tamany recomanat 250x250px. L'imatge serà redimensionada a aquest tamany si es mes gran.</p>
            </div>
          </div>
        </div>

    </div>

<? } else { // Si l'usuari no està identificat, mostrem el form de login i el boto de registre ?>

    <div id="my-account" class="container top40 bottom80">

        <div class="row">
            <div class="col-lg-3 col-lg-offset-1 col-sm-4 col-sm-offset-1 col-xs-8 col-xs-offset-2">
            <h1>Benvingut!!</h1>
            </div>
        </div>

        <?php if(isset($_GET['error'])) { ?>
          <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <b>Error en sessi&oacute;</b><br />
            Hi ha hagut un error en l'inici de sessió o en les dades enviades.<br>
            Si us plau, revisa les credencials i torna a intentar-ho
          </div>
        <? } ?>

        <div class="row top40 bottom40 login">

            <div class="col-lg-3 col-lg-offset-2 col-sm-4 col-sm-offset-2 col-xs-8 col-xs-offset-2">
                <h2 class="form-signin-heading">Crear un compte</h2>
                <a class="btn btn-lg btn-primary btn-block" href="register.php" >Crear un compte</a>
            </div>

            <div class="col-lg-3 col-lg-offset-2 col-sm-4 col-sm-offset-2 col-xs-8 col-xs-offset-2">

                <form class="form-signin" action="action-login.php" method="POST" autocomplete="off">
                    <h2 class="form-signin-heading">Ja estàs registrat?</h2>
                    <label for="inputEmail" class="sr-only">Correu electrònic</label>
                    <input type="email" name="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="" tabindex="1">
                    <label for="inputPassword" class="sr-only">Contrasenya</label>
                    <input type="password" name="inputPassword" class="form-control" placeholder="Password" required="" tabindex="2">
                    <input type="submit" name="enviar" value="Accedir" class="btn btn-lg btn-primary btn-block" />
                </form>
            </div>

        </div>

    </div>

<? } ?>

<?php

require 'footer.php';
?>  
