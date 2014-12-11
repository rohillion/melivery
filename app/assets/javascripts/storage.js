var storage = {
    data: null,
    init: function() {
        this.data = $.localStorage;
    },
    push: function(key, value) {

        if (this.data.isSet(key)) {

            var array = this.data.get(key);

            array.push(value);

            this.data.set(key, array);
            
            return this.data.get(key);

        }
        
        return false;

    }
}