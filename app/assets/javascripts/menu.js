var menu = {
    init: function() {
        this.popover();
        this.productConfig();
        this.checkRules();
        this.confirmForm();
        main.popover();
    },
    popover: function() {

        $('body').on('hidden.bs.popover', function() {
            $('.popover:not(.in)').hide().detach();
        });

        $("#type-mask").popover({
            html: true,
            content: $('#type-list').html(),
            placement: 'bottom',
            trigger: 'manual'
        });

        $("#category-mask").popover({
            html: true,
            content: $('#category-list').html(),
            placement: 'bottom',
            trigger: 'manual'
        });
        $("#subcategory-mask").popover({
            html: true,
            content: $('#subcategory-list').html(),
            placement: 'bottom',
            trigger: 'manual'
        });

        $("#sort-mask").popover({
            html: true,
            content: $('#sort-list').html(),
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

    },
    productConfig: function() {
        $('body').on('click', '[data-attr^="attr-"]', function() {

            var attribute = $(this);

            var layoutAttribute = $('#order-paper .config-panel-layout').find('[data-attr="' + attribute.attr('data-attr') + '"]');

            layoutAttribute.prop('checked', attribute.prop('checked')).attr('checked', attribute.prop('checked'));


            menu.rules(attribute.closest('.config-panel'));
            menu.rules(layoutAttribute.closest('.config-panel'));
        });
    },
    checkRules: function() {

        $("#order-paper").on('shown.bs.popover', '.config-product', function() {

            var trigger = $(this);
            var popover = $('.popover');

            if (popover.length > 1) {

                $('body').one('hidden.bs.popover', function() {

                    popover = $('.popover');
                    var configPanel = popover.find('.config-panel');
                    var configPanelLayout = trigger.next().find('.config-panel');


                    menu.rules(configPanel);
                    menu.rules(configPanelLayout);
                });
            } else {

                var configPanel = popover.find('.config-panel');
                var configPanelLayout = trigger.next().find('.config-panel');

                menu.rules(configPanel);
                menu.rules(configPanelLayout);
            }

        });
    },
    rules: function(configPanel) {

        var selectedAttr = configPanel.find('.product-attr:checked');
        var unselectedAttr = configPanel.find('.product-attr:not(:checked)');
        var rules = configPanel.attr('data-rules').split(',');

        for (var i in rules) {

            if (rules[i].match(/max_limit/g)) {

                var max = parseInt(rules[i].match(/\(([^)]+)\)/)[1]);

                if (selectedAttr.length == max) {
                    $(unselectedAttr).attr('disabled', true);

                } else if (selectedAttr.length < max) {
                    $(unselectedAttr).attr('disabled', false);
                }

                configPanel.find('.rules-viewport').text('(Elija ' + (max - selectedAttr.length) + ')');
            }

        }

        return true;
    },
    confirmForm : function(){
        $('.custom-pay').on('change',function(){
            
            var radio = $(this);

            if(radio.is(':checked'))
                radio.closest('.radio-inline').find('.custom-amount').focus();
        });
        
        $('.custom-amount').on('focus',function(){
            
            var input = $(this);
            
            input.closest('.radio-inline').find('.custom-pay').prop('checked',true);
        });
    }
}