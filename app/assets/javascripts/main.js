var main = {
    init: function () {
        //this.getChannel();
        this.onSuccess();
        storage.init();
    },
    onSuccess: function () {
        var msg = $('#success-msg');

        if (msg.val()) {
            main.notify({
                message: msg.val()
            });
        }
    },
    sendForm: function (target, formData, callback) {

        $.ajax({
            url: target + "?" + formData,
            dataType: "json"
        }).done(function (res) {

            if (typeof callback == 'function')
                callback(res);
        });
    },
    sendFormPost: function (target, formData, callback) {
        $.ajax({
            type: 'post',
            url: target,
            data: formData,
            dataType: "json"
        }).done(function (res) {

            if (typeof callback == 'function')
                callback(res);
        });
    },
    run: function (target, callback) {

        $.ajax({
            url: target,
            dataType: "json",
            cache: false
        }).done(function (res) {

            if (typeof callback == 'function')
                callback(res);
        });
    },
    post: function (target, callback) {

        $.ajax({
            type: 'post',
            url: target,
            dataType: "json"
        }).done(function (res) {

            if (typeof callback == 'function')
                callback(res);
        });
    },
    notify: function (res, callback) {

        var msg = new Array;

        if (typeof res.message == 'string') {

            msg.push(res.message);
        } else if (typeof res.message == 'object') {

            for (var x in res.message) {
                msg.push(res.message[x]);
            }

        }

        // create the notification
        var notification = new NotificationFx({
            // element to which the notification will be appended
            // defaults to the document.body
            wrapper: document.body,
            // the message
            message: '<p>' + msg[0] + '</p>',
            // layout type: growl|attached|bar|other
            layout: 'growl',
            // effects for the specified layout:
            // for growl layout: scale|slide|genie|jelly
            // for attached layout: flip|bouncyflip
            // for other layout: boxspinner|cornerexpand|loadingcircle|thumbslider
            effect: 'jelly',
            // if the user doesn´t close the notification then we remove it 
            // after the following time
            type: res.status, // notice, warning, error or success

            // time to remove notification if user don't do it
            ttl: res.time ? res.time : 3500,
            // callbacks
            onClose: function () {
                if (typeof callback == 'function')
                    callback();
            }
        });

        // show the notification
        notification.show();
    },
    highlight: function (element) {

        element.effect("highlight", {
            color: "#ffff99"
        }, 2000);
    },
    scrollTo: function (element, offsetTopFix, callback) {

        if (location.pathname.replace(/^\//, '') == element.pathname.replace(/^\//, '') && location.hostname == element.hostname) {

            var target = $(element.hash);
            target = target.length ? target : $('[name=' + element.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top - offsetTopFix
                }, 1000, 'swing', callback);
                return false;
            }
        }

    },
    calculateElapsedTime: function (date, time, callback) {

        // set the date we're counting down to
        var targetDate = new Date(date[0], date[1] - 1, date[2], time[0], time[1], time[2]);

        // find the amount of "seconds" between now and target
        var currentDate = new Date();
        var secondsLeft = (currentDate.getTime() - targetDate.getTime()) / 1000;

        // do some time calculations
        currentDate.setDate(parseInt(secondsLeft / 86400));
        secondsLeft = secondsLeft % 86400;

        currentDate.setHours(parseInt(secondsLeft / 3600));
        secondsLeft = secondsLeft % 3600;

        currentDate.setMinutes(parseInt(secondsLeft / 60));
        currentDate.setSeconds(parseInt(secondsLeft % 60));

        callback(currentDate);
    },
    calculateRemainingTime: function (date, time, callback) {

        // set the date we're counting down to
        var targetDate = new Date(date[0], date[1] - 1, date[2], time[0], time[1], time[2]);

        // find the amount of "seconds" between now and target
        var currentDate = new Date();
        var secondsLeft = (targetDate.getTime() - currentDate.getTime()) / 1000;

        // do some time calculations
        currentDate.setDate(parseInt(secondsLeft / 86400));
        secondsLeft = secondsLeft % 86400;

        currentDate.setHours(parseInt(secondsLeft / 3600));
        secondsLeft = secondsLeft % 3600;

        currentDate.setMinutes(parseInt(secondsLeft / 60));
        currentDate.setSeconds(parseInt(secondsLeft % 60));

        callback(currentDate, targetDate);
    },
    doClock: function (elapsedDate, callback) {

        var orderSeconds = elapsedDate.getSeconds() * 1000,
                currentDate = new Date(),
                elapsedSeconds = elapsedDate.getSeconds() * 1000,
                diff = 0,
                increment = '0.0';

        function instance()
        {

            if (elapsedSeconds >= 60000) {
                orderSeconds = orderSeconds - 60000;
                elapsedSeconds = 0;
            }

            elapsedSeconds += diff > 100 ? diff : 100;

            increment = Math.floor(elapsedSeconds / 100) / 10;

            if (Math.round(increment) == increment)
                increment += '.0';


            elapsedDate.setSeconds(parseInt(increment));

            callback(elapsedDate);

            diff = (new Date().getTime() - currentDate.getTime()) - (elapsedSeconds - orderSeconds);

            window.setTimeout(instance, (100 - diff));

        }

        window.setTimeout(instance, 100);

    },
    doTimer: function (elapsedDate, callback) {

        var orderSeconds = elapsedDate.getSeconds() * 1000,
                currentDate = new Date(),
                remainingSeconds = elapsedDate.getSeconds() * 1000,
                diff = 0,
                decrement = '0.0';

        function instance()
        {

            if (remainingSeconds <= 100) {
                elapsedDate.setMinutes(elapsedDate.getMinutes() - 1);
                orderSeconds = orderSeconds + 60000;
                remainingSeconds = 60000;
            }

            remainingSeconds -= diff > 100 ? diff : 100;

            decrement = Math.floor(remainingSeconds / 100) / 10;

            if (Math.round(decrement) == decrement)
                decrement += '.0';

            elapsedDate.setSeconds(parseInt(decrement));

            callback(elapsedDate);

            diff = (new Date().getTime() - currentDate.getTime()) + (remainingSeconds - orderSeconds);

            if (elapsedDate.getMinutes() > 0 || elapsedDate.getSeconds() > 0)
                window.setTimeout(instance, (100 - diff));

        }

        window.setTimeout(instance, 100);

    },
    substringMatcher: function (strs) {

        return function findMatches(q, cb) {
            var matches, substrRegex;

            // an array that will be populated with substring matches
            matches = [];

            // regex used to determine if a string contains the substring `q`
            substrRegex = new RegExp(q, 'i');

            // iterate through the pool of strings and for any string that
            // contains the substring `q`, add it to the `matches` array
            $.each(strs, function (i, str) {
                if (substrRegex.test(str)) {
                    // the typeahead jQuery plugin expects suggestions to a
                    // JavaScript object, refer to typeahead docs for more info
                    matches.push({value: str});
                }
            });

            cb(matches);
        };
    },
    popover: function () {

        $('body').on('hidden.bs.popover', function () {
            $('.popover:not(.in)').hide().detach();
        });

        var popoverOld = false;

        $(document).on('mousedown', 'html', function (e) {

            if ($('.popover').has(e.target).length === 0) {

                var popoverNew = $(e.target).closest('.popover-trigger');

                if ($(popoverNew).length === 0) {

                    $(popoverOld).popover('hide');
                    popoverOld = false;
                } else {

                    if (!popoverOld) {

                        popoverNew.popover('show');
                        popoverOld = popoverNew;

                    } else {

                        if ($(popoverOld).is(popoverNew)) {

                            $(popoverOld).one('hidden.bs.popover', function () {

                                popoverOld = false;
                            });

                            $(popoverOld).popover('hide');

                        } else {

                            $(popoverOld).one('hidden.bs.popover', function () {

                                $(popoverNew).one('shown.bs.popover', function () {
                                    popoverOld = popoverNew;
                                });

                                $(popoverNew).popover('show');
                            });

                            $(popoverOld).popover('hide');
                        }
                    }
                }

            }

        });

        $(document).on('click', '.popover-item', function () {

            var item = $(this);
            item.closest('.select-mask').find('.mask').text(item.attr('data-label'));
            $(popoverOld).popover('hide');
            popoverOld = false;
            return false;
        });
    },
    tooltip: function () {
        $('[data-toggle="tooltip"]').tooltip();
    },
    isEmpty: function (el) {
        return !$.trim(el.html());
    },
    delay: function () {
        var timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    }(),
    mobileFormat: function () {

        var input = $('.mobileFormat'),
                output = input.next();

        input.intlTelInput({
            nationalMode: true,
            utilsScript: "assets/plugins/intlTelInput/utils.min.js",
            utilsScriptOnLoad: function () {
                if (input.length > 0)
                    input.keyup();
            },
            defaultCountry: input.data('code'),
        });

       
        input.on("keyup change input", function () {
            var intlNumber = input.intlTelInput("getNumber");
            if (intlNumber) {
                output.val(intlNumber);
            }else{
                output.val('');
            }
        });
    }
}