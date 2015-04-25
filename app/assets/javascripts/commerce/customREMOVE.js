var commerce = {
    init: function() {
        this.tooltip();
        this.tabs();
        this.tag();
        this.onSuccess();
        this.orderEntry();
        this.orderChronometer();
        this.orderTimer();
        /*$('#switch-view').on('click', function() {
         $('.tab-content').find('.tab-pane').toggleClass('col-lg-3 visible-lg-block');
         });*/
        
    },
    tooltip: function() {

        $('[data-toggle="tooltip"]').tooltip();
    },
    tabs: function() {

        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href=#' + url.split('#')[1] + ']').tab('show');
        }

        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function(e) {
            window.location.hash = e.target.hash;
        })
    },
    tag: function() {

        $(".custom-tag-form").on("submit", function(event) {

            event.preventDefault();
            event.stopImmediatePropagation();


            var form = $(this), modal = form.closest('.modal');

            main.sendForm(form.attr('action'), form.serialize(), function(res) {

                if (res.status != 'error') {

                    modal.modal('hide');

                    modal.on('hidden.bs.modal', function(e) {
                        main.notify(res, function() {
                            location.reload();
                        });
                    });

                    return;
                }

                main.notify(res);
            });
        });
    },
    onSuccess: function() {

        var url = document.location.toString(), msg = $('#success-msg');

        if (url.match('#')) {

            var element = $('#' + url.split('#')[1]);

            main.scrollTo(element, function() {
                main.highlight(element);
            });

        }

        if (msg.val()) {
            main.notify({
                message: msg.val()
            });
        }

    },
    orderEntry: function() {

        var url = window.location.pathname.substr(1, window.location.pathname.length), section = url !== null ? url : 'dashboard';

        // Enable pusher logging - don't include this in production
        Pusher.log = function(message) {
            if (window.console && window.console.log) {
                window.console.log(message);
            }
        };

        var pusher = new Pusher('69f2f7efe77c2c0c9cae');
        var channel = pusher.subscribe(chnl);
        channel.bind('order', function(data) {

            if (section !== 'order') {

                main.notify({
                    message: 'Ha entrado un nuevo pedido!'
                });
            } else {

                location.reload();
            }
            //location.reload();
        });
    },
    orderTimer: function() {

        var progressOrder = $('.progress-order');

        var orderDateTime, orderDate, orderTime;

        for (var i = 0; i < progressOrder.length; i++) {

            orderDateTime = $(progressOrder[i]).find('.order-progress-time').val().split(" ");
            orderDate = orderDateTime[0].split("-");
            orderTime = orderDateTime[1].split(":");

            main.calculateRemainingTime(orderDate, orderTime, function(remainingDate, targetDate) {

                var clock = $(progressOrder[i]).find('.remaining-time');

                if (targetDate.getTime() > new Date().getTime() && (remainingDate.getMinutes() > 0 || remainingDate.getSeconds() > 0)) {

                    main.doTimer(remainingDate, function(elapsedDate) {

                        // update clock
                        clock.html(('0' + elapsedDate.getHours().toString()).slice(-2) + ':' + ('0' + elapsedDate.getMinutes().toString()).slice(-2) + ':' + ('0' + elapsedDate.getSeconds().toString()).slice(-2));
                    });
                } else {

                    clock.html('Tiempo agotado.');
                }
            });
        }

    },
    orderChronometer: function() {

        var entryOrder = $('.entry-order');

        var orderDateTime, orderDate, orderTime;

        for (var i = 0; i < entryOrder.length; i++) {

            orderDateTime = $(entryOrder[i]).find('.order-entry-time').val().split(" ");
            orderDate = orderDateTime[0].split("-");
            orderTime = orderDateTime[1].split(":");

            main.calculateElapsedTime(orderDate, orderTime, function(currentDate) {

                var clock = $(entryOrder[i]).find('.elapsed-time');

                main.doClock(currentDate, function(elapsedDate) {

                    // update clock
                    clock.html(('0' + elapsedDate.getHours().toString()).slice(-2) + ':' + ('0' + elapsedDate.getMinutes().toString()).slice(-2) + ':' + ('0' + elapsedDate.getSeconds().toString()).slice(-2));
                });
            });
        }

        //console.log(main.setTimer(entry, order_date, order_time));
        //order.timers[order_id] = main.setTimer(entry, order_date, order_time);
    }
}