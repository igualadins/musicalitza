var xatInterval;

$(document).ready( function() {

    if($('#my-chat').length){ // Si estem a la pàgina de xat

        var chatMessages = document.getElementById("chatMessages");
        chatMessages.scrollTop = chatMessages.scrollHeight; // Fem que l'scroll vagi a baix de tot nomès entrar, per si te converses antigues

        xatInterval = setInterval( updateChat, 5000); // Actualitzem el chat cada 5 segons

        $("#my-chat-form").submit(function(e){
            
            e.preventDefault(e); // evitem el submit del form

            if ( $("#chat-message").val().trim().length > 0 ) {
                sendMessage(); // Si el camp de missatge te contingut, fem l'enviament per AJAX
            }
           
        });

    }

});

/* enviar missatge de xat */
function sendMessage() {

    var formData = new FormData();
    formData.append('chat-message', $("#chat-message").val());
    formData.append('friendId', $("#friendId").val());
    formData.append('userId', $("#userId").val());
    formData.append('action', 'ENVIARMISSATGE');
    $.ajax({
        url: "action-chat.php",
        type: "POST",
        data: formData,
        contentType:false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function() {
            $('#btn-chat').prop( "disabled", true ); 
        },
        success: function(data) {
            if (data.success == true){ // Si ha ido todo bien
                $("#chat-message").val('');
                stopTimer();                
                starTimer();                
            } else { // Si han habido problemas
                alert('Error al enviar el missatge');
            }
            $('#btn-chat').prop( "disabled", false );
        },
        error: function() {
            alert('Error al enviar el missatge');
            $('#btn-chat').prop( "disabled", false ); 
        }
    });

}
/* final enviar missatge de xat */

/* actualitzar el xat */
function updateChat() {

    var formData = new FormData();
    // Si no hi ha missatges enviem un 0
    var lastChatId = $( "#chat li" ).length ? $( "#chat li" ).last().attr('id') : 0;
    formData.append('lastChatId', lastChatId);
    var friendId = $("#friendId").val();
    formData.append('friendId', friendId);
    var userId = $("#userId").val();
    formData.append('userId', userId);
    formData.append('action', 'ACTUALITZARCHAT');
    $.ajax({
        url: "action-chat.php",
        type: "POST",
        data: formData,
        contentType:false,
        cache: false,
        processData: false,
        dataType: 'json',
        success: function(data) {
            if (data.success == true){ // Si ha ido todo bien
                if (data.missatges.length) { // Si arriben missatges
                    $('#chat').append(data.missatges);
                    var chatMessages = document.getElementById("chatMessages");
                    chatMessages.scrollTop = chatMessages.scrollHeight; // Fem que l'scroll vagi a baix de tot a l'actualitzar
                }
            }
        }
    });

}
/* final actualitzar el xat */

function stopTimer(){
    clearInterval(xatInterval);
}

function starTimer(){
    xatInterval = setInterval( updateChat, 5000);
}
