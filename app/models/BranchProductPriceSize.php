<?php

class BranchProductPriceSize extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'branch_product_price_size';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('branch_product_price_id','size_name');
    
    public function setCreatedAtAttribute($value) {
        // to Disable created_at
    }
    
    public function setUpdatedAtAttribute($value) {
        // to Disable updated_at
    }

    /**
     * Product relationship
     */
    public function branchProductPrice() {
        return $this->belongsTo('BranchProductPrice', 'branch_product_price_id');
    }

}
