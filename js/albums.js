
$(document).ready(function () {

    if ($('#albumSearch').length) { // Si estem a la pàgina d'albums 

        updateAlbumFavoritList(); // al carregar la pàgina actualitzem la llista de favorits

        /* cercar albumes */
        $("#albumSearch").on('blur', function () {//Quan perdi el focus (no quan es faci click!)
            if ($("#albumSearch").val().length > 0) {//I en cas que s'hagi introduït algu a l'input albumSearch
                var formData = new FormData();//Creem obj FormData
                formData.append('search', $("#albumSearch").val()); //Param search amb el valor entrat per l'usuari a l'input
                formData.append('action', 'CERCAR');//La acció a fer a action-albums.php es cercar
                $.ajax({
                    url: "action-albums.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function () {
                        $('#search-results').html('<div class="loader"></div>');
                        $('#favorit-albums').hide();
                    },
                    success: function (data) {
                        if (data.success == true) { // Si ha ido todo bien
                            $('#search-results').html('');//comencem a omplir
                            $('#search-results').append('<div class="closeLayerbutton"><button type="button" onclick="closeSearchAlbumLayer();" class="close"><span>&times;</span></button></div>');//Botó tancar                     
                            if (data.albums.length) {//Si la cerca ha tornat elements...
                                for (var i in data.albums) {//Recorrem els elements trobats
                                    tmpHtml = '<div class="col-sm-4" id="albumElement' + data.albums[i].id + '"><div class="albumElement"><img src="' + data.albums[i].image + '" class="img-responsive albumSearchImage" /><span class="albumSearchName">' + data.albums[i].name + '</span><a class="btn btn-primary btn-xs" href="my-albums-detail.php?albumId=' + data.albums[i].id + '"><i class="glyphicon glyphicon-eye-open"></i> Veure fitxa</a><button class="btn btn-danger btn-xs albumElementButton" id="albumElementButton' + data.albums[i].id + '" data-album-id="' + data.albums[i].id + '" type="button" onclick="addAlbumToFavorites(\'' + data.albums[i].id + '\');"><i class="glyphicon glyphicon-heart"></i> Afegir a favorits</button><span class="albumSearchLikes"><i class="glyphicon glyphicon-heart-empty"></i> ' + data.albums[i].likes + ' seguidors</span></div></div>';//Mostrem els albums
                                    $('#search-results').append(tmpHtml);//Els afegim al div amb id search-results
                                }
				                disableAlbumsElementButtons();
                            } else {//Si no... mostrem que no s'ha trobat res...
                                $('#search-results').append('<span id="search-no-results" class="text-danger">No s\'han trobat resultats</span>');
                            }//Oferim buscar a la API de LastFM
                            $('#search-results').append('<div class="clearfix"></div>');
                            $('#search-results').append('<div id="search-results-api-suggest" class="well"><b>¿No has trobat el que volies?</b> <br /> Fes clic al següent botó per cercar a la Base de dades mundial d\'albumes <br /><br /><button class="btn btn-danger" id="search-results-api-button" type="button" onclick="searchAlbumInAPI();"><i class="glyphicon glyphicon-search"></i> Cercar</button></div><div id="search-results-api"></div>');
                        } else { // Si han habido problemas
                            alert('Error en la cerca');
                            closeSearchAlbumLayer();
                        }
                    },
                    error: function () {
                        alert('Error en la cerca');
                        closeSearchAlbumLayer();
                    }
                });
            }
        });
        /* final cercar albumes */

    }

});


