/* Order Custom.JS */
var custom = {
    init: function () {
        main.tooltip();
        main.popover();
        this.orderEntry();
        this.orderChronometer();
        this.orderTimer();
        this.togglePendingPanel();
        this.draggable();
        this.orderRemove();
        this.orderDonde();
        this.orderCanceled();
        this.popover();
        this.dispatchDealer();
        this.reportDealer();
        this.historyPanel();
        this.estimationControl();
    },
    estimationControl: function () {

        var estimatedTime, minutes, newtime, range = 30, pendingPanel = $('#order-pending'), progressPanel = $('#order-progress'), historyPanel = $('#order-history');

        $('.decreaseEstimated').on('click', function () {
            estimatedTime = $(this).closest('.btn-group-justified').find('.estimatedTime');
            if ($(estimatedTime[0]).val() > 10) {
                $.each(estimatedTime, function (i) {
                    minutes = parseInt($(estimatedTime[i]).val()) - range;
                    newtime = minutes > 60 ? Math.floor((minutes / 60)) + 'h:' + ((minutes % 60 === 0) ? '00' : minutes % 60) : minutes;
                    $(estimatedTime[i]).val(minutes).attr('title', newtime + ' minutes').text(newtime + 'm');//TODO. Lang
                });
            }
        });

        $('.increaseEstimated').on('click', function () {
            estimatedTime = $(this).closest('.btn-group-justified').find('.estimatedTime');
            if ($(estimatedTime[2]).val() < 120) {
                $.each(estimatedTime, function (i) {
                    minutes = parseInt($(estimatedTime[i]).val()) + range;
                    newtime = minutes > 60 ? Math.floor((minutes / 60)) + 'h:' + ((minutes % 60 === 0) ? '00' : minutes % 60) : minutes;
                    $(estimatedTime[i]).val(minutes).attr('title', newtime + ' minutes').text(newtime + 'm');//TODO. Lang
                });
            }
        });

        $('.estimatedTime').on('click', function () {
            main.sendFormPost($('#estimatedTimeAction').attr('action'), $.param({
                'estimated': $(this).val()
            }), function (res) {

                if (res.status) {

                    pendingPanel.find('[data-id="' + res.order.id + '"]').addClass('archive');

                    main.run('/order/' + res.order.id + '/card', function (res) {
                        if (res.status) {
                            progressPanel.prepend(res.orderCard);
                            setTimeout(function () {
                                progressPanel.find('.new-progress').removeClass('new-progress');
                                custom.draggable();
                                main.tooltip();
                            }, 200);
                        }
                    });
                }

                main.notify(res);
            });
        });

        $('.rejectOrder').on('click', function () {
            
            var orderId = this.dataset.orderid;

            custom.archiveOrder(orderId, 5, function () {

                main.run('/order/' + orderId + '/card?history=1', function (res) {
                    if (res.status) {
                        historyPanel.prepend(res.orderCard);
                        setTimeout(function () {
                            historyPanel.find('.new-history').removeClass('new-history');
                        }, 200);
                    }
                });

            }, this.dataset.motiveid);
            
            return false;
        });
    },
    orderEntry: function () {

        var url = window.location.pathname.substr(1, window.location.pathname.length), section = url !== null ? url : 'dashboard';
        // Enable pusher logging - don't include this in production
        /*Pusher.log = function(message) {
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
         });TODO. Pusher*/
    },
    orderTimer: function () {

        var progressTimer = $('.order-progress-time');
        var orderDateTime, orderDate, orderTime;
        for (var i = 0; i < progressTimer.length; i++) {

            orderDateTime = $(progressTimer[i]).find('input').val().split(" ");
            orderDate = orderDateTime[0].split("-");
            orderTime = orderDateTime[1].split(":");
            main.calculateRemainingTime(orderDate, orderTime, function (remainingDate, targetDate) {

                var clock = $(progressTimer[i]).find('.remaining-time');
                if (targetDate.getTime() > new Date().getTime() && (remainingDate.getMinutes() > 0 || remainingDate.getSeconds() > 0)) {

                    main.doTimer(remainingDate, function (elapsedDate) {

                        // update clock
                        clock.html(('0' + elapsedDate.getHours().toString()).slice(-2) + ':' + ('0' + elapsedDate.getMinutes().toString()).slice(-2) + ':' + ('0' + elapsedDate.getSeconds().toString()).slice(-2));
                    });
                } else {

                    clock.html('Tiempo agotado.');
                }
            });
        }

    },
    orderChronometer: function () {

        var entryOrder = $('.entry-order');
        var orderDateTime, orderDate, orderTime;
        for (var i = 0; i < entryOrder.length; i++) {

            orderDateTime = $(entryOrder[i]).find('.order-entry-time').val().split(" ");
            orderDate = orderDateTime[0].split("-");
            orderTime = orderDateTime[1].split(":");
            main.calculateElapsedTime(orderDate, orderTime, function (currentDate) {

                var clock = $(entryOrder[i]).find('.elapsed-time');
                main.doClock(currentDate, function (elapsedDate) {

                    // update clock
                    clock.html(('0' + elapsedDate.getHours().toString()).slice(-2) + ':' + ('0' + elapsedDate.getMinutes().toString()).slice(-2) + ':' + ('0' + elapsedDate.getSeconds().toString()).slice(-2));
                });
            });
        }

    },
    togglePendingPanel: function () {

        $('#togglePendingPanel').on('click', function () {
            $('#order-pending-fixed').toggleClass('shown');
        });
    },
    draggable: function () {

        var remove = false;
        var dragCursor;
        var curBrowser = navigator.userAgent.indexOf('Firefox');
        dragCursor = (curBrowser === -1) ? "-webkit-grabbing" : "-moz-grabbing";
        if (storage.data.isSet('assignments'))
            storage.data.remove('assignments');
        storage.data.set('assignments', []);
        var dealerPanel = $('#dealer-panel'),
                dealerBox = dealerPanel.find(".box-body"),
                helpers = dealerBox.parent().find('.order-helper');
        $.each(helpers, function (i) {
            storage.push('assignments', $(helpers[i]).attr('data-id'));
        });
        $(".progress-order").draggable({
            connectToSortable: "#dealer-panel .box-body",
            handle: ".grab-order",
            helper: function (e) {
                return '<div class="order-helper" data-id="' + e.currentTarget.dataset.id + '" data-delivery="' + e.currentTarget.dataset.delivery + '" data-client="' + e.currentTarget.dataset.client + '" data-paycash="' + e.currentTarget.dataset.paycash + '"><i class="fa fa-paperclip"></i><div class="box box-solid order-helper-client"><div class="client-name">' + e.currentTarget.dataset.client + ' </div><div class="order-change">Cambio <strong>$' + e.currentTarget.dataset.change + '</strong></div></div></div>';
            },
            cursor: dragCursor,
            cursorAt: {left: 50, top: 30},
            appendTo: 'body',
            start: function () {
                dealerBox.addClass('bg-over');
            },
            stop: function () {
                dealerBox.removeClass('bg-over');
            }
        });
        dealerBox.sortable({
            cursor: dragCursor,
            connectWith: "#dealer-panel .box-body",
            receive: function (e, ui) {

                if (!remove) {

                    var currentBox = $(this),
                            dealerId = currentBox.parent().attr('data-dealer-id');
                    if ($(ui.sender).is('.progress-order')) {//Si viene de una orden

                        var helper = $(ui.helper),
                                orderId = helper.attr('data-id');
                        helper.removeClass('ui-draggable-dragging')
                                .attr('style', '');
                        if (helper.data('delivery')) {

                            if (storage.data.get('assignments').indexOf(orderId) === -1) {

                                custom.attachDealerOrder(orderId, dealerId);
                            }
                            else {

                                helper.remove();
                                helper.promise().done(function () {
                                    dealerPanel.find('[data-id="' + orderId + '"]').effect("shake", {
                                        direction: "up",
                                        times: 6,
                                        distance: 5
                                    }, "slow");
                                });
                            }
                        } else {

                            $('#changeOrderType_success').on('click', function () {
                                custom.changeOrderType(orderId, function (res) {

                                    $('#order-panel').find('[data-id="' + res.order.id + '"]').attr('data-paycash', res.order.paycash).attr('data-change', res.order.change).attr('data-delivery', res.order.delivery);
                                    helper.find('.order-change strong').text('$' + res.order.change);
                                    helper.attr('data-paycash', res.order.paycash);

                                    custom.toggleDealerActionButtons();
                                    custom.attachDealerOrder(orderId, dealerId);

                                    $('#changeOrderType_cancel').off();
                                    $('#changeOrderTypeModal').modal('hide');
                                });
                            });

                            $('#changeOrderType_cancel').on('click', function () {
                                $('#dealer-panel').find('[data-id="' + orderId + '"]').remove();
                                custom.toggleDealerActionButtons();
                                $('#changeOrderType_success').off();
                            });

                            $('#changeOrderTypeModal').modal('show');
                        }

                    } else {//Si viene de sortable

                        var item = $(ui.item),
                                orderId = item.attr('data-id');
                        custom.attachDealerOrder(orderId, dealerId);
                    }

                }

            },
            beforeStop: function (event, ui) {

                var itemWidth = $(ui.item).width(),
                        itemHeight = $(ui.item).height(),
                        dealerPanelWidth = dealerPanel.width(),
                        dealerPanelHeight = dealerPanel.height();
                var horizontal = ui.offset.left + itemWidth > dealerPanel.offset().left && ui.offset.left < dealerPanel.offset().left + dealerPanelWidth;
                var vertical = ui.offset.top + itemHeight > dealerPanel.offset().top && ui.offset.top < dealerPanel.offset().top + dealerPanelHeight;
                if (!(horizontal && vertical)) {

                    remove = true;
                    var orderId = $(ui.item).attr('data-id');
                    custom.detachDealerOrder(orderId, function () {

                        remove = false;
                    });
                }

            },
            start: function () {

                dealerBox.addClass('bg-over');
            },
            stop: function () {

                dealerBox.removeClass('bg-over');
                custom.toggleDealerActionButtons();
            }
        });
    },
    changeOrderType: function (orderId, callback) {

        main.sendForm('/order/' + orderId + '/type', $.param({
            'paycash': $('#changeOrderType_paycash').val()
        }), function (res) {

            if (res.status) {
                if (typeof callback === 'function')
                    callback(res);
            } else {

                main.notify(res);
            }
        });
    },
    archiveOrder: function (orderId, statusId, callback, motiveId) {

        var params = typeof motiveId != 'undefined' ? $.param({'motive': motiveId}) : $.param({});

        main.sendForm('/order/' + orderId + '/status/' + statusId, params, function (res) {

            if (res.status) {

                $('#order-panel').find('[data-id="' + orderId + '"]').addClass('archive');
                if (typeof callback === 'function')
                    callback(res);
            } else {

                main.notify(res);
            }
        });
    },
    orderRemove: function () {

        $(document).on("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", '.progress-order, .pending-order', function (e) {
            if ($(e.target).is('.archive'))
                $(this).remove();

            if (!$('#order-pending-fixed').find('.pending-order').length > 0)
                $('#order-pending-fixed').removeClass('shown');
        });
    },
    orderDonde: function () {

        $(document).on('click', '.done-order', function (e) {

            e.preventDefault();
            var orderId = this.dataset.order;
            custom.archiveOrder(orderId, 4, function () {

                custom.detachDealerOrder(orderId);
            });
        });
    },
    orderCanceled: function () {

        var rejectModal = $('#reject-motive');
        $('.cancel-order-id').on('click', function () {
            rejectModal.find('.cancel-order').attr('data-order', this.dataset.order);
        });
        $('.cancel-order').on('click', function (e) {

            e.preventDefault();
            rejectModal.modal('hide');
            var orderId = this.dataset.order;
            custom.archiveOrder(orderId, 5, function () {

                custom.detachDealerOrder(orderId);
            }, rejectModal.find('[name="motive"]').val());
        });
    },
    popover: function () {
        $(".client-name").popover({
            html: true,
            content: function () {
                return $(this).next().html();
            },
            trigger: 'manual'
        });
    },
    dispatchDealer: function () {

        $('.dispatch').on('click', function () {

            var trigger = $(this);
            main.run('/order/' + this.dataset.dealer + '/dispatch', function (res) {

                trigger.addClass('hidden').next().removeClass('hidden');
                main.notify(res);
            });
        });
    },
    reportDealer: function () {

        $('.report').on('click', function () {

            var trigger = $(this),
                    dealerId = this.dataset.dealer;
            main.run('/order/' + dealerId + '/report', function (res) {

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
    cleanDealer: function (dealerId) {

        var box = $('#dealer-panel').find('[data-dealer-id="' + dealerId + '"] .box-body');
        var orders = box.find('.order-helper');
        $.grep(orders, function (order) {
            storage.removeElement('assignments', order.dataset.id);
        });
        box.empty();
        custom.toggleDealerActionButtons();
    },
    attachDealerOrder: function (orderId, dealerId) {

        main.run('/order/' + orderId + '/dealer/' + dealerId, function (res) {

            if (res.status) {

                storage.push('assignments', orderId);
            } else {

                main.notify(res);
            }
        });
    },
    detachDealerOrder: function (orderId, callback) {

        var dealerOrder = $('#dealer-panel').find('[data-id="' + orderId + '"]');
        if (dealerOrder.length > 0) {

            dealerOrder.remove();
            custom.toggleDealerActionButtons();
            main.run('/order/' + orderId + '/dealer/remove', function (res) {

                if (res.status) {

                    storage.removeElement('assignments', orderId);
                    if (typeof callback === 'function')
                        callback(res);
                } else {

                    main.notify(res);
                }

            });
        }

    },
    toggleDealerActionButtons: function () {

        var box = $('#dealer-panel .box-body');
        $.each(box, function () {

            var current = $(this).parent();
            if (main.isEmpty($(this))) {

                current.find('.dispatch').addClass('hidden');
                if (!current.find('.report').hasClass('hidden')) {
                    main.run('/order/' + current.attr('data-dealer-id') + '/undispatch');
                    current.find('.report').addClass('hidden');
                }

                current.find('.dealer-total span').text('$0');
            } else {

                if (current.find('.report').hasClass('hidden'))
                    current.find('.dispatch').removeClass('hidden');
                var orders = current.find('.order-helper');
                var total = 0;
                $.each(orders, function (index, item) {
                    total = (parseFloat(total) + parseFloat(item.dataset.paycash));
                });
                current.find('.dealer-total span').text('$' + total);
            }
        });
    },
    historyPanel: function () {

        var history = $('#order-history-fixed'),
                orders = $('#order-panel');
        $('#toggleHistory').on('click', function () {

            if (history.hasClass('shown')) {
                history.one('transitionend MSTransitionEnd', function () {
                    history.css('position', 'fixed');
                    orders.removeClass('col-sm-6').addClass('col-sm-8');
                });
                history.removeClass('shown');
            } else {
                orders.removeClass('col-sm-8').addClass('col-sm-6');
                history.css('position', 'inherit').addClass('shown');
            }

        });
    }
}