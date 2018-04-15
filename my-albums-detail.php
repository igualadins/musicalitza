<?php

require 'header.php';

// incloem la classe albums
include('class/albums.php');
$albumsObj = new Albums($dbConnection);

// incloem la classe artist
include('class/artists.php');
$artistsObj = new Artists($dbConnection);

$albumId = filter_input (INPUT_GET, 'albumId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador de l'album
if ($albumId > 0) {
  $album = $albumsObj->getAlbumData($albumId); // Llegim l'album
} else { 
  die('Error: no ha arribat el codi del album'); 
}

?>

<?php if ($userLoggedIn) { // Si l'usuari ha fet login mostrem el panell d'usuari ?>

    <div id="my-album-detail" class="container top40 bottom80">

        <div class="row">
            <div class="col-sm-4 col-xs-12">
              <?php include 'user-panel.php'; ?>
            </div>
            <div class="col-sm-8 col-xs-12">
              <div class="userPanelSectionBox">

                <div class="col-sm-4 col-xs-12">
                  <img src="<? echo $album['image']; ?>" class="img-responsive albumImage" />
                  <span class="albumLikesCount"><i class="glyphicon glyphicon-heart-empty"></i> <? echo $album['likes']; ?> seguidors</span>

                  <? $relationId = $albumsObj->getUserAlbumRelationId($_SESSION['id'],$album['id']); // Mirem si l'usuari te l'album a favorits ?>

                  <div id="favoritAlbumElement<? echo $album['id']; ?>">
                    <!-- posem l'id d'usuari al DOM perque sigui accesible desde Javascript i aixi ajudar a les crides AJAX -->
                    <input type="hidden" id="userId" name="userId" value="<?php echo $_SESSION['id']; ?>" />
                      <? if ($relationId > 0) { ?>
                        <button class="btn btn-danger btn-xs" type="button" onclick="removeAlbumToFavorites('<? echo $album['id']; ?>');"><i class="glyphicon glyphicon-remove"></i> Treure de favorits</button>
                      <? } else { ?>
                        <button class="btn btn-success btn-xs" id="albumElementButton<? echo $album['id']; ?>" type="button" onclick="addAlbumToFavorites('<? echo $album['id']; ?>');"><i class="glyphicon glyphicon-ok"></i> Afegir favorits</button>
                      <? } ?>
                  </div>
                  <a class="btn btn-default btn-xs" href="my-albums.php"><i class="glyphicon glyphicon-chevron-left"></i> Tornar a favorits</a>

                  <hr />

                  <? $valoracioMitja = $albumsObj->getAlbumAverageRating($album['id']); // Mirem la valoració mitja de l'album ?>

                  <p>Valoració dels usuaris</p>

                  <div class="contenidor-estrelles-valoracions">
                    <i class="glyphicon glyphicon-star estrellaInfo <? if($valoracioMitja > 0) { echo 'estrella-marcada'; } ?>"></i>
                    <i class="glyphicon glyphicon-star estrellaInfo <? if($valoracioMitja > 1) { echo 'estrella-marcada'; } ?>"></i>
                    <i class="glyphicon glyphicon-star estrellaInfo <? if($valoracioMitja > 2) { echo 'estrella-marcada'; } ?>"></i>
                    <i class="glyphicon glyphicon-star estrellaInfo <? if($valoracioMitja > 3) { echo 'estrella-marcada'; } ?>"></i>
                    <i class="glyphicon glyphicon-star estrellaInfo <? if($valoracioMitja > 4) { echo 'estrella-marcada'; } ?>"></i>
                  </div>
        
                  <a href="#contenidor-valoracions">Veure valoracions</a>


                  <hr />

                  <p class="text-center"><b>Artista</b></p>

                  <? 
                    $artist = $artistsObj->getArtistData($album['artistId']); // Busquem l'artista del disc 
                    echo '<div class="favoritArtistElement col-sm-12" id="favoritArtistElement'.$artist['id'].'"><div class="artistElement"><img src="'.$artist['image'].'" class="img-responsive artistSearchImage" /><span class="artistSearchName">'.$artist['name'].'</span><span class="artistSearchLikes"><i class="glyphicon glyphicon-heart-empty"></i> '.$artist['likes'].' seguidors</span><a class="btn btn-primary btn-xs" href="my-artists-detail.php?artistId='.$artist['id'].'"><i class="glyphicon glyphicon-eye-open"></i> Veure fitxa</a></div></div>';
                  ?>

                </div>
                <div class="col-sm-8 col-xs-12">
                  <h1><? echo $album['name']; ?></h1>
                  <? $trackList = json_decode($album['trackInfo']); ?>
                  <div class="albumBio">
                    <? 
                      $numero = 0;
                      foreach ($trackList as $track) {
                        foreach ($track as $tema) {
                          $numero++;
                          $duracio = $albumsObj->convertTime($tema->duration);
                          echo '<div class="tema">';
                          echo '<span class="numero">'.$numero.'.- </span> <span class="nom">'.$tema->name.'</span> <span class="duracio">('.$duracio.')</span> ';
                          echo '</div>';
                        }
                      }
                    ?>
                  </div>

                  <? 
                    $albumValorated = $albumsObj->checkUserAlbumValoration($_SESSION['id'],$album['id']); // Mirem si l'usuari ja ha valorat l'album
                    if ($relationId > 0 && $albumValorated == 0) { // si l'usuari el te a favorits pot valorar (pero nomès si no ho ha fet ja) 
                  ?>

                    <hr />
                    <div class="clearfix"></div>

                    <div id="contenidor-valoracions"> <!-- anchor per veure les valoracions i valorar -->
                      <h4>Valora i comenta la teva opinió sobre aquest album</h4>
                      <form autocomplete="off" id="formValorar" class="formValorar" method="post" action="action-albums.php">
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
                        <input type="hidden" id="relationType" name="relationType" value="2" /> <!-- 1 - artist , 2 - album -->
                        <input type="submit" class="btn btn-primary" id="enviar" name="enviar" value="Enviar valoració" />
                      </form>
                    </div>

                  <? } ?>

                  <hr />
                  <div class="clearfix"></div>

                  <h4>Valoracions fetes sobre l'album</h4>

                  <? 

                    $valoracions = $albumsObj->getAlbumRatings($album['id']); // Recollim les valoracions que ha rebut l'album 

                    if (count($valoracions) == 0) echo '<p>No hi ha valoracions. Sigues el primer en valorar aquest album!</p>';

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

                  <h4>Usuaris als que li agrada aquest album</h4>

                  <? 

                    $usuaris = $albumsObj->getAlbumUsersRelated($album['id']);

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

                </div>
                <div class="clearfix"></div>
              </div>
            </div>
        </div>

    </div>

<?php } else { // Si l'usuari no està identificat, mostrem error 

  include 'error.php';

} 

require 'footer.php';
?>  
