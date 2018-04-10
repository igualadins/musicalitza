
$(document).ready( function() {

    if($('#artistSearch').length){ // Si estem a la pàgina d'artistes

        updateFavoritList(); // al carregar la pàgina actualitzem la llista de favorits

        /* cercar artistes */
        $("#artistSearch").on('blur', function () {
            if ($("#artistSearch").val().length > 0) {
                var formData = new FormData();
                formData.append('search', $("#artistSearch").val()); 
                formData.append('action', 'CERCAR');
                $.ajax({
                    url: "action-artists.php",
                    type: "POST",
                    data: formData,
                    contentType:false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function() {
                        $('#search-results').html('<div class="loader"></div>');
                        $('#favorit-artists').hide();
                    },
                    success: function(data) {
                        if (data.success == true){ // Si ha ido todo bien
                            $('#search-results').html('');
                            $('#search-results').append('<div class="closeLayerbutton"><button type="button" onclick="closeSearchArtistLayer();" class="close"><span>&times;</span></button></div>');
                            if (data.artists.length) {
                                for (var i in data.artists) {
                                    tmpHtml = ' <div class="col-sm-4" id="artistElement'+ data.artists[i].id +'"><div class="artistElement"><img src="'+ data.artists[i].image +'" class="img-responsive artistSearchImage" /><span class="artistSearchName">'+ data.artists[i].name +'</span><a class="btn btn-primary btn-xs" href="my-artists-detail.php?artistId=' + data.artists[i].id + '"><i class="glyphicon glyphicon-eye-open"></i> Veure fitxa</a><button class="btn btn-danger btn-xs artistElementButton" id="artistElementButton' + data.artists[i].id + '" data-artist-id="' + data.artists[i].id + '" type="button" onclick="addArtistToFavorites(\'' + data.artists[i].id + '\');"><i class="glyphicon glyphicon-heart"></i> Afegir a favorits</button><span class="artistSearchLikes"><i class="glyphicon glyphicon-heart-empty"></i> ' + data.artists[i].likes + ' seguidors</span></div></div>';
                                    $('#search-results').append(tmpHtml);
                                }
                                disableArtistElementButtons();
                            } else {
                                $('#search-results').append('<span id="search-no-results" class="text-danger">No s\'han trobat resultats</span>');
                            }
                            $('#search-results').append('<div class="clearfix"></div>');
                            $('#search-results').append('<div id="search-results-api-suggest" class="well"><b>¿No has trobat el que volies?</b> <br /> Fes clic al següent botó per cercar a la Base de dades mundial d\'artistes <br /><br /><button class="btn btn-danger" id="search-results-api-button" type="button" onclick="searchArtistInAPI();"><i class="glyphicon glyphicon-search"></i> Cercar</button></div><div id="search-results-api"></div>');
                        } else { // Si han habido problemas
                            alert('Error en la cerca');
                            closeSearchArtistLayer();
                        }
                    },
                    error: function() {
                        alert('Error en la cerca');
                        closeSearchArtistLayer();
                    }
                });
            }
        });
        /* final cercar artistes */

    }

});


