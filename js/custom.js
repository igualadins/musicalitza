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
});


/* valoracions amb estrelles */

$(document).ready(function(){

    if($('#formValorar').length) {

        $('.estrella').on("mouseover",function(){
            //segons la id de l'estrella on estem iluminem mes o menys estrelles
            var estrella_id = $(this).attr('id');
            switch (estrella_id){
                case "estrella-1":
                    $("#estrella-1").addClass('estrella-marcada');
                    break;
                case "estrella-2":
                    $("#estrella-1").addClass('estrella-marcada');
                    $("#estrella-2").addClass('estrella-marcada');
                    break;
                case "estrella-3":
                    $("#estrella-1").addClass('estrella-marcada');
                    $("#estrella-2").addClass('estrella-marcada');
                    $("#estrella-3").addClass('estrella-marcada');
                    break;
                case "estrella-4":
                    $("#estrella-1").addClass('estrella-marcada');
                    $("#estrella-2").addClass('estrella-marcada');
                    $("#estrella-3").addClass('estrella-marcada');
                    $("#estrella-4").addClass('estrella-marcada');
                    break;
                case "estrella-5":
                    $("#estrella-1").addClass('estrella-marcada');
                    $("#estrella-2").addClass('estrella-marcada');
                    $("#estrella-3").addClass('estrella-marcada');
                    $("#estrella-4").addClass('estrella-marcada');
                    $("#estrella-5").addClass('estrella-marcada');
                    break;
            }
        }).mouseout(function(){
            $('.estrella').removeClass('estrella-marcada'); // treiem les estrelles marcades
            var valor = $('#valoracio').val();
            for(var i = valor; i>0; i--) { // marquem les que s'han valorat
                $("#estrella-" + i).addClass('estrella-marcada');
            }
        }); 
         
        $('.estrella').click(function(){
            // Al fer clic en una estrella guardem el seu "valor" al camp hidden de la valoració
            var valoracio = $(this).attr("id").split("-")[1];        
            $('#numero-valoracio').html(valoracio); // el mostrem
            $('#valoracio').val(valoracio); // el guardem al input
        });

        $('#formValorar').on("submit",function(e){ 
            e.preventDefault();
            if(this.valoracio.value > 0) { // Si s'ha fet valoració

                var formData = new FormData();
                formData.append('valoracio', this.valoracio.value); // la valoracio
                formData.append('comentari', this.comentari.value); // el comentari
                formData.append('relationId', $('#relationId').val());  // el id de rel.lació d'usuari amb l'element (artist o album)
                if( $('#relationType').val() == 1 ) { // 1 - artist, 2 - album 
                    var urlToPost = 'action-artists.php'; // ruta si es una valoració de artistes
                } else {
                    var urlToPost = 'action-albums.php'; // ruta si es una valoració de albums
                }
                formData.append('action', 'VALORAR');
                $.ajax({
                    url: urlToPost,
                    type: "POST",
                    data: formData,
                    contentType:false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function() {
                        $('#contenidor-valoracions').html('<div class="loader"></div>');
                    },
                    success: function(data) {
                        if (data.success == true){ // Si ha ido todo bien
                            $('#contenidor-valoracions').html('<span class="text-success"><i class="glyphicon glyphicon-ok"></i> Valoracio registrada correctament</span>').delay(3500).fadeOut(700);
                        } else { // Si han habido problemas
                            alert('Error al fer la valoracio');
                            $('#contenidor-valoracions').fade(0);
                        }
                    },
                    error: function() {
                        alert('Error al fer la valoracio');
                        $('#contenidor-valoracions').fade(0);
                    }
                });

            } else { // Si no s'ha fet valoració
                alert('Error: feu servir les estrelles per indicar la vostra valoració abans d\'enviar els comentaris');
            }
        });
 
    } 

});
