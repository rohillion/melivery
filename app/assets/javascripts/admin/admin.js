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
            placement: 'bottom',
            container: 'body',
            trigger: 'manual'
        });

        /*$('.popover-trigger').click(function(e) {
            e.stopPropagation();
        });*/
    }
}