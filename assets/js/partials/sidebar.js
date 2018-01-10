$(document).ready(function () {

    var isAuthenticated = $('.js-user-logged').data('is-authenticated');

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar, #content').toggleClass('active');
        $(this).toggleClass('active');
    });

})
;
