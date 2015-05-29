/* Menu Custom.JS */
var custom = {
    init: function () {
        menu.init();
    },
    popover:function(){
        $(".config-product").popover({
            html: true,
            content: function () {
                return $(this).next().html();
            },
            placement: 'right',
            container: 'body',
            trigger: 'manual'
        });
    }
}