/* cercar albumes API */
function searchAlbumInAPI() { 

    var formData = new FormData();//Creem objecte formdata
    formData.append('search', $("#albumSearch").val()); //Agafem el valor de l'input
    formData.append('action', 'CERCARAPI');//Farem la opció CERCARAPI
    $.ajax({
        url: "action-albums.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            $('#search-results-api').html('<div class="loader"></div>');
            if ($('#search-no-results')) { $('#search-no-results').hide(); }
            $('#search-results-api-button').hide();
            $('#search-results-api-suggest').hide();
        },
        success: function (data) {
            if (data.success == true) { // Si ha ido todo bien... Mostrem els resultats maquetats
                $('#search-results-api').html('');
                if (data.albums.length) {
                    for (var i in data.albums) {
                        tmpHtml = '<div class="col-sm-4" id="' + data.albums[i].mbid + '"><div class="albumElement"><img src="' + data.albums[i].image + '" class="img-responsive albumSearchImage" /><span class="albumSearchName">' + data.albums[i].name + '</span><button class="btn btn-danger btn-xs" type="button" onclick="addAlbumFromApi(\'' + data.albums[i].mbid + '\');"><i class="glyphicon glyphicon-heart"></i> Afegir a favorits</button></div></div>';
                        $('#search-results').append(tmpHtml);
                    }
                } else {
                    $('#search-results-api').append('<span class="text-danger">No s\'han trobat resultats</span>');
                }
            } else { // Si han habido problemas
                alert('Error en la cerca');
                closeSearchAlbumLayer();
            }
        },
        error: function () {
            alert('Error en la cerca');
            closeSearchAlbumLayer();
        }
    });

}
/* final cercar albumes API */

/* Afegir un albuma desde l'API a la base de dades de l´aplicació */
function addAlbumFromApi(mbid) {

    var formData = new FormData();
    formData.append('mbId', mbid);
    formData.append('action', 'AFEGIRALBUM');
    $.ajax({
        url: "action-albums.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            $('#' + mbid).html('<div class="smallLoader"></div>');
            // Posar un layer mentre es fa el procès...
        },
        success: function (data) {
            if (data.success == true) { // Si ha ido todo bien
                if (data.albumId > 0) {
                    addAlbumToFavorites(data.albumId); // Afegim a favorits
                    $('#' + mbid).html('<span class="text-success"><i class="glyphicon glyphicon-ok"></i> Afegit correctament als teus favorits</span>').delay(1500).fadeOut(700);
                } else {
                    alert('Error a l\'afegir album -> ' + data.albumId);
                    closeSearchAlbumLayer();
                }
            } else { // Si han habido problemas
                alert('Error a l\'afegir album');
                closeSearchAlbumLayer();
            }
        },
        error: function (ts) {
            alert(ts.responseText);
            closeSearchAlbumLayer();
        }
    });

}
/* Afegir un album desde l'API a la base de dades de l´aplicació */

/* Afegir l'album als favorits de l'usuari */
function addAlbumToFavorites(albumId) {

    var formData = new FormData();
    formData.append('albumId', albumId);
    formData.append('userId', $("#userId").val());
    formData.append('action', 'AFEGIRFAVORITS');
    $.ajax({
        url: "action-albums.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            $('#albumElementButton'+albumId).prop( "disabled", true ); 
            $('#albumElementButton'+albumId).html('<i class="glyphicon glyphicon-heart"></i> Processant');
        },
        success: function (data) {
            if (data.success == true) { // Si ha ido todo bien
                if (data.userAlbumId > 0) {
                    if ($('#albumElementButton'+albumId).length) { 
                        var currentElement = $('#albumElementButton'+albumId);
                        $(currentElement).removeClass('btn-danger').addClass('btn-success');
                        $(currentElement).html('<i class="glyphicon glyphicon-heart"></i> Als teus favorits');
                    }
                    updateAlbumFavoritList(); // Actualitzem l'apartat de favorits
                } else {
                    alert('Error a l\'afegir album a favorits');
                }
            } else { // Si han habido problemas
                alert('Error a l\'afegir album a favorits');
            }
        },
        error: function () {
            alert('Error a l\'afegir album a favorits');
        }
    });

}
/* Afegir l'album als favorits de l'usuari */


