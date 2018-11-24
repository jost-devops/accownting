$(function() {
    if ($('body').hasClass('route__app_unit_index')) {
        let unitTable = $('.unit--index--table-units').DataTable({
            "paging": true,
            "info": true,
            "processing": true,
            "ajax": "/unit/data",
            "columns": [
                {"data": "id"},
                {"data": "name"},
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        return '<div class="btn-group"><a href="/unit/' + data + '/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>' +
                            '<a href="/unit/' + data + '/delete" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i></a></div>';
                    },
                },
            ],
            "stateSave": true,
            "select": true,
        });
    }
});
