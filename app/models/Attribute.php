<?php

class Attribute extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attribute';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = array('attribute_name','id_attribute_type');

    /**
     * Dish relationship
     */
    public function dishes() {
        return $this->belongsToMany('Dish','attribute_dish','id_attribute','id_dish');
    }
    
    /**
     * Category relationship
     */
    public function categories() {
        return $this->belongsToMany('Category','attribute_category','id_attribute','id_category');
    }
    
    /**
     * Attribute_type relationship
     */
    public function attribute_types() {
        return $this->belongsTo('AttributeType','id_attribute_type');
    }
    
    /**
     * Order relationship
     */
    public function orders() {
        return $this->belongsToMany('Order', 'attribute_order_product','attribute_id','order_product_id')->withPivot('product_id');
    }

}
