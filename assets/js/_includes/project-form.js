$(function() {
    let $body = $('body');

    if ($body.hasClass('route__app_project_add')) {
        let updateProjectNumber = () => {
            let companyId = $('#project_company').val();

            $.ajax({
                type: 'get',
                url: '/company/' + companyId + '/next-numbers',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#project_projectNumber').val(response.numbers.nextProjectNumber);
                    }
                }
            });
        };

        updateProjectNumber();

        $('#project_company').change(function() {
            updateProjectNumber();
        });
    }
});
