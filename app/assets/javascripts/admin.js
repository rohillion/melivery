var admin = {
    init: function() {
        this.popover();
    },
    popover: function() {

        $('.popover-trigger').popover({
            html: true,
            content: function() {
                return $(this).next().html();
            },
            placement: 'bottom'
        });

        $('.popover-trigger').click(function(e) {
            e.stopPropagation();
        });

        $(document).on('click', function(e) {

            if ($('.popover').has(e.target).length == 0) {
                $('.popover-trigger').popover('hide');
            }
        });
    }
}

admin.init();