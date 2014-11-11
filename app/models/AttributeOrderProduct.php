<?php

class AttributeOrderProduct extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attribute_order_product';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    //protected $fillable = array('product_id','order_id');

    /**
     * Attribute relationship
     */
    public function attributes() {
        return $this->belongsTo('Attribute', 'attribute_id');
    }

}
