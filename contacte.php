<?php
require 'header.php';
?>

<div id="contacte" class="container">
    <div class="row bottom80 top20">
        <div class="col-md-6 col-md-offset-3">
            <div class="form-area">  
                <form role="form">
                    <br style="clear:both">
                    <h1 class="bottom20">Contacta amb nosaltres!</h1>
                    <div class="form-group">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nom i Cognoms" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="email" name="email" placeholder="Correu electrònic" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Títol" required>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" type="textarea" id="message" placeholder="Missatge" maxlength="140" rows="7"></textarea>
                        <span class="help-block"><p id="characterLeft" class="help-block ">You have reached the limit</p></span>                    
                    </div>

                    <button type="button" id="submit" name="submit" class="btn btn-lg btn-primary pull-right">Envia missatge</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require 'footer.php';
