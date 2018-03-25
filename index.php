<?php
require 'header.php';
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

            <!-- Últims afegits -->
            <div class="col-sm-4 col-xs-12">
                <div class="panel">
                    <div class="panel-heading">TOP ARTISTES</div>
                    <div class="panel-body">
                        <!-- Num1 -->
                        <div class="row disc">
                            <div class="col-xs-6">
                                <img src="http://via.placeholder.com/150x150" style="width:100%" class="img-responsive" alt="Image">
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">Nom Artista</a></p>
                                <p>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </p>
                            </div>
                        </div>

                        <!-- Num2 -->
                        <div class="row disc">
                            <div class="col-xs-6">
                                <img src="http://via.placeholder.com/150x150" style="width:100%" class="img-responsive" alt="Image">
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">Nom Album</a></p>
                                <p>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </p>
                            </div>
                        </div>

                        <!-- Num3 -->
                        <div class="row disc">
                            <div class="col-xs-6">
                                <img src="http://via.placeholder.com/150x150" style="width:100%" class="img-responsive" alt="Image">
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">Nom Album</a></p>
                                <p>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </p>
                            </div>
                        </div>

                    </div>                 
                    <div class="panel-footer"><a href="#">Veuré més</a></div>
                </div>
            </div>


            <!-- Millor valorats -->
            <div class="col-sm-4 col-xs-12">
                <div class="panel">
                    <div class="panel-heading">TOP ALBUMS</div>
                    <div class="panel-body">
                        <!-- Num1 -->
                        <div class="row disc">
                            <div class="col-xs-6">
                                <img src="http://via.placeholder.com/150x150" style="width:100%" class="img-responsive" alt="Image">
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">Nom Album</a></p>
                                <p><a href="#">Nom Artista</a></p>
                                <p><a href="#">Estil</a></p>
                                <p>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </p>
                            </div>
                        </div>

                        <!-- Num2 -->
                        <div class="row disc">
                            <div class="col-xs-6">
                                <img src="http://via.placeholder.com/150x150" style="width:100%" class="img-responsive" alt="Image">
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">Nom Album</a></p>
                                <p><a href="#">Nom Artista</a></p>
                                <p><a href="#">Estil</a></p>
                                <p>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </p>
                            </div>
                        </div>

                        <!-- Num3 -->
                        <div class="row disc">
                            <div class="col-xs-6">
                                <img src="http://via.placeholder.com/150x150" style="width:100%" class="img-responsive" alt="Image">
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">Nom Album</a></p>
                                <p><a href="#">Nom Artista</a></p>
                                <p><a href="#">Estil</a></p>
                                <p>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </p>
                            </div>
                        </div>

                    </div>                 
                    <div class="panel-footer"><a href="#">Veuré més</a></div>
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
                                    <p>19:00</p>
                                </div>
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">Nom Artista</a></p>
                                <p><a href="#">Nom de la Sala</a></p>
                            </div>
                        </div>

                        <!-- Num2 -->
                        <div class="row disc">
                            <div class="col-md-4 col-xs-6">
                                <div class="data">
                                    <p>15</p>
                                    <p>MAR</p>
                                    <p>19:00</p>
                                </div>
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">Nom Artista</a></p>
                                <p><a href="#">Nom de la Sala</a></p>
                            </div>
                        </div>

                        <!-- Num3 -->
                        <div class="row disc">
                            <div class="col-md-4 col-xs-6">
                                <div class="data">
                                    <p>15</p>
                                    <p>MAR</p>
                                    <p>19:00</p>
                                </div>
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">Nom Artista</a></p>
                                <p><a href="#">Nom de la Sala</a></p>
                            </div>
                        </div>

                        <!-- Num4 -->
                        <div class="row disc">
                            <div class="col-md-4 col-xs-6">
                                <div class="data">
                                    <p>15</p>
                                    <p>MAR</p>
                                    <p>19:00</p>
                                </div>
                            </div>

                            <div class="col-xs-6 dades">
                                <p><a href="#">Nom Artista</a></p>
                                <p><a href="#">Nom de la Sala</a></p>
                            </div>
                        </div>

                    </div>

                </div>                 
                <div class="panel-footer"><a href="#">Veuré més</a></div>
            </div>
        </div>
    </div>
</section>
<!-- end Ranks -->

<?php //if (!$userLoggedIn) { // Suprimeixo la condició al haver canviat la section ?>


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