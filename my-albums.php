<?php

require 'header.php';

// incloem la classe albums
include('class/albums.php');
$albumsObj = new Albums($dbConnection);

?>

<?php if ($userLoggedIn) { // Si l'usuari ha fet login mostrem el panell d'usuari ?>

    <div id="my-albums" class="container top40 bottom80">

        <div class="row">
            <div class="col-sm-4 col-xs-12">
              <?php include 'user-panel.php'; ?>
            </div>
            <div class="col-sm-8 col-xs-12">
              <div class="userPanelSectionBox">
                <h1>Albums</h1>

                <div id="search-input">
                  <div class="input-group col-md-12">
                    <input type="text" id="albumSearch" name="albumSearch" class="form-control input-lg" placeholder="Buscar albums" />
                    <!-- posem l'id d'usuari al DOM perque sigui accesible desde Javascript i aixi ajudar a les crides AJAX -->
                    <input type="hidden" id="userId" name="userId" value="<?php echo $_SESSION['id']; ?>" />
                    <span class="input-group-btn">
                      <button class="btn btn-info btn-lg" type="button">
                        <i class="glyphicon glyphicon-search"></i>
                      </button>
                    </span>
                  </div>
                </div>

                <div id="search-results">
                </div>                  

                <div id="favorit-albums">
                  <h2>Els meus albums favorits</h2>

                    <div id="favorit-albums-list">
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
