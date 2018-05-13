<?php
require 'header.php';
?>

</header>

<section id="concerts">

    <div class="container">

        <div class="row">
            <div class="col-md-9">
                <h1>Artistes</h1>
            </div>

            <div class="col-md-3">
                <select id="optionLlistar" class="form-control form-control-lg">
                    <option>Per data [Propers primer]</option>
                    <option>Per data [Últims abans]</option>
                    <option>Per nom [A-Z]</option>
                    <option>Per nom [Z-A]</option>
                    <option>Per nom de la sala [A-Z]</option>
                    <option>Per nom de la sala [Z-A]</option>
                </select>
            </div> 
        </div>


        <div class="panel">

            <div class="panel-body" style="text-align: center">

                <!-- ================================================== -->
                <div class="row disc">
                    <div class="col-sm-3">
                        <div class="data">
                            <p>15</p>
                            <p>MAR</p>
                            <p>21:00</p>
                        </div>
                    </div>

                    <div class="col-sm-5 dades">
                        <p><a href="#">Suicidal Tendencies</a></p>
                        <p><a href="#">Razzmatazz</a></p>
                    </div>

                    <div class="col-sm-4 compra">
                        <input type="submit" name="enviar" value="Reserva entrades!" class="btn btn-lg btn-primary btn-block">
                    </div>
                </div>

                <!-- ================================================== -->
                <div class="row disc">
                    <div class="col-sm-3">
                        <div class="data">
                            <p>19</p>
                            <p>MAR</p>
                            <p>19:00</p>
                        </div>
                    </div>

                    <div class="col-sm-5 dades">
                        <p><a href="#">Sobrinus</a></p>
                        <p><a href="#">Bikini</a></p>
                    </div>

                    <div class="col-sm-4 compra">
                        <input type="submit" name="enviar" value="Reserva entrades!" class="btn btn-lg btn-primary btn-block">
                    </div>
                </div>

                <!-- ================================================== -->
                <div class="row disc">
                    <div class="col-sm-3">
                        <div class="data">
                            <p>20</p>
                            <p>MAR</p>
                            <p>22:00</p>
                        </div>
                    </div>

                    <div class="col-sm-5 dades">
                        <p><a href="#">Serie Z</a></p>
                        <p><a href="#">Casal Popular Nou Barris</a></p>
                    </div>

                    <div class="col-sm-4 compra">
                        <input type="submit" name="enviar" value="Reserva entrades!" class="btn btn-lg btn-primary btn-block">
                    </div>
                </div>

                <!-- ================================================== -->
                <div class="row disc">
                    <div class="col-sm-3">
                        <div class="data">
                            <p>20</p>
                            <p>MAR</p>
                            <p>23:00</p>
                        </div>
                    </div>

                    <div class="col-sm-5 dades">
                        <p><a href="#">Rocío Jurado</a></p>
                        <p><a href="#">Teatre Apolo</a></p>
                    </div>

                    <div class="col-sm-4 compra">
                        <input type="submit" name="enviar" value="Reserva entrades!" class="btn btn-lg btn-primary btn-block">
                    </div>
                </div>

                <!-- ================================================== -->
                <div class="row disc">
                    <div class="col-sm-3">
                        <div class="data">
                            <p>21</p>
                            <p>MAR</p>
                            <p>19:00</p>
                        </div>
                    </div>

                    <div class="col-sm-5 dades">
                        <p><a href="#">Joaquín Sabina</a></p>
                        <p><a href="#">Palau Sant Jordi</a></p>
                    </div>

                    <div class="col-sm-4 compra">
                        <input type="submit" name="enviar" value="Reserva entrades!" class="btn btn-lg btn-primary btn-block">
                    </div>
                </div>

            </div>

        </div>
    </div>

</section>

<?php
require 'footer.php';
