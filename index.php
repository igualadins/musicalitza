<?php
require 'header.php';

// incloem la classe artist
include('class/artists.php');
include('class/albums.php');
$artistsObj = new Artists($dbConnection);
$albumsObj = new Albums($dbConnection);
?>

<!-- BANNER INTRO -->
<div class="jumbotron">
    <div class="container text-center">
        <div class="col-lg-6 col-lg-offset-6 col-md-6 col-md-offset-6 col-sm-12 col-xs-12">
            <div id="registrat"  class="row slogan wow pulse">
                <h1>Troba <span class="white">amics</span> musicals!</h1>
            </div>
            <?php if (!$userLoggedIn) { ?>
                <button type="button" class="btn registrat"><a href="my-account.php">Registra't</a></button>     
            <?php } ?>
        </div>
    </div>
</div>
<!-- end banner intro -->
</header>

<!-- RANKS -->
<section id="ranks">
    <div class="container">    
        <div class="row">

            <!-- Top Artistes -->
            <div class="col-sm-4 col-xs-12">
                <div class="panel">
                    <div class="panel-heading">TOP ARTISTES</div>
                    <div class="panel-body">
                        <?php
                        $artist5 = $artistsObj->getTop5RatedArtists();

                        foreach ($artist5 as $a) { ?>
                                              
                            <div class="row disc">
                                <div class="col-xs-6">
                                    <img src="<?php echo $a['image'] ?>" style="width:100%" class="img-responsive" alt="Image">
                                </div>

                                <div class="col-xs-6 dades">
                                    <p><a href="artists-detail.php?artistId=<?php echo $a['id'] ?>"><?php echo $a['name'] ?></a></p>
                                    <?php $valoracioMitja = $artistsObj->getArtistAverageRating($a['id']); // Mirem la valoraciÛ mitja de l'artista ?>

                                    <p>
                                        
                                        <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 0) { echo 'estrella-marcada'; } ?>"></i>
                                        <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 1) { echo 'estrella-marcada'; } ?>"></i>
                                        <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 2) { echo 'estrella-marcada'; } ?>"></i>
                                        <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 3) { echo 'estrella-marcada'; } ?>"></i>
                                        <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 4) { echo 'estrella-marcada'; } ?>"></i>

                                    </p>
                                </div>
                            </div>
                        
                        <?php } ?>  
                        
                    </div>                 
                    <div class="panel-footer"><a href="#">Veur√© m√©s</a></div>
                </div>
            </div>


            <!-- Top Albums -->
            <div class="col-sm-4 col-xs-12">
                <div class="panel">
                    <div class="panel-heading">TOP ALBUMS</div>
                    <div class="panel-body">
                                                <?php
                        $album5 = $albumsObj->getTop4RatedAlbums();

                        foreach ($album5 as $a) { ?>
                                              
                            <div class="row disc">
                                <div class="col-xs-6">
                                    <img src="<?php echo $a['image'] ?>" style="width:100%" class="img-responsive" alt="Image">
                                </div>

                                <div class="col-xs-6 dades">
                                    <p><a href="albums-detail.php?albumId=<?php echo $a['id'] ?>"><?php echo $a['name'] ?></a></p>
                                    <?php $valoracioMitja = $albumsObj->getAlbumAverageRating($a['id']); // Mirem la valoraciÛ mitja de l'artista ?>

                                    <p>
                                        
                                        <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 0) { echo 'estrella-marcada'; } ?>"></i>
                                        <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 1) { echo 'estrella-marcada'; } ?>"></i>
                                        <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 2) { echo 'estrella-marcada'; } ?>"></i>
                                        <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 3) { echo 'estrella-marcada'; } ?>"></i>
                                        <i class="glyphicon glyphicon-star estrellaInfo <?php if($valoracioMitja > 4) { echo 'estrella-marcada'; } ?>"></i>

                                    </p>
                                </div>
                            </div>
                        
                        <?php } ?>  
                        
                    </div>                 
                    <div class="panel-footer"><a href="#">Veur√© m√©s</a></div>
                </div>
            </div>


            <!-- Concerts -->
            <div class="col-sm-4 col-xs-12">
                <div class="panel">
                    <div class="panel-heading">PROPERS CONCERTS</div>
                   <div class="panel-body">
                        <!-- Num1 -->
                        <div class="row disc">
                            <div class="col-md-4 col-xs-6">
                                <div class="data">
                                    <p>15</p>
                                    <p>MAR</p>
                                    <p>21:00</p>
                                </div>
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">Suicidal Tendencies</a></p>
                                <p><a href="#">Razzmatazz</a></p>
                            </div>
                        </div>

                        <!-- Num2 -->
                        <div class="row disc">
                            <div class="col-md-4 col-xs-6">
                                <div class="data">
                                    <p>19</p>
                                    <p>MAR</p>
                                    <p>19:00</p>
                                </div>
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">Sobrinus</a></p>
                                <p><a href="#">Bikini</a></p>
                            </div>
                        </div>

                        <!-- Num3 -->
                        <div class="row disc">
                            <div class="col-md-4 col-xs-6">
                                <div class="data">
                                    <p>20</p>
                                    <p>MAR</p>
                                    <p>22:00</p>
                                </div>
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">Serie Z</a></p>
                                <p><a href="#">Casal Popular Nou Barris</a></p>
                            </div>
                        </div>

                        <!-- Num4 -->
                        <div class="row disc">
                            <div class="col-md-4 col-xs-6">
                                <div class="data">
                                    <p>20</p>
                                    <p>MAR</p>
                                    <p>23:00</p>
                                </div>
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">RocÌo Jurado</a></p>
                                <p><a href="#">Teatre Apolo</a></p>
                            </div>
                        </div>
                        
                        <!-- Num5 -->
                        <div class="row disc">
                            <div class="col-md-4 col-xs-6">
                                <div class="data">
                                    <p>21</p>
                                    <p>MAR</p>
                                    <p>19:00</p>
                                </div>
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">JoaquÌn Sabina</a></p>
                                <p><a href="#">Palau Sant Jordi</a></p>
                            </div>
                        </div>

                    </div>

                </div>                 
                <div class="panel-footer"><a href="#">Veur√© m√©s</a></div>
            </div>
        </div>
    </div>
</section>
<!-- end Ranks -->

<?php //if (!$userLoggedIn) { // Suprimeixo la condici√≥ al haver canviat la section ?>


<section id="three-col">
    <div class="row">
        <div class="col-md-offset-3 col-md-2 col-xs-4">
            <p class="wow flipInY"><i class="fas fa-headphones"></i></p>
            <p>Escolta</p>
        </div>
        <div class="col-md-2 col-xs-4">
            <p class="wow flipInY"><i class="far fa-gem"></i></p>
            <p>Descobreix</p>
        </div>
        <div class="col-md-2 col-xs-4">
            <p class="wow flipInY"><i class="far fa-share-square"></i></p>
            <p>Comparteix</p>
        </div>
    </div>
</section>
<!-- End Registra't -->

<?php //} ?> 

<?php
require 'footer.php';
?>      