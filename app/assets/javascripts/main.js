var main = {
    init: function() {
        //this.getChannel();
    },
    sendForm: function(target, formData, callback) {
        $.ajax({
            url: target + "?" + formData,
            dataType: "json"
        }).done(function(res) {

            if (typeof callback == 'function')
                callback(res);
        });
    },
    notify: function(res, callback) {

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
            ttl: 3500,
            // callbacks
            onClose: function() {
                if (typeof callback == 'function')
                    callback();
            }
        });

        // show the notification
        notification.show();
    },
    highlight: function(element) {

        element.effect("highlight", {
            color: "#ffff99"
        }, 2000);
    },
    scrollTo: function(element, callback) {

        var container = $('body');

        container.animate({
            scrollTop: element.offset().top
        }, 1000, 'swing', callback);
    },
    calculateElapsedTime: function(date, time, callback) {

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
    calculateRemainingTime: function(date, time, callback) {

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
    doClock: function(elapsedDate, callback) {

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
    doTimer: function(elapsedDate, callback) {

        var orderSeconds = elapsedDate.getSeconds() * 1000,
                currentDate = new Date(),
                remainingSeconds = elapsedDate.getSeconds() * 1000,
                diff = 0,
                decrement = '0.0';

        function instance()
        {

            if (remainingSeconds <= 100) {
                elapsedDate.setMinutes(elapsedDate.getMinutes()-1);
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

    }
}