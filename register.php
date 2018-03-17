<?php

require 'header.php';
?>

<? if ($userLoggedIn) { // Si l'usuari ha fet login mostrem un boto cap el panell d'usuari ?>

    <div id="my-account" class="container top40 bottom80">

        <div class="row">
            <div class="col-sm-12 text-center">
                <h1>Benvingut!!</h1>
            </div>
        </div>

        <div class="row top40 bottom40 login">
            <div class="col-lg-3 col-lg-offset-2 col-sm-4 col-sm-offset-2 col-xs-8 col-xs-offset-2">
                <a class="btn btn-lg btn-primary btn-block" href="my-account.php">Anar al meu compte</a>
            </div>
        </div>

    </div>

<? } else { // Si l'usuari no està identificat, mostrem el form de registre ?>

    <div id="my-account" class="container top40 bottom80">

        <div class="row">
            <div class="col-sm-12 text-center">
                <h1>Troba amics musicals!</h1>
            </div>
        </div>

        <div class="row top40 bottom40 login">


          <?php if(isset($_GET['error'])) { // Si rebem un error per parametre a la url mostrem un missatge a l'usuari per indicar que algo ha anat malament ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <b>Error en registre</b><br />
              Hi ha hagut un error en el procès de registre d'usuari
              <?php if(isset($_GET['message'])) { echo "<br><b>Motiu: </b>".$_GET['message']; }?>
            </div>
          <? } ?>

          <div class="col-sm-6">
            <h2 class="form-signin-heading">Registra't ara i podràs..</h2>
            <ul class="list-unstyled advantageList">
              <li class="registerBenefit"><span class="glyphicon glyphicon-ok text-success"></span> Estar informat de concerts i novetats musicals</li>
              <li class="registerBenefit"><span class="glyphicon glyphicon-ok text-success"></span> Indicar els teus artistes favorits</li>
              <li class="registerBenefit"><span class="glyphicon glyphicon-ok text-success"></span> Indicar els teus albums favorits</li>
              <li class="registerBenefit"><span class="glyphicon glyphicon-ok text-success"></span> Trobar amics amb els mateixos gustos musicals</li>
              <li class="registerBenefit"><span class="glyphicon glyphicon-ok text-success"></span> Intercambiar missatges amb altres usuaris</li>
            </ul>
            <p>I tot completament <span class="text-success">GRATIS</span></p>
            <p>No ho dubtis, forma part de la nostra comunitat i podràs trobar nous amics amb qui sortir, compartir musica, anar de concerts, i qui sap si potser trobar parella i tot! ;)</p>
          </div>
          <div class="col-sm-6">
            <h2 class="form-signin-heading">Crear un compte</h2>
            <div class="well">
              <form autocomplete="off" action="action-register.php" method="POST">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Nom d'usuari" name="inputNickname" required autofocus tabindex="1">
                </div>
                <div class="form-group">
                  <input type="email" class="form-control" placeholder="Email" name="inputEmail" required tabindex="2">
                </div>
                <div class="form-group">
                  <input type="Password" class="form-control" placeholder="Password" name="inputPassword" required tabindex="3">
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="conditions" value="conditions" required  tabindex="4"> He llegit i accepto les condicions d'ús i pol&iacute;tica de privacitat de la web
                  </label>
                </div>
                <input type="submit" name="enviar" value="Crear un compte" class="btn btn-lg btn-primary btn-block"  tabindex="5" />
              </form>
              <div class="clearfix"></div>
            </div>
          </div>

        </div>

    </div>

<? } ?>

<?php

require 'footer.php';
?>  
