/* Home Custom.JS */
var custom = {
    init: function() {
        main.popover();
        this.popover();
        this.parallax();
        this.affixTopBar();
    },
    affixTopBar: function() {

        $('#content-header').affix({
            offset: {
                top: function() {
                    return $('.parallax-group').height() - 54;
                }
            }
        });

        $('#order-paper').affix({
            offset: {
                top: function() {
                    return $('.parallax-group').height() - 54;
                }
            }
        });
        
        $('#content-header').on('affixed.bs.affix',function(){
            
            $('body').addClass('skin-red').removeClass('skin-landing');
            
        }).on('affixed-top.bs.affix',function(){
            
            $('body').addClass('skin-landing').removeClass('skin-red');
        });
    },
    parallax: function() {

        $('.cover-container').parallax("50%", -0.5);
    },
    popover: function() {

        $('body').on('hidden.bs.popover', function() {
            $('.popover:not(.in)').hide().detach();
        });

        $("#branch-mask").popover({
            html: true,
            content: $('#branch-list').html(),
            placement: 'bottom',
            trigger: 'manual'
        });

        $(".config-product").popover({
            html: true,
            content: function() {
                return $(this).next().html();
            },
            placement: 'right',
            container: 'body',
            trigger: 'manual'
        });
    }
}