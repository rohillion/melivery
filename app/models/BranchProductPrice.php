<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class BranchProductPrice extends Eloquent {

    use SoftDeletingTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'branch_product_price';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('branch_product_id','price','price_before','deleted_at');
    
    public function setCreatedAtAttribute($value) {
        // to Disable created_at
    }

    /**
     * BranchProduct relationship
     */
    public function branchProduct() {
        return $this->belongsTo('BranchProduct', 'branch_product_id');
    }
    
    /**
     * BranchProductPriceSize relationship
     */
    public function size() {
        return $this->hasOne('BranchProductPriceSize', 'branch_product_price_id');
    }

}
