jQuery( document ).ready( function( $ ) {

    $('.login_form .btn_login').click(function (e) {

        if (!$('.login_form input.username').val() || !$('.login_form input.password').val() ) {
            $('.login_form p.message').removeClass('hidden').addClass('red');
            $('.login_form p.message').text("Please fill inputs!");
            e.preventDefault();
        }
        else {
            $('.login_form p.message').addClass('hidden');
        }
    });
});