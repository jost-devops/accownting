$(function() {
    if ($('body').hasClass('route__app_invoice_index')) {
        let invoiceTable = $('.invoice--index--table-invoices').DataTable({
            "paging": true,
            "info": true,
            "processing": true,
            "ajax": "/invoice/data",
            "columns": [
                {"data": "invoiceNumber"},
                {"data": "invoiceDate"},
                {"data": "company"},
                {"data": "customer"},
                {"data": "subject"},
                {
                    "data": "totalNetPrice",
                    "render": function (data, type, row) {
                        return '<small>(Net)</small> ' + data + ' €<br><small>(Gross)</small> ' + row.totalGrossPrice + ' €';
                    },
                    'className': 'text-right',
                },
                {
                    "data": "paid",
                    "render": function (data, type, row) {
                        return (data !== null) ? '<span class="badge badge-success">' + data + '</span>' : '<span class="badge badge-danger">no</span>';
                    }
                },
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        return '<div class="btn-group"><a href="/invoice/' + data + '/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>' +
                            '<a href="/invoice/' + data + '/delete" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i></a>' +
                            '<a href="/invoice/' + data + '/set-paid" class="btn btn-primary btn-sm"><i class="fa fa-money"></i></a></div>';
                    },
                },
            ],
            "stateSave": true,
            "select": true,
        });
    }
});
