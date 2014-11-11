<?php

class AttributeProduct extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attribute_product';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = array('id_attribute','id_product');

}
