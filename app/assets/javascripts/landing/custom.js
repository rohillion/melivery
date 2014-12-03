/* Home Custom.JS */
var custom = {
    init: function() {
        this.parallax();
        //this.affixAction();
    },
    affixAction: function() {
        
        $('.navbar-affix').on('affix.bs.affix', function() {
            $(this).addClass('width-space').find('.nav').addClass('col-md-offset-1');
            $('#groupsHolder').addClass('top-space');
        }).on('affix-top.bs.affix', function() {
            $(this).removeClass('width-space').find('.nav').removeClass('col-md-offset-1');
            $('#groupsHolder').removeClass('top-space');
        });
    },
    parallax : function(){
        
        $('.cover-container').parallax("50%", -0.5);
    }
}