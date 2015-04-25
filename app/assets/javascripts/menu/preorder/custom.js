/* Preorder Custom.JS */
var custom = {
    init: function () {
        menu.init();
        this.confirmForm();
    },
    confirmForm : function(){
        $(document).on('change', '.custom-pay', function(){
            
            var radio = $(this);

            if(radio.is(':checked'))
                radio.closest('.radio-inline').find('.custom-amount').focus();
        });
        
        $(document).on('focus', '.custom-amount', function(){
            
            var input = $(this);
            
            input.closest('.radio-inline').find('.custom-pay').prop('checked',true);
        });
    }
}