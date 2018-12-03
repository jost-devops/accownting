$(function() {
    let $body = $('body');

    if ($body.hasClass('route__app_customer_add')) {
        let updateCustomerNumber = function() {
            let companyId = $('#customer_company').val();

            $.ajax({
                type: 'get',
                url: '/company/' + companyId + '/next-numbers',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#customer_customerNumber').val(response.numbers.nextCustomerNumber);
                    }
                }
            });
        };
        updateCustomerNumber();

        $('#customer_company').change(function() {
            updateCustomerNumber();
        });
    }
});
