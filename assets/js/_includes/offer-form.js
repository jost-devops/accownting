$(function() {
    let $body = $('body');
    let $itemTable = $('.item-table');

    if ($body.hasClass('route__app_offer_add') || $body.hasClass('route__app_offer_edit')) {

        let updateOfferNumber = function() {
            let companyId = $('#offer_company').val();

            $.ajax({
                type: 'get',
                url: '/company/' + companyId + '/next-numbers',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#offer_offerNumber').val(response.numbers.nextOfferNumber);
                    }
                }
            });
        };
        updateOfferNumber();

        $('#offer_company').change(function() {
            updateOfferNumber();
        });

        $('.btn-add-item').click(function(e) {
            e.preventDefault();

            let newItemIndex = $itemTable.find('tbody tr').length;

            let prototype = $('<div>').append($itemTable.find('.prototype').clone()).html();
            let $prototype = $(prototype.replace(/__name__/g, 'new_' + newItemIndex));

            $prototype.removeClass('prototype').removeClass('d-none');
            $prototype.find('.item--index').html(newItemIndex);
            $prototype.appendTo($itemTable.find('tbody'));
        });

        $itemTable.on('click', '.btn-remove-item', function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();
        });

        let calculateTotals = function() {
            let $trs = $itemTable.find('tbody tr');

            let totalNet = 0;
            let totalTaxes = 0;
            let totalGross = 0;

            for (var i = 0; i < $trs.length; i++) {
                let $tr = $trs.eq(i);

                let amount = parseFloat($tr.find('.item--amount').val().replace(',', '.')) || 0;
                let priceSingle = parseFloat($tr.find('.item--price-single').val().replace(',', '.')) || 0;
                let vatRate = parseInt($tr.find('.item--vat-rate option:selected').data('rate')) || 0;

                let netPrice = (Math.round((amount * priceSingle * 100))  / 100);
                let taxes = (Math.round((amount * priceSingle * vatRate)) / 100);
                let grossPrice = (Math.round((amount * priceSingle * (100 + vatRate)))  / 100);

                totalNet += netPrice;
                totalTaxes += taxes;
                totalGross += grossPrice;

                $tr.find('.item--total-net-price').html(netPrice.toFixed(2) + ' €');
                $tr.find('.item--total-gross-price').html(grossPrice.toFixed(2) + ' €');
            }

            $itemTable.find('.total-net').html(totalNet.toFixed(2) + ' €');
            $itemTable.find('.total-taxes').html(totalTaxes.toFixed(2) + ' €');
            $itemTable.find('.total-gross').html(totalGross.toFixed(2) + ' €');
        };

        calculateTotals();

        $itemTable.on('keyup', '.item--amount,.item--price-single', function(e) {
            calculateTotals();
        });

        $('form[name=offer]').submit(function(e) {
            $(this).find('.prototype').remove();
        });
    }
});
