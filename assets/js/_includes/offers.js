var language = require('../de.json');

$(function() {
    if ($('body').hasClass('route__app_offer_index')) {
        let offerTable = $('.offer--index--table-offers').DataTable({
            "language": language,
            "paging": true,
            "info": true,
            "processing": true,
            "ajax": "/offer/data",
            "columns": [
                {"data": "offerNumber"},
                {"data": "offerDate"},
                {"data": "company"},
                {"data": "customer"},
                {"data": "subject"},
                {
                    "data": "totalNetPrice",
                    "render": function (data, type, row) {
                        return '<small>(' + translations['Net'] + ')</small> ' + data + ' €<br><small>(' + translations['Gross'] + ')</small> ' + row.totalGrossPrice + ' €';
                    },
                    'className': 'text-right',
                },
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        return '<div class="btn-group"><a href="/offer/' + data + '/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>' +
                            '<a href="/offer/' + data + '/delete" class="btn btn-danger btn-sm btn-delete"><i class="fa fa-remove"></i></a>' +
                            '<a href="/offer/' + data + '/pdf" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i></a></div>';
                    },
                },
            ],
            "stateSave": true,
            "select": true,
        });
    }
});
