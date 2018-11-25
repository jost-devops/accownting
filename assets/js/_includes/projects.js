$(function() {
    if ($('body').hasClass('route__app_project_index')) {
        let projectTable = $('.project--index--table-projects').DataTable({
            "paging": true,
            "info": true,
            "processing": true,
            "ajax": "/project/data",
            "columns": [
                {"data": "id"},
                {"data": "name"},
                {
                    "data": "customer",
                    "render": function (data, type, row) {
                        return data + '<br>' + row.company;
                    },
                },
                {
                    "data": "budget",
                    "render": function (data, type, row) {
                        let lines = [];

                        lines.push(translations['Budget'] + ': ' + ((!data) ? translations['no'] : data + ' €'));
                        lines.push(translations['Price per Hour'] + ': ' + ((!row.pricePerHour) ? '-' : row.pricePerHour + ' €'));

                        return lines.join('<br>');
                    },
                },
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        return '<div class="btn-group"><a href="/project/' + data + '/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>' +
                            '<a href="/project/' + data + '/delete" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i></a></div>';
                    },
                },
            ],
            "stateSave": true,
            "select": true,
        });
    }
});
