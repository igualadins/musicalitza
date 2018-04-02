<<<<<<< HEAD
/* 
 * Musicalitza. Custom JS scripts
 */

// WOW Scrolling effects
// ================================================== */
new WOW().init();

// Parallax Home Banner Effect
// ================================================== */
$(document).ready(function () {

    $(window).scroll(function () {
        var barra = $(window).scrollTop();
        var posicion = (barra * 0.50);

        $('.jumbotron').css({
            'background-position': '0 -' + posicion + 'px'
        });
    });
});
=======
>>>>>>> 2f57c9101ed4b890980467b97be3a255149f6185

// Per quan es fa servir l'input de tipus file per actualitzar l'imatge
// Amb aquest petit script li canviem el nom pel nom de l'imatge sel.lecionada
$(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});
$(document).ready( function() {
    $(':file').on('fileselect', function(event, numFiles, label) {
      var input = $(this).parents('.input-group').find(':text');
      input.val(label);
    });
<<<<<<< HEAD
});
=======
});
>>>>>>> 2f57c9101ed4b890980467b97be3a255149f6185
