
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