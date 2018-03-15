<?php

require 'header.php';
?>

    <div id="my-account" class="container top40 bottom80">

        <div class="row">
            <div class="col-lg-3 col-lg-offset-1 col-sm-4 col-sm-offset-1 col-xs-8 col-xs-offset-2">
            <h1>Benvingut!!</h1>
            </div>
        </div>

        <div class="row top40 bottom40 login">

            <div class="col-lg-3 col-lg-offset-2 col-sm-4 col-sm-offset-2 col-xs-8 col-xs-offset-2">
                <h2 class="form-signin-heading">Crear un compte</h2>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Crear un compte</button>
            </div>

            <div class="col-lg-3 col-lg-offset-2 col-sm-4 col-sm-offset-2 col-xs-8 col-xs-offset-2">
                <form class="form-signin">
                    <h2 class="form-signin-heading">Ja estàs registrat?</h2>
                    <label for="inputEmail" class="sr-only">Correu electrònic</label>
                    <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
                    <label for="inputPassword" class="sr-only">Contrasenya</label>
                    <input type="password" id="inputPassword" class="form-control" placeholder="Password" required="">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="remember-me"> Recorda-ho
                        </label>
                    </div>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Accedir</button>
                </form>
            </div>

        </div>

    </div>

<?php

require 'footer.php';
?>  
