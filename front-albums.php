<?php

require 'header.php';

// incloem la classe album
include('class/albums.php');
$albumsObj = new Albums($dbConnection);

?>

</header>

<section id="front-albums">
    <div class="container">


        <div class="row bottom40">
            
            <div class="col-md-9 col-sm-12">
                <h1>Albums</h1>
            </div>
            
            <div class="col-md-3 col-sm-12">
                <select id="optionLlistar" class="form-control form-control-lg">
                    <option name="mode" data-id="az">Per nom [A-Z]</option>
                    <option data-id="za">Per nom [Z-A]</option>
                    <option data-id="mesval">Més valorats</option>
                    <option data-id="menysval">Menys valorats</option>
                    <option data-id="messeg">Més seguidors</option>
                    <option data-id="menysseg">Menys seguidors</option>
                </select>
            </div>
        </div>

        <div id="albumList">
        </div>
    </div>
</section>

<?php

require 'footer.php';
