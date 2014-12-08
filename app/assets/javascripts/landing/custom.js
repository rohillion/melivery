/* Landing Custom.JS */
var custom = {
    init: function() {
        main.popover();
        this.popover();
        this.parallax();
        this.affixTopBar();
        this.scrollTo();
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

        $('#content-header').on('affixed.bs.affix', function() {

            $('body').addClass('skin-red').removeClass('skin-landing');

        }).on('affixed-top.bs.affix', function() {

            $('body').addClass('skin-landing').removeClass('skin-red');
        });
    },
    parallax: function() {

        $('.cover-container').parallax("50%", -0.5);
    },
    popover: function() {

        $("#branch-mask").popover({
            html: true,
            content: $('#branch-list').html(),
            placement: 'bottom',
            trigger: 'manual'
        });

        $("#category-mask").popover({
            html: true,
            content: $('#category-list').html(),
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
    scrollTo: function() {

        var lastId,
                flag = true,
                menuItem,
                offsetTopFix = 140,
                // All list items
                menuItems = $('#category-list').find("a"),
                // Anchors corresponding to menu items
                scrollItems = menuItems.map(function() {
                    var item = $($(this).attr("href"));
                    if (item.length) {
                        return item;
                    }
                });

        $(document).on('click', '.scrollTo', function(e) {
            flag = false;
            e.preventDefault();
            main.scrollTo(this, offsetTopFix, function() {
                flag = true;
            });
        });

        $(window).scroll(function() {
            if (flag) {
                // Get container scroll position
                var fromTop = $(this).scrollTop() - offsetTopFix;

                // Get id of current scroll item
                var cur = scrollItems.map(function() {
                    //console.log($(this).offset().top  - offsetTopFix < fromTop);
                    if ($(this).offset().top - offsetTopFix - 145 < fromTop)
                        return this;
                });
                // Get the id of the current element
                cur = cur[cur.length - 1];

                var id = cur && cur.length ? cur[0].id : "";

                if (lastId !== id) {
                    lastId = id;
                    // Set/remove active class
                    menuItem = menuItems.filter("[href=#" + id + "]");
                    menuItem.closest('.select-mask').find('.mask').text(menuItem.attr('data-label'));
                }
            }
        });
    }
}