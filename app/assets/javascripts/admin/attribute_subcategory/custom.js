/* AttributeSubcategory Custom.JS */
var custom = {
    init: function () {
        this.popover();
        main.popover();
    },
    popover: function () {
        $(".popover-attributes").popover({
            html: true,
            content: function () {
                return $(this).next().html();
            },
            trigger: 'manual',
            placement:'left'
        });
    }
}