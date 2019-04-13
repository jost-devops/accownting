let moment = require('moment');
let language = require('../de.json');

$(function() {
    if ($('body').hasClass('route__app_document_index')) {
        let documentTable = $('.document--index--table-documents').DataTable({
            "language": language,
            "paging": true,
            "info": true,
            "processing": true,
            "ajax": "/document/data",
            "columns": [
                {"data": "id"},
                {
                    "data": "date",
                    "render": function (data, type, row, meta) {
                        if (type === 'sort' || type === 'type') {
                            return data;
                        }

                        return moment(data, 'YYYY-MM-DD').format('DD.MM.YYYY');
                    },
                },
                {"data": "title"},
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        return '<div class="btn-group"><a target="_blank" href="/document/' + data + '/file/' + row.filename + '" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>' +
                            '<div class="btn-group"><a href="/document/' + data + '/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>' +
                            '<a href="/document/' + data + '/delete" class="btn btn-danger btn-sm btn-delete"><i class="fa fa-remove"></i></a></div>';
                    },
                },
            ],
            "stateSave": true,
            "select": true,
            "order": [[1, "desc"]],
        });
    }
});
