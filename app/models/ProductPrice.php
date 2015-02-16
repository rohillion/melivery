<?php

class ProductPrice extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_price';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('product_id','price','price_before');
    
    public function setCreatedAtAttribute($value) {
        // to Disable created_at
    }

    /**
     * Product relationship
     */
    public function product() {
        return $this->belongsTo('Product', 'product_id');
    }
    
    /**
     * Product relationship
     */
    public function productPriceSize() {
        return $this->hasOne('ProductPriceSize', 'product_price_id');
    }

}
