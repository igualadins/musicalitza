$(document).ready(function () {

    if ($('#albumList').length) { // Si estem a la pàgina d'albums

        //De sortida ja executem la funció
        listAlbums();

        //Ara afegim el listener
        $("#optionLlistar").on('change', function () {
            listAlbums();
        });

    }
});

function listAlbums() {
    var formData = new FormData();//--> Los objetos FormData le permiten compilar un conjunto de pares clave/valor para enviar mediante XMLHttpRequest.
    formData.append('mode', $('#optionLlistar option:selected').data('id'));
    formData.append('action', 'LLISTA');

    $.ajax({
        url: "action-front-albums.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            $('#albumList').html('<div class="loader"></div>');
        },
        success: function (data) {
            if (data.success == true) { // Si ha ido todo bien
                $('#albumList').html('');

                console.log(data);

                if (data.albums.length) {
                    for (var i in data.albums) {

                        tmpHtml = '<div class="favoritAlbumElement col-sm-2 col-xs-6" id="albumElement' + data.albums[i].id + '">';
                        tmpHtml += '<div class="albumElement">';
                        tmpHtml += '<img src="' + data.albums[i].image + '" class="img-responsive albumSearchImage" />';
                        tmpHtml += '<span class="albumSearchName">' + data.albums[i].name + '</span>';
                        tmpHtml += '<span class="albumSearchLikes">';
                        tmpHtml += '<i class="glyphicon glyphicon-heart-empty"></i>';
                        tmpHtml += data.albums[i].likes + ' seguidors</span>';
                        tmpHtml += '<a class="btn btn-primary btn-xs" href="albums-detail.php?albumId=' + data.albums[i].id + '"><i class="glyphicon glyphicon-eye-open"></i> Veure fitxa</a>';
                        tmpHtml += '</div>';
                        tmpHtml += '</div>';
                        $('#albumList').append(tmpHtml);
                    }
                } else {
                    $('#albumList').append('<span class="text-danger">No hi ha cap albuma a la base de dades.</span>');
                }
            } else { // Si han habido problemas
                alert('Error processant');
                $('#albumList').html('');
            }
        },
        error: function (ts) {
            alert('Error processant: ' + ts.responseText);
            $('#albumList').html('');
        }
    });
}