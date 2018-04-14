/*
 * Funcio on bloquejem els amics que tenim acceptats
 * OK
 */
function bloquejarAcceptat(userId,friendId,imatge,nom){
   
    var formData = new FormData();
    formData.append('userId', userId); 
    formData.append('friendId', friendId);
    formData.append('blocked', 1);
    formData.append('action', 'BLOQUEJARACCEPTAT');
    $.ajax({
        url: "action-friends.php",
        type: "POST",
        data: formData,
        contentType:false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function() {
            //if($('#artistElement' + artistId)) $('#artistElement' + artistId).html('Processant ...');
        },
        success: function(data) {
            if (data.success == true){ // Si ha ido todo bien
                if (data.accepted >= 0) {                    
                    // treiem usuari del llistat acceptats i l'afegim a bloquejats
                    $('#accept' + friendId).remove();
                    // afegim usuari al llistat de bloquejats
                    $('#group_bloquejats').append(codiBloquejat(userId,friendId,imatge,nom)); 
                } else {
                    alert('Error al bloquejar usuari a acceptats, codi error: 001');
                }
                               
            } else { // Si han habido problemas
                alert('Error al bloquejar usuari a acceptats, codi error: 002');
            }
        },
        error: function() {
            alert('Error al bloquejar usuari a acceptats, codi error: 003');
        }
    });
    
}


/*
 * Funcio on acceptem un amic bloquejat
 * Retorna a l'estat anterior. Es a dir, si estava acceptat o pendent.
 * OK
 */
function acceptarBloquejat(userId,friendId,imatge,nom){
      
    var formData = new FormData();
    formData.append('userId', userId); 
    formData.append('friendId', friendId); 
    formData.append('blocked', 0);
    formData.append('action', 'ACCEPTARBLOQUEJAT');
    $.ajax({
        url: "action-friends.php",
        type: "POST",
        data: formData,
        contentType:false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function() {
            //if($('#artistElement' + artistId)) $('#artistElement' + artistId).html('Processant ...');
        },
        success: function(data) {
            if (data.success == true){ // Si ha ido todo bien
                if (data.accepted >= 0) {                    
                    // treiem usuari del llistat bloquejats
                    $('#bloquejat' + friendId).remove();
                    
                    // segons si l'accepted es 1, vol dir que era un amic acceptat
                    if(data.accepted==1){                    
                        $('#group_accepted').append(codiAcceptat(userId,friendId,imatge,nom));
                    }                   
                } else {
                    alert('Error a l\'aceptar usuari bloquejat, codi error: 007');                    
                }
                                
            } else { // Si han habido problemas
                alert('Error a l\'aceptar usuari bloquejat, codi error: 008');                    
            }
        },
        error: function() {
            alert('Error a l\'aceptar usuari bloquejat, codi error: 009');                    
        }
    });
    
}


/*
 * Funcio que accepta un amic pendent
 * @param {type} userId
 * @param {type} friendId
 * @param {type} imatge
 * @param {type} nom
 * @returns {undefined}
 * OK
 */
function acceptarPendent(userId,friendId,imatge,nom){

    var formData = new FormData();
    formData.append('userId', userId); 
    formData.append('friendId', friendId); 
    formData.append('accepted', 1);
    formData.append('action', 'ACCEPTARPENDENT');
    $.ajax({
        url: "action-friends.php",
        type: "POST",
        data: formData,
        contentType:false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function() {
            //if($('#artistElement' + artistId)) $('#artistElement' + artistId).html('Processant ...');
        },
        success: function(data) {
            if (data.success == true){ // Si ha ido todo bien
                if (data.accepted >= 0) {                    
                    // treiem usuari del llistat pendents i l'afegim a acceptats
                    $('#pendent' + friendId).remove();
                    
                    if(data.accepted==1){                        
                        $('#group_accepted').append(codiAcceptat(userId,friendId,imatge,nom));
                    } else {
                        // no fem res
                    }                    
                } else {
                    alert('Error a l\'aceptar usuari pendent, codi error: 010');                                        
                }                                                       
            } else { // Si han habido problemas
                alert('Error a l\'aceptar usuari pendent, codi error: 011');
            }
        },
        error: function() {
            alert('Error a l\'aceptar usuari pendent, codi error: 012');
        }
    });
    
}

/**
 * Funcio que esborra un usuari de qualsevol estat
 * @param {type} userId
 * @param {type} friendId
 * @param {type} tipus
 * @returns {undefined}
 * OK
 */
function esborrar(userId,friendId,tipus){
    
    var formData = new FormData();
    formData.append('userId', userId); 
    formData.append('friendId', friendId);     
    formData.append('action', 'ESBORRAR');
    $.ajax({
        url: "action-friends.php",
        type: "POST",
        data: formData,
        contentType:false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function() {
            //if($('#artistElement' + artistId)) $('#artistElement' + artistId).html('Processant ...');
        },
        success: function(data) {
            if (data.success == true){ // Si ha ido todo bien
                if (data.accepted >= 0) {                    
                    // treiem usuari del llistat que sigui
                    var identificador = '#' + tipus + friendId;
                    $(identificador).remove();                                                           
                } else {
                    alert('Error a l\'esborrar usuari, codi error: 013');
                }                                
            } else { // Si han habido problemas
                alert('Error a l\'esborrar usuari, codi error: 014');
            }
        },
        error: function() {
            alert('Error a l\'esborrar usuari, codi error: 015');
        }
    });
    
}

/**
 * funcio que retorna el bloc de codi html per un usuari acceptat
 * @param {type} userId
 * @param {type} friendId
 * @param {type} imatge
 * @param {type} nom
 * @returns {String}
 */
