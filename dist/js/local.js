function unhideL() {
    setTimeout(function() {
            $(".loading-clock").hide();
        }, 1000) //wait ten seconds before continuing
}
$(document).ready(function(e) {
    $('body').addClass('text-sm');
    $('.nav-sidebar').addClass('nav-child-indent');
    $('.main-sidebar').addClass('sidebar-no-expand');
});