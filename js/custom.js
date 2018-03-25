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