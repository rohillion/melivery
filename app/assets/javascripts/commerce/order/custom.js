/* Order Custom.JS */
var custom = {
    init: function() {
        this.orderEntry();
        this.orderChronometer();
        this.orderTimer();
        this.togglePendingPanel();
        this.draggable();
        this.archiveOrder();
        main.tooltip();
        main.popover();
        this.popover();
        this.dispatchDealer();
        this.reportDealer();
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

        var progressTimer = $('.order-progress-time');
        var orderDateTime, orderDate, orderTime;

        for (var i = 0; i < progressTimer.length; i++) {

            orderDateTime = $(progressTimer[i]).find('input').val().split(" ");
            orderDate = orderDateTime[0].split("-");
            orderTime = orderDateTime[1].split(":");

            main.calculateRemainingTime(orderDate, orderTime, function(remainingDate, targetDate) {

                var clock = $(progressTimer[i]).find('.remaining-time');

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
    togglePendingPanel: function() {

        $('#order-pending-fixed').on('click', function() {
            $(this).toggleClass('shown');
        });
    },
    draggable: function() {

        var dragCursor;
        var curBrowser = navigator.userAgent.indexOf('Firefox');

        dragCursor = (curBrowser === -1) ? "-webkit-grabbing" : "-moz-grabbing";

        if (storage.data.isSet('assignments'))
            storage.data.remove('assignments');

        storage.data.set('assignments', []);

        var dealerPanel = $('#dealer-panel'),
                dealerBox = dealerPanel.find(".box-body"),
                helpers = dealerBox.parent().find('.order-helper');

        $.each(helpers, function(i) {
            storage.push('assignments', $(helpers[i]).attr('data-id'));
        });

        $(".progress-order").draggable({
            connectToSortable: "#dealer-panel .box-body",
            handle: ".grab-order",
            helper: function(e) {
                return '<div class="order-helper" data-id="' + e.currentTarget.dataset.id + '" data-client="' + e.currentTarget.dataset.client + '"><i class="fa fa-paperclip"></i><div class="box box-solid client-helper-name">' + e.currentTarget.dataset.client + '</div></div>';
            },
            cursor: dragCursor,
            cursorAt: {left: 50, top: 30},
            appendTo: 'body',
            start: function() {
                dealerBox.addClass('bg-warning');
            },
            stop: function() {
                dealerBox.removeClass('bg-warning');
            }
        });

        dealerBox.sortable({
            cursor: dragCursor,
            connectWith: "#dealer-panel .box-body",
            receive: function(e, ui) {

                var currentBox = $(this),
                        dealerId = currentBox.parent().attr('data-dealer-id');

                if ($(ui.sender).is('.progress-order')) {//Si viene de una orden

                    var helper = $(ui.helper),
                            orderId = helper.attr('data-id');

                    helper.removeClass('ui-draggable-dragging')
                            .attr('style', '');

                    if (storage.data.get('assignments').indexOf(orderId) === -1) {

                        main.run('/order/' + orderId + '/dealer/' + dealerId, function(res) {

                            if (res.status) {

                                storage.push('assignments', orderId);
                            } else {

                                main.notify(res);
                            }

                        });

                    } else {

                        helper.remove();

                        helper.promise().done(function() {
                            dealerPanel.find('[data-id="' + orderId + '"]').effect("shake", {
                                direction: "up",
                                times: 6,
                                distance: 5
                            }, "slow");
                        });


                    }

                } else {//Si viene de sortable

                    var item = $(ui.item),
                            orderId = item.attr('data-id');

                    main.run('/order/' + orderId + '/dealer/' + dealerId, function(res) {

                        if (res.status) {

                            storage.push('assignments', orderId);
                        } else {

                            main.notify(res);
                        }

                    });
                }

            },
            beforeStop: function(event, ui) {

                var itemWidth = $(ui.item).width(),
                        itemHeight = $(ui.item).height(),
                        dealerPanelWidth = dealerPanel.width(),
                        dealerPanelHeight = dealerPanel.height();

                var horizontal = ui.offset.left + itemWidth > dealerPanel.offset().left && ui.offset.left < dealerPanel.offset().left + dealerPanelWidth;
                var vertical = ui.offset.top + itemHeight > dealerPanel.offset().top && ui.offset.top < dealerPanel.offset().top + dealerPanelHeight;

                if (!(horizontal && vertical)) {

                    var item = $(ui.item),
                            orderId = item.attr('data-id');

                    ui.item.remove();

                    main.run('/order/' + orderId + '/dealer/remove', function(res) {

                        if (res.status) {

                            storage.removeElement('assignments', orderId);
                        } else {

                            main.notify(res);
                        }

                    });
                }

            },
            start: function() {

                dealerBox.addClass('bg-warning');
            },
            stop: function() {

                dealerBox.removeClass('bg-warning');

                var box = $('#dealer-panel .box-body');

                $.each(box, function() {
                    if (main.isEmpty($(this))) {
                        $(this).parent().find('.dispatch').addClass('hidden');
                    } else {
                        $(this).parent().find('.dispatch').removeClass('hidden');
                    }
                });

            }
        });
    },
    archiveOrder: function() {

        var rejectModal = $('#reject-motive');

        $('.cancel-order-id').on('click', function() {
            rejectModal.find('.cancel-order').attr('data-order', this.dataset.order)
        });

        $('.cancel-order,.done-order').on('click', function(e) {

            $('.progress-order').on("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function() {
                $(this).remove();
            });

            e.preventDefault();

            rejectModal.modal('hide');

            var orderId = this.dataset.order;

            main.sendForm('/order/' + orderId + '/status/' + this.dataset.status, $.param({
                'motive': $('#reject-form [name="motive"]').val()
            }), function(res) {

                if (res.status) {

                    $('#order-panel').find('[data-id="' + orderId + '"]').addClass('archive');
                } else {

                    main.notify(res);
                }

            });

        });

    },
    popover: function() {
        $(".client-name").popover({
            html: true,
            content: function() {
                return $(this).next().html();
            },
            trigger: 'manual'
        });
    },
    dispatchDealer: function() {

        $('.dispatch').on('click', function() {

            var trigger = $(this);

            main.run('/order/' + this.dataset.dealer + '/dispatch', function(res) {

                trigger.addClass('hidden').next().removeClass('hidden');

                main.notify(res);
            });

        });
    },
    reportDealer: function() {

        $('.report').on('click', function() {

            $('.progress-order').one("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function() {
                $(this).remove();
            });

            var trigger = $(this),
                    dealerId = this.dataset.dealer;

            main.run('/order/' + dealerId + '/report', function(res) {

                if (res.status) {

                    trigger.addClass('hidden');

                    custom.cleanDealer(dealerId);

                    for (var i in res.orders) {
                        $('#order-panel').find('[data-id="' + res.orders[i].id + '"]').addClass('archive');
                    }
                }

                main.notify(res);
            });

        });
    },
    cleanDealer: function(dealerId) {
        $('#dealer-panel').find('[data-dealer-id="'+dealerId+'"] .box-body').empty();
    }
}