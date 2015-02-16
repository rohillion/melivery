<?php

class ProductPriceSize extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_price_size';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('product_price_id','size_name');
    
    public function setCreatedAtAttribute($value) {
        // to Disable created_at
    }
    
    public function setUpdatedAtAttribute($value) {
        // to Disable updated_at
    }

    /**
     * Product relationship
     */
    public function productPrice() {
        return $this->belongsTo('ProductPrice', 'product_price_id');
    }

}
