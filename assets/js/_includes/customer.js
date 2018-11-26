var language = require('../de.json');

$(function() {
    if ($('body').hasClass('route__app_customer_index')) {
        let customerTable = $('.customer--index--table-customers').DataTable({
            "language": language,
            "paging": true,
            "info": true,
            "processing": true,
            "ajax": "/customer/data",
            "columns": [
                {"data": "id"},
                {"data": "name"},
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        return '<div class="btn-group"><a href="/customer/' + data + '/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>' +
                            '<a href="/customer/' + data + '/delete" class="btn btn-danger btn-sm btn-delete"><i class="fa fa-remove"></i></a></div>';
                    },
                },
            ],
            "stateSave": true,
            "select": true,
        });
    }
});
