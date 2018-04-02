<?php

require 'header.php';
?>

<?php if ($userLoggedIn) { // Si l'usuari ha fet login mostrem el panell d'usuari ?>

      <?php if(isset($_GET['newuser'])) { ?>
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <b>Benvingut a Musicalitza</b><br />
          Hola amant de la música, benvingut a la nostra comunitat. :)<br />
          Et recomanem que completis el teu perfil amb una petita bio i la teva foto, i llavors ja pots començar a buscar artistes i albums favorits
          que t'ajudaran a trobar gent amb les mateixes afinitats musicals que tu.<br />
        </div>
      <?php } ?>

    <div id="my-account" class="container top40 bottom80">

        <div class="row">
            <div class="col-sm-4 col-xs-12">
              <?php include 'user-panel.php'; ?>
            </div>
            <div class="col-sm-8 col-xs-12">
              <div class="userPanelSectionBox text-center">
<<<<<<< HEAD
                <?php $userData = $user->getUserDataById($_SESSION['id']); ?>
                <div class="profilePicture">
                  <?php if (strlen($userData['image'])) { ?>
                    <img src="<?php echo $userData['image']; ?>" class="img-circle" />
                  <?php } else { ?>
                    <img src="img/users/_user.jpg" class="img-circle" />
                  <?php } ?>                  
                </div>
                <div class="profileName"><?php echo $userData['nickname']; ?></div>
                <div class="profileEmail"><?php echo $userData['email']; ?></div>
                <div class="profileBio"><?php echo $userData['bio']; ?></div>
=======
                <? $userData = $user->getUserDataById($_SESSION['id']); ?>
                <div class="profilePicture">
                  <? if (strlen($userData['image'])) { ?>
                    <img src="<? echo $userData['image']; ?>" class="img-circle" />
                  <? } else { ?>
                    <img src="img/users/_user.jpg" class="img-circle" />
                  <? } ?>                  
                </div>
                <div class="profileName"><? echo $userData['nickname']; ?></div>
                <div class="profileEmail"><? echo $userData['email']; ?></div>
                <div class="profileBio"><? echo $userData['bio']; ?></div>
>>>>>>> 2f57c9101ed4b890980467b97be3a255149f6185
                <div class="row">
                  <div class="col-md-2 col-xs-4">
                    <div class="favoriteItemsNumber">12</div>
                    <div class="favoriteItemsName">Artistes favorits</div>
                  </div>
                  <div class="col-md-2 col-xs-4">
                    <div class="favoriteItemsNumber">21</div>
                    <div class="favoriteItemsName">Álbums favorits</div>
                  </div>
                  <div class="col-md-2 col-xs-4">
                    <div class="favoriteItemsNumber">46</div>
                    <div class="favoriteItemsName">Amics</div>
                  </div>
                  <div class="col-md-2 col-xs-4">
                    <div class="favoriteItemsNumber">2</div>
                    <div class="favoriteItemsName">Sol.licituds</div>
                  </div>
                  <div class="col-md-2 col-xs-4">
                    <div class="favoriteItemsNumber">1</div>
                    <div class="favoriteItemsName">Xats pendents</div>
                  </div>
                  <div class="col-md-2 col-xs-4">
                    <div class="favoriteItemsNumber">0</div>
                    <div class="favoriteItemsName">Bloquejats</div>
                  </div>
                </div>
              </div>
            </div>
        </div>

    </div>

<?php } else { // Si l'usuari no està identificat, mostrem el form de login i el boto de registre ?>

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
        <?php } ?>

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

<?php } ?>

<?php

require 'footer.php';
?>  
