$(document).ready(function () {

    if ($('#artistList').length) { // Si estem a la pàgina d'artistes

        //De sortida ja executem la funció
        listArtists();

        //Ara afegim el listener
        $("#optionLlistar").on('change', function () {
            listArtists();
        });

    }
});

function listArtists() {
    var formData = new FormData();//--> Los objetos FormData le permiten compilar un conjunto de pares clave/valor para enviar mediante XMLHttpRequest.
    formData.append('mode', $('#optionLlistar option:selected').data('id'));
    formData.append('action', 'LLISTA');

    $.ajax({
        url: "action-front-artists.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            $('#artistList').html('<div class="loader"></div>');
        },
        success: function (data) {
            if (data.success == true) { // Si ha ido todo bien
                $('#artistList').html('');

                console.log(data);

                if (data.artists.length) {
                    for (var i in data.artists) {

                        tmpHtml = '<div class="favoritArtistElement col-sm-2 col-xs-6" id="artistElement' + data.artists[i].id + '">';
                        tmpHtml += '<div class="artistElement">';
                        tmpHtml += '<img src="' + data.artists[i].image + '" class="img-responsive artistSearchImage" />';
                        tmpHtml += '<span class="artistSearchName">' + data.artists[i].name + '</span>';
                        tmpHtml += '<span class="artistSearchLikes">';
                        tmpHtml += '<i class="glyphicon glyphicon-heart-empty"></i>';
                        tmpHtml += data.artists[i].likes + ' seguidors</span>';
                        tmpHtml += '<a class="btn btn-primary btn-xs" href="artists-detail.php?artistId=' + data.artists[i].id + '"><i class="glyphicon glyphicon-eye-open"></i> Veure fitxa</a>';
                        tmpHtml += '</div>';
                        tmpHtml += '</div>';
                        $('#artistList').append(tmpHtml);
                    }
                } else {
                    $('#artistList').append('<span class="text-danger">No hi ha cap artista a la base de dades.</span>');
                }
            } else { // Si han habido problemas
                alert('Error processant');
                $('#artistList').html('');
            }
        },
        error: function (ts) {
            alert('Error processant: ' + ts.responseText);
            $('#artistList').html('');
        }
    });
}