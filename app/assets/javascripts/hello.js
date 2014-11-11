var hello = {
    init: function() {
        //this.searchByPosition();
        this.skipPosition();
    },
    searchByPosition: function() {
        $('#search_food').on('click', function() {

            main.sendForm('/position', $('.position,.address').serialize(), function(res) {
                if(res.status){
                    $('#hello-wrapper').removeClass('show');
                }else{
                    alert('Por favor ingrese una direccion');
                }
                    
            });

            return false;
        });
    },
    skipPosition: function() {
        
        $('#skip_step').on('click', function() {

            $('#hello-wrapper').removeClass('show');

            return false;
        });
    }
}