<?php

require 'header.php';

// incloem la classe artists
include('class/artists.php');
$artistsObj = new Artists($dbConnection);

// incloem la classe albums
include('class/albums.php');
$albumsObj = new Albums($dbConnection);

$artistId = filter_input (INPUT_GET, 'artistId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador de l'artista
if ($artistId > 0) {
  $artist = $artistsObj->getArtistData($artistId); // Llegim l'artista
  $artistAlbums = $artistsObj->getArtistAlbums($artist['id']); // llegim els albums de l'artista
} else { 
  die('Error: no ha arribat el codi del artista'); 
}

?>


    <div id="my-artist-detail" class="container top40 bottom80">

        <div class="row">
            <div class="col-xs-12">
              <div class="userPanelSectionBox">

                <div class="col-sm-4 col-xs-12">
                  <img src="<?php echo $artist['image']; ?>" class="img-responsive artistImage" />
                  <span class="artistAlbumsCount"><i class="glyphicon glyphicon-cd"></i> <?php echo count($artistAlbums); ?> àlbum(s)</span>
                  <span class="artistLikesCount"><i class="glyphicon glyphicon-heart-empty"></i> <?php echo $artist['likes']; ?> seguidors</span>

                <?php if ($userLoggedIn) { //Si l'usuari està loguejat mostrem opcions d'agregar a favorits, etc ?>
                  
                  <?php $relationId = $artistsObj->getUserArtistRelationId($_SESSION['id'],$artist['id']); // Mirem si l'usuari te l'artista a favorits ?>
    
                    <div id="favoritArtistElement<?php echo $artist['id']; ?>">
                      <!-- posem l'id d'usuari al DOM perque sigui accesible desde Javascript i aixi ajudar a les crides AJAX -->
                      <input type="hidden" id="userId" name="userId" value="<?php echo $_SESSION['id']; ?>" />
                      <?php if ($relationId > 0) { ?>
                        <button class="btn btn-danger btn-xs" type="button" onclick="removeArtistToFavorites('<?php echo $artist['id']; ?>');"><i class="glyphicon glyphicon-remove"></i> Treure de favorits</button>
                      <?php } else { ?>
                        <button class="btn btn-success btn-xs" id="artistElementButton<?php echo $artist['id']; ?>" type="button" onclick="addArtistToFavorites('<?php echo $artist['id']; ?>');"><i class="glyphicon glyphicon-ok"></i> Afegir favorits</button>
                      <?php } ?>
                    </div>
                  
                <?php } ?>

                  <a class="btn btn-default btn-xs" href="my-artists.php"><i class="glyphicon glyphicon-chevron-left"></i> Tornar a favorits</a>

                  <hr />

                  <?php $valoracioMitja = $artistsObj->getArtistAverageRating($artist['id']); // Mirem la valoració mitja de l'artista ?>

                  <p>Valoració dels usuaris</p>

                  <div class="contenidor-estrelles-valoracions">
                    <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 0) { echo 'estrella-marcada'; } ?>"></i>
                    <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 1) { echo 'estrella-marcada'; } ?>"></i>
                    <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 2) { echo 'estrella-marcada'; } ?>"></i>
                    <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 3) { echo 'estrella-marcada'; } ?>"></i>
                    <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 4) { echo 'estrella-marcada'; } ?>"></i>
                  </div>
        
                <?php if ($userLoggedIn) { ?>
                  <a href="#contenidor-valoracions">Veure valoracions</a>
                <?php } ?>

                  <hr />

                  <?php 
                    if(count($artistAlbums)) echo '<p class="text-center"><b>Àlbums</b></p>';
                    foreach ($artistAlbums as $album) {
                      echo '<div class="favoritAlbumElement col-sm-12" id="favoritAlbumElement'.$album['id'].'"><div class="albumElement"><img src="'.$album['image'].'" class="img-responsive albumSearchImage" /><span class="albumSearchName">'.$album['name'].'</span><span class="albumSearchLikes"><i class="glyphicon glyphicon-heart-empty"></i> '.$album['likes'].' seguidors</span><a class="btn btn-primary btn-xs" href="my-albums-detail.php?albumId='.$album['id'].'"><i class="glyphicon glyphicon-eye-open"></i> Veure fitxa</a></div></div>';
                    }
                  ?>

                </div>
                <div class="col-sm-8 col-xs-12">
                  <h1><?php echo $artist['name']; ?></h1>
                  <div class="artistBio"><?php echo $artist['bio']; ?></div>

                <?php if ($userLoggedIn) { ?>  
                  
                  <?php 
                    $artistValorated = $artistsObj->checkUserArtistValoration($_SESSION['id'],$artist['id']); // Mirem si l'usuari ja ha valorat a l'artista
                    if ($relationId > 0 && $artistValorated == 0) { // si l'usuari el te a favorits pot valorar (pero nomès si no ho ha fet ja) 
                  ?>

                    <hr />
                    <div class="clearfix"></div>

                    <div id="contenidor-valoracions"> <!-- anchor per veure les valoracions i valorar -->
                      <h4>Valora i comenta la teva opinió sobre aquest artista</h4>
                      <form autocomplete="off" id="formValorar" class="formValorar" method="post" action="action-artists.php">
                        <div class="form-group">
                          <div id="contenidor-estrelles-valoracions">
                              <i class="glyphicon glyphicon-star estrella" id="estrella-1"></i>
                              <i class="glyphicon glyphicon-star estrella" id="estrella-2"></i>
                              <i class="glyphicon glyphicon-star estrella" id="estrella-3"></i>
                              <i class="glyphicon glyphicon-star estrella" id="estrella-4"></i>
                              <i class="glyphicon glyphicon-star estrella" id="estrella-5"></i>
                               (<span id="numero-valoracio">0</span>)
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="comentari">Comentari</label>
                          <textarea name="comentari" id="comentari" class="form-control" required tabindex="1"></textarea>
                        </div>
                        <input type="hidden" name="valoracio" id="valoracio" value="" />
                        <input type="hidden" id="relationId" name="relationId" value="<?php echo $relationId; ?>" />
                        <input type="hidden" id="relationType" name="relationType" value="1" /> <!-- 1 - artist , 2 - album -->
                        <input type="submit" class="btn btn-primary" id="enviar" name="enviar" value="Enviar valoració" />
                      </form>
                    </div>

                  <?php } ?>

                  <hr />
                  <div class="clearfix"></div>

                  <h4>Valoracions fetes sobre l'artista</h4>

                  <?php 

                    $valoracions = $artistsObj->getArtistRatings($artist['id']); // Recollim les valoracions que ha rebut l'artista 

                    if (count($valoracions) == 0) echo '<p>No hi ha valoracions. Sigues el primer en valorar aquest artista!</p>';

                    foreach ($valoracions as $valoracio) {
                      echo '<div class="valoracio-user col-xs-12">';
                      echo '<div class="valoracio-user-img col-xs-3">';
                      if (strlen($valoracio['image'])) {
                        echo '<img src="'.$valoracio['image'].'" class="img-responsive img-circle" />';
                      } else {
                        echo '<img src="img/users/_user.jpg" class="img-responsive img-circle" />';
                      }
                      echo '</div>';
                      echo '<div class="valoracio-user-content col-xs-9">';
                      echo '<div class="valoracio-user-stars">';
                      if($valoracio['rating'] > 0) { $marca = 'estrella-marcada'; } else { $marca = ''; }
                      echo '<i class="glyphicon glyphicon-star estrellaInfo '.$marca.'"></i>';
                      if($valoracio['rating'] > 1) { $marca = 'estrella-marcada'; } else { $marca = ''; }
                      echo '<i class="glyphicon glyphicon-star estrellaInfo '.$marca.'"></i>';
                      if($valoracio['rating'] > 2) { $marca = 'estrella-marcada'; } else { $marca = ''; }
                      echo '<i class="glyphicon glyphicon-star estrellaInfo '.$marca.'"></i>';
                      if($valoracio['rating'] > 3) { $marca = 'estrella-marcada'; } else { $marca = ''; }
                      echo '<i class="glyphicon glyphicon-star estrellaInfo '.$marca.'"></i>';
                      if($valoracio['rating'] > 4) { $marca = 'estrella-marcada'; } else { $marca = ''; }
                      echo '<i class="glyphicon glyphicon-star estrellaInfo '.$marca.'"></i>';
                      echo '</div>';
                      echo '<div class="valoracio-user-name">'.$valoracio['nickname'].'</div>';
                      echo '<div class="valoracio-user-comment">Comentari: '.$valoracio['comment'].'</div>';
                      echo '</div>';
                      echo '</div>';
                    }

                  ?>
                 

                  <hr />
                  <div class="clearfix"></div>

                  <h4>Usuaris als que li agrada aquest artista</h4>

                  <?php 

                    $usuaris = $artistsObj->getArtistUsersRelated($artist['id']);

                    // incloem la classe friends
                    include('class/friends.php');
                    $friendsObj = new Friends($dbConnection, $_SESSION['id']);
                    $amics = $friendsObj->getUserFriendsRelationIdList(); // agafem totes les rel.lacions de l'usuari
                    //var_dump($amics); 

                    if (count($usuaris) == 0) echo '<p>Encara no n\'hi ha! Sigues el primer en posar a favorits aquest artista!</p>';

                    echo '<div class="row">';

                    foreach ($usuaris as $usuariFavorits) {
                      if ($_SESSION['id'] != $usuariFavorits['id']) { // Si l'usuari no es un mateix, el mostrem
                        echo '<div class="favorits-user col-xs-3">';
                        echo '<div class="favorits-user-img">';
                        if (strlen($usuariFavorits['image'])) {
                          echo '<img src="'.$usuariFavorits['image'].'" class="img-responsive img-circle" />';
                        } else {
                          echo '<img src="img/users/_user.jpg" class="img-responsive img-circle" />';
                        }
                        echo '</div>';
                        echo '<div class="favorits-user-name">'.$usuariFavorits['nickname'].'</div>';
                        echo '<div class="favorits-buttons">';
                        if ( $friendsObj->checkFriendShip($usuariFavorits['id']) ) { // Si l'usuari ja te alguna rel.lació amb aquest
                          echo '<button class="btn btn-danger btn-xs" id="userFavoritsElementButton'.$usuariFavorits['id'].'" type="button" onclick="eliminarAmistat('.$_SESSION['id'].','.$usuariFavorits['id'].');"><i class="glyphicon glyphicon-remove"></i> <br /> Eliminar <br /> amistat</button>';
                        } else {
                          echo '<button class="btn btn-success btn-xs" id="userFavoritsElementButton'.$usuariFavorits['id'].'" type="button" onclick="solicitarAmistat('.$_SESSION['id'].','.$usuariFavorits['id'].');"><i class="glyphicon glyphicon-link"></i> <br /> Sol·licitar <br /> amistat</button>';
                        }
                        echo '</div>';
                        echo '</div>';
                      }
                    }

                    echo '</div>';

                  ?>

                <?php } //Fi per usuaris loguejats ?>
                  
                </div>
                <div class="clearfix"></div>
              </div>
            </div>
        </div>

    </div>

<?php
require 'footer.php';
?>  
