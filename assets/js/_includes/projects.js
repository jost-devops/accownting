var language = require('../de.json');

$(function() {
    if ($('body').hasClass('route__app_project_index')) {
        let projectTable = $('.project--index--table-projects').DataTable({
            "language": language,
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
                    "render": function (data, type, row) {
                        let html = '';

                        if (row.hoursUsage !== null) {
                            let bgClass = 'bg-success';

                            if (row.hoursUsage > 80) {
                                bgClass = 'bg-warning';
                            }

                            if (row.hoursUsage > 100) {
                                bgClass = 'bg-danger';
                            }

                            html += '<div class="progress progress-xl mb-2"><div class="progress-bar ' + bgClass + '" role="progressbar" aria-valuenow="' + row.hoursUsage + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + row.hoursUsage + '%"></div></div>';
                        }

                        html += '<div>' + row.hoursSpentChargeable + ' (' + row.hoursSpent + ') / ' + ((row.hoursAvailable !== null) ? row.hoursAvailable : '*') + ' ' + translations['Hours spent'];

                        return html;
                    }
                },
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        return '<div class="btn-group"><a href="/time-tracking/by-project/' + data + '" class="btn btn-primary btn-sm"><i class="fa fa-list"></i></a>' +
                            '<a href="/project/' + data + '/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>' +
                            '<a href="/project/' + data + '/delete" class="btn btn-danger btn-sm btn-delete"><i class="fa fa-remove"></i></a></div>';
                    },
                },
            ],
            "stateSave": true,
        });
    }
});


