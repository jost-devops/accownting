$(function() {
    if ($('body').hasClass('route__app_user_index')) {
        let userTable = $('.user--index--table-users').DataTable({
            "paging": true,
            "info": true,
            "processing": true,
            "ajax": "/user/data",
            "columns": [
                {"data": "id"},
                {"data": "email"},
                {"data": "name"},
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        return '<div class="btn-group"><a href="/user/' + data + '/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>' +
                            '<a href="/user/' + data + '/delete" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i></a></div>';
                    },
                },
            ],
            "stateSave": true,
            "select": true,
        });
    }
});
