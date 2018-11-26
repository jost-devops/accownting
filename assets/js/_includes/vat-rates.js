var language = require('../de.json');

$(function() {
    if ($('body').hasClass('route__app_vatrate_index')) {
        let vatRateTable = $('.vat-rate--index--table-vat-rates').DataTable({
            "language": language,
            "paging": true,
            "info": true,
            "processing": true,
            "ajax": "/vat-rate/data",
            "columns": [
                {"data": "id"},
                {"data": "name"},
                {"data": "rate"},
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        return '<div class="btn-group"><a href="/vat-rate/' + data + '/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>' +
                            '<a href="/vat-rate/' + data + '/delete" class="btn btn-danger btn-sm btn-delete"><i class="fa fa-remove"></i></a></div>';
                    },
                },
            ],
            "stateSave": true,
            "select": true,
        });
    }
});
