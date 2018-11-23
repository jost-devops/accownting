$(function() {
    if ($('body').hasClass('route__app_company_index')) {
        let companyTable = $('.company--index--table-companies').DataTable({
            "paging": true,
            "info": true,
            "processing": true,
            "ajax": "/company/data",
            "columns": [
                {"data": "id"},
                {"data": "name"},
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        return '<div class="btn-group"><a href="/company/' + data + '/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>' +
                            '<a href="/company/' + data + '/delete" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i></a></div>';
                    },
                },
            ],
            "stateSave": true,
            "select": true,
        });
    }
});
