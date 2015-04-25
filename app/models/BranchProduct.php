<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class BranchProduct extends Eloquent {
    
    use SoftDeletingTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'branch_product';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('branch_id','product_id','active');

    /**
     * Branch relationship
     */
    public function branch() {
        return $this->belongsTo('Branch', 'branch_id');
    }
    
    /**
     * Branch relationship
     */
    public function product() {
        return $this->belongsTo('Product', 'product_id');
    }
    
    /**
     * BranchProductPrice relationship
     */
    public function prices() {
        return $this->hasMany('BranchProductPrice', 'branch_product_id');
    }

}
