$(document).ready(function () {
    $(".message").click(function () {
        $(this).fadeOut()
    });
});

$('#modal-confirmation').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var user_login = button.data('user_login');
    var delete_user_path = button.data('delete_user_path');

    var modal = $(this);
    modal.find('.modal-confirmation-user-login').text(user_login);

    $("#modal-confirmation-confirmation-button").on('click', function() {
        window.location.assign(delete_user_path);
    });
});

$("input.hash_focus").on('focus', function() { $(this).select(); });