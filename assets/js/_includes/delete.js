$(function() {
    $('body').on('click', '.btn-delete', function(e) {
        let choice = confirm(translations['Are you sure you want to delete this item?']);

        if (!choice) {
            e.preventDefault();
        }
    });
});
