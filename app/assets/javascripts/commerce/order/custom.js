var custom = {
    init: function() {
        this.orderEntry();
        this.orderChronometer();
        this.orderTimer();
        this.togglePendingPanel();
        /*$('#switch-view').on('click', function() {
         $('.tab-content').find('.tab-pane').toggleClass('col-lg-3 visible-lg-block');
         });*/
        
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

    },
    togglePendingPanel : function(){
        
        $('#order-pending').on('click', function(){
            $(this).toggleClass('shown');
        });
    }
}