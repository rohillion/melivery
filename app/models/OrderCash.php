<?php

class OrderCash extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_cash';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('order_id', 'total', 'paycash', 'change');

    public function setCreatedAtAttribute($value) {
        // to Disable created_at
    }
    
    public function setUpdatedAtAttribute($value) {
        // to Disable updated_at
    }

    /**
     * Order relationship
     */
    public function order() {
        return $this->belongsTo('Order', 'order_id');
    }

}