function codiAcceptat(userId,friendId,imatge,nom){
            
    var cadena = "<li class=\"list-group-item\" id=\"accept"+friendId+"\">" +
                    "<div class=\"col-xs-12 col-sm-3\">" +
                        "<img src=\""+imatge+"\" height=\"50\" width=\"50\" class=\"img-responsive img-circle\">" + 
                    "</div>" + 
                    "<div class=\"col-xs-12 col-sm-3\">" +
                        "<span class=\"name\">"+nom+"</span><br/>" +
                    "</div>" +
                    "<div class=\"col-xs-12 col-sm-6\">" +
                        "<div class=\"pull-right action-buttons\">" +
                            "<div class=\"pull-right action-buttons\">" +
                                "<button class=\"btn btn-sm btn-danger btn-lock\" type=\"button\" onclick=\"bloquejarAcceptat('"+userId+"','"+friendId+"','"+imatge+"','"+nom+"')\">" +
                                    "<i class=\"glyphicon glyphicon-lock\"></i>" +
                                "</button>" +
                                "<button class=\"btn btn-sm btn-info btn-envelope\" type=\"button\" onclick=\"window.location.href='/musicalitza/my-chat.php?friendId="+friendId+"'\">" +
                                    "<i class=\"glyphicon glyphicon-envelope\"></i>" +
                                "</button>" +
                                "<button class=\"btn btn-sm btn-warning btn-trash\" type=\"button\" onclick=\"esborrar('"+userId+"','"+friendId+"','accept')\">" +
                                    "<i class=\"glyphicon glyphicon-trash\"></i>" +
                                "</button>" +
                            "</div>" +
                        "</div>" +
                    "</div>" +
                    "<div class=\"clearfix\"></div>" +
                "</li>";
                                               
    return cadena; 
}



/**
 * funcio que retorna el bloc de codi html per un usuari bloquejat
 * @param {type} userId
 * @param {type} friendId
 * @param {type} imatge
 * @param {type} nom
 * @returns {String}
 */
function codiBloquejat(userId,friendId,imatge,nom){
        
    var cadena = "<li class=\"list-group-item\" id=\"bloquejat"+friendId+"\">" +
                    "<div class=\"col-xs-12 col-sm-3\">" +
                        "<img src=\""+imatge+"\" height=\"50\" width=\"50\" class=\"img-responsive img-circle\">" + 
                    "</div>" + 
                    "<div class=\"col-xs-12 col-sm-3\">" +
                        "<span class=\"name\">"+nom+"</span><br/>" +
                    "</div>" +
                    "<div class=\"col-xs-12 col-sm-6\">" +
                        "<div class=\"pull-right action-buttons\">" +
                            "<div class=\"pull-right action-buttons\">" +
                                "<button class=\"btn btn-sm btn-success btn-ok\" type=\"button\" onclick=\"acceptarBloquejat('"+userId+"','"+friendId+"','"+imatge+"','"+nom+"')\">" +
                                    "<i class=\"glyphicon glyphicon-ok\"></i>" +
                                "</button>" +
                                "<button class=\"btn btn-sm btn-warning btn-trash\" type=\"button\" onclick=\"esborrar('"+userId+"','"+friendId+"','bloquejat')\">" +
                                    "<i class=\"glyphicon glyphicon-trash\"></i>" +
                                "</button>" +
                            "</div>" +
                        "</div>" +
                    "</div>" +
                    "<div class=\"clearfix\"></div>" +
                "</li>";
                                               
    return cadena; 
    
}


/*
 * Funcio per sol.licitar amistat a un usuari
 * OK
 */
function solicitarAmistat(userId,friendId){
   
    var formData = new FormData();
    formData.append('userId', userId); 
    formData.append('friendId', friendId);
    formData.append('action', 'SOLICITARAMISTAT');
    $.ajax({
        url: "action-friends.php",
        type: "POST",
        data: formData,
        contentType:false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function() {
            $('#userFavoritsElementButton' + friendId).html('Processant ...');
            $('#userFavoritsElementButton' + friendId).prop( "disabled", true ); 
        },
        success: function(data) {
            if (data.success == true){ // Si ha ido todo bien
                if (data.accepted >= 0) {                    
                    $('#userFavoritsElementButton' + friendId).html('Sol.licitud<br />enviada');
                } else {
                    alert('Error al sol.licitar amistat');
                }
                               
            } else { // Si han habido problemas
                alert('Error al sol.licitar amistat');
            }
        },
        error: function() {
            alert('Error al sol.licitar amistat');
        }
    });
    
}

/*
 * Funcio per eliminat la rel.lacio amb un usuari desde fora de la pantalla d'amics
 * OK
 */
function eliminarAmistat(userId,friendId){
    
    var formData = new FormData();
    formData.append('userId', userId); 
    formData.append('friendId', friendId);     
    formData.append('action', 'ESBORRAR');
    $.ajax({
        url: "action-friends.php",
        type: "POST",
        data: formData,
        contentType:false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function() {
            $('#userFavoritsElementButton' + friendId).html('Processant ...');
            $('#userFavoritsElementButton' + friendId).prop( "disabled", true ); 
        },
        success: function(data) {
            if (data.success == true){ // Si ha ido todo bien
                if (data.accepted >= 0) {
                    $('#userFavoritsElementButton' + friendId).html('Rel.lació<br />eliminada');
                } else {
                    alert('Error');
                }                                
            } else { // Si han habido problemas
                alert('Error');
            }
        },
        error: function() {
            alert('Error');
        }
    });
    
}