var menu = {
    init: function () {
        this.basket();
        this.popover();
        this.productConfig();
        this.checkRules();
        main.popover();
    },
    basket: function () {

        $('.addBasket').on('click', function () {

            if (custom.adding)
                return false;

            custom.adding = true;

            var priceid = this.dataset.priceid,
                    productid = this.dataset.productid,
                    btn = $(this);

            main.sendFormPost(btn.closest('form').attr('action'), {
                'productid': productid,
                'priceid': priceid
            }, function (res) {

                menu.refreshBasket(res.basket, function () {
                    custom.adding = false;
                    //menu.popover();
                    custom.popover();
                });

                if (res.message.length > 0)
                    main.notify(res);
            });

            return false;
        });

        $(document).on('click', '.removeBasket', function () {

            var btn = $(this);

            main.run(btn.attr('href'), function (res) {
                menu.refreshBasket(res.basket, function () {
                    //menu.popover();
                    custom.popover();
                });
            });

            return false;
        });

        $(document).on('change', '.qty', function () {

            var url = this.dataset.action,
                    qty = $(this);

            main.sendFormPost(url, {
                'qty': qty.val()
            }, function (res) {
                menu.refreshBasket(res.basket, function () {
                    //menu.popover();
                    custom.popover();
                });
            });

        });

        $(document).on('click', '.aplyAttr', function () {

            var form = $('#' + $(this).attr('form')),
                    configPopover = $('.order-body').find('.config-product[aria-describedby]');

            main.sendFormPost(form.attr('action'), form.serializeArray(), function (res) {

                configPopover.on('hidden.bs.popover', function () {
                    menu.refreshBasket(res.basket, function () {
                        //menu.popover();
                        custom.popover();
                    });
                });

                configPopover.popover('hide');
            });
        });
        
        var path = location.pathname.match('/preorder/confirm') ? '?confirm=1' : '';

        main.run('/preorder/show' + path, function (res) {
            menu.refreshBasket(res.basket, function () {
                custom.popover();
            });
        });
    },
    refreshBasket: function (basket, callback) {
        $('#order-paper .box').empty().append(basket);
        callback();
    },
    popover: function () {

        $("#branch-mask").popover({
            html: true,
            content: $('#branch-list').html(),
            placement: 'bottom',
            trigger: 'manual'
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

    },
    productConfig: function () {
        $('body').on('click', '[data-attr^="attr-"]', function () {

            var attribute = $(this);

            var layoutAttribute = $('#order-paper .config-panel-layout').find('[data-attr="' + attribute.attr('data-attr') + '"]');

            layoutAttribute.prop('checked', attribute.prop('checked')).attr('checked', attribute.prop('checked'));


            menu.rules(attribute.closest('.config-panel'));
            menu.rules(layoutAttribute.closest('.config-panel'));
        });
    },
    checkRules: function () {

        $("#order-paper").on('shown.bs.popover', '.config-product', function () {

            var trigger = $(this);
            var popover = $('.popover');

            if (popover.length > 1) {

                $('body').one('hidden.bs.popover', function () {

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
    rules: function (configPanel) {

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
    }
}