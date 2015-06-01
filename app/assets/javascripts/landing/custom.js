/* Landing Custom.JS */
var custom = {
    adding: false,
    init: function () {
        menu.init();
        this.parallax();
        this.affixTopBar();
        this.scrollTo();
        this.basket();
    },
    affixTopBar: function () {

        $('#content-header,#order-paper').affix({
            offset: {
                top: function () {
                    return $('.parallax-group').height() - 54;
                }
            }
        });

        $('#content-header').on('affixed.bs.affix', function () {

            $('body').addClass('skin-red').removeClass('skin-landing');

        }).on('affixed-top.bs.affix', function () {

            $('body').addClass('skin-landing').removeClass('skin-red');
        });
    },
    parallax: function () {

        $('.cover-container').parallax("50%", -0.5);
    },
    scrollTo: function () {

        var lastId,
                flag = true,
                menuItem,
                offsetTopFix = 140,
                // All list items
                menuItems = $('#category-list').find("a"),
                // Anchors corresponding to menu items
                scrollItems = menuItems.map(function () {
                    var item = $($(this).attr("href"));
                    if (item.length) {
                        return item;
                    }
                });

        $(document).on('click', '.scrollTo', function (e) {

            flag = false;
            e.preventDefault();
            main.scrollTo(this, offsetTopFix, function () {
                flag = true;
            });
        });

        $(window).scroll(function () {
            if (flag) {
                // Get container scroll position
                var fromTop = $(this).scrollTop() - offsetTopFix;

                // Get id of current scroll item
                var cur = scrollItems.map(function () {
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
    },
    popover: function () {
        $(".config-product").popover({
            html: true,
            content: function () {
                return $(this).next().html();
            },
            //placement : 'auto right',
            placement: function () {
                var width = $(window).width();
                if (width > 768){
                    return 'right';
                }else{
                    return 'auto right';
                }
            },
            container: 'body',
            trigger: 'manual'
        });
    },
    basket: function () {
        $(document).on('click', '#order-paper .box-header', function () {
            console.log('pasa');
            $(this).closest('#order-paper').toggleClass('shown');
        });
    }
}