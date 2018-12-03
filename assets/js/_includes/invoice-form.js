$(function() {
    let $body = $('body');
    let $lineItemTable = $('.line-item-table');

    if ($body.hasClass('route__app_invoice_add')) {
        let updateInvoiceNumber = function() {
            let companyId = $('#invoice_company').val();

            $.ajax({
                type: 'get',
                url: '/company/' + companyId + '/next-numbers',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#invoice_invoiceNumber').val(response.numbers.nextInvoiceNumber);
                    }
                }
            });
        };
        updateInvoiceNumber();

        $('#invoice_company').change(function() {
            updateInvoiceNumber();
        });
    }

    if ($body.hasClass('route__app_invoice_add') || $body.hasClass('route__app_invoice_edit')) {

        $('.btn-add-line-item').click(function(e) {
            e.preventDefault();

            let newItemIndex = $lineItemTable.find('tbody tr').length;

            let prototype = $('<div>').append($lineItemTable.find('.prototype').clone()).html();
            let $prototype = $(prototype.replace(/__name__/g, 'new_' + newItemIndex));

            $prototype.removeClass('prototype').removeClass('d-none');
            $prototype.find('.line-item--index').html(newItemIndex);
            $prototype.appendTo($lineItemTable.find('tbody'));
        });

        $lineItemTable.on('click', '.btn-remove-line-item', function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();
        });

        let calculateTotals = function() {
            let $trs = $lineItemTable.find('tbody tr');

            let totalNet = 0;
            let totalTaxes = 0;
            let totalGross = 0;

            for (var i = 0; i < $trs.length; i++) {
                let $tr = $trs.eq(i);

                let amount = parseFloat($tr.find('.line-item--amount').val().replace(',', '.')) || 0;
                let priceSingle = parseFloat($tr.find('.line-item--price-single').val().replace(',', '.')) || 0;
                let vatRate = parseInt($tr.find('.line-item--vat-rate option:selected').data('rate')) || 0;

                let netPrice = (Math.round((amount * priceSingle * 100))  / 100);
                let taxes = (Math.round((amount * priceSingle * vatRate)) / 100);
                let grossPrice = (Math.round((amount * priceSingle * (100 + vatRate)))  / 100);

                totalNet += netPrice;
                totalTaxes += taxes;
                totalGross += grossPrice;

                $tr.find('.line-item--total-net-price').html(netPrice.toFixed(2) + ' €');
                $tr.find('.line-item--total-gross-price').html(grossPrice.toFixed(2) + ' €');
            }

            $lineItemTable.find('.total-net').html(totalNet.toFixed(2) + ' €');
            $lineItemTable.find('.total-taxes').html(totalTaxes.toFixed(2) + ' €');
            $lineItemTable.find('.total-gross').html(totalGross.toFixed(2) + ' €');
        };

        calculateTotals();

        $lineItemTable.on('keyup', '.line-item--amount,.line-item--price-single', function(e) {
            calculateTotals();
        });

        $('form[name=invoice]').submit(function(e) {
            $(this).find('.prototype').remove();
        });
    }
});
