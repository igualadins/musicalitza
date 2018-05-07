/*
 * Funcio on enviem peticio d'amistat
 */
function enviarAmistat(userId,friendId){
    console.log(userId + " " + friendId);
    var formData = new FormData();
    formData.append('userId', userId); 
    formData.append('friendId', friendId);    
    formData.append('action', 'ENVIARAMISTAT');
    $.ajax({
        url: "action-account.php",
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
                    var identificador = '#suggest' + friendId;
                    $(identificador).remove();                                                           
                } else {
                    alert('Error a l\'esborrar usuari amb afinitat, codi error: 0133');
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


