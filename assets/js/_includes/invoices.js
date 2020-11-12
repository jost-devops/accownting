let moment = require('moment');
let language = require('../de.json');

$(function() {
    if ($('body').hasClass('route__app_invoice_index')) {
        let invoiceTable = $('.invoice--index--table-invoices').DataTable({
            "language": language,
            "paging": true,
            "info": true,
            "processing": true,
            "ajax": "/invoice/data",
            "columns": [
                {"data": "invoiceNumber"},
                {
                    "data": "invoiceDate",
                    "render": function (data, type, row, meta) {
                        if (type === 'sort' || type === 'type') {
                            return data;
                        }

                        return moment(data, 'YYYY-MM-DD').format('DD.MM.YYYY');
                    },
                },
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
                    "data": "paid",
                    "render": function (data, type, row) {
                        return (data !== null) ? '<span class="badge badge-success">' + moment(data, 'YYYY-MM-DD').format('DD.MM.YYYY') + '</span>' : '<span class="badge badge-danger">' + translations.no + '</span>';
                    }
                },
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        let invoiceReminderDropdown = '<div class="dropdown">' +
                          '  <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                          '    <i class="fa fa-crosshairs"></i>' +
                          '  </button>' +
                          '  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">' +
                          '    <a class="dropdown-item" href="/invoice/' + data + '/reminder/1" target="_blank">Mahnung 1</a>' +
                          '    <a class="dropdown-item" href="/invoice/' + data + '/reminder/2" target="_blank">Mahnung 2</a>' +
                          '  </div>' +
                          '</div>'

                        return '<div class="btn-group"><a href="/invoice/' + data + '/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>' +
                            '<a href="/invoice/' + data + '/delete" class="btn btn-danger btn-sm btn-delete"><i class="fa fa-remove"></i></a>' +
                            '<a href="/invoice/' + data + '/pdf" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i></a>' +
                            '<a href="/invoice/' + data + '/duplicate" class="btn btn-primary btn-sm"><i class="fa fa-files-o"></i></a>' +
                            '<a href="/invoice/' + data + '/set-paid" class="btn btn-success btn-sm"><i class="fa fa-money"></i></a>' +
                            invoiceReminderDropdown +
                            '</div>'
                        ;
                    },
                },
            ],
            "stateSave": true,
            "select": true,
            "order": [[1, "desc"]],
        });
    }
});
