<?php

require 'header.php';
// incloem la classe friends
include('class/friends.php');
?>

<?php if ($userLoggedIn) { // Si l'usuari ha fet login mostrem el panell d'usuari 
    $userFriendsObj = new Friends($dbConnection, $_SESSION['id']);
    ?>
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
                <div class="row">

                  <? 

                    // Incloem els objectes necessaris per mostrar el recompte estadístic
                    // La classe friends ja te com a propietats els recomptes d'amistat en cada estat
                    // La classe artists i albums te un mètode per obtenir el numero de favorits de l'usuari

                    $userFriendsObj = new Friends($dbConnection,$_SESSION['id']); // Instanciem l'objecte de les amistats

                    // incloem la classe artistes
                    include('class/artists.php');
                    $artistsObj = new Artists($dbConnection,$_SESSION['id']); // Instanciem l'objecte d'artistes

                    // incloem la classe albums
                    include('class/albums.php');
                    $albumsObj = new Albums($dbConnection,$_SESSION['id']); // Instanciem l'objecte d'albums

                  ?>

                  <div class="col-md-2 col-xs-4">
                    <div class="favoriteItemsNumber"><? echo $artistsObj->getFavoriteArtistsCount($_SESSION['id']); ?></div>
                    <div class="favoriteItemsName">Artistes favorits</div>
                  </div>
                  <div class="col-md-2 col-xs-4">
                    <div class="favoriteItemsNumber"><? echo $albumsObj->getFavoriteAlbumsCount($_SESSION['id']); ?></div>
                    <div class="favoriteItemsName">Álbums favorits</div>
                  </div>
                  <div class="col-md-2 col-xs-4">
                    <div class="favoriteItemsNumber"><? echo $userFriendsObj->acceptedFriends; ?></div>
                    <div class="favoriteItemsName">Amics</div>
                  </div>
                  <div class="col-md-2 col-xs-4">
                    <div class="favoriteItemsNumber"><? echo $userFriendsObj->pendingRequests; ?></div>
                    <div class="favoriteItemsName">Sol.licituds enviades</div>
                  </div>
                  <div class="col-md-2 col-xs-4">
                    <div class="favoriteItemsNumber"><? echo $userFriendsObj->pendingFriends; ?></div>
                    <div class="favoriteItemsName">Sol.licituds rebudes</div>
                  </div>
                  <div class="col-md-2 col-xs-4">
                    <div class="favoriteItemsNumber"><? echo $userFriendsObj->blockedFriends; ?></div>
                    <div class="favoriteItemsName">Bloquejats</div>
                  </div>
                </div>
              </div>
                
              <div class="userPanelSectionBox text-center">
                <div id="les-meves-suggerencies-list">
                  <h2>Suggeriment d'usuaris per afinitat</h2>                   
                  <ul class="list-group" id="group_suggest">                        
                      <?php
                      $userSuggestFriends = $userFriendsObj->suggestFriends();
                      foreach ($userSuggestFriends as $suggest) {
                          ?>
                          <li class="list-group-item" id="suggest<?php echo "{$suggest['suggestUser']}"; ?>">
                              <div class="col-xs-3 col-sm-2">
                                  <img src="<?php echo "{$suggest['imagen']}"; ?>" height="50" width="50" class="img-responsive img-circle"> 
                              </div> 
                              <div class="col-xs-6 col-sm-6 text-left">
                                  <span class="nom-amic-suggerit"><?php echo "{$suggest['nom']}"; ?></span>
                                  <span class="percentatge-afinitat-suggerit">Percentatge d'afinitat: <?php echo "{$suggest['afinitat']}" . '%'; ?></span>
                              </div>
                              <div class="col-xs-3 col-sm-4 text-right">
                                  <div class="action-buttons">                                                 
                                    <button class="btn btn-info" type="button" onclick="enviarAmistat('<?php echo "{$_SESSION['id']}"; ?>', '<?php echo "{$suggest['suggestUser']}"; ?>')">
                                        <i class="glyphicon glyphicon-send"></i> Sol.licitar amistat
                                    </button>                                                
                                  </div>
                              </div>                                    
                              <div class="clearfix"></div
                          </li>                                                                                  
                      <?php } ?>
                  </ul>                    
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
