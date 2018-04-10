<?php

require 'header.php';

// incloem la classe artists
include('class/artists.php');
$artistsObj = new Artists($dbConnection);
$artistId = filter_input (INPUT_GET, 'artistId', FILTER_SANITIZE_NUMBER_INT); // Agafem el parametre identificador de l'artista
if ($artistId > 0) {
  $artist = $artistsObj->getArtistData($artistId); // Llegim l'artista
} else { 
  die('Error: no ha arribat el codi del artista'); 
}

?>

<?php if ($userLoggedIn) { // Si l'usuari ha fet login mostrem el panell d'usuari ?>

    <div id="my-artist-detail" class="container top40 bottom80">

        <div class="row">
            <div class="col-sm-4 col-xs-12">
              <?php include 'user-panel.php'; ?>
            </div>
            <div class="col-sm-8 col-xs-12">
              <div class="userPanelSectionBox">

                <div class="col-sm-4 col-xs-12">
                  <img src="<? echo $artist['image']; ?>" class="img-responsive artistImage" />
                  <span class="artistAlbumsCount"><i class="glyphicon glyphicon-cd"></i> XX àlbums</span>
                  <span class="artistLikesCount"><i class="glyphicon glyphicon-heart-empty"></i> <? echo $artist['likes']; ?> seguidors</span>
                  <div id="favoritArtistElement<? echo $artist['id']; ?>">
                    <!-- posem l'id d'usuari al DOM perque sigui accesible desde Javascript i aixi ajudar a les crides AJAX -->
                    <input type="hidden" id="userId" name="userId" value="<?php echo $_SESSION['id']; ?>" />
                    <button class="btn btn-danger btn-xs" type="button" onclick="removeArtistToFavorites('<? echo $artist['id']; ?>');"><i class="glyphicon glyphicon-remove"></i> Treure de favorits</button>
                  </div>
                  <a class="btn btn-default btn-xs" href="my-artists.php"><i class="glyphicon glyphicon-chevron-left"></i> Tornar a favorits</a>
                </div>
                <div class="col-sm-8 col-xs-12">
                  <h1><? echo $artist['name']; ?></h1>
                  <div class="artistBio"><? echo $artist['bio']; ?></div>
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
