<?php

class AttributeSubcategory extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attribute_subcategory';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = array('id_attribute','id_subcategory');

}