/* Treure l'album de favorits de l'usuari */
function removeAlbumToFavorites(albumId) {

    var formData = new FormData();
    formData.append('albumId', albumId);
    formData.append('userId', $("#userId").val());
    formData.append('action', 'TREUREFAVORITS');
    $.ajax({
        url: "action-albums.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            if($('#favoritAlbumElement' + albumId)) $('#favoritAlbumElement' + albumId).html('<div class="smallLoader"></div>');
        },
        success: function (data) {
            if (data.success == true) { // Si ha ido todo bien
                if($('#favoritAlbumElement' + albumId)) $('#favoritAlbumElement' + albumId).html('<span class="text-success"><i class="glyphicon glyphicon-ok"></i> Eliminat correctament dels teus favorits</span>').delay(1500).fadeOut(700).delay(1000).queue(function() { $(this).remove(); });;
            } else { // Si han habido problemas
                alert('Error al treure album de favorits');
            }
        },
        error: function () {
            alert('Error al treure album de favorits');
        }
    });

}
/* Treure l'album de favorits de l'usuari */

/* tanca la capa de cerca d'albumes */
function closeSearchAlbumLayer() {
    $('#search-results').html('');
    $('#favorit-albums').show();
}
/* tanca la capa de cerca d'albumes */


/* deshabilita el boto d'afegir a favorits dels albums que ja hi son a la llista de l'usuari */
function disableAlbumElementButtons () {
    $('.albumElementButton').each(function(index, currentElement) {
        var currentId = $(currentElement).attr("data-album-id");
        // Si trobo a la llista de favorits l'album resultant de la cerca, deshabilito el seu boto d'afegir a favorits
        if ($('#favoritAlbumElement'+currentId).length) { 
            $(currentElement).prop( "disabled", true ); 
            $(currentElement).removeClass('btn-danger').addClass('btn-success');
            $(currentElement).html('<i class="glyphicon glyphicon-heart"></i> Als teus favorits');
        }
    });
}
/* deshabilita el boto d'afegir a favorits dels albums que ja hi son a la llista de l'usuari */

/* actualitza la llista de favorits */
function updateAlbumFavoritList() {

    if ($('#favorit-albums-list').length) {//Si existeix el div amb id favorit-albums-list
        var formData = new FormData(); //Creem un objecte formData --->
        formData.append('userId', $("#userId").val()); //Guardem al formData el nom de l'usuari en sessió a la variable userId
        formData.append('action', 'LLISTAFAVORITS'); //Posem a la variable action que la acció a fer serà llistar els favorits
        $.ajax({
            url: "action-albums.php", //Enviem el POST a aquest arxiu php
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                $('#favorit-albums-list').html('<div class="loader"></div>');
            },
            success: function (data) {
                if (data.success == true) { // Si ha ido todo bien
                    $('#favorit-albums-list').html('');
                    if (data.favoriteAlbums.length) {
                        for (var i in data.favoriteAlbums) {
                            tmpHtml = '<div class="favoritAlbumElement col-sm-4" id="favoritAlbumElement' + data.favoriteAlbums[i].id + '"><div class="albumElement"><img src="' + data.favoriteAlbums[i].image + '" class="img-responsive albumSearchImage" /><span class="albumSearchName">' + data.favoriteAlbums[i].name + '</span><span class="albumSearchLikes"><i class="glyphicon glyphicon-heart-empty"></i> ' + data.favoriteAlbums[i].likes + ' seguidors</span><a class="btn btn-primary btn-xs" href="my-albums-detail.php?albumId=' + data.favoriteAlbums[i].id + '"><i class="glyphicon glyphicon-eye-open"></i> Veure fitxa</a><button class="btn btn-danger btn-xs text-danger" type="button" onclick="removeAlbumToFavorites(\'' + data.favoriteAlbums[i].id + '\');"><i class="glyphicon glyphicon-remove"></i> Treure de favorits</button></div></div>';
                            $('#favorit-albums-list').append(tmpHtml);
                        }
                    } else {
                        $('#favorit-albums-list').append('<span class="text-danger">Encara no tens albums favorits. Fes una cerca i afegeix els teus preferits.</span>');
                    }
                } else { // Si han habido problemas
                    alert("Error processant");
                    $('#favorit-albums-list').html('');
                }
            },
            error: function() {
                alert('Error processant');
                $('#favorit-albums-list').html('');
            }
        });
    }

}
/* actualitza la llista de favorits */