/* cercar artistes API */
function searchArtistInAPI() {

    var formData = new FormData();
    formData.append('search', $("#artistSearch").val()); 
    formData.append('action', 'CERCARAPI');
    $.ajax({
        url: "action-artists.php",
        type: "POST",
        data: formData,
        contentType:false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function() {
            $('#search-results-api').html('<div class="loader"></div>');
            if ( $('#search-no-results') ) { $('#search-no-results').hide(); }
            $('#search-results-api-button').hide();
            $('#search-results-api-suggest').hide();            
        },
        success: function(data) {
            if (data.success == true){ // Si ha ido todo bien
                $('#search-results-api').html('');
                if (data.artists.length) {
                    for (var i in data.artists) {
                        tmpHtml = '<div class="col-sm-4" id="'+ data.artists[i].mbid +'"><div class="artistElement"><img src="'+ data.artists[i].image +'" class="img-responsive artistSearchImage" /><span class="artistSearchName">'+ data.artists[i].name +'</span><button class="btn btn-danger btn-xs" type="button" onclick="addArtistFromApi(\'' + data.artists[i].mbid + '\');"><i class="glyphicon glyphicon-heart"></i> Afegir a favorits</button></div></div>';
                        $('#search-results').append(tmpHtml);
                    }
                } else {
                    $('#search-results-api').append('<span class="text-danger">No s\'han trobat resultats</span>');
                }
            } else { // Si han habido problemas
                alert('Error en la cerca');
                closeSearchArtistLayer();
            }
        },
        error: function() {
            alert('Error en la cerca');
            closeSearchArtistLayer();
        }
    });

}
/* final cercar artistes API */

/* Afegir un artista desde l'API a la base de dades de l´aplicació */
function addArtistFromApi(mbid) {
    
    var formData = new FormData();
    formData.append('mbId', mbid); 
    formData.append('action', 'AFEGIRARTIST');
    $.ajax({
        url: "action-artists.php",
        type: "POST",
        data: formData,
        contentType:false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function() {
            $('#' + mbid).html('<div class="smallLoader"></div>');
            // Posar un layer mentre es fa el procès...
        },
        success: function(data) {
            if (data.success == true){ // Si ha ido todo bien
                if (data.artistId > 0) {
                    addArtistToFavorites(data.artistId); // Afegim a favorits
                    $('#' + mbid).html('<span class="text-success"><i class="glyphicon glyphicon-ok"></i> Afegit correctament als teus favorits</span>').delay(1500).fadeOut(700);
                } else {
                    alert('Error a l\'afegir artista');
                    closeSearchArtistLayer();
                }
            } else { // Si han habido problemas
                alert('Error a l\'afegir artista');
                closeSearchArtistLayer();
            }
        },
        error: function() {
            alert('Error a l\'afegir artista');
            closeSearchArtistLayer();
        }
    });

}
/* Afegir un artista desde l'API a la base de dades de l´aplicació */

/* Afegir l'artista als favorits de l'usuari */
function addArtistToFavorites(artistId) {

    var formData = new FormData();
    formData.append('artistId', artistId); 
    formData.append('userId', $("#userId").val()); 
    formData.append('action', 'AFEGIRFAVORITS');
    $.ajax({
        url: "action-artists.php",
        type: "POST",
        data: formData,
        contentType:false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function() {
            $('#artistElementButton'+artistId).prop( "disabled", true ); 
            $('#artistElementButton'+artistId).html('<i class="glyphicon glyphicon-heart"></i> Processant');
        },
        success: function(data) {
            if (data.success == true){ // Si ha ido todo bien
                if (data.userArtistId > 0) {
                    if ($('#artistElementButton'+artistId).length) { 
                        var currentElement = $('#artistElementButton'+artistId);
                        $(currentElement).removeClass('btn-danger').addClass('btn-success');
                        $(currentElement).html('<i class="glyphicon glyphicon-heart"></i> Als teus favorits');
                    }
                    updateFavoritList(); // Actualitzem l'apartat de favorits
                } else {
                    alert('Error a l\'afegir artista a favorits');
                }
            } else { // Si han habido problemas
                alert('Error a l\'afegir artista a favorits');
            }
        },
        error: function() {
            alert('Error a l\'afegir artista a favorits');
        }
    });

}
/* Afegir l'artista als favorits de l'usuari */


