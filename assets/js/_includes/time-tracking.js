$(function() {
    if ($('body').hasClass('route__app_timetracking_index')) {
        $('.accownting-timeline').each(function(index, timeline) {
            $(timeline).find('.timeline--day').click(function(e) {
                e.preventDefault();

                document.location = '/time-tracking?date=' + $(this).data('date');
            });
        });
    }

    if ($('body').hasClass('route__app_timetracking_byproject')) {
        $table = $('.table-items');

        let checkButtonState = function() {
            $('.btn-clear-items').prop('disabled', $table.find('.item-checked:checked').length === 0)
        };

        $table.find('.check-all').click(function() {
            $table.find('.item-checked').prop('checked', $(this).is(':checked'));
            $table.find('.check-all').prop('checked', $(this).is(':checked'));
            checkButtonState();
        });

        $table.find('.item-checked').click(function() {
            checkButtonState();
        });

        $('.btn-clear-items').click(function(e) {
            e.preventDefault();

            let items = [];

            let $checked = $table.find('.item-checked:checked');

            for (let i = 0; i < $checked.length; i++) {
                items.push($checked.eq(i).closest('tr').data('item-id'));
            }

            $.ajax({
                type: 'post',
                url: '/time-tracking/clear',
                data: {
                    items: items,
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        document.location.reload();
                    } else {
                        alert('An error occurred.');
                    }
                }
            })
        });
    }
});
