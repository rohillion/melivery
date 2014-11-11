<?php

class BranchDealer extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'branch_dealer';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('branch_id','dealer_name','dispatched');

    /**
     * Branch relationship
     */
    public function branch() {
        return $this->belongsTo('Branch', 'branch_id');
    }
    
    /**
     * Order relationship
     */
    public function orders() {
        return $this->belongsToMany('Order', 'branch_dealer_order', 'branch_dealer_id', 'order_id');
    }

}