/* Treure l'artista de favorits de l'usuari */
function removeArtistToFavorites(artistId) {

    var formData = new FormData();
    formData.append('artistId', artistId); 
    formData.append('userId', $("#userId").val()); 
    formData.append('action', 'TREUREFAVORITS');
    $.ajax({
        url: "action-artists.php",
        type: "POST",
        data: formData,
        contentType:false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function() {
            if($('#favoritArtistElement' + artistId)) $('#favoritArtistElement' + artistId).html('<div class="smallLoader"></div>');
        },
        success: function(data) {
            if (data.success == true){ // Si ha ido todo bien
                if($('#favoritArtistElement' + artistId)) $('#favoritArtistElement' + artistId).html('<span class="text-success"><i class="glyphicon glyphicon-ok"></i> Eliminat correctament dels teus favorits</span>').delay(1500).fadeOut(700).delay(1000).queue(function() { $(this).remove(); });;
            } else { // Si han habido problemas
                alert('Error al treure artista de favorits');
            }
        },
        error: function() {
            alert('Error al treure artista de favorits');
        }
    });

}
/* Treure l'artista de favorits de l'usuari */

/* tanca la capa de cerca d'artistes */
function closeSearchArtistLayer() {
    $('#search-results').html('');
    $('#favorit-artists').show();
}
/* tanca la capa de cerca d'artistes */


/* deshabilita el boto d'afegir a favorits dels artistes que ja hi son a la llista de l'usuari */
function disableArtistElementButtons () {
    $('.artistElementButton').each(function(index, currentElement) {
        var currentId = $(currentElement).attr("data-artist-id");
        // Si trobo a la llista de favorits l'artista resultant de la cerca, deshabilito el seu boto d'afegir a favorits
        if ($('#favoritArtistElement'+currentId).length) { 
            $(currentElement).prop( "disabled", true ); 
            $(currentElement).removeClass('btn-danger').addClass('btn-success');
            $(currentElement).html('<i class="glyphicon glyphicon-heart"></i> Als teus favorits');
        }
    });
}
/* deshabilita el boto d'afegir a favorits dels artistes que ja hi son a la llista de l'usuari */

/* actualitza la llista de favorits */
function updateFavoritList() {

    if($('#favorit-artists-list')) {
        var formData = new FormData();
        formData.append('userId', $("#userId").val()); 
        formData.append('action', 'LLISTAFAVORITS');
        $.ajax({
            url: "action-artists.php",
            type: "POST",
            data: formData,
            contentType:false,
            cache: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
                $('#favorit-artists-list').html('<div class="loader"></div>');
            },
            success: function(data) {
                if (data.success == true){ // Si ha ido todo bien
                    $('#favorit-artists-list').html('');
                    if (data.favoriteArtists.length) {
                        for (var i in data.favoriteArtists) {
                            tmpHtml = ' <div class="favoritArtistElement col-sm-4" id="favoritArtistElement'+ data.favoriteArtists[i].id +'"><div class="artistElement"><img src="'+ data.favoriteArtists[i].image +'" class="img-responsive artistSearchImage" /><span class="artistSearchName">'+ data.favoriteArtists[i].name +'</span><span class="artistSearchLikes"><i class="glyphicon glyphicon-heart-empty"></i> ' + data.favoriteArtists[i].likes + ' seguidors</span><a class="btn btn-primary btn-xs" href="my-artists-detail.php?artistId=' + data.favoriteArtists[i].id + '"><i class="glyphicon glyphicon-eye-open"></i> Veure fitxa</a><button class="btn btn-danger btn-xs text-danger" type="button" onclick="removeArtistToFavorites(\'' + data.favoriteArtists[i].id + '\');"><i class="glyphicon glyphicon-remove"></i> Treure de favorits</button></div></div>';
                            $('#favorit-artists-list').append(tmpHtml);
                        }
                    } else {
                        $('#favorit-artists-list').append('<span class="text-danger">Encara no tens artistes favorits. Fes una cerca i afegeix els teus preferits.</span>');
                    }
                } else { // Si han habido problemas
                    alert('Error processant');
                    $('#favorit-artists-list').html('');
                }
            },
            error: function() {
                alert('Error processant');
                $('#favorit-artists-list').html('');
            }
        });
    }

}  
/* actualitza la llista de favorits */