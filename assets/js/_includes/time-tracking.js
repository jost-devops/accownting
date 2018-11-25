$(function() {
    if ($('body').hasClass('route__app_timetracking_index')) {
        let $filterForm = $('form[name=time_track_filter]');

        $filterForm.change(function(e) {
            $filterForm.submit();
        });
    }
});
