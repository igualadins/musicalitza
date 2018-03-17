<?php

require 'header.php';
?>

    <div id="my-account" class="container top40 bottom80">

        <div class="row">
            <div class="col-sm-12 text-center">
                <h1>Troba amics musicals!</h1>
            </div>
        </div>

        <div class="row top40 bottom40 login">

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
              <form autocomplete="off">
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
                    <input type="checkbox" name="conditions" value="conditions" required> He llegit i accepto les condicions d'ús i pol&iacute;tica de privacitat de la web
                  </label>
                </div>
                <input type="submit" name="enviar" value="Crear un compte" class="btn btn-lg btn-primary btn-block" />
              </form>
              <div class="clearfix"></div>
            </div>
          </div>

        </div>

    </div>

<?php

require 'footer.php';
?>